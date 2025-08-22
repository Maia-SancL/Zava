document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editProfileModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.querySelector('.close-btn');

    if (openBtn) {
        openBtn.addEventListener('click', function() {
            if (modal) modal.style.display = 'flex';
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            if (modal) modal.style.display = 'none';
        });
    }

    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            if (modal) modal.style.display = 'none';
        }
    });

    const deleteForm = document.getElementById('deleteAccountFormCliente');

    if (deleteForm) {
        deleteForm.addEventListener('submit', function(event) {
            const confirmDelete = confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción es irreversible.');
            if (!confirmDelete) {
                event.preventDefault();
            }
        });
    }
});
