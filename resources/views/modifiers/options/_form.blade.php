@php

    $isEdit = isset($option);

@endphp


<form
    action="{{ $isEdit
        ? route('modifier-options.update', [$modifierGroup, $option])
        : route('modifier-options.store', $modifierGroup)
    }}"
    method="POST"
    class="space-y-6 p-6"
>

    @csrf

    @if($isEdit)
        @method('PUT')
    @endif


    {{-- Nombre --}}
    <div>

        <label
            for="{{ $isEdit ? 'edit-option-name-'.$option->id : 'new-option-name' }}"
            class="mb-2 block text-sm font-medium text-slate-700"
        >
            Nombre
        </label>

        <input
            type="text"
            id="{{ $isEdit ? 'edit-option-name-'.$option->id : 'new-option-name' }}"
            name="name"
            value="{{ old('name', $option->name ?? '') }}"
            placeholder="Ej. Buffalo"
            maxlength="100"
            required
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
        >

    </div>



{{-- Precio --}}
<div>

    <label
        for="{{ $isEdit ? 'edit-option-price-'.$option->id : 'new-option-price' }}"
        class="mb-2 block text-sm font-medium text-slate-700"
    >
        Ajuste de precio
    </label>

    <div class="relative">

        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400">
            L
        </span>

        <input
            type="number"
            id="{{ $isEdit ? 'edit-option-price-'.$option->id : 'new-option-price' }}"
            name="price_adjustment"
            value="{{ old('price_adjustment', $option->price_adjustment ?? 0) }}"
            step="0.01"
            min="0"
            placeholder="0.00"
            data-tax-rate="{{ $modifierGroup->tax?->rate ?? 0 }}"
            oninput="updateModifierOptionPrice(this)"
            class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-8 pr-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
        >

    </div>

    <p class="mt-1 text-xs text-slate-500">
        Usa 0.00 si la opción no tiene costo adicional.
    </p>


    {{-- Precio final con impuesto --}}
    <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Precio al cliente
                </p>

                <p class="mt-0.5 text-xs text-slate-400">
                    Incluye impuesto
                    ({{ number_format($modifierGroup->tax?->rate ?? 0, 2) }}%)
                </p>

            </div>

            <p
                id="{{ $isEdit ? 'edit-option-final-price-'.$option->id : 'new-option-final-price' }}"
                class="text-lg font-bold text-slate-800"
            >
                L 0.00
            </p>

        </div>

    </div>

</div>


    {{-- Orden --}}
    <div>

        <label
            for="{{ $isEdit ? 'edit-option-sort-'.$option->id : 'new-option-sort' }}"
            class="mb-2 block text-sm font-medium text-slate-700"
        >
            Orden de visualización
        </label>

        <input
            type="number"
            id="{{ $isEdit ? 'edit-option-sort-'.$option->id : 'new-option-sort' }}"
            name="sort_order"
            value="{{ old('sort_order', $option->sort_order ?? 1) }}"
            min="1"
            required
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
        >

    </div>


    {{-- Estado --}}
    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">

        <label class="flex cursor-pointer items-center gap-3">

            <input
                type="checkbox"
                name="active"
                value="1"
                @checked(
                    old(
                        'active',
                        $option->active ?? true
                    )
                )
                class="h-4 w-4 rounded border-slate-300 text-slate-800 focus:ring-slate-300"
            >

            <div>

                <p class="text-sm font-medium text-slate-700">
                    Opción activa
                </p>

                <p class="text-xs text-slate-500">
                    Las opciones inactivas no estarán disponibles para seleccionar.
                </p>

            </div>

        </label>

    </div>


    {{-- Acciones --}}
    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">

        <button
            type="button"
            onclick="{{ $isEdit
                ? "closeEditModifierOptionModal({$option->id})"
                : "closeModifierOptionModal()"
            }}"
            class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
        >
            Cancelar
        </button>


        <button
            type="submit"
            class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
        >
            {{ $isEdit ? 'Actualizar opción' : 'Guardar opción' }}
        </button>

    </div>

</form>