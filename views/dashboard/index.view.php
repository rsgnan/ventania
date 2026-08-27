<div class="stats-grid">
    <div class="stat-card stat-card-blue">
        <div class="stat-icon">
            <?php echo icon('dollar-sign'); ?>
        </div>

        <div class="stat-value">
            R$ <?php echo number_format($revenueCurrentMonth, 2, ',', '.'); ?>
        </div>

        <div class="stat-label">
            Faturamento no mês
        </div>

        <div class="stat-change <?php echo $revenueChange >= 0
                                    ? 'stat-change-positive'
                                    : 'stat-change-negative'; ?>">

            <?php echo $revenueChange >= 0 ? '↑' : '↓'; ?>

            <?php echo number_format($revenueChange, 1, ',', '.'); ?>%

            <span>vs. mês anterior</span>
        </div>
    </div>

    <div class="stat-card stat-card-green">
        <div class="stat-icon">
            <?php echo icon('shopping-cart'); ?>
        </div>

        <div class="stat-value">
            <?php echo e($salesCurrentMonth); ?>
        </div>

        <div class="stat-label">
            Vendas no mês
        </div>

        <div class="stat-change <?php echo $salesChange >= 0
                                    ? 'stat-change-positive'
                                    : 'stat-change-negative'; ?>">

            <?php echo $salesChange >= 0 ? '↑' : '↓'; ?>

            <?php echo number_format($salesChange, 1, ',', '.'); ?>%

            <span>vs. mês anterior</span>
        </div>
    </div>

    <div class="stat-card stat-card-purple">
        <div class="stat-icon">
            <?php echo icon('clock'); ?>
        </div>

        <div class="stat-value">
            <?php echo e($pendingSalesCount); ?>
        </div>

        <div class="stat-label">
            Vendas pendentes
        </div>

        <div class="stat-meta">
            Aguardando conclusão
        </div>

    </div>

    <div class="stat-card stat-card-orange">
        <div class="stat-icon">
            <?php echo icon('alert-triangle'); ?>
        </div>

        <div class="stat-value">
            <?php echo e($lowStockCount); ?>
        </div>

        <div class="stat-label">
            Estoque baixo
        </div>

        <div class="stat-meta">
            Produtos com até 5 unidades
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">
                Últimas vendas
            </div>

            <div class="card-subtitle">
                Vendas registradas recentemente
            </div>
        </div>

        <a class="btn btn-ghost btn-sm" href="?route=sales/index">
            Ver todas
        </a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Venda</th>
                    <th>Data</th>
                    <th>Itens</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($latestSales as $sale): ?>
                    <tr>
                        <td>
                            <strong>
                                #<?php echo e($sale['id']); ?>
                            </strong>
                        </td>

                        <td>
                            <?php echo date('d/m/Y H:i', strtotime($sale['created_at'])); ?>
                        </td>

                        <td>
                            <?php echo e($sale['items_quantity']); ?>
                        </td>

                        <td>
                            R$ <?php echo number_format($sale['total_amount'], 2, ',', '.'); ?>
                        </td>

                        <td>
                            <?php 
                            $statusLabel = '';
                            $statusClass = '';

                            switch ($sale['status']) {
                                case 'completed':
                                    $statusLabel = 'Concluída';
                                    $statusClass = 'green';
                                    break;
                                
                                case 'pending':
                                    $statusLabel = 'Pendente';
                                    $statusClass = 'orange';
                                    break;
                                
                                case 'cancelled':
                                    $statusLabel = 'Cancelada';
                                    $statusClass = 'red';
                                    break;

                                default:
                                    $statusLabel = ucfirst($sale['status']);
                                    $statusClass = '';
                                    break;
                            }
                        ?>
                        <span class="badge <?php echo e($statusClass); ?>">
                            <?php echo e($statusLabel); ?>
                        </span>
                        </td>

                        <td>
                            <a
                                class="btn btn-ghost btn-sm"
                                href="?route=sales/edit&id=<?php echo e($sale['id']); ?>">
                                Ver
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>