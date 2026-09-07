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
                                Nenhum produto encontrado.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>