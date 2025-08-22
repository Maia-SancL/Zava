document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editProfileModal');
    const openBtn = document.querySelector('.btn-editar');
    const closeBtn = document.querySelector('.close-btn');
    const deleteForm = document.getElementById('deleteAccountForm');

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

    if (deleteForm) {
        deleteForm.addEventListener('submit', function(event) {
            const confirmDelete = confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción es irreversible y se borrarán todos tus datos, incluidos tus productos.');
            if (!confirmDelete) {
                event.preventDefault();
            }
        });
    }
});
