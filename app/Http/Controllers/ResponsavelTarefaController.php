<?php

namespace App\Http\Controllers;

use App\Actions\RegistrarHistoricoTarefa;
use App\EventoHistoricoTarefa;
use App\Http\Requests\AtribuirTarefaRequest;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class ResponsavelTarefaController extends Controller
{
    public function __invoke(AtribuirTarefaRequest $request, Tarefa $tarefa, RegistrarHistoricoTarefa $registrarHistorico): RedirectResponse
    {
        $this->authorize('assign', $tarefa);
        $responsavelId = $request->input('responsavel_id');

        if ($responsavelId !== null && ! $tarefa->lista->equipe->usuarios()->whereKey($responsavelId)->wherePivot('ativo', true)->where((new User)->qualifyColumn('ativo'), true)->exists()) {
            throw ValidationException::withMessages(['responsavel_id' => 'O responsável precisa ser membro ativo da equipe.']);
        }

        $anterior = $tarefa->responsavel_id;
        $tarefa->update(['responsavel_id' => $responsavelId]);
        $registrarHistorico->execute($tarefa, $request->user(), EventoHistoricoTarefa::ResponsavelAlterado, 'responsavel_id', ['value' => $anterior], ['value' => $responsavelId]);

        return back()->with('success', 'Responsável atualizado.');
    }
}
