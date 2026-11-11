<?php

use App\Enums\ProductType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de produtos e a tabela pivô de composição de kits.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->string('name');
            $table->string('sku', 50)->nullable()->unique();
            $table->string('type', 20)->default(ProductType::Simple->value);

            // NUMERIC(10, 2) no PostgreSQL para precisão financeira exata
            $table->decimal('price', 10, 2);

            // Todo produto simples nasce com estoque 0 (só muda via Movimentação). Kits calculam dinamicamente.
            $table->integer('stock_quantity')->default(0);

            // Proibido deletar produtos: controle apenas via ativação/inativação
            $table->boolean('is_active')->default(true);

            $table->index(['is_active', 'type', 'category_id']);
            $table->timestamps();
        });

        // Tabela pivô de autorrelacionamento: define quais Produtos Simples compõem cada Kit
        Schema::create('product_kit_items', function (Blueprint $table) {
            $table->id();

            // O produto do tipo 'kit'
            $table->foreignId('kit_product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // O produto do tipo 'simple' que faz parte da receita do kit
            $table->foreignId('component_product_id')
                ->constrained('products')
                ->restrictOnDelete();

            // Quantidade necessária deste componente para montar 1 unidade do kit
            $table->unsignedInteger('quantity');

            // Impede que o mesmo componente seja duplicado dentro do mesmo kit
            $table->unique(['kit_product_id', 'component_product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_kit_items');
        Schema::dropIfExists('products');
    }
};
