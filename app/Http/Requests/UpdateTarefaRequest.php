<?php

namespace App\Http\Requests;

use App\PrioridadeTarefa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateTarefaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('tarefas.editar') ?? false;
    }

    /** @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'], 'descricao' => ['nullable', 'string'],
            'prioridade' => ['required', Rule::enum(PrioridadeTarefa::class)], 'data_inicio' => ['nullable', 'date'],
            'prazo' => ['nullable', 'date'], 'estimativa_minutos' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /** @return array<int, \Closure(Validator): void> */
    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($this->filled('data_inicio') && $this->filled('prazo') && $this->date('prazo')->isBefore($this->date('data_inicio'))) {
                $validator->errors()->add('prazo', 'O prazo deve ser igual ou posterior à data de início.');
            }
        }];
    }
}
