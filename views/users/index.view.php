<!-- PRODUTOS -->
<?php echo csrf_field(); ?>
<div class="page-header">
    <div>
        <h1>Usuários</h1>
        <p><?php echo e(count($users)); ?> usuários cadastrados</p>
    </div>
    <a href="?route=users/create" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Novo Usuário
    </a>
</div>

<div class="card">
    <table>
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
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><strong><?php echo e($user->name); ?></strong></td>
                    <td><?php echo e($user->username); ?></td>
                    <td><?php echo e($user->email); ?></td>
                    <td>
                        <?php echo e(
                            $user->role === 'admin'
                            ? 'Administrador'
                            : 'Operador'
                        ); ?>
                    </td>
                    <td>
                        <?php echo $user->is_active ? 'Ativo' : 'Inativo'; ?>
                    </td>
                    <td>
                        <div class="actions">
                            <a href="?route=users/edit&id=<?php echo e($user->id); ?>" class="btn btn-ghost btn-sm">Editar</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>