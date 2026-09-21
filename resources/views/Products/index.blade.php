@extends('layouts.app')

@section('title', 'Productos')
@section('subtitle', 'Administra el catálogo de productos del restaurante')

@section('content')

    <div class="space-y-6">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Productos
                </h2>

                <p class="text-sm text-slate-500">
                    Administra productos, precios y disponibilidad.
                </p>
            </div>

            <button
                type="button"
                onclick="openProductModal()"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700"
            >
                <span class="text-lg leading-none">+</span>
                Nuevo producto
            </button>

        </div>


        {{-- Mensaje de éxito --}}
        @if(session('success'))

            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>

        @endif


        {{-- Filtros --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                {{-- Buscar --}}
                <div>

                    <label
                        for="search"
                        class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500"
                    >
                        Buscar
                    </label>

                    <input
                        type="text"
                        id="search"
                        placeholder="Buscar producto..."
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    >

                </div>


                {{-- Categoría --}}
                <div>

                    <label
                        for="categoryFilter"
                        class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500"
                    >
                        Categoría
                    </label>

                    <select
                        id="categoryFilter"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    >

                        <option value="">
                            Todas las categorías
                        </option>

                        @foreach($categories as $category)

                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Estado --}}
                <div>

                    <label
                        for="statusFilter"
                        class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500"
                    >
                        Estado
                    </label>

                    <select
                        id="statusFilter"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    >

                        <option value="">
                            Todos
                        </option>

                        <option value="active">
                            Activos
                        </option>

                        <option value="inactive">
                            Inactivos
                        </option>

                    </select>

                </div>

            </div>

        </div>


        {{-- Tabla --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Producto
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Categoría
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Precio
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Estado
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Acciones
                            </th>

                        </tr>

                    </thead>

                    <tbody
                        id="productsTable"
                        class="divide-y divide-slate-100"
                    >

                        @forelse($products as $product)

                            <tr
                                class="product-row transition hover:bg-slate-50"
                                data-name="{{ strtolower($product->name) }}"
                                data-category="{{ $product->category_id }}"
                                data-active="{{ $product->active ? 'active' : 'inactive' }}"
                            >

                                {{-- Producto --}}
                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-lg bg-slate-100">

                                            @if($product->image)

                                                <img
                                                    src="{{ $product->image }}"
                                                    alt="{{ $product->name }}"
                                                    class="h-full w-full object-cover"
                                                >

                                            @else

                                                <span class="text-lg">
                                                    🍽️
                                                </span>

                                            @endif

                                        </div>

                                        <div>

                                            <div class="font-semibold text-slate-800">
                                                {{ $product->name }}
                                            </div>

                                            @if($product->description)

                                                <div class="max-w-md truncate text-sm text-slate-500">
                                                    {{ $product->description }}
                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Categoría --}}
                                <td class="px-6 py-4">

                                    <span class="text-sm text-slate-600">
                                        {{ $product->category?->name ?? 'Sin categoría' }}
                                    </span>

                                </td>


                                {{-- Precio --}}
                                <td class="px-6 py-4">

                                    @if($product->variants->count())

                                        <span class="text-sm font-semibold text-slate-700">
                                            Desde
                                            L {{ number_format($product->variants->min('price'), 2) }}
                                        </span>

                                        @if($product->variants->count() > 1)

                                            <span class="block text-xs text-slate-400">
                                                {{ $product->variants->count() }} variantes
                                            </span>

                                        @endif

                                    @else

                                        <span class="text-sm text-slate-400">
                                            Sin precio
                                        </span>

                                    @endif

                                </td>


                                {{-- Estado --}}
                                <td class="px-6 py-4">

                                    @if($product->active)

                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            Activo
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                                            Inactivo
                                        </span>

                                    @endif

                                </td>


                                {{-- Acciones --}}
                                <td class="px-6 py-4">

                                    <div class="flex justify-end gap-2">

                                        <button
                                            type="button"
                                            data-product-id="{{ $product->id }}"
                                            onclick="openEditProductModal(this.dataset.productId)"
                                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100"
                                        >
                                            Editar
                                        </button>

                                        <form
                                            action="{{ route('products.destroy', $product) }}"
                                            method="POST"
                                            onsubmit="return confirm('¿Eliminar este producto?')"
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

                                <td
                                    colspan="5"
                                    class="px-6 py-12 text-center"
                                >

                                    <div class="text-slate-400">

                                        <div class="mb-2 text-4xl">
                                            🍽️
                                        </div>

                                        <p class="font-medium text-slate-600">
                                            No hay productos todavía
                                        </p>

                                        <p class="mt-1 text-sm">
                                            Crea el primer producto para comenzar a configurar el menú.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    @foreach($products as $product)

    <div
        id="editProductModal{{ $product->id }}"
        class="fixed inset-0 z-50 hidden"
        aria-hidden="true"
    >

        <div
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
            onclick="closeEditProductModal({{ $product->id }})"
        ></div>


        <div class="relative flex min-h-full items-start justify-center p-4 pt-10">

            <div class="flex max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">

                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                    <div>

                        <h2 class="text-lg font-semibold text-slate-800">
                            Editar producto
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Modifica la información del producto.
                        </p>

                    </div>

                    <button
                        type="button"
                        onclick="closeEditProductModal({{ $product->id }})"
                        class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    >
                        ✕
                    </button>

                </div>


                <div class="px-6 py-5">

                    @include(
                        'products._form',
                        ['product' => $product]
                    )

                </div>

            </div>

        </div>

    </div>

@endforeach


    {{-- Modal nuevo producto --}}
<div
    id="productModal"
    class="fixed inset-0 z-50 hidden"
    aria-hidden="true"
>

    {{-- Fondo --}}
    <div
        class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeProductModal()"
    ></div>


    {{-- Contenedor --}}
    <div class="relative flex min-h-full items-start justify-center p-4 pt-16">

       <div class="w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-2xl">

            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                <div>

                    <h2 class="text-lg font-semibold text-slate-800">
                        Nuevo producto
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Define la información básica del producto.
                    </p>

                </div>

                <button
                    type="button"
                    onclick="closeProductModal()"
                    class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                >
                    ✕
                </button>

            </div>


            {{-- Formulario --}}
            <div class="px-6 py-5">

              @include('products._form', ['product' => null])

            </div>

        </div>

    </div>

</div>

@endsection


