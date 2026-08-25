<?php

namespace App\Repository;

use PDO;
use App\Model\UserModel;

class UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM `users`
            ORDER BY `name` ASC'
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, UserModel::class);
    }

    public function getById(int $id): ?UserModel
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM `users`
            WHERE `id` = :id'
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, UserModel::class);

        $entry = $stmt->fetch();

        return $entry !== false ? $entry : null;
    }

    public function create(
        string $name,
        string $username,
        string $email,
        string $password,
        string $role,
        int $isActive
    ): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO `users`
            (`name`, `username`, `email`, `password`, `role`, `is_active`)
            VALUES
            (:name, :username, :email, :password, :role, :is_active)'
        );

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        $stmt->bindValue(':is_active', $isActive, PDO::PARAM_INT);

        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function update(
        int $id,
        string $name,
        string $username,
        string $email,
        string $role,
        int $isActive
    ): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE `users`
            SET
                `name` = :name,
                `username` = :username,
                `email` = :email,
                `role` = :role,
                `is_active` = :is_active
            WHERE `id` = :id'
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        $stmt->bindValue(':is_active', $isActive, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updatePassword(
        int $id,
        string $password
    ): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE `users`
            SET `password` = :password
            WHERE `id` = :id'
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function usernameExists(string $username): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1
            FROM `users`
            WHERE `username` = :username
            LIMIT 1'
        );

        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    public function usernameExistsForOtherUser(
        string $username,
        int $userId
    ): bool {
        $stmt = $this->pdo->prepare(
            'SELECT 1
            FROM `users`
            WHERE `username` = :username
            AND `id` != :id
            LIMIT 1'
        );

        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1
            FROM `users`
            WHERE `email` = :email
            LIMIT 1'
        );

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    public function emailExistsForOtherUser(
        string $email,
        int $userId
    ): bool {
        $stmt = $this->pdo->prepare(
            'SELECT 1
            FROM `users`
            WHERE `email` = :email
            AND `id` != :id
            LIMIT 1'
        );

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }
}
