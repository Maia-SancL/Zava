document.addEventListener('DOMContentLoaded', () => {
    const btnDesplegar = document.getElementById('desplegar-menu');
    const capaOverflow = document.getElementById('overflow-capa');
    const contenedor2 = document.querySelector('.contenedor-2');

    if (!btnDesplegar || !contenedor2) return;

    //  clase 'abierto' en contenedor-2 al hacer clic en el botón
    btnDesplegar.addEventListener('click', (e) => {
        e.stopPropagation();
        contenedor2.classList.toggle('abierto');
        capaOverflow.classList.remove('oculto');
        capaOverflow.classList.toggle('mostrar-block');
    });

    // Cerrar el men al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (
            contenedor2.classList.contains('abierto') &&
            !contenedor2.contains(e.target) &&
            !btnDesplegar.contains(e.target)
        ) {
            contenedor2.classList.remove('abierto');
            capaOverflow.classList.remove('mostrar-block');
            capaOverflow.classList.toggle('oculto');
        }
    });

    // Cerrar con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && contenedor2.classList.contains('abierto')) {
            contenedor2.classList.remove('abierto');
            capaOverflow.classList.remove('mostrar-block');
            capaOverflow.classList.toggle('oculto');
        }
    });
});