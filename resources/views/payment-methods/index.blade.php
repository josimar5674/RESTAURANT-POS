@extends('layouts.app')

@section('title', 'Métodos de pago')
@section('subtitle', 'Administra los métodos de pago del restaurante')

@section('content')

<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

       

        <button
            type="button"
            onclick="openCreateModal()"
            class="inline-flex items-center justify-center gap-2 px-5 py-3
                   bg-blue-600 text-white rounded-xl font-semibold
                   hover:bg-blue-700 transition shadow-sm">

            <span class="text-xl leading-none">+</span>
            Nuevo método

        </button>

    </div>


    {{-- Mensajes --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700
                    px-5 py-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700
                    px-5 py-4 rounded-xl">
            {{ session('error') }}
        </div>
    @endif


    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700
                    px-5 py-4 rounded-xl">

            <p class="font-semibold mb-2">
                No se pudo guardar el método de pago.
            </p>

            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif





    {{-- Tabla --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-200">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                

                <input
                    type="text"
                    id="payment-method-search"
                    placeholder="Buscar método..."
                    class="w-full sm:w-64 rounded-xl border border-slate-300
                           px-4 py-2.5 text-sm focus:border-blue-500
                           focus:ring-2 focus:ring-blue-100 outline-none">

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">

                    <tr>
                        <th class="px-6 py-4">Método</th>
                        <th class="px-6 py-4">Código</th>
                        <th class="px-6 py-4">Referencia</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>

                </thead>

                <tbody id="payment-methods-table"
                       class="divide-y divide-slate-100">

                    @forelse ($paymentMethods as $paymentMethod)

                        <tr class="hover:bg-slate-50 transition payment-method-row">

                            <td class="px-6 py-4">

                                <div class="font-semibold text-slate-800">
                                    {{ $paymentMethod->name }}
                                </div>

                                <div class="text-xs text-slate-400 mt-1">
                                    Orden: {{ $paymentMethod->sort_order }}
                                </div>

                            </td>

                            <td class="px-6 py-4">

                                <code class="text-xs bg-slate-100 text-slate-600
                                             px-2 py-1 rounded-md">
                                    {{ $paymentMethod->code }}
                                </code>

                            </td>

                            <td class="px-6 py-4">

                                @if ($paymentMethod->requires_reference)

                                    <span class="text-xs font-medium text-blue-700
                                                 bg-blue-50 px-3 py-1.5 rounded-full">
                                        Requerida
                                    </span>

                                @else

                                    <span class="text-xs text-slate-500">
                                        No requerida
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                @if ($paymentMethod->active)

                                    <span class="inline-flex items-center gap-2
                                                 text-xs font-semibold text-green-700
                                                 bg-green-50 px-3 py-1.5 rounded-full">

                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        Activo

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-2
                                                 text-xs font-semibold text-slate-600
                                                 bg-slate-100 px-3 py-1.5 rounded-full">

                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                        Inactivo

                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    {{-- Editar --}}
                                    <button
                                        type="button"
                                        onclick="openEditModal({{ $paymentMethod->id }})"
                                        class="px-3 py-2 text-sm font-medium text-blue-700
                                               bg-blue-50 hover:bg-blue-100 rounded-lg transition">

                                        Editar

                                    </button>


                                    {{-- Activar / Inactivar --}}
                                    <form
                                        method="POST"
                                        action="{{ route('payment-methods.toggle-status', $paymentMethod) }}"
                                        onsubmit="return confirm('¿Deseas {{ $paymentMethod->active ? 'inactivar' : 'activar' }} este método de pago?')">

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="px-3 py-2 text-sm font-medium rounded-lg transition
                                            {{ $paymentMethod->active
                                                ? 'text-red-700 bg-red-50 hover:bg-red-100'
                                                : 'text-green-700 bg-green-50 hover:bg-green-100' }}">

                                            {{ $paymentMethod->active ? 'Inactivar' : 'Activar' }}

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">

                                <div class="text-4xl mb-3">💳</div>

                                <p class="font-semibold text-slate-700">
                                    Todavía no hay métodos de pago
                                </p>

                                <p class="text-sm text-slate-500 mt-1">
                                    Agrega el primer método para comenzar.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Paginación --}}
        @if ($paymentMethods->hasPages())

            <div class="px-6 py-4 border-t border-slate-200">

                {{ $paymentMethods->links() }}

            </div>

        @endif

    </div>

</div>


{{-- Modal de creación --}}
@include('payment-methods.create')


{{-- Modal de edición --}}
@include('payment-methods.edit')


{{-- JavaScript --}}
<script>

const paymentMethodsData = {{ Js::from(
    $paymentMethods->getCollection()
        ->mapWithKeys(fn ($method) => [
            $method->id => [
                'id' => $method->id,
                'name' => $method->name,
                'code' => $method->code,
                'requires_reference' => $method->requires_reference,
                'active' => $method->active,
                'sort_order' => $method->sort_order,
            ],
        ])
        ->all()
) }};


  function openCreateModal() {
    const modal = document.getElementById('create-payment-method-modal');
    const form = document.getElementById('create-payment-method-form');

    if (!modal || !form) {
        console.error('No se encontró el modal o el formulario de creación.');
        return;
    }

    form.reset();

    const active = document.getElementById('create-payment-method-active');
    const sortOrder = document.getElementById('create-payment-method-sort-order');

    if (active) {
        active.checked = true;
    }

    if (sortOrder) {
        sortOrder.value = 0;
    }

    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}


    function closeCreateModal() {
        document.getElementById('create-payment-method-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }


    function openEditModal(id) {
        const method = paymentMethodsData[id];

        if (!method) {
            return;
        }

        const modal = document.getElementById('edit-payment-method-modal');

        document.getElementById('edit-payment-method-form').action =
            `/payment-methods/${method.id}`;

        document.getElementById('edit-payment-method-name').value = method.name;
        document.getElementById('edit-payment-method-code').value = method.code;

        document.getElementById('edit-payment-method-requires-reference').checked =
            Boolean(method.requires_reference);

        document.getElementById('edit-payment-method-active').checked =
            Boolean(method.active);

        document.getElementById('edit-payment-method-sort-order').value =
            method.sort_order;

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }


    function closeEditModal() {
        document.getElementById('edit-payment-method-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }


    // Cerrar al pulsar fuera del contenido del modal.
    document.querySelectorAll('[data-modal-backdrop]').forEach(function (backdrop) {
        backdrop.addEventListener('click', function (event) {
            if (event.target === backdrop) {
                closeCreateModal();
                closeEditModal();
            }
        });
    });


    // Cerrar con Escape.
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
        }
    });


    // Búsqueda en la página actual.
    document.getElementById('payment-method-search')
        .addEventListener('input', function () {

            const query = this.value.toLowerCase().trim();

            document.querySelectorAll('.payment-method-row').forEach(function (row) {

                const text = row.textContent.toLowerCase();

                row.classList.toggle('hidden', !text.includes(query));

            });

        });
</script>

@endsection