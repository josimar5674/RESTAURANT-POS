@extends('layouts.app')

@section('title', 'Grupos de modificadores')
@section('subtitle', 'Administra las opciones de personalización de los productos')

@section('content')

<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">
                Grupos de modificadores
            </h2>

            <p class="text-sm text-slate-500">
                Define grupos como salsas, extras, tipos de carne y otras opciones.
            </p>
        </div>

        <button
            type="button"
            onclick="openModifierGroupModal()"
            class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
        >
            <span class="text-lg leading-none">+</span>
            Nuevo grupo
        </button>
    </div>


    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- Errores --}}
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">
            <p class="mb-1 text-sm font-semibold text-red-700">
                Revisa los siguientes errores:
            </p>

            <ul class="list-disc space-y-1 pl-5 text-sm text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Tabla --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">
            <div class="flex items-center justify-between">

                <div>
                    <h3 class="font-semibold text-slate-800">
                        Grupos registrados
                    </h3>

                    <p class="text-sm text-slate-500">
                        {{ $groups->count() }}
                        {{ $groups->count() === 1 ? 'grupo registrado' : 'grupos registrados' }}
                    </p>
                </div>

            </div>
        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Grupo
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Selecciones
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Orden
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Estado
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($groups as $group)

                        <tr class="hover:bg-slate-50">

                            {{-- Grupo --}}
                            <td class="px-6 py-4">

                                <div>
                                    <p class="font-semibold text-slate-800">
                                        {{ $group->name }}
                                    </p>

                                    @if($group->description)
                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $group->description }}
                                        </p>
                                    @endif
                                </div>

                            </td>


                            {{-- Selecciones --}}
                            <td class="px-6 py-4">

                                <div class="text-sm text-slate-700">

                                    @if($group->min_selections === $group->max_selections)

                                        <span class="font-medium">
                                            {{ $group->min_selections }}
                                        </span>

                                        <span class="text-slate-400">
                                            {{ $group->min_selections === 1 ? 'opción' : 'opciones' }}
                                        </span>

                                    @else

                                        <span class="font-medium">
                                            {{ $group->min_selections }}
                                        </span>

                                        <span class="text-slate-400">
                                            a
                                        </span>

                                        <span class="font-medium">
                                            {{ $group->max_selections }}
                                        </span>

                                        <span class="text-slate-400">
                                            opciones
                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- Orden --}}
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $group->sort_order }}
                            </td>


                            {{-- Estado --}}
                            <td class="px-6 py-4">

                                @if($group->active)

                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        Activo
                                    </span>

                                @else

                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                                        Inactivo
                                    </span>

                                @endif

                            </td>


                            {{-- Acciones --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('modifier-options.index', $group) }}"
                                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900"
                                    >
                                        Opciones
                                    </a>

                                    <button
                                        type="button"
                                        data-group-id="{{ $group->id }}"
                                        onclick="openEditModifierGroupModal(this.dataset.groupId)"
                                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900"
                                    >
                                        Editar
                                    </button>


                                    <form
                                        action="{{ route('modifier-groups.destroy', $group) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Deseas eliminar este grupo de modificadores?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg border border-red-200 px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50"
                                        >
                                            Eliminar
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="px-6 py-12 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-xl text-slate-400">
                                        +
                                    </div>

                                    <p class="font-medium text-slate-700">
                                        No hay grupos de modificadores
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Crea el primer grupo para comenzar.
                                    </p>

                                    <button
                                        type="button"
                                        onclick="openModifierGroupModal()"
                                        class="mt-4 text-sm font-semibold text-slate-800 hover:underline"
                                    >
                                        Crear grupo
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODALES DE EDICIÓN --}}
{{-- ========================================================= --}}

@foreach($groups as $group)

    <div
        id="editModifierGroupModal{{ $group->id }}"
        class="fixed inset-0 z-50 hidden"
        aria-hidden="true"
    >

        <div
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
            onclick="closeEditModifierGroupModal({{ $group->id }})"
        ></div>


        <div class="relative flex min-h-full items-center justify-center p-6">

            <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">

                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">
                            Editar grupo
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Modifica la configuración del grupo.
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="closeEditModifierGroupModal({{ $group->id }})"
                        class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    >
                        ✕
                    </button>

                </div>


                @include(
                    'modifiers.groups._form',
                    ['group' => $group]
                )

            </div>

        </div>

    </div>

@endforeach


{{-- ========================================================= --}}
{{-- MODAL NUEVO GRUPO --}}
{{-- ========================================================= --}}

<div
    id="modifierGroupModal"
    class="fixed inset-0 z-50 hidden"
    aria-hidden="true"
>

    <div
        class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModifierGroupModal()"
    ></div>


    <div class="relative flex min-h-full items-center justify-center p-6">

        <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">

            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                <div>
                    <h3 class="text-lg font-semibold text-slate-800">
                        Nuevo grupo de modificadores
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Define las reglas de selección para este grupo.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="closeModifierGroupModal()"
                    class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                >
                    ✕
                </button>

            </div>


            @include(
                'modifiers.groups._form',
                ['group' => null]
            )

        </div>

    </div>

</div>




@endsection