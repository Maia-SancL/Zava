<?php
session_start();
include 'conexion.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';

// Obtener productos del carrito
$productos_info = [];
$total_carrito = 0;
$cantidad_productos = 0;

// para pruebas, si no hay sesion, usamos un id de usuario por defecto.
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['id_usuario'] = 1; // id de usuario de prueba.
    // en un sistema real, aqui redirigiriamos al login:
    // header('Location: /Zava/login.php');
    // exit();
}

if (empty($_SESSION['carrito'])) {
    header('Location: /Zava/php/cliente/carrito.php');
    exit();
}

$ids_para_sql = implode(',', array_keys($_SESSION['carrito']));
$sql = "SELECT id_producto, nombre, precio, imagen FROM Productos WHERE id_producto IN ($ids_para_sql)";
$resultado = mysqli_query($conexion, $sql);
    
if ($resultado) {
    while ($producto = mysqli_fetch_assoc($resultado)) {
        $id = $producto['id_producto'];
        $cantidad = $_SESSION['carrito'][$id];
        $subtotal = $producto['precio'] * $cantidad;
        
        $productos_info[] = [
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'cantidad' => $cantidad,
            'subtotal' => $subtotal
        ];
        
        $total_carrito += $subtotal;
        $cantidad_productos += $cantidad;
            $id = $producto['id_producto'];
            $cantidad = $_SESSION['carrito'][$id];
            $subtotal = $producto['precio'] * $cantidad;
            
            $productos_info[] = [
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad,
                'subtotal' => $subtotal
            ];
            
            $total_carrito += $subtotal;
            $cantidad_productos += $cantidad;
        }
    }


$costo_envio = 2000; // Valor de envío fijo por ahora
$total_final = $total_carrito + $costo_envio;

?>
<link rel="stylesheet" href="/Zava/css/cliente/facturacion.css">
<div class="layout">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/menuLateral.php';?>
    <link rel="stylesheet" href="/Zava/css/mostrarReceta.css">
    <main>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';?>

        <div class="container">
            <div class="form-section">
                <form action="procesar_pago.php" method="POST" id="form-pago">
                    <div class="delivery-details">
                        <h2>Detalle de entrega</h2>
                        <div class="input-group">
                            <div class="input-field">
                                <label for="calle">Calle</label>
                                <input type="text" id="calle" name="calle">
                            </div>
                            <div class="input-field">
                                <label for="numero">Número</label>
                                <input type="text" id="numero" name="numero">
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-field">
                                <label for="departamento">Departamento</label>
                                <input type="text" id="departamento" name="departamento">
                            </div>
                            <div class="input-field">
                                <label for="piso">Piso</label>
                                <input type="text" id="piso" name="piso">
                            </div>
                        </div>
                        <div class="input-field">
                            <label for="instrucciones">Instrucciones de entrega</label>
                            <textarea id="instrucciones" name="instrucciones"></textarea>
                        </div>
                    </div>

                    <div class="payment-method-section" style="margin-top: 30px;">
                        <h2>Metodo de pago</h2>
                        <div class="payment-method">
                            <input type="radio" id="debito" name="metodo_pago" value="debito" checked>
                            <label for="debito">Tarjeta de débito</label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" id="credito" name="metodo_pago" value="credito">
                            <label for="credito">Tarjeta de crédito</label>
                        </div>
                        
                        <div class="card-details" style="margin-top: 20px;">
                            <div class="input-field" style="margin-bottom: 15px;">
                                <label for="numero-tarjeta">Número de la tarjeta</label>
                                <input type="text" id="numero-tarjeta" name="numero-tarjeta" placeholder="1111222233334444" maxlength="16" pattern="[0-9]{16}" title="Debe contener 16 dígitos." required>
                            </div>
                            <div class="input-field" style="margin-bottom: 15px;">
                                <label for="nombre_apellido">Nombre y apellido</label>
                                <small>Como figura en la tarjeta</small>
                                <input type="text" id="nombre_apellido" name="nombre_apellido">
                            </div>
                            <div class="card-details-row">
                                <div class="input-field">
                                    <label for="vencimiento">Fecha de vencimiento</label>
                                    <input type="text" id="vencimiento" name="vencimiento" placeholder="MM/AA" pattern="(0[1-9]|1[0-2])\/([0-9]{2})" title="Formato MM/AA" required>
                                </div>
                                <div class="input-field">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" pattern="[0-9]{3,4}" title="Debe contener 3 o 4 dígitos." required>
                                </div>
                            </div>
                            <div class="input-field" style="margin-top: 15px;">
                                <label for="dni">DNI del titular de la tarjeta</label>
                                <input type="text" id="dni" name="dni" maxlength="8" pattern="[0-9]{7,8}" title="Debe contener 8 dígitos." required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="summary-section">
                <h2>Resumen de pedido</h2>
                <div class="summary-item">
                    <span>Productos</span>
                    <span><?php echo $cantidad_productos; ?></span>
                </div>
                <?php foreach ($productos_info as $item): ?>
                    <div class="summary-item">
                        <span><?php echo htmlspecialchars($item['nombre']); ?> (x<?php echo $item['cantidad']; ?>)</span>
                        <span>$<?php echo number_format($item['subtotal'], 2, ',', '.'); ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="summary-item">
                    <span>Costo de envio</span>
                    <span>$<?php echo number_format($costo_envio, 2, ',', '.'); ?></span>
                </div>
                <div class="summary-item total">
                    <span>Total</span>
                    <span>$<?php echo number_format($total_final, 2, ',', '.'); ?></span>
                </div>
                <button type="submit" class="btn-submit" form="form-pago">Realizar pago</button>
            </div>
        </div>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/footer.php';?>
    </body>
