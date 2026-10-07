const taxModal = document.getElementById('taxModal');
const taxForm = document.getElementById('taxForm');

const taxModalTitle = document.getElementById('taxModalTitle');

const taxName = document.getElementById('tax_name');
const taxCode = document.getElementById('tax_code');
const taxRate = document.getElementById('tax_rate');
const taxDescription = document.getElementById('tax_description');
const taxSortOrder = document.getElementById('tax_sort_order');
const taxActive = document.getElementById('tax_active');

const taxMethod = document.getElementById('taxMethod');


/*
|--------------------------------------------------------------------------
| Abrir modal - Nuevo impuesto
|--------------------------------------------------------------------------
*/

function openTaxModal() {

    if (!taxModal || !taxForm) return;

    taxModalTitle.textContent = 'Nuevo impuesto';

    taxForm.action = '/taxes';

    taxMethod.innerHTML = '';

    taxName.value = '';
    taxCode.value = '';
    taxRate.value = '';
    taxDescription.value = '';
    taxSortOrder.value = '1';

    taxActive.checked = true;

    taxModal.classList.remove('hidden');
    taxModal.classList.add('flex');

    document.body.classList.add('overflow-hidden');

    setTimeout(() => {
        taxName.focus();
    }, 50);
}


/*
|--------------------------------------------------------------------------
| Editar impuesto
|--------------------------------------------------------------------------
*/

function editTax(tax) {

    if (!taxModal || !taxForm) return;

    taxModalTitle.textContent = 'Editar impuesto';

    taxForm.action = `/taxes/${tax.id}`;

    taxMethod.innerHTML = `
        <input type="hidden" name="_method" value="PUT">
    `;

    taxName.value = tax.name ?? '';
    taxCode.value = tax.code ?? '';
    taxRate.value = tax.rate ?? '';
    taxDescription.value = tax.description ?? '';
    taxSortOrder.value = tax.sort_order ?? 1;

    taxActive.checked = Boolean(tax.active);

    taxModal.classList.remove('hidden');
    taxModal.classList.add('flex');

    document.body.classList.add('overflow-hidden');

    setTimeout(() => {
        taxName.focus();
    }, 50);
}


/*
|--------------------------------------------------------------------------
| Cerrar modal
|--------------------------------------------------------------------------
*/

function closeTaxModal() {

    if (!taxModal) return;

    taxModal.classList.add('hidden');
    taxModal.classList.remove('flex');

    document.body.classList.remove('overflow-hidden');
}


/*
|--------------------------------------------------------------------------
| Cerrar haciendo click fuera del modal
|--------------------------------------------------------------------------
*/

if (taxModal) {

    taxModal.addEventListener('click', function (event) {

        if (event.target === taxModal) {
            closeTaxModal();
        }

    });

}


/*
|--------------------------------------------------------------------------
| Escape
|--------------------------------------------------------------------------
*/

document.addEventListener('keydown', function (event) {

    if (event.key === 'Escape') {
        closeTaxModal();
    }

});


/*
|--------------------------------------------------------------------------
| Exponer funciones para Blade
|--------------------------------------------------------------------------
*/

window.openTaxModal = openTaxModal;
window.editTax = editTax;
window.closeTaxModal = closeTaxModal;