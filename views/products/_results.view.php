<div id="products-results">

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
                                <?php if ($search !== '' && $selectedCategory !== null): ?>

                                    Nenhum produto encontrado nesta categoria.

                                    <a
                                        class="table-empty-link"
                                        href="?<?php echo e(http_build_query([
                                                    'route' => 'products/index',
                                                    'search' => $search
                                                ])); ?>">
                                        Buscar em todas as categorias.
                                    </a>
                                <?php else: ?>

                                    Nenhum produto encontrado.

                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPages > 1): ?>
        <nav class="pagination" aria-label="Paginação">

            <?php
            $baseParams = [
                'route' => 'products/index'
            ];

            if ($search !== '') {
                $baseParams['search'] = $search;
            }

            if ($selectedCategory !== null) {
                $baseParams['category'] = $selectedCategory;
            }
            ?>

            <?php if ($currentPage > 1): ?>
                <a
                    class="pagination-link"
                    href="?<?php echo e(http_build_query(
                                $baseParams + [
                                    'page' => $currentPage - 1
                                ]
                            )); ?>">
                    Anterior
                </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a
                    class="pagination-link <?php echo $i === $currentPage ? 'active' : ''; ?>"
                    href="?<?php echo e(http_build_query(
                                $baseParams + [
                                    'page' => $i
                                ]
                            )); ?>">

                    <?php echo e($i); ?>
                </a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a
                    class="pagination-link"
                    href="?<?php echo e(http_build_query(
                                $baseParams + [
                                    'page' => $currentPage + 1
                                ]
                            )); ?>">
                    Próxima
                </a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</div>