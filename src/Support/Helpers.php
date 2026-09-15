<?php

function e($value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

function validatePhoto(array $file): array
{
    if (
        !isset($file['error'], $file['size'], $file['tmp_name']) ||
        is_array($file['error'])
    ) {
        return [
            'success' => false,
            'error' => 'Upload inválido.'
        ];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [
            'success' => false,
            'error' => match ($file['error']) {
                UPLOAD_ERR_NO_FILE =>
                'Nenhum arquivo enviado.',

                UPLOAD_ERR_INI_SIZE,
                UPLOAD_ERR_FORM_SIZE =>
                'Arquivo excede o tamanho permitido pelo servidor.',

                default =>
                'Erro no upload do arquivo.'
            }
        ];
    }

    // Confirma que o arquivo foi recebido pelo mecanismo de upload do PHP
    if (!is_uploaded_file($file['tmp_name'])) {
        return [
            'success' => false,
            'error' => 'Upload inválido.'
        ];
    }

    $maxSize = 5 * 1024 * 1024;

    if ($file['size'] <= 0 || $file['size'] > $maxSize) {
        return [
            'success' => false,
            'error' => 'O arquivo deve ter no máximo 5 MB.'
        ];
    }

    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!in_array($mimeType, ['image/jpeg', 'image/png'], true)) {
        return [
            'success' => false,
            'error' => 'Formato inválido. Permitido: JPG, JPEG e PNG.'
        ];
    }

    if (getimagesize($file['tmp_name']) === false) {
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

function uploadPhoto(array $file, string $uploadDir): array
{
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            return [
                'success' => false,
                'filename' => null,
                'error' => 'Não foi possível criar o diretório de upload.'
            ];
        }
    }

    // Define a extensão pelo MIME real, sem confiar no nome enviado pelo usuário
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    $extension = match ($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        default => null
    };

    if ($extension === null) {
        return [
            'success' => false,
            'filename' => null,
            'error' => 'Formato de imagem inválido.'
        ];
    }

    $filename = uniqid('product_', true) . '.' . $extension;
    $destination = rtrim($uploadDir, '/') . '/' . $filename;

    // move_uploaded_file() também garante que a imagem é um upload HTTP válido
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return [
            'success' => false,
            'filename' => null,
            'error' => 'Falha ao salvar a imagem no servidor.'
        ];
    }

    cleanOldTempFiles(__DIR__ . '/../../public/uploads/tmp');

    return [
        'success' => true,
        'filename' => $filename,
        'error' => null
    ];
}

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
        if (
            is_file($file) &&
            ($now - filemtime($file)) >= $maxAgeInSeconds
        ) {
            unlink($file);
        }
    }
}
