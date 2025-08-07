document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-comentario');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const tipo = form.dataset.tipo;
            const id = form.dataset.id;

            formData.append('tipo', tipo);
            formData.append('id', id);

            fetch('/Zava/php/componentes/agregar_comentario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const nuevoComentario = data.comentario;
                    const comentarioHtml = `
                        <div class="comentario-user">
                            <div class="info-user">
                                <div class="cont-img">
                                    <img src="/Zava/img/perfiles/${nuevoComentario.foto}" alt="Foto de perfil">
                                </div>
                                <div class="info">
                                    <div class="fullname-username">
                                        <span class="fullname">${nuevoComentario.nombre}</span>
                                        <span class="username">@${nuevoComentario.nickname}</span>
                                    </div>
                                    <div class="fecha-publicacion">
                                        <span class="fecha">${nuevoComentario.fecha_comentario}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="cont-comentario">
                                <p class="comentario">${nuevoComentario.comentario}</p>
                            </div>
                        </div>
                    `;

                    const contComentarios = document.querySelector('.cont-comentarios');
                    const formComentario = contComentarios.querySelector('.cont-agregar-comentario');
                    
                    // Eliminar el mensaje de "No hay comentarios"
                    const noComentarios = contComentarios.querySelector('.comentario-user p.comentario');
                    if (noComentarios && noComentarios.textContent === 'No hay comentarios aún.') {
                        noComentarios.closest('.comentario-user').remove();
                    }

                    formComentario.insertAdjacentHTML('beforebegin', comentarioHtml);
                    document.getElementById('comentario-input').value = '';
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
