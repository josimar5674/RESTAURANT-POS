<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
{
    $users = User::with('roles')
        ->orderBy('first_name')
        ->get();

    $roles = Role::orderBy('name')->get();

    return view('users.index', compact('users', 'roles'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'pin' => [
                'required',
                'digits:6',
            ],

            'role' => [
                'required',
                'exists:roles,name',
            ],

            'active' => [
                'required',
                'boolean',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'required_if:role,Gerente,Administrador',
            ],
        ]);

        /*
         * Verificar que el PIN no esté
         * asignado a otro usuario.
         */
        $pinExists = User::whereNotNull('pin')
            ->get()
            ->contains(function ($user) use ($data) {
                return Hash::check($data['pin'], $user->pin);
            });

        if ($pinExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'pin' => 'Este PIN ya está asignado a otro usuario.',
                ]);
        }

        /*
         * Crear usuario.
         *
         * El modelo User tiene:
         *
         * 'pin' => 'hashed'
         *
         * por lo que Laravel se encarga
         * automáticamente de hashearlo.
         */
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? null,
            'pin' => $data['pin'],
            'password' => $data['password'] ?? null,
            'active' => $data['active'],
        ]);

        $user->assignRole($data['role']);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],

            'pin' => [
                'nullable',
                'digits:6',
            ],

            'role' => [
                'required',
                'exists:roles,name',
            ],

            'active' => [
                'required',
                'boolean',
            ],
        ]);

        /*
         * Si se proporcionó un nuevo PIN,
         * comprobar que no lo tenga otro usuario.
         */
        if (!empty($data['pin'])) {

            $pinExists = User::whereNotNull('pin')
                ->where('id', '!=', $user->id)
                ->get()
                ->contains(function ($otherUser) use ($data) {
                    return Hash::check(
                        $data['pin'],
                        $otherUser->pin
                    );
                });

            if ($pinExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'pin' => 'Este PIN ya está asignado a otro usuario.',
                    ]);
            }
        }

        /*
         * Actualizar información.
         */
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'] ?? null;
        $user->active = $data['active'];

        /*
         * Solo modificar el PIN si se ingresó uno nuevo.
         */
        if (!empty($data['pin'])) {
            $user->pin = $data['pin'];
        }

        $user->save();

        /*
         * Actualizar rol.
         */
        $user->syncRoles([
            $data['role']
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}