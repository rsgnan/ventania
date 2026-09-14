<!-- Vendas -->

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Vendas
        </h1>

        <p class="page-description">
            <?php echo e($totalSales); ?>
            <?php echo $totalSales === 1 ? 'venda encontrada' : 'vendas encontradas'; ?>
        </p>
    </div>

    <div class="page-header-actions">
        <a class="btn btn-primary" href="?route=sales/create">

            <?php echo icon('plus'); ?>

            Nova Venda
        </a>
    </div>
</div>

<div class="filters">

    <div id="sales-tabs">
        <?php require __DIR__ . '/_tabs.view.php'; ?>
    </div>

    <form
        class="filters-search"
        method="GET">

        <input
            type="hidden"
            name="route"
            value="sales/index">

        <?php if ($status !== null): ?>
            <input
                type="hidden"
                name="status"
                value="<?php echo e($status); ?>">
        <?php endif; ?>

        <?php echo icon('search'); ?>

        <input
            class="form-input"
            type="search"
            id="sale-search"
            name="search"
            value="<?php echo e($search); ?>"
            placeholder="Buscar por cliente"
            aria-label="Buscar vendas">
    </form>
</div>

<?php require __DIR__ . '/_results.view.php'; ?>