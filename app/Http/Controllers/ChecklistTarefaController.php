<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChecklistItemRequest;
use App\Http\Requests\ToggleChecklistItemRequest;
use App\Models\ItemChecklistTarefa;
use App\Models\Tarefa;
use Illuminate\Http\RedirectResponse;

class ChecklistTarefaController extends Controller
{
    public function store(StoreChecklistItemRequest $request, Tarefa $tarefa): RedirectResponse
    {
        $this->authorize('changeStatus', $tarefa);
        $item = $tarefa->itensChecklist()->make($request->validated());
        $item->criado_por = $request->user()->id;
        $item->ordem = (int) $tarefa->itensChecklist()->max('ordem') + 1;
        $item->save();

        return back()->with('success', 'Item adicionado ao checklist.');
    }

    public function update(ToggleChecklistItemRequest $request, Tarefa $tarefa, ItemChecklistTarefa $item): RedirectResponse
    {
        abort_unless($item->tarefa_id === $tarefa->id, 404);
        $this->authorize('changeStatus', $tarefa);
        $concluido = $request->boolean('concluido');
        $item->update([
            'concluido' => $concluido,
            'concluido_por' => $concluido ? $request->user()->id : null,
            'concluido_em' => $concluido ? now() : null,
        ]);

        return back();
    }
}
