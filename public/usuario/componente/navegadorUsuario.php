<?php
// Verificar si tiene sesión iniciada como Usuario
$sesion_usuario = isset($_SESSION['tipo_usuario']) && isset($_SESSION['id']) && $_SESSION['tipo_usuario'] === 'Usuario';

// Si tiene sesión, obtener datos del usuario
if ($sesion_usuario) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/public/conexion.php');
    $id_usuario = $_SESSION['id'];
    $query = "SELECT nombre, apellido, nickname, foto FROM Usuarios WHERE id_usuario = $id_usuario";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        $nombre = htmlspecialchars($usuario['nombre']);
        $apellido = htmlspecialchars($usuario['apellido']);
        $nickname = htmlspecialchars($usuario['nickname']);
        $foto = $usuario['foto'] ? htmlspecialchars($usuario['foto']) : 'perfil.png';
        $rutaImg = "/Zava/public/img/perfiles/" . $foto;
    }
}
?>

<link rel="stylesheet" href="/Zava/css/usuario/navegadorUsuario.css">
<div class="contenedor-1">
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
                
                <?php if ($sesion_usuario): ?>
                    <!-- ============================================ -->
                    <!-- BLOQUE: NAVEGADOR CON SESIÓN INICIADA -->
                    <!-- ============================================ -->
                    <a href="/Zava/carrito" title="Carrito">
                        <iconify-icon icon="solar:cart-3-bold" class="icon color-primario icon-h5"></iconify-icon>
                    </a>
                    <div class="desplegable-usuario">
                        <iconify-icon icon="solar:user-bold" class="icon color-primario icon-h5"></iconify-icon>
                        <div class="contenedor-desplegable-usuario borde-redondeado shadow">
                            <div class="contenedor-informacion-usuario">
                                <div class="contenedor-imagen">
                                    <img src="<?php echo $rutaImg; ?>" alt="Foto de perfil">
                                </div>
                                <p class="media-negrita nombre pequenio color-primario">
                                    <?php echo $nombre . ' ' . $apellido; ?>
                                </p>
                                <p class="user-name pequenio">@<?php echo $nickname; ?></p>
                            </div>
                            <ul class="contenedor-opciones">
                                <a href="/Zava/perfil">
                                    <li>
                                        <iconify-icon icon="solar:user-bold" class="icon color-primario icon-h6"
                                            title="Perfil"></iconify-icon>
                                        <p class="pequenio medium">Perfil</p>
                                    </li>
                                </a>
                                <a href="/Zava/perfil?seccion=ajustes">
                                    <li>
                                        <iconify-icon icon="material-symbols:settings-rounded"
                                            class="icon color-primario icon-h6" title="Ajustes"></iconify-icon>
                                        <p class="pequenio medium">Ajustes</p>
                                    </li>
                                </a>
                                <a href="/Zava/cerrar-sesion">
                                    <li>
                                        <iconify-icon icon="material-symbols:logout" class="icon color-primario icon-h6"
                                            title="Cerrar sesión"></iconify-icon>
                                        <p class="pequenio medium">Cerrar sesión</p>
                                    </li>
                                </a>
                            </ul>
                        </div>
                    </div>
                    <a href="/Zava/subir" class="btn btn-primario">
                        <iconify-icon icon="material-symbols:add-rounded"
                            class="icon color-primario icon-h5"></iconify-icon>
                        <p class="pequenio color-secundario medium">Crear</p>
                    </a>
                    <!-- FIN BLOQUE: CON SESIÓN -->
                    
                <?php else: ?>
                    <!-- ============================================ -->
                    <!-- BLOQUE: NAVEGADOR SIN SESIÓN -->
                    <!-- ============================================ -->
                    <a href="/Zava/login" class="btn btn-primario">
                        <iconify-icon icon="tabler:user-filled" class="icon color-primario icon-h6"></iconify-icon>
                        <p class="pequenio color-secundario texto-truncado">Iniciar sesión</p>
                    </a>
                    <a href="/Zava/registro" class="btn btn-secundario">
                        <iconify-icon icon="mingcute:user-add-fill" class="icon color-primario icon-h6"></iconify-icon>
                        <p class="pequenio color-primario texto-truncado">Registrarse</p>
                    </a>
                    <!-- FIN BLOQUE: SIN SESIÓN -->
                    
                <?php endif; ?>
                
            </div>
        </div>
    </nav>
</div>

<script src="/Zava/js/desplegarMenuLateral.js"></script>