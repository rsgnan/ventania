<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Dashboard
        </h1>

        <p class="page-description">
            Visão geral das vendas e do estoque.
        </p>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
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
                                    ? 'stat-positive'
                                    : 'stat-negative'; ?>">

            <?php echo $revenueChange >= 0 ? '↑' : '↓'; ?>
            <?php echo number_format(abs($revenueChange), 1, ',', '.'); ?>%

            <span>vs. mês anterior</span>
        </div>
    </div>

    <div class="stat-card stat-sales">
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
                                    ? 'stat-positive'
                                    : 'stat-negative'; ?>">

            <?php echo $salesChange >= 0 ? '↑' : '↓'; ?>
            <?php echo number_format(abs($salesChange), 1, ',', '.'); ?>%

            <span>vs. mês anterior</span>
        </div>
    </div>

    <div class="stat-card stat-pending">
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

    <div class="stat-card stat-stock">
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

<div class="dashboard-grid">
    <!-- Produtos mais vendidos -->
    <div class="card">
        <div class="card-header">
            <div class="card-header-content">
                <div class="card-title">
                    Produtos mais vendidos
                </div>

                <div class="card-subtitle">
                    Ranking por quantidade vendida nos últimos 30 dias
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Qtd.</th>
                        <th>Faturamento</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($bestSellingProducts)): ?>
                        <?php foreach ($bestSellingProducts as $product): ?>
                            <tr>
                                <td class="dashboard-product">
                                    <?php echo e($product['name']); ?>
                                </td>

                                <td>
                                    <?php echo e($product['quantity_sold']); ?>
                                </td>

                                <td class="dashboard-value">
                                    R$ <?php echo number_format(
                                            $product['revenue'],
                                            2,
                                            ',',
                                            '.'
                                        ); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="table-empty">
                                Nenhum produto vendido no período.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Categorias mais vendidas -->
    <div class="card">
        <div class="card-header">
            <div class="card-header-content">
                <div class="card-title">
                    Categorias mais vendidas
                </div>

                <div class="card-subtitle">
                    Ranking por quantidade vendida nos últimos 30 dias
                </div>
            </div>
        </div>

        <div class="ranking">
            <?php if (!empty($topSellingCategories)): ?>
                <?php
                $maxCategorySales = max(
                    array_column($topSellingCategories, 'quantity_sold')
                );
                ?>

                <?php foreach ($topSellingCategories as $index => $category): ?>
                    <?php
                    $percentage = $maxCategorySales > 0
                        ? ((int) $category['quantity_sold'] / $maxCategorySales) * 100
                        : 0;
                    ?>

                    <div class="ranking-item">
                        <div class="ranking-position">
                            <?php echo $index + 1; ?>
                        </div>

                        <div class="ranking-content">
                            <div class="ranking-header">
                                <span class="ranking-name">
                                    <?php echo e($category['name']); ?>
                                </span>

                                <span class="ranking-value">
                                    <?php echo e($category['quantity_sold']); ?> itens
                                </span>
                            </div>

                            <div class="ranking-bar">
                                <div
                                    class="ranking-progress"
                                    style="width: <?php echo number_format(
                                                        $percentage,
                                                        2,
                                                        '.',
                                                        ''
                                                    ); ?>%">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="ranking-empty">
                    Nenhuma venda encontrada nos últimos 30 dias.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Últimas vendas -->
<div class="card">
    <div class="card-header">
        <div class="card-header-content">
            <div class="card-title">
                Últimas vendas
            </div>

            <div class="card-subtitle">
                Vendas registradas recentemente
            </div>
        </div>

        <a
            class="btn btn-ghost btn-sm"
            href="?route=sales/index">

            Ver todas
        </a>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th class="table-hide-mobile">Venda</th>
                    <th>Data</th>
                    <th class="table-hide-mobile">Itens</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="table-hide-mobile">Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($latestSales)): ?>
                    <?php foreach ($latestSales as $sale): ?>
                        <?php
                        [$statusLabel, $statusClass] = match ($sale['status']) {
                            'completed' => ['Concluída', 'badge-success'],
                            'pending' => ['Pendente', 'badge-warning'],
                            'cancelled' => ['Cancelada', 'badge-danger'],
                            default => [
                                ucfirst((string) $sale['status']),
                                'badge-info'
                            ],
                        };
                        ?>

                        <tr>
                            <td class="table-hide-mobile dashboard-value">
                                #<?php echo e($sale['id']); ?>
                            </td>

                            <td>
                                <?php echo date(
                                    'd/m/Y H:i',
                                    strtotime($sale['created_at'])
                                ); ?>
                            </td>

                            <td class="table-hide-mobile">
                                <?php echo e($sale['items_quantity']); ?>
                            </td>

                            <td class="dashboard-value">
                                R$ <?php echo number_format(
                                        $sale['total_amount'],
                                        2,
                                        ',',
                                        '.'
                                    ); ?>
                            </td>

                            <td>
                                <span class="badge <?php echo e($statusClass); ?>">
                                    <?php echo e($statusLabel); ?>
                                </span>
                            </td>

                            <td class="table-hide-mobile">
                                <div class="table-actions">
                                    <a
                                        class="btn btn-ghost btn-sm"
                                        href="?route=sales/edit&id=<?php echo e($sale['id']); ?>">

                                        Ver
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="table-empty">
                            Nenhuma venda encontrada.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>