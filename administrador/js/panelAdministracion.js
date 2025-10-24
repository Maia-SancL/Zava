document.querySelectorAll('.btn-desplegable').forEach(btn => {
    btn.addEventListener('click', () => {
        const producto = btn.closest('.producto');
        const detalleActual = producto.querySelector('.detalles-producto');

        document.querySelectorAll('.detalles-producto').forEach(detalle => {
            if (detalle !== detalleActual) {
                detalle.classList.remove('activo');
                detalle.classList.add('oculto');
            }
        });

        detalleActual.classList.toggle('activo');
        detalleActual.classList.toggle('oculto');
    });
});

document.querySelectorAll('.form-agregar-stock').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const cantidad = prompt("¿Cuántas unidades deseas agregar al stock?", "1");
        
        if (cantidad !== null && !isNaN(cantidad) && Number.isInteger(Number(cantidad)) && parseInt(cantidad) > 0) {
            this.querySelector('.cantidad-a-agregar').value = parseInt(cantidad);
            this.submit();
        } else if (cantidad !== null) {
            alert("Por favor, introduce un número entero válido y mayor que cero.");
        }
    });
});
