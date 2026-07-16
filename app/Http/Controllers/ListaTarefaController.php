<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListaTarefaRequest;
use App\Http\Requests\UpdateListaTarefaRequest;
use App\Models\Equipe;
use App\Models\ListaTarefa;
use Illuminate\Http\RedirectResponse;

class ListaTarefaController extends Controller
{
    public function store(StoreListaTarefaRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $equipe = Equipe::query()->whereKey($attributes['equipe_id'])->firstOrFail();
        $this->authorize('create', [ListaTarefa::class, $equipe]);

        $lista = new ListaTarefa($attributes);
        $lista->equipe_id = $equipe->id;
        $lista->criada_por = $request->user()->id;
        $lista->ordem = (int) $equipe->listasTarefas()->max('ordem') + 1;
        $lista->save();

        return to_route('equipes.show', $equipe)->with('success', 'Lista criada com sucesso.');
    }

    public function update(UpdateListaTarefaRequest $request, ListaTarefa $listasTarefa): RedirectResponse
    {
        $this->authorize('update', $listasTarefa);
        $attributes = $request->validated();
        $arquivada = $attributes['arquivada'] ?? null;
        unset($attributes['arquivada']);
        $listasTarefa->fill($attributes);

        if ($arquivada !== null) {
            $listasTarefa->arquivada_em = $arquivada ? now() : null;
        }

        $listasTarefa->save();

        return back()->with('success', 'Lista atualizada com sucesso.');
    }
}
