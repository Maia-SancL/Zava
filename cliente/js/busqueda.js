document.addEventListener('DOMContentLoaded', function () {

    function inicializarBusqueda(selectorInput, selectorContenedor) {
        const busquedaInput = document.querySelector(selectorInput);
        if (!busquedaInput) return;

        const searchContainer = document.querySelector(selectorContenedor);
        let sugerenciasContainer = null;

        busquedaInput.addEventListener('input', function () {
            const query = this.value.trim();

            if (query.length > 0) {
                if (!sugerenciasContainer) {
                    sugerenciasContainer = document.createElement('div');
                    sugerenciasContainer.className = 'sugerencias-busqueda';
                    searchContainer.appendChild(sugerenciasContainer);
                }

                sugerenciasContainer.innerHTML = `
                    <a href="/Zava/php/cliente/recetario.php?busqueda=${encodeURIComponent(query)}" class="sugerencia-item">Buscar "${query}" en Recetas</a>
                    <a href="/Zava/php/cliente/productos.php?busqueda=${encodeURIComponent(query)}" class="sugerencia-item">Buscar "${query}" en Productos</a>
                `;
                sugerenciasContainer.style.display = 'block';
            } else {
                if (sugerenciasContainer) {
                    sugerenciasContainer.style.display = 'none';
                }
            }
        });

        document.addEventListener('click', function (e) {
            if (sugerenciasContainer && !searchContainer.contains(e.target)) {
                sugerenciasContainer.style.display = 'none';
            }
        });
    }

    // Se asegura de que los estilos de sugerencias estén disponibles.
    const style = document.createElement('style');
    style.innerHTML = `
        .sugerencias-busqueda {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            width: 100%;
            background-color: #fff;
            border: 1px solid #ddd;
            border-top: none;
            z-index: 1000;
            border-radius: 0 0 8px 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .sugerencia-item {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            font-size: 1rem;
        }
        .sugerencia-item:hover {
            background-color: #f0f0f0;
        }
    `;
    document.head.appendChild(style);

    inicializarBusqueda('#busqueda-principal', '.contenedor-busqueda-principal'); // Para index.php
    inicializarBusqueda('#busqueda-nav', '#contenedor-busqueda-nav');       // Para navegador.php
});
