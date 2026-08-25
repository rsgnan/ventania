<?php

namespace App\Controller;

use App\Core\ViewController;
use App\Repository\UserRepository;
use App\Support\AuthService;
use App\Support\ActivityLogService;

class UserController extends ViewController
{
    public function __construct(
        AuthService $authService,
        private UserRepository $userRepository,
        private ActivityLogService $activityLogService
    ) {
        parent::__construct($authService);
    }

    public function index()
    {
        $users = $this->userRepository->getAll();

        $this->render('users/index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $username = strtolower(trim($_POST['username'] ?? ''));
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'operator';
            $isActive = $_POST['is_active'] ?? '1';

            $this->validateFields(
                $name,
                $username,
                $email,
                $role,
                $isActive,
                $errors
            );

            // A senha é obrigatória no cadastro
            if ($password === '') {
                $errors[] = 'Informe a senha.';
            } elseif (strlen($password) < 6) {
                $errors[] = 'A senha deve ter pelo menos 6 caracteres.';
            }

            if (
                $username !== ''
                && $this->userRepository->usernameExists($username)
            ) {
                $errors[] = 'Este nome de usuário já está cadastrado.';
            }

            if (
                $email !== ''
                && filter_var($email, FILTER_VALIDATE_EMAIL)
                && $this->userRepository->emailExists($email)
            ) {
                $errors[] = 'Este e-mail já está cadastrado.';
            }

            if (empty($errors)) {
                $isActive = (int) $isActive;
                $passwordHash = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $userId = $this->userRepository->create(
                    $name,
                    $username,
                    $email,
                    $passwordHash,
                    $role,
                    $isActive
                );

                $this->activityLogService->log(
                    'user',
                    $userId,
                    $name,
                    'create'
                );

                header('Location: index.php?' . http_build_query([
                    'route' => 'users/index'
                ]));

                die();
            }
        }

        $this->render('users/create', [
            'errors' => $errors
        ]);
    }

    public function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $errors = [];

        $user = $this->userRepository->getById($id);

        if (!$user) {
            header('Location: index.php?' . http_build_query([
                'route' => 'users/index'
            ]));

            die();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $username = strtolower(trim($_POST['username'] ?? ''));
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'operator';
            $isActive = $_POST['is_active'] ?? '1';

            $this->validateFields(
                $name,
                $username,
                $email,
                $role,
                $isActive,
                $errors
            );

            if ($password !== '' && strlen($password) < 6) {
                $errors[] = 'A senha deve ter pelo menos 6 caracteres.';
            }

            if (
                $username !== ''
                && $this->userRepository->usernameExistsForOtherUser(
                    $username,
                    $id
                )
            ) {
                $errors[] = 'Este nome de usuário já está cadastrado.';
            }

            if (
                $email !== ''
                && filter_var($email, FILTER_VALIDATE_EMAIL)
                && $this->userRepository->emailExistsForOtherUser(
                    $email,
                    $id
                )
            ) {
                $errors[] = 'Este e-mail já está cadastrado.';
            }

            if (empty($errors)) {
                $isActive = (int) $isActive;

                $this->userRepository->update(
                    $id,
                    $name,
                    $username,
                    $email,
                    $role,
                    $isActive
                );

                // A senha só é alterada quando um novo valor é infomado
                if ($password !== '') {
                    $passwordHash = password_hash(
                        $password,
                        PASSWORD_DEFAULT
                    );

                    $this->userRepository->updatePassword(
                        $id,
                        $passwordHash
                    );
                }

                $this->activityLogService->log(
                    'user',
                    $id,
                    $name,
                    'update'
                );

                header('Location: index.php?' . http_build_query([
                    'route' => 'users/index'
                ]));

                die();
            }

            $user->name = $name;
            $user->username = $username;
            $user->email = $email;
            $user->role = $role;
            $user->is_active = (int) $isActive;
        }

        $this->render('users/edit', [
            'user' => $user,
            'errors' => $errors
        ]);
    }

    private function validateFields(
        string $name,
        string $username,
        string $email,
        string $role,
        string $isActive,
        array &$errors
    ): void {
        if ($name === '') {
            $errors[] = 'Preencha o nome corretamente.';
        }

        if ($username === '') {
            $errors[] = 'Informe o nome de usuário.';
        } elseif (!preg_match('/^[a-z0-9_]+$/', $username)) {
            $errors[] = 'O nome de usuário deve conter apenas letras minúsculas, números e sublinhado.';
        }

        if ($email === '') {
            $errors[] = 'Informe o e-mail.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Informe um e-mail válido.';
        }

        if (!in_array($role, ['admin', 'operator'], true)) {
            $errors[] = 'Perfil de acesso inválido.';
        }

        if (!in_array($isActive, ['0', '1'], true)) {
            $errors[] = 'Status da conta inválido.';
        }
    }
}
