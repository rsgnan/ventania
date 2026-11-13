<?php

namespace App\Enums;

/**
 * Define as direções possíveis de uma movimentação de estoque.
 */
enum StockMovementType: string
{
    case In = 'in';
    case Out = 'out';

    /**
     * Retorna o nome amigável para exibição na interface.
     */
    public function label(): string
    {
        return match ($this) {
            self::In => 'Entrada',
            self::Out => 'Saída',
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
