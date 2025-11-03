<?php
session_start();

if (isset($_SESSION['tipo_usuario']) && isset($_SESSION['id'])) {
    if ($_SESSION['tipo_usuario'] === 'Usuario') {
        include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/public/conexion.php');
        $id_usuario = $_SESSION['id'];
        $query = "SELECT nombre, apellido, nickname, imagen FROM usuarios WHERE id_usuario = $id_usuario";
        $resultado = mysqli_query($conexion, $query);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $usuario = mysqli_fetch_assoc($resultado);
            $nombre = htmlspecialchars($usuario['nombre']);
            $nickname = htmlspecialchars($usuario['nickname']);
            $foto = $usuario['imagen'] ? htmlspecialchars($usuario['imagen']) : 'perfil.png';
            $rutaImg = BASE_URL . "public/img/perfiles/" . $foto;
        }
    }
}
?>

<link rel="stylesheet" href="/Zava/css/usuario/navegadorUsuario.css">
<div class=" contenedor-1">
    <nav class="navegador">
        <div class="barra-busqueda">
            <iconify-icon icon="ic:round-search" class="icon icon-h6"></iconify-icon>
            <input class="input-busqueda borde-redondeado" type="text" autocomplete="off" placeholder="Búscar">
        </div>
        <div class="contenedor-botones-navegador">
            <button class="menu-desplegable oculto" id="desplegar-menu">
                <iconify-icon icon="ic:round-menu" class="icon color-primario icon-h5"></iconify-icon>
            </button>
            <div class="contenedor-botones-usuario">
                <a href="/Zava/public/public/iniciarSesion.php" class="btn btn-primario ">
                    <iconify-icon icon="tabler:user-filled" class="icon color-primario icon-h6"></iconify-icon>
                    <p class="pequenio color-secundario texto-truncado">Iniciar sesión</p>
                </a>
                <a href="/Zava/public/public/registrarse.php" class="btn btn-secundario">
                    <iconify-icon icon="mingcute:user-add-fill" class="icon color-primario icon-h6"></iconify-icon>
                    <p class="pequenio color-primario texto-truncado">Registrarse</p>
                </a>
                <!-- <a href="">
                    <iconify-icon icon="solar:cart-3-bold" class="icon color-primario icon-h5"></iconify-icon>
                </a>
                <div class="desplegable-usuario">
                    <iconify-icon icon="solar:user-bold" class="icon color-primario icon-h5"></iconify-icon>
                    <div class="contenedor-desplegable-usuario borde-redondeado shadow">
                        <div class="contenedor-informacion-usuario">
                            <div class="contenedor-imagen">
                                <img src="/Zava-ESTILOS NUEVOS/css/imagenes/Logo 4.0.png">
                            </div>
                            <p class="media-negrita nombre pequenio color-primario">Carlos Joaquin Roldan De La Calle
                            </p>
                            <p class="user-name pequenio">@jolareka</p>
                        </div>
                        <ul class="contenedor-opciones">
                            <a>
                                <li>
                                    <iconify-icon icon="solar:user-bold" class="icon color-primario icon-h6"
                                        title="Perfil"></iconify-icon>
                                    <p class="pequenio medium">Perfil</p>
                                </li>
                            </a>
                            <a>
                                <li>
                                    <iconify-icon icon="material-symbols:settings-rounded"
                                        class="icon color-primario icon-h6" title="Ajustes"></iconify-icon>
                                    <p class="pequenio medium">Ajustes</p>
                                </li>
                            </a>
                            <a>
                                <li>
                                    <iconify-icon icon="material-symbols:logout" class="icon color-primario icon-h6"
                                        title="Cerrar sesión"></iconify-icon>
                                    <p class="pequenio medium">Cerrar sesión</p>
                                </li>
                            </a>
                        </ul>
                    </div>
                </div>
                <a href="/Zava/public/usuario/crear.php" class="btn btn-primario">
                    <iconify-icon icon="material-symbols:add-rounded"
                        class="icon color-primario icon-h5"></iconify-icon>
                    <p class="pequenio color-secundario medium">Crear</p>
                </a> -->
            </div>
        </div>
    </nav>
</div>

<script src="/Zava/js/desplegarMenuLateral.js"></script>