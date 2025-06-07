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

// Datos del usuario
$nombre = htmlspecialchars($usuario['nombre']);
$apellido = htmlspecialchars($usuario['apellido']);
$nickname = htmlspecialchars($usuario['nickname']);
$foto = $usuario['foto'] ? htmlspecialchars($usuario['foto']) : 'perfil.png';
?>
    <!-- Información del usuario -->
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

        <!-- Secciones Favs principales -->
        <div class="perfil-secciones">
            <div class="seccion"  onclick="location.href='profile-recetas-favoritas.php'">
                <h2 >Recetas</h2>
                <div class="seccion-icono"></div>
            </div>
            <div class="seccion" onclick="location.href='profile-restaurantes-favoritos.php'">
                <h2>Restaurantes</h2>
                <div class="seccion-icono"></div>
            </div>
            <div class="seccion" onclick="location.href='profile-productos-favoritos.php'">
                <h2>Productos</h2>
                <div class="seccion-icono"></div>
            </div>
        </div>