<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/funciones/tags.php');
if (!isset($conexion)) {
    die('Error: No se pudo establecer la conexión a la base de datos.');
}
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
<link rel="stylesheet" href="/Zava/css/cliente/perfil-inicio.css">
<link rel="stylesheet" href="/Zava/css/cliente/perfilHistorial.css">
<link rel="stylesheet" href="/Zava/css/cliente/editarPerfil.css">
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
            <a> <button class="btn-editar" id="openModalBtn"> Editar perfil
                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="icon" d="M9.49993 15.1341L15.6633 8.9708C14.6264 8.53766 13.6847 7.90514 12.8916 7.10914C12.0952 6.31584 11.4624 5.37385 11.0291 4.33664L4.86577 10.5C4.38493 10.9808 4.1441 11.2216 3.93743 11.4866C3.69359 11.7995 3.48432 12.1379 3.31327 12.4958C3.1691 12.7991 3.0616 13.1225 2.8466 13.7675L1.7116 17.17C1.65936 17.3258 1.65161 17.493 1.68922 17.653C1.72683 17.8129 1.80831 17.9592 1.92449 18.0754C2.04068 18.1916 2.18697 18.2731 2.34692 18.3107C2.50688 18.3483 2.67415 18.3405 2.82993 18.2883L6.23243 17.1533C6.87827 16.9383 7.20077 16.8308 7.5041 16.6866C7.86355 16.5155 8.19993 16.3075 8.51327 16.0625C8.77827 15.8558 9.0191 15.615 9.49993 15.1341ZM17.3733 7.2608C17.9878 6.64628 18.333 5.8128 18.333 4.94372C18.333 4.07465 17.9878 3.24117 17.3733 2.62664C16.7587 2.01211 15.9253 1.66687 15.0562 1.66687C14.1871 1.66687 13.3536 2.01211 12.7391 2.62664L11.9999 3.3658L12.0316 3.4583C12.3958 4.50062 12.9919 5.44663 13.7749 6.22497C14.5765 7.03149 15.5557 7.63934 16.6341 7.99997L17.3733 7.2608Z" fill="#FBF6EE"/>
                    </svg>
                </button>
            </a>
        </div>
    </div>
    <div class="cont-nav">
        <div onclick="location.href='/Zava/php/cliente/perfil/perfil.php'" class="caja-nav">
            <p>Favoritos</p>
        </div>
        <div class="caja-nav-seleccionado">
            <p>Ultimo Visto</p>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilPedidos.php'" class="caja-nav">
            <p>Pedidos</p>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilOpiniones.php'" class="caja-nav">
            <p>Opiniones</p>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilRecetas.php'" class="caja-nav">
            <p>Mis Recetas</p>
        </div>
    </div>
    <?php
    if (!$conexion) {
        echo "<p style='color: red;'>Error: No se pudo conectar a la base de datos.</p>";
    } else {
        $revisar_tabla = "SHOW TABLES LIKE 'historial_vistas'";
        $table_result = mysqli_query($conexion, $revisar_tabla);
        if (mysqli_num_rows($table_result) == 0) {
            echo "<p style='color: orange;'>La tabla 'historial_vistas' no existe en la base de datos.</p>";
        } else {
            $hoy = date('Y-m-d');
            // $ayer = date('Y-m-d', strtotime('-1 day'));
            // Consulta para elementos de hoy (ultimos 5)
            $query_hoy = "SELECT 
                v.fecha_vista, 
                v.tipo_contenido,
                v.id_contenido,
                r.nombre as receta_nombre,
                r.imagen as receta_imagen,
                r.tiempo_preparacion as tiempo_preparacion,
                r.tipo_comida as tipo_comida,
                r.tipo_dieta as tipo_dieta,
                p.nombre as producto_nombre,
                p.imagen as producto_imagen,
                c.nombre as categoria_nombre
            FROM historial_vistas v
            LEFT JOIN recetas r ON v.id_contenido = r.id_receta AND v.tipo_contenido = 'receta'
            LEFT JOIN productos p ON v.id_contenido = p.id_producto AND v.tipo_contenido = 'producto'
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            WHERE v.id_usuario = " . intval($id_usuario) . "
            AND DATE(v.fecha_vista) = '$hoy'
            ORDER BY v.fecha_vista DESC
            LIMIT 5";
            // Consulta para elementos de ayer 
            // $query_ayer = "SELECT 
            //     v.fecha_vista, 
            //     v.tipo_contenido,
            //     v.id_contenido,
            //     r.nombre as receta_nombre,
            //     r.imagen as receta_imagen,
            //     p.nombre as producto_nombre,
            //     p.imagen as producto_imagen
            // FROM historial_vistas v
            // LEFT JOIN recetas r ON v.id_contenido = r.id_receta AND v.tipo_contenido = 'receta'
            // LEFT JOIN productos p ON v.id_contenido = p.id_producto AND v.tipo_contenido = 'producto'
            // WHERE v.id_usuario = " . intval($id_usuario) . "
            // AND DATE(v.fecha_vista) = '$ayer'
            // ORDER BY v.fecha_vista DESC
            // LIMIT 5";
            $resultado_hoy = mysqli_query($conexion, $query_hoy);
            // $resultado_ayer = mysqli_query($conexion, $query_ayer);
    ?>
    <div class="global">
                <div class="contVistos">
                    <div class="buscadorVistos">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.5 14H14.71L14.43 13.73C15.444 12.5541 16.0012 11.0527 16 9.5C16 8.21442 15.6188 6.95772 14.9046 5.8888C14.1903 4.81988 13.1752 3.98676 11.9874 3.49479C10.7997 3.00282 9.49279 2.87409 8.23192 3.1249C6.97104 3.3757 5.81285 3.99477 4.90381 4.90381C3.99477 5.81285 3.3757 6.97104 3.1249 8.23192C2.87409 9.49279 3.00282 10.7997 3.49479 11.9874C3.98676 13.1752 4.81988 14.1903 5.8888 14.9046C6.95772 15.6188 8.21442 16 9.5 16C11.11 16 12.59 15.41 13.73 14.43L14 14.71V15.5L19 20.49L20.49 19L15.5 14ZM9.5 14C7.01 14 5 11.99 5 9.5C5 7.01 7.01 5 9.5 5C11.99 5 14 7.01 14 9.5C14 11.99 11.99 14 9.5 14Z" fill="#1F1F1F"></path>
                        </svg>
                        <input type="text" id="filtro-historial" placeholder="Buscar en tu historial...">
                    </div>
                    <div class="globalVistos">
                        <p>Ultimas 24 horas</p>
                        <?php if ($resultado_hoy && mysqli_num_rows($resultado_hoy) > 0): ?>
                            <div class='grid-historial'>
                            <?php
                            $q = isset($_GET['q']) ? trim($_GET['q']) : '';
                            while ($item = mysqli_fetch_assoc($resultado_hoy)):
                                $nombre = ($item['tipo_contenido'] == 'receta') ? $item['receta_nombre'] : $item['producto_nombre'];
                                $imagen = ($item['tipo_contenido'] == 'receta') ? $item['receta_imagen'] : $item['producto_imagen'];
                                $tipo = $item['tipo_contenido'];
                                $id_contenido = $item['id_contenido'];
                                $categoria = isset($item['categoria_nombre']) ? $item['categoria_nombre'] : '';
                                $fecha = date('H:i', strtotime($item['fecha_vista']));
                                if ($tipo == 'receta') {
                                    $url = "/Zava/php/cliente/mostrarReceta.php?id=$id_contenido";
                                    $ruta_imagen = "/Zava/img/recetas/" . $imagen; // Siempre tomar el nombre de imagen principal
                                } else {
                                    $url = "/Zava/php/cliente/mostrarProducto.php?id=$id_contenido";
                                    $ruta_imagen = "/Zava/img/productos/" . $imagen; // Siempre tomar el nombre de imagen principal
                                }
                                
                                if ($q !== '' && stripos($nombre, $q) === false && stripos($categoria, $q) === false) {
                                    continue;
                                }
                            ?>
                                    <div class="globalCosas">
                                            <div class="contImagen">
                                                <img src='<?php echo $ruta_imagen; ?>' alt='<?php echo htmlspecialchars($nombre); ?>' class='img-historial'>
                                            </div>
                                            <div class="contGlobal">
                                                <div class="contEtiquetas">
                                                    <div class="etiquetas">
                                                    <?php if ($tipo == 'receta') { ?>
                                                        <div class="etiquetaGlobal">
                                                            <?php if (!empty($item['tipo_comida'])) { echo filtrarTags(strtolower($item['tipo_comida'])); } ?>
                                                            <p><?php echo htmlspecialchars($item['tipo_comida'] ?? ''); ?></p>
                                                        </div> 
                                                        <div class="etiquetaGlobal">
                                                            <?php if (!empty($item['tipo_dieta'])) { echo filtrarTags(strtolower($item['tipo_dieta'])); } ?>
                                                            <p><?php echo htmlspecialchars($item['tipo_dieta'] ?? ''); ?></p>
                                                        </div>
                                                    <?php } else { ?>
                                                    <div class="etiquetaGlobal">
                                                        <p><?php echo ucfirst($tipo); ?></p>
                                                    </div>
                                                    <?php if (!empty($item['categoria_nombre'])): ?>
                                                    <div class="etiquetaGlobal etiquetaCategoria">
                                                        <?php echo filtrarTags(strtolower($item['categoria_nombre'])); ?>
                                                        <p><?php echo htmlspecialchars($item['categoria_nombre']); ?></p>
                                                    </div>
                                                    <?php endif; ?>
                                                <?php } ?>
                                                </div>

                                        <div class="favoritoVacio">
                                        <?php if ($tipo == 'receta') {
                                            $isFavorito = false;
                                            $query_fav = "SELECT 1 FROM Favoritos_Recetas WHERE id_usuario = $id_usuario AND id_receta = $id_contenido";
                                            $res_fav = mysqli_query($conexion, $query_fav);
                                            $isFavorito = mysqli_fetch_assoc($res_fav) ? true : false;
                                        ?>
                                        <button class="btn-favorito-historial" data-id="<?= $id_contenido ?>" data-tipo="receta" data-favorito="<?= $isFavorito ? '1' : '0' ?>">
                                            <?php if ($isFavorito): ?>
                                                <svg class="icon-fav-historial" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path fill="currentColor" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125"/></svg>
                                            <?php else: ?>
                                                <svg class="icon-fav-historial" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125z"/></svg>
                                            <?php endif; ?>
                                        </button>
                                        <?php } elseif ($tipo == 'producto') {
                                            
                                            $isFavorito = false;
                                            $query_fav = "SELECT 1 FROM Favoritos_Productos WHERE id_usuario = $id_usuario AND id_producto = $id_contenido";
                                            $res_fav = mysqli_query($conexion, $query_fav);
                                            $isFavorito = mysqli_fetch_assoc($res_fav) ? true : false;
                                        ?>
                                        <button class="btn-favorito-historial" data-id="<?= $id_contenido ?>" data-tipo="producto" data-favorito="<?= $isFavorito ? '1' : '0' ?>">
                                            <?php if ($isFavorito): ?>
                                                <svg class="icon-fav-historial" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path fill="currentColor" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125"/></svg>
                                            <?php else: ?>
                                                <svg class="icon-fav-historial" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125z"/></svg>
                                            <?php endif; ?>
                                        </button>
                                        <?php } ?>
                                    </div>
                                </div>

                                <h5><?php echo htmlspecialchars($nombre); ?></h5>

                                <div class="tiempoReceta">
                                    <div class="tiempoReceta">
                                        <?php if ($tipo == 'receta' && isset($item['tiempo_preparacion']) && $item['tiempo_preparacion'] !== null) { ?>
                                            <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.625 2.75C10.5147 2.75 12.3269 3.50067 13.6631 4.83686C14.9993 6.17306 15.75 7.98533 15.75 9.875C15.75 11.7647 14.9993 13.5769 13.6631 14.9131C12.3269 16.2493 10.5147 17 8.625 17C6.73533 17 4.92306 16.2493 3.58686 14.9131C2.25067 13.5769 1.5 11.7647 1.5 9.875C1.5 7.98533 2.25067 6.17306 3.58686 4.83686C4.92306 3.50067 6.73533 2.75 8.625 2.75ZM8.625 3.5C6.93425 3.5 5.31274 4.17165 4.11719 5.36719C2.92165 6.56274 2.25 8.18425 2.25 9.875C2.25 11.5658 2.92165 13.1873 4.11719 14.3828C5.31274 15.5784 6.93425 16.25 8.625 16.25C9.46218 16.25 10.2912 16.0851 11.0646 15.7647C11.8381 15.4444 12.5408 14.9748 13.1328 14.3828C13.7248 13.7908 14.1944 13.0881 14.5147 12.3146C14.8351 11.5412 15 10.7122 15 9.875C15 8.18425 14.3284 6.56274 13.1328 5.36719C11.9373 4.17165 10.3158 3.5 8.625 3.5ZM8.25 5.75H9V9.815L12.525 11.8475L12.15 12.5L8.25 10.25V5.75Z" fill="#1F1F1F"/>
                                            </svg>
                                            <p><?php echo intval($item['tiempo_preparacion']) . ' min'; ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class='sin-historial'>No has visto nada hoy.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
        }
    }
    ?>
</main>
<div id="editProfileModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Editar Perfil</h2>
            <span class="close-btn">&times;</span>
        </div>
        <div class="modal-body">
            <form action="/Zava/php/cliente/funciones/actualizarPerfil.php" method="post" enctype="multipart/form-data" id="form-perfil">
                <div class="form-group profile-pic-group">
                    <label for="foto">Foto de Perfil:</label>
                    <img src="<?php echo $rutaImg; ?>" alt="Foto de perfil actual" class="current-pic">
                    <input type="file" id="foto" name="foto" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo $apellido; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nickname">Nickname:</label>
                    <input type="text" id="nickname" name="nickname" value="<?php echo $nickname; ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-guardar">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/footer.php';
?>
<script src="/Zava/js/cliente/modalPerfil.js"></script>
<script src="/Zava/js/cliente/perfilHistorial.js"></script>
<script src="/Zava/js/cliente/filtroHistorial.js"></script>