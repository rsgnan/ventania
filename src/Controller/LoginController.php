<?php

namespace App\Controller;

use App\Core\ViewController;

class LoginController extends ViewController
{
    public function logout(): void
    {
        $this->authService->logout();
        header('Location: index.php?' . http_build_query([
            'route' => 'admin/login'
        ]));
        return;
    }

    public function login(): void
    {
        if ($this->authService->isLoggedIn()) {
            header('Location: index.php?' . http_build_query(['route' => 'dashboard/index']));
            return;
        }

        $loginError = false;
        $username = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim((string) ($_POST['username'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            if (!empty($username) && !empty($password)) {
                $loginOk = $this->authService->handleLogin($username, $password);
                if ($loginOk) {
                    header('Location: index.php?' . http_build_query(['route' => 'dashboard/index']));
                    return;
                }

                $loginError = true;
            } else {
                $loginError = true;
            }
        }

        $this->renderStandalone('admin/login', [
            'loginError' => $loginError,
            'oldUsername' => $username
        ]);
    }
}
