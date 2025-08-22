<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'add_to_cart') {
    ob_start();

    include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/cliente/conexion.php';

    $response = ['status' => 'error', 'message' => 'Ocurrió un error inesperado.'];
    $id_producto_carrito = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
    $cantidad_a_agregar = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

    if ($id_producto_carrito > 0 && $cantidad_a_agregar > 0) {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        
        if (isset($_SESSION['carrito'][$id_producto_carrito])) {
            $_SESSION['carrito'][$id_producto_carrito] += $cantidad_a_agregar;
        } else {
            $_SESSION['carrito'][$id_producto_carrito] = $cantidad_a_agregar;
        }

        $total_productos = array_sum($_SESSION['carrito']);

        $response = [
            'status' => 'success',
            'message' => 'Producto agregado correctamente.',
            'total_productos' => $total_productos
        ];
    } else {
        $response['message'] = 'Datos de producto o cantidad no válidos.';
    }

    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/cliente/conexion.php';

if (isset($_SESSION['id']) && isset($_GET['id_producto'])) {
    $id_usuario_hist = intval($_SESSION['id']);
    $id_producto_hist = intval($_GET['id_producto']);
    $tipo_contenido = 'producto';
    $conexion->query("INSERT INTO Historial_Vistas (id_usuario, tipo_contenido, id_contenido, fecha_vista) VALUES ($id_usuario_hist, '$tipo_contenido', $id_producto_hist, NOW())");
    $conexion->query("DELETE hv FROM Historial_Vistas hv 
        JOIN (SELECT id_usuario, tipo_contenido, id_contenido, MAX(id_vista) as max_id
              FROM Historial_Vistas
              WHERE id_usuario = $id_usuario_hist AND DATE(fecha_vista) = CURDATE()
              GROUP BY tipo_contenido, id_contenido
              HAVING COUNT(*) > 1) sub
        ON hv.id_usuario = sub.id_usuario AND hv.tipo_contenido = sub.tipo_contenido AND hv.id_contenido = sub.id_contenido
        WHERE hv.id_vista < sub.max_id");
    $res = $conexion->query("SELECT id_vista FROM Historial_Vistas WHERE id_usuario = $id_usuario_hist AND DATE(fecha_vista) = CURDATE() ORDER BY fecha_vista DESC");
    $ids = array();
    while($row = $res->fetch_assoc()){ $ids[] = $row['id_vista']; }
    if(count($ids) > 5){
        $ids_to_delete = array_slice($ids, 5);
        $ids_str = implode(',', $ids_to_delete);
        $conexion->query("DELETE FROM Historial_Vistas WHERE id_vista IN ($ids_str)");
    }
}

$id_producto = $_GET['id_producto']; 

$query_producto = "SELECT * FROM productos WHERE id_producto = $id_producto";
$resultado_producto = mysqli_query($conexion, $query_producto);
$producto = mysqli_fetch_array($resultado_producto);

$id_usuario = $producto['id_usuario'];
$query_usuario = "SELECT * FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);

$imagen_producto = $producto['imagen'] ? $producto['imagen'] : 'producto_default.png';
$rutaImagenProducto = "/Zava/img/productos/" . $imagen_producto;

$nickname_comercio = $usuario['nickname'];
$foto_usuario_comercio = $usuario['foto'] ? $usuario['foto'] : 'perfil.png';
$rutaImagenComercio = "/Zava/img/perfiles/" . $foto_usuario_comercio;

$nombre_categoria = $producto['categoria'] ?? 'Sin categoría';
?>

<div class="layout">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/menuLateral.php';?>
    <link rel="stylesheet" href="/Zava/css/cliente/mostrarProducto.css">
    <main>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';?>
        <section id="producto-contenedor" class="producto-principal" data-id-producto="<?php echo $producto['id_producto']; ?>">
            <article class="producto-contenedor">
                <div class="cont-imgs">
                    <div class="cont-img-principal">
                        <img src="<?php echo $rutaImagenProducto ?>" alt="Imagen del producto">
                        <?php if($producto['descuento'] == 1 && !empty($producto['porcentaje_descuento'])): ?>
                            <div class="cont-oferta-badge">
                                <p>%<?php echo $producto['porcentaje_descuento']; ?> off</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="cont-detalles">
                    <div class="cont-btns">
                        <div class="cont-fav-share">
<?php
$isFavorito = false;
if (isset($_SESSION['id'])) {
    $id_usuario = $_SESSION['id'];
    $query_fav = "SELECT * FROM favoritos_productos WHERE id_usuario = $id_usuario AND id_producto = $id_producto";
    $res_fav = mysqli_query($conexion, $query_fav);
    if ($res_fav && mysqli_num_rows($res_fav) > 0) {
        $isFavorito = true;
    }
}
?>
<button id="btn-favorito" class="btn-favorito" data-id="<?= $producto['id_producto'] ?? 0 ?>" data-tipo="producto" data-favorito="<?= $isFavorito ? '1' : '0' ?>">
    <span id="icon-fav">
    <?php if ($isFavorito): ?>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125"/></svg>
    <?php else: ?>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="none" stroke="currentColor" stroke-width="2" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125z"/></svg>
    <?php endif; ?>
    </span>
</button>

                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81c1.66 0 3-1.34 3-3s-1.34-3-3-3s-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65c0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92"/></svg>
                        </div>
                    </div>
                    <div class="nombre-estado">
                        <div class="nombre-producto">
                            <h2><?php echo $producto['nombre'];?></h2>
                        </div>
                        <?php if($producto['stock']>0){?>
                            <span class="estado-producto <?php echo $producto['stock'] > 0 ? 'in-stock' : 'out-of-stock';?>">En stock</span> 
                        <?php }; ?>
                    </div>
                                        <div class="cont-precios">
                        <?php 
                        $precio_final = $producto['precio'];
                        $precio_original = null;

                        if($producto['descuento'] == 1 && !empty($producto['porcentaje_descuento']) && $producto['porcentaje_descuento'] > 0){
                            $precio_original = $producto['precio'];
                            $descuento_valor = $precio_original * ($producto['porcentaje_descuento'] / 100);
                            $precio_final = $precio_original - $descuento_valor;
                        ?>
                            <span class="precio-oferta">$<?php echo number_format($precio_original, 2, ',', '.')?></span>
                        <?php } ?>
                        
                        <span class="precio-principal">$<?php echo number_format($precio_final, 2, ',', '.')?></span>
                    </div>
                    <p class="descripcion"><?php echo $producto['descripcion'];?></p>

                    <span class="peso">Peso: <?php echo $producto['peso'];?>g</span>
                    <div class="panel-btns">
                        <button class="quitar" type="button">-</button>
                        <span class="cantidad">1</span>
                        <button class="sumar" type="button">+</button>
                    </div>
                     <button class="btn-agregar-carrito">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path class="icon" fill="currentColor" fill-rule="evenodd" d="M10 2.25a1.75 1.75 0 0 0-1.582 1c-.684.006-1.216.037-1.692.223A3.25 3.25 0 0 0 5.3 4.563c-.367.493-.54 1.127-.776 1.998l-.047.17l-.513 2.964q-.277.191-.486.459c-.901 1.153-.472 2.87.386 6.301c.545 2.183.818 3.274 1.632 3.91C6.31 21 7.435 21 9.685 21h4.63c2.25 0 3.375 0 4.189-.635c.814-.636 1.086-1.727 1.632-3.91c.858-3.432 1.287-5.147.386-6.301a2.2 2.2 0 0 0-.487-.46l-.513-2.962l-.046-.17c-.237-.872-.41-1.506-.776-2a3.25 3.25 0 0 0-1.426-1.089c-.476-.186-1.009-.217-1.692-.222A1.75 1.75 0 0 0 14 2.25zm8.418 6.896l-.362-2.088c-.283-1.04-.386-1.367-.56-1.601a1.75 1.75 0 0 0-.768-.587c-.22-.086-.486-.111-1.148-.118A1.75 1.75 0 0 1 14 5.75h-4a1.75 1.75 0 0 1-1.58-.998c-.663.007-.928.032-1.148.118a1.75 1.75 0 0 0-.768.587c-.174.234-.277.56-.56 1.6l-.362 2.089C6.58 9 7.91 9 9.685 9h4.63c1.775 0 3.105 0 4.103.146M8 12.25a.75.75 0 0 1 .75.75v4a.75.75 0 0 1-1.5 0v-4a.75.75 0 0 1 .75-.75m8.75.75a.75.75 0 0 0-1.5 0v4a.75.75 0 0 0 1.5 0zM12 12.25a.75.75 0 0 1 .75.75v4a.75.75 0 0 1-1.5 0v-4a.75.75 0 0 1 .75-.75" clip-rule="evenodd"/></svg>
                        Agregar al carrito
                    </button> 
                </div>
                <div class="cont-comercio">
                        <div class="cont-img">
                            <img src="<?php echo $rutaImagenComercio ?>" alt="Foto de perfil">
                        </div>
                        <span class="fullname"><?php echo $nickname_comercio?></span>
                </div>
            </article>
        </section>
    </main>
</div>
<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/footer.php';
?>
<script src="/Zava/js/cliente/favoritos.js"></script>
<script src="/Zava/js/cliente/producto.js"></script>

   
