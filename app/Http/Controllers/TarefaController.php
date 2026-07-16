<?php

namespace App\Http\Controllers;

use App\Actions\AtualizarTarefa;
use App\Actions\CriarTarefa;
use App\Http\Requests\StoreTarefaRequest;
use App\Http\Requests\UpdateTarefaRequest;
use App\Models\ListaTarefa;
use App\Models\Tarefa;
use App\Models\User;
use App\StatusTarefa;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TarefaController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Tarefa::class);
        $usuario = request()->user();
        $tarefas = Tarefa::query()->with(['lista.equipe', 'responsavel']);

        if (! $usuario->hasRole('Admin')) {
            $equipesGerenciadas = $usuario->equipes()->wherePivot('tipo_participacao', 'gestor')->wherePivot('ativo', true)->pluck('equipes.id');
            $tarefas->where(function ($query) use ($equipesGerenciadas, $usuario): void {
                $query->where('responsavel_id', $usuario->id)
                    ->orWhereHas('lista', fn ($lista) => $lista->whereIn('equipe_id', $equipesGerenciadas));
            });
        }

        if (request()->filled('status')) {
            $tarefas->where('status', request()->string('status')->toString());
        }

        return Inertia::render('Tarefas/Index', [
            'tarefas' => $tarefas->orderBy('prazo')->orderBy('ordem')->get(),
            'listas' => $this->listasParaCriacao(),
            'statusDisponiveis' => array_map(fn (StatusTarefa $status) => ['value' => $status->value, 'label' => str($status->value)->replace('_', ' ')->title()->toString()], StatusTarefa::cases()),
        ]);
    }

    public function store(StoreTarefaRequest $request, CriarTarefa $criarTarefa): RedirectResponse
    {
        $lista = ListaTarefa::query()->findOrFail($request->integer('lista_tarefa_id'));
        $this->authorize('create', [Tarefa::class, $lista]);
        $tarefa = $criarTarefa->execute($request->user(), $request->validated());

        return to_route('tarefas.show', $tarefa)->with('success', 'Tarefa criada com sucesso.');
    }

    public function show(Tarefa $tarefa): Response
    {
        $this->authorize('view', $tarefa);

        return Inertia::render('Tarefas/Show', [
            'tarefa' => $tarefa->load(['lista.equipe', 'criador:id,name', 'responsavel:id,name,email', 'itensChecklist.criador:id,name', 'comentarios.autor:id,name', 'historicos.usuario:id,name']),
        ]);
    }

    public function update(UpdateTarefaRequest $request, Tarefa $tarefa, AtualizarTarefa $atualizarTarefa): RedirectResponse
    {
        $this->authorize('update', $tarefa);
        $atualizarTarefa->execute($tarefa, $request->user(), $request->validated());

        return back()->with('success', 'Tarefa atualizada com sucesso.');
    }

    /** @return \Illuminate\Support\Collection<int, array{id: int, nome: string, equipe: string, responsaveis: \Illuminate\Support\Collection<int, array{id: int, name: string}>}> */
    private function listasParaCriacao()
    {
        $usuario = request()->user();
        $listas = ListaTarefa::query()
            ->with([
                'equipe' => fn ($query) => $query->select('id', 'nome')->with([
                    'usuarios' => fn ($usuarios) => $usuarios
                        ->select('users.id', 'users.name')
                        ->where((new User)->qualifyColumn('ativo'), true)
                        ->wherePivot('ativo', true),
                ]),
            ])
            ->whereNull('arquivada_em');

        if (! $usuario->hasRole('Admin')) {
            $equipesGerenciadas = $usuario->equipes()->wherePivot('tipo_participacao', 'gestor')->wherePivot('ativo', true)->pluck('equipes.id');
            $listas->whereIn('equipe_id', $equipesGerenciadas);
        }

        return $listas->orderBy('ordem')->get()->map(fn (ListaTarefa $lista) => [
            'id' => $lista->id,
            'nome' => $lista->nome,
            'equipe' => $lista->equipe->nome,
            'responsaveis' => $lista->equipe->usuarios
                ->map(fn (User $membro) => ['id' => $membro->id, 'name' => $membro->name])
                ->values(),
        ]);
    }
}
