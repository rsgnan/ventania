<form method="POST">
    <?php echo csrf_field(); ?>

    <div class="page-header">
        <div class="page-header-left">
            <div class="page-header-title">
                <a
                    class="btn btn-ghost btn-icon"
                    href="?route=users/index"
                    title="Voltar para Usuários">

                    <?php echo icon('arrow-left'); ?>

                </a>
                <div>
                    <h1 class="page-title">
                        Novo Usuário
                    </h1>

                    <p class="page-description">
                        Cadastre um novo usuário para acessar o sistema.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="form-panel">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                
                <?php echo icon('alert-circle'); ?>

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
                    <div class="card-title">
                        Dados do Usuário
                    </div>

                    <div class="card-subtitle">
                        Informações pessoais e credenciais de acesso
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label" for="name">
                            Nome
                        </label>

                        <input
                            class="form-input"
                            type="text"
                            id="name"
                            name="name"
                            value="<?php echo e($_POST['name'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="form-field">
                        <label class="form-label" for="username">
                            Usuário
                        </label>

                        <input
                            class="form-input"
                            type="text"
                            id="username"
                            name="username"
                            value="<?php echo e($_POST['username'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="form-field">
                        <label class="form-label" for="email">
                            E-mail
                        </label>

                        <input
                            class="form-input"
                            type="email"
                            id="email"
                            name="email"
                            value="<?php echo e($_POST['email'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="form-field">
                        <label class="form-label" for="password">
                            Senha
                        </label>

                        <input
                            class="form-input"
                            type="password"
                            id="password"
                            name="password"
                            required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">
                        Acesso e Permissões
                    </div>

                    <div class="card-subtitle">
                        Defina o perfil de acesso e o status da conta
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label" for="role">
                            Perfil
                        </label>

                        <select
                            class="form-input"
                            id="role"
                            name="role"
                            required>

                            <option
                                value="operator"
                                <?php echo ($_POST['role'] ?? 'operator') === 'operator' ? 'selected' : ''; ?>>
                                Operador
                            </option>

                            <option
                                value="admin"
                                <?php echo ($_POST['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>
                                Administrador
                            </option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label class="form-label" for="is_active">
                            Status
                        </label>

                        <select 
                            class="form-input" 
                            id="is_active" 
                            name="is_active" 
                            required>

                            <option value="1"
                            <?php echo ($_POST['is_active'] ?? '1') === '1' ? 'selected' : ''; ?>>
                            Ativo
                            </option>

                            <option value="0"
                            <?php echo ($_POST['is_active'] ?? '') === '0' ? 'selected' : ''; ?>>
                            Inativo
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Salvar Usuário
            </button>

            <a class="btn btn-ghost" href="?route=users/index">
                Cancelar
            </a>
        </div>
    </div>
</form>