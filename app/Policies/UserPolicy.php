<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Apenas administradores podem acessar a listagem de usuários.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Apenas administradores podem cadastrar novos usuários.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Apenas administradores podem atualizar dados, role ou status de usuários.
     */
    public function update(User $user, User $target): bool
    {
        return $user->isAdmin();
    }

    /**
     * Regra inegociável: proibida a exclusão de usuários por qualquer perfil.
     */
    public function delete(User $user, User $target): bool
    {
        return false;
    }
}
