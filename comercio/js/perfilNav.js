document.addEventListener('DOMContentLoaded', () => {
    const navContainer = document.querySelector('.cont-nav');
    if (!navContainer) return;

    const misProductosBtn = Array.from(navContainer.querySelectorAll('.caja-nav a')).find(el => el.textContent.trim() === 'Mis Productos');
    const misVentasBtn = Array.from(navContainer.querySelectorAll('.caja-nav a')).find(el => el.textContent.trim() === 'Mis Ventas');

    if (misProductosBtn) {
        misProductosBtn.parentElement.addEventListener('click', () => {
            location.href = '/Zava/php/comercio/perfil/perfil.php';
        });
    }

    if (misVentasBtn) {
        misVentasBtn.parentElement.addEventListener('click', () => {
            location.href = '/Zava/php/comercio/perfil/misVentas.php';
        });
    }
});
