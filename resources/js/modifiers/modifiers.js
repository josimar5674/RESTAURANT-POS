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
// ESCAPE
// ============================================================

document.addEventListener('keydown', function (event) {

    if (event.key !== 'Escape') {
        return;
    }

    // Cerrar modal de nuevo grupo
    closeModifierGroupModal();

    // Cerrar edición de grupos
    document
        .querySelectorAll('[id^="editModifierGroupModal"]')
        .forEach(function (modal) {

            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
            }

        });


    // Cerrar modal de nueva opción
    closeModifierOptionModal();

    // Cerrar edición de opciones
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

window.openModifierGroupModal = openModifierGroupModal;
window.closeModifierGroupModal = closeModifierGroupModal;

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