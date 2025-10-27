 document.addEventListener("DOMContentLoaded", function () {
        const inputCantidad = document.querySelector(".cantidad-producto");
        const btnAgregar = document.getElementById("agregar-uno");
        const btnEliminar = document.getElementById("eliminar-uno");
        const botonAgregarCarrito = document.getElementById("btn-agregar-carrito");
        const contenedorAgregar = document.querySelector(".contenedor-agregar-mas");

        botonAgregarCarrito.addEventListener("click", agregarProducto);

        function agregarProducto() {
            contenedorAgregar.classList.add('mostrar-flex');
            contenedorAgregar.classList.remove('oculto');

            botonAgregarCarrito.classList.add('oculto');
            inputCantidad.value = 1;
        }


        function validarInput() {
            let valor = inputCantidad.value;

            // Eliminar todo lo que no sean dígitos
            valor = valor.replace(/\D/g, "");


            if (valor === "" || parseInt(valor) === 0) {
                inputCantidad.value = 1;
                return;
            }

            if (parseInt(valor) > 100) {
                valor = 100;
            }

            inputCantidad.value = valor;
        }

        // Validar mientras se escribe
        inputCantidad.addEventListener("input", validarInput);

        // Evitar copiar/pegar símbolos
        inputCantidad.addEventListener("paste", function (e) {
            const pasted = e.clipboardData.getData("text");
            if (/\D/.test(pasted)) {
                e.preventDefault();
            }
        });

        // Boton agregar
        btnAgregar.addEventListener("click", function () {
            let valor = parseInt(inputCantidad.value) || 1;
            if (valor < 100) {
                valor += 1;
            }
            inputCantidad.value = valor;
        });

        // Boton borrar
        btnEliminar.addEventListener("click", function () {
            let valor = parseInt(inputCantidad.value) || 1;
            if (valor > 0) {
                valor -= 1;

            }
            inputCantidad.value = valor;
            if (inputCantidad.value == 0) {
                contenedorAgregar.classList.add('oculto');
                contenedorAgregar.classList.remove('mostrar-flex');

                botonAgregarCarrito.classList.remove('oculto');
                botonAgregarCarrito.classList.remove('mostrar-flex');
            }
        });
    });