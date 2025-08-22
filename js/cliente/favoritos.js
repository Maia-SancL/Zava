document.addEventListener('DOMContentLoaded', function() {
    const btnFav = document.getElementById('btn-favorito');
    if (!btnFav) return;

    btnFav.addEventListener('click', function(e) {
        e.preventDefault();

        const id = this.dataset.id;
        const tipo = this.dataset.tipo;
        let isFavorito = this.dataset.favorito === '1';

        if (!id || !tipo) {
            console.error('Error: Faltan atributos data-id o data-tipo en el botón de favorito.');
            return;
        }

        const url = isFavorito ? '/Zava/php/cliente/funciones/eliminarFavorito.php' : '/Zava/php/cliente/funciones/agregarFavorito.php';

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `tipo=${tipo}&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                isFavorito = !isFavorito;
                this.dataset.favorito = isFavorito ? '1' : '0';
                
                const iconSpan = this.querySelector('span#icon-fav');
                const iconContainer = iconSpan ? iconSpan : this;

                if (isFavorito) {
                    iconContainer.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54z"/>
                        </svg>`;
                } else {
                    iconContainer.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>`;
                }
            } else {
                console.error('Error:', data.message);
                alert(data.message || 'Error al actualizar favorito');
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('Error de conexión al intentar actualizar favorito.');
        });
    });
});
