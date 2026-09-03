<!-- Vendas -->

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Vendas
        </h1>

        <p class="page-description">
            <?php echo e(count($sales)); ?> vendas registradas
        </p>
    </div>

    <div class="page-header-actions">
        <a class="btn btn-primary" href="?route=sales/create">
            <?php echo icon('plus'); ?>
            Nova Venda
        </a>
    </div>
</div>

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
                <?php if (empty($sales)): ?>
                    <tr>
                        <td colspan="6" class="table-empty">
                            Nenhuma venda registrada ainda.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td>
                                <strong>
                                    <?php echo e($sale->customer_name); ?>
                                </strong>
                            </td>

                            <td>
                                <?php echo e($sale->items_quantity); ?>
                                <?php echo $sale->items_quantity == 1 ? 'item' : 'itens'; ?>
                            </td>

                            <td>
                                <strong>
                                    R$ <?php echo e(number_format((float) $sale->total_amount, 2, ',', '.')); ?>
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
                                <?php else: ?>
                                    <span class="badge badge-danger">
                                        Cancelada
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php echo e(date('d/m/Y H:i', strtotime($sale->created_at))); ?>
                            </td>

                            <td>
                                <div class="table-actions">
                                    <a
                                        class="btn btn-ghost btn-sm"
                                        href="?route=sales/edit&id=<?php echo e($sale->id); ?>">
                                        Editar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>