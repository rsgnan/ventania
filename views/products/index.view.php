<!-- Produtos -->
<?php echo csrf_field(); ?>

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Produtos
        </h1>

        <p class="page-description">
            <?php echo e(count($products)); ?> produtos cadastrados
        </p>
    </div>

    <div class="page-header-actions">
        <a class="btn btn-primary" href="?route=products/create">
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linejoin="round"
                stroke-linecap="round">

                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Novo Produto
        </a>
    </div>
</div>

<?php
$selectedCategory = isset($_GET['category'])
    ? (int) ($_GET['category'])
    : 0;
?>

<div class="tabs">
    <a
        class="tab <?php echo $selectedCategory === 0 ? 'active' : ''; ?>"
        href="?route=products/index">
        Todos
    </a>

    <?php foreach ($categories as $category): ?>
        <a
            class="tab <?php echo $selectedCategory === (int) $category->id
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
                            <?php echo e($product->price); ?>
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
            </tbody>
        </table>
    </div>
</div>