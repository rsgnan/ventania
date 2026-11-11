<?php

namespace App\Enums;

enum ProductType: string
{
    case Simple = 'simple';
    case Kit = 'kit';

    public function label(): string
    {
        return match ($this) {
            self::Simple => 'Simples',
            self::Kit => 'Kit',
        };
    }

    /**
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
