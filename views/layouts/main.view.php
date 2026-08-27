<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ventania - Painel Administrativo</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <link href="assets/css/admin.css" rel="stylesheet">

    <link rel="icon" href="assets/img/favicon.ico" sizes="any">
    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="assets/img/apple-touch-icon">
</head>

<body>

    <div class="app">

        <!-- Sidebar -->
        <aside class="app-sidebar" id="app-sidebar">

            <div class="sidebar-header">
                <a class="sidebar-brand" href="?route=dashboard/index">
                    <div class="sidebar-logo">
                        VN
                    </div>

                    <span class="sidebar-brand-name">
                        Ventania
                    </span>
                </a>

                <button
                    class="sidebar-close"
                    id="sidebar-close"
                    type="button"
                    aria-label="Fechar menu">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <nav class="sidebar-nav">

                <a
                    class="sidebar-nav-link
                    <?php if (
                        str_starts_with($_GET["route"] ?? "", "dashboard/")
                    ) {
                        echo " active";
                    } ?>"
                    href="?route=dashboard/index">

                    <span>Dashboard</span>
                </a>

                <a
                    class="sidebar-nav-link
                    <?php if (
                        str_starts_with($_GET["route"] ?? "", "products/")
                    ) {
                        echo " active";
                    } ?>"
                    href="?route=products/index">

                    <span>Produtos</span>
                </a>

                <a
                    class="sidebar-nav-link
                    <?php if (str_starts_with($_GET["route"] ?? "", "sales/")) {
                        echo " active";
                    } ?>"
                    href="?route=sales/index">

                    <span>Vendas</span>
                </a>

                <?php if ($this->authService->isAdmin()): ?>
                <a
                    class="sidebar-nav-link
                    <?php if (str_starts_with($_GET["route"] ?? "", "users/")) {
                        echo " active";
                    } ?>"
                    href="?route=users/index">

                    <span>Usuários</span>
                </a>
                <?php endif; ?>

            </nav>

            <div class="sidebar-footer">
                    <div class="sidebar-version">
                        Ventania
                        <span>v1.0.0</span>
                    </div>

            </div>

        </aside>

        <!-- Overlay mobile -->
        <div class="app-overlay" id="app-overlay"></div>

        <!-- Área principal -->
        <div class="app-body">

            <!-- Header -->
            <header class="app-header">

                <button
                    class="header-menu-button"
                    id="sidebar-open"
                    type="button"
                    aria-label="Abrir menu">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round">
                        <line x1="4" y1="6" x2="20" y2="6" />
                        <line x1="4" y1="12" x2="20" y2="12" />
                        <line x1="4" y1="18" x2="20" y2="18" />
                    </svg>

                </button>

                <div class="header-spacer"></div>

                <div class="header-user">

                    <div class="header-avatar">
                        RG
                    </div>

                    <div class="header-user-info">
                        <span class="header-user-name">
                            Renan Gonçalves
                        </span>

                        <span class="header-user-role">
                            Administrador
                        </span>
                    </div>

                </div>

            </header>

            <!-- Conteúdo -->
            <main class="app-main">
                <div class="app-content">
                    <?php echo $contents; ?>
                </div>
            </main>

        </div>

    </div>

    <script>
        const sidebar = document.getElementById('app-sidebar');
        const overlay = document.getElementById('app-overlay');
        const sidebarOpen = document.getElementById('sidebar-open');
        const sidebarClose = document.getElementById('sidebar-close');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        }

        sidebarOpen.addEventListener('click', openSidebar);
        sidebarClose.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Preview da imagem do produto
        const productImageInput = document.getElementById('product-image-input');

        if (productImageInput) {
            productImageInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                const preview = document.getElementById('image-preview');
                const icon = document.getElementById('image-upload-icon');
                const text = document.getElementById('image-upload-text');

                if (!file) {
                    preview.style.display = 'none';
                    icon.style.display = '';
                    text.style.display = '';
                    return;
                }

                const reader = new FileReader();

                reader.onload = function (event) {
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                    icon.style.display = 'none';
                    text.style.display = 'none';
                };

                reader.readAsDataURL(file);
            });
        }
    </script>

</body>

</html>
