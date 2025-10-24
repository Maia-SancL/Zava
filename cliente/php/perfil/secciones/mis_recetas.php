<?php
$query_recetas = "SELECT id_receta, nombre, imagen FROM recetas WHERE id_usuario = $id_usuario";
$result_recetas = mysqli_query($conexion, $query_recetas);
?>

<section class="section-tabla-productos">
    <?php if (mysqli_num_rows($result_recetas) > 0): ?>
        <?php while ($receta = mysqli_fetch_assoc($result_recetas)): ?>
            <article class="producto">
                <div class="informacion-principal">
                    <div class="informacion">
                        <span class="id">#<?= htmlspecialchars($receta['id_receta']) ?></span>
                        <img src="<?php echo BASE_URL; ?>public/img/recetas/<?= htmlspecialchars($receta['imagen']) ?>" alt="Imagen de <?= htmlspecialchars($receta['nombre']) ?>" class="imagen-item">
                        <span class="nombre"><?= htmlspecialchars($receta['nombre']) ?></span>
                    </div>
                    <div class="btns">
                        <a href="<?php echo BASE_URL; ?>receta?id_receta=<?= $receta['id_receta'] ?>" class="btn-ver">Ver</a>
                        <a href="<?php echo BASE_URL; ?>editar-receta?id_receta=<?= $receta['id_receta'] ?>" class="btn-editar">Editar</a>
                        <a href="<?php echo BASE_URL; ?>receta/eliminar?id_receta=<?= $receta['id_receta'] ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar esta receta?');">Eliminar</a>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No has creado ninguna receta todavía.</p>
    <?php endif; ?>
</section>
