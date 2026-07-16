<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembroEquipeRequest;
use App\Models\Equipe;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class MembroEquipeController extends Controller
{
    public function store(StoreMembroEquipeRequest $request, Equipe $equipe): RedirectResponse
    {
        $this->authorize('manageMembers', $equipe);
        $membro = User::query()->findOrFail($request->integer('user_id'));

        if (! $membro->ativo) {
            throw ValidationException::withMessages(['user_id' => 'Usuários inativos não podem participar de equipes.']);
        }

        $equipe->usuarios()->syncWithoutDetaching([
            $membro->id => ['tipo_participacao' => $request->string('tipo_participacao')->toString(), 'ativo' => true],
        ]);

        return back()->with('success', 'Membro atualizado com sucesso.');
    }
}
