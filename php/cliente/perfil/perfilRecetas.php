<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';
include_once('conexion.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];
$tabla = $_GET['tabla'] ?? 'inicio';

// Datos usuario
$query_usuario = "SELECT nombre, apellido, nickname, foto FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);
$nombre = htmlspecialchars($usuario['nombre']);
$apellido = htmlspecialchars($usuario['apellido']);
$nickname = htmlspecialchars($usuario['nickname']);
$foto = $usuario['foto'] ? htmlspecialchars($usuario['foto']) : 'perfil.png';
$rutaImg="/Zava/img/perfiles/".$foto;
?>
<link rel="stylesheet" href="/Zava/css/perfil-inicio.css">
<link rel="stylesheet" href="/Zava/css/perfil-recetas.css">

    <main>
        <div class="cont-perfil">
            <div class="img-info">
                <img class="img-perfil" src="<?php echo $rutaImg;?>">
            </div>
            <div class="cont-info">
                <div class="nombre-info">
                    <h4><?php echo $nombre." ".$apellido;?> </h4>
                    <h5><?php echo $nickname;?></h5>
                </div>
                <a> <button class="btn-editar"> Editar perfil
                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="icon" d="M9.49993 15.1341L15.6633 8.9708C14.6264 8.53766 13.6847 7.90514 12.8916 7.10914C12.0952 6.31584 11.4624 5.37385 11.0291 4.33664L4.86577 10.5C4.38493 10.9808 4.1441 11.2216 3.93743 11.4866C3.69359 11.7995 3.48432 12.1379 3.31327 12.4958C3.1691 12.7991 3.0616 13.1225 2.8466 13.7675L1.7116 17.17C1.65936 17.3258 1.65161 17.493 1.68922 17.653C1.72683 17.8129 1.80831 17.9592 1.92449 18.0754C2.04068 18.1916 2.18697 18.2731 2.34692 18.3107C2.50688 18.3483 2.67415 18.3405 2.82993 18.2883L6.23243 17.1533C6.87827 16.9383 7.20077 16.8308 7.5041 16.6866C7.86355 16.5155 8.19993 16.3075 8.51327 16.0625C8.77827 15.8558 9.0191 15.615 9.49993 15.1341ZM17.3733 7.2608C17.9878 6.64628 18.333 5.8128 18.333 4.94372C18.333 4.07465 17.9878 3.24117 17.3733 2.62664C16.7587 2.01211 15.9253 1.66687 15.0562 1.66687C14.1871 1.66687 13.3536 2.01211 12.7391 2.62664L11.9999 3.3658L12.0316 3.4583C12.3958 4.50062 12.9919 5.44663 13.7749 6.22497C14.5765 7.03149 15.5557 7.63934 16.6341 7.99997L17.3733 7.2608Z" fill="#FBF6EE"/>
                    </svg>
                </button>
            </a>
        </div>
    </div>

    <div class="cont-nav">
        <div onclick="location.href='/Zava/php/cliente/perfil/perfil.php'" class="caja-nav">
            <a>Favoritos</a>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilHistorial.php'" class="caja-nav">
            <a>Ultimo Visto</a>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilPedidos.php'" class="caja-nav">
            <a>Pedidos</a>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilOpiniones.php'" class="caja-nav">
            <a>Opiniones</a>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilRecetas.php'" class="caja-nav seleccionado">
            <a>Mis Recetas</a>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilReseñas.php'" class="caja-nav">
            <a>Mis Reseñas</a>
        </div>
    </div>

    <div class="main-content">
        <div class="lista-recetas">
            <?php
            $query_recetas = "SELECT id_receta, nombre, descripcion, tiempo_preparacion, imagen, fecha_publicacion, tipo_comida, tipo_dieta FROM Recetas WHERE id_usuario = $id_usuario ORDER BY fecha_publicacion DESC";
            $resultado_recetas = mysqli_query($conexion, $query_recetas);

            if (mysqli_num_rows($resultado_recetas) > 0) {
                while ($receta = mysqli_fetch_assoc($resultado_recetas)) {
                    $ruta_imagen_receta = "/Zava/img/recetas/" . htmlspecialchars($receta['imagen']);
                    $fecha_formateada = date("d/m/Y", strtotime($receta['fecha_publicacion']));
            ?>
                    <div class="receta-card">
                        <img class="receta-img" src="<?php echo $ruta_imagen_receta; ?>" alt="<?php echo htmlspecialchars($receta['nombre']); ?>">
                        <div class="receta-info">
                            <div class="receta-header">
                                <span class="tag-tipo-comida"><?php echo htmlspecialchars(ucfirst($receta['tipo_comida'])); ?></span>
                                <span class="tag-tipo-dieta"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $receta['tipo_dieta']))); ?></span>
                                <span class="fecha"><?php echo $fecha_formateada; ?></span>
                            </div>
                            <h3 class="receta-titulo"><?php echo htmlspecialchars($receta['nombre']); ?></h3>
                            <p class="receta-descripcion"><?php echo htmlspecialchars($receta['descripcion']); ?></p>
                            <div class="receta-meta">
                                <span class="tiempo"><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($receta['tiempo_preparacion']); ?> min</span>
                            </div>
                            <div class="receta-actions">
                                <a href="/Zava/php/cliente/modificarReceta.php?id=<?php echo $receta['id_receta']; ?>" class="btn-modificar">
                                    <i class="fa-solid fa-pencil"></i> Modificar
                                </a>
                                <a href="/Zava/php/cliente/eliminarReceta.php?id=<?php echo $receta['id_receta']; ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar esta receta?');">
                                    <i class="fa-solid fa-trash"></i> Eliminar
                                </a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<div class='no-recetas'><p>Aún no has creado ninguna receta. ¡Anímate a compartir tus creaciones!</p> <a href='/Zava/php/cliente/crear.php' class='btn-crear-receta'>Crear mi primera receta</a></div>";
            }
            ?>
        </div>
    </div>