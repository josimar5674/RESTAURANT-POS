@extends('layouts.app')

@section('title', 'Editor de Salón')

@section('subtitle', 'Diseño visual del restaurante')


@section('content')



<div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
    

    {{-- Barra de herramientas --}}


<div class="flex items-center justify-between px-5 py-3 border-b border-slate-200 bg-slate-50">

    <div class="flex items-center gap-3">

   <div>

    <div class="flex items-center justify-between mb-2">

        <label class="text-sm font-medium">
            Área
        </label>

        <button
            id="btn-new-area"
            type="button"
            class="text-sm text-blue-600 hover:text-blue-800">

            + Nueva

        </button>

    </div>

    <select
        id="area-selector"
        class="w-full rounded-lg border border-slate-300 px-3 py-2">

    </select>

</div>

    </div>
        <div class="flex items-center gap-3">

            <button id="tool-select"
                class="w-10 h-10 border rounded-lg hover:bg-slate-100"
                title="Seleccionar">
                🖱
            </button>

            <button id="tool-table-square"
                class="w-10 h-10 border rounded-lg hover:bg-slate-100"
                title="Mesa cuadrada">
                ▭
            </button>

            <button id="tool-table-round"
                class="w-10 h-10 border rounded-lg hover:bg-slate-100"
                title="Mesa redonda">
                ⭕
            </button>

            <button id="tool-wall"
                class="w-10 h-10 border rounded-lg hover:bg-slate-100"
                title="Muro">
                🧱
            </button>

            <button id="tool-plant"
                class="w-10 h-10 border rounded-lg hover:bg-slate-100"
                title="Planta">
                🪴
            </button>

        </div>

    </div>

    <div class="flex h-[750px]">

        {{-- Canvas --}}
        <div class="flex-1 bg-slate-100">
            <div id="konva-container" class="w-full h-full"></div>
        </div>

        {{-- Panel derecho --}}
        <aside class="w-80 border-l border-slate-200 bg-white p-5">

            <h2 id="properties-title"
                class="text-lg font-semibold">
                Propiedades
            </h2>

            <p class="text-sm text-slate-500 mt-2 mb-6">
                Seleccione un objeto del plano.
            </p>

            <div class="space-y-6">

                <div>

                    <label class="block text-sm font-medium mb-2">
                        Nombre
                    </label>



                    <input
                        id="table-name"
                        type="text"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                        placeholder="Nombre"
                        disabled>

                </div>

                <div class="mt-5">

    <label class="block text-sm font-medium mb-2">
        Rotación
    </label>

    <div class="flex gap-2">

        <button
            id="btn-rotate-left"
            class="flex-1 rounded-lg border border-slate-300 py-2 hover:bg-slate-100"
            disabled>

            ↺ -15°

        </button>

        <button
            id="btn-rotate-right"
            class="flex-1 rounded-lg border border-slate-300 py-2 hover:bg-slate-100"
            disabled>

            +15° ↻

        </button>

    </div>

</div>

                <hr>

                <button
                    id="btn-delete-object"
                    class="w-full bg-red-600 hover:bg-red-700 text-white rounded-lg py-3 transition disabled:opacity-40 disabled:cursor-not-allowed"
                    disabled>

                    🗑 Eliminar objeto

                </button>

            </div>

        </aside>

    </div>

</div>

@endsection