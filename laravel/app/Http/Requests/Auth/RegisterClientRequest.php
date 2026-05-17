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
            'name'          => ['required', 'string', 'min:2', 'max:45'],
            'email'         => ['required', 'email:rfc', 'unique:users,email', 'max:70'],
            'password'      => ['required', 'min:8', 'max:30', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'phone'         => ['nullable', 'string', 'max:20', 'regex:/^[\d\+\-\(\) ]{10,20}$/'],
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
            'password.mixed'    => 'A senha deve conter letras maiúsculas e minúsculas.',
            'password.numbers'  => 'A senha deve conter pelo menos um número.',
            'password.symbols'  => 'A senha deve conter pelo menos um caractere especial (!@#$%).',
            'password.max'      => 'A senha não pode ter mais que 30 caracteres.',
            'name.max'          => 'O nome não pode ter mais que 45 caracteres.',
            'email.max'         => 'O e-mail não pode ter mais que 70 caracteres.',
            'phone.max'         => 'O telefone não pode ter mais que 20 caracteres.',
            'phone.regex'       => 'O formato do telefone é inválido.',
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
