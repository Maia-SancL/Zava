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
    $sql = "SELECT id_producto, nombre, precio, imagen FROM Productos WHERE id_producto IN ($ids_para_sql)";
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
        
        <section class="titulo">
            <div class="cont-icono">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z"/>
                </svg>
            </div>
            <div class="cont-titulo">
               <h2>Tu carrito</h2>
            </div>
        </section>

        <section class="contenedor-productos">
            <?php if (empty($productos_info)): ?>
                <div class="carrito-vacio">
                    <p>Tu carrito está vacío</p>
                </div>
            <?php else: ?>
                <?php foreach ($productos_info as $id => $producto): ?>
                    <div class="tarjeta-producto">
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                            <button type="submit" class="btn-eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/>
                                </svg>
                            </button>
                        </form>
                        
                        <div class="cont-producto">
                            <div class="producto-imagen">
                                <img src="/Zava/imagenes/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                            </div>
                            <div class="producto-info">
                                <div class="cont-nombre">
                                    <span><?php echo htmlspecialchars($producto['nombre']); ?></span>
                                </div>
                                <div class="cont-peso">
                                    <span>$<?php echo number_format($producto['precio'], 2); ?></span>
                                </div>
                            </div>
                            <div class="cont-cantidad">
                                <div class="cont-precio">
                                    <span>$<?php echo number_format($producto['precio'], 2); ?></span>
                                </div>
                                <div class="cont-cantidad-btns">
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="accion" value="restar">
                                        <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                        <button type="submit" class="btn-cantidad">-</button>
                                    </form>
                                    <span class="cantidad"><?php echo $producto['cantidad']; ?></span>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="accion" value="agregar">
                                        <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                        <button type="submit" class="btn-cantidad">+</button>
                                    </form>
                                </div>
                                <div class="cont-precio-final">
                                    <span>$<?php echo number_format($producto['subtotal'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section class="contenedor-precio">
            <div class="cont-total">
                <span>Total</span>
                <span>$<?php echo number_format($total_carrito, 2); ?></span>
            </div>
            <div class="cont-btns">
                <form method="post" style="display: inline;">
                    <input type="hidden" name="accion" value="vaciar">
                    <button type="submit" class="btn-vaciar">Vaciar carrito</button>
                </form>
                <a href="/Zava/php/cliente/facturacion.php" class="btn-comprar">Procesar pago</a>
            </div>
        </section>
         </main>

