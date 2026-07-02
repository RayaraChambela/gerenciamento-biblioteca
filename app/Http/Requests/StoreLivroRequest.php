<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLivroRequest extends FormRequest
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
            'titulo' => ['required', 'string', 'max:255'],
            'autor' => ['required', 'string', 'max:255'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'isbn' => ['nullable', 'string', 'max:20', 'unique:livros,isbn'],
            'ano_publicacao' => ['nullable', 'integer', 'min:1500', 'max:'.(date('Y') + 1)],
            'quantidade_total' => ['required', 'integer', 'min:1'],
        ];
    }
}
