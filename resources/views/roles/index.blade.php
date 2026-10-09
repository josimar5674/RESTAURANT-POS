@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Roles
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Administra los roles de acceso del sistema.
            </p>
        </div>

        <a
            href="{{ route('roles.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5
                   bg-orange-500 hover:bg-orange-600
                   text-white font-semibold
                   rounded-xl transition"
        >
            <span class="text-lg">+</span>
            Nuevo rol
        </a>

    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200
                    px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200
                    px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">

        <table class="w-full">

            <thead class="bg-slate-50 border-b border-slate-200">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-bold
                               text-slate-500 uppercase tracking-wider">
                        Rol
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold
                               text-slate-500 uppercase tracking-wider">
                        Usuarios
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-bold
                               text-slate-500 uppercase tracking-wider">
                        Acciones
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse($roles as $role)

                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-6 py-5">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-xl
                                            bg-orange-100
                                            flex items-center justify-center">

                                    <svg
                                        class="w-5 h-5 text-orange-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zm10-9V7a4 4 0 00-8 0v3h8z"
                                        />
                                    </svg>

                                </div>

                                <div>
                                    <div class="font-semibold text-slate-800">
                                        {{ $role->name }}
                                    </div>

                                    <div class="text-xs text-slate-400">
                                        {{ $role->guard_name }}
                                    </div>
                                </div>

                            </div>

                        </td>

                        <td class="px-6 py-5">

                            <span class="inline-flex items-center
                                         px-3 py-1 rounded-full
                                         bg-slate-100 text-slate-700
                                         text-sm font-semibold">

                                {{ $role->users_count }}

                                {{ $role->users_count === 1 ? 'usuario' : 'usuarios' }}

                            </span>

                        </td>

                        <td class="px-6 py-5">

                            <div class="flex justify-end gap-2">

                                <a
                                    href="{{ route('roles.edit', $role) }}"
                                    class="px-3 py-2 rounded-lg
                                           text-sm font-semibold
                                           text-slate-600
                                           hover:bg-slate-100 transition"
                                >
                                    Editar
                                </a>

                                <form
                                    action="{{ route('roles.destroy', $role) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Seguro que deseas eliminar este rol?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="px-3 py-2 rounded-lg
                                               text-sm font-semibold
                                               text-red-600
                                               hover:bg-red-50 transition"
                                    >
                                        Eliminar
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="3"
                            class="px-6 py-12 text-center"
                        >

                            <div class="text-slate-400">

                                <div class="text-4xl mb-3">
                                    🔐
                                </div>

                                <p class="font-semibold text-slate-600">
                                    No hay roles registrados
                                </p>

                                <p class="text-sm mt-1">
                                    Crea el primer rol para comenzar.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection