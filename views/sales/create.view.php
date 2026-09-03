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
                    Nova Venda
                </h1>

                <p class="page-description">
                    Busque e adicione os produtos para registrar uma nova venda.
                </p>

            </div>
        </div>
    </div>

    <div class="form-panel form-panel-wide">

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

            <!-- Itens da venda -->
            <div class="sale-main">

                <div class="autocomplete">
                    <?php echo icon('search'); ?>

                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Buscar produto pelo nome..."
                        autocomplete="off"
                        aria-label="Buscar produto">

                    <div
                        class="autocomplete-results"
                        id="autocompleteResults">
                    </div>
                </div>

                <div class="card sale-items-card">

                    <div class="card-header">
                        <div class="card-header-content">

                            <div class="card-title">
                                Itens adicionados
                            </div>

                            <div class="card-subtitle">
                                Os produtos adicionados à venda aparecerão aqui.
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
                            <tbody id="itemsBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resumo da venda -->
            <aside class="sale-summary">

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
                        value="<?php echo e($_POST['customer_name'] ?? ''); ?>"
                        placeholder="Ex: Maria da Silva">
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
                        value="<?php echo e($_POST['discount_amount'] ?? 0); ?>">
                </div>

                <div class="field">
                    <label class="form-label" for="statusSelect">
                        Status
                    </label>

                    <select
                        class="form-select"
                        id="statusSelect"
                        name="status">

                        <option value="pending" <?php echo e($_POST['status'] ?? 'pending') === 'pending' ? 'selected' : ''; ?>>
                            Pendente
                        </option>

                        <option value="completed" <?php echo e($_POST['status'] ?? 'pending') === 'completed' ? 'selected' : ''; ?>>
                            Concluída
                        </option>

                    </select>
                </div>

                <div class="sale-summary-divider"></div>

                <div class="sale-summary-info">

                    <div class="sale-summary-row">
                        <span>Itens distintos</span>
                        <strong id="sumDistinct">0</strong>
                    </div>

                    <div class="sale-summary-row">
                        <span>Quantidade total</span>
                        <strong id="sumQty">0</strong>
                    </div>

                    <div class="sale-summary-row">
                        <span>Subtotal</span>
                        <strong id="sumSubtotal">R$ 0,00</strong>
                    </div>
                </div>

                <div class="sale-summary-total">
                    <span>Total</span>

                    <strong id="sumTotal">
                        R$ 0,00
                    </strong>
                </div>

                <input
                    type="hidden"
                    name="items"
                    id="saleItems">

                <button
                    type="submit"
                    class="btn btn-primary">
                    Finalizar venda
                </button>

            </aside>

        </div>

    </div>

</form>

<script>
    const products = <?php echo json_encode($products); ?>;
    const existingSaleItems = [];
</script>

<script src="assets/js/sales/sales.js"></script>