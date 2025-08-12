<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';

$rol = $_SESSION['id_rol'] ?? 1; // Rol por defecto: 1 (cliente o sin sesión)

switch ($rol) {
    case 3: // Admin
        include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/admin/index_admin.php';
        break;
    case 2: // Comercio
        include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/comercio/index_comercio.php';
        break;
    default: // Cliente (rol 1) o sin sesión
        include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/index_cliente.php';
        break;
}

?>
<script src="/Zava/js/cliente/busqueda.js"></script>
<?php 

include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/footer.php';
?>
