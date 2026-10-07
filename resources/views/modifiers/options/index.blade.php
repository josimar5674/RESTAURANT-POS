@extends('layouts.app')

@section('title', 'Opciones de modificador')
@section('subtitle', 'Administra las opciones disponibles para este grupo')

@section('content')

<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between">

        <div>

            <div class="mb-2">
                <a
                    href="{{ route('modifier-groups.index') }}"
                    class="text-sm font-medium text-slate-500 hover:text-slate-800"
                >
                    ← Grupos de modificadores
                </a>
            </div>

            <h2 class="text-lg font-semibold text-slate-800">
                {{ $modifierGroup->name }}
            </h2>

            <p class="text-sm text-slate-500">
                {{ $modifierGroup->description ?: 'Administra las opciones de este grupo.' }}
            </p>

        </div>


        <button
            type="button"
            onclick="openModifierOptionModal()"
            class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
        >
            <span class="text-lg leading-none">+</span>
            Nueva opción
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


    {{-- Información del grupo --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Mínimo
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-800">
                {{ $modifierGroup->min_selections }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                selecciones
            </p>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Máximo
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-800">
                {{ $modifierGroup->max_selections }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                selecciones
            </p>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Opciones
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-800">
                {{ $options->count() }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                registradas
            </p>

        </div>

    </div>


    {{-- Tabla --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">

            <h3 class="font-semibold text-slate-800">
                Opciones disponibles
            </h3>

            <p class="text-sm text-slate-500">
                Estas son las opciones que podrán seleccionarse.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Opción
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Ajuste de precio
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Precio al cliente
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Orden
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Estado
                        </th>

                        <th class="w-48 px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($options as $option)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">

                                <p class="font-semibold text-slate-800">
                                    {{ $option->name }}
                                </p>

                            </td>


                            <td class="px-6 py-4">

                                @if((float) $option->price_adjustment > 0)

                                    <span class="font-medium text-emerald-700">
                                        + L {{ number_format($option->price_adjustment, 2) }}
                                    </span>

                                @elseif((float) $option->price_adjustment < 0)

                                    <span class="font-medium text-red-600">
                                        - L {{ number_format(abs($option->price_adjustment), 2) }}
                                    </span>

                                @else

                                    <span class="text-slate-500">
                                        Sin ajuste
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                    @php
                                        $taxRate = (float) ($modifierGroup->tax?->rate ?? 0);
                                        $priceAdjustment = (float) $option->price_adjustment;
                                        $customerPrice = $priceAdjustment * (1 + $taxRate / 100);
                                    @endphp

                                    @if($priceAdjustment > 0)

                                        <div>
                                            <span class="font-semibold text-slate-800">
                                                L {{ number_format($customerPrice, 2) }}
                                            </span>

                                            @if($taxRate > 0)
                                                <p class="mt-0.5 text-xs text-slate-400">
                                                    Incluye {{ number_format($taxRate, 2) }}% impuesto
                                                </p>
                                            @endif
                                        </div>

                                    @else

                                        <span class="text-slate-500">
                                            L 0.00
                                        </span>

                                    @endif

                                </td>



                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $option->sort_order }}
                            </td>


                            <td class="px-6 py-4">

                                @if($option->active)

                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        Activo
                                    </span>

                                @else

                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                                        Inactivo
                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <button
                                        type="button"
                                        data-option-id="{{ $option->id }}"
                                        onclick="openEditModifierOptionModal(this.dataset.optionId)"
                                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900"
                                    >
                                        Editar
                                    </button>


                                    <form
                                        action="{{ route('modifier-options.destroy', [$modifierGroup, $option]) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Deseas eliminar esta opción?');"
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

                            <td colspan="6" class="px-6 py-12 text-center">

                                <p class="font-medium text-slate-700">
                                    No hay opciones registradas
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Agrega la primera opción para este grupo.
                                </p>

                                <button
                                    type="button"
                                    onclick="openModifierOptionModal()"
                                    class="mt-4 text-sm font-semibold text-slate-800 hover:underline"
                                >
                                    Crear opción
                                </button>

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

@foreach($options as $option)

    <div
        id="editModifierOptionModal{{ $option->id }}"
        class="fixed inset-0 z-50 hidden"
        aria-hidden="true"
    >

        <div
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
            onclick="closeEditModifierOptionModal({{ $option->id }})"
        ></div>


        <div class="relative flex min-h-full items-center justify-center p-6">

            <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">

                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                    <div>

                        <h3 class="text-lg font-semibold text-slate-800">
                            Editar opción
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $modifierGroup->name }}
                        </p>

                    </div>


                    <button
                        type="button"
                        onclick="closeEditModifierOptionModal({{ $option->id }})"
                        class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    >
                        ✕
                    </button>

                </div>


                @include(
                    'modifiers.options._form',
                    ['option' => $option]
                )

            </div>

        </div>

    </div>

@endforeach


{{-- ========================================================= --}}
{{-- MODAL NUEVA OPCIÓN --}}
{{-- ========================================================= --}}

<div
    id="modifierOptionModal"
    class="fixed inset-0 z-50 hidden"
    aria-hidden="true"
>

    <div
        class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModifierOptionModal()"
    ></div>


    <div class="relative flex min-h-full items-center justify-center p-6">

        <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">

            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                <div>

                    <h3 class="text-lg font-semibold text-slate-800">
                        Nueva opción
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $modifierGroup->name }}
                    </p>

                </div>


                <button
                    type="button"
                    onclick="closeModifierOptionModal()"
                    class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                >
                    ✕
                </button>

            </div>


            @include(
                'modifiers.options._form',
                ['option' => null]
            )

        </div>

    </div>

</div>

@endsection