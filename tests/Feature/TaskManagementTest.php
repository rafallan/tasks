<?php

use App\Models\Equipe;
use App\Models\ListaTarefa;
use App\Models\Tarefa;
use App\Models\User;
use App\TipoParticipacaoEquipe;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('a manager can create a task for an active team member', function () {
    $manager = User::factory()->create();
    $manager->assignRole('Gestor');
    $member = User::factory()->create();
    $member->assignRole('User');
    $equipe = Equipe::factory()->create(['criado_por' => $manager->id]);
    $equipe->usuarios()->attach($manager, ['tipo_participacao' => TipoParticipacaoEquipe::Gestor->value, 'ativo' => true]);
    $equipe->usuarios()->attach($member, ['tipo_participacao' => TipoParticipacaoEquipe::Membro->value, 'ativo' => true]);
    $lista = ListaTarefa::factory()->create(['equipe_id' => $equipe->id, 'criada_por' => $manager->id]);

    $response = $this->actingAs($manager)->post(route('tarefas.store'), [
        'lista_tarefa_id' => $lista->id,
        'titulo' => 'Preparar apresentação',
        'prioridade' => 'alta',
        'responsavel_id' => $member->id,
    ]);

    $tarefa = Tarefa::query()->firstOrFail();

    $response->assertRedirect(route('tarefas.show', $tarefa));
    expect($tarefa->responsavel_id)->toBe($member->id)
        ->and($tarefa->historicos)->toHaveCount(1);
});

test('an assignee completes their task and the completion is audited', function () {
    $user = User::factory()->create();
    $user->assignRole('User');
    $equipe = Equipe::factory()->create();
    $equipe->usuarios()->attach($user, ['tipo_participacao' => TipoParticipacaoEquipe::Membro->value, 'ativo' => true]);
    $lista = ListaTarefa::factory()->create(['equipe_id' => $equipe->id]);
    $tarefa = Tarefa::factory()->create(['lista_tarefa_id' => $lista->id, 'responsavel_id' => $user->id, 'status' => 'em_andamento']);

    $this->actingAs($user)->patch(route('tarefas.status.update', $tarefa), ['status' => 'concluida'])->assertRedirect();

    $tarefa->refresh();
    expect($tarefa->status->value)->toBe('concluida')
        ->and($tarefa->concluida_em)->not->toBeNull()
        ->and($tarefa->historicos()->where('evento', 'tarefa_concluida')->exists())->toBeTrue();
});

test('a manager cannot assign a task to a user outside their team', function () {
    $manager = User::factory()->create();
    $manager->assignRole('Gestor');
    $outsider = User::factory()->create();
    $equipe = Equipe::factory()->create(['criado_por' => $manager->id]);
    $equipe->usuarios()->attach($manager, ['tipo_participacao' => TipoParticipacaoEquipe::Gestor->value, 'ativo' => true]);
    $lista = ListaTarefa::factory()->create(['equipe_id' => $equipe->id, 'criada_por' => $manager->id]);

    $this->actingAs($manager)->post(route('tarefas.store'), [
        'lista_tarefa_id' => $lista->id,
        'titulo' => 'Tarefa protegida',
        'prioridade' => 'media',
        'responsavel_id' => $outsider->id,
    ])->assertSessionHasErrors('responsavel_id');
});
