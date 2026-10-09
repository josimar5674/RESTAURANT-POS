@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-6 py-8">

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
                Nuevo rol
            </h1>

        </div>

        <p class="text-sm text-slate-500 ml-8">
            Crea un nuevo rol para controlar el acceso al sistema.
        </p>

    </div>


    {{-- Errores --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl bg-red-50 border border-red-200
                    px-4 py-3">

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


    {{-- Formulario --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">

        <form
            action="{{ route('roles.store') }}"
            method="POST"
        >

            @csrf


            {{-- Nombre --}}
            <div class="mb-6">

                <label
                    for="name"
                    class="block text-sm font-semibold text-slate-700 mb-2"
                >
                    Nombre del rol
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Ej. Supervisor"
                    maxlength="255"
                    required
                    autofocus
                    class="w-full px-4 py-3
                           border border-slate-200
                           rounded-xl
                           text-slate-800
                           placeholder-slate-400
                           outline-none
                           focus:border-orange-500
                           focus:ring-2
                           focus:ring-orange-100
                           transition"
                >

                <p class="mt-2 text-xs text-slate-400">
                    El nombre debe identificar claramente las funciones del rol.
                </p>

            </div>


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
                    Crear rol
                </button>

            </div>

        </form>

    </div>

</div>

@endsection