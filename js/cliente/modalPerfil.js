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
});
