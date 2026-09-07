<!-- Produtos -->

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Produtos
        </h1>

        <p class="page-description">
            <?php echo e($totalProducts); ?> produtos encontrados
        </p>
    </div>

    <div class="page-header-actions">
        <a class="btn btn-primary" href="?route=products/create">

            <?php echo icon('plus'); ?>

            Novo Produto
        </a>
    </div>
</div>

<div class="table-toolbar">
    <div class="table-search">
        <?php echo icon('search'); ?>

        <input
            class="form-input"
            type="search"
            id="product-search"
            name="search"
            value="<?php echo e($search); ?>"
            placeholder="Buscar produto">
    </div>
</div>

<div class="tabs">
    <a
        class="tab <?php echo $selectedCategory === null ? 'active' : ''; ?>"
        href="?route=products/index">
        Todos
    </a>

    <?php foreach ($categories as $category): ?>
        <a
            class="tab <?php echo $selectedCategory === $category->id
                            ? 'active'
                            : ''; ?>"
            href="?route=products/index&category=<?php echo e($category->id); ?>">
            <?php echo e($category->name); ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="card">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Estoque</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <strong>
                                    <?php echo e($product->name); ?>
                                </strong>
                            </td>

                            <td>
                                <?php echo e($product->category_name); ?>
                            </td>

                            <td>
                                <?php echo e($product->stock); ?>
                            </td>

                            <td>
                                R$ <?php echo number_format((float) $product->price, 2, ',', '.'); ?>
                            </td>

                            <td>
                                <div class="table-actions">
                                    <a
                                        class="btn btn-ghost btn-sm"
                                        href="?route=products/edit&id=<?php echo e($product->id); ?>">
                                        Editar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="table-empty">
                            Nenhum produto encontrado.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($totalPages > 1): ?>
    <nav class="pagination" aria-label="Paginação de produtos">
        <?php if ($currentPage > 1): ?>
            <?php
            $previousParams = [
                'route' => 'products/index',
                'page' => $currentPage - 1
            ];

            if ($selectedCategory !== null) {
                $previousParams['category'] = $selectedCategory;
            }

            if ($search !== '') {
                $previousParams['search'] = $search;
            }
            ?>

            <a
                class="pagination-link"
                href="?<?php echo e(http_build_query($previousParams)); ?>">
                Anterior
            </a>
        <?php endif; ?>

        <?php for ($page = 1; $page <= $totalPages; $page++): ?>
            <?php
            $pageParams = [
                'route' => 'products/index',
                'page' => $page
            ];

            if ($selectedCategory !== null) {
                $pageParams['category'] = $selectedCategory;
            }

            if ($search !== '') {
                $pageParams['search'] = $search;
            }
            ?>

            <a
                class="pagination-link <?php echo $page === $currentPage ? 'active' : ''; ?>"
                href="?<?php echo e(http_build_query($pageParams)); ?>">
                <?php echo e($page); ?>
            </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>

            <?php
            $nextParams = [
                'route' => 'products/index',
                'page' => $currentPage + 1
            ];

            if ($selectedCategory !== null) {
                $nextParams['category'] = $selectedCategory;
            }

            if ($search !== '') {
                $nextParams['search'] = $search;
            }
            ?>

            <a
                class="pagination-link"
                href="?<?php echo e(http_build_query($nextParams)); ?>">
                Próxima
            </a>
        <?php endif; ?>
    </nav>
<?php endif; ?>