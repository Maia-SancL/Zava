<?php
session_start();

// Obtener la URL solicitada
$url = $_GET['url'] ?? '';
$url = trim($url, '/');
// echo '<pre style="background:#fff;color:#000;padding:8px;border:2px solid #444;">DEBUG url: ' . htmlspecialchars($url) . '</pre>';

if ($url === '') {
    // Mostrar home según el tipo de usuario
    $tipo = $_SESSION['tipo'] ?? 'usuario';
    if ($tipo === 'admin') {
        include 'public/admin/inicioAdmin.php';
    } elseif ($tipo === 'comercio') {
        include 'public/comercio/inicioComercio.php';
    } elseif ($tipo === 'cliente') {
        include 'public/public/inicioUsuario.php';
    } else {
        include 'public/public/inicioUsuario.php';
    }
    exit;
}

// Mapa de rutas 
$mapaRutas = [
    //Cliente
    'inicio' => function() {
        // Mostrar inicio según el tipo de usuario
        if (isset($_SESSION['tipo_usuario'])) {
            switch($_SESSION['tipo_usuario']) {
                case 'Comercio':
                    include 'public/comercio/inicioComercio.php';
                    break;
                case 'Admin':
                    include 'public/admin/inicioAdmin.php';
                    break;
                case 'Usuario':
                default:
                    include 'public/public/inicioUsuario.php';
                    break;
            }
        } else {
            // Sin sesión, mostrar inicio público
            include 'public/public/inicioUsuario.php';
        }
    },
    //Public - Usuario
    'login' => 'public/public/iniciarSesion.php',
    'registro' => 'public/public/diferenciacionRegistro.php',
    'registrarse' => 'public/public/registrarse.php',
    'verificar' => 'public/public/mails/verificar.php',
    'reenviar-verificacion' => 'public/public/mails/solicitarReenvio.php',
    'restaurar-contrasena' => 'public/public/mails/restaurarContrasenia.php',
    'confirmar-eliminacion' => 'public/public/mails/confirmarEliminacion.php',
    'confirmar-cambio-correo' => 'public/public/mails/confirmarCambioCorreo.php',
    'cerrar-sesion' => 'public/public/cerrarSesion.php',
    'catalogo' => 'public/public/productos.php',
    'recetario' => 'public/public/recetario.php',
    'receta' => 'cliente/php/receta.php',
    'perfil' => 'cliente/php/perfil.php',
    'carrito' => 'cliente/php/carrito.php',
    'subir' => 'public/usuario/crear.php',
    //Comercio
    'comercio' => 'public/comercio/inicioComercio.php',
    'comercio-productos' => 'comercio/php/comercio-productos.php',
    'comercio-agregar-producto' => 'comercio/php/comercio-agregar-producto.php',
    //Admin
    'admin' => 'public/admin/inicioAdmin.php',
    'admin-productos' => 'public/admin/productos.php',
    'admin-usuarios' => 'public/admin/usuarios.php',
    // Agregar
];

if (isset($mapaRutas[$url])) {
    // Si es una función, ejecutarla
    if (is_callable($mapaRutas[$url])) {
        $mapaRutas[$url]();
        exit;
    }
    // Si es un archivo, incluirlo
    elseif (file_exists($mapaRutas[$url])) {
        include $mapaRutas[$url];
        exit;
    }
}

// Si no existe la ruta, mostrar 404
http_response_code(404);
echo '404 - Página no encontrada';
exit;


?>