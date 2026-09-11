<div class="filters-tabs">
    <?php
    $statuses = [
        'pending' => 'Pendentes',
        'completed' => 'Concluídas',
        'cancelled' => 'Canceladas'
    ];

    $allParams = [
        'route' => 'sales/index'
    ];

    if ($search !== '') {
        $allParams['search'] = $search;
    }
    ?>

    <a
        class="filters-tab <?php echo $status === null ? 'active' : ''; ?>"
        href="?<?php echo e(http_build_query($allParams)); ?>">

        Todas

        <span class="filters-tab-count">
            <?php echo e($allSalesCount); ?>
        </span>
    </a>

    <?php foreach ($statuses as $statusValue => $statusLabel): ?>
        <?php
        $statusParams = [
            'route' => 'sales/index',
            'status' => $statusValue
        ];

        if ($search !== '') {
            $statusParams['search'] = $search;
        }
        ?>

        <a
            class="filters-tab <?php echo $status === $statusValue
                                    ? 'active'
                                    : ''; ?>"
            href="?<?php echo e(http_build_query($statusParams)); ?>">

            <?php echo e($statusLabel); ?>

            <span class="filters-tab-count">
                <?php echo e($statusCounts[$statusValue] ?? 0); ?>
            </span>
        </a>
    <?php endforeach; ?>
</div>