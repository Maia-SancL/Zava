<?php

include_once('conexion.php');

// Detectar el id de la receta
$id = 0;

if (isset($id_receta)) {
    $id = intval($id_receta);
} else {
    echo "<p>No se ha especificado una receta para mostrar comentarios.</p>";
    return;
}

// Obtener comentarios de la receta
$comentarios = [];
$query_comentarios = "
    SELECT c.id_comentario, c.comentario, c.fecha_comentario, u.nombre, u.nickname, u.foto
    FROM Comentarios_Recetas c
    JOIN Usuarios u ON c.id_usuario = u.id_usuario
    WHERE c.id_receta = $id
    ORDER BY c.fecha_comentario DESC
";
$res = mysqli_query($conexion, $query_comentarios);
while ($row = mysqli_fetch_assoc($res)) {
    $comentarios[] = $row;
}

?>
<section class="cont-comentarios">
    <div class="subtitulo">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12a10 10 0 0 0 .951 4.262l-.93 4.537a1 1 0 0 0 1.18 1.18l4.537-.93c1.294.61 2.74.95 4.262.95c5.523 0 10-4.476 10-10c0-5.522-4.477-10-10-10" clip-rule="evenodd"/></svg>
        <h4>Comentarios</h4>
    </div>

    <?php if (empty($comentarios)): ?>
        <div class="comentario-user">
            <div class="cont-comentario">
                <p class="comentario">No hay comentarios aún.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($comentarios as $comentario): ?>
            <div class="comentario-user">
                <div class="info-user">
                    <div class="cont-img">
                        <img src="/Zava/img/perfiles/<?= htmlspecialchars($comentario['foto'] ?? 'default.png') ?>" alt="Foto de perfil">
                    </div>
                    <div class="info">
                        <div class="fullname-username">
                            <span class="fullname"><?= htmlspecialchars($comentario['nombre']) ?></span>
                            <span class="username">@<?= htmlspecialchars($comentario['nickname']) ?></span>
                        </div>
                        <div class="fecha-publicacion">
                            <span class="fecha"><?= date('d/m/Y', strtotime($comentario['fecha_comentario'])) ?></span>
                        </div>
                    </div>
                </div>
                <div class="cont-comentario">
                    <p class="comentario"><?= htmlspecialchars($comentario['comentario']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php 
    // Condicion para mostrar el formulario:
    // El usuario debe estar logueado (existe $_SESSION)
    // El usuario logueado no debe ser el autor de la receta
    if (isset($_SESSION['id']) && isset($receta['id_usuario']) && $_SESSION['id'] != $receta['id_usuario']): 
    ?>
        <div class="cont-agregar-comentario">
            <div class="cont-img">
                <img src="/Zava/img/perfiles/<?php echo $_SESSION['foto'] ?? 'default.png' ?>" alt="Foto de perfil">
            </div>
            <form action="/Zava/php/componentes/funciones/agregarComentario.php" method="POST" class="agregar-comentario">
                <input type="hidden" name="tipo" value="receta">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="text" name="comentario" placeholder="Agregar un comentario..." required>
                <button type="submit" class="btn-enviar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M3.4 20.4l17.4-8.4c.8-.4.8-1.6 0-2L3.4 1.6c-.8-.4-1.6.4-1.4 1.2l3.6 7.2c.2.4.2.8 0 1.2L2 19.2c-.2.8.6 1.6 1.4 1.2Z"/></svg>
                </button>
            </form>
        </div>
    <?php endif; ?>
</section>
