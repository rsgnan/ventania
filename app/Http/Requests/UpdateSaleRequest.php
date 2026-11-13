<?php

namespace App\Http\Requests;

use App\Enums\SaleStatus;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateSaleRequest extends StoreSaleRequest
{
    /**
     * Herda as regras de StoreSaleRequest e adiciona o controle de transição de status.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'status' => ['required', Rule::enum(SaleStatus::class)],
        ];
    }

    /**
     * Garante na camada de Request que vendas Concluídas ou Canceladas não possam ser alteradas.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (! $this->route('sale')?->status->isMutable()) {
                    $validator->errors()->add('status', __('sales.immutable_sale'));
                }
            },
        ];
    }
}
