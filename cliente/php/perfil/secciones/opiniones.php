<?php
$query_opiniones = "
    SELECT 
        rc.calificacion, 
        rc.comentario, 
        rc.fecha_calificacion, 
        r.id_receta, 
        r.nombre as nombre_receta, 
        r.imagen as imagen_receta
    FROM Receta_Calificaciones rc
    JOIN Recetas r ON rc.id_receta = r.id_receta
    WHERE rc.id_usuario = $id_usuario
    ORDER BY rc.fecha_calificacion DESC;
";
$result_opiniones = mysqli_query($conexion, $query_opiniones);
?>

<style>
.info-opinion {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 100%;
}
.opinion-header {
    display: flex;
    align-items: center;
    gap: 15px;
}
.opinion-header img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
}
.comentario-texto {
    font-style: italic;
    color: #555;
}
.calificacion-estrellas {
    color: #ffc107;
}
</style>

<section class="section-tabla-productos">
    <?php if (mysqli_num_rows($result_opiniones) > 0): ?>
        <?php while ($opinion = mysqli_fetch_assoc($result_opiniones)): ?>
            <article class="producto">
                <div class="informacion-principal">
                    <div class="info-opinion">
                        <div class="opinion-header">
                            <img src="<?php echo BASE_URL; ?>public/img/recetas/<?= htmlspecialchars($opinion['imagen_receta']) ?>" alt="Imagen de <?= htmlspecialchars($opinion['nombre_receta']) ?>">
                            <div>
                                <strong><?= htmlspecialchars($opinion['nombre_receta']) ?></strong>
                                <div class="calificacion-estrellas">
                                    <?php for ($i = 0; $i < floor($opinion['calificacion']); $i++): ?>★<?php endfor; ?><?php if ($opinion['calificacion'] - floor($opinion['calificacion']) > 0): ?>½<?php endif; ?>
                                    (<?= htmlspecialchars($opinion['calificacion']) ?>)
                                </div>
                            </div>
                        </div>
                        <p class="comentario-texto">"<?= htmlspecialchars($opinion['comentario']) ?>"</p>
                        <small>Fecha: <?= htmlspecialchars(date('d/m/Y', strtotime($opinion['fecha_calificacion']))) ?></small>
                    </div>
                    <div class="btns">
                        <a href="<?php echo BASE_URL; ?>receta?id_receta=<?= $opinion['id_receta'] ?>" class="btn-ver">Ver Receta</a>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No has dejado ninguna opinión todavía.</p>
    <?php endif; ?>
</section>
