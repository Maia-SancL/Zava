document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.form-agregar-productos');
    if (!form) return;

    const inputImagenes = document.getElementById('input-imagenes');
    const iconoImagen = document.getElementById('icono-imagen');
    const zonaTexto = document.getElementById('zona-texto');
    const previewContainer = document.getElementById('previewContainer');
    const zonaImagenes = document.getElementById('zona-imagenes');
    
    const slots = [
        document.getElementById('slot-0'),
        document.getElementById('slot-1'),
        document.getElementById('slot-2')
    ];
    
    let imagenesSeleccionadas = [null, null, null]; // Almacenará los objetos File
    let slotActual = 0;
    let previewActivado = false;

    if (zonaImagenes) {
        zonaImagenes.addEventListener('click', function(e) {
            if (!previewActivado && !e.target.classList.contains('btn-x-eliminar')) {
                if(previewContainer) previewContainer.style.display = 'grid';
                if (iconoImagen) iconoImagen.style.display = 'none';
                if (zonaTexto) zonaTexto.style.display = 'none';
                previewActivado = true;
            }
        });

        slots.forEach((slot, idx) => {
            if (slot) {
                slot.addEventListener('click', function(e) {
                    e.stopPropagation();
                    slotActual = idx;
                    if(inputImagenes) inputImagenes.click();
                });
            }
        });

        if(inputImagenes) {
            inputImagenes.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file || !file.type.startsWith('image/')) return;
                
                imagenesSeleccionadas[slotActual] = file; // Guardar el objeto File
                mostrarImagen(slotActual, URL.createObjectURL(file));
                
                inputImagenes.value = ''; // Limpiar para permitir seleccionar el mismo archivo de nuevo
            });
        }
    }

    function mostrarImagen(idx, src) {
        const slot = slots[idx];
        if (!slot) return;
        
        slot.innerHTML = '';
        
        const img = document.createElement('img');
        img.src = src;
        img.style.width = idx === 0 ? '240px' : '110px';
        img.style.height = idx === 0 ? '240px' : '110px';
        img.style.objectFit = 'cover';
        img.style.border = '2px solid #ccc';
        img.style.borderRadius = '6px';
        
        const btnX = document.createElement('button');
        btnX.innerHTML = '×';
        btnX.className = 'btn-x-eliminar';
        btnX.style.position = 'absolute';
        btnX.style.top = '4px';
        btnX.style.right = '4px';
        btnX.style.background = 'rgba(0,0,0,0.7)';
        btnX.style.color = '#fff';
        btnX.style.border = 'none';
        btnX.style.borderRadius = '50%';
        btnX.style.width = '20px';
        btnX.style.height = '20px';
        btnX.style.cursor = 'pointer';
        btnX.style.fontSize = '14px';
        
        btnX.addEventListener('click', function(e) {
            e.stopPropagation();
            URL.revokeObjectURL(img.src); // Liberar memoria
            imagenesSeleccionadas[idx] = null;
            slot.innerHTML = '';
        });
        
        slot.appendChild(img);
        slot.appendChild(btnX);
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        formData.delete('imagenes[]');

        let tieneImagenes = false;
        imagenesSeleccionadas.forEach((file, index) => {
            if (file) {
                formData.append('imagenes[]', file, file.name);
                tieneImagenes = true;
            }
        });

        if (!tieneImagenes) {
            alert('Por favor, sube al menos una imagen.');
            return;
        }

        fetch('comercio-agregar-producto.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            const mensajeDiv = document.getElementById('mensaje-respuesta');
            if (result.success) {
                mensajeDiv.textContent = '¡Producto agregado con éxito!';
                mensajeDiv.className = 'mensaje exito';
                form.reset();
                // Limpiar previews
                slots.forEach((slot, idx) => {
                    const img = slot.querySelector('img');
                    if(img) URL.revokeObjectURL(img.src);
                    slot.innerHTML = '';
                });
                imagenesSeleccionadas = [null, null, null];
                if(previewContainer) previewContainer.style.display = 'none';
                if (iconoImagen) iconoImagen.style.display = 'flex';
                if (zonaTexto) zonaTexto.style.display = 'block';
                previewActivado = false;
            } else {
                mensajeDiv.textContent = 'Error: ' + result.error;
                mensajeDiv.className = 'mensaje error';
            }
            mensajeDiv.style.display = 'block';
        })
        .catch(error => {
            const mensajeDiv = document.getElementById('mensaje-respuesta');
            mensajeDiv.textContent = 'Error al enviar el formulario.';
            mensajeDiv.className = 'mensaje error';
            mensajeDiv.style.display = 'block';
            console.error('Error:', error);
        });
    });
});