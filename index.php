<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';

$tipo_usuario = $_SESSION['tipo_usuario'] ?? '';

switch ($tipo_usuario) {
    case 'Admin':
        include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/admin/index_admin.php';
        break;
    case 'Comercio':
        include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/comercio/index_comercio.php';
        break;
    case 'Usuario':
    default:
        include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/index_cliente.php';
        break;
}
?>
<script src="/Zava/js/cliente/busqueda.js"></script>
<?php 

include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/footer.php';
?>
