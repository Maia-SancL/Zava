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
<link rel="stylesheet" href="/Zava/css/perfilPedidos.css">
<link rel="stylesheet" href="/Zava/css/editarPerfil.css">
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
                <a> <button class="btn-editar" id="openModalBtn"> Editar perfil
                    
                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="icon" d="M9.49993 15.1341L15.6633 8.9708C14.6264 8.53766 13.6847 7.90514 12.8916 7.10914C12.0952 6.31584 11.4624 5.37385 11.0291 4.33664L4.86577 10.5C4.38493 10.9808 4.1441 11.2216 3.93743 11.4866C3.69359 11.7995 3.48432 12.1379 3.31327 12.4958C3.1691 12.7991 3.0616 13.1225 2.8466 13.7675L1.7116 17.17C1.65936 17.3258 1.65161 17.493 1.68922 17.653C1.72683 17.8129 1.80831 17.9592 1.92449 18.0754C2.04068 18.1916 2.18697 18.2731 2.34692 18.3107C2.50688 18.3483 2.67415 18.3405 2.82993 18.2883L6.23243 17.1533C6.87827 16.9383 7.20077 16.8308 7.5041 16.6866C7.86355 16.5155 8.19993 16.3075 8.51327 16.0625C8.77827 15.8558 9.0191 15.615 9.49993 15.1341ZM17.3733 7.2608C17.9878 6.64628 18.333 5.8128 18.333 4.94372C18.333 4.07465 17.9878 3.24117 17.3733 2.62664C16.7587 2.01211 15.9253 1.66687 15.0562 1.66687C14.1871 1.66687 13.3536 2.01211 12.7391 2.62664L11.9999 3.3658L12.0316 3.4583C12.3958 4.50062 12.9919 5.44663 13.7749 6.22497C14.5765 7.03149 15.5557 7.63934 16.6341 7.99997L17.3733 7.2608Z" fill="#FBF6EE"/>
                    </svg>
                </button>
            </a>
            </div>
        </div>

        <div class="cont-nav">
                        <div data-href="/Zava/php/cliente/perfil/perfil.php" class="caja-nav">
                <a>Favoritos</a>
            </div>
                        <div data-href="/Zava/php/cliente/perfil/perfilHistorial.php" class="caja-nav">
                <a>Ultimo Visto</a>
            </div>
            <div class="caja-nav seleccionado">
                <a>Pedidos</a>
            </div>
                        <div data-href="/Zava/php/cliente/perfil/perfilOpiniones.php" class="caja-nav">
                <a>Opiniones</a>
            </div>
                        <div data-href="/Zava/php/cliente/perfil/perfilRecetas.php" class="caja-nav">
                <a>Mis Recetas</a>
            </div>
        </div>

        <div class="cont-pedidos">
            <h3>Mis Pedidos</h3>
            <?php
            
            if (!isset($_SESSION['id_usuario'])) {
                $_SESSION['id_usuario'] = 1; 
            }
            $id_usuario = $_SESSION['id_usuario'];

            $query_pedidos = "SELECT id_pedido, numero_pedido, fecha_pedido, total, estado FROM Pedidos WHERE id_usuario = $id_usuario ORDER BY fecha_pedido DESC";
            $resultado_pedidos = mysqli_query($conexion, $query_pedidos);

            if (mysqli_num_rows($resultado_pedidos) > 0) {
                echo '<div class="lista-pedidos">';
                while ($pedido = mysqli_fetch_assoc($resultado_pedidos)) {
                    $id_pedido = $pedido['id_pedido'];
                    $estado_clase = 'estado-' . strtolower(htmlspecialchars($pedido['estado']));
                    
                    echo '<div class="item-pedido-contenedor">';
                    echo '  <div class="item-pedido">';
                    echo '    <div class="info-pedido">';
                    echo '        <p><strong>Pedido #:</strong> ' . htmlspecialchars($pedido['numero_pedido']) . '</p>';
                    echo '        <p><strong>Fecha:</strong> ' . date('d/m/Y', strtotime($pedido['fecha_pedido'])) . '</p>';
                    echo '        <p><strong>Total:</strong> $' . number_format($pedido['total'], 2, ',', '.') . '</p>';
                    echo '    </div>';
                    echo '    <div class="estado-pedido ' . $estado_clase . '">' . htmlspecialchars($pedido['estado']) . '</div>';
                    echo '    <button class="btn-detalles" data-id="' . $id_pedido . '">Ver detalles</button>';
                    echo '  </div>';

                    echo '  <div id="detalles-' . $id_pedido . '" class="detalles-pedido" style="display:none;">';
                    $query_detalles = "SELECT dp.cantidad, dp.precio_unitario, p.nombre FROM Detalle_Pedido dp JOIN Productos p ON dp.id_producto = p.id_producto WHERE dp.id_pedido = $id_pedido";
                    $resultado_detalles = mysqli_query($conexion, $query_detalles);

                    if (mysqli_num_rows($resultado_detalles) > 0) {
                        echo '<ul>';
                        while ($detalle = mysqli_fetch_assoc($resultado_detalles)) {
                            echo '<li>' . htmlspecialchars($detalle['nombre']) . ' (x' . $detalle['cantidad'] . ') - $' . number_format($detalle['precio_unitario'], 2, ',', '.') . '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p>No se encontraron productos para este pedido.</p>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<div class="sin-pedidos">Aún no has realizado ningún pedido.</div>';
            }
            ?>
        </div>
    </main>

        <!-- El Modal -->
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
<script src="/Zava/js/cliente/pedidos.js"></script>