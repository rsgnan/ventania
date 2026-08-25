<?php

namespace App\Support;

use PDO;

class AuthService
{
    public function __construct(private PDO $pdo) {}

    public function ensureSession(): void
    {
        if (session_id() === '') {
            session_start();
        }
    }

    public function logout(): void
    {
        $this->ensureSession();
        
        // Remove os dados do usuário da sessão
        unset(
            $_SESSION['user_id'],
            $_SESSION['user_name'],
            $_SESSION['user_role']
        );

        session_regenerate_id(true);
    }

    public function getUserId(): ?int
    {
        $this->ensureSession();

        return isset($_SESSION['user_id'])
            ? (int) $_SESSION['user_id']
            : null;
    }

    public function handleLogin(string $username, string $password): bool
    {
        if ($username === '' || $password === '') {
            return false;
        }

        $stmt = $this->pdo->prepare(
            'SELECT 
                `id`, 
                `name`, 
                `password`, 
                `role`, 
                `is_active`
            FROM `users` 
            WHERE `username` = :username'
        );

        $stmt->bindValue(':username', $username);
        $stmt->execute();
        
        $entry = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($entry === false) {
            return false;
        }

        // Impede o acesso de usuários inativos
        if ((int) $entry['is_active'] !== 1) {
            return false;
        }

        if (!password_verify($password, $entry['password'])) {
            return false;
        }

        $this->ensureSession();

        // Armazena os dados necessários do usuário na sessão
        $_SESSION['user_id'] = (int) $entry['id'];
        $_SESSION['user_name'] = $entry['name'];
        $_SESSION['user_role'] = $entry['role'];

        session_regenerate_id(true);

        return true;
    }

    public function isAdmin(): bool
    {
        $this->ensureSession();

        return ($_SESSION['user_role'] ?? null) === 'admin';
    }

    public function isLoggedIn(): bool
    {
        $this->ensureSession();

        return !empty($_SESSION['user_id']);
    }

    public function ensureAdmin(): void
    {
        $this->ensureLoggedIn();

        // Restringe o acesso aos administradores
        if (!$this->isAdmin()) {
            header('Location: index.php?' . http_build_query([
                'route' => 'dashboard/index'
            ]));

            exit;
        }
    }

    public function ensureLoggedIn(): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: index.php?' . http_build_query([
                'route' => 'admin/login'
            ]));

            exit;
        }
    }
}
