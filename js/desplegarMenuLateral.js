document.addEventListener('DOMContentLoaded', () => {
    const btnDesplegar = document.getElementById('desplegar-menu');
    const capaOverflow = document.getElementById('overflow-capa');
    const desplegar = document.querySelector('.contenedor-2');

    if (!btnDesplegar || !desplegar) return;

    //  clase 'abierto' en contenedor-2 al hacer clic en el botón
    btnDesplegar.addEventListener('click', (e) => {
        e.stopPropagation();
        desplegar.classList.toggle('abierto');
         capaOverflow.classList.remove('oculto');
         capaOverflow.classList.toggle('mostrar-block');
    });

    // Cerrar el men al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (
            desplegar.classList.contains('abierto') &&
            !desplegar.contains(e.target) &&
            !btnDesplegar.contains(e.target)
        ) {
            desplegar.classList.remove('abierto');
            capaOverflow.classList.remove('mostrar-block');
             capaOverflow.classList.toggle('oculto');
        }
    });

    // Cerrar con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && desplegar.classList.contains('abierto')) {
            desplegar.classList.remove('abierto');
            capaOverflow.classList.remove('mostrar-block');
            capaOverflow.classList.toggle('oculto');
        }
    });
});