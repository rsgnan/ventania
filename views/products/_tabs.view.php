<div class="filters-tabs">
    <?php
    $allParams = [
        'route' => 'products/index'
    ];

    if ($search !== '') {
        $allParams['search'] = $search;
    }
    ?>

    <a
        class="filters-tab <?php echo $selectedCategory === null ? 'active' : ''; ?>"
        href="?<?php echo e(http_build_query($allParams)); ?>">

        Todos

        <span class="filters-tab-count">
            <?php echo e($allProductsCount); ?>
        </span>

    </a>

    <?php foreach ($categories as $category): ?>
        <?php
        $categoryParams = [
            'route' => 'products/index',
            'category' => $category->id
        ];

        if ($search !== '') {
            $categoryParams['search'] = $search;
        }
        ?>

        <a
            class="filters-tab <?php echo $selectedCategory === $category->id
                                    ? 'active'
                                    : ''; ?>"
            href="?<?php echo e(http_build_query($categoryParams)); ?>">

            <?php echo e($category->name); ?>

            <span class="filters-tab-count">
                <?php echo e($category->product_count); ?>
            </span>

        </a>
    <?php endforeach; ?>
</div>