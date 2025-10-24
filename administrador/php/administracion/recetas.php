<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/public/componentes/php/conexion.php');

$recetas_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) {
    $pagina_actual = 1;
}
$inicio = ($pagina_actual - 1) * $recetas_por_pagina;

$query_total = "SELECT COUNT(*) as total FROM recetas";
$resultado_total = mysqli_query($conexion, $query_total);
$total_recetas = mysqli_fetch_assoc($resultado_total)['total'];
$total_paginas = ceil($total_recetas / $recetas_por_pagina);

$where = [];
if (!empty($_GET['buscar'])) {
    $buscar = mysqli_real_escape_string($conexion, $_GET['buscar']);
    $where[] = "(r.nombre LIKE '%$buscar%' OR u.nombre LIKE '%$buscar%')";
}
if (isset($_GET['estado']) && $_GET['estado'] !== '') {
    $estado = ($_GET['estado'] == '1') ? 1 : 0;
    $where[] = "r.activa = $estado";
}
$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$query_recetas = "SELECT r.*, r.imagen, u.nombre as autor_nombre FROM recetas r JOIN usuarios u ON r.id_usuario = u.id_usuario $whereSQL ORDER BY r.id_receta DESC LIMIT $inicio, $recetas_por_pagina";
$resultado_recetas = mysqli_query($conexion, $query_recetas);

?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>administrador/css/tablas.css">

<section class="section-titulo-btn">
    <h2 class="subtitulo">Gestión de Recetas</h2>
</section>

<section class="section-tabla-productos">
    <?php if (mysqli_num_rows($resultado_recetas) > 0): ?>
        <?php while ($receta = mysqli_fetch_assoc($resultado_recetas)): ?>
            <?php 
                $estado = $receta['activa'] ? 'Activa' : 'Inactiva';
            ?>
            <article class="producto">
                <div class="informacion-principal">
                    <div class="informacion">
                        <span class="id">#<?= htmlspecialchars($receta['id_receta']) ?></span>
                        <span class="nombre"><?= htmlspecialchars($receta['nombre']) ?></span>
                        <span class="vendedor">Autor: <?= htmlspecialchars($receta['autor_nombre']) ?></span>
                        <span class="estado <?= $estado === 'Activa' ? 'estado-activo' : 'estado-inactivo' ?>"><?= $estado ?></span>
                    </div>
                    <div class="detalles-producto oculto">
                        <div class="cont-img">
                            <img src="<?php echo BASE_URL; ?>public/img/recetas/<?= htmlspecialchars($receta['imagen']) ?>" alt="Imagen de <?= htmlspecialchars($receta['nombre']) ?>" style="height:80px;max-width:80px;object-fit:cover;">
                        </div>
                        <div class="cont-descripcion">
                            <h5>Descripción</h5>
                            <p class="descripcion"><?= htmlspecialchars($receta['descripcion']) ?></p>
                        </div>
                    </div>
                    <div class="btns">
                        <form action="<?php echo BASE_URL; ?>administrador/recetas/eliminar" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta receta?');" style="display: inline;">
                            <input type="hidden" name="id_receta" value="<?= $receta['id_receta'] ?>">
                            <button type="submit" class="btn-eliminar" title="Eliminar Receta">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6h14v13q0 .825-.587 1.413T17 21zM17 6H7V5q0-.425.288-.712T8 4h8q.425 0 .713.288T17 5zM9 17q.425 0 .713-.288T10 16V9q0-.425-.288-.712T9 8t-.712.288T8 9v7q0 .425.288.713T9 17m4 0q.425 0 .713-.288T14 16V9q0-.425-.288-.712T13 8t-.712.288T12 9v7q0 .425.288.713T13 17"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron recetas.</p>
    <?php endif; ?>
</section>

<section class="paginacion">
    <?php if ($total_paginas > 1): ?>
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="<?php echo BASE_URL; ?>administrador/panel/recetas?pagina=<?= $i ?>" class="<?= ($i == $pagina_actual) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    <?php endif; ?>
</section>

<script src="<?php echo BASE_URL; ?>administrador/js/panelAdministracion.js"></script>
