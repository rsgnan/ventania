<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;
use App\Enums\StockMovementType;
use App\Enums\ProductType;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\SaleService;
use App\Services\StockMovementService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Popula o banco de dados da aplicação com dados iniciais (Seed).
     */
    public function run(): void
    {
        // 1. Conta fixa de Administrador (Idempotente via firstOrCreate pelo e-mail único)
        User::firstOrCreate(
            ['email' => 'admin@ventania.com'],
            [
                'name' => 'Administrador Ventania',
                'password' => 'password', // O cast 'hashed' no Model User fará o hash automaticamente
                'role' => UserRole::Admin,
                'is_active' => true,
            ]
        );

        // 2. Conta fixa de Usuário Comum (Vendedor/Operador ativo)
        User::firstOrCreate(
            ['email' => 'vendedor@ventania.com'],
            [
                'name' => 'Vendedor Padrão',
                'password' => 'password',
                'role' => UserRole::User,
                'is_active' => true,
            ]
        );

        // 3. Conta fixa de Usuário Inativo (Para testar o bloqueio de autenticação)
        User::firstOrCreate(
            ['email' => 'inativo@ventania.com'],
            [
                'name' => 'Funcionário Desligado',
                'password' => 'password',
                'role' => UserRole::User,
                'is_active' => false,
            ]
        );

        // 4. Gera usuários adicionais apenas se o banco tiver poucos registros (mantém idempotência de volume)
        if (User::count() < 15) {
            // Cria 10 operadores ativos aleatórios
            User::factory(10)->create();

            // Cria 2 operadores inativos aleatórios para testar filtros na listagem
            User::factory(2)->inactive()->create();
        }

        // 5. Categorias fixas do catálogo Ventania (Idempotente via slug único)
        foreach (['Vestuários', 'Pedras', 'Guias', 'Ervas', 'Sabonetes', 'Perfumes', 'Incensos', 'Acessórios', 'Amigurumis'] as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }

        // 6. Produtos iniciais e Kit de exemplo do catálogo Ventania
        $incensosCategory = Category::where('slug', 'incensos')->first();
        $sabonetesCategory = Category::where('slug', 'sabonetes')->first();

        $incenso = Product::firstOrCreate(
            ['sku' => 'PRO-00001'],
            [
                'category_id' => $incensosCategory->id,
                'name' => 'Incenso Natural de Arruda',
                'type' => ProductType::Simple,
                'price' => 8.50,
                'min_stock' => 5,
                'description' => 'Incenso artesanal para defumação e limpeza de ambientes.',
                'is_active' => true,
            ],
        );

        $sabonete = Product::firstOrCreate(
            ['sku' => 'PRO-00002'],
            [
                'category_id' => $sabonetesCategory->id,
                'name' => 'Sabonete Artesanal Sete Ervas',
                'type' => ProductType::Simple,
                'price' => 12.00,
                'min_stock' => 5,
                'description' => 'Sabonete glicerinado com extrato natural das sete ervas.',
                'is_active' => true,
            ],
        );

        $kit = Product::firstOrCreate(
            ['sku' => 'KIT-00001'],
            [
                'category_id' => $incensosCategory->id,
                'name' => 'Kit Limpeza Energética (2x Incenso + 1x Sabonete)',
                'type' => ProductType::Kit,
                'price' => 26.00,
                'min_stock' => 3,
                'description' => 'Kit completo pronto para banho de ervas e defumação.',
                'is_active' => true,
            ],
        );

        $kit->components()->sync([
            $incenso->id => ['quantity' => 2],
            $sabonete->id => ['quantity' => 1],
        ]);

        // 7. Movimentação inicial de estoque e vendas de exemplo (Idempotente)
        if (StockMovement::count() === 0) {
            $admin = User::where('email', 'admin@ventania.com')->first();

            app(StockMovementService::class)->record(
                userId: $admin->id,
                type: StockMovementType::In,
                items: [
                    ['product_id' => $incenso->id, 'quantity' => 30],
                    ['product_id' => $sabonete->id, 'quantity' => 15],
                ],
                notes: 'Compra de fornecedor - Lote Inicial NF #001',
            );

            $saleService = app(SaleService::class);

            // Venda #1: Concluída (baixa automática no estoque)
            $completedSale = $saleService->create($admin->id, [
                'customer_name' => 'Maria Clara Oliveira',
                'payment_method' => PaymentMethod::Pix->value,
                'discount' => 2.50,
                'notes' => 'Cliente recorrente da loja física',
                'items' => [
                    ['product_id' => $incenso->id, 'quantity' => 1],
                    ['product_id' => $kit->id, 'quantity' => 1],
                ],
            ]);

            $saleService->update($completedSale, [
                'customer_name' => 'Maria Clara Oliveira',
                'status' => SaleStatus::Completed->value,
                'payment_method' => PaymentMethod::Pix->value,
                'discount' => 2.50,
                'notes' => 'Cliente recorrente da loja física',
                'items' => [
                    ['product_id' => $incenso->id, 'quantity' => 1],
                    ['product_id' => $kit->id, 'quantity' => 1],
                ],
            ], $admin->id);

            // Venda #2: Pendente (em aberto para testar edição no painel)
            $saleService->create($admin->id, [
                'customer_name' => 'Consumidor Final',
                'payment_method' => PaymentMethod::CreditCard->value,
                'discount' => 0,
                'notes' => 'Aguardando confirmação no balcão',
                'items' => [
                    ['product_id' => $sabonete->id, 'quantity' => 2],
                ],
            ]);
        }
    }
}
