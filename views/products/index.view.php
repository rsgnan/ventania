<!-- Produtos -->

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Produtos
        </h1>

         <p class="page-description">
            <?php echo e($totalProducts); ?>
            <?php echo $totalProducts === 1 ? 'produto encontrado' : 'produtos encontrados'; ?>
        </p>
    </div>

    <div class="page-header-actions">
        <a class="btn btn-primary" href="?route=products/create">

            <?php echo icon('plus'); ?>

            Novo Produto
        </a>
    </div>
</div>

<div class="filters">

    <div id="products-tabs">
        <?php require __DIR__ . '/_tabs.view.php'; ?>
    </div>

    <form
        class="filters-search"
        method="GET">

        <input
            type="hidden"
            name="route"
            value="products/index">

        <?php if ($selectedCategory !== null): ?>
            <input
                type="hidden"
                name="category"
                value="<?php echo e($selectedCategory); ?>">
        <?php endif; ?>

        <?php echo icon('search'); ?>

        <input
            class="form-input"
            type="search"
            id="product-search"
            name="search"
            value="<?php echo e($search); ?>"
            placeholder="Buscar por nome ou tag"
            aria-label="Buscar produtos">
    </form>
</div>

<?php require __DIR__ . '/_results.view.php'; ?>