<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/conexion.php';

// Obtiene el ID del producto desde el formulario enviado por POST
$id_producto = $_GET['id_producto'];

// Consulta para obtener los datos del producto
$query_producto = "SELECT * FROM productos WHERE id_producto = $id_producto";
$resultado_producto = mysqli_query($conexion, $query_producto);
$producto = mysqli_fetch_array($resultado_producto);

// Consulta para obtener los datos del usuario que registró el producto
$id_usuario = $producto['id_usuario'];
$query_usuario = "SELECT * FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_array($resultado_usuario);

// Imagen del producto
$imagen_producto = $producto['imagen'] ? $producto['imagen'] : 'default.png';
$rutaImagenProducto= "../comercio/uploads/".$imagen_producto;

// Datos del comercio
$nickname_comercio = $usuario['nickname'];
$foto_usuario_comercio = $usuario['foto'] ? $usuario['foto'] : 'perfil.png';
$rutaImagenComercio= "uploads/".$foto_usuario_comercio;
?>

<div class="layout">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/menuLateral.php';?>
    <link rel="stylesheet" href="/Zava/css/mostrarProducto.css">
    <main>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';?>
        <section class="section-principal">
            <div class="cont-img">
                <img src="<?php echo $rutaImagenProducto?>" alt="<?php echo $producto['nombre'];?>">
            </div>
            <article class="informacion">
                <div class="informacion-principal">
                    <div class="cont-tipo-btns">
                        <span class="tipo-producto"><?php echo $producto['categoria'];?></span>
                        <div class="btns">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125"/></svg>

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
                        <span class="precio-principal">$<?php echo number_format($producto['precio'], 2, ',', '.');?></span> <!--EN CASO DE QUE ESTE EN OFERTA SE MOSTRARA ESE PRECIO (EL QUE TIENE DESCUENTO)-->
                        <span class="precio-oferta">$<?php echo number_format($producto['precio'], 2, ',','.');?></span> <!--SE MOSTRARA UNICAMENTE SI EL PRODUCTO ESTA EN OFERTA. EL CONTENIDO SERA EL PRECIO ORIGINAL-->
                    </div>
                    <p class="descripcion"><?php echo $producto['descripcion'];?></p>

                    <span class="peso">Peso: <?php echo $producto['peso'];?>g</span>
                    <div class="panel-btns"> <!--ESTE PANEL APARECE SOLO SI SE DA CLICK EL BTN AGREGAR AL CARRITO--->
                        <button class="quitar">-</button>
                        <span class="cantidad">0</span>
                        <button class="sumar">+</button>
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

   
