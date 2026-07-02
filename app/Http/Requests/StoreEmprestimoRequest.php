<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmprestimoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'livro_id' => ['required', 'integer', 'exists:livros,id'],
            'user_id' => [
                'required', 'integer',
                Rule::exists('users', 'id')->where('role', UserRole::Leitor->value),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'Selecione um leitor válido.',
        ];
    }
}
