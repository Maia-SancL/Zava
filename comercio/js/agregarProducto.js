document.addEventListener('DOMContentLoaded', () => {
    // === SCRIPT PARA PREVISUALIZACIÓN DE IMAGEN ===
    const inputFile = document.getElementById('imagen-producto');
    if (inputFile) {
        const contenidoUpload = inputFile.parentElement.querySelector('.contenido-upload');
        const svgIcon = contenidoUpload.querySelector('svg');
        const textoSpan = contenidoUpload.querySelector('span');
        const previewImg = contenidoUpload.querySelector('.preview-img');

        inputFile.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    previewImg.src = ev.target.result;
                    previewImg.style.display = 'block';
                    if(svgIcon) svgIcon.style.display = 'none';
                    if(textoSpan) textoSpan.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                previewImg.src = '';
                previewImg.style.display = 'none';
                if(svgIcon) svgIcon.style.display = 'block';
                if(textoSpan) textoSpan.style.display = 'block';
            }
        });
    }

    // === SCRIPT PARA MOSTRAR/OCULTAR CAMPO DE DESCUENTO ===
    const checkboxOferta = document.querySelector('.btn-switch');
    const contDescuento = document.querySelector('.cont-input.oculto');
    if (checkboxOferta && contDescuento) {
        const inputDescuento = contDescuento.querySelector('input');
        function toggleDescuento() {
            if (checkboxOferta.checked) {
                contDescuento.classList.remove('oculto');
                inputDescuento.required = true;
            } else {
                contDescuento.classList.add('oculto');
                inputDescuento.required = false;
                inputDescuento.value = '';
            }
        }
        checkboxOferta.addEventListener('change', toggleDescuento);
        toggleDescuento();
    }

    // === SCRIPT PARA CATEGORÍAS Y TIPOS DEPENDIENTES ===
    const categoriasYTipos = {
        "Golosinas": ["Chocolates", "Caramelos y chupetines", "Chicles y pastillas", "Bombones y bocaditos", "Confituras", "Gomitas y gelatinas", "Obleas, turrones y postres de maní", "Tabletas"],
        "Panaderia": ["Panes", "Premezclas", "Galletitas dulces y salada", "Tostadas y crackers", "Muffins y cupcakes", "Bizcochuelos y tortas"],
        "Snacks": ["Papas Fritas", "Bastones de Maiz", "Mani", "Palitos"],
        "Cereales": ["Cereales con azúcar", "Barras"],
        "Aderezos": ["Mayonesas", "Mostazas", "Ketchup", "Salsa Golf"],
        "Infusiones": ["Café", "Mate", "Té", "Cacao en polvo"],
        "Pastas": ["Fideos", "Ravioles", "Sorrentinos", "Ñoquis"],
        "Harinas y premezclas": ["Harina", "Premezclas"],
        "Arroz y legumbres": ["Arroz", "Legumbres"],
        "Mermeladas y Dulces": ["Mermeladas", "Dulce", "Miel"],
        "Congelados": ["Hamburguesas", "Milanesas", "Papas", "Nugguets", "Patitas", "Bastones de mozzarella", "Pizzas y pizzetas", "Panificados", "Empanadas y tartas"],
        "Lacteos": ["Cremas", "Leches", "Yogures", "Dulce de leche"],
        "Quesos": ["Queso cremoso", "Quesos crema y untables", "Queso rallado"],
        "Bebidas": ["Aguas", "Gaseosas", "Jugos", "En polvo"],
        "Salsas y Pure de Tomate": ["Salsas y Puré de Tomate"]
    };
    const categoriaSelect = document.getElementById('categoria');
    const tipoSelect = document.getElementById('tipo');
    if (categoriaSelect && tipoSelect) {
        // Poblar el select de categorías
        for (const categoria in categoriasYTipos) {
            const option = document.createElement('option');
            option.value = categoria;
            option.textContent = categoria;
            categoriaSelect.appendChild(option);
        }

        // Event listener para cuando cambia la categoría
        categoriaSelect.addEventListener('change', function() {
            const categoriaSeleccionada = this.value;
            tipoSelect.innerHTML = '<option value="" disabled selected>Selecciona un tipo de producto</option>';
            if (categoriaSeleccionada && categoriasYTipos[categoriaSeleccionada]) {
                const tipos = categoriasYTipos[categoriaSeleccionada];
                tipos.forEach(function(tipo) {
                    const option = document.createElement('option');
                    option.value = tipo;
                    option.textContent = tipo;
                    tipoSelect.appendChild(option);
                });
                tipoSelect.disabled = false;
            } else {
                tipoSelect.disabled = true;
            }
        });

        tipoSelect.disabled = true;
    }

    // === SCRIPT PARA EL BOTÓN BORRAR ===
    const btnBorrar = document.querySelector('.btn-borrar');
    const form = document.getElementById('form-agregar-productos');
    if (btnBorrar && form) {
        btnBorrar.addEventListener('click', (e) => {
            e.preventDefault();
            form.reset();

            // Resetear la previsualización de la imagen
            const inputFile = document.getElementById('imagen-producto');
            if (inputFile) {
                const contenidoUpload = inputFile.parentElement.querySelector('.contenido-upload');
                const svgIcon = contenidoUpload.querySelector('svg');
                const textoSpan = contenidoUpload.querySelector('span');
                const previewImg = contenidoUpload.querySelector('.preview-img');

                previewImg.src = '';
                previewImg.style.display = 'none';
                if (svgIcon) svgIcon.style.display = 'block';
                if (textoSpan) textoSpan.style.display = 'block';
            }

            // Asegurarse de que el campo de descuento se oculte
            const checkboxOferta = document.querySelector('.btn-switch');
            const contDescuento = document.querySelector('.cont-input.oculto');
            if (checkboxOferta && contDescuento) {
                const inputDescuento = contDescuento.querySelector('input');
                contDescuento.classList.add('oculto');
                inputDescuento.required = false;
                inputDescuento.value = '';
            }

            // Deshabilitar el select de tipo
            const tipoSelect = document.getElementById('tipo');
            if (tipoSelect) {
                tipoSelect.innerHTML = '<option value="" disabled selected>Selecciona un tipo de producto</option>';
                tipoSelect.disabled = true;
            }
        });
    }
});
