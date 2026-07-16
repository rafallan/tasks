<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function (): void {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('admin can access user and role management', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $this->actingAs($admin)
        ->get(route('admin.usuarios.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Admin/Users/Index')
            ->where('auth.isAdmin', true));

    $this->actingAs($admin)
        ->get(route('admin.papeis.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Admin/Roles/Index'));
});

test('gestor cannot access administrative management', function () {
    $gestor = User::factory()->create();
    $gestor->assignRole('Gestor');

    $this->actingAs($gestor)
        ->get(route('admin.usuarios.index'))
        ->assertForbidden();

    $this->actingAs($gestor)
        ->get(route('admin.papeis.index'))
        ->assertForbidden();

    $this->actingAs($gestor)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('auth.isAdmin', false));
});

test('database seeder creates a verified administrator account', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();

    expect($admin->name)->toBe('Admin')
        ->and($admin->ativo)->toBeTrue()
        ->and($admin->email_verified_at)->not->toBeNull()
        ->and($admin->hasRole('Admin'))->toBeTrue();
});

test('admin can create a user with a role', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $this->actingAs($admin)
        ->post(route('admin.usuarios.store'), [
            'name' => 'Novo Gestor',
            'email' => 'novo.gestor@example.com',
            'password' => 'password123',
            'ativo' => true,
            'roles' => ['Gestor'],
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $user = User::query()->where('email', 'novo.gestor@example.com')->firstOrFail();

    expect($user->ativo)->toBeTrue();
    expect($user->hasRole('Gestor'))->toBeTrue();
});
