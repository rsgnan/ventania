<?php

use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a estrutura Master-Detail de Vendas (cabeçalho) e Itens da Venda (produtos simples ou kits).
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            // Operador/Vendedor que registrou a venda
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('customer_name');
            $table->string('status', 20)->default(SaleStatus::Pending->value);

            $table->string('payment_method', 20)->default(PaymentMethod::Pix->value);
            $table->decimal('discount', 10, 2)->default(0);

            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('notes')->nullable();

            // Timestamps do ciclo de vida da venda
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            // Índices compostos para filtros por status e ranking de vendas por vendedor
            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_id')
                ->constrained('sales')
                ->cascadeOnDelete();

            // Pode apontar tanto para um Produto Físico (PRO) quanto para um Kit (KIT)
            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete();

            $table->unsignedInteger('quantity');

            // Fotografia financeira (Price Snapshot) congelada no momento da venda
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);

            $table->unique(['sale_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};
