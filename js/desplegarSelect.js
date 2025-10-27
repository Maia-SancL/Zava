function cerrarTodosLosSelects() {
    document.querySelectorAll('.caja-filtro').forEach(filtro => {
        const principal = filtro.querySelector('.caja-filtro-principal');
        const contenido = filtro.querySelector('.caja-filtro-contenido');
        const icon = principal?.querySelector('.icon');

        if (contenido && !contenido.classList.contains('oculto')) {
            contenido.classList.remove('mostrar-flex');
            contenido.classList.add('oculto');
            if (icon) icon.style.transform = 'rotate(0deg)';
            principal.style.borderRadius = '.8rem';
        }
    });
}

function desplegarSelect(elem) {
    const contenido = elem.nextElementSibling;
    const iconRotado = elem.querySelector('.icon');
    const estaOculto = contenido.classList.contains('oculto');

    // Cerrar otros antes de abrir uno nuevo
    cerrarTodosLosSelects();

    if (estaOculto) {
        contenido.classList.remove('oculto');
        contenido.classList.add('mostrar-flex');
        if (iconRotado) iconRotado.style.transform = 'rotate(180deg)';
        elem.style.borderRadius = '.8rem .8rem 0 0';
    }
}

function seleccionarOpcion(opcion) {
    const contenedor = opcion.closest('.caja-filtro');
    const principal = contenedor.querySelector('.caja-filtro-principal');
    const textoPrincipal = principal.querySelector('p');
    const icon = principal.querySelector('.icon');
    const contenido = contenedor.querySelector('.caja-filtro-contenido');

    // Cambia el texto principal
    textoPrincipal.textContent = opcion.textContent;

    // Cierra el menú
    contenido.classList.remove('mostrar-flex');
    contenido.classList.add('oculto');
    if (icon) icon.style.transform = 'rotate(0deg)';
    principal.style.borderRadius = '.8rem';
}

// Detectar clics globales
document.addEventListener('click', e => {
    const principal = e.target.closest('.caja-filtro-principal');
    const opcion = e.target.closest('.caja-filtro-contenido p');

    if (principal) {
        desplegarSelect(principal);
    } else if (opcion) {
        seleccionarOpcion(opcion);
    } else if (!e.target.closest('.caja-filtro')) {
        cerrarTodosLosSelects();
    }
});
