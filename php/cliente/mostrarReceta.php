<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/cliente/conexion.php';
session_start();
if (isset($_SESSION['id']) && isset($_GET['id_receta']) && is_numeric($_GET['id_receta'])) {
    $id_usuario_hist = intval($_SESSION['id']);
    $id_receta_hist = intval($_GET['id_receta']);
    $tipo_contenido = 'receta';
    // Insertar la vista
    $conexion->query("INSERT INTO Historial_Vistas (id_usuario, tipo_contenido, id_contenido, fecha_vista) VALUES ($id_usuario_hist, '$tipo_contenido', $id_receta_hist, NOW())");
    // Eliminar duplicados del mismo contenido en el día, dejando solo el más reciente
    $conexion->query("DELETE hv FROM Historial_Vistas hv 
        JOIN (SELECT id_usuario, tipo_contenido, id_contenido, MAX(id_vista) as max_id
              FROM Historial_Vistas
              WHERE id_usuario = $id_usuario_hist AND DATE(fecha_vista) = CURDATE()
              GROUP BY tipo_contenido, id_contenido
              HAVING COUNT(*) > 1) sub
        ON hv.id_usuario = sub.id_usuario AND hv.tipo_contenido = sub.tipo_contenido AND hv.id_contenido = sub.id_contenido
        WHERE hv.id_vista < sub.max_id");
    // Limitar a los últimos 5 vistos del día actual (productos y recetas juntos)
    $res = $conexion->query("SELECT id_vista FROM Historial_Vistas WHERE id_usuario = $id_usuario_hist AND DATE(fecha_vista) = CURDATE() ORDER BY fecha_vista DESC");
    $ids = array();
    while($row = $res->fetch_assoc()){ $ids[] = $row['id_vista']; }
    if(count($ids) > 5){
        $ids_to_delete = array_slice($ids, 5);
        $ids_str = implode(',', $ids_to_delete);
        $conexion->query("DELETE FROM Historial_Vistas WHERE id_vista IN ($ids_str)");
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/conexion.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/funciones/tags.php';

// Inicializar mensaje
$mensaje = '';
$receta = null;
$usuario = null;

// Validar el parámetro de la URL
if (isset($_GET['id_receta']) && is_numeric($_GET['id_receta']) && intval($_GET['id_receta']) > 0) {
    $id_receta = intval($_GET['id_receta']);

    // Consultar la receta
    $query = "SELECT * FROM Recetas WHERE id_receta = $id_receta";
    $resultado = mysqli_query($conexion, $query);
    $receta = mysqli_fetch_assoc($resultado);

    if (!$receta) {
        $mensaje = "Receta no encontrada.";
    } else {
        // Consultar datos del usuario creador de la receta
        $id_usuario = $receta['id_usuario'];
        $query_usuario = "SELECT id_usuario, nombre, apellido, nickname, foto FROM Usuarios WHERE id_usuario = $id_usuario";
        $resultado_usuario = mysqli_query($conexion, $query_usuario);
        $usuario_receta = mysqli_fetch_assoc($resultado_usuario);

        // Consultar imágenes adicionales
        $query_imagenes = "SELECT ruta_imagen FROM Receta_Imagenes WHERE id_receta = $id_receta AND es_principal = 0 LIMIT 2";
        $resultado_imagenes = mysqli_query($conexion, $query_imagenes);
        $imagenes_adicionales = [];
        while ($fila = mysqli_fetch_assoc($resultado_imagenes)) {
            $imagenes_adicionales[] = $fila['ruta_imagen'];
        }
    }
} else {
    $mensaje = "Receta no encontrada.";
};


function formatoTiempo($min) {
    return $min < 60 ? "$min min" : (floor($min/60) . " hr" . (($min % 60) ? ' ' . ($min % 60) . ' min' : ''));
}

function convertirTiempoAMinutos($hora) {
    list($h, $m, $s) = explode(':', $hora);
    return ($h * 60) + $m;
}
?>

<div class="layout">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/menuLateral.php';?>
    <link rel="stylesheet" href="/Zava/css/mostrarReceta.css">
    <main>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';?>
        <?php
        if (!empty($mensaje)) {
            echo $mensaje;
        }?>
        <?php 
        // Asegurarse de que la ruta de la imagen principal sea correcta
        $rutaPrincipal = "/Zava/imagenes/recetas/" . $receta['imagen_principal'];
        ?>
        <article class="cont-imagenes-receta">
            <div class="cont-img-izquierda">
                <img src="<?= $rutaPrincipal ?>" alt="Imagen principal de la receta">
            </div>
            <div class="cont-img-derecha">
                <?php foreach ($imagenes_adicionales as $index => $img_path): ?>
                    <div class="cont-img">
                        <img src="/Zava/imagenes/recetas/<?= htmlspecialchars($img_path) ?>" alt="Imagen adicional de la receta <?= $index + 1 ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </article>
        <div class="cont-titulo-receta">
            <h1 class="titulo-receta"><?php echo $receta['nombre'];?></h1>
        </div>
        <section class="cont-tags-favorito">
            <article class="cont-tags">
                <div class="tag tiempo">
                    <?php $minutos = $receta['tiempo_preparacion'];?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M17 3.34a10 10 0 1 1-14.995 8.984L2 12l.005-.324A10 10 0 0 1 17 3.34M12 6a1 1 0 0 0-.993.883L11 7v5l.009.131a1 1 0 0 0 .197.477l.087.1l3 3l.094.082a1 1 0 0 0 1.226 0l.094-.083l.083-.094a1 1 0 0 0 0-1.226l-.083-.094L13 11.585V7l-.007-.117A1 1 0 0 0 12 6"/></svg>
                    <span class="lbl-tiempo"><?php echo formatoTiempo($minutos);?></span>
                </div>
                <div class="tag porciones">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"><circle cx="152" cy="184" r="72" fill="currentColor" class="icon"/><path class="icon" fill="currentColor" d="M234 296c-28.16-14.3-59.24-20-82-20c-44.58 0-136 27.34-136 82v42h150v-16.07c0-19 8-38.05 22-53.93c11.17-12.68 26.81-24.45 46-34"/><path class=icon fill="currentColor" d="M340 288c-52.07 0-156 32.16-156 96v48h312v-48c0-63.84-103.93-96-156-96"/><circle class="icon" cx="340" cy="168" r="88" fill="currentColor"/></svg>
                    <span class="lbl-porciones"><?php echo $receta['porciones'];?></span>
                </div>
                <?php if(isset($receta['tipo_dieta'])){
                        switch ($receta['tipo_dieta']){
                            case('vegano'):?>
                                <div class="tag">
                                    <?php echo filtrarTags($receta['tipo_dieta']);?>
                                    <span>Vegano</span>
                                </div> 
                                <?php break;
                            case ('vegetariano'):
                                echo filtrarTags($receta['tipo_dieta']);?>
                                <span>Vegetariana</span>
                                <?php break;
                            default:
                             break;
                        }
                    }?>
                <div class="tag tipo">
                    <?php echo filtrarTags($receta['tipo_comida']);?>
                    <span class="lbl-tipo"><?php echo ucfirst($receta['tipo_comida']); ?></span>
                </div>
            </article>
            <article class="cont-fav-compartir">
                <?php
$isFavorito = false;
if (isset($_SESSION['id']) && isset($receta['id_receta'])) {
    $id_usuario = $_SESSION['id'];
    $id_receta_actual = $receta['id_receta'];
    $query_fav = "SELECT 1 FROM Favoritos_Recetas WHERE id_usuario = $id_usuario AND id_receta = $id_receta_actual";
    $res_fav = mysqli_query($conexion, $query_fav);
    $isFavorito = mysqli_fetch_assoc($res_fav) ? true : false;
}
?>
<button id="btn-favorito" class="btn-favorito" data-favorito="<?= $isFavorito ? '1' : '0' ?>">
    <?php if ($isFavorito): ?>
        <!-- icono relleno -->
        <svg id="icon-fav" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125"/></svg>
    <?php else: ?>
        <!-- icono vacio -->
        <svg id="icon-fav" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="none" stroke="currentColor" stroke-width="2" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125z"/></svg>
    <?php endif; ?>
</button>
<script> // codigo para agregar y eliminar favoritos
document.addEventListener('DOMContentLoaded', function() { 
    const btnFav = document.getElementById('btn-favorito'); // boton favorito
    if (!btnFav) return; // si no existe el boton favorito
    btnFav.addEventListener('click', function(e) { // cuando se hace click en el boton favorito
        e.preventDefault(); // prevenir el comportamiento por defecto
        const esFavorito = btnFav.getAttribute('data-favorito') === '1'; // verificar si es favorito
        const idReceta = <?= isset($receta['id_receta']) ? intval($receta['id_receta']) : 0 ?>; // id de la receta
        if (!idReceta) return; // si no existe el id de la receta
        const accion = esFavorito ? 'eliminar' : 'agregar'; // accion a realizar
        fetch(`/Zava/php/cliente/perfil/${accion}_favorito.php`, { // ruta del archivo php
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `tipo=receta&id=${idReceta}`
        })
        .then(response => response.json()) // respuesta del archivo php
        .then(data => { 
            if (data.success) {
                btnFav.setAttribute('data-favorito', esFavorito ? '0' : '1'); // actualizar el atributo data-favorito
                const iconFav = document.getElementById('icon-fav'); // icono favorito
                if (esFavorito) {
                    iconFav.outerHTML = `<svg id="icon-fav" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="none" stroke="currentColor" stroke-width="2" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125z"/></svg>`;
                } else {
                    iconFav.outerHTML = `<svg id="icon-fav" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125"/></svg>`;
                }
            } else {
                alert(data.message || 'Error al actualizar favorito'); // mensaje de error
            }
        })
        .catch(err => {
            alert('Error de conexión');
        });
    });
});
</script>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81c1.66 0 3-1.34 3-3s-1.34-3-3-3s-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65c0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92"/></svg>
            </article>
        </section>

        <section class="info-princiapl">
            <div class="info-user">
                <?php $rutaImg = "/Zava/img/perfiles/". $usuario_receta['foto'];?>
                <div class="cont-img">
                    <img src="<?php echo $rutaImg;?>" alt="Foto de perfil">
                </div>
                <div class="info">
                    <div class="fullname-username">
                        <span class="fullname"><?php echo $usuario_receta['nombre']." ".$usuario_receta['apellido'];?></span>
                        <span class="username">@<?php echo $usuario_receta['nickname'];?></span>
                    </div>
                    <div class="fecha-publicacion">
                        <span class="fecha"><?php echo date('d-m-Y', strtotime($receta['fecha_publicacion'])); ?></span>
                    </div>
                </div>
            </div>

            <article class="cont-descripcion">
                <p class="descripcion"><?php echo $receta['descripcion'];?></p>
            </article>

            <article class="cont-ingredientes-pasos">
                <div class="cont-ingredientes">
                    <div class="subtitulo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path class="icon" d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z"/><path class="icon" fill="currentColor" d="M12.03 13.739L4.422 7.705a2.337 2.337 0 1 1 3.283-3.283l6.034 7.608c1.307-.571 3.3-.648 4.979 1.03c1.015 1.016 1.647 2.258 1.863 3.44c.21 1.15.049 2.426-.803 3.278c-.851.852-2.128 1.013-3.277.803c-1.182-.216-2.425-.848-3.44-1.864c-1.68-1.679-1.602-3.671-1.031-4.978"/></g></svg>               
                        <h4>Ingredientes</h4>
                    </div>
                    <ul>
                        <?php $texto_dividido = preg_split('/\s*,\s*/', $receta['ingredientes']);?>
                         <?php foreach ($texto_dividido as $linea) {
                            echo "<li>$linea</li>";
                        }?>
                    </ul>
                </div>

                <div class="cont-pasos">
                    <div class="subtitulo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 14 14"><path fill="currentColor" class="icon" fill-rule="evenodd" d="M.658.44A1.5 1.5 0 0 1 1.718 0h5.587a1.5 1.5 0 0 1 1.06.44l3.414 3.414a1.5 1.5 0 0 1 .44 1.06V12.5a1.5 1.5 0 0 1-1.5 1.5h-9a1.5 1.5 0 0 1-1.5-1.5v-11c0-.398.158-.78.44-1.06ZM5.33 4.527a.75.75 0 0 1 .175 1.047L4.108 7.53a.75.75 0 0 1-1.14.094l-.838-.838a.75.75 0 0 1 1.06-1.06l.212.211l.882-1.234a.75.75 0 0 1 1.046-.175Zm.95 1.847a.75.75 0 0 1 .75-.75h2.5a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75m0 3.969a.75.75 0 0 1 .75-.75h2.5a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75m-.775-.738a.75.75 0 1 0-1.22-.872l-.883 1.235l-.212-.212a.75.75 0 0 0-1.06 1.06l.838.838a.75.75 0 0 0 1.14-.094z" clip-rule="evenodd"/></svg>
                        <h4>Pasos</h4>
                    </div>
                    <ol>
                        <?php 
                            // Dividir los pasos por punto o punto y coma, y eliminar elementos vacíos
                            $pasos_lista = preg_split('/[;.]\s*/', $receta['pasos'], -1, PREG_SPLIT_NO_EMPTY);
                            foreach ($pasos_lista as $paso) {
                                echo "<li>" . trim($paso) . "</li>";
                            }
                        ?>
                    </ol>
                </div>
            </article>
        </section>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/mostrarComentarios.php';?>
    </main>
</div>
<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/footer.php';
?>
<script src="/Zava/js/comentarios.js"></script>

