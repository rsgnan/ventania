<?php

declare(strict_types=1); // Força checagem estrita de tipos no PHP 8.3+ (evita conversões implícitas silenciosas)

namespace App\Enums;

/**
 * Define os níveis de acesso (Roles) permitidos no Ventania.
 */
enum UserRole: string
{
    case Admin = 'admin';
    case User = 'user';

    /**
     * Retorna o nome amigável para exibição na interface.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::User => 'Usuário',
        };
    }

    /**
     * Método auxiliar para retornar todas as opções formatadas para componentes de <Select> no Frontend.
     *
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn(self $role) => [
                'value' => $role->value,
                'label' => $role->label(),
            ],
            self::cases()
        );
    }
}
