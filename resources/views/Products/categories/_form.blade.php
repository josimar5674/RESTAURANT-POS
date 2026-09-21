<form
    action="{{ isset($category)
        ? route('product-categories.update', $category)
        : route('product-categories.store') }}"
    method="POST"
>
    @csrf

    @if(isset($category))
        @method('PUT')
    @endif

                @csrf

                <div class="space-y-6">

                    {{-- Nombre --}}
                    <div>

                        <label
                            for="name"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Nombre
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $category->name ?? '') }}"
                            placeholder="Ej. Entradas"
                            required
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Descripción --}}
                    <div>

                        <label
                            for="description"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Descripción
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Descripción opcional de la categoría"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                       >{{ old('description', $category->description ?? '') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

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
                            id="sort_order"
                            name="sort_order"
                            value="{{ old('sort_order', $category->sort_order ?? 1) }}"
                            min="1"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                        >

                        <p class="mt-1 text-xs text-slate-500">
                            Define la posición de la categoría en el menú.
                        </p>

                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Estado --}}
                    <div class="flex items-center gap-3">

                        <input
                            type="checkbox"
                            id="active"
                            name="active"
                            value="1"
                            {{ old('active', $category->active ?? true) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-slate-300"
                        >

                        <div>

                            <label
                                for="active"
                                class="text-sm font-medium text-slate-700"
                            >
                                Categoría activa
                            </label>

                            <p class="text-xs text-slate-500">
                                Los productos podrán utilizar esta categoría.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Botones --}}
                <div class="mt-8 flex justify-end gap-3 border-t border-slate-200 pt-6">

                    <a
                        href="{{ route('product-categories.index') }}"
                        class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700"
                    >
                    {{ isset($category) ? 'Actualizar categoría' : 'Guardar categoría' }}                    </button>

                </div>

            </form>