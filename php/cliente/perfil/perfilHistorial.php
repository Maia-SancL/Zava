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

// Datos usuario
$id_usuario_esc = mysqli_real_escape_string($conexion, $id_usuario);
$query_usuario = "SELECT nombre, apellido, nickname, foto FROM usuarios WHERE id_usuario = '{$id_usuario_esc}'";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);

$nombre = htmlspecialchars($usuario['nombre']);
$apellido = htmlspecialchars($usuario['apellido']);
$nickname = htmlspecialchars($usuario['nickname']);
$foto = $usuario['foto'] ? htmlspecialchars($usuario['foto']) : 'perfil.png';
$rutaImg = "/Zava/img/perfiles/" . $foto;
?>
<link rel="stylesheet" href="/Zava/css/perfil-inicio.css">
<link rel="stylesheet" href="/Zava/css/perfilHistorial.css">
<main>
    <div class="cont-perfil">
        <div class="img-info">
            <img class="img-perfil" src="<?php echo $rutaImg; ?>">
        </div>
        <div class="cont-info">
            <div class="nombre-info">
                <h4><?php echo $nombre . " " . $apellido; ?></h4>
                <h5><?php echo $nickname; ?></h5>
            </div>
            <a>
                <button class="btn-editar"> Editar perfil
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
        <div class="caja-nav seleccionado">
            <a>Historial</a>
        </div>
        <div  onclick="location.href='/Zava/php/cliente/perfil/perfilPedidos.php'" class="caja-nav">
            <a>Pedidos</a>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilOpiniones.php'" class="caja-nav">
            <a>Opiniones</a>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilRecetas.php'" class="caja-nav">
            <a>Mis Recetas</a>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilReseñas.php'" class="caja-nav">
            <a>Mis Reseñas</a>
        </div>
    </div>

    <?php
    function mostrar_historial($conexion, $id_usuario, $fecha, $titulo) {
        $query = "SELECT 
                    v.fecha_vista AS fecha_de_la_vista, 
                    v.tipo_contenido,
                    r.id_receta,
                    r.nombre AS receta_nombre,
                    r.imagen_principal AS receta_imagen,
                    p.id_producto,
                    p.nombre AS producto_nombre,
                    p.imagen AS producto_imagen
                  FROM historial_vistas v
                  LEFT JOIN recetas r ON v.id_contenido = r.id_receta AND v.tipo_contenido = 'receta'
                  LEFT JOIN productos p ON v.id_contenido = p.id_producto AND v.tipo_contenido = 'producto'
                  WHERE v.id_usuario = ? AND DATE(v.fecha_vista) = ?
                  AND (v.tipo_contenido = 'receta' OR v.tipo_contenido = 'producto')
                  ORDER BY v.fecha_vista DESC";
        
        $id_usuario_esc = mysqli_real_escape_string($conexion, $id_usuario);
        $fecha_esc = mysqli_real_escape_string($conexion, $fecha);

   
        $query = "SELECT 
                    v.fecha_vista AS fecha_de_la_vista, 
                    v.tipo_contenido,
                    r.id_receta,
                    r.nombre AS receta_nombre,
                    r.imagen_principal AS receta_imagen,
                    p.id_producto,
                    p.nombre AS producto_nombre,
                    p.imagen AS producto_imagen
                  FROM historial_vistas v
                  LEFT JOIN recetas r ON v.id_contenido = r.id_receta AND v.tipo_contenido = 'receta'
                  LEFT JOIN productos p ON v.id_contenido = p.id_producto AND v.tipo_contenido = 'producto'
                  WHERE v.id_usuario = '{$id_usuario_esc}' AND DATE(v.fecha_vista) = '{$fecha_esc}'
                  AND (v.tipo_contenido = 'receta' OR v.tipo_contenido = 'producto')
                  ORDER BY v.fecha_vista DESC";

        $resultado = mysqli_query($conexion, $query);

        echo '<div class="seccion-historial">';
        echo '<h3>' . $titulo . '</h3>';
        echo '<div class="lista-historial">';

        if (mysqli_num_rows($resultado) > 0) {
            while ($item = mysqli_fetch_assoc($resultado)) {
                $es_receta = ($item['tipo_contenido'] == 'receta');

                $nombre = $es_receta ? $item['receta_nombre'] : $item['producto_nombre'];
                $imagen = $es_receta ? $item['receta_imagen'] : $item['producto_imagen'];
                $id = $es_receta ? $item['id_receta'] : $item['id_producto'];
                $ruta_imagen = $es_receta ? '/Zava/img/recetas/' : '/Zava/img/productos/';
                $enlace = $es_receta ? '/Zava/php/cliente/mostrarReceta.php?id=' : '/Zava/php/cliente/mostrarProducto.php?id=';
                $texto_boton = $es_receta ? 'Ver receta' : 'Ver producto';

                echo '<div class="item-historial">';
                echo '<div class="imagen-historial">';
                if ($imagen) {
                    echo '<img src="' . $ruta_imagen . htmlspecialchars($imagen) . '" alt="' . htmlspecialchars($nombre) . '">';
                } else {
                    echo '<div class="sin-imagen">' . ($es_receta ? '📖' : '📦') . '</div>';
                }
                echo '</div>';
                echo '<div class="info-historial">';
                echo '<h4>' . htmlspecialchars($nombre) . '</h4>';
                echo '<p>Visto a las ' . date('H:i', strtotime($item['fecha_de_la_vista'])) . '</p>';
                echo '<a href="' . $enlace . $id . '" class="btn-ver-historial">' . $texto_boton . '</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            $mensaje = ($titulo == 'Hoy') ? 'No has visto nada hoy.' : 'No viste nada ayer.';
            echo '<div class="sin-historial">' . $mensaje . '</div>';
        }
        echo '</div>';
        echo '</div>';
    }

    // Mostrar historial de hoy y ayer
    mostrar_historial($conexion, $id_usuario, date('Y-m-d'), 'Hoy');
    mostrar_historial($conexion, $id_usuario, date('Y-m-d', strtotime('-1 day')), 'Ayer');
    ?>
</main>

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/footer.php';
?>