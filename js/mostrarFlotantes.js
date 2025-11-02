function mostrarFlotante(elem) {
    const contenedorFlotante = document.querySelector('.contenedor-filtros');
    if (!contenedorFlotante) return;

    const btnCerrar = contenedorFlotante.querySelector('.btn-cerrar-flotante');

    const cerrarFlotante = () => {
        contenedorFlotante.classList.remove('mostrar-flex');
        contenedorFlotante.classList.add('oculto');
        document.removeEventListener('click', clickFuera);
    };

    const clickFuera = (e) => {
        if (
            !contenedorFlotante.contains(e.target) &&
            !elem.contains(e.target)
        ) {
            cerrarFlotante();
        }
    };

    // Si está oculto, mostrarlo
    if (contenedorFlotante.classList.contains('oculto')) {
        contenedorFlotante.classList.remove('oculto');
        contenedorFlotante.classList.add('mostrar-flex');

        // Cerrar con el botón
        if (btnCerrar) {
            btnCerrar.addEventListener('click', cerrarFlotante, { once: true });
        }

        // Detectar clic fuera
        setTimeout(() => {
            document.addEventListener('click', clickFuera);
        }, 0);
    } else {
        cerrarFlotante();
    }
}
