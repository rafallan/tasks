<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\StatusTarefa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $tarefas = $this->tarefasVisiveis($request);

        return Inertia::render('Dashboard', [
            'resumo' => [
                'pendentes' => (clone $tarefas)->where('status', StatusTarefa::Pendente->value)->count(),
                'em_andamento' => (clone $tarefas)->where('status', StatusTarefa::EmAndamento->value)->count(),
                'bloqueadas' => (clone $tarefas)->where('status', StatusTarefa::Bloqueada->value)->count(),
                'concluidas' => (clone $tarefas)->where('status', StatusTarefa::Concluida->value)->count(),
                'atrasadas' => (clone $tarefas)->whereNotIn('status', [StatusTarefa::Concluida->value, StatusTarefa::Cancelada->value])->where('prazo', '<', now())->count(),
            ],
            'tarefasRecentes' => $tarefas->with(['lista.equipe', 'responsavel'])->latest()->limit(8)->get(),
        ]);
    }

    /** @return Builder<Tarefa> */
    private function tarefasVisiveis(Request $request): Builder
    {
        $usuario = $request->user();
        $query = Tarefa::query();

        if ($usuario->hasRole('Admin')) {
            return $query;
        }

        $equipesGerenciadas = $usuario->equipes()
            ->wherePivot('tipo_participacao', 'gestor')
            ->wherePivot('ativo', true)
            ->pluck('equipes.id');

        return $query->where(function ($query) use ($equipesGerenciadas, $usuario): void {
            $query->where('responsavel_id', $usuario->id)
                ->orWhereHas('lista', fn ($lista) => $lista->whereIn('equipe_id', $equipesGerenciadas));
        });
    }
}
