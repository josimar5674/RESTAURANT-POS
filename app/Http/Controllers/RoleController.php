<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')
            ->orderBy('name')
            ->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name',
            ],
        ]);

        Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol creado correctamente.');
    }

    public function show(Role $role)
    {
        return redirect()->route('roles.edit', $role);
    }


public function edit(Role $role)
{
    $permissions = Permission::orderBy('name')->get();

    $rolePermissions = $role->permissions
        ->pluck('name')
        ->toArray();

    return view('roles.edit', compact(
        'role',
        'permissions',
        'rolePermissions'
    ));
}

    

 public function update(Request $request, Role $role)
{
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'unique:roles,name,' . $role->id,
        ],
        'permissions' => [
            'nullable',
            'array',
        ],
        'permissions.*' => [
            'string',
            'exists:permissions,name',
        ],
    ]);

    $role->update([
        'name' => $validated['name'],
    ]);

    $role->syncPermissions($validated['permissions'] ?? []);

    return redirect()
        ->route('roles.index')
        ->with('success', 'Rol actualizado correctamente.');
}

    public function destroy(Role $role)
    {
        if ($role->users()->exists()) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'No se puede eliminar un rol que tiene usuarios asignados.');
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }
}