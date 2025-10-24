document.addEventListener('DOMContentLoaded', function() {

    function attachProductListeners() {
        document.querySelectorAll('.producto').forEach(article => {
            // Eliminar listeners antiguos para evitar duplicados
            const newArticle = article.cloneNode(true);
            article.parentNode.replaceChild(newArticle, article);

            newArticle.addEventListener('click', function() {
                const href = this.dataset.href;
                if (href) {
                    window.location.href = href;
                }
            });
        });
    }

    function updateProducts() {
        const form = document.getElementById('filtros-restaurantes');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        const url = `/Zava/php/cliente/productos.php?${params}&ajax=1`;

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const productSection = document.querySelector('.section-productos');
                if (productSection) {
                    productSection.innerHTML = html;
                    attachProductListeners(); // Volver a añadir listeners a los nuevos productos
                }
            })
            .catch(error => console.error('Error al filtrar productos:', error));
    }

    const filterForm = document.getElementById('filtros-restaurantes');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            updateProducts();
        });
    }

    const orderSelect = document.getElementById('orden');
    if(orderSelect) {
        orderSelect.addEventListener('change', function() {
             updateProducts();
        });
    }

    // Adjuntar listeners iniciales
    attachProductListeners();
});
