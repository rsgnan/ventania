<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Pix = 'pix';
    case CreditCard = 'credit_card';
    case DebitCard = 'debit_card';
    case Cash = 'cash';

    public function label(): string
    {
        return match ($this) {
            self::Pix => 'PIX',
            self::CreditCard => 'Cartão de Crédito',
            self::DebitCard => 'Cartão de Débito',
            self::Cash => 'Dinheiro',
        };
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn(self $method) => [
                'value' => $method->value,
                'label' => $method->label(),
            ],
            self::cases(),
        );
    }
}
