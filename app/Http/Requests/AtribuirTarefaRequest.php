<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtribuirTarefaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('tarefas.atribuir') ?? false;
    }

    /** @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return ['responsavel_id' => ['nullable', 'integer', 'exists:users,id']];
    }
}
