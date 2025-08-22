<?php
if (!isset($result)) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php');
    $where = [];
    if (!empty($_GET['buscar'])) {
        $buscar = mysqli_real_escape_string($conexion, $_GET['buscar']);
        $where[] = "(nombre LIKE '%$buscar%' OR apellido LIKE '%$buscar%' OR nickname LIKE '%$buscar%' OR correo LIKE '%$buscar%')";
    }
    if (isset($_GET['estado']) && $_GET['estado'] !== '') {
        $estado = ($_GET['estado'] == '1') ? 1 : 0;
        $where[] = "activo = $estado";
    }
    $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    $query = "SELECT id_usuario, nombre, apellido, nickname, correo, activo, imagen FROM Usuarios $whereSQL";
    $result = mysqli_query($conexion, $query);
}
?>

<link rel="stylesheet" href="/Zava/css/admin/tablas.css">

<section class="section-titulo-btn">
    <h2 class="subtitulo">Gestion de Usuarios</h2>
</section>

<section class="section-tabla-productos">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($usuario = mysqli_fetch_assoc($result)):
            $estado = $usuario['activo'] ? 'Activo' : 'Inactivo';
        ?>
            <article class="producto">
                <div class="informacion-principal">
                    <div class="informacion">
                        <span class="id">#<?= htmlspecialchars($usuario['id_usuario']) ?></span>
                        <span class="nombre">
    <img src="/Zava/img/perfiles/<?= htmlspecialchars($usuario['imagen']) ?>" alt="Imagen de <?= htmlspecialchars($usuario['nickname']) ?>" style="height:32px;max-width:32px;object-fit:cover;margin-right:4px;vertical-align:middle;">
    <?= htmlspecialchars($usuario['nombre']) . ' ' . htmlspecialchars($usuario['apellido']) ?> (<?= htmlspecialchars($usuario['nickname']) ?>)
</span>
                        <span class="vendedor">Correo: <?= htmlspecialchars($usuario['correo']) ?></span>
                        <span class="estado <?= $estado === 'Activo' ? 'estado-activo' : 'estado-inactivo' ?>"><?= $estado ?></span>
                    </div>
                    <div class="btns">
                        <form action="/Zava/php/admin/funciones/toggleUsuario.php" method="POST" style="display: inline;">
                            <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                            <button type="submit" class="btn-editar" title="<?= $estado === 'Activo' ? 'Desactivar' : 'Activar' ?> Usuario">
                                <?= $estado === 'Activo' ? 'Desactivar' : 'Activar' ?>
                            </button>
                        </form>
                        <form action="/Zava/php/admin/funciones/banearUsuario.php" method="POST" onsubmit="return confirm('Â¿EstÃ¡s seguro de que quieres banear a este usuario?');" style="display: inline;">
                            <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                            <button type="submit" class="btn-eliminar" title="Banear Usuario">
                                Banear
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron usuarios.</p>
    <?php endif; ?>
</section>
