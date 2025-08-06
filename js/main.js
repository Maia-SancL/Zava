
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

            const input = document.createElement('input');
            input.className ='input-ingrediente';
            input.type = 'text';
            input.name = 'ingredientes[]';
            input.placeholder = 'Ejemplo: 300ml de leche de almendras';
            input.required = true;
            input.value = valor;

            const botonEliminar = document.createElement('button');
            botonEliminar.className ='btn-eliminar';
            botonEliminar.type = 'button';
            botonEliminar.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" width="1024" height="1024" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zm3-4q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17"/></svg>';
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
        botonEliminar.className ='btn-eliminar';
        botonEliminar.type = 'button';
        botonEliminar.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" width="1024" height="1024" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zm3-4q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17"/></svg>';
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

    