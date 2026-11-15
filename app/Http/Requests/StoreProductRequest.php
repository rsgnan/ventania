<?php

namespace App\Http\Requests;

use App\Enums\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($this->route('product'))],
            'sku' => ['nullable', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($this->route('product'))],
            'type' => ['required', Rule::enum(ProductType::class)],
            'price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['required', 'boolean'],
            'components' => ['exclude_unless:type,kit', 'required', 'array', 'min:1'],
            'components.*.product_id' => [
                'exclude_unless:type,kit',
                'required',
                'integer',
                'distinct',
                Rule::exists('products', 'id')->where('type', ProductType::Simple->value),
            ],
            'components.*.quantity' => ['exclude_unless:type,kit', 'required', 'integer', 'min:1'],
        ];
    }
}
