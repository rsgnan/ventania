<div class="filters-tabs">
    <?php
    $allParams = [
        'route' => 'sales/index'
    ];

    if ($search !== '') {
        $allParams['search'] = $search;
    }
    ?>

    <a
        class="filters-tab <?php echo $status === null
                                ? 'active'
                                : ''; ?>"
        href="?<?php echo e(http_build_query($allParams)); ?>">

        Todas

        <span class="filters-tab-count">
            <?php echo e($statusCounts['all']); ?>
        </span>
    </a>

    <?php
    $statuses = [
        'pending' => 'Pendentes',
        'completed' => 'Concluídas',
        'cancelled' => 'Canceladas'
    ];
    ?>

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
                <?php echo e($statusCounts[$statusValue]); ?>
            </span>
        </a>
    <?php endforeach; ?>
</div>