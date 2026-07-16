<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::query()->with('roles:id,name')->orderBy('name')->get(['id', 'name', 'email', 'ativo']),
            'roles' => Role::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $user = User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => $attributes['password'],
            'ativo' => $attributes['ativo'],
        ]);
        $user->syncRoles($attributes['roles']);

        return back()->with('success', 'Usuário criado com sucesso.');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $attributes = $request->validated();

        if ($user->is($request->user()) && (! $attributes['ativo'] || ! in_array('Admin', $attributes['roles'], true))) {
            throw ValidationException::withMessages([
                'roles' => 'Você não pode remover seu próprio acesso administrativo.',
            ]);
        }

        $userAttributes = [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'ativo' => $attributes['ativo'],
        ];

        if ($attributes['password'] !== null) {
            $userAttributes['password'] = $attributes['password'];
        }

        $user->update($userAttributes);
        $user->syncRoles($attributes['roles']);

        return back()->with('success', 'Usuário atualizado com sucesso.');
    }
}
