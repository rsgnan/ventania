<?php

$selectedStatus = $_POST['status'] ?? $sale->status;

$existingSaleItems = $items;

if (!empty($_POST['items'])) {
    $decodedItems = json_decode($_POST['items'], true);

    if (is_array($decodedItems)) {
        $existingSaleItems = $decodedItems;
    }
}
?>

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

            <div class="items-manager">

                <div class="card items-selector">

                    <div class="card-header">
                        <div class="card-header-content">

                            <div class="card-title">
                                Adicionar produtos
                            </div>

                            <div class="card-subtitle">
                                Busque pelo nome e selecione os produtos da venda.
                            </div>

                        </div>
                    </div>

                    <div class="selector-content">

                        <div class="item-search">

                            <?php echo icon('search'); ?>

                            <input
                                class="form-input"
                                type="text"
                                id="searchInput"
                                placeholder="Buscar produto pelo nome..."
                                autocomplete="off"
                                aria-label="Buscar produto">

                            <div
                                class="search-results"
                                id="productSearchResults">
                            </div>

                        </div>

                    </div>

                </div>

                <div class="card items-list">

                    <div class="card-header">
                        <div class="card-header-content">

                            <div class="card-title">
                                Itens da venda
                            </div>

                            <div class="card-subtitle">
                                Altere os produtos e quantidades desta venda.
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

            <aside class="sale-summary">

                <h2>
                    Resumo da venda
                </h2>

                <div class="field">
                    <label
                        class="form-label"
                        for="customerName">

                        Nome do cliente
                    </label>

                    <input
                        class="form-input"
                        type="text"
                        id="customerName"
                        name="customer_name"
                        value="<?php echo e($_POST['customer_name'] ?? $sale->customer_name); ?>"
                        placeholder="Ex: Maria da Silva">
                </div>

                <div class="field">
                    <label
                        class="form-label"
                        for="discountAmount">

                        Desconto (R$)
                    </label>

                    <input
                        class="form-input"
                        type="number"
                        id="discountAmount"
                        name="discount_amount"
                        min="0"
                        step="0.01"
                        value="<?php echo e($_POST['discount_amount'] ?? $sale->discount_amount); ?>">
                </div>

                <fieldset class="field sale-status-field">

                    <legend class="form-label">
                        Status
                    </legend>

                    <div class="sale-status-options">

                        <label class="sale-status-option sale-status-pending">

                            <input
                                type="radio"
                                name="status"
                                value="pending"
                                <?php echo $selectedStatus === 'pending' ? 'checked' : ''; ?>>

                            <span class="sale-status-content">
                                <?php echo icon('clock'); ?>

                                <span>
                                    Pendente
                                </span>
                            </span>

                        </label>

                        <label class="sale-status-option sale-status-completed">

                            <input
                                type="radio"
                                name="status"
                                value="completed"
                                <?php echo $selectedStatus === 'completed' ? 'checked' : ''; ?>>

                            <span class="sale-status-content">
                                <?php echo icon('check'); ?>

                                <span>
                                    Concluída
                                </span>
                            </span>

                        </label>

                        <label class="sale-status-option sale-status-cancelled">

                            <input
                                type="radio"
                                name="status"
                                value="cancelled"
                                <?php echo $selectedStatus === 'cancelled' ? 'checked' : ''; ?>>

                            <span class="sale-status-content">
                                <?php echo icon('x'); ?>

                                <span>
                                    Cancelada
                                </span>
                            </span>

                        </label>

                    </div>

                </fieldset>

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
                    id="saleItems"
                    value="<?php echo e($_POST['items'] ?? ''); ?>">

                <button
                    type="submit"
                    class="btn btn-primary">
                    Salvar alterações
                </button>

            </aside>

        </div>

    </div>

</form>

<script>
    const existingSaleItems = <?php echo json_encode(
                                    $existingSaleItems,
                                    JSON_UNESCAPED_UNICODE
                                        | JSON_UNESCAPED_SLASHES
                                        | JSON_HEX_TAG
                                        | JSON_HEX_AMP
                                        | JSON_HEX_APOS
                                        | JSON_HEX_QUOT
                                ); ?>;

    const originalSaleItems = <?php echo json_encode(
                                    $items,
                                    JSON_UNESCAPED_UNICODE
                                        | JSON_UNESCAPED_SLASHES
                                        | JSON_HEX_TAG
                                        | JSON_HEX_AMP
                                        | JSON_HEX_APOS
                                        | JSON_HEX_QUOT
                                ); ?>;
</script>

<script src="assets/js/sales.js"></script>