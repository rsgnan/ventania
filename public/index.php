<?php
// Exibe erros para melhor trabalhar
ini_set('display_errors', 1);
session_start();

// Carrega dependênciase funções
require __DIR__ . '/../src/Support/Autoloader.php';
require __DIR__ . '/../src/Support/Functions.php';
require __DIR__ . '/../src/Support/Icons.php';

// Container básico do sistema
$container = new \App\Core\Container();
$container->bind('pdo', function () {
    return require __DIR__ . '/../src/Database/Connection.php';
});

// AuthService
$container->bind('authService', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \App\Support\AuthService($pdo);
});

// Logs de atividade
$container->bind('activityLogRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\ActivityLogRepository($pdo);
});

$container->bind('activityLogService', function () use ($container) {
    $activityLogRepository = $container->get('activityLogRepository');
    $authService = $container->get('authService');

    return new \App\Support\ActivityLogService(
        $activityLogRepository,
        $authService
    );
});

// Login
$container->bind('loginController', function () use ($container) {
    $authService = $container->get('authService');
    return new \App\Controller\LoginController($authService);
});

// Produtos
$container->bind('productRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\ProductRepository($pdo);
});

$container->bind('productController', function () use ($container) {
    $authService = $container->get('authService');
    $productRepository = $container->get('productRepository');
    $activityLogService = $container->get('activityLogService');

    return new \App\Controller\ProductController(
        $authService,
        $productRepository,
        $activityLogService
    );
});

// Usuários
$container->bind('userRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\UserRepository($pdo);
});

$container->bind('userController', function () use ($container) {
    $authService = $container->get('authService');
    $userRepository = $container->get('userRepository');
    $activityLogService = $container->get('activityLogService');

    return new \App\Controller\UserController(
        $authService,
        $userRepository,
        $activityLogService
    );
});


// Vendas
$container->bind('saleRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\SaleRepository($pdo);
});

$container->bind('saleItemRepository', function () use ($container) {
    $pdo = $container->get('pdo');
    return new \App\Repository\SaleItemRepository($pdo);
});

$container->bind('saleController', function () use ($container) {

    $authService = $container->get('authService');
    $pdo = $container->get('pdo');
    $productRepository = $container->get('productRepository');
    $saleRepository = $container->get('saleRepository');
    $saleItemRepository = $container->get('saleItemRepository');
    $activityLogService = $container->get('activityLogService');

    return new \App\Controller\SaleController(
        $authService,
        $pdo,
        $productRepository,
        $saleRepository,
        $saleItemRepository,
        $activityLogService
    );
});

// Dashboard
$container->bind('dashboardRepository', function () use ($container) {
    $pdo = $container->get('pdo');

    return new \App\Repository\DashboardRepository($pdo);
});

$container->bind('dashboardController', function () use ($container) {
    $authService = $container->get('authService');
    $dashboardRepository = $container->get('dashboardRepository');

    return new \App\Controller\DashboardController(
        $authService,
        $dashboardRepository
    );
});

// CSRF
$container->bind('csrfHelper', function () {
    return new \App\Support\CsrfHelper();
});

$csrfHelper = $container->get('csrfHelper');
$csrfHelper->handle();

function csrf_token()
{
    global $container;
    $csrfHelper = $container->get('csrfHelper');
    return $csrfHelper->generateToken();
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

// Errors
$container->bind('errorController', function () use ($container) {
    return new \App\Controller\ErrorController();
});

// Tratamento de route

$route = (string) ($_GET['route'] ?? 'pages');

if ($route == 'pages') {
    $page = (string) ($_GET['page'] ?? 'index');

    $adminController = $container->get('productController');
    $adminController->index();
} else if ($route === 'admin/login') {
    $adminController = $container->get('loginController');
    $adminController->login();
} else if ($route === 'admin/logout') {
    $adminController = $container->get('loginController');
    $adminController->logout();
} else if ($route === 'products/index') {
    $authService = $container->get('authService');
    $authService->ensureLoggedIn();

    $adminController = $container->get('productController');
    $adminController->index();
} else if ($route === 'products/create') {
    $authService = $container->get('authService');
    $authService->ensureLoggedIn();

    $adminController = $container->get('productController');
    $adminController->create();
} else if ($route === 'products/edit') {
    $authService = $container->get('authService');
    $authService->ensureLoggedIn();

    $adminController = $container->get('productController');
    $adminController->update();
} else if ($route === 'login/index') {
    $adminController = $container->get('productController');
    $adminController->update();
} else if ($route === 'sales/index') {
    $salesController = $container->get('saleController');
    $salesController->index();
} else if ($route === 'sales/create') {
    $authService = $container->get('authService');
    $authService->ensureLoggedIn();

    $salesController = $container->get('saleController');
    $salesController->create();
} else if ($route === 'sales/edit') {
    $authService = $container->get('authService');
    $authService->ensureLoggedIn();

    $salesController = $container->get('saleController');
    $salesController->edit();
} else if ($route === 'sales/cancel') {
    $authService = $container->get('authService');
    $authService->ensureLoggedIn();

    $salesController = $container->get('saleController');
    $salesController->cancel();
} else if ($route === 'users/index') {
    $authService = $container->get('authService');
    $authService->ensureAdmin();

    $userController = $container->get('userController');
    $userController->index();
} else if ($route === 'users/create') {
    $authService = $container->get('authService');
    $authService->ensureAdmin();

    $userController = $container->get('userController');
    $userController->create();
} else if ($route === 'users/edit') {
    $authService = $container->get('authService');
    $authService->ensureAdmin();

    $userController = $container->get('userController');
    $userController->edit();
} else if ($route === 'dashboard/index') {
    $authService = $container->get('authService');
    $authService->ensureLoggedIn();

    $dashboardController = $container->get('dashboardController');
    $dashboardController->index();
} else {
    // Nenhuma rota bateu então devolve o error 404
    $errorController = $container->get('errorController');
    $errorController->notFound();
}
