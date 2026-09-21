
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

    } else {

        // Mostrar precio único
        singlePrice.classList.remove('hidden');

        // Ocultar variantes
        variantsSection.classList.add('hidden');

    }
}

function addProductVariant(container) {

    const index = productVariantIndex++;
   

    const row = document.createElement('div');

    row.className =
        'variant-row flex items-end gap-3 rounded-lg border border-slate-200 bg-white p-3';

    row.innerHTML = `
        <div class="flex-1">

            <label class="mb-1 block text-xs font-medium text-slate-500">
                Nombre
            </label>

            <input
                type="text"
                name="variants[${index}][name]"
                placeholder="Ej. Sencilla"
                required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
            >

        </div>

        <div class="w-32">

            <label class="mb-1 block text-xs font-medium text-slate-500">
                Precio
            </label>

            <div class="relative">

                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400">
                    L
                </span>

                <input
                    type="number"
                    name="variants[${index}][price]"
                    placeholder="0.00"
                    min="0"
                    step="0.01"
                    required
                    class="w-full rounded-lg border border-slate-300 bg-white py-2 pl-7 pr-2 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >

            </div>

        </div>

        <button
            type="button"
            onclick="this.closest('.variant-row').remove()"
            class="rounded-lg border border-red-200 px-3 py-2 text-sm text-red-500 transition hover:bg-red-50"
        >
            ✕
        </button>
    `;

    container.appendChild(row);
}

function openEditProductModal(id) {

    const modal = document.getElementById(
        'editProductModal' + id
    );

    modal.classList.remove('hidden');

    modal.setAttribute('aria-hidden', 'false');

    document.body.classList.add('overflow-hidden');
}


function closeEditProductModal(id) {

    const modal = document.getElementById(
        'editProductModal' + id
    );

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

window.openProductModal = openProductModal;
window.closeProductModal = closeProductModal;
window.toggleProductVariants = toggleProductVariants;
window.addProductVariant = addProductVariant;
window.openEditProductModal = openEditProductModal;
window.closeEditProductModal = closeEditProductModal;
window.filterProducts = filterProducts;