<?php

namespace App\Actions;

use App\EventoHistoricoTarefa;
use App\Models\Tarefa;
use App\Models\User;
use App\StatusTarefa;
use Illuminate\Validation\ValidationException;

class AlterarStatusTarefa
{
    public function execute(Tarefa $tarefa, User $usuario, StatusTarefa $novoStatus): Tarefa
    {
        $atual = $tarefa->status;

        if (! in_array($novoStatus, $this->transicoesPermitidas($atual), true)) {
            throw ValidationException::withMessages(['status' => 'Esta transição de status não é permitida.']);
        }

        if (in_array($novoStatus, [StatusTarefa::EmAndamento, StatusTarefa::Pendente], true) && in_array($atual, [StatusTarefa::Concluida, StatusTarefa::Cancelada], true) && ! $usuario->hasRole('Admin') && ! $usuario->gerenciaEquipe($tarefa->lista->equipe)) {
            throw ValidationException::withMessages(['status' => 'Somente gestor ou administrador pode reabrir tarefas.']);
        }

        $tarefa->status = $novoStatus;
        $tarefa->concluida_em = $novoStatus === StatusTarefa::Concluida ? now() : null;
        $tarefa->save();

        $event = $novoStatus === StatusTarefa::Concluida ? EventoHistoricoTarefa::TarefaConcluida : ($atual === StatusTarefa::Concluida ? EventoHistoricoTarefa::TarefaReaberta : EventoHistoricoTarefa::StatusAlterado);
        app(RegistrarHistoricoTarefa::class)->execute($tarefa, $usuario, $event, 'status', ['value' => $atual->value], ['value' => $novoStatus->value]);

        return $tarefa;
    }

    /** @return array<StatusTarefa> */
    private function transicoesPermitidas(StatusTarefa $status): array
    {
        return match ($status) {
            StatusTarefa::Pendente => [StatusTarefa::EmAndamento, StatusTarefa::Cancelada],
            StatusTarefa::EmAndamento => [StatusTarefa::Bloqueada, StatusTarefa::Concluida, StatusTarefa::Cancelada],
            StatusTarefa::Bloqueada => [StatusTarefa::EmAndamento, StatusTarefa::Cancelada],
            StatusTarefa::Concluida => [StatusTarefa::EmAndamento],
            StatusTarefa::Cancelada => [StatusTarefa::Pendente],
        };
    }
}
