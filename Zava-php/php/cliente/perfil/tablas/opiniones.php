<?php

include_once('conexion.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];

// Eliminar comentario de receta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_comentario_receta'])) {
    $id_comentario = intval($_POST['eliminar_comentario_receta']);
    mysqli_query($conexion, "DELETE FROM comentarios_recetas WHERE id_comentario = $id_comentario AND id_usuario = $id_usuario");
    header("Location: perfilInicio.php?tabla=opiniones");
    exit;
}

// Eliminar comentario de restaurante
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_comentario_restaurante'])) {
    $id_comentario = intval($_POST['eliminar_comentario_restaurante']);
    mysqli_query($conexion, "DELETE FROM comentarios_restaurantes WHERE id_comentario = $id_comentario AND id_usuario = $id_usuario");
    header("Location: perfilInicio.php?tabla=opiniones");
    exit;
}

// Traer comentarios de recetas
$q_recetas = "SELECT cr.id_comentario, r.nombre AS contenido, cr.comentario, cr.fecha_comentario AS fecha, 'receta' AS tipo
              FROM comentarios_recetas cr
              JOIN recetas r ON cr.id_receta = r.id_receta
              WHERE cr.id_usuario = $id_usuario";

// Traer comentarios de restaurantes
$q_restaurantes = "SELECT cr.id_comentario, res.nombre AS contenido, cr.comentario, NULL AS fecha, 'restaurante' AS tipo
                   FROM comentarios_restaurantes cr
                   JOIN restaurantes res ON cr.id_restaurante = res.id_restaurante
                   WHERE cr.id_usuario = $id_usuario";

// Unir ambos resultados
$res_recetas = mysqli_query($conexion, $q_recetas);
$res_restaurantes = mysqli_query($conexion, $q_restaurantes);

$opiniones = [];
while ($row = mysqli_fetch_assoc($res_recetas)) {
    $opiniones[] = $row;
}
while ($row = mysqli_fetch_assoc($res_restaurantes)) {
    $opiniones[] = $row;
}

// Ordenar por fecha descendente (puede que fecha de restaurante sea NULL)
usort($opiniones, function($a, $b) {
    return strtotime($b['fecha'] ?? '1970-01-01') <=> strtotime($a['fecha'] ?? '1970-01-01');
});
?>
<section class="section-tabla-productos">
    <article class="producto guia">
        <div class="informacion-principal">
            <div class="informacion">
                <span class="lbl-informacion">Contenido</span>
                <span class="lbl-informacion">Tipo</span>
                <span class="lbl-informacion">Opinión</span>
                <span class="lbl-informacion">Fecha</span>
                <span class="lbl-informacion">Eliminar</span>
            </div>
        </div>
    </article>
    <?php foreach ($opiniones as $op): ?>
        <article class="producto">
            <div class="informacion-principal">
                <div class="informacion">
                    <span class="lbl-informacion"><?= htmlspecialchars($op['contenido']) ?></span>
                    <span class="lbl-informacion"><?= ucfirst($op['tipo']) ?></span>
                    <span class="lbl-informacion"><?= htmlspecialchars($op['comentario']) ?></span>
                    <span class="lbl-informacion"><?= $op['fecha'] ? date('d/m/Y H:i', strtotime($op['fecha'])) : '' ?></span>
                    <div class="btns">
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta opinión?');" onclick="event.stopPropagation();">
                            <?php if ($op['tipo'] === 'receta'): ?>
                                <input type="hidden" name="eliminar_comentario_receta" value="<?= $op['id_comentario'] ?>">
                            <?php else: ?>
                                <input type="hidden" name="eliminar_comentario_restaurante" value="<?= $op['id_comentario'] ?>">
                            <?php endif; ?>
                            <button type="submit" class="btn-eliminar">🗑️</button>
                        </form>
                    </div>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
    <?php if (count($opiniones) === 0): ?>
        <article class="producto">
            <div class="informacion-principal">
                <div class="informacion">
                    <span class="lbl-informacion">No tienes opiniones registradas.</span>
                    </div>
                </div>
            </article>
        <?php endif; ?>
</section>
            