
    function openProductModal() {

        const modal = document.getElementById('productModal');

        modal.classList.remove('hidden');

        modal.setAttribute('aria-hidden', 'false');

        document.body.classList.add('overflow-hidden');

    }


    function closeProductModal() {

        const modal = document.getElementById('productModal');

        modal.classList.add('hidden');

        modal.setAttribute('aria-hidden', 'true');

        document.body.classList.remove('overflow-hidden');

    }


    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            closeProductModal();

        }

    });

let productVariantIndex = 1000;


function toggleProductVariants(checkbox) {

    const form = checkbox.closest('form');

    if (!form) {
        console.error('No se encontró el formulario.');
        return;
    }

    const singlePrice =
        form.querySelector('[data-single-price]');

    const variantsSection =
        form.querySelector('[data-variants-section]');

    const variantsContainer =
        form.querySelector('[data-variants-container]');

    const priceInput =
        form.querySelector('[data-price]');


    if (!singlePrice || !variantsSection || !variantsContainer) {

        console.error(
            'No se encontraron los elementos de variantes.',
            {
                singlePrice,
                variantsSection,
                variantsContainer
            }
        );

        return;
    }


    if (checkbox.checked) {

        // Ocultar precio único
        singlePrice.classList.add('hidden');

        // Mostrar sección de variantes
        variantsSection.classList.remove('hidden');

        // Limpiar precio único
        if (priceInput) {
            priceInput.value = '';
        }

        // Crear la primera variante
        if (variantsContainer.children.length === 0) {

            addProductVariant(variantsContainer);

        }

        variantsContainer
    .querySelectorAll('input[name^="variants"]')
    .forEach(input => input.required = true);

    } else {

        // Mostrar precio único
        singlePrice.classList.remove('hidden');

        // Ocultar variantes
        variantsSection.classList.add('hidden');

        variantsContainer
    .querySelectorAll('input[name^="variants"]')
    .forEach(input => input.required = false);

    }
}

function addProductVariant(container) {

    const index = productVariantIndex++;

    const row = document.createElement('div');

    row.className =
        'variant-row flex items-end gap-3 rounded-lg border border-slate-200 bg-white p-3';

    row.innerHTML = `
        <div class="min-w-0 flex-1">

            <label class="mb-1 block text-xs font-medium text-slate-500">
                Nombre
            </label>

            <input
                type="text"
                name="variants[${index}][name]"
                placeholder="Ej. Sencilla"
        
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
            >

        </div>

        <div class="w-32 shrink-0">

            <label class="mb-1 block text-xs font-medium text-slate-500">
                Precio base
            </label>

            <div class="relative">

                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400">
                    L
                </span>

                <input
                    type="number"
                    name="variants[${index}][price]"
                    placeholder="0.00"
                    min="0"
                    step="0.01"
                
                    data-variant-base-price
                    class="w-full rounded-lg border border-slate-300 bg-white py-2 pl-7 pr-2 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >

            </div>

        </div>

        <div class="w-32 shrink-0">

            <label class="mb-1 block text-xs font-medium text-slate-500">
                Precio final
            </label>

            <div class="relative">

                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400">
                    L
                </span>

                <input
                    type="text"
                    data-variant-final-price
                    value="0.00"
                    readonly
                    class="w-full rounded-lg border border-slate-200 bg-slate-100 py-2 pl-7 pr-2 text-sm text-slate-700"
                >

            </div>

        </div>

        <button
            type="button"
            onclick="this.closest('.variant-row').remove()"
            class="h-9 w-9 shrink-0 rounded-lg border border-red-200 text-red-500 transition hover:bg-red-50"
            title="Eliminar variante"
        >
            ✕
        </button>
    `;

    container.appendChild(row);

    // Actualizar precio final inmediatamente
    const form = container.closest('form');

    if (form && typeof updateProductFinalPrices === 'function') {
        updateProductFinalPrices(form);
    }
}

function openEditProductModal(id) {
    const modal = document.getElementById('editProductModal' + id);

    if (!modal) {
        return;
    }

    const form = modal.querySelector('form');

    if (form) {
        // Guardamos el estado original completo del formulario
        form.dataset.originalHtml = form.innerHTML;
    }

    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');

    document.body.classList.add('overflow-hidden');
}


function closeEditProductModal(id) {
    const modal = document.getElementById('editProductModal' + id);

    if (!modal) {
        return;
    }

    const form = modal.querySelector('form');

    if (form && form.dataset.originalHtml) {
        // Restauramos exactamente como estaba al abrir
        form.innerHTML = form.dataset.originalHtml;

        // Volvemos a inicializar el cálculo de precios
        initializeProductPriceCalculation(form);
    }

    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');

    document.body.classList.remove('overflow-hidden');
}

document.addEventListener('DOMContentLoaded', function () {

    document
        .querySelectorAll('form[data-product-form]')
        .forEach(function (form) {

            const checkbox =
                form.querySelector('[data-has-variants]');

            const variantsSection =
                form.querySelector('[data-variants-section]');

            const singlePriceSection =
                form.querySelector('[data-single-price]');

            if (!checkbox) {
                return;
            }

            if (checkbox.checked) {

                singlePriceSection.classList.add('hidden');

                variantsSection.classList.remove('hidden');


            }
            initializeProductPriceCalculation(form);

        });

});

function filterProducts() {

    const search =
        document.getElementById('search').value.toLowerCase().trim();

    const category =
        document.getElementById('categoryFilter').value;

    const status =
        document.getElementById('statusFilter').value;

    const rows =
        document.querySelectorAll('.product-row');


    rows.forEach(function (row) {

        const name =
            row.dataset.name || '';

        const rowCategory =
            row.dataset.category || '';

        const rowStatus =
            row.dataset.active || '';


        const matchesSearch =
            name.includes(search);

        const matchesCategory =
            !category ||
            rowCategory === category;

        const matchesStatus =
            !status ||
            rowStatus === status;


        if (
            matchesSearch &&
            matchesCategory &&
            matchesStatus
        ) {

            row.classList.remove('hidden');

        } else {

            row.classList.add('hidden');

        }

    });

}

document.addEventListener('DOMContentLoaded', function () {

    document
        .getElementById('search')
        .addEventListener('input', filterProducts);

    document
        .getElementById('categoryFilter')
        .addEventListener('change', filterProducts);

    document
        .getElementById('statusFilter')
        .addEventListener('change', filterProducts);

});


function updateProductFinalPrices(form) {

    const taxSelect = form.querySelector('select[name="tax_id"]');

    if (!taxSelect) {
        return;
    }

    const selectedOption =
        taxSelect.options[taxSelect.selectedIndex];

    const taxRate =
        parseFloat(selectedOption?.dataset.taxRate || 0);


    // =========================
    // Precio único
    // =========================

    const basePriceInput =
        form.querySelector('[data-price]');

    const finalPriceInput =
        form.querySelector('[data-final-price]');

    if (basePriceInput && finalPriceInput) {

        const basePrice =
            parseFloat(basePriceInput.value) || 0;

        const finalPrice =
            basePrice * (1 + taxRate / 100);

        finalPriceInput.value =
            finalPrice.toFixed(2);
    }


    // =========================
    // Variantes
    // =========================

    form.querySelectorAll('.variant-row').forEach(function (row) {

        const baseInput =
            row.querySelector('[data-variant-base-price]');

        const finalInput =
            row.querySelector('[data-variant-final-price]');

        if (!baseInput || !finalInput) {
            return;
        }

        const basePrice =
            parseFloat(baseInput.value) || 0;

        const finalPrice =
            basePrice * (1 + taxRate / 100);

        finalInput.value =
            finalPrice.toFixed(2);
    });
}

function initializeProductPriceCalculation(form) {

    const taxSelect =
        form.querySelector('select[name="tax_id"]');

    if (!taxSelect) {
        return;
    }

    taxSelect.addEventListener('change', function () {

        updateProductFinalPrices(form);

    });


    const basePriceInput =
        form.querySelector('[data-price]');

    if (basePriceInput) {

        basePriceInput.addEventListener('input', function () {

            updateProductFinalPrices(form);

        });

    }


    form.addEventListener('input', function (event) {

        if (
            event.target.matches('[data-variant-base-price]')
        ) {

            updateProductFinalPrices(form);

        }

    });


    // Calcular al cargar
    updateProductFinalPrices(form);
}
window.openProductModal = openProductModal;
window.closeProductModal = closeProductModal;
window.toggleProductVariants = toggleProductVariants;
window.addProductVariant = addProductVariant;
window.openEditProductModal = openEditProductModal;
window.closeEditProductModal = closeEditProductModal;
window.filterProducts = filterProducts;