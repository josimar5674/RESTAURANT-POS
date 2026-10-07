@php
    $isEdit = isset($product);
    $formKey = $isEdit
        ? 'product-' . $product->id
        : 'new-product';
@endphp

<form
    action="{{ isset($product)
        ? route('products.update', $product)
        : route('products.store') }}"
    method="POST"
    data-product-form
    class="max-h-[calc(90vh-90px)] overflow-y-auto">
    @csrf

    @if(isset($product))
        @method('PUT')
    @endif
<div class="min-h-0 flex-1 overflow-y-auto px-6 py-6">

<div class="space-y-5">

    {{-- Información principal --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-[0.85fr_1.15fr]">

        {{-- Columna izquierda --}}
        <div class="space-y-4">

            <div>

                <label
                    for="{{ $formKey }}-name"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Nombre
                </label>

                <input
                    type="text"
                    id="{{ $formKey }}-name"
                    name="name"
                    value="{{ old('name', $product->name ?? '') }}"
                    placeholder="Ej. Hamburguesa clásica"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            <div>

                <label
                   for="{{ $formKey }}-category_id"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Categoría
                </label>

                <select
                    id="{{ $formKey }}-category_id"
                    name="category_id"
                    required
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >

                    <option value="">
                        Selecciona una categoría
                    </option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                           @selected(
                                old('category_id', $product->category_id ?? '') == $category->id
                            )
                        >
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>

                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Impuesto --}}
<div>
    <label
        for="tax_id_{{ $formKey }}"
        class="mb-1.5 block text-sm font-semibold text-slate-700"
    >
        Impuesto aplicable
    </label>

<select
    name="tax_id"
    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
>
    <option
        value=""
        data-tax-rate="0"
        @selected(!$product?->tax_id)
    >
        Sin impuesto
    </option>

    @foreach($taxes as $tax)
        <option
            value="{{ $tax->id }}"
            data-tax-rate="{{ $tax->rate }}"
            @selected($product?->tax_id == $tax->id)
        >
            {{ $tax->name }} ({{ $tax->rate }}%)
        </option>
    @endforeach
</select>

    <p class="mt-1.5 text-xs text-slate-500">
        Selecciona el impuesto que se aplicará al precio del producto.
    </p>
</div>


            <div>

                <label
                        for="{{ $formKey }}-description"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Descripción
                </label>

                <textarea
                    id="{{ $formKey }}-description"
                    name="description"
                    rows="4"
                    placeholder="Descripción del producto"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >{{ old('description', $product->description ?? '') }}</textarea>

                @error('description')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        </div>


        {{-- Columna derecha --}}
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

            <div class="mb-4">

                <h3 class="text-sm font-semibold text-slate-800">
                    Precio y variantes
                </h3>

                <p class="mt-1 text-xs text-slate-500">
                    Define un precio único o varias presentaciones.
                </p>

            </div>


            {{-- Precio único --}}
         {{-- Precio único --}}
<div id="{{ $formKey }}-single-price" data-single-price class="space-y-3">

    <div class="grid grid-cols-2 gap-3">

        {{-- Precio base --}}
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-600">
                Precio base
            </label>

            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">
                    L
                </span>

                <input
                    type="number"
                    id="{{ $formKey }}-price"
                    data-price
                    name="price"
                    value="{{ old('price', isset($product) ? optional($product->variants->first())->price : '') }}"
                    step="0.01"
                    min="0"
                    class="w-full rounded-lg border border-slate-200 bg-white py-2 pl-8 pr-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                >
            </div>
        </div>

        {{-- Precio final --}}
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-600">
                Precio final
            </label>

            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">
                    L
                </span>

                <input
                    type="text"
                    data-final-price
                    readonly
                    class="w-full rounded-lg border border-slate-200 bg-slate-100 py-2 pl-8 pr-3 text-sm text-slate-700"
                    value="0.00"
                >
            </div>
        </div>

    </div>

    @error('price')
        <p class="text-xs text-red-500">{{ $message }}</p>
    @enderror

</div>


            {{-- Tiene variantes --}}
            <div class="mt-4 flex items-start gap-3">

                        <input
                        type="checkbox"
                        id="{{ $formKey }}-has_variants"
                        data-has-variants
                        name="has_variants"
                        value="1"
                        @checked(
                            old(
                                'has_variants',
                                isset($product)
                                    ? $product->variants->count() > 1 ||
                                    optional($product->variants->first())->name !== 'Único'
                                    : false
                            )
                        )
                        class="mt-1 h-4 w-4 rounded border-slate-300"
                        onchange="toggleProductVariants(this)"
                    >

                <div>

                    <label
                         for="{{ $formKey }}-has_variants"
                        class="text-sm font-medium text-slate-700"
                    >
                        Este producto tiene variantes
                    </label>

                    <p class="text-xs text-slate-500">
                        Diferentes tamaños, presentaciones o precios.
                    </p>

                </div>

            </div> 


            {{-- Variantes --}}
   {{-- Variantes --}}
<div
    id="{{ $formKey }}-variants-section"
    data-variants-section
    class="mt-4 hidden"
>

    <div class="mb-3 flex items-center justify-between">

        <h4 class="text-sm font-semibold text-slate-700">
            Variantes
        </h4>

        <button
            type="button"
            onclick="addProductVariant(
                this.closest('form').querySelector('[data-variants-container]')
            )"
            class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-100"
        >
            + Agregar
        </button>

    </div>

    <div
        id="{{ $formKey }}-variants-container"
        data-variants-container
        class="max-h-48 space-y-2 overflow-y-auto pr-1"
    >

        @if(isset($product))

            @foreach($product->variants as $index => $variant)

                @if($variant->name !== 'Único' || $product->variants->count() > 1)

                    <div class="variant-row flex items-end gap-3 rounded-lg border border-slate-200 bg-white p-3">

                        <input
                            type="hidden"
                            name="variants[{{ $index }}][id]"
                            value="{{ $variant->id }}"
                        >

                        {{-- Nombre --}}
                        <div class="w-32 shrink-0">

                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Nombre
                            </label>

                            <input
                                type="text"
                                name="variants[{{ $index }}][name]"
                                value="{{ old("variants.$index.name", $variant->name) }}"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                placeholder="Ej. 12 unidades"
                            >

                        </div>

                        {{-- Precio base --}}
                        <div class="w-32 shrink-0">

                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Precio base
                            </label>

                            <div class="relative">

                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">
                                    L
                                </span>

                                <input
                                    type="number"
                                    name="variants[{{ $index }}][price]"
                                    value="{{ old("variants.$index.price", $variant->price) }}"
                                    step="0.01"
                                    min="0"
                                    data-variant-base-price
                                    class="w-full rounded-lg border border-slate-200 bg-white py-2 pl-8 pr-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                >

                            </div>

                        </div>

                        {{-- Precio final --}}
                        <div class="w-32 shrink-0">

                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Precio final
                            </label>

                            <div class="relative">

                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">
                                    L
                                </span>

                                <input
                                    type="text"
                                    data-variant-final-price
                                    readonly
                                    value="0.00"
                                    class="w-full rounded-lg border border-slate-200 bg-slate-100 py-2 pl-8 pr-2 text-sm text-slate-700"
                                >

                            </div>

                        </div>

                        {{-- Eliminar --}}
                        <button
                            type="button"
                            onclick="this.closest('.variant-row').remove()"
                            class="h-9 w-9 shrink-0 rounded-lg border border-red-200 text-red-500 hover:bg-red-50"
                            title="Eliminar variante"
                        >
                            ✕
                        </button>

                    </div>

                @endif

            @endforeach

        @endif

    </div>

</div>

</div> {{-- Cierra columna derecha --}}

</div> {{-- Cierra grid principal --}}

{{-- Modificadores --}}
<div class="border-t border-slate-200 pt-6">

    {{-- Modificadores --}}
<div class="border-t border-slate-200 pt-6">

    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-800">
            Modificadores
        </h3>

        <p class="mt-1 text-xs text-slate-500">
            Selecciona los grupos de opciones que estarán disponibles para este producto.
        </p>
    </div>


    @if(isset($modifierGroups) && $modifierGroups->count())

        <div class="space-y-3">

            @foreach($modifierGroups as $modifierGroup)

                @php
                    $selectedModifierGroups = old(
                        'modifier_groups',
                        isset($product)
                            ? $product->modifierGroups->pluck('id')->toArray()
                            : []
                    );
                @endphp

                <label
                    class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200 bg-white p-4 transition hover:border-slate-300 hover:bg-slate-50"
                >

                    <div class="flex items-center gap-3">

                        <input
                            type="checkbox"
                            name="modifier_groups[]"
                            value="{{ $modifierGroup->id }}"
                            @checked(
                                in_array(
                                    $modifierGroup->id,
                                    $selectedModifierGroups
                                )
                            )
                            class="h-4 w-4 rounded border-slate-300 text-slate-800 focus:ring-slate-300"
                        >

                      <div class="min-w-0">

    <p class="text-sm font-semibold text-slate-800">
        {{ $modifierGroup->name }}
    </p>

    @if($modifierGroup->description)

        <p class="mt-0.5 text-xs text-slate-500">
            {{ $modifierGroup->description }}
        </p>

    @endif


    {{-- Opciones del grupo --}}
    @if($modifierGroup->options->count())

        <div class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1">

            @foreach($modifierGroup->options as $option)

                <span class="text-xs text-slate-500">
                    {{ $option->name }}
                    @if(!$loop->last)
                        <span class="text-slate-300">·</span>
                    @endif
                </span>

            @endforeach

        </div>

    @else

        <p class="mt-2 text-xs italic text-slate-400">
            Sin opciones configuradas
        </p>

    @endif


    {{-- Enlace a las opciones --}}
    <a
        href="{{ route('modifier-options.index', $modifierGroup) }}"
        target="_blank"
        class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-slate-500 transition hover:text-slate-900"
        onclick="event.stopPropagation();"
    >
        Editar opciones
        <span aria-hidden="true">→</span>
    </a>

</div>

                    </div>


                    <div class="text-right">

                        @if($modifierGroup->min_selections === $modifierGroup->max_selections)

                            <span class="text-xs font-medium text-slate-600">
                                {{ $modifierGroup->min_selections }}
                                {{ $modifierGroup->min_selections === 1 ? 'opción' : 'opciones' }}
                            </span>

                        @else

                            <span class="text-xs font-medium text-slate-600">
                                {{ $modifierGroup->min_selections }}
                                a
                                {{ $modifierGroup->max_selections }}
                                opciones
                            </span>

                        @endif

                        <p class="mt-0.5 text-[11px] text-slate-400">
                            Selección
                        </p>

                    </div>

                </label>

            @endforeach

        </div>

    @else

        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-6 text-center">

            <p class="text-sm font-medium text-slate-600">
                No hay grupos de modificadores disponibles.
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Crea primero un grupo de modificadores para poder asignarlo a este producto.
            </p>

        </div>

    @endif

</div>


    {{-- Información secundaria --}}
    <div class="border-t border-slate-200 pt-5">

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Imagen --}}
            <div>

                <label
                    for="image"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Imagen
                </label>

                <input
                    type="text"
                    id="{{ $formKey }}-image"
                    name="image"
                    value="{{ old('image', $product->image ?? '') }}"
                    placeholder="URL o ruta de la imagen"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >

            </div>


            {{-- Orden --}}
            <div>

                <label
                    for="sort_order"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Orden de aparición
                </label>

                <input
                    type="number"
                    id="{{ $formKey }}-sort_order"
                    name="sort_order"
                    value="{{ old('sort_order', $product->sort_order ?? 1) }}"
                    min="1"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >

            </div>

        </div>


        {{-- Estado --}}
        <div class="mt-4 flex items-center gap-3">

            <input
                type="checkbox"
                id="{{ $formKey }}-active"
                name="active"
                value="1"
               @checked(
    old(
        'active',
        $product->active ?? true
    )
)
                class="h-4 w-4 rounded border-slate-300"
            >

            <div>

                <label
                    for="{{ $formKey }}-active"
                    class="text-sm font-medium text-slate-700"
                >
                    Producto activo
                </label>

                <p class="text-xs text-slate-500">
                    El producto estará disponible para la venta.
                </p>

            </div>

        </div>

    </div>

</div>

    {{-- Botones --}}
    <div class="mt-5 flex justify-end gap-3 border-t border-slate-200 pt-4">

       <button
    type="button"
    onclick="{{ $isEdit
        ? "closeEditProductModal({$product->id})"
        : "closeProductModal()"
    }}"
            class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
        >
            Cancelar
        </button>

        <button
            type="submit"
            class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700"
        >
          {{ $isEdit ? 'Actualizar producto' : 'Guardar producto' }}
        </button>

    </div>


</div>
</form>

