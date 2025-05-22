function bandejaUsuario(event) {
    event.stopPropagation();
    var dropdown = document.getElementById('userDropdown');
    if (dropdown) {
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }
}
document.addEventListener('click', function() {
    var dropdown = document.getElementById('userDropdown');
    if (dropdown) dropdown.style.display = 'none';
});