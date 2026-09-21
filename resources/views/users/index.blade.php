@extends('layouts.app')

@section('title', 'Usuarios')

@section('subtitle', 'Gestión de usuarios del restaurante')

@section('content')

<div
    x-data="{
        showUserModal: {{ $errors->has('pin') && old('form') === 'create' ? 'true' : 'false' }},
        showEditUserModal: false,

        role: @js(old('role', 'Mesero')),

        editUser: {
            id: null,
            first_name: '',
            last_name: '',
            email: '',
            pin: '',
            role: '',
            active: true
        }
    }"
    class="space-y-6"
>

    <!-- Encabezado -->
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-lg font-semibold text-slate-800">
                Usuarios
            </h2>

            <p class="text-sm text-slate-500">
                Administra los usuarios que tendrán acceso al sistema.
            </p>
        </div>

        <button
            type="button"
            @click="showUserModal = true"
            class="px-5 py-2.5 bg-blue-600 text-white rounded-xl
                   font-semibold hover:bg-blue-700 transition"
        >
            + Nuevo usuario
        </button>

    </div>


    <!-- Tabla -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <table class="w-full">

            <thead class="bg-slate-50 border-b border-slate-200">

                <tr>

                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-600">
                        Usuario
                    </th>

                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-600">
                        Correo
                    </th>

                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-600">
                        Rol
                    </th>

                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-600">
                        Estado
                    </th>

                    <th class="text-right px-6 py-4 text-sm font-semibold text-slate-600">
                        Acción
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse ($users as $user)

                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-6 py-4">

                            <div class="font-semibold text-slate-800">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </div>

                        </td>


                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ $user->email }}
                        </td>


                        <td class="px-6 py-4">

                            @foreach ($user->roles as $role)

                                <span
                                    class="px-3 py-1 text-xs font-semibold
                                           bg-blue-100 text-blue-700 rounded-full"
                                >
                                    {{ $role->name }}
                                </span>

                            @endforeach

                        </td>


                        <td class="px-6 py-4">

                            @if ($user->active)

                                <span
                                    class="px-3 py-1 text-xs font-semibold
                                           bg-green-100 text-green-700 rounded-full"
                                >
                                    Activo
                                </span>

                            @else

                                <span
                                    class="px-3 py-1 text-xs font-semibold
                                           bg-red-100 text-red-700 rounded-full"
                                >
                                    Inactivo
                                </span>

                            @endif

                        </td>


                        <td class="px-6 py-4 text-right">

                            <button
                                type="button"
                                @click="
                                    showEditUserModal = true;

                                    editUser = {
                                        id: {{ $user->id }},
                                        first_name: @js($user->first_name),
                                        last_name: @js($user->last_name),
                                        email: @js($user->email),
                                        pin: '',
                                        role: @js($user->roles->first()?->name),
                                        active: {{ $user->active ? 'true' : 'false' }}
                                    }
                                "
                                class="text-blue-600 hover:text-blue-800
                                       font-semibold text-sm"
                            >
                                Editar
                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="px-6 py-12 text-center">

                            <div class="text-slate-400">
                                No hay usuarios registrados.
                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>



    <!-- ===================================================== -->
    <!-- MODAL NUEVO USUARIO                                  -->
    <!-- ===================================================== -->

    <form
        method="POST"
        action="{{ route('users.store') }}"
    >

        @csrf

        <input
            type="hidden"
            name="form"
            value="create"
        >

        <div
            x-show="showUserModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center"
        >

            <!-- Fondo -->
            <div
                class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
                @click="showUserModal = false"
            ></div>


            <!-- Modal -->
            <div
                x-show="showUserModal"
                x-transition
                class="relative w-full max-w-lg mx-4
                       bg-white rounded-2xl shadow-2xl"
            >

                <!-- Header -->
                <div
                    class="flex items-center justify-between
                           px-6 py-5 border-b border-slate-200"
                >

                    <div>

                        <h2 class="text-xl font-bold text-slate-800">
                            Nuevo usuario
                        </h2>

                        <p class="text-sm text-slate-500">
                            Crea un usuario para acceder al POS.
                        </p>

                    </div>


                    <button
                        type="button"
                        @click="showUserModal = false"
                        class="text-slate-400 hover:text-slate-600 text-2xl"
                    >
                        &times;
                    </button>

                </div>


                <!-- Campos -->
                <div class="p-6 space-y-5">

                    <!-- Nombre / Apellido -->
                    <div class="grid grid-cols-2 gap-4">

                        <div>

                            <label
                                class="block text-sm font-medium
                                       text-slate-700 mb-1"
                            >
                                Nombre
                            </label>

                            <input
                                name="first_name"
                                type="text"
                                value="{{ old('first_name') }}"
                                required
                                class="w-full rounded-xl border-slate-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                                placeholder="Juan"
                            >

                        </div>


                        <div>

                            <label
                                class="block text-sm font-medium
                                       text-slate-700 mb-1"
                            >
                                Apellido
                            </label>

                            <input
                                name="last_name"
                                type="text"
                                value="{{ old('last_name') }}"
                                required
                                class="w-full rounded-xl border-slate-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                                placeholder="Pérez"
                            >

                        </div>

                    </div>


                    <!-- Email -->
                    <div>

                        <label
                            class="block text-sm font-medium
                                   text-slate-700 mb-1"
                        >
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-xl border-slate-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                            placeholder="usuario@restaurant.local"
                        >

                        @error('email')

                            @if (old('form') === 'create')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @endif

                        @enderror

                    </div>


                    <!-- PIN -->
                    <div>

                        <label
                            class="block text-sm font-medium
                                   text-slate-700 mb-1"
                        >
                            PIN de acceso
                        </label>

                        <input
                            type="password"
                            name="pin"
                            maxlength="6"
                            inputmode="numeric"
                            pattern="[0-9]{6}"
                            value="{{ old('pin') }}"
                            required
                            class="w-full rounded-xl
                                   border-slate-300
                                   @error('pin')
                                       border-red-500
                                   @enderror
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                            placeholder="••••••"
                        >

                        @error('pin')

                            @if (old('form') === 'create')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @endif

                        @enderror

                        <p class="mt-1 text-xs text-slate-400">
                            El PIN debe contener 6 dígitos.
                        </p>

                    </div>


                    <!-- Contraseña -->
                    <div
                        x-show="
                            role === 'Gerente' ||
                            role === 'Administrador'
                        "
                        x-transition
                    >

                        <label
                            class="block text-sm font-medium
                                   text-slate-700 mb-1"
                        >
                            Contraseña
                        </label>

                        <input
                            type="password"
                            name="password"
                            x-bind:required="
                                role === 'Gerente' ||
                                role === 'Administrador'
                            "
                            class="w-full rounded-xl
                                   border-slate-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                            placeholder="Contraseña"
                        >

                        <p class="mt-1 text-xs text-slate-400">
                            Obligatoria para Gerente y Administrador.
                        </p>

                    </div>


                    <!-- Rol -->
                    <div>

                        <label
                            class="block text-sm font-medium
                                   text-slate-700 mb-1"
                        >
                            Rol
                        </label>

                        <select
                            x-model="role"
                            name="role"
                            class="w-full rounded-xl
                                   border-slate-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                        >

                            <option value="Mesero">
                                Mesero
                            </option>

                            <option value="Cajero">
                                Cajero
                            </option>

                            <option value="Gerente">
                                Gerente
                            </option>

                            <option value="Cocina">
                                Cocina
                            </option>

                            <option value="Administrador">
                                Administrador
                            </option>

                        </select>

                    </div>


                    <input
                        type="hidden"
                        name="active"
                        value="1"
                    >

                </div>


                <!-- Footer -->
                <div
                    class="flex justify-end gap-3 px-6 py-4
                           bg-slate-50 rounded-b-2xl
                           border-t border-slate-200"
                >

                    <button
                        type="button"
                        @click="showUserModal = false"
                        class="px-5 py-2.5 rounded-xl
                               border border-slate-300
                               text-slate-700 font-semibold
                               hover:bg-slate-100"
                    >
                        Cancelar
                    </button>


                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-xl
                               bg-blue-600 text-white
                               font-semibold hover:bg-blue-700"
                    >
                        Guardar usuario
                    </button>

                </div>

            </div>

        </div>

    </form>



    <!-- ===================================================== -->
    <!-- MODAL EDITAR USUARIO                                 -->
    <!-- ===================================================== -->

    <form
        method="POST"
        :action="`/users/${editUser.id}`"
    >

        @csrf

        @method('PUT')

        <input
            type="hidden"
            name="form"
            value="edit"
        >


        <div
            x-show="showEditUserModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center"
        >

            <!-- Fondo -->
            <div
                class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
                @click="showEditUserModal = false"
            ></div>


            <!-- Modal -->
            <div
                x-show="showEditUserModal"
                x-transition
                class="relative w-full max-w-lg mx-4
                       bg-white rounded-2xl shadow-2xl"
            >

                <!-- Header -->
                <div
                    class="flex items-center justify-between
                           px-6 py-5 border-b border-slate-200"
                >

                    <div>

                        <h2 class="text-xl font-bold text-slate-800">
                            Editar usuario
                        </h2>

                        <p class="text-sm text-slate-500">
                            Modifica la información del usuario.
                        </p>

                    </div>


                    <button
                        type="button"
                        @click="showEditUserModal = false"
                        class="text-slate-400 hover:text-slate-600 text-2xl"
                    >
                        &times;
                    </button>

                </div>


                <!-- Campos -->
                <div class="p-6 space-y-5">

                    <!-- Nombre / Apellido -->
                    <div class="grid grid-cols-2 gap-4">

                        <div>

                            <label
                                class="block text-sm font-medium
                                       text-slate-700 mb-1"
                            >
                                Nombre
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                x-model="editUser.first_name"
                                required
                                class="w-full rounded-xl
                                       border-slate-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                        </div>


                        <div>

                            <label
                                class="block text-sm font-medium
                                       text-slate-700 mb-1"
                            >
                                Apellido
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                x-model="editUser.last_name"
                                required
                                class="w-full rounded-xl
                                       border-slate-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                        </div>

                    </div>


                    <!-- Email -->
                    <div>

                        <label
                            class="block text-sm font-medium
                                   text-slate-700 mb-1"
                        >
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            name="email"
                            x-model="editUser.email"
                            class="w-full rounded-xl
                                   border-slate-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                        >

                    </div>


                    <!-- PIN -->
                    <div>

                        <label
                            class="block text-sm font-medium
                                   text-slate-700 mb-1"
                        >
                            PIN de acceso
                        </label>

                        <input
                            type="password"
                            name="pin"
                            x-model="editUser.pin"
                            maxlength="6"
                            inputmode="numeric"
                            pattern="[0-9]{6}"
                            class="w-full rounded-xl
                                   border-slate-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                            placeholder="Nuevo PIN"
                        >

                        <p class="mt-1 text-xs text-slate-400">
                            Déjalo vacío para conservar el PIN actual.
                        </p>

                    </div>


                    <!-- Rol -->
                    <div>

                        <label
                            class="block text-sm font-medium
                                   text-slate-700 mb-1"
                        >
                            Rol
                        </label>

                        <select
                            name="role"
                            x-model="editUser.role"
                            required
                            class="w-full rounded-xl
                                   border-slate-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                        >

                            <option value="Mesero">
                                Mesero
                            </option>

                            <option value="Cajero">
                                Cajero
                            </option>

                            <option value="Gerente">
                                Gerente
                            </option>

                            <option value="Cocina">
                                Cocina
                            </option>

                            <option value="Administrador">
                                Administrador
                            </option>

                        </select>

                    </div>


                    <!-- Estado -->
                    <div>

                        <label
                            class="block text-sm font-medium
                                   text-slate-700 mb-1"
                        >
                            Estado
                        </label>

                        <select
                            name="active"
                            x-model="editUser.active"
                            class="w-full rounded-xl
                                   border-slate-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                        >

                            <option value="1">
                                Activo
                            </option>

                            <option value="0">
                                Inactivo
                            </option>

                        </select>

                    </div>

                </div>


                <!-- Footer -->
                <div
                    class="flex justify-end gap-3 px-6 py-4
                           bg-slate-50 rounded-b-2xl
                           border-t border-slate-200"
                >

                    <button
                        type="button"
                        @click="showEditUserModal = false"
                        class="px-5 py-2.5 rounded-xl
                               border border-slate-300
                               text-slate-700 font-semibold
                               hover:bg-slate-100"
                    >
                        Cancelar
                    </button>


                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-xl
                               bg-blue-600 text-white
                               font-semibold hover:bg-blue-700"
                    >
                        Guardar cambios
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection