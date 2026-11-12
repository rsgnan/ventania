<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a estrutura Master-Detail de movimentações de estoque (Cabeçalho + Itens).
     */
    public function up(): void
    {
        // 1. Cabeçalho da Movimentação
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // 'in' (Entrada) ou 'out' (Saída) via Enum StockMovementType
            $table->string('type', 10);

            // Motivo ou referência da movimentação (ex: "Compra Fornecedor", "Ajuste de inventário", "Venda #10")
            $table->string('notes')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->index(['type', 'created_at']);
        });

        // 2. Itens da Movimentação (1 ou mais produtos simples por operação)
        Schema::create('stock_movement_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stock_movement_id')
                ->constrained('stock_movements')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete();

            $table->unsignedInteger('quantity');

            // Histórico Kardex: registra o saldo exato antes e depois da operação
            $table->integer('previous_stock');
            $table->integer('new_stock');

            $table->unique(['stock_movement_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movement_items');
        Schema::dropIfExists('stock_movements');
    }
};
