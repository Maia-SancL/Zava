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

// Consulta para obtener las recetas favoritas del usuario
$query_favoritas = "
    SELECT r.id_receta, r.nombre, r.descripcion, r.imagen, r.tiempo
    FROM Favoritos_Recetas fr
    JOIN Recetas r ON fr.id_receta = r.id_receta
    WHERE fr.id_usuario = $id_usuario
";
$resultado_favoritas = mysqli_query($conexion, $query_favoritas);
if (!$resultado_favoritas) {
    die("Error en la consulta: " . mysqli_error($conexion));
}
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

<!-- Lista de recetas favoritas -->
<section class="recetas-favoritas">
    <h2>Tus recetas favoritas</h2>
    <div class="contenedor-recetas">
        <?php while ($receta = mysqli_fetch_assoc($resultado_favoritas)): ?>
            <div class="tarjeta-receta">
                <img src="<?= $receta['imagen'] ?? 'default-receta.png' ?>" alt="Imagen de la receta">
                <div class="info-receta">
                    <h3><?= htmlspecialchars($receta['nombre']) ?></h3>
                    <p><?= htmlspecialchars($receta['descripcion']) ?></p>
                    <p><strong>Tiempo:</strong> <?= htmlspecialchars($receta['tiempo']) ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Filtros -->
<aside class="filtros">
    <h3>Filtros</h3>
    <form action="profile-recetas-favoritas.php" method="POST">
        <label for="tipo">Tipo</label>
        <select name="tipo" id="tipo">
            <option value="">Todos</option>
            <option value="desayuno">Desayuno</option>
            <option value="almuerzo">Almuerzo</option>
            <option value="cena">Cena</option>
        </select>

        <label for="ingredientes">Ingredientes</label>
        <input type="text" name="ingredientes" id="ingredientes" placeholder="Buscar por ingredientes">

        <label for="tiempo">Tiempo:</label>
        <div class="tiempo-slider-container">
            <input type="range" name="tiempo" id="tiempo" min="0" max="300" step="5" value="0">
            <div class="tiempo-labels">
                <span></span>
                <span id="tiempo-valor">0 min</span>
                <span></span>
            </div>
        </div>

        <label for="orden">Orden alfabético</label>
        <select name="orden" id="orden">
            <option value="asc">Ascendente</option>
            <option value="desc">Descendente</option>
        </select>

        <button type="submit">Aplicar</button>
        <button type="reset" onclick="resetTiempo()">Limpiar filtros</button>
    </form>
</aside>