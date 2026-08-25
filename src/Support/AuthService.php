<?php

namespace App\Support;

use PDO;

class AuthService
{
    public function __construct(private PDO $pdo) {}

    public function ensureSession()
    {
        if (session_id() === '') {
            session_start();
        }
    }

    public function logout()
    {
        $this->ensureSession();
        
        // Remove os dados do usuário da sessão
        unset(
            $_SESSION['user_id'],
            $_SESSION['user_name'],
            $_SESSION['user_role']
        );

        session_regenerate_id();
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
        if (empty($username)) return false;
        if (empty($password)) return false;

        // Busca o usuário pelo nome de usuário
        $stmt = $this->pdo->prepare('SELECT `id`, `name`, `password`, `role`, `is_active`
        FROM `users` 
        WHERE `username` = :username');
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $entry = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($entry)) {
            return false;
        }

        // Impede o acesso de usuários inativos
        if (!$entry['is_active']) {
            return false;
        }

        $hash = $entry['password'];
        $passwordOk = password_verify($password, $hash);

        if (empty($passwordOk)) {
            return false;
        }

        $this->ensureSession();

        // Armazena os dados necessários do usuário na sessão
        $_SESSION['user_id'] = $entry['id'];
        $_SESSION['user_name'] = $entry['name'];
        $_SESSION['user_role'] = $entry['role'];

        session_regenerate_id();

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

    public function ensureAdmin()
    {
        $this->ensureLoggedIn();

        // Restringe o acesso aos administradores
        if (!$this->isAdmin()) {
            header('Location: index.php?' . http_build_query([
                'route' => 'dashboard/index'
            ]));

            die();
        }
    }

    public function ensureLoggedIn()
    {
        $isLoggedIn = $this->isLoggedIn();
        if (empty($isLoggedIn)) {
            header('Location: index.php?' . http_build_query(['route' => 'admin/login']));
            die();
        }
    }
}
