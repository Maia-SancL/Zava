
//TOP-MENU.PHP
//BANDEJA DE USUARIOS
function bandejaUsuario(event) {
    event.stopPropagation(); // Evita que el clic cierre inmediatamente el menú
    const dropdown = document.getElementById('userDropdown');
    if (dropdown) {
        dropdown.classList.toggle('show'); // Alterna la clase 'show' para mostrar/ocultar el menú
    }
}

// Cierra el menú si se hace clic fuera de él
document.addEventListener('click', function () {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown) {
        dropdown.classList.remove('show');
    }
});

    