<?php

namespace App\Controller;

use App\Core\ViewController;
use App\Repository\ProductRepository;
use App\Support\AuthService;
use App\Support\ActivityLogService;

class ProductController extends ViewController
{
    public function __construct(
        AuthService $authService,
        private ProductRepository $productRepository,
        private ActivityLogService $activityLogService,
        private ErrorController $errorController
    ) {
        parent::__construct($authService);
    }

    public function index(): void
    {
        $categoryId = isset($_GET['category'])
            ? (int) $_GET['category']
            : null;

        if ($categoryId !== null && $categoryId <= 0) {
            $categoryId = null;
        }

        if (
            $categoryId !== null
            && !$this->productRepository->categoryExists($categoryId)
        ) {
            $categoryId = null;
        }

        $search = trim((string) ($_GET['search'] ?? ''));

        // Limita buscas excessivamente longas
        if (mb_strlen($search) > 100) {
            $search = mb_substr($search, 0, 100);
        }

        // Página atual nunca menor que 1
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $limit = 10;

        $allProductsCount = $search !== ''
            ? $this->productRepository->countSearch($search, null)
            : $this->productRepository->countAll();

        if ($search !== '') {
            $totalProducts = $this->productRepository->countSearch(
                $search,
                $categoryId
            );
        } elseif ($categoryId !== null) {
            $totalProducts = $this->productRepository->countByCategory(
                $categoryId
            );
        } else {
            $totalProducts = $allProductsCount;
        }

        $totalPages = max(1, (int) ceil($totalProducts / $limit));

        $page = min($page, $totalPages);

        $offset = ($page - 1) * $limit;

        if ($search !== '') {
            $products = $this->productRepository->search(
                $search,
                $categoryId,
                $limit,
                $offset
            );
        } elseif ($categoryId !== null) {
            $products = $this->productRepository->getByCategory(
                $categoryId,
                $limit,
                $offset
            );
        } else {
            $products = $this->productRepository->getAll(
                $limit,
                $offset
            );
        }

        $categories = $this->productRepository->getAllCategories($search);

        $this->render('products/index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts,
            'allProductsCount' => $allProductsCount
        ]);
    }

    public function create(): void
    {
        $categories = $this->productRepository->getAllCategories();
        $errors = [];
        $tempPhoto = $_POST['temp_photo'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim((string) ($_POST['name'] ?? ''));
            $categoryId = (int) ($_POST['category_id'] ?? 0);
            $tag = trim((string) ($_POST['tag'] ?? ''));
            $price = (float) ($_POST['price'] ?? 0);
            $stock = (int) ($_POST['stock'] ?? 0);
            $description = trim((string) ($_POST['description'] ?? ''));

            $this->validateFields(
                $name,
                $tag,
                $categoryId,
                $stock,
                $price,
                $errors
            );

            $photo = $this->handlePhoto($errors, $tempPhoto, null);

            if (empty($errors)) {
                try {
                    $productId = $this->productRepository->create(
                        $name,
                        $categoryId,
                        $tag,
                        $price,
                        $stock,
                        $description,
                        $photo
                    );
                } catch (\Throwable $exception) {
                    $this->deletePhoto($photo, 'products');

                    throw $exception;
                }

                $this->activityLogService->log(
                    'product',
                    $productId,
                    $name,
                    'create'
                );

                header('Location: index.php?route=products/index');
                exit;
            }
        }

        $this->render('products/create', [
            'errors' => $errors,
            'categories' => $categories,
            'tempPhoto' => $tempPhoto
        ]);
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $product = $this->productRepository->getById($id);

        if ($product === null) {
            $this->errorController->notFound();
            return;
        }

        $categories = $this->productRepository->getAllCategories();
        $errors = [];
        $tempPhoto = $_POST['temp_photo'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim((string) ($_POST['name'] ?? ''));
            $categoryId = (int) ($_POST['category_id'] ?? 0);
            $tag = trim((string) ($_POST['tag'] ?? ''));
            $price = (float) ($_POST['price'] ?? 0);
            $stock = (int) ($_POST['stock'] ?? 0);
            $description = trim((string) ($_POST['description'] ?? ''));

            $this->validateFields(
                $name,
                $tag,
                $categoryId,
                $stock,
                $price,
                $errors
            );

            $oldPhoto = $product->photo ?? '';

            $photo = $this->handlePhoto(
                $errors,
                $tempPhoto,
                $oldPhoto
            );

            if (empty($errors)) {
                try {
                    $this->productRepository->update(
                        $id,
                        $name,
                        $categoryId,
                        $tag,
                        $price,
                        $stock,
                        $description,
                        $photo
                    );
                } catch (\Throwable $exception) {
                    if ($photo !== $oldPhoto) {
                        $this->deletePhoto($photo, 'products');
                    }

                    throw $exception;
                }

                if ($oldPhoto !== $photo) {
                    $this->deletePhoto($oldPhoto, 'products');
                }

                $this->activityLogService->log(
                    'product',
                    $id,
                    $name,
                    'update'
                );

                header('Location: index.php?route=products/index');
                exit;
            }
        }

        $this->render('products/edit', [
            'product' => $product,
            'errors' => $errors,
            'categories' => $categories,
            'tempPhoto' => $tempPhoto
        ]);
    }

    private function validateFields(
        string $name,
        string $tag,
        int $categoryId,
        int $stock,
        float $price,
        array &$errors
    ): void {
        if ($name === '') {
            $errors[] = 'Preencha o nome do produto corretamente.';
        } elseif (mb_strlen($name) > 150) {
            $errors[] = 'O nome do produto deve ter no máximo 150 caracteres.';
        }

        if (mb_strlen($tag) > 100) {
            $errors[] = 'A tag deve ter no máximo 100 caracteres.';
        }

        if ($categoryId <= 0) {
            $errors[] = 'Selecione uma categoria.';
        } elseif (!$this->productRepository->categoryExists($categoryId)) {
            $errors[] = 'A categoria selecionada não existe.';
        }

        if ($stock < 0) {
            $errors[] = 'Valor de estoque não pode ser negativo.';
        }

        if ($price <= 0) {
            $errors[] = 'Estabeleça o valor do produto.';
        }
    }

    private function handlePhoto(
        array &$errors,
        ?string &$tempPhoto,
        ?string $currentPhoto
    ): string {
        $photo = $currentPhoto ?? '';

        if (
            isset($_FILES['photo']['error'])
            && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE
        ) {
            $validation = validatePhoto($_FILES['photo']);

            if (!$validation['success']) {
                $errors[] = $validation['error'];
                return $photo;
            }

            // Usa a pasta temporária quando outros campos possuem erros
            $isTemporary = !empty($errors);
            $folder = $isTemporary ? 'tmp' : 'products';

            $upload = uploadPhoto(
                $_FILES['photo'],
                __DIR__ . '/../../public/uploads/' . $folder
            );

            if (!$upload['success']) {
                $errors[] = $upload['error'];
                return $photo;
            }

            // Remove a foto temporária anterior após o novo upload ter sucesso
            if (!empty($tempPhoto)) {
                $this->deletePhoto($tempPhoto, 'tmp');
                $this->forgetTempPhoto($tempPhoto);
            }

            if ($isTemporary) {
                $tempPhoto = $upload['filename'];

                $_SESSION['temp_photos'] ??= [];
                $_SESSION['temp_photos'][] = $tempPhoto;
            } else {
                $photo = $upload['filename'];
                $tempPhoto = null;
            }

            return $photo;
        }

        if (empty($_POST['temp_photo'])) {
            return $photo;
        }

        $tempPhotoName = basename(trim((string) $_POST['temp_photo']));
        $tempPath = __DIR__ . '/../../public/uploads/tmp/' . $tempPhotoName;

        // Confirma que a foto temporária pertence à sessão atual
        $belongsToUser = in_array(
            $tempPhotoName,
            $_SESSION['temp_photos'] ?? [],
            true
        );

        if (!$belongsToUser || !is_file($tempPath)) {
            $tempPhoto = null;
            return $photo;
        }

        if (!empty($errors)) {
            $tempPhoto = $tempPhotoName;
            return $photo;
        }

        $finalPath = __DIR__ . '/../../public/uploads/products/' . $tempPhotoName;

        if (rename($tempPath, $finalPath)) {
            $this->forgetTempPhoto($tempPhotoName);
            $tempPhoto = null;

            return $tempPhotoName;
        }

        $errors[] = 'Falha ao salvar a imagem no servidor.';
        $tempPhoto = $tempPhotoName;

        return $photo;
    }

    private function forgetTempPhoto(string $filename): void
    {
        if (empty($_SESSION['temp_photos'])) {
            return;
        }

        $_SESSION['temp_photos'] = array_values(
            array_diff($_SESSION['temp_photos'], [$filename])
        );
    }

    private function deletePhoto(string $filename, string $folder): void
    {
        if ($filename === '') {
            return;
        }

        $filename = basename($filename);

        $path = __DIR__
            . '/../../public/uploads/'
            . $folder
            . '/'
            . $filename;

        if (is_file($path)) {
            unlink($path);
        }
    }
}
