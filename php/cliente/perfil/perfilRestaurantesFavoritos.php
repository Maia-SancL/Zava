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

// Consulta para obtener los restaurantes favoritos del usuario
$query_favoritos = "
    SELECT r.id_restaurante, r.nombre, r.descripcion, r.imagen, r.promedio_calificacion 
    FROM favoritos_restaurantes fr
    JOIN restaurantes r ON fr.id_restaurante = r.id_restaurante
    WHERE fr.id_usuario = $id_usuario
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

<!-- Lista de restaurantes favoritos -->
<section class="restaurantes-favoritos">
    <h2>Tus restaurantes favoritos</h2>
    <div class="contenedor-restaurantes">
        <?php while ($restaurante = mysqli_fetch_assoc($resultado_favoritos)): ?>
            <div class="tarjeta-restaurante">
                <img src="<?= $restaurante['imagen'] ?? 'default-restaurante.png' ?>" alt="Imagen del restaurante">
                <div class="info-restaurante">
                    <h3><?= htmlspecialchars($restaurante['nombre']) ?></h3>
                    <p><?= htmlspecialchars($restaurante['descripcion']) ?></p>
                    <p><strong>Calificación:</strong> <?= $restaurante['promedio_calificacion'] ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Filtros -->
<aside class="filtros">
    <h3>Filtros</h3>
    <form action="profile-restaurantes-favoritos.php" method="POST">
        <label for="calificacion">Calificación mínima</label>
        <input type="number" name="calificacion" id="calificacion" min="0" max="5" step="0.1" placeholder="Ej: 4.5">

        <label for="orden">Orden alfabético</label>
        <select name="orden" id="orden">
            <option value="asc">Ascendente</option>
            <option value="desc">Descendente</option>
        </select>

        <button type="submit">Aplicar</button>
        <button type="reset">Limpiar filtros</button>
    </form>
</aside>