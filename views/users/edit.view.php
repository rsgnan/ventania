<form method="POST">
    <?php echo csrf_field(); ?>

    <div class="page-header">
        <div class="page-header-left">
            <a class="btn btn-ghost btn-icon" href="?route=users/index" title="Voltar para Usuários">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
            </a>
            <div>
                <h1>Editar Usuário</h1>
                <p>Atualize os dados e permissões do usuário</p>
            </div>
        </div>
    </div>

    <div class="form-panel">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <ul class="alert-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Dados do Usuário</div>
                    <div class="card-subtitle">Informações pessoais e credenciais de acesso</div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nome</label>
                        <input class="form-input" type="text" id="name" name="name" value="<?php echo e($user->name); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Usuário</label>
                        <input class="form-input" type="text" id="username" name="username" value="<?php echo e($user->username); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">E-mail</label>
                        <input class="form-input" type="email" id="email" name="email" value="<?php echo e($user->email); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nova Senha</label>
                        <input class="form-input" type="password" id="password" name="password">
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Acesso e Permissões</div>
                    <div class="card-subtitle">Defina o perfil de acesso e o status da conta</div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Perfil</label>
                        <select class="form-input" id="role" name="role" required>
                            <option value="operator" <?php if ($user->role === 'operator') echo 'selected'; ?>>Operador</option>
                            <option value="admin" <?php if ($user->role === 'admin') echo 'selected'; ?>>Administrador</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-input" id="is_active" name="is_active" required>
                            <option value="1" <?php if ((int) $user->is_active === 1) echo 'selected'; ?>>Ativo</option>
                            <option value="0" <?php if ((int) $user->is_active === 0) echo 'selected'; ?>>Inativo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Salvar Alterações
            </button>
            <a href="?route=users/index" class="btn btn-ghost">
                Cancelar
            </a>
        </div>
    </div>
</form>