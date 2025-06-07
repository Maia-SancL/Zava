
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



//CREAR.PHP
    //FUNCION PARA AGREGAR INGREDIENTES
    document.addEventListener('DOMContentLoaded', function () {
        const contenedorIngredientes = document.getElementById('contenedor-ingredientes');
        const botonAgregarIngrediente = document.getElementById('agregar-ingrediente');

        function agregarIngrediente(valor = '') {
            const div = document.createElement('div');
            div.className = 'ingrediente-item';
            div.style.display = 'flex';
            div.style.alignItems = 'center';
            div.style.marginBottom = '8px';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'ingredientes[]';
            input.placeholder = 'Ejemplo: 300ml de leche de almendras';
            input.required = true;
            input.value = valor;
            input.style.flex = '1';
            input.style.marginRight = '8px';

            const botonEliminar = document.createElement('button');
            botonEliminar.type = 'button';
            botonEliminar.textContent = '🗑️';
            botonEliminar.style.background = 'none';
            botonEliminar.style.border = 'none';
            botonEliminar.style.cursor = 'pointer';
            botonEliminar.onclick = () => div.remove();

            div.appendChild(input);
            div.appendChild(botonEliminar);
            contenedorIngredientes.appendChild(div);
        }

        agregarIngrediente(); // Al menos un campo al inicio

        botonAgregarIngrediente.onclick = () => agregarIngrediente();

    //FUNCION PARA BOTON BORRAR
    document.getElementById('cancelar-boton').addEventListener('click', function () {
                if (confirm('¿Estás seguro de que deseas cancelar la creación de la receta?')) {
                    alert('Se canceló la creación de la receta.');
                    window.location.href = 'index.php'; // Redirige al index
                    }
                });

    //FUNCION PARA AGREGAR PASOS
    const contenedorPasos = document.getElementById('contenedor-pasos');
    const botonAgregarPaso = document.getElementById('agregar-paso');

    function actualizarNumerosPasos() {
        const items = contenedorPasos.querySelectorAll('.paso-item');
        items.forEach((item, idx) => {
            item.querySelector('.numero-paso').textContent = idx + 1;
        });
    }

    function agregarPaso(valor = '') {
        const div = document.createElement('div');
        div.className = 'paso-item';
        div.style.display = 'flex';
        div.style.alignItems = 'center';
        div.style.marginBottom = '8px';

        const numero = document.createElement('span');
        numero.className = 'numero-paso';
        numero.textContent = contenedorPasos.children.length + 1;
        numero.style.display = 'inline-block';
        numero.style.width = '32px';
        numero.style.height = '32px';
        numero.style.borderRadius = '50%';
        numero.style.background = '#ede5da';
        numero.style.color = '#7d5a4a';
        numero.style.textAlign = 'center';
        numero.style.lineHeight = '32px';
        numero.style.marginRight = '12px';
        numero.style.fontWeight = 'bold';

        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'pasos[]';
        input.placeholder = 'Descripción';
        input.required = true;
        input.value = valor;
        input.style.flex = '1';
        input.style.marginRight = '8px';

        const botonEliminar = document.createElement('button');
        botonEliminar.type = 'button';
        botonEliminar.textContent = '🗑️';
        botonEliminar.style.background = 'none';
        botonEliminar.style.border = 'none';
        botonEliminar.style.cursor = 'pointer';
        botonEliminar.onclick = () => {
            div.remove();
            actualizarNumerosPasos();
        };

        div.appendChild(numero);
        div.appendChild(input);
        div.appendChild(botonEliminar);
        contenedorPasos.appendChild(div);
        actualizarNumerosPasos();
    }

    agregarPaso(); // Al menos un campo al inicio

    botonAgregarPaso.onclick = () => agregarPaso();

    // SUBIDA DE IMÁGENES PERSONALIZADA
    const zonaImagenes = document.getElementById('zona-imagenes');
    const inputImagenes = document.getElementById('input-imagenes');
    const previewImagenes = document.getElementById('preview-imagenes');
    
    // Al hacer clic en la zona, abre el selector de archivos
    zonaImagenes.addEventListener('click', () => inputImagenes.click());
    
    // Drag & drop
    zonaImagenes.addEventListener('dragover', function (e) {
        e.preventDefault();
        zonaImagenes.classList.add('dragover');
    });
    zonaImagenes.addEventListener('dragleave', function (e) {
        e.preventDefault();
        zonaImagenes.classList.remove('dragover');
    });
    zonaImagenes.addEventListener('drop', function (e) {
        e.preventDefault();
        zonaImagenes.classList.remove('dragover');
        if (e.dataTransfer.files.length > 3) {
            alert('Solo puedes seleccionar hasta 3 imágenes.');
            return;
        }
        inputImagenes.files = e.dataTransfer.files;
        mostrarPreviewImagenes(inputImagenes.files);
    });
    
    // Cuando seleccionas archivos
    inputImagenes.addEventListener('change', function () {
        if (this.files.length > 3) {
            alert('Solo puedes seleccionar hasta 3 imágenes.');
            this.value = '';
            previewImagenes.innerHTML = '';
            return;
        }
        mostrarPreviewImagenes(this.files);
    });
    
    function mostrarPreviewImagenes(files) {
        previewImagenes.innerHTML = '';
        const archivos = Array.from(files).slice(0, 3);
    
        // Estructura visual: 1 grande a la izquierda, 2 pequeñas a la derecha
        const container = document.createElement('div');
        container.className = 'preview-grid';
    
        // Imagen grande
        if (archivos[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-img preview-img-large';
                container.appendChild(img);
            };
            reader.readAsDataURL(archivos[0]);
        }
    
        // Contenedor para las dos imágenes pequeñas
        const rightCol = document.createElement('div');
        rightCol.className = 'preview-right-col';
    
        // Imagen pequeña 1
        if (archivos[1]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-img preview-img-small';
                rightCol.appendChild(img);
            };
            reader.readAsDataURL(archivos[1]);
        }
    
        // Imagen pequeña 2
        if (archivos[2]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-img preview-img-small';
                rightCol.appendChild(img);
            };
            reader.readAsDataURL(archivos[2]);
        }
    
        container.appendChild(rightCol);
        previewImagenes.appendChild(container);
}
});    

//GENERAL: FILTROS DE BUQUEDA

    //BARRA DE TIEMPO
    document.addEventListener('DOMContentLoaded', function () {
        const inputTiempo = document.getElementById('tiempo');
        const tiempoValor = document.getElementById('tiempo-valor');

        function mostrarTiempo(valor) {
            if (valor < 60) {
                tiempoValor.textContent = valor + ' min';
            } else {
                const horas = Math.floor(valor / 60);
                const minutos = valor % 60;
                let texto = horas + ' hr';
                if (horas > 1) texto += 's';
                if (minutos > 0) texto += ' ' + minutos + ' min';
                tiempoValor.textContent = texto;
            }
        }

        mostrarTiempo(inputTiempo.value);

        inputTiempo.addEventListener('input', function () {
            mostrarTiempo(this.value);
        });
    });

    