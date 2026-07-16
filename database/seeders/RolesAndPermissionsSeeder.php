<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'usuarios.visualizar', 'usuarios.criar', 'usuarios.editar', 'usuarios.excluir', 'papeis.gerenciar',
            'equipes.visualizar', 'equipes.criar', 'equipes.editar', 'equipes.excluir', 'equipes.gerenciar_membros',
            'listas.visualizar', 'listas.criar', 'listas.editar', 'listas.arquivar', 'listas.excluir',
            'tarefas.visualizar', 'tarefas.criar', 'tarefas.editar', 'tarefas.atribuir', 'tarefas.alterar_status', 'tarefas.concluir', 'tarefas.reabrir', 'tarefas.cancelar', 'tarefas.excluir',
            'comentarios.criar', 'comentarios.editar', 'comentarios.excluir', 'historicos.visualizar', 'relatorios.visualizar',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::findOrCreate('Admin', 'web');
        $gestor = Role::findOrCreate('Gestor', 'web');
        $user = Role::findOrCreate('User', 'web');

        $gestor->syncPermissions([
            'equipes.visualizar', 'equipes.criar', 'equipes.editar', 'equipes.gerenciar_membros',
            'listas.visualizar', 'listas.criar', 'listas.editar', 'listas.arquivar',
            'tarefas.visualizar', 'tarefas.criar', 'tarefas.editar', 'tarefas.atribuir', 'tarefas.alterar_status', 'tarefas.concluir', 'tarefas.reabrir', 'tarefas.cancelar',
            'comentarios.criar', 'comentarios.editar', 'comentarios.excluir', 'historicos.visualizar', 'relatorios.visualizar',
        ]);

        $user->syncPermissions(['equipes.visualizar', 'listas.visualizar', 'tarefas.visualizar', 'tarefas.alterar_status', 'tarefas.concluir', 'comentarios.criar', 'comentarios.editar']);
    }
}
