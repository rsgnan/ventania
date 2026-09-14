<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Entrar | Ventania</title>
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">

    <link
        rel="stylesheet"
        href="assets/css/admin.css">

    <link
        rel="stylesheet"
        href="assets/css/login.css">

    <link
        rel="icon"
        href="assets/img/favicon.ico"
        sizes="any">

    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="assets/img/apple-touch-icon.png">
</head>

<body>

    <main class="login-page">

        <div class="login-brand">
            <div class="login-mark">
                V
            </div>

            <div>
                <div class="login-name">
                    Ventania
                </div>

                <div class="login-description">
                    Gestão de estoque
                </div>
            </div>
        </div>

        <div class="card login-card">

            <div class="login-header">
                <h1 class="login-title">Acessar painel</h1>

                <p class="login-subtitle">
                    Entre com suas credenciais para continuar.
                </p>
            </div>

            <!-- Alerta de erro -->
            <?php if (!empty($loginError)): ?>

                <div class="alert alert-danger">

                    <?php echo icon('alert-circle'); ?>

                    <span>
                        Usuário ou senha incorretos. Tente novamente.
                    </span>
                </div>
            <?php endif; ?>

            <form
                class="login-form"
                method="POST"
                action="index.php?<?php echo http_build_query([
                                        'route' => 'users/login'
                                    ]); ?>">

                <?php echo csrf_field(); ?>

                <div class="form-field">
                    <label
                        class="form-label"
                        for="username">

                        Usuário
                    </label>
                    <input
                        class="form-input"
                        type="text" id="username"
                        name="username"
                        value="<?php echo e($oldUsername ?? ''); ?>"
                        placeholder="Digite seu usuário"
                        autocomplete="username"
                        autofocus
                        required>
                </div>

                <div class="form-field">
                    <label
                        class="form-label"
                        for="password">

                        Senha
                    </label>

                    <div class="login-password">
                        <input
                            class="form-input"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Digite sua senha"
                            autocomplete="current-password"
                            required>

                        <button
                            type="button"
                            class="login-toggle"
                            id="togglePass"
                            aria-label="Mostrar senha">
                            <?php echo icon('eye'); ?>

                        </button>
                    </div>
                </div>

                <label class="login-remember">
                    <input
                        type="checkbox"
                        name="remember"
                        value="1">

                    <span>
                        Lembrar-me
                    </span>
                </label>


                <button
                    class="btn btn-primary login-submit"
                    type="submit">
                    Entrar
                </button>

            </form>
        </div>

        <div class="login-footer">
            &copy; 2026 Ventania
        </div>
    </main>

    <script>
        // Visibilidade da senha
        const togglePass = document.getElementById('togglePass');
        const password = document.getElementById('password');

        togglePass.addEventListener('click', () => {
            const isPassword = password.type === 'password';

            password.type = isPassword ? 'text' : 'password';

            togglePass.setAttribute(
                'aria-label',
                isPassword ? 'Ocultar senha' : 'Mostrar senha'
            );
        });
    </script>
</body>

</html>