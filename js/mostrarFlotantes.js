function mostrarFlotante(elem) {
    const contenedorFlotante = document.querySelector('.contenedor-filtros');
    const capaOverflow = document.getElementById('overflow-capa');

    if (!contenedorFlotante) return;

    // Mostrar u ocultar el contenedor
    if (contenedorFlotante.classList.contains('oculto')) {
        contenedorFlotante.classList.remove('oculto');
        contenedorFlotante.classList.add('mostrar-flex');
        contenedorFlotante.classList.add('mostrar-flex');
        capaOverflow.classList.add('mostrar-block');

        // evento solo cuando se abre
        const btnCerrar = contenedorFlotante.querySelector('.btn-cerrar-flotante');
        if (btnCerrar) {
            btnCerrar.addEventListener('click', () => {
                contenedorFlotante.classList.remove('mostrar-flex');
                contenedorFlotante.classList.add('oculto');
                capaOverflow.classList.remove('mostrar-block');
                capaOverflow.classList.add('oculto');
            }, { once: true }); // Solo una vez
        }


    } else {
        contenedorFlotante.classList.remove('mostrar-flex');
        contenedorFlotante.classList.add('oculto');
        capaOverflow.classList.remove('mostrar-block');
        capaOverflow.classList.add('oculto');
    }

    document.addEventListener('click', (e) => {
        if (
            contenedorFlotante.classList.contains('mostrar-flex') &&
            !contenedorFlotante.contains(e.target)
        ) {
            contenedorFlotante.classList.remove('mostrar-flex');
            capaOverflow.classList.remove('mostrar-block');
            capaOverflow.classList.toggle('oculto');
        }
    });

}
