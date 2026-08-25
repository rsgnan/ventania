<?php

namespace App\Controller;

use App\Core\ViewController;
use App\Repository\ProductRepository;
use App\Controller\ErrorController;
use App\Support\AuthService;
use App\Support\ActivityLogService;

class ProductController extends ViewController
{
    public function __construct(
        AuthService $authService,
        private ProductRepository $productRepository,
        private ActivityLogService $activityLogService
    ) {
        parent::__construct($authService);
    }

    public function index(): void
    {
        $products = $this->productRepository->getAll();
        $categories = $this->productRepository->getAllCategories();
        $productCategoryRows = $this->productRepository->getWithCategoryName();

        $categoryMap = [];

        foreach ($productCategoryRows as $row) {
            $categoryMap[$row['id']] = $row['category_name'];
        }

        foreach ($products as $product) {
            $product->category_name = $categoryMap[$product->id] ?? '';
        }

        $this->render('products/index', [
            'products' => $products,
            'categories' => $categories
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
                $categoryId,
                $stock,
                $price,
                $errors
            );

            $photo = $this->handlePhoto($errors, $tempPhoto, null);

            if (empty($errors)) {
                $productId = $this->productRepository->create(
                    $name,
                    $categoryId,
                    $tag,
                    $price,
                    $stock,
                    $description,
                    $photo
                );

                $this->activityLogService->log(
                    'product',
                    $productId,
                    $name,
                    'create'
                );
                header('Location: index.php?route=products/index');
                return;
            }
        }

        $this->render('products/create', [
            'errors' => $errors,
            'categories' => $categories,
            'tempPhoto' => $tempPhoto
        ]);
    }

    public function update(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $product = $this->productRepository->getById($id);

        if ($product === null) {
            (new ErrorController())->notFound();
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

                // Remove a foto antiga somente após atualizar o produto
                if (!empty($oldPhoto) && $oldPhoto !== $photo) {
                    $oldPhotoPath = __DIR__ . '/../../public/uploads/products/' . $oldPhoto;
                    if (is_file($oldPhotoPath)) {
                        @unlink($oldPhotoPath);
                    }
                }

                $this->activityLogService->log(
                    'product',
                    $id,
                    $name,
                    'update'
                );

                header('Location: index.php?route=products/index');
                return;
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
        int $categoryId,
        int $stock,
        float $price,
        array &$errors
    ): void {
        if (trim($name) === '') {
            $errors[] = 'Preencha o nome do produto corretamente.';
        }

        if ($categoryId === 0) {
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

        $hasNewPhoto = !empty($_FILES['photo']['name']);

        if ($hasNewPhoto) {
            $validation = validatePhoto($_FILES['photo']);

            if (!$validation['success']) {
                $errors[] = $validation['error'];
                return $photo;
            }

            // Usa a pasta temporária quando outros campos possuem erros
            $isTemporary = !empty($errors);
            $folder = $isTemporary ? 'tmp' : 'products';

            $upload = uploadPhoto($_FILES['photo'], __DIR__ . '/../../public/uploads/' . $folder);

            if (!$upload['success']) {
                $errors[] = $upload['error'];
                return $photo;
            }

            if ($isTemporary) {
                $tempPhoto = $upload['filename'];

                // Registra o arquivo para impedir o uso de fotos de outra sessão
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
        $belongToUser = in_array(
            $tempPhotoName,
            $_SESSION['temp_photos'] ?? [],
            true
        );

        if (!$belongToUser || !is_file($tempPath)) {
            $tempPhoto = null;
            return $photo;
        }

        $checkFile = [
            'error'     => UPLOAD_ERR_OK,
            'name'      => $tempPhotoName,
            'tmp_name'  => $tempPath,
            'size'      => filesize($tempPath)
        ];

        // Valida novamente o arquivo antes de movê-lo
        $validation = validatePhoto($checkFile);

        if (!$validation['success']) {
            $errors[] = $validation['error'];

            @unlink($tempPath);

            $this->forgetTempPhoto($tempPhotoName);

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
}
