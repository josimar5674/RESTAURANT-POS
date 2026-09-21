    function openCategoryModal() {

        const modal = document.getElementById('categoryModal');

        modal.classList.remove('hidden');

        modal.setAttribute('aria-hidden', 'false');

        document.body.classList.add('overflow-hidden');

    }


    function closeCategoryModal() {

        const modal = document.getElementById('categoryModal');

        modal.classList.add('hidden');

        modal.setAttribute('aria-hidden', 'true');

        document.body.classList.remove('overflow-hidden');

    }


    // Cerrar con ESC
    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            closeCategoryModal();

        }

    });


function openEditCategoryModal(id) {

    const modal = document.getElementById(
        'editCategoryModal' + id
    );

    modal.classList.remove('hidden');

    modal.setAttribute('aria-hidden', 'false');

    document.body.classList.add('overflow-hidden');
}


function closeEditCategoryModal(id) {

    const modal = document.getElementById(
        'editCategoryModal' + id
    );

    modal.classList.add('hidden');

    modal.setAttribute('aria-hidden', 'true');

    document.body.classList.remove('overflow-hidden');
}


window.openCategoryModal = openCategoryModal;
window.closeCategoryModal = closeCategoryModal;
window.openEditCategoryModal = openEditCategoryModal;
window.closeEditCategoryModal = closeEditCategoryModal;