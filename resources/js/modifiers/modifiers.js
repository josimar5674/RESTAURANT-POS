// ============================================================
// GRUPOS DE MODIFICADORES
// ============================================================

function openModifierGroupModal() {
    const modal = document.getElementById('modifierGroupModal');

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');

    document.body.classList.add('overflow-hidden');
}


function closeModifierGroupModal() {
    const modal = document.getElementById('modifierGroupModal');

    if (!modal) return;

    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');

    document.body.classList.remove('overflow-hidden');
}


function openEditModifierGroupModal(id) {
    const modal =
        document.getElementById('editModifierGroupModal' + id);

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');

    document.body.classList.add('overflow-hidden');
}


function closeEditModifierGroupModal(id) {
    const modal =
        document.getElementById('editModifierGroupModal' + id);

    if (!modal) return;

    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');

    document.body.classList.remove('overflow-hidden');
}


// ============================================================
// OPCIONES DE MODIFICADORES
// ============================================================

function openModifierOptionModal() {
    const modal = document.getElementById('modifierOptionModal');

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');

    document.body.classList.add('overflow-hidden');
}


function closeModifierOptionModal() {
    const modal = document.getElementById('modifierOptionModal');

    if (!modal) return;

    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');

    document.body.classList.remove('overflow-hidden');
}


function openEditModifierOptionModal(id) {
    const modal =
        document.getElementById('editModifierOptionModal' + id);

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');

    document.body.classList.add('overflow-hidden');
}


function closeEditModifierOptionModal(id) {
    const modal =
        document.getElementById('editModifierOptionModal' + id);

    if (!modal) return;

    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');

    document.body.classList.remove('overflow-hidden');
}


// ============================================================
// PRECIO DE MODIFICADORES + IMPUESTO
// ============================================================

function updateModifierOptionPrice(input) {

    const price = parseFloat(input.value) || 0;
    const taxRate = parseFloat(input.dataset.taxRate) || 0;

    const finalPrice = price * (1 + taxRate / 100);

    const finalPriceElement = input
    .closest('form')
    .querySelector('[id*="final-price"]');

        

    if (!finalPriceElement) {
        return;
    }

    finalPriceElement.textContent =
        'L ' + finalPrice.toFixed(2);
}


document.addEventListener('DOMContentLoaded', function () {

    document
        .querySelectorAll('input[name="price_adjustment"]')
        .forEach(function (input) {

            updateModifierOptionPrice(input);

        });

});


// ============================================================
// ESCAPE
// ============================================================

document.addEventListener('keydown', function (event) {

    if (event.key !== 'Escape') {
        return;
    }

    closeModifierGroupModal();

    document
        .querySelectorAll('[id^="editModifierGroupModal"]')
        .forEach(function (modal) {

            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
            }

        });


    closeModifierOptionModal();

    document
        .querySelectorAll('[id^="editModifierOptionModal"]')
        .forEach(function (modal) {

            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
            }

        });


    document.body.classList.remove('overflow-hidden');
});


// ============================================================
// FUNCIONES DISPONIBLES PARA BLADE
// ============================================================

window.openModifierGroupModal =
    openModifierGroupModal;

window.closeModifierGroupModal =
    closeModifierGroupModal;

window.openEditModifierGroupModal =
    openEditModifierGroupModal;

window.closeEditModifierGroupModal =
    closeEditModifierGroupModal;

window.openModifierOptionModal =
    openModifierOptionModal;

window.closeModifierOptionModal =
    closeModifierOptionModal;

window.openEditModifierOptionModal =
    openEditModifierOptionModal;

window.closeEditModifierOptionModal =
    closeEditModifierOptionModal;

window.updateModifierOptionPrice =
    updateModifierOptionPrice;