<?php

session_start();
include_once('./php/componentes/header.php');
include_once('./php/componentes/navegador.php');
include_once('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];

// Consulta para obtener los datos del usuario
$query_usuario = "SELECT nombre, apellido, nickname, foto FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);

$nombre = htmlspecialchars($usuario['nombre']);
$apellido = htmlspecialchars($usuario['apellido']);
$nickname = htmlspecialchars($usuario['nickname']);
$foto = $usuario['foto'] ? htmlspecialchars($usuario['foto']) : 'perfil.png';

// Consulta para obtener los productos favoritos del usuario
$query_favoritos = "
    SELECT p.id_producto, p.nombre, p.descripcion, p.imagen, p.precio
    FROM favoritos_productos fp
    JOIN productos p ON fp.id_producto = p.id_producto
    WHERE fp.id_usuario = $id_usuario
";
$resultado_favoritos = mysqli_query($conexion, $query_favoritos);
?>

<!-- Información del usuario -->
<div class="perfil">
    <div class="perfil-foto">
        <img src="<?= $foto ?>" alt="Foto de perfil">
    </div>
    <div class="perfil-info">
        <h1 class="perfil-nombre"><?= $nombre ?> <?= $apellido ?></h1>
        <p class="perfil-nickname">@<?= $nickname ?></p>
        <p class="perfil-ubicacion">Ubicación</p>
    </div>
    <button class="btn-editar-perfil" onclick="location.href='profile-editar.php'">Editar perfil</button>
</div>

<!-- Opciones del perfil -->
<div class="perfil-opciones">
    <button class="btn-opcion activo" onclick="location.href='profile-inicio.php'">Favoritos</button>
    <button class="btn-opcion" onclick="location.href='profile-historial.php'">Último visto</button>
    <button class="btn-opcion" onclick="location.href='profile-pedidos.php'">Pedidos</button>
    <button class="btn-opcion">Comentarios y reseñas</button>
    <button class="btn-opcion">Mis recetas</button>
    <button class="btn-opcion">Mis restaurantes</button>
</div>

<!-- Lista de productos favoritos -->
<section class="productos-favoritos">
    <h2>Tus productos favoritos</h2>
    <div class="contenedor-productos">
        <?php if (mysqli_num_rows($resultado_favoritos) > 0): ?>
            <?php while ($producto = mysqli_fetch_assoc($resultado_favoritos)): ?>
                <div class="tarjeta-producto">
                    <img src="<?= $producto['imagen'] ?? 'default-producto.png' ?>" alt="Imagen del producto">
                    <div class="info-producto">
                        <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                        <p><?= htmlspecialchars($producto['descripcion']) ?></p>
                        <p><strong>Precio:</strong> $<?= number_format($producto['precio'], 2) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No tienes productos favoritos.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Filtros -->
<aside class="filtros">
    <h3>Filtros</h3>
    <form action="profile-productos-favoritos.php" method="POST">
        <label for="precio_min">Precio mínimo</label>
        <input type="number" name="precio_min" id="precio_min" min="0" step="0.01" placeholder="Ej: 10.00">

        <label for="precio_max">Precio máximo</label>
        <input type="number" name="precio_max" id="precio_max" min="0" step="0.01" placeholder="Ej: 100.00">

        <label for="orden">Orden alfabético</label>
        <select name="orden" id="orden">
            <option value="asc">Ascendente</option>
            <option value="desc">Descendente</option>
        </select>

        <button type="submit">Aplicar</button>
        <button type="reset">Limpiar filtros</button>
    </form>
</aside>