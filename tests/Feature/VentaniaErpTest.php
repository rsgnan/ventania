<?php

namespace Tests\Feature;

use App\Enums\PaymentMethod;
use App\Enums\ProductType;
use App\Enums\SaleStatus;
use App\Enums\StockMovementType;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\User;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VentaniaErpTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_main_menus_and_settings_pages_load_successfully_for_admin(): void
    {
        $this->seed();
        $admin = User::where('email', 'admin@ventania.com')->firstOrFail();

        $this->actingAs($admin)->get('/dashboard')->assertOk();
        $this->actingAs($admin)->get('/sales')->assertOk();
        $this->actingAs($admin)->get('/products')->assertOk();
        $this->actingAs($admin)->get('/stock-movements')->assertOk();
        $this->actingAs($admin)->get('/users')->assertOk();
        $this->actingAs($admin)->get('/settings/profile')->assertOk();
        $this->actingAs($admin)->get('/settings/password')->assertOk();
        $this->actingAs($admin)->get('/settings/appearance')->assertOk();
    }

    public function test_inactive_user_cannot_login_and_active_user_is_revoked_when_deactivated(): void
    {
        $this->seed();

        // 1. Tenta logar com usuário inativo
        $this->post('/login', [
            'email' => 'inativo@ventania.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();

        // 2. Loga com vendedor ativo e depois o inativa com a sessão aberta
        $vendedor = User::where('email', 'vendedor@ventania.com')->firstOrFail();
        $this->actingAs($vendedor)->get('/dashboard')->assertOk();

        $vendedor->update(['is_active' => false]);

        $this->actingAs($vendedor->fresh())
            ->get('/dashboard')
            ->assertRedirect('/login');
    }

    public function test_common_user_cannot_access_users_management_and_admin_cannot_self_lockout(): void
    {
        $this->seed();
        $vendedor = User::where('email', 'vendedor@ventania.com')->firstOrFail();
        $admin = User::where('email', 'admin@ventania.com')->firstOrFail();

        // Vendedor comum recebe 403 Forbidden em /users
        $this->actingAs($vendedor)->get('/users')->assertForbidden();

        // Admin tenta desativar a própria conta -> deve falhar na validação
        $this->actingAs($admin)->put("/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => UserRole::Admin->value,
            'is_active' => false,
        ])->assertSessionHasErrors('role');

        $this->assertTrue($admin->fresh()->is_active);
    }

    public function test_products_kits_and_stock_movements_calculate_and_protect_inventory(): void
    {
        $this->seed();
        $admin = User::where('email', 'admin@ventania.com')->firstOrFail();
        $category = Category::firstOrFail();

        // 1. Cria um novo produto simples (deve nascer com estoque 0 e SKU automático)
        $this->actingAs($admin)->post('/products', [
            'category_id' => $category->id,
            'name' => 'Vela de Sete Dias Branca',
            'type' => ProductType::Simple->value,
            'price' => 15.00,
            'min_stock' => 5,
            'is_active' => true,
        ])->assertSessionHasNoErrors();

        $vela = Product::where('name', 'Vela de Sete Dias Branca')->firstOrFail();
        $this->assertSame(0, $vela->stock_quantity);
        $this->assertSame('PRO-00003', $vela->sku);

        // 2. Tenta dar saída sem ter estoque -> deve barrar e manter saldo 0
        $this->actingAs($admin)->post('/stock-movements', [
            'type' => StockMovementType::Out->value,
            'notes' => 'Tentativa inválida',
            'items' => [
                ['product_id' => $vela->id, 'quantity' => 5],
            ],
        ])->assertSessionHasErrors('items');

        $this->assertSame(0, $vela->fresh()->stock_quantity);

        // 3. Dá entrada de 10 unidades da Vela e confere se o saldo subiu para 10
        $this->actingAs($admin)->post('/stock-movements', [
            'type' => StockMovementType::In->value,
            'notes' => 'Entrada de teste',
            'items' => [
                ['product_id' => $vela->id, 'quantity' => 10],
            ],
        ])->assertSessionHasNoErrors();

        $this->assertSame(10, $vela->fresh()->stock_quantity);
    }

    public function test_sale_lifecycle_deducts_simple_and_kit_stock_on_completion_and_locks_immutability(): void
    {
        $this->seed();
        $admin = User::where('email', 'admin@ventania.com')->firstOrFail();

        $incenso = Product::where('sku', 'PRO-00001')->firstOrFail();
        $sabonete = Product::where('sku', 'PRO-00002')->firstOrFail();
        $kit = Product::where('sku', 'KIT-00001')->firstOrFail();

        $initialIncensoStock = $incenso->stock_quantity; // 27 após o seeder
        $initialSaboneteStock = $sabonete->stock_quantity; // 14 após o seeder

        // 1. Cria venda Pendente com 2x Incenso avulso + 1x Kit (que leva +2 Incensos e +1 Sabonete)
        $this->actingAs($admin)->post('/sales', [
            'customer_name' => 'Cliente Teste Automatizado',
            'payment_method' => PaymentMethod::Pix->value,
            'discount' => 3.00,
            'notes' => 'Venda via PHPUnit',
            'items' => [
                ['product_id' => $incenso->id, 'quantity' => 2],
                ['product_id' => $kit->id, 'quantity' => 1],
            ],
        ])->assertSessionHasNoErrors();

        $sale = Sale::where('customer_name', 'Cliente Teste Automatizado')->firstOrFail();
        $this->assertSame(SaleStatus::Pending, $sale->status);
        // (2 * 8.50) + (1 * 26.00) - 3.00 = 40.00
        $this->assertEquals(40.00, (float) $sale->total_amount);

        // Enquanto Pendente, o estoque físico NÃO pode ter mudado
        $this->assertSame($initialIncensoStock, $incenso->fresh()->stock_quantity);

        // 2. Conclui a venda -> deve baixar 4 Incensos (2 avulsos + 2 do Kit) e 1 Sabonete (do Kit)
        $this->actingAs($admin)->put("/sales/{$sale->id}", [
            'customer_name' => 'Cliente Teste Automatizado',
            'status' => SaleStatus::Completed->value,
            'payment_method' => PaymentMethod::Pix->value,
            'discount' => 3.00,
            'notes' => 'Venda concluída',
            'items' => [
                ['product_id' => $incenso->id, 'quantity' => 2],
                ['product_id' => $kit->id, 'quantity' => 1],
            ],
        ])->assertSessionHasNoErrors();

        $this->assertSame($initialIncensoStock - 4, $incenso->fresh()->stock_quantity);
        $this->assertSame($initialSaboneteStock - 1, $sabonete->fresh()->stock_quantity);

        // 3. Tenta editar a venda já Concluída -> deve bloquear por imutabilidade
        $this->actingAs($admin)->put("/sales/{$sale->id}", [
            'customer_name' => 'Tentativa de Adulteração',
            'status' => SaleStatus::Cancelled->value,
            'payment_method' => PaymentMethod::Cash->value,
            'discount' => 0,
            'items' => [
                ['product_id' => $incenso->id, 'quantity' => 1],
            ],
        ])->assertSessionHasErrors('status');

        $this->assertSame(SaleStatus::Completed, $sale->fresh()->status);
    }

    public function test_domain_models_strictly_forbid_hard_deletion(): void
    {
        $this->seed();

        $this->expectException(DomainException::class);
        User::first()->delete();
    }
}
