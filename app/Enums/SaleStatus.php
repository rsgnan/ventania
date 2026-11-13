<?php

namespace App\Enums;

enum SaleStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Completed => 'Concluída',
            self::Cancelled => 'Cancelada',
        };
    }

    /**
     * Define se a venda ainda permite edições ou transição de status (apenas enquanto Pendente).
     */
    public function isMutable(): bool
    {
        return $this === self::Pending;
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn(self $status) => [
                'value' => $status->value,
                'label' => $status->label(),
            ],
            self::cases(),
        );
    }
}
