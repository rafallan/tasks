<?php

namespace App\Http\Requests;

use App\TipoParticipacaoEquipe;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMembroEquipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('equipes.gerenciar_membros') ?? false;
    }

    /** @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return ['user_id' => ['required', 'integer', 'exists:users,id'], 'tipo_participacao' => ['required', Rule::enum(TipoParticipacaoEquipe::class)]];
    }
}
