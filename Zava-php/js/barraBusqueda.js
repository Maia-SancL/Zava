document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('busqueda-input');
    const sugerencias = document.getElementById('sugerencias-busqueda');
    const permanentes = [
        { label: 'Buscar recetas', tipo: 'recetas', url: 'resultados-recetas.php' },
        { label: 'Buscar restaurantes', tipo: 'restaurantes', url: 'resultados-restaurantes.php' },
        { label: 'Buscar productos', tipo: 'productos', url: 'resultados-productos.php' }
    ];

    function renderSugerencias(dinamicas = []) {
        sugerencias.innerHTML = '';
        // Permanentes
        permanentes.forEach(p => {
            const div = document.createElement('div');
            div.textContent = p.label;
            div.style.padding = '8px 12px';
            div.style.cursor = 'pointer';
            div.addEventListener('mousedown', function(e) {
                e.preventDefault();
                window.location.href = p.url + '?q=' + encodeURIComponent(input.value.trim());
            });
            sugerencias.appendChild(div);
        });
        // Separador
        if (dinamicas.length > 0) {
            const sep = document.createElement('div');
            sep.style.borderTop = '1px solid #eee';
            sugerencias.appendChild(sep);
        }
        // Dinámicas
        dinamicas.forEach(s => {
            const div = document.createElement('div');
            div.textContent = s.nombre;
            div.style.padding = '8px 12px';
            div.style.cursor = 'pointer';
            div.addEventListener('mousedown', function(e) {
                e.preventDefault();
                // Redirigir a mostrar-receta.php con POST usando form dinámico
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'mostrar-receta.php';
                form.style.display = 'none';
                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'id';
                inputId.value = s.id;
                form.appendChild(inputId);
                document.body.appendChild(form);
                form.submit();
            });
            sugerencias.appendChild(div);
        });
        sugerencias.style.display = 'block';
    }

    input.addEventListener('input', function() {
        const valor = input.value.trim();
        if (valor.length === 0) {
            renderSugerencias([]);
            sugerencias.style.display = 'block';
            return;
        }
        fetch('php/componentes/busqueda-sugerencias.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'q=' + encodeURIComponent(valor)
    })
    .then(r => r.json())
    .then(data => {
        renderSugerencias(data);
    });
    });

    input.addEventListener('focus', function() {
        renderSugerencias([]);
        sugerencias.style.display = 'block';
    });

    document.addEventListener('mousedown', function(e) {
        if (!sugerencias.contains(e.target) && e.target !== input) {
            sugerencias.style.display = 'none';
        }
    });
});