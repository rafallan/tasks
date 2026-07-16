<?php

namespace App\Actions;

use App\EventoHistoricoTarefa;
use App\Models\Tarefa;
use App\Models\User;

class RegistrarHistoricoTarefa
{
    /**
     * @param array<string, mixed>|null $valorAnterior
     * @param array<string, mixed>|null $valorNovo
     * @param array<string, mixed>|null $metadados
     */
    public function execute(Tarefa $tarefa, ?User $usuario, EventoHistoricoTarefa $evento, ?string $campo = null, ?array $valorAnterior = null, ?array $valorNovo = null, ?array $metadados = null): void
    {
        $tarefa->historicos()->create([
            'user_id' => $usuario?->id,
            'evento' => $evento->value,
            'campo' => $campo,
            'valor_anterior' => $valorAnterior,
            'valor_novo' => $valorNovo,
            'metadados' => $metadados,
        ]);
    }
}
