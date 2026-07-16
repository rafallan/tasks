<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('equipes.editar') ?? false;
    }

    /** @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return ['nome' => ['required', 'string', 'max:150'], 'descricao' => ['nullable', 'string'], 'ativo' => ['boolean']];
    }
}
