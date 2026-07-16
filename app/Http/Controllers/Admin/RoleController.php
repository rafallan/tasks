<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Roles/Index', [
            'roles' => Role::query()
                ->with('permissions:id,name')
                ->withCount('users')
                ->orderBy('name')
                ->get(['id', 'name']),
            'permissions' => Permission::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $role = Role::create(['name' => $attributes['name'], 'guard_name' => 'web']);
        $role->syncPermissions($attributes['permissions']);

        return back()->with('success', 'Papel criado com sucesso.');
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $attributes = $request->validated();

        if ($role->name === 'Admin' && $attributes['name'] !== 'Admin') {
            throw ValidationException::withMessages([
                'name' => 'O papel Admin não pode ser renomeado.',
            ]);
        }

        $role->update(['name' => $attributes['name']]);
        $role->syncPermissions($attributes['permissions']);

        return back()->with('success', 'Papel atualizado com sucesso.');
    }
}
