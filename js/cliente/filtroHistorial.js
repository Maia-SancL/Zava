document.addEventListener('DOMContentLoaded', function() {
    const filtroInput = document.getElementById('filtro-historial');
    const historialItems = document.querySelectorAll('.globalCosas');

    if (filtroInput) {
        filtroInput.addEventListener('input', function() {
            const filtroTexto = this.value.toLowerCase().trim();

            historialItems.forEach(function(item) {
                const nombreElemento = item.querySelector('h5');
                if (nombreElemento) {
                    const nombreTexto = nombreElemento.textContent.toLowerCase();
                    if (nombreTexto.includes(filtroTexto)) {
                        item.style.display = ''; 
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        });
    }
});
