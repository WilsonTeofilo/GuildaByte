<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'min:2', 'max:255'],
            'email'         => ['required', 'email:rfc,dns', 'unique:users,email', 'max:255'],
            'password'      => ['required', 'min:8', 'confirmed'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'business_type' => ['nullable', 'string', 'max:100'],
            'business_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Informe seu nome completo.',
            'email.required'    => 'Informe um e-mail válido.',
            'email.unique'      => 'Este e-mail já está cadastrado.',
            'email.email'       => 'Formato de e-mail inválido.',
            'password.required' => 'Crie uma senha de acesso.',
            'password.min'      => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed'=> 'As senhas não conferem.',
        ];
    }

    /** Sanitização antes de validar — bloqueia XSS em qualquer campo de texto */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'          => strip_tags(trim($this->name ?? '')),
            'business_name' => strip_tags(trim($this->business_name ?? '')),
            'business_type' => strip_tags(trim($this->business_type ?? '')),
            'phone'         => preg_replace('/[^0-9+\-() ]/', '', $this->phone ?? ''),
            'email'         => strtolower(trim($this->email ?? '')),
        ]);
    }
}
