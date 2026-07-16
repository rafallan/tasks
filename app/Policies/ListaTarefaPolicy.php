<?php

namespace App\Policies;

use App\Models\Equipe;
use App\Models\ListaTarefa;
use App\Models\User;

class ListaTarefaPolicy
{
    public function view(User $user, ListaTarefa $lista): bool
    {
        return $user->can('listas.visualizar') && $user->participaDaEquipe($lista->equipe);
    }

    public function create(User $user, Equipe $equipe): bool
    {
        return $user->can('listas.criar') && $user->gerenciaEquipe($equipe);
    }

    public function update(User $user, ListaTarefa $lista): bool
    {
        return $user->can('listas.editar') && $user->gerenciaEquipe($lista->equipe);
    }
}
