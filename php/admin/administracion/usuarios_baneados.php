<?php
if (!isset($result)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php');
    $where = [];
    if (!empty($_GET['buscar'])) {
        $buscar = mysqli_real_escape_string($conexion, $_GET['buscar']);
        $where[] = "(u.nickname LIKE '%$buscar%' OR u.correo LIKE '%$buscar%' OR ub.motivo LIKE '%$buscar%')";
    }
    $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    $query = "SELECT ub.id_baneo, ub.motivo, ub.fecha_baneo, u.nickname, u.correo, u.id_usuario, u.imagen FROM Usuarios_Baneados ub JOIN Usuarios u ON ub.id_usuario = u.id_usuario $whereSQL";
    $result = mysqli_query($conexion, $query);
}
?>

<link rel="stylesheet" href="/Zava/css/admin/tablas.css">

<section class="section-titulo-btn">
    <h2 class="subtitulo">Gestion de Usuarios Baneados</h2>
</section>

<section class="section-tabla-productos">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($baneado = mysqli_fetch_assoc($result)):
        ?>
            <article class="producto">
                <div class="informacion-principal">
                    <div class="informacion">
                        <span class="id">#<?= htmlspecialchars($baneado['id_baneo']) ?></span>
                        <span class="imagen-usuario">
                            <img src="/Zava/img/perfiles/<?= htmlspecialchars($baneado['imagen']) ?>" alt="Imagen de <?= htmlspecialchars($baneado['nickname']) ?>" style="height:32px;max-width:32px;object-fit:cover;margin-left:8px;vertical-align:middle;">
                        </span>
                        <p class="nombre"><strong>Usuario:</strong> <?= htmlspecialchars($baneado['nickname']) ?> (<?= htmlspecialchars($baneado['correo']) ?>)</p>
                        <p><strong>Motivo:</strong> "<?= htmlspecialchars($baneado['motivo']) ?>"</p>
                        <span class="fecha">Fecha de Baneo: <?= htmlspecialchars($baneado['fecha_baneo']) ?></span>
                    </div>
                    <div class="btns">
                        <form action="/Zava/php/admin/funciones/unbanUsuario.php" method="POST" onsubmit="return confirm('Â¿EstÃ¡s seguro de que quieres quitar el baneo a este usuario?');" style="display: inline;">
                            <input type="hidden" name="id_baneo" value="<?= $baneado['id_baneo'] ?>">
                            <input type="hidden" name="id_usuario" value="<?= $baneado['id_usuario'] ?>">
                            <button type="submit" class="btn-editar" title="Quitar Baneo">
                                Quitar Baneo
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron usuarios baneados.</p>
    <?php endif; ?>
</section>
