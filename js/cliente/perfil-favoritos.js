function mostrarFavoritos(tipo) {
    document.querySelector('.cont-opciones').style.display = 'none';
    const contenedorFavoritos = document.getElementById('contenido-favoritos');
    contenedorFavoritos.style.display = 'block';
    
    const titulos = {
        'recetas': 'Recetas Favoritas',
        'restaurantes': 'Restaurantes Favoritos',
        'productos': 'Productos Favoritos'
    };
    document.getElementById('titulo-favoritos').textContent = titulos[tipo] || 'Favoritos';
    
    const gridFavoritos = document.getElementById('grid-favoritos');
    gridFavoritos.innerHTML = '<div class="loading">Cargando favoritos...</div>';
    
    // Usar GET en lugar de POST para simplificar
    fetch(`/Zava/php/cliente/funciones/obtenerFavoritos.php?tipo=${encodeURIComponent(tipo)}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.text();
    })
    .then(html => {
        gridFavoritos.innerHTML = html;
        
        // Agregar estilos dinámicos si no están presentes
        if (!document.getElementById('estilos-favoritos-dinamicos')) {
            const estilos = document.createElement('style');
            estilos.id = 'estilos-favoritos-dinamicos';
            estilos.textContent = `
                .grid-favoritos-dinamico {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                    gap: 20px;
                    padding: 20px;
                }
                .item-favorito-dinamico {
                    border: 1px solid #e0e0e0;
                    border-radius: 8px;
                    overflow: hidden;
                    transition: transform 0.3s ease;
                }
                .item-favorito-dinamico:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                }
                .imagen-favorito-dinamico img {
                    width: 100%;
                    height: 200px;
                    object-fit: cover;
                }
                .info-favorito-dinamico {
                    padding: 15px;
                }
                .info-favorito-dinamico h4 {
                    margin: 0 0 10px 0;
                    color: #333;
                }
                .precio-dinamico {
                    font-weight: bold;
                    color: var(--primario-100);
                    font-size: 1.1em;
                    margin: 10px 0;
                }
                .acciones-dinamico {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 15px;
                }
                .btn-ver-dinamico {
                    background-color: var(--primario-100);
                    color: white;
                    border: none;
                    padding: 8px 15px;
                    border-radius: 4px;
                    cursor: pointer;
                    text-decoration: none;
                    font-size: 0.9em;
                }
                .btn-eliminar-dinamico {
                    background: none;
                    border: 2px solid #ff4d4d;
                    color: #ff4d4d;
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    cursor: pointer;
                    font-size: 16px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                }
                .btn-eliminar-dinamico:hover {
                    background-color: #ff4d4d;
                    color: white;
                }
                .sin-favoritos-dinamico {
                    grid-column: 1 / -1;
                    text-align: center;
                    padding: 40px 20px;
                    color: #666;
                }
                .loading {
                    grid-column: 1 / -1;
                    text-align: center;
                    padding: 40px 20px;
                    color: #666;
                }
            `;
            document.head.appendChild(estilos);
        }
    })
    .catch(error => {
        console.error('Error al cargar favoritos:', error);
        gridFavoritos.innerHTML = `
            <div class="sin-favoritos-dinamico">
                Error al cargar los favoritos. 
                <button onclick="mostrarFavoritos('${tipo}')" class="btn-ver-dinamico" style="margin-top: 10px;">
                    Reintentar
                </button>
            </div>`;
    });
}

function ocultarFavoritos() {
    document.querySelector('.cont-opciones').style.display = 'flex';
    document.getElementById('contenido-favoritos').style.display = 'none';
}

function eliminarFavoritoDinamico(tipo, id) {
    if(!confirm('¿Estás seguro de que quieres eliminar este elemento de tus favoritos?')) {
        return;
    }
    
    // Mostrar indicador de carga
    const gridFavoritos = document.getElementById('grid-favoritos');
    const contenidoAnterior = gridFavoritos.innerHTML;
    gridFavoritos.innerHTML = '<div class="loading">Eliminando favorito...</div>';
    
    // Determinar el tipo actual basado en el título
    const titulo = document.getElementById('titulo-favoritos').textContent.toLowerCase();
    let tipoActual = 'productos';
    if (titulo.includes('receta')) {
        tipoActual = 'recetas';
    } else if (titulo.includes('restaurante')) {
        tipoActual = 'restaurantes';
    }
    
    // Enviar la petición
    fetch(`/Zava/php/cliente/funciones/eliminarFavorito.php?tipo=${encodeURIComponent(tipo)}&id=${encodeURIComponent(id)}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Recargar la lista de favoritos
            mostrarFavoritos(tipoActual);
        } else {
            throw new Error(data.message || 'Error al eliminar el favorito');
        }
    })
    .catch(error => {
        console.error('Error al eliminar favorito:', error);
        gridFavoritos.innerHTML = `
            <div class="sin-favoritos-dinamico">
                ${error.message || 'Error al eliminar el favorito'}
                <button onclick="mostrarFavoritos('${tipoActual}')" class="btn-ver-dinamico" style="margin-top: 10px;">
                    Volver a intentar
                </button>
            </div>`;
    });
}
