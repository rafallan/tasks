<?php

namespace App\Actions;

use App\EventoHistoricoTarefa;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AtualizarTarefa
{
    /** @param array<string, mixed> $attributes */
    public function execute(Tarefa $tarefa, User $usuario, array $attributes): Tarefa
    {
        return DB::transaction(function () use ($attributes, $tarefa, $usuario): Tarefa {
            $changes = [];

            foreach (['titulo', 'descricao', 'prioridade', 'data_inicio', 'prazo', 'estimativa_minutos'] as $field) {
                if (array_key_exists($field, $attributes) && $tarefa->getAttribute($field) !== $attributes[$field]) {
                    $changes[$field] = [$tarefa->getAttribute($field), $attributes[$field]];
                }
            }

            $tarefa->fill($attributes);
            $tarefa->save();

            foreach ($changes as $field => [$old, $new]) {
                $event = $field === 'prazo' ? EventoHistoricoTarefa::PrazoAlterado : ($field === 'prioridade' ? EventoHistoricoTarefa::PrioridadeAlterada : EventoHistoricoTarefa::TarefaCriada);
                app(RegistrarHistoricoTarefa::class)->execute($tarefa, $usuario, $event, $field, ['value' => $old], ['value' => $new]);
            }

            return $tarefa;
        });
    }
}
