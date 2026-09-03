<!-- Usuários -->
<?php echo csrf_field(); ?>

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            Usuários
        </h1>

        <p class="page-description">
            <?php echo e(count($users)); ?> usuários cadastrados
        </p>
    </div>

    <div class="page-header-actions">
        <a class="btn btn-primary" href="?route=users/create">

            <?php echo icon('plus'); ?>
            
            Novo Usuário
        </a>
    </div>
</div>
<div class="card">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Usuário</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <strong>
                                    <?php echo e($user->name); ?>
                                </strong>
                            </td>

                            <td>
                                <?php echo e($user->username); ?>
                            </td>

                            <td>
                                <?php echo e($user->email); ?>
                            </td>

                            <td>
                                <?php echo e(
                                    $user->role === 'admin'
                                        ? 'Administrador'
                                        : 'Operador'
                                ); ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $user->is_active
                                                        ? 'badge-success'
                                                        : 'badge-danger'; ?>">
                                    <?php echo $user->is_active
                                        ? 'Ativo'
                                        : 'Inativo'; ?></span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a
                                        class="btn btn-ghost btn-sm"
                                        href="?route=users/edit&id=<?php echo e($user->id); ?>">
                                        Editar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Nenhum usuário cadastrado.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>