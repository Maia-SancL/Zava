document.addEventListener('DOMContentLoaded', function() {
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
    
    let imagenesSeleccionadas = [null, null, null];
    let slotActual = 0;
    let previewActivado = false;

    // Click en zona-imagenes: mostrar preview una sola vez
    zonaImagenes.addEventListener('click', function(e) {
        if (!previewActivado && !e.target.classList.contains('btn-x-eliminar')) {
            previewContainer.style.display = 'grid';
            if (iconoImagen) iconoImagen.style.display = 'none';
            if (zonaTexto) zonaTexto.style.display = 'none';
            previewActivado = true;
        }
    });

    // Click en cada slot: abrir input
    slots.forEach((slot, idx) => {
        if (slot) {
            slot.addEventListener('click', function(e) {
                e.stopPropagation(); // Evitar que el click se propague a zona-imagenes
                slotActual = idx;
                inputImagenes.click();
            });
        }
    });

    // Input change: mostrar imagen
    inputImagenes.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file || !file.type.startsWith('image/')) return;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            imagenesSeleccionadas[slotActual] = e.target.result;
            mostrarImagen(slotActual, e.target.result);
        };
        reader.readAsDataURL(file);
        inputImagenes.value = '';
    });

    // Mostrar imagen en slot
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
        btnX.className = 'btn-x-eliminar';
        
        btnX.addEventListener('click', function(e) {
            e.stopPropagation();
            imagenesSeleccionadas[idx] = null;
            slot.innerHTML = '';
        });
        
        slot.appendChild(img);
        slot.appendChild(btnX);
    }

    // Helper para convertir base64 a Blob
    function dataURLtoBlob(dataurl) {
        let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new Blob([u8arr], {type:mime});
    }

    // Interceptar el envío del formulario
    const form = document.querySelector('.form-receta');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            formData.delete('imagenes[]'); // Limpiar input original

            let tieneImagenes = false;
            imagenesSeleccionadas.forEach((dataUrl, index) => {
                if (dataUrl) {
                    const blob = dataURLtoBlob(dataUrl);
                    const filename = `receta-imagen-${index}.png`;
                    formData.append('imagenes[]', blob, filename);
                    tieneImagenes = true;
                }
            });

            // Si no hay imágenes, se puede agregar una validación si se desea
            // if (!tieneImagenes) {
            //     alert('Por favor, sube al menos una imagen.');
            //     return;
            // }

            fetch('crear.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // O .json() si PHP devuelve JSON
            .then(result => {
                console.log('Respuesta del servidor:', result);
                if (result.success) {
                    window.location.href = `mostrarReceta.php?id=${result.id_receta}`;
                } else {
                    alert('Error al crear la receta: ' + result.error);
                }
            })
            .catch(error => {
                console.error('Error al enviar el formulario:', error);
                alert('Error al enviar la receta.');
            });
        });
    }
});



