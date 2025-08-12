function mostrarFavoritos(tipo) {
    document.querySelector('.cont-opciones').style.display = 'none';
    document.getElementById('contenido-favoritos').style.display = 'block';
    
    const titulos = {
        'recetas': 'Recetas Favoritas',
        'restaurantes': 'Restaurantes Favoritos',
        'productos': 'Productos Favoritos'
    };
    document.getElementById('titulo-favoritos').textContent = titulos[tipo];
    
    document.getElementById('grid-favoritos').innerHTML = '<div class="loading">Cargando favoritos...</div>';
    
    fetch('obtener_favoritos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'tipo=' + encodeURIComponent(tipo)
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('grid-favoritos').innerHTML = data;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('grid-favoritos').innerHTML = '<div class="sin-favoritos-dinamico">Error al cargar los favoritos. Inténtalo de nuevo.</div>';
    });
}

function ocultarFavoritos() {
    document.querySelector('.cont-opciones').style.display = 'flex';
    document.getElementById('contenido-favoritos').style.display = 'none';
}

function eliminarFavoritoDinamico(tipo, id) {
    if(confirm('¿Estás seguro de que quieres eliminar este elemento de tus favoritos?')) {
        fetch('eliminar_favorito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'tipo=' + encodeURIComponent(tipo) + '&id=' + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const tipoActual = document.getElementById('titulo-favoritos').textContent.toLowerCase().includes('recetas') ? 'recetas' :
                                document.getElementById('titulo-favoritos').textContent.toLowerCase().includes('restaurantes') ? 'restaurantes' : 'productos';
                mostrarFavoritos(tipoActual);
            } else {
                alert('Error al eliminar el favorito: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el favorito');
        });
    }
}
