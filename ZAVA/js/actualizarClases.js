function actualizarClase() {
    const contenedor = document.querySelector('.modo-responsive');
    if (window.innerWidth < 1024) {
        contenedor.classList.add('oculto');
        contenedor.classList.remove('mostrar-flex');
    } else {
        contenedor.classList.add('mostrar-flex');
        contenedor.classList.remove('oculto');
    }
}

window.addEventListener('DOMContentLoaded', actualizarClase);

// Ejecutar al redimensionar la pantalla
window.addEventListener('resize', actualizarClase);