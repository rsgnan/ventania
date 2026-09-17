<div id="sales-results">

    <div class="card">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Itens</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($sales)): ?>
                        <?php foreach ($sales as $sale): ?>
                            <tr>
                                <td>
                                    <strong>
                                        <?php echo e($sale->customer_name); ?>
                                    </strong>
                                </td>

                                <td>
                                    <?php echo e($sale->items_quantity); ?>
                                    <?php echo $sale->items_quantity === 1 ? 'item' : 'itens'; ?>
                                </td>

                                <td>
                                    <strong>
                                        R$ <?php echo e(
                                                number_format(
                                                    (float) $sale->total_amount,
                                                    2,
                                                    ',',
                                                    '.'
                                                )
                                            ); ?>
                                    </strong>
                                </td>

                                <td>
                                    <?php if ($sale->status === 'pending'): ?>
                                        <span class="badge badge-warning">
                                            Pendente
                                        </span>
                                    <?php elseif ($sale->status === 'completed'): ?>
                                        <span class="badge badge-success">
                                            Concluída
                                        </span>
                                    <?php elseif ($sale->status === 'cancelled'): ?>
                                        <span class="badge badge-danger">
                                            Cancelada
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php echo e(
                                        date(
                                            'd/m/Y H:i',
                                            strtotime($sale->created_at)
                                        )
                                    );
                                    ?>
                                </td>

                                <td>
                                    <div class="table-actions">
                                        <a
                                            class="btn btn-ghost btn-sm"
                                            href="?route=sales/show&id=<?php echo e($sale->id); ?>">
                                            Ver
                                        </a>

                                        <?php if ($sale->status !== 'cancelled'): ?>
                                            <a
                                                class="btn btn-ghost btn-sm"
                                                href="?route=sales/edit&id=<?php echo e($sale->id); ?>">
                                                Editar
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="table-empty">
                                <?php if ($search !== '' && $status !== null): ?>

                                    Nenhuma venda encontrada neste status.

                                    <a
                                        class="table-empty-link"
                                        href="?<?php echo e(http_build_query([
                                                    'route' => 'sales/index',
                                                    'search' => $search
                                                ])); ?>">
                                        Buscar em todas as vendas.
                                    </a>
                                <?php else: ?>

                                    Nenhuma venda encontrada.

                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($totalPages > 1): ?>
        <?php
        $paginationParams = [
            'route' => 'sales/index'
        ];

        if ($search !== '') {
            $paginationParams['search'] = $search;
        }

        if ($status !== null) {
            $paginationParams['status'] = $status;
        }

        $startPage = max(1, $page - 2);
        $endPage = min($totalPages, $page + 2);
        ?>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <?php
                $previousParams = $paginationParams;
                $previousParams['page'] = $page - 1;
                ?>

                <a
                    class="pagination-link"
                    href="?<?php echo e(http_build_query($previousParams)); ?>">
                    Anterior
                </a>
            <?php endif; ?>

            <?php if ($startPage > 1): ?>
                <?php
                $firstPageParams = $paginationParams;
                $firstPageParams['page'] = 1;
                ?>

                <a
                    class="pagination-link"
                    href="?<?php echo e(http_build_query($firstPageParams)); ?>">
                    1
                </a>

                <?php if ($startPage > 2): ?>
                    <span class="pagination-ellipsis">
                        ...
                    </span>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <?php
                $pageParams = $paginationParams;
                $pageParams['page'] = $i;
                ?>

                <a
                    class="pagination-link <?php echo $i === $page ? 'active' : ''; ?>"
                    href="?<?php echo e(http_build_query($pageParams)); ?>">
                    <?php echo e($i); ?>
                </a>
            <?php endfor; ?>

            <?php if ($endPage < $totalPages): ?>

                <?php if ($endPage < $totalPages - 1): ?>
                    <span class="pagination-ellipsis">
                        ...
                    </span>
                <?php endif; ?>

                <?php
                $lastPageParams = $paginationParams;
                $lastPageParams['page'] = $totalPages;
                ?>

                <a
                    class="pagination-link"
                    href="?<?php echo e(http_build_query($lastPageParams)); ?>">
                    <?php echo e($totalPages); ?>
                </a>
            <?php endif; ?>

            <?php if ($page < $totalPages): ?>
                <?php
                $nextParams = $paginationParams;
                $nextParams['page'] = $page + 1;
                ?>

                <a
                    class="pagination-link"
                    href="?<?php echo e(http_build_query($nextParams)); ?>">
                    Próxima
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>