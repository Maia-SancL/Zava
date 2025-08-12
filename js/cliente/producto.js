document.addEventListener('DOMContentLoaded', function() {
    const productoContenedor = document.getElementById('producto-contenedor');
    if (!productoContenedor) return;

    const idProducto = productoContenedor.dataset.idProducto;

    // Carrito
    const btnSumar = document.querySelector('.sumar');
    const btnQuitar = document.querySelector('.quitar');
    const cantidadSpan = document.querySelector('.cantidad');
    const btnAgregarCarrito = document.querySelector('.btn-agregar-carrito');

    let cantidad = 1;

    if(btnSumar) {
        btnSumar.addEventListener('click', () => {
            cantidad++;
            cantidadSpan.textContent = cantidad;
        });
    }

    if(btnQuitar) {
        btnQuitar.addEventListener('click', () => {
            if (cantidad > 1) {
                cantidad--;
                cantidadSpan.textContent = cantidad;
            }
        });
    }

    if (btnAgregarCarrito) {
        btnAgregarCarrito.addEventListener('click', (e) => {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('accion', 'add_to_cart');
            formData.append('id_producto', idProducto);
            formData.append('cantidad', cantidad);

            fetch('/Zava/php/cliente/mostrarProducto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const carritoCount = document.getElementById('carrito-count');
                    if (carritoCount) {
                        carritoCount.textContent = data.total_productos;
                        carritoCount.classList.add('update');
                        setTimeout(() => carritoCount.classList.remove('update'), 500);
                    }
                } else {
                    alert(data.message || 'Error al agregar el producto al carrito.');
                }
            })
            .catch(error => {
                console.error('Error en la petición fetch:', error);
                window.location.href = '/Zava/php/cliente/carrito.php';
            });
        });
    }

    actualizarCantidad(1);
});
