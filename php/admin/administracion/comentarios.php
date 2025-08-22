<?php
if (!isset($result)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php');
    $where = [];
    if (!empty($_GET['buscar'])) {
        $buscar = mysqli_real_escape_string($conexion, $_GET['buscar']);
        $where[] = "(u.nickname LIKE '%$buscar%' OR r.nombre LIKE '%$buscar%' OR cr.comentario LIKE '%$buscar%')";
    }
    $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    $query = "SELECT cr.id_comentario, cr.comentario, cr.fecha_comentario, u.nickname as usuario, r.nombre as receta, r.imagen as receta_imagen FROM Comentarios_Recetas cr JOIN Usuarios u ON cr.id_usuario = u.id_usuario JOIN Recetas r ON cr.id_receta = r.id_receta $whereSQL";
    $result = mysqli_query($conexion, $query);
}
?>

<link rel="stylesheet" href="/Zava/css/admin/tablas.css">

<section class="section-titulo-btn">
    <h2 class="subtitulo">Gestion de Comentarios</h2>
</section>

<section class="section-tabla-productos">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($comentario = mysqli_fetch_assoc($result)):
        ?>
            <article class="producto">
                <div class="informacion-principal">
                    <div class="informacion">
                        <span class="id">#<?= htmlspecialchars($comentario['id_comentario']) ?></span>
                        <p class="nombre"><strong>Receta:</strong> <?= htmlspecialchars($comentario['receta']) ?></p>
                        <p class="vendedor"><strong>Usuario:</strong> <?= htmlspecialchars($comentario['usuario']) ?></p>
                        <p><strong>Comentario:</strong> "<?= htmlspecialchars($comentario['comentario']) ?>"</p>
                        <span class="fecha"><?= htmlspecialchars($comentario['fecha_comentario']) ?></span>
                    </div>
                    <div class="detalles-producto oculto">
                        <div class="cont-img">
                            <img src="/Zava/img/recetas/<?= htmlspecialchars($comentario['receta_imagen']) ?>" alt="Imagen de <?= htmlspecialchars($comentario['receta']) ?>" style="height:80px;max-width:80px;object-fit:cover;">
                        </div>
                    </div>
                    <div class="btns">
                        <form action="/Zava/php/admin/funciones/eliminarComentario.php" method="POST" onsubmit="return confirm('Â¿EstÃ¡s seguro de que quieres eliminar este comentario?');" style="display: inline;">
                            <input type="hidden" name="id_comentario" value="<?= $comentario['id_comentario'] ?>">
                            <button type="submit" class="btn-eliminar" title="Eliminar Comentario">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6h14v13q0 .825-.587 1.413T17 21zM17 6H7V5q0-.425.288-.712T8 4h8q.425 0 .713.288T17 5zM9 17q.425 0 .713-.288T10 16V9q0-.425-.288-.712T9 8t-.712.288T8 9v7q0 .425.288.713T9 17m4 0q.425 0 .713-.288T14 16V9q0-.425-.288-.712T13 8t-.712.288T12 9v7q0 .425.288.713T13 17"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron comentarios.</p>
    <?php endif; ?>
</section>
