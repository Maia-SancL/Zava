document.addEventListener('DOMContentLoaded', function() {
    // Lógica de filtrado
    const filtroTipo = document.getElementById('filtro-tipo-comida');
    const filtroTiempo = document.getElementById('filtro-tiempo');
    const recetas = document.querySelectorAll('.globalReceta');

    function filtrarRecetas() {
        const tipo = filtroTipo.value;
        const tiempo = filtroTiempo.value;

        recetas.forEach(receta => {
            const tipoComida = receta.dataset.tipoComida;
            const tiempoPreparacion = parseInt(receta.dataset.tiempo, 10);
            let mostrar = true;

            if (tipo && tipoComida !== tipo) {
                mostrar = false;
            }

            if (tiempo) {
                if (tiempo === '61' && tiempoPreparacion <= 60) {
                    mostrar = false;
                } else if (tiempo < '61' && tiempoPreparacion > parseInt(tiempo, 10)) {
                    mostrar = false;
                }
            }

            receta.style.display = mostrar ? '' : 'none';
        });
    }

    if (filtroTipo && filtroTiempo) {
        filtroTipo.addEventListener('change', filtrarRecetas);
        filtroTiempo.addEventListener('change', filtrarRecetas);
    }

    // Lógica de eliminación con AJAX
    document.querySelectorAll('.botonEliminar').forEach(button => {
        button.addEventListener('click', function() {
            const idReceta = this.dataset.id;
            if (confirm('¿Estás seguro de que deseas eliminar esta receta? Esta acción es irreversible.')) {
                fetch('/Zava/php/cliente/eliminarReceta.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + idReceta
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.globalReceta').remove();
                    } else {
                        alert(data.message || 'Error al eliminar la receta.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error de conexión al intentar eliminar la receta.');
                });
            }
        });
    });
});
