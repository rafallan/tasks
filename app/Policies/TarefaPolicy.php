<?php

namespace App\Policies;

use App\Models\ListaTarefa;
use App\Models\Tarefa;
use App\Models\User;

class TarefaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('tarefas.visualizar');
    }

    public function view(User $user, Tarefa $tarefa): bool
    {
        return $user->can('tarefas.visualizar') && ($tarefa->responsavel_id === $user->id || $user->gerenciaEquipe($tarefa->lista->equipe));
    }

    public function create(User $user, ListaTarefa $lista): bool
    {
        return $user->can('tarefas.criar') && $user->gerenciaEquipe($lista->equipe);
    }

    public function update(User $user, Tarefa $tarefa): bool
    {
        return $user->can('tarefas.editar') && $user->gerenciaEquipe($tarefa->lista->equipe);
    }

    public function changeStatus(User $user, Tarefa $tarefa): bool
    {
        return $user->can('tarefas.alterar_status') && ($tarefa->responsavel_id === $user->id || $user->gerenciaEquipe($tarefa->lista->equipe));
    }

    public function assign(User $user, Tarefa $tarefa): bool
    {
        return $user->can('tarefas.atribuir') && $user->gerenciaEquipe($tarefa->lista->equipe);
    }
}
