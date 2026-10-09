@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="mb-8">

        <div class="flex items-center gap-3 mb-2">

            <a
                href="{{ route('roles.index') }}"
                class="text-slate-400 hover:text-slate-600 transition"
            >
                ←
            </a>

            <h1 class="text-2xl font-bold text-slate-800">
                Editar rol
            </h1>

        </div>

        <p class="text-sm text-slate-500 ml-8">
            Configura el nombre y los permisos de este rol.
        </p>

    </div>


    {{-- Errores --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3">

            <p class="text-sm font-semibold text-red-700 mb-2">
                Revisa los siguientes errores:
            </p>

            <ul class="text-sm text-red-600 space-y-1">

                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('roles.update', $role) }}"
        method="POST"
        class="space-y-6"
    >

        @csrf
        @method('PUT')


        {{-- Información del rol --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">

            <h2 class="text-lg font-bold text-slate-800 mb-1">
                Información del rol
            </h2>

            <p class="text-sm text-slate-500 mb-5">
                Define el nombre con el que se identificará este rol.
            </p>

            <label
                for="name"
                class="block text-sm font-semibold text-slate-700 mb-2"
            >
                Nombre
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $role->name) }}"
                maxlength="255"
                required
                class="w-full px-4 py-3
                       border border-slate-200
                       rounded-xl
                       text-slate-800
                       outline-none
                       focus:border-orange-500
                       focus:ring-2
                       focus:ring-orange-100
                       transition"
            >

        </div>


        {{-- Permisos --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">

            <div class="mb-6">

                <h2 class="text-lg font-bold text-slate-800">
                    Permisos
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Selecciona las acciones que podrá realizar este rol.
                </p>

            </div>

@php

    /*
    |--------------------------------------------------------------------------
    | Agrupar permisos por módulo
    |--------------------------------------------------------------------------
    */

    $groupedPermissions = $permissions
        ->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });


    /*
    |--------------------------------------------------------------------------
    | Nombres amigables de los módulos
    |--------------------------------------------------------------------------
    */

    $moduleLabels = [
        'users' => 'Usuarios',
        'roles' => 'Roles',
        'products' => 'Productos',
        'product_categories' => 'Categorías de productos',
        'modifier_groups' => 'Grupos de modificadores',
        'modifier_options' => 'Opciones de modificadores',
        'tables' => 'Mesas',
        'taxes' => 'Impuestos',
        'configuration' => 'Configuración',
    ];


    /*
    |--------------------------------------------------------------------------
    | Nombres amigables de las acciones
    |--------------------------------------------------------------------------
    */

    $actionLabels = [
        'view' => 'Ver',
        'create' => 'Crear',
        'edit' => 'Editar',
        'delete' => 'Eliminar',
    ];

@endphp


@foreach($groupedPermissions as $module => $modulePermissions)

    @php
        $moduleLabel = $moduleLabels[$module]
            ?? ucfirst(str_replace('_', ' ', $module));
    @endphp


    <div class="border border-slate-200 rounded-xl overflow-hidden">

        {{-- Encabezado del módulo --}}
        <div
            class="px-5 py-4
                   bg-slate-50
                   border-b border-slate-200"
        >

            <h3 class="font-semibold text-slate-800">
                {{ $moduleLabel }}
            </h3>

        </div>


        {{-- Permisos --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-5">

            @foreach($modulePermissions as $permission)

                @php
                    $parts = explode('.', $permission->name);

                    $action = $parts[1] ?? $permission->name;

                    $actionLabel = $actionLabels[$action]
                        ?? ucfirst(str_replace('_', ' ', $action));
                @endphp


                <label
                    class="flex items-center gap-3
                           p-3 rounded-xl
                           border border-slate-200
                           hover:bg-slate-50
                           cursor-pointer transition"
                >

                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="{{ $permission->name }}"
                        {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                        class="w-4 h-4
                               text-orange-500
                               border-slate-300
                               rounded
                               focus:ring-orange-500"
                    >

                    <span class="text-sm font-medium text-slate-700">
                        {{ $actionLabel }}
                    </span>

                </label>

            @endforeach

        </div>

     

    </div>

@endforeach


       


        {{-- Acciones --}}
        <div class="flex items-center justify-end gap-3">

            <a
                href="{{ route('roles.index') }}"
                class="px-4 py-2.5
                       rounded-xl
                       text-sm font-semibold
                       text-slate-600
                       hover:bg-slate-100
                       transition"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="inline-flex items-center gap-2
                       px-5 py-2.5
                       bg-orange-500
                       hover:bg-orange-600
                       text-white
                       font-semibold
                       rounded-xl
                       transition"
            >
                Guardar cambios
            </button>

        </div>

    </form>

</div>

@endsection