<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListaTarefaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('listas.criar') ?? false;
    }

    /** @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return ['equipe_id' => ['required', 'integer', 'exists:equipes,id'], 'nome' => ['required', 'string', 'max:150'], 'descricao' => ['nullable', 'string'], 'cor' => ['nullable', 'string', 'max:20']];
    }
}
