<?php

namespace App\Http\Controllers;

use App\Actions\RegistrarHistoricoTarefa;
use App\EventoHistoricoTarefa;
use App\Http\Requests\StoreComentarioTarefaRequest;
use App\Models\Tarefa;
use Illuminate\Http\RedirectResponse;

class ComentarioTarefaController extends Controller
{
    public function store(StoreComentarioTarefaRequest $request, Tarefa $tarefa, RegistrarHistoricoTarefa $registrarHistorico): RedirectResponse
    {
        $this->authorize('create', [\App\Models\ComentarioTarefa::class, $tarefa]);
        $comentario = $tarefa->comentarios()->make($request->validated());
        $comentario->user_id = $request->user()->id;
        $comentario->save();
        $registrarHistorico->execute($tarefa, $request->user(), EventoHistoricoTarefa::ComentarioAdicionado, metadados: ['comentario_id' => $comentario->id]);

        return back()->with('success', 'Comentário adicionado.');
    }
}
