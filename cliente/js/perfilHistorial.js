document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-favorito-historial').forEach(function(btnFav) {
        btnFav.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const tipo = this.getAttribute('data-tipo');
            let esFavorito = this.getAttribute('data-favorito') === '1';
            const url = esFavorito ? '/Zava/php/cliente/perfil/eliminar_favorito.php' : '/Zava/php/cliente/perfil/agregar_favorito.php';

            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `tipo=${tipo}&id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.setAttribute('data-favorito', esFavorito ? '0' : '1');
                    const icon = this.querySelector('svg');
                    if (esFavorito) {
                        icon.innerHTML = '<path fill="none" stroke="currentColor" stroke-width="2" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125z"/>';
                    } else {
                        icon.innerHTML = '<path fill="currentColor" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125"/>';
                    }
                } else {
                    alert(data.message || 'Error al actualizar favorito');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Error de conexión');
            });
        });
    });
});
