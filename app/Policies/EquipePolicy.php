<?php

namespace App\Policies;

use App\Models\Equipe;
use App\Models\User;

class EquipePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('equipes.visualizar');
    }

    public function view(User $user, Equipe $equipe): bool
    {
        return $user->can('equipes.visualizar') && $user->participaDaEquipe($equipe);
    }

    public function create(User $user): bool
    {
        return $user->can('equipes.criar');
    }

    public function update(User $user, Equipe $equipe): bool
    {
        return $user->can('equipes.editar') && $user->gerenciaEquipe($equipe);
    }

    public function manageMembers(User $user, Equipe $equipe): bool
    {
        return $user->can('equipes.gerenciar_membros') && $user->gerenciaEquipe($equipe);
    }
}
