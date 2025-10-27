document.addEventListener('DOMContentLoaded', () => {

    // ============================
    //     Manejo de Imágenes
    // ============================

    const input = document.getElementById('input-imagenes');
    const mainImg = document.querySelector('.contenedor-agregar-imagen img');
    const mainText = document.querySelector('.contenedor-texto-icon');
    const previews = document.querySelectorAll('.cont-img-preview');

    let imagenes = [];

    if (input) {
        input.addEventListener('change', handleNuevaImagen);
    }

    function handleNuevaImagen(e) {
        const files = Array.from(e.target.files); // Todos los archivos seleccionados
        if (!files.length) return;

        // Procesa cada img
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = ev => {
                const nuevaImagen = ev.target.result;
                imagenes.unshift(nuevaImagen); // Se agrega al principio
                if (imagenes.length > 3) imagenes = imagenes.slice(0, 3); // Limite 3
                actualizarVista();
            };
            reader.readAsDataURL(file);
        });

        // Limpiar input para poder subir los mismos archivos otra
        e.target.value = '';
    }

    function actualizarVista() {
        if (imagenes[0]) {
            mainImg.src = imagenes[0];
            mainImg.classList.remove('oculto');
            mainText.classList.add('oculto');
        } else {
            mainImg.classList.add('oculto');
            mainText.classList.remove('oculto');
        }

        previews.forEach((preview, i) => {
            const img = preview.querySelector('img');
            if (imagenes[i + 1]) { // +1 porque mainImg ocupa la primera
                img.src = imagenes[i + 1];
                preview.classList.remove('oculto');
                preview.classList.add('mostrar-block');
            } else {
                img.src = '';
                preview.classList.remove('mostrar-block');
                preview.classList.add('oculto');
            }
        });
    }


    // ============================
    //   Autoajuste del Textarea
    // ============================

    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = `${textarea.scrollHeight}px`;
    }

    const descripciones = document.querySelectorAll('.input-texto.descripcion');
    descripciones.forEach((descripcion) => {
        descripcion.addEventListener('input', function () {
            autoResize(this);
        });
        autoResize(descripcion);
    });

    // ============================
    //   Autoajuste del range tiempo
    // ============================

    const inputTiempo = document.getElementById('tiempo');
    const spanValor = document.getElementById('tiempo-valor');

    function actualizarBarraYSpan() {
        const porcentaje = (inputTiempo.value - inputTiempo.min) / (inputTiempo.max - inputTiempo.min) * 100;
        inputTiempo.style.background = `linear-gradient(to right, var(--primario-500) ${porcentaje}%, var(--secundario-200) ${porcentaje}%)`;

        const totalMinutos = parseInt(inputTiempo.value);
        let texto = '';
        if (totalMinutos < 60) {
            texto = `${totalMinutos} min`;
        } else {
            const horas = Math.floor(totalMinutos / 60);
            const minutos = totalMinutos % 60;
            texto = minutos === 0 ? `${horas} h` : `${horas} h ${minutos} min`;
        }
        spanValor.textContent = texto;
    }

    if (inputTiempo) inputTiempo.addEventListener('input', actualizarBarraYSpan);

    // ============================
    //   Manejo dinámico de ingredientes
    // ============================

    const contenedorIngredientes = document.querySelector('.zona-instruccion.ingredientes');
    const btnAgregarIngrediente = document.querySelector('.btn-agregar-ingrediente');
    const MAX_INGREDIENTES = 30;

    function actualizarBotonIngredientes() {
        const total = contenedorIngredientes.querySelectorAll('.contenedor-instruccion').length;
        if (total >= MAX_INGREDIENTES) {
            btnAgregarIngrediente.classList.add('oculto');
            btnAgregarIngrediente.classList.remove('mostrar-flex');
        } else {
            btnAgregarIngrediente.classList.remove('oculto');
            btnAgregarIngrediente.classList.add('mostrar-flex');
        }
    }

    function crearIngrediente() {
        const total = contenedorIngredientes.querySelectorAll('.contenedor-instruccion').length;
        if (total >= MAX_INGREDIENTES) return;

        const div = document.createElement('div');
        div.classList.add('contenedor-instruccion');

        const input = document.createElement('input');
        input.className = 'input-texto';
        input.name = 'ingredientes[]';
        input.placeholder = 'Ingrediente';
        input.maxLength = 50;

        const icon = document.createElement('iconify-icon');
        icon.setAttribute('icon', 'ic:round-delete');
        icon.className = 'icon icon-h5 color-primario btn-eliminar-ingriente';

        icon.addEventListener('click', () => {
            div.remove();
            actualizarBotonIngredientes();
        });

        div.appendChild(input);
        div.appendChild(icon);
        contenedorIngredientes.appendChild(div);

        actualizarBotonIngredientes();
    }

    if (btnAgregarIngrediente) {
        btnAgregarIngrediente.addEventListener('click', crearIngrediente);
        actualizarBotonIngredientes();
    }

    // ============================
    //   Manejo dinámico de pasos
    // ============================

    const contenedorPasos = document.querySelector('.zona-instruccion.pasos');
    const btnAgregarPaso = document.querySelector('.btn-agregar-paso');
    const MAX_PASOS = 30;

    function actualizarBotonPasos() {
        const total = contenedorPasos.querySelectorAll('.contenedor-instruccion').length;
        if (total >= MAX_PASOS) {
            btnAgregarPaso.classList.add('oculto');
            btnAgregarPaso.classList.remove('mostrar-flex');
        } else {
            btnAgregarPaso.classList.remove('oculto');
            btnAgregarPaso.classList.add('mostrar-flex');
        }
    }

    function crearPaso(numero) {
        const div = document.createElement('div');
        div.classList.add('contenedor-instruccion');

        const numeroPaso = document.createElement('p');
        numeroPaso.className = 'pequenio numero-paso';
        numeroPaso.textContent = numero;

        const textarea = document.createElement('textarea');
        textarea.className = 'input-texto descripcion';
        textarea.setAttribute('name', 'instruccion[]');
        textarea.setAttribute('placeholder', `Describe el paso ${numero}`);
        textarea.setAttribute('wrap', 'hard');
        textarea.setAttribute('maxlength', '500');
        textarea.required = true;

        textarea.addEventListener('input', function () {
            autoResize(this);
        });
        autoResize(textarea);

        const icon = document.createElement('iconify-icon');
        icon.setAttribute('icon', 'ic:round-delete');
        icon.className = 'icon icon-h5 color-primario btn-eliminar-ingriente';

        icon.addEventListener('click', () => {
            div.remove();
            actualizarNumerosPasos();
            actualizarBotonPasos();
        });

        div.appendChild(numeroPaso);
        div.appendChild(textarea);
        div.appendChild(icon);
        contenedorPasos.appendChild(div);

        actualizarBotonPasos();
    }

    function actualizarNumerosPasos() {
        const pasos = contenedorPasos.querySelectorAll('.contenedor-instruccion');
        pasos.forEach((paso, i) => {
            const num = paso.querySelector('.numero-paso');
            const textarea = paso.querySelector('textarea');
            const numero = i + 1;
            num.textContent = numero;
            textarea.placeholder = `Describe el paso ${numero}`;
        });
    }

    if (btnAgregarPaso) {
        btnAgregarPaso.addEventListener('click', () => {
            const total = contenedorPasos.querySelectorAll('.contenedor-instruccion').length;
            if (total >= MAX_PASOS) return;
            crearPaso(total + 1);
        });
        actualizarBotonPasos();
    }

    // ============================
    //   Inicializar botones eliminar existentes
    // ============================

    const btnsEliminar = document.querySelectorAll('.btn-eliminar-ingriente');
    btnsEliminar.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const contenedor = e.target.closest('.contenedor-instruccion');
            if (contenedor) {
                const zona = contenedor.closest('.zona-instruccion');
                contenedor.remove();
                if (zona && zona.classList.contains('pasos')) {
                    actualizarNumerosPasos();
                    actualizarBotonPasos();
                }
                if (zona && zona.classList.contains('ingredientes')) {
                    actualizarBotonIngredientes();
                }
            }
        });
    });


    // ============================
    //   Barra flotante de botones (responsive)
    // ============================

    const contenedorBtns = document.querySelector('.contenedor-btns');
    let ultimaPosScroll = 0;
    let timeoutOcultarBtns = null;

    // Solo se activa si el ancho es <= 1024px
    function activarBarraResponsive() {
        if (!contenedorBtns) return;

        const esPantallaChica = window.innerWidth <= 1024;

        if (!esPantallaChica) {
            // Resetear estado si se vuelve a pantalla grande
            contenedorBtns.classList.remove('oculto', 'mostrar-flex', 'mostrar-block');
            window.removeEventListener('scroll', manejarScrollBtns);
            return;
        }

        // Mostrar barra inicialmente
        contenedorBtns.classList.remove('oculto');
        contenedorBtns.classList.add('mostrar-flex'); // asumimos que es display:flex

        window.addEventListener('scroll', manejarScrollBtns);
    }

    function manejarScrollBtns() {
        const posicionActual = window.scrollY;

        // Scroll hacia abajo → ocultar
        if (posicionActual > ultimaPosScroll && posicionActual > 100) {
            contenedorBtns.classList.remove('mostrar-flex', 'mostrar-block');
            contenedorBtns.classList.add('oculto');
        }
        // Scroll hacia arriba → mostrar
        else {
            contenedorBtns.classList.remove('oculto');
            contenedorBtns.classList.add('mostrar-flex');
        }

        ultimaPosScroll = posicionActual;

        // Ocultar automáticamente luego de 3 segundos sin scroll
        clearTimeout(timeoutOcultarBtns);
        timeoutOcultarBtns = setTimeout(() => {
            if (posicionActual > 200) {
                contenedorBtns.classList.remove('mostrar-flex', 'mostrar-block');
                contenedorBtns.classList.add('oculto');
            }
        }, 3000);
    }

    // Escucha cambios de tamaño de pantalla (para activar/desactivar)
    window.addEventListener('resize', activarBarraResponsive);

    // Inicializar al cargar
    activarBarraResponsive();


});
