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
<div class="sale-view">

    <div class="card">
        <div class="card-header">
            <div>
                <h2 class="card-title">
                    Informações da venda
                </h2>

                <p class="card-description">
                    Dados gerais e situação atual da venda.
                </p>
            </div>
        </div>

        <div class="sale-info">
            <div class="sale-detail">
                <span class="sale-label">
                    Cliente
                </span>

                <strong>
                    <?php echo e($sale->customer_name); ?>
                </strong>
            </div>

            <div class="sale-detail">
                <span class="sale-label">
                    Data
                </span>

                <strong>
                    <?php echo e(
                        date(
                            'd/m/Y H:i',
                            strtotime($sale->created_at)
                        )
                    ); ?>
                </strong>
            </div>

            <div class="sale-detail">
                <span class="sale-label">
                    Status
                </span>

                <div>
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
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <h2 class="card-title">
                    Produtos
                </h2>

                <p class="card-description">
                    Itens registrados nesta venda.
                </p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço unitário</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <strong>
                                    <?php echo e($item['product_name']); ?>
                                </strong>
                            </td>

                            <td>
                                R$ <?php echo e(
                                        number_format(
                                            (float) $item['unit_price'],
                                            2,
                                            ',',
                                            '.'
                                        )
                                    ); ?>
                            </td>

                            <td>
                                <?php echo e($item['quantity']); ?>
                            </td>

                            <td>
                                <strong>
                                    R$ <?php echo e(
                                            number_format(
                                                (float) $item['subtotal'],
                                                2,
                                                ',',
                                                '.'
                                            )
                                        ); ?>
                                </strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <h2 class="card-title">
                    Resumo
                </h2>

                <p class="card-description">
                    Valores finais da venda.
                </p>
            </div>
        </div>

        <div class="sale-totals">
            <?php
            $subtotal = (float) $sale->total_amount
                + (float) $sale->discount_amount;
            ?>

            <div class="sale-total-row">
                <span>
                    Subtotal
                </span>

                <span>
                    R$ <?php echo e(
                            number_format(
                                $subtotal,
                                2,
                                ',',
                                '.'
                            )
                        ); ?>
                </span>
            </div>

            <div class="sale-total-row">
                <span>
                    Desconto
                </span>

                <span>
                    R$ <?php echo e(
                            number_format(
                                $sale->discount_amount,
                                2,
                                ',',
                                '.'
                            )
                        ); ?>
                </span>
            </div>

            <div class="sale-total-row sale-total-final">
                <strong>
                    Total
                </strong>

                <strong>
                    R$ <?php echo e(
                            number_format(
                                $sale->total_amount,
                                2,
                                ',',
                                '.'
                            )
                        ); ?>
                </strong>
            </div>
        </div>
    </div>
</div>