<div
    id="edit-payment-method-modal"
    data-modal-backdrop
    class="hidden fixed inset-0 z-50 flex items-center justify-center
           bg-slate-900/60 backdrop-blur-sm p-4">

    <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">

            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Editar método de pago
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Modifica la configuración del método seleccionado.
                </p>
            </div>

            <button
                type="button"
                onclick="closeEditModal()"
                class="w-9 h-9 rounded-lg text-slate-500
                       hover:bg-slate-100 hover:text-slate-800 text-xl">

                &times;

            </button>

        </div>


        {{-- Formulario --}}
        <form
            id="edit-payment-method-form"
            method="POST"
            action="#">

            @csrf
            @method('PUT')

            <div class="p-6 space-y-5">

                {{-- Nombre --}}
                <div>

                    <label for="edit-payment-method-name"
                           class="block text-sm font-semibold text-slate-700 mb-2">

                        Nombre del método *

                    </label>

                    <input
                        type="text"
                        id="edit-payment-method-name"
                        name="name"
                        required
                        maxlength="100"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3
                               focus:border-blue-500 focus:ring-2
                               focus:ring-blue-100 outline-none">

                </div>


                {{-- Código --}}
                <div>

                    <label for="edit-payment-method-code"
                           class="block text-sm font-semibold text-slate-700 mb-2">

                        Código interno *

                    </label>

                    <input
                        type="text"
                        id="edit-payment-method-code"
                        name="code"
                        required
                        maxlength="50"
                        pattern="[a-zA-Z0-9_-]+"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3
                               focus:border-blue-500 focus:ring-2
                               focus:ring-blue-100 outline-none">

                </div>


                {{-- Orden --}}
                <div>

                    <label for="edit-payment-method-sort-order"
                           class="block text-sm font-semibold text-slate-700 mb-2">

                        Orden de visualización

                    </label>

                    <input
                        type="number"
                        id="edit-payment-method-sort-order"
                        name="sort_order"
                        min="0"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3
                               focus:border-blue-500 focus:ring-2
                               focus:ring-blue-100 outline-none">

                </div>


                {{-- Referencia --}}
                <label class="flex items-start gap-3 cursor-pointer">

                    <input
                        type="checkbox"
                        id="edit-payment-method-requires-reference"
                        name="requires_reference"
                        value="1"
                        class="mt-1 w-4 h-4 rounded border-slate-300 text-blue-600">

                    <span>
                        <span class="block text-sm font-semibold text-slate-700">
                            Requiere referencia
                        </span>

                        <span class="block text-xs text-slate-500 mt-1">
                            Solicita un número de autorización o referencia bancaria.
                        </span>
                    </span>

                </label>


                {{-- Estado --}}
                <label class="flex items-center gap-3 cursor-pointer">

                    <input
                        type="checkbox"
                        id="edit-payment-method-active"
                        name="active"
                        value="1"
                        class="w-4 h-4 rounded border-slate-300 text-blue-600">

                    <span class="text-sm font-semibold text-slate-700">
                        Método activo
                    </span>

                </label>

            </div>


            {{-- Acciones --}}
            <div class="flex justify-end gap-3 px-6 py-5 bg-slate-50 border-t border-slate-200">

                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="px-5 py-2.5 rounded-xl border border-slate-300
                           text-slate-700 font-semibold hover:bg-slate-100 transition">

                    Cancelar

                </button>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-blue-600 text-white
                           font-semibold hover:bg-blue-700 transition">

                    Guardar cambios

                </button>

            </div>

        </form>

    </div>

</div>