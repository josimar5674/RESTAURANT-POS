@extends('layouts.app')

@section('title', 'Categorías')
@section('subtitle', 'Administra las categorías de productos del restaurante')

@section('content')

    <div class="space-y-6">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Categorías de productos
                </h2>

                <p class="text-sm text-slate-500">
                    Organiza el menú de tu restaurante.
                </p>
            </div>

        <button
    type="button"
    onclick="openCategoryModal()"
    class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700"
>
    <span class="text-lg leading-none">+</span>
    Nueva categoría
</button>

        </div>


        {{-- Mensaje de éxito --}}
        @if(session('success'))

            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>

        @endif


        {{-- Tabla --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Categoría
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Descripción
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Orden
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Estado
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Acciones
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($categories as $category)

                            <tr class="transition hover:bg-slate-50">

                                {{-- Nombre --}}
                                <td class="px-6 py-4">

                                    <div class="font-semibold text-slate-800">
                                        {{ $category->name }}
                                    </div>

                                </td>


                                {{-- Descripción --}}
                                <td class="px-6 py-4 text-sm text-slate-500">

                                    {{ $category->description ?: 'Sin descripción' }}

                                </td>


                                {{-- Orden --}}
                                <td class="px-6 py-4 text-sm text-slate-600">

                                    {{ $category->sort_order }}

                                </td>


                                {{-- Estado --}}
                                <td class="px-6 py-4">

                                    @if($category->active)

                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            Activa
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                                            Inactiva
                                        </span>

                                    @endif

                                </td>


                                {{-- Acciones --}}
                                <td class="px-6 py-4">

                                    <div class="flex justify-end gap-2">

                                      <button
                                            type="button"
                                            onclick="openEditCategoryModal({{$category->id}})"
                                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100"
                                        >
                                            Editar
                                        </button>

                                        <form
                                            action="{{ route('product-categories.destroy', $category) }}"
                                            method="POST"
                                            onsubmit="return confirm('¿Eliminar esta categoría?')"
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
                                            📂
                                        </div>

                                        <p class="font-medium text-slate-600">
                                            No hay categorías todavía
                                        </p>

                                        <p class="mt-1 text-sm">
                                            Crea la primera categoría para comenzar a configurar el menú.
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

{{-- Modales de edición --}}
@foreach($categories as $category)

    <div
        id="editCategoryModal{{ $category->id }}"
        class="fixed inset-0 z-50 hidden"
        aria-hidden="true"
    >

        {{-- Fondo --}}
        <div
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
            onclick="closeEditCategoryModal({{ $category->id }})"
        ></div>


        {{-- Contenedor --}}
        <div class="relative flex min-h-full items-start justify-center p-4 pt-16">

            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                    <div>

                        <h2 class="text-lg font-semibold text-slate-800">
                            Editar categoría
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Modifica la información de la categoría.
                        </p>

                    </div>

                    <button
                        type="button"
                        onclick="closeEditCategoryModal({{ $category->id }})"
                        class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    >
                        ✕
                    </button>

                </div>


                {{-- Formulario --}}
                <div class="px-6 py-5">

                    @include(
                        'products.categories._form',
                        ['category' => $category]
                    )

                </div>

            </div>

        </div>

    </div>

@endforeach


    {{-- Modal nueva categoría --}}
<div
    id="categoryModal"
    class="fixed inset-0 z-50 hidden"
    aria-hidden="true"
>

    {{-- Fondo --}}
    <div
        class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeCategoryModal()"
    ></div>


    {{-- Contenedor --}}
<div class="relative flex min-h-full items-start justify-center p-4 pt-12">
    <div
        class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl"
    >

            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                <div>

                    <h2 class="text-lg font-semibold text-slate-800">
                        Nueva categoría
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Define la información básica de la categoría.
                    </p>

                </div>

                <button
                    type="button"
                    onclick="closeCategoryModal()"
                    class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                >
                    ✕
                </button>

            </div>


            {{-- Formulario --}}
            <div class="p-6">

               @include(
    'products.categories._form',
    ['category' => null]
)

            </div>

        </div>

    </div>

</div>


<script>


</script>

@endsection