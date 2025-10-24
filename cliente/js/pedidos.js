document.addEventListener('DOMContentLoaded', function () {
    // Manejar la navegación del perfil
    const navLinks = document.querySelectorAll('.caja-nav[data-href]');
    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            window.location.href = this.dataset.href;
        });
    });

    // Manejar el botón "Ver detalles"
    const detailButtons = document.querySelectorAll('.btn-detalles');
    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const idPedido = this.dataset.id;
            const detalles = document.getElementById('detalles-' + idPedido);

            if (detalles) {
                const isVisible = detalles.style.display === 'block';
                detalles.style.display = isVisible ? 'none' : 'block';
                this.textContent = isVisible ? 'Ver detalles' : 'Ocultar detalles';
            }
        });
    });
});
