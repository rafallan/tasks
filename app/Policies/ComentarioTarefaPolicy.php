<?php

namespace App\Policies;

use App\Models\ComentarioTarefa;
use App\Models\Tarefa;
use App\Models\User;

class ComentarioTarefaPolicy
{
    public function create(User $user, Tarefa $tarefa): bool
    {
        return $user->can('comentarios.criar') && $user->can('view', $tarefa);
    }

    public function update(User $user, ComentarioTarefa $comentario): bool
    {
        return $user->can('comentarios.editar') && $comentario->user_id === $user->id;
    }

    public function delete(User $user, ComentarioTarefa $comentario): bool
    {
        return $user->can('comentarios.excluir') && ($comentario->user_id === $user->id || $user->gerenciaEquipe($comentario->tarefa->lista->equipe));
    }
}
