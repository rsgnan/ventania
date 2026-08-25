<?php

// Proteção de ataque XSS
function e($value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Validação da Foto
function validatePhoto(array $file): array
{
    // Verifica se o campo realmente foi enviado
    if (!isset($file['error']) || is_array($file['error'])) {
        return [
            'success' => false,
            'error' => 'Upload inválido.'
        ];
    }

    // Verifica erros do próprio PHP
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;

        case UPLOAD_ERR_NO_FILE:
            return [
                'success' => false,
                'error' => 'Nenhum arquivo enviado.'
            ];

        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return [
                'success' => false,
                'error' => 'Arquivo excede o tamanho permitido pelo servidor.'
            ];

        default:
            return [
                'success' => false,
                'error' => 'Erro desconhecido no upload.'
            ];
    }

    // Valida o nome do arquivo
    $name = (string) ($file['name'] ?? '');

    if (
        $name === '' ||
        basename($name) !== $name ||
        strpos($name, "\0") !== false
    ) {
        return [
            'success' => false,
            'error' => 'Nome de arquivo inválido.'
        ];
    }

    // Limite de 5MB
    $maxSize = 5 * 1024 * 1024;

    if ($file['size'] > $maxSize) {
        return [
            'success' => false,
            'error' => 'O arquivo deve ter no máximo 5MB.'
        ];
    }

    // Valida a extensão
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $extension = strtolower(
        pathinfo($file['name'], PATHINFO_EXTENSION)
    );

    if (!in_array($extension, $allowedExtensions, true)) {
        return [
            'success' => false,
            'error' => 'Extensão inválida. Permitido: JPG, JPEG e PNG.'
        ];
    }

    // Verifica se o arquivo temporário existe
    if (
        empty($file['tmp_name']) ||
        !is_file($file['tmp_name'])
    ) {
        return [
            'success' => false,
            'error' => 'Arquivo temporário inválido.'
        ];
    }

    // Valida o tipo MIME real do arquivo
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    $allowedMimeTypes = ['image/jpeg', 'image/png'];

    if (!in_array($mimeType, $allowedMimeTypes, true)) {
        return [
            'success' => false,
            'error' => 'O arquivo não é uma imagem válida.'
        ];
    }

    return [
        'success' => true,
        'error' => null
    ];
}

// Upload da Foto
function uploadPhoto(array $file, string $uploadDir): array
{
    // Garante que a pasta existe
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Não foi possível criar o diretório de upload.'
            ];
        }
    }

    // Gera um nome único, mantendo a extensão original
    $extension = strtolower(
        pathinfo($file['name'], PATHINFO_EXTENSION)
    );

    $filename = uniqid('product_', true) . '.' . $extension;
    $destination = rtrim($uploadDir, '/') . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return [
            'success' => false,
            'filename' => null,
            'error' => 'Falha ao salvar a imagem no servidor.'
        ];
    }

    // Limpeza dos uploads temporários com mais de 2 horas
    $tmpDir = __DIR__ . '/../../public/uploads/tmp';

    cleanOldTempFiles($tmpDir, 7200);

    return [
        'success' => true,
        'filename' => $filename,
        'error' => null
    ];
}

// Limpa arquivos antigos da pasta /tmp
function cleanOldTempFiles(
    string $dir,
    int $maxAgeInSeconds = 7200
): void {
    if (!is_dir($dir)) {
        return;
    }

    $files = glob($dir . '/*');

    if ($files === false) {
        return;
    }

    $now = time();

    foreach ($files as $file) {
        if (is_file($file)) {
            // Remove arquivos que ultrapassaram o tempo limite
            if (($now - filemtime($file)) >= $maxAgeInSeconds) {
                unlink($file);
            }
        }
    }
}
