<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('user'));
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'password' => ['nullable', 'string', Password::defaults()],
            'role' => ['required', Rule::enum(UserRole::class)],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * Trava de autoproteção executada após as regras básicas: impede auto-bloqueio ou auto-rebaixamento do Admin.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (
                    $this->user()->is($this->route('user'))
                    && (! $this->boolean('is_active') || $this->input('role') !== UserRole::Admin->value)
                ) {
                    $validator->errors()->add('role', __('users.self_lockout'));
                }
            },
        ];
    }
}
