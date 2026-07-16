<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComentarioTarefaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('comentarios.criar') ?? false;
    }

    /** @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return ['comentario' => ['required', 'string', 'max:10000']];
    }
}
