<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipeRequest;
use App\Http\Requests\UpdateEquipeRequest;
use App\Models\Equipe;
use App\Models\User;
use App\TipoParticipacaoEquipe;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class EquipeController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Equipe::class);
        $usuario = request()->user();
        $equipes = Equipe::query()->withCount(['usuarios', 'listasTarefas']);

        if (! $usuario->hasRole('Admin')) {
            $equipes->whereHas('usuarios', fn ($query) => $query->whereKey($usuario));
        }

        return Inertia::render('Equipes/Index', ['equipes' => $equipes->orderBy('nome')->get()]);
    }

    public function store(StoreEquipeRequest $request): RedirectResponse
    {
        $this->authorize('create', Equipe::class);
        $equipe = new Equipe($request->validated());
        $equipe->criado_por = $request->user()->id;
        $equipe->save();
        $equipe->usuarios()->attach($request->user(), ['tipo_participacao' => TipoParticipacaoEquipe::Gestor->value, 'ativo' => true]);

        return to_route('equipes.show', $equipe)->with('success', 'Equipe criada com sucesso.');
    }

    public function show(Equipe $equipe): Response
    {
        $this->authorize('view', $equipe);

        return Inertia::render('Equipes/Show', ['equipe' => $equipe->load(['usuarios:id,name,email', 'listasTarefas' => fn ($query) => $query->orderBy('ordem')])]);
    }

    public function update(UpdateEquipeRequest $request, Equipe $equipe): RedirectResponse
    {
        $this->authorize('update', $equipe);
        $equipe->update($request->validated());

        return back()->with('success', 'Equipe atualizada com sucesso.');
    }
}
