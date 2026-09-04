<div class="page-header">
    <div class="page-header-title">
        <a
            class="btn btn-ghost btn-icon"
            href="?route=sales/index"
            title="Voltar para Vendas"
            aria-label="Voltar para Vendas">

            <?php echo icon('arrow-left'); ?>

        </a>

        <div class="page-header-left">
            <h1 class="page-title">
                Venda #<?php echo e($sale->id); ?>
            </h1>

            <p class="page-description">
                Visualize os detalhes desta venda.
            </p>
        </div>
    </div>

    <?php if ($sale->status !== 'cancelled'): ?>
        <div class="page-header-actions">
            <a
                class="btn btn-primary"
                href="?route=sales/edit&id=<?php echo e($sale->id); ?>">
                Editar venda
            </a>
        </div>
    <?php endif; ?>
</div>