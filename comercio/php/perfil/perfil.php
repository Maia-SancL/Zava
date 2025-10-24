<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/componentes/php/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/componentes/php/navegador.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];
$tabla = $_GET['tabla'] ?? 'inicio';

$query_usuario = "SELECT nombre, apellido, nickname, foto FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);
$nombre = htmlspecialchars($usuario['nombre']);
$apellido = htmlspecialchars($usuario['apellido']);
$nickname = htmlspecialchars($usuario['nickname']);
$foto = $usuario['foto'] ? htmlspecialchars($usuario['foto']) : 'perfil.png';
$rutaImg = BASE_URL . "public/img/perfiles/" . $foto;

// --- CACULOS DEL DASHBOARD ---

// Total de productos vendidos hoy
$query_hoy = "SELECT SUM(dp.cantidad) as total FROM Detalle_Pedido dp JOIN Pedidos p ON dp.id_pedido = p.id_pedido JOIN Productos pr ON dp.id_producto = pr.id_producto WHERE pr.id_usuario = $id_usuario AND DATE(p.fecha_pedido) = CURDATE()";
$res_hoy = mysqli_query($conexion, $query_hoy);
$ventas_hoy = mysqli_fetch_assoc($res_hoy)['total'] ?? 0;

// Total de productos vendidos esta semana
$query_semana = "SELECT SUM(dp.cantidad) as total FROM Detalle_Pedido dp JOIN Pedidos p ON dp.id_pedido = p.id_pedido JOIN Productos pr ON dp.id_producto = pr.id_producto WHERE pr.id_usuario = $id_usuario AND WEEK(p.fecha_pedido, 1) = WEEK(CURDATE(), 1) AND YEAR(p.fecha_pedido) = YEAR(CURDATE())";
$res_semana = mysqli_query($conexion, $query_semana);
$ventas_semana = mysqli_fetch_assoc($res_semana)['total'] ?? 0;

// Total de productos vendidos este mes
$query_mes = "SELECT SUM(dp.cantidad) as total FROM Detalle_Pedido dp JOIN Pedidos p ON dp.id_pedido = p.id_pedido JOIN Productos pr ON dp.id_producto = pr.id_producto WHERE pr.id_usuario = $id_usuario AND MONTH(p.fecha_pedido) = MONTH(CURDATE()) AND YEAR(p.fecha_pedido) = YEAR(CURDATE())";
$res_mes = mysqli_query($conexion, $query_mes);
$ventas_mes = mysqli_fetch_assoc($res_mes)['total'] ?? 0;

// Total de productos activos
$query_activos = "SELECT COUNT(*) as total FROM Productos WHERE id_usuario = $id_usuario AND activo = 1";
$res_activos = mysqli_query($conexion, $query_activos);
$productos_activos = mysqli_fetch_assoc($res_activos)['total'] ?? 0;

// Producto con stock bajo (ej: < 10)
$query_stock_bajo = "SELECT nombre FROM Productos WHERE id_usuario = $id_usuario AND stock < 10 AND activo = 1 ORDER BY stock ASC LIMIT 1";
$res_stock_bajo = mysqli_query($conexion, $query_stock_bajo);
$producto_stock_bajo = mysqli_fetch_assoc($res_stock_bajo)['nombre'] ?? 'Ninguno';

// Producto más vendido
$query_mas_vendido = "SELECT pr.nombre, SUM(dp.cantidad) as total_vendido FROM Detalle_Pedido dp JOIN Productos pr ON dp.id_producto = pr.id_producto WHERE pr.id_usuario = $id_usuario GROUP BY pr.id_producto, pr.nombre ORDER BY total_vendido DESC LIMIT 1";
$res_mas_vendido = mysqli_query($conexion, $query_mas_vendido);
$producto_mas_vendido = mysqli_fetch_assoc($res_mas_vendido)['nombre'] ?? 'Ninguno';

// Ingresos totales
$query_ingresos = "SELECT SUM(dp.subtotal) as total FROM Detalle_Pedido dp JOIN Pedidos p ON dp.id_pedido = p.id_pedido JOIN Productos pr ON dp.id_producto = pr.id_producto WHERE pr.id_usuario = $id_usuario";
$res_ingresos = mysqli_query($conexion, $query_ingresos);
$ingresos_totales = mysqli_fetch_assoc($res_ingresos)['total'] ?? 0;

?>
<link rel="stylesheet" href="/Zava/public/css/perfilComercio.css">
<link rel="stylesheet" href="/Zava/public/css/modalPerfilComercio.css">
<aside>
    <main>
        <div class="cont-perfil">
            <div class="img-info">
                <img class="img-perfil" src="/Zava/public/img/perfiles/<?php echo $foto;?>">
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

        <!-- <div class="cont-nav">
            <div class="caja-nav seleccionado">
                <a>Mis Productos</a>
            </div>
            <div class="caja-nav">
                <a>Mis Ventas</a>
            </div>
        </div> -->

        <div class="dashboard-container">
            <div class="dashboard-grid">
                <!-- Fila 1 -->
                <div class="dashboard-card card-ventas-hoy">
                    <h3>Ventas de Hoy</h3>
                    <p class="metric"><?php echo $ventas_hoy; ?></p>
                </div>
                <div class="dashboard-card card-ventas-semana">
                    <h3>Ventas (Semana)</h3>
                    <p class="metric"><?php echo $ventas_semana; ?></p>
                </div>
                <div class="dashboard-card card-ventas-mes">
                    <h3>Ventas (Mes)</h3>
                    <p class="metric"><?php echo $ventas_mes; ?></p>
                </div>
                <div class="dashboard-card card-mas-vendido">
                    <h3>Más Vendido</h3>
                    <p class="metric small"><?php echo htmlspecialchars($producto_mas_vendido); ?></p>
                </div>

                <!-- Fila 2 -->
                <div class="dashboard-card card-activos">
                    <h3>Productos Activos</h3>
                    <p class="metric"><?php echo $productos_activos; ?></p>
                </div>
                <div class="dashboard-card card-stock-bajo">
                    <h3>Con Stock Bajo</h3>
                    <p class="metric small"><?php echo htmlspecialchars($producto_stock_bajo); ?></p>
                </div>
                <div class="dashboard-card card-ingresos">
                    <h3>Ingresos Totales</h3>
                    <p class="metric metric-currency"><?php echo number_format($ingresos_totales, 2); ?></p>
                </div>

                 <!-- (Placeholders) -->
                <!-- <div class="dashboard-card card-placeholder-1">
                    <h3></h3>
                    <p class="metric small"> </p>
                </div>
                <div class="dashboard-card card-placeholder-2">
                    <h3></h3>
                    <p class="metric small"> </p>
                </div> -->
            </div>
        </div>
    </aside>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/componentes/php/footer.php';?>
<script src="/Zava/comercio/js/perfilNav.js"></script>
<script src="/Zava/comercio/js/modalPerfil.js"></script>

<!-- Modal para Editar Perfil -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Editar Perfil</h2>
        <form id="editProfileForm" action="<?php echo BASE_URL; ?>comercio/perfil/actualizar" method="POST" enctype="multipart/form-data">
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
                <label for="foto">Foto de Perfil:</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>
            <button type="submit" class="btn-guardar">Guardar Cambios</button>
        </form>

        <hr style="margin: 20px 0; border-top: 1px solid #e0e0e0;">
        <div class="advanced-settings-section">
            <h4 style="color: var(--primario-100);">Configuración Avanzada</h4>
            <p style="font-size: 0.9em; color: #666;">Para cambiar tu correo electrónico, ingresa la nueva dirección. Se enviará un enlace de confirmación a tu correo actual para validar el cambio.</p>
            <form id="changeEmailFormComercio" action="<?php echo BASE_URL; ?>comercio/perfil/iniciar-cambio-correo" method="POST">
                <div class="form-group">
                    <label for="nuevo_correo_comercio">Nuevo Correo Electrónico:</label>
                    <input type="email" id="nuevo_correo_comercio" name="nuevo_correo" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-guardar">Solicitar Cambio de Correo</button>
                </div>
            </form>
        </div>

        <div class="delete-section">
            <h3>Eliminar Cuenta</h3>
            <p>Esta acción es irreversible. Se eliminarán todos tus productos y datos asociados.</p>
            <form id="deleteAccountRequestForm" action="<?php echo BASE_URL; ?>comercio/perfil/iniciar-eliminacion" method="POST">
                 <button type="submit" class="btn-eliminar">Eliminar mi cuenta</button>
            </form>
            <p style="font-size: 0.8em; color: #666; margin-top: 10px;">Se te enviará un correo electrónico para confirmar la eliminación.</p>
        </div>
    </div>
</div>