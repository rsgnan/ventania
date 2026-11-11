<?php

namespace App\Http\Requests;

use App\Enums\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determina se o usuário logado tem permissão para realizar esta requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para criação (e base para atualização) de Produtos e Kits.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($this->route('product'))],
            'type' => ['required', Rule::enum(ProductType::class)],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'components' => ['required_if:type,kit', 'nullable', 'array', 'min:1'],
            'components.*.product_id' => [
                'required_if:type,kit',
                'integer',
                'distinct',
                Rule::exists('products', 'id')->where('type', ProductType::Simple->value),
            ],
            'components.*.quantity' => ['required_if:type,kit', 'integer', 'min:1'],
        ];
    }
}
