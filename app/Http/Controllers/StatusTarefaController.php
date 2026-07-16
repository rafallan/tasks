<?php

namespace App\Http\Controllers;

use App\Actions\AlterarStatusTarefa;
use App\Http\Requests\AlterarStatusTarefaRequest;
use App\Models\Tarefa;
use App\StatusTarefa;
use Illuminate\Http\RedirectResponse;

class StatusTarefaController extends Controller
{
    public function __invoke(AlterarStatusTarefaRequest $request, Tarefa $tarefa, AlterarStatusTarefa $alterarStatus): RedirectResponse
    {
        $this->authorize('changeStatus', $tarefa);
        $alterarStatus->execute($tarefa, $request->user(), StatusTarefa::from($request->string('status')->toString()));

        return back()->with('success', 'Status da tarefa atualizado.');
    }
}
