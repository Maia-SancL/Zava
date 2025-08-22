<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/cliente/conexion.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/funciones/tags.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;

    if ($_POST['accion'] == 'agregar' && $id_producto > 0) {
        if (isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto]++;
        } else {
            $_SESSION['carrito'][$id_producto] = 1;
        }
    }
    
    if ($_POST['accion'] == 'restar' && $id_producto > 0) {
        if (isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto]--;
            if ($_SESSION['carrito'][$id_producto] <= 0) {
                unset($_SESSION['carrito'][$id_producto]);
            }
        }
    }
    
    if ($_POST['accion'] == 'eliminar' && $id_producto > 0) {
        unset($_SESSION['carrito'][$id_producto]);
    }
    
    if ($_POST['accion'] == 'vaciar') {
        $_SESSION['carrito'] = [];
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$productos_info = [];
$total_carrito = 0;

if (!empty($_SESSION['carrito'])) {
    $ids_para_sql = implode(',', array_keys($_SESSION['carrito']));
    $sql = "SELECT id_producto, nombre, precio, imagen, peso FROM Productos WHERE id_producto IN ($ids_para_sql)";
    $resultado = mysqli_query($conexion, $sql);
    
    if ($resultado) {
        while ($producto = mysqli_fetch_assoc($resultado)) {
            $id = $producto['id_producto'];
            $cantidad = $_SESSION['carrito'][$id];
            $subtotal = $producto['precio'] * $cantidad;
            
            $productos_info[$id] = [
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'imagen' => $producto['imagen'],
                'peso' => $producto['peso'],
                'cantidad' => $cantidad,
                'subtotal' => $subtotal
            ];
            
            $total_carrito += $subtotal;
        }
    }
}
?>
<link rel="stylesheet" href="/Zava/css/cliente/carrito.css">
<div class="layout">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/menuLateral.php';?>
    <main>  
        <link rel="stylesheet" href="/Zava/css/general/index.css">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';?>
        
        <section class="titulo-icono">
            <section class="contenedor-tit-ico">
                <div class="cont icono">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2h3.27l.94 2H20a1 1 0 0 1 1 1c0 .17-.05.34-.12.5l-3.58 6.47c-.34.61-1 1.03-1.75 1.03H8.1l-.9 1.63l-.03.12a.25.25 0 0 0 .25.25H19v2H7a2 2 0 0 1-2-2c0-.35.09-.68.24-.96l1.36-2.45L3 4H1zm6 16c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2m9-7l2.78-5H6.14l2.36 5z"/></svg>
                </div>
                <div class="cont titulo">
                    <h2>Tu carrito</h2>
                </div>
            </section>
        </section>
        <section class="carrito-container">
            <section class="contenedor productos">
                <?php if (empty($productos_info)): ?>
                    <p>Tu carrito está vacío.</p>
                <?php else: ?>
                    <?php foreach ($productos_info as $id => $producto): 
                        $ruta_imagen = "/Zava/img/productos/" . ($producto['imagen'] ?? 'producto.jpg');
                    ?>
                        <div class="producto">
                            <div class="contenedor-producto eliminar">
                                <form method="post" class="form-eliminar">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                        <button type="submit" class="btn-eliminar">
                                            <svg class="equis"width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6.37978 5.99749L10.9965 1.37626C11.0779 1.29441 11.1236 1.18362 11.1236 1.06815C11.1235 0.952679 11.0776 0.841947 10.9961 0.760205C10.8328 0.597783 10.5453 0.596963 10.3804 0.761025L5.76495 5.38226L1.14782 0.759795C0.98376 0.597783 0.69624 0.598603 0.532998 0.760615C0.49248 0.800972 0.460404 0.848994 0.438644 0.90188C0.416884 0.954766 0.405875 1.01146 0.40626 1.06864C0.40626 1.18513 0.451377 1.29423 0.532998 1.37503L5.14972 5.99708L0.533408 10.6195C0.451945 10.7015 0.406325 10.8125 0.406556 10.928C0.406786 11.0436 0.452849 11.1544 0.534639 11.236C0.613799 11.3143 0.725771 11.3595 0.841435 11.3595H0.843896C0.959971 11.3591 1.07194 11.3135 1.14946 11.2344L5.76495 6.61314L10.3821 11.2356C10.4637 11.3168 10.5728 11.3619 10.6885 11.3619C10.7456 11.362 10.8022 11.3508 10.8551 11.3289C10.9079 11.3071 10.9559 11.275 10.9963 11.2346C11.0367 11.1942 11.0688 11.1462 11.0906 11.0933C11.1125 11.0405 11.1237 10.9839 11.1236 10.9267C11.1236 10.8107 11.0785 10.7012 10.9965 10.6204L6.37978 5.99749Z" fill="#1F1F1F"/>
                                            </svg>
                                        </button>
                                </form>
                            </div>
                            <div class="contenedor-producto imagen-nombre-peso">
                                <div class="cont-prod imagen">
                                <img src="<?php echo $ruta_imagen; ?>" class="imagen-producto">
                                </div>
                                <div class="cont-prod nombre-peso">
                                    <div class="informacion nombre-producto">
                                    <span><?php echo htmlspecialchars($producto['nombre']); ?></span>
                                    </div>
                                    <div class="informacion peso-producto">
                                    <span><?php echo htmlspecialchars($producto['peso']); ?>gr</span>
                                    </div>
                                </div>
                            </div>
                            <div class="contenedor-producto precio-cantidad-subtotal">
                                <div class="cont-prod precio">
                                    <span>$<?php echo htmlspecialchars($producto['precio']); ?></span>
                                </div>
                                <div class="cont-prod cantidad">
                                    <div class="cont-cantidad-btns">
                                        <form method="post">
                                            <input type="hidden" name="accion" value="restar">
                                            <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                            <button type="submit" class="btn-cantidad">-</button>
                                        </form>
                                        <span class="cantidad"><?php echo $producto['cantidad']; ?></span>
                                        <form method="post">
                                            <input type="hidden" name="accion" value="agregar">
                                            <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                            <button type="submit" class="btn-cantidad">+</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="cont-prod subtotal">
                                    <span>$<?php echo htmlspecialchars($producto['subtotal']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
            <section class="contenedor resumen">
                <h3>Resumen de pedido</h3>
                <div class="linea-detalle">
                    <span>Productos</span>
                    <span><?php echo count($productos_info); ?></span>
                </div>
                <?php foreach ($productos_info as $id => $producto): ?>
                    <div class="linea-detalle">
                        <span><?php echo htmlspecialchars($producto['nombre']); ?></span>
                        <span>$<?php echo number_format($producto['subtotal'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
                <hr>
                <div class="linea-total">
                    <span>Total</span>
                    <span>$<?php echo number_format($total_carrito, 2); ?></span>
                </div>
                <a href="/Zava/php/cliente/facturacion.php" class="btn-comprar">Procesar pago</a>
            </section>
        </section>
    </main>
