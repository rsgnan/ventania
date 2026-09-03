<form method="POST">
    <?php echo csrf_field(); ?>

    <div class="page-header">
        <div class="page-header-title">
            <a
                class="btn btn-ghost btn-icon"
                href="?route=sales/index"
                title="Voltar para Vendas"
                aria-label="Voltar para Vendas">

                <?php echo icon('arrow-left'); ?>

            </a>
            <div class="page-header-left">
                <h1 class="page-title">
                    Editar Venda
                </h1>

                <p class="page-description">
                    Altere os produtos e informações da venda.
                </p>
            </div>
        </div>
    </div>

    <div class="form-panel">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">

                <?php echo icon('alert-circle'); ?>

                <ul class="alert-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="sale-layout">

            <!-- Área principal -->
            <div class="sale-main">
                <div class="autocomplete">
                    <?php echo icon('search'); ?>

                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Buscar produto pelo nome..."
                        autocomplete="off">

                    <div
                        class="autocomplete-results"
                        id="autocompleteResults">
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">
                                Itens da venda
                            </div>

                            <div class="card-subtitle">
                                Alterar os produtos e quantidades desta venda.
                            </div>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Qtd.</th>
                                    <th>Valor unitário</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody id="itemsBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resumo -->
            <div class="summary">
                <h2>
                    Resumo da venda
                </h2>

                <div class="field">
                    <label class="form-label" for="customerName">
                        Nome do Cliente
                    </label>

                    <input
                        class="form-input"
                        type="text"
                        id="customerName"
                        name="customer_name"
                        value="<?php echo e($sale->customer_name); ?>" placeholder="Ex: Maria da Silva">
                </div>

                <div class="field">
                    <label class="form-label" for="discountAmount">
                        Desconto (R$)
                    </label>
                    <input
                        class="form-input"
                        type="number"
                        id="discountAmount"
                        name="discount_amount"
                        min="0"
                        step="0.01"
                        value="<?php echo e($sale->discount_amount); ?>">
                </div>

                <div class="field">
                    <label class="form-label" for="statusSelect">
                        Status
                    </label>
                    <select
                        class="form-select"
                        id="statusSelect"
                        name="status">

                        <option
                            value="pending" <?php echo $sale->status === 'pending' ? 'selected' : ''; ?>>
                            Pendente
                        </option>

                        <option
                            value="completed" <?php echo $sale->status === 'completed' ? 'selected' : ''; ?>>
                            Concluída
                        </option>

                        <option
                            value="cancelled" <?php echo $sale->status === 'cancelled' ? 'selected' : ''; ?>>
                            Cancelada
                        </option>
                    </select>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-row">
                    <span>Itens distintos</span>
                    <span id="sumDistinct">0</span>
                </div>

                <div class="summary-row">
                    <span>Quantidade total</span>
                    <span id="sumQty">0</span>
                </div>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="sumSubtotal">R$ 0,00</span>
                </div>

                <div class="summary-row">
                    <span>Total</span>
                    <span id="sumTotal">R$ 0,00</span>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary">
                    Salvar Alterações
                </button>

                <input
                    type="hidden"
                    name="items"
                    id="saleItems">
            </div>
        </div>
    </div>
</form>
<script>
    const products = <?php echo json_encode($products); ?>;
    const existingSaleItems = <?php echo json_encode($items); ?>;
</script>
<script src="assets/js/sales/sales.js"></script>