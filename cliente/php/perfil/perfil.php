<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/public/componentes/php/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/public/componentes/php/header.php');

if (!isset($_SESSION['id'])) {
    header("Location: " . BASE_URL . "login");
    exit;
}

$id_usuario = $_SESSION['id'];
$seccion_activa = $_GET['seccion'] ?? 'favoritos';

// Obtener datos del usuario
$query_usuario = "SELECT nombre, apellido, nickname, imagen FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);

$nombre = htmlspecialchars($usuario['nombre']);
$apellido = htmlspecialchars($usuario['apellido']);
$nickname = htmlspecialchars($usuario['nickname']);
$foto = $usuario['imagen'] ? htmlspecialchars($usuario['imagen']) : 'perfil.png';
$rutaImg = BASE_URL . "public/img/perfiles/" . $foto;

?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>cliente/css/perfil.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>cliente/css/editarPerfil.css">

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/componentes/php/navegador.php'; ?>

<main class="layout">
    <div class="cont-perfil">
        <div class="img-info">
            <img class="img-perfil" src="<?php echo $rutaImg; ?>" alt="Foto de perfil">
        </div>
        <div class="cont-info">
            <div class="nombre-info">
                <h4><?php echo $nombre . " " . $apellido; ?></h4>
                <h5><?php echo $nickname; ?></h5>
            </div>
            <button class="btn-editar" id="openModalBtn">Editar perfil
                <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="icon" d="M9.49993 15.1341L15.6633 8.9708C14.6264 8.53766 13.6847 7.90514 12.8916 7.10914C12.0952 6.31584 11.4624 5.37385 11.0291 4.33664L4.86577 10.5C4.38493 10.9808 4.1441 11.2216 3.93743 11.4866C3.69359 11.7995 3.48432 12.1379 3.31327 12.4958C3.1691 12.7991 3.0616 13.1225 2.8466 13.7675L1.7116 17.17C1.65936 17.3258 1.65161 17.493 1.68922 17.653C1.72683 17.8129 1.80831 17.9592 1.92449 18.0754C2.04068 18.1916 2.18697 18.2731 2.34692 18.3107C2.50688 18.3483 2.67415 18.3405 2.82993 18.2883L6.23243 17.1533C6.87827 16.9383 7.20077 16.8308 7.5041 16.6866C7.86355 16.5155 8.19993 16.3075 8.51327 16.0625C8.77827 15.8558 9.0191 15.615 9.49993 15.1341ZM17.3733 7.2608C17.9878 6.64628 18.333 5.8128 18.333 4.94372C18.333 4.07465 17.9878 3.24117 17.3733 2.62664C16.7587 2.01211 15.9253 1.66687 15.0562 1.66687C14.1871 1.66687 13.3536 2.01211 12.7391 2.62664L11.9999 3.3658L12.0316 3.4583C12.3958 4.50062 12.9919 5.44663 13.7749 6.22497C14.5765 7.03149 15.5557 7.63934 16.6341 7.99997L17.3733 7.2608Z" fill="#FBF6EE"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="cont-nav">
        <a href="<?php echo BASE_URL; ?>perfil/favoritos" class="caja-nav <?php echo ($seccion_activa === 'favoritos') ? 'seleccionado' : ''; ?>">Favoritos</a>
        <a href="<?php echo BASE_URL; ?>perfil/historial" class="caja-nav <?php echo ($seccion_activa === 'historial') ? 'seleccionado' : ''; ?>">Último Visto</a>
        <a href="<?php echo BASE_URL; ?>perfil/pedidos" class="caja-nav <?php echo ($seccion_activa === 'pedidos') ? 'seleccionado' : ''; ?>">Pedidos</a>
        <a href="<?php echo BASE_URL; ?>perfil/opiniones" class="caja-nav <?php echo ($seccion_activa === 'opiniones') ? 'seleccionado' : ''; ?>">Opiniones</a>
        <a href="<?php echo BASE_URL; ?>perfil/recetas" class="caja-nav <?php echo ($seccion_activa === 'recetas') ? 'seleccionado' : ''; ?>">Mis Recetas</a>
    </div>

    <div class="contenido-seccion">
        <?php
        switch ($seccion_activa) {
            case 'favoritos':
                include 'secciones/favoritos.php';
                break;
            case 'historial':
                include 'secciones/historial.php';
                break;
            case 'pedidos':
                include 'secciones/pedidos.php';
                break;
            case 'opiniones':
                include 'secciones/opiniones.php';
                break;
            case 'recetas':
                include 'secciones/mis_recetas.php';
                break;
            default:
                include 'secciones/favoritos.php';
                break;
        }
        ?>
    </div>
</main>

<!-- Modal de Editar Perfil -->
<div id="editProfileModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Editar Perfil</h2>
            <span class="close-btn">&times;</span>
        </div>
        <div class="modal-body">
            <form action="<?php echo BASE_URL; ?>cliente/actualizar-perfil" method="POST" enctype="multipart/form-data" class="edit-form">
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
             <hr style="margin: 20px 0; border-top: 1px solid #e0e0e0;">
            <div class="advanced-settings-section">
                <h4 style="color: var(--primario-100);">Configuración Avanzada</h4>
                <p style="font-size: 0.9em; color: #666;">Para cambiar tu correo electrónico, ingresa la nueva dirección. Se enviará un enlace de confirmación a tu correo actual para validar el cambio.</p>
                <form id="changeEmailForm" action="<?php echo BASE_URL; ?>cliente/iniciar-cambio-correo" method="POST">
                    <div class="form-group">
                        <label for="nuevo_correo">Nuevo Correo Electrónico:</label>
                        <input type="email" id="nuevo_correo" name="nuevo_correo" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn-guardar">Solicitar Cambio de Correo</button>
                    </div>
                </form>
            </div>
            <hr style="margin: 20px 0; border-top: 1px solid #e0e0e0;">
            <div class="delete-account-section">
                <h4 style="color: var(--primario-100);">Eliminar Cuenta</h4>
                <p style="font-size: 0.9em; color: #666;">Esta acción es permanente y no se puede deshacer. Se eliminarán todos tus datos (favoritos, pedidos, etc.).</p>
                <form id="deleteAccountRequestForm" action="<?php echo BASE_URL; ?>cliente/iniciar-eliminacion" method="POST">
                 <button type="submit" class="btn-eliminar">Eliminar mi cuenta</button>
            </form>
            <p style="font-size: 0.8em; color: #666; margin-top: 10px;">Se te enviará un correo electrónico para confirmar la eliminación.</p>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>cliente/js/modalPerfil.js"></script>
<script src="<?php echo BASE_URL; ?>cliente/js/perfil.js"></script>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/componentes/php/footer.php';
?>
