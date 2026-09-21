@php

    $isEdit = isset($group);

@endphp


<form
    action="{{ $isEdit
        ? route('modifier-groups.update', $group)
        : route('modifier-groups.store')
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
            for="{{ $isEdit ? 'edit-name-'.$group->id : 'new-name' }}"
            class="mb-2 block text-sm font-medium text-slate-700"
        >
            Nombre
        </label>

        <input
            type="text"
            id="{{ $isEdit ? 'edit-name-'.$group->id : 'new-name' }}"
            name="name"
            value="{{ old('name', $group->name ?? '') }}"
            placeholder="Ej. Salsas"
            required
            maxlength="100"
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
        >

    </div>


    {{-- Descripción --}}
    <div>

        <label
            for="{{ $isEdit ? 'edit-description-'.$group->id : 'new-description' }}"
            class="mb-2 block text-sm font-medium text-slate-700"
        >
            Descripción
        </label>

        <input
            type="text"
            id="{{ $isEdit ? 'edit-description-'.$group->id : 'new-description' }}"
            name="description"
            value="{{ old('description', $group->description ?? '') }}"
            placeholder="Ej. Selecciona las salsas para las alitas"
            maxlength="255"
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
        >

    </div>


    {{-- Selecciones --}}
    <div>

        <div class="mb-2">

            <label class="block text-sm font-medium text-slate-700">
                Selecciones permitidas
            </label>

            <p class="mt-1 text-xs text-slate-500">
                Define cuántas opciones puede o debe seleccionar el cliente.
            </p>

        </div>


        <div class="grid grid-cols-2 gap-4">

            {{-- Mínimo --}}
            <div>

                <label
                    for="{{ $isEdit ? 'edit-min-'.$group->id : 'new-min' }}"
                    class="mb-2 block text-xs font-medium text-slate-500"
                >
                    Mínimo
                </label>

                <input
                    type="number"
                    id="{{ $isEdit ? 'edit-min-'.$group->id : 'new-min' }}"
                    name="min_selections"
                    value="{{ old('min_selections', $group->min_selections ?? 0) }}"
                    min="0"
                    max="255"
                    required
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >

            </div>


            {{-- Máximo --}}
            <div>

                <label
                    for="{{ $isEdit ? 'edit-max-'.$group->id : 'new-max' }}"
                    class="mb-2 block text-xs font-medium text-slate-500"
                >
                    Máximo
                </label>

                <input
                    type="number"
                    id="{{ $isEdit ? 'edit-max-'.$group->id : 'new-max' }}"
                    name="max_selections"
                    value="{{ old('max_selections', $group->max_selections ?? 1) }}"
                    min="1"
                    max="255"
                    required
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >

            </div>

        </div>

    </div>


    {{-- Orden --}}
    <div>

        <label
            for="{{ $isEdit ? 'edit-sort-'.$group->id : 'new-sort' }}"
            class="mb-2 block text-sm font-medium text-slate-700"
        >
            Orden de visualización
        </label>

        <input
            type="number"
            id="{{ $isEdit ? 'edit-sort-'.$group->id : 'new-sort' }}"
            name="sort_order"
            value="{{ old('sort_order', $group->sort_order ?? 1) }}"
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
                        $group->active ?? true
                    )
                )
                class="h-4 w-4 rounded border-slate-300 text-slate-800 focus:ring-slate-300"
            >

            <div>

                <p class="text-sm font-medium text-slate-700">
                    Grupo activo
                </p>

                <p class="text-xs text-slate-500">
                    Los grupos inactivos no estarán disponibles para asignarlos a productos.
                </p>

            </div>

        </label>

    </div>


    {{-- Acciones --}}
    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">

        <button
            type="button"
            onclick="{{ $isEdit
                ? "closeEditModifierGroupModal({$group->id})"
                : "closeModifierGroupModal()"
            }}"
            class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
        >
            Cancelar
        </button>


        <button
            type="submit"
            class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
        >
            {{ $isEdit ? 'Actualizar grupo' : 'Guardar grupo' }}
        </button>

    </div>

</form>