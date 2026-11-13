<?php

namespace App\Enums;

/**
 * Define os tipos de produto suportados pelo catálogo e controle de estoque.
 */
enum ProductType: string
{
    case Simple = 'simple';
    case Kit = 'kit';

    /**
     * Retorna o nome amigável para exibição na interface.
     */
    public function label(): string
    {
        return match ($this) {
            self::Simple => 'Simples',
            self::Kit => 'Kit',
        };
    }

    /**
     * Método auxiliar para retornar todas as opções formatadas para componentes do Frontend.
     *
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn(self $type) => [
                'value' => $type->value,
                'label' => $type->label(),
            ],
            self::cases(),
        );
    }
}
