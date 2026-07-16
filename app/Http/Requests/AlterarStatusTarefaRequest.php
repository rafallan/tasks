<?php

namespace App\Http\Requests;

use App\StatusTarefa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlterarStatusTarefaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('tarefas.alterar_status') ?? false;
    }

    /** @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return ['status' => ['required', Rule::enum(StatusTarefa::class)]];
    }
}
