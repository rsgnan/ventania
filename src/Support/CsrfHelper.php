<?php

namespace App\Support;

class CsrfHelper
{
    public function handle(): void
    {
        $this->ensureSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                isset($_POST['_csrf']) &&
                isset($_SESSION['csrfToken']) &&
                hash_equals(
                    $_SESSION['csrfToken'],
                    (string) $_POST['_csrf']
                )
            ) {
                unset($_SESSION['csrfToken']);
                return;
            }

            http_response_code(419);

            echo "Error: CSRF token mismatch";

            exit();
        }
    }

    public function generateToken(): string
    {
        $this->ensureSession();

        if (!isset($_SESSION['csrfToken'])) {
            $_SESSION['csrfToken'] = bin2hex(
                random_bytes(32)
            );
        }
        
        return $_SESSION['csrfToken'];
    }

    private function ensureSession(): void
    {
        if (session_id() === '') {
            session_start();
        }
    }
}
