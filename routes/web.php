<?php

use App\Http\Controllers\ChecklistTarefaController;
use App\Http\Controllers\ComentarioTarefaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\ListaTarefaController;
use App\Http\Controllers\MembroEquipeController;
use App\Http\Controllers\ResponsavelTarefaController;
use App\Http\Controllers\StatusTarefaController;
use App\Http\Controllers\TarefaController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('equipes', EquipeController::class)->only(['index', 'store', 'show', 'update']);
    Route::post('equipes/{equipe}/membros', [MembroEquipeController::class, 'store'])->name('equipes.membros.store');
    Route::resource('listas-tarefas', ListaTarefaController::class)->only(['store', 'update']);
    Route::resource('tarefas', TarefaController::class)->only(['index', 'store', 'show', 'update']);
    Route::patch('tarefas/{tarefa}/status', StatusTarefaController::class)->name('tarefas.status.update');
    Route::patch('tarefas/{tarefa}/responsavel', ResponsavelTarefaController::class)->name('tarefas.responsavel.update');
    Route::post('tarefas/{tarefa}/checklist', [ChecklistTarefaController::class, 'store'])->name('tarefas.checklist.store');
    Route::patch('tarefas/{tarefa}/checklist/{item}', [ChecklistTarefaController::class, 'update'])->name('tarefas.checklist.update');
    Route::post('tarefas/{tarefa}/comentarios', [ComentarioTarefaController::class, 'store'])->name('tarefas.comentarios.store');
});

require __DIR__.'/settings.php';
