<?php

namespace App\Actions;

use App\EventoHistoricoTarefa;
use App\Models\ListaTarefa;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CriarTarefa
{
    /** @param array<string, mixed> $attributes */
    public function execute(User $usuario, array $attributes): Tarefa
    {
        /** @var ListaTarefa $lista */
        $lista = ListaTarefa::query()->whereKey($attributes['lista_tarefa_id'])->firstOrFail();

        if ($lista->arquivada_em !== null) {
            throw ValidationException::withMessages(['lista_tarefa_id' => 'Não é possível criar tarefas em uma lista arquivada.']);
        }

        $this->validarResponsavel($lista, $attributes['responsavel_id'] ?? null);

        return DB::transaction(function () use ($attributes, $lista, $usuario): Tarefa {
            $tarefa = new Tarefa($attributes);
            $tarefa->criada_por = $usuario->id;
            $tarefa->ordem = (int) $lista->tarefas()->max('ordem') + 1;
            $tarefa->save();

            app(RegistrarHistoricoTarefa::class)->execute($tarefa, $usuario, EventoHistoricoTarefa::TarefaCriada);

            return $tarefa;
        });
    }

    private function validarResponsavel(ListaTarefa $lista, ?int $responsavelId): void
    {
        if ($responsavelId === null) {
            return;
        }

        $isMembroAtivo = $lista->equipe->usuarios()
            ->whereKey($responsavelId)
            ->wherePivot('ativo', true)
            ->where((new User)->qualifyColumn('ativo'), true)
            ->exists();

        if (! $isMembroAtivo) {
            throw ValidationException::withMessages(['responsavel_id' => 'O responsável precisa ser membro ativo da equipe.']);
        }
    }
}
