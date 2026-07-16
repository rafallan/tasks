<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToggleChecklistItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('tarefas.alterar_status') ?? false;
    }

    /** @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return ['concluido' => ['required', 'boolean']];
    }
}
