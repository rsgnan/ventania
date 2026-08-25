<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumia - Painel Administrativo</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="assets/css/admin.css" rel="stylesheet">
    <link href="assets/css/sale.css" rel="stylesheet">
</head>

<body>
    <!-- CABEÇALHO PRINCIPAL -->
    <header class="navbar">
        <div class="navbar-container">
            <a href="?route=dashboard/index" class="navbar-brand">
                <div class="logo-icon">LM</div>
                <span>Lumia</span>
            </a>

            <div class="navbar-user">
                <div class="avatar">RG</div>
                <div class="user-info">
                    <div class="user-name">Renan Gonçalves</div>
                    <div class="user-role">Administrador</div>
                </div>
            </div>
        </div>
    </header>

    <!-- MENU PRINCIPAL -->
    <nav class="main-nav">
        <div class="main-nav-container">
            <a class="main-nav-item<?php if(str_starts_with($_GET['route'] ?? '', 'dashboard/')) echo e(' active'); ?>" href="?route=dashboard/index">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1" />
                    <rect x="14" y="3" width="7" height="7" rx="1" />
                    <rect x="3" y="14" width="7" height="7" rx="1" />
                    <rect x="14" y="14" width="7" height="7" rx="1" />
                </svg>
                Dashboard
            </a>
            <a class="main-nav-item<?php if(str_starts_with($_GET['route'] ?? '', 'products/')) echo e(' active'); ?>" href="?route=products/index">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 8v13H3V8" />
                    <path d="M1 3h22v5H1z" />
                    <line x1="10" y1="12" x2="14" y2="112" />
                </svg>
                Produtos
            </a>
            <a class="main-nav-item<?php if(str_starts_with($_GET['route'] ?? '', 'sales/')) echo e(' active'); ?>" href="?route=sales/index">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23" />
                    <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                </svg>
                Vendas
            </a>
            <?php if ($this->authService->isAdmin()): ?>
            <a class="main-nav-item<?php if(str_starts_with($_GET['route'] ?? '', 'users/')) echo e(' active'); ?>" href="?route=users/index">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                Usuários
            </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- CONTEÚDO -->
    <main class="main">
        <div class="content">
            <?php echo $contents; ?>
        </div>
    </main>
</body>
<script>
    // PREVIEW IMAGE
    const productImageInput = document.getElementById('product-image-input');
    if (productImageInput) {
        productImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image-preview');
            const icon = document.getElementById('image-upload-icon');
            const text = document.getElementById('image-upload-text');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    preview.src = ev.target.result;
                    preview.style.display = 'block';
                    icon.style.display = 'none';
                    text.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                icon.style.display = '';
                text.style.display = '';
            }
        });
    }
    // BAR CHART
    const months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    const values = [62, 78, 55, 88, 72, 95, 84, 102, 91, 88, 75, 94];
    const max = Math.max(...values);
    const chart = document.getElementById('barChart');
    const labels = document.getElementById('barLabels');
    if (chart && labels) {
        months.forEach((m, i) => {
            const h = Math.round((values[i] / max) * 140);
            chart.innerHTML += `<div class="bar-wrap"><div class="bar" style="height:${h}px" title="R$ ${values[i]}k"></div></div>`;
            labels.innerHTML += `<div class="bar-wrap">${m}</div>`;
        });
    }
</script>

</html>