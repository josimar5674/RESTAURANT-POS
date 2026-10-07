@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-7xl px-6 py-8">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Impuestos
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Configura los impuestos aplicables a los productos del restaurante.
            </p>
        </div>

        <button
            type="button"
            onclick="openTaxModal()"
            class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
        >
            <span class="text-lg leading-none">+</span>
            Nuevo impuesto
        </button>
    </div>


    {{-- Success --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- Validation errors --}}
    @if($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
            <ul class="list-inside list-disc text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Impuesto
                        </th>

                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Código
                        </th>

                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Tasa
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Orden
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Estado
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($taxes as $tax)

                        <tr class="transition hover:bg-slate-50">

                            {{-- Name --}}
                            <td class="px-6 py-4">

                                <div>
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $tax->name }}
                                    </p>

                                    @if($tax->description)
                                        <p class="mt-0.5 text-xs text-slate-500">
                                            {{ $tax->description }}
                                        </p>
                                    @endif
                                </div>

                            </td>


                            {{-- Code --}}
                            <td class="px-6 py-4">

                                @if($tax->code)

                                    <span class="rounded-lg bg-slate-100 px-2.5 py-1 font-mono text-xs font-medium text-slate-600">
                                        {{ $tax->code }}
                                    </span>

                                @else

                                    <span class="text-xs text-slate-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Rate --}}
                            <td class="px-6 py-4">

                                <span class="text-sm font-bold text-slate-800">
                                    {{ number_format((float) $tax->rate, 2) }}%
                                </span>

                            </td>


                            {{-- Sort order --}}
                            <td class="px-6 py-4 text-center">

                                <span class="text-sm text-slate-600">
                                    {{ $tax->sort_order }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">

                                @if($tax->active)

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">

                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                        Activo

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">

                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>

                                        Inactivo

                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <button
                                        type="button"
                                        onclick='editTax(@json($tax))'
                                        class="rounded-lg px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                                    >
                                        Editar
                                    </button>


                                    <form
                                        action="{{ route('taxes.destroy', $tax) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar este impuesto?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg px-3 py-2 text-xs font-semibold text-red-500 transition hover:bg-red-50 hover:text-red-700"
                                        >
                                            Eliminar
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-6 py-16 text-center">

                                <div class="mx-auto max-w-sm">

                                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-xl">
                                        %
                                    </div>

                                    <h3 class="text-sm font-semibold text-slate-800">
                                        No hay impuestos configurados
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Crea el primer impuesto para comenzar a configurar los productos.
                                    </p>

                                    <button
                                        type="button"
                                        onclick="openTaxModal()"
                                        class="mt-5 text-sm font-semibold text-slate-700 underline underline-offset-4 hover:text-slate-900"
                                    >
                                        Crear primer impuesto
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
{{-- TAX MODAL --}}
{{-- ========================================================= --}}

<div
    id="taxModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 backdrop-blur-sm"
>

    <div
        class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl"
        onclick="event.stopPropagation()"
    >

        {{-- Modal header --}}
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

            <div>
                <h2
                    id="taxModalTitle"
                    class="text-lg font-bold text-slate-900"
                >
                    Nuevo impuesto
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Define la tasa que se aplicará a los productos.
                </p>
            </div>

            <button
                type="button"
                onclick="closeTaxModal()"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
            >
                ×
            </button>

        </div>


        {{-- Form --}}
        <form
            id="taxForm"
            method="POST"
            action="{{ route('taxes.store') }}"
        >

            @csrf

            <div id="taxMethod"></div>


            <div class="space-y-5 px-6 py-6">

                {{-- Name --}}
                <div>

                    <label
                        for="tax_name"
                        class="mb-1.5 block text-sm font-semibold text-slate-700"
                    >
                        Nombre
                    </label>

                    <input
                        type="text"
                        id="tax_name"
                        name="name"
                        maxlength="100"
                        placeholder="Ej. ISV General"
                        required
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100"
                    >

                </div>


                {{-- Code + Rate --}}
                <div class="grid grid-cols-2 gap-4">

                    <div>

                        <label
                            for="tax_code"
                            class="mb-1.5 block text-sm font-semibold text-slate-700"
                        >
                            Código
                        </label>

                        <input
                            type="text"
                            id="tax_code"
                            name="code"
                            maxlength="50"
                            placeholder="Ej. ISV15"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100"
                        >

                    </div>


                    <div>

                        <label
                            for="tax_rate"
                            class="mb-1.5 block text-sm font-semibold text-slate-700"
                        >
                            Tasa (%)
                        </label>

                        <div class="relative">

                            <input
                                type="number"
                                id="tax_rate"
                                name="rate"
                                min="0"
                                max="100"
                                step="0.01"
                                placeholder="15.00"
                                required
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 pr-10 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100"
                            >

                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400">
                                %
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Description --}}
                <div>

                    <label
                        for="tax_description"
                        class="mb-1.5 block text-sm font-semibold text-slate-700"
                    >
                        Descripción
                    </label>

                    <input
                        type="text"
                        id="tax_description"
                        name="description"
                        maxlength="255"
                        placeholder="Descripción opcional"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-100"
                    >

                </div>


                {{-- Sort order --}}
                <div>

                    <label
                        for="tax_sort_order"
                        class="mb-1.5 block text-sm font-semibold text-slate-700"
                    >
                        Orden
                    </label>

                    <input
                        type="number"
                        id="tax_sort_order"
                        name="sort_order"
                        min="1"
                        value="1"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100"
                    >

                </div>


                {{-- Active --}}
                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <input
                        type="checkbox"
                        id="tax_active"
                        name="active"
                        value="1"
                        checked
                        class="h-4 w-4 rounded border-slate-300"
                    >

                    <div>

                        <p class="text-sm font-semibold text-slate-700">
                            Impuesto activo
                        </p>

                        <p class="text-xs text-slate-500">
                            Los impuestos inactivos no aparecerán al configurar productos.
                        </p>

                    </div>

                </label>

            </div>


            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4">

                <button
                    type="button"
                    onclick="closeTaxModal()"
                    class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-200"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
                >
                    Guardar
                </button>

            </div>

        </form>

    </div>

</div>

@endsection