<?php

// Obtener la URL solicitada
$url = $_GET['url'] ?? '';
$url = trim($url, '/');
// echo '<pre style="background:#fff;color:#000;padding:8px;border:2px solid #444;">DEBUG url: ' . htmlspecialchars($url) . '</pre>';

if ($url === '') {
    // Mostrar home según el tipo de usuario
    $tipo = $_SESSION['tipo'] ?? 'cliente';
    if ($tipo === 'admin') {
        include 'administrador/views/inicioAdmin.php';
    } elseif ($tipo === 'comercio') {
        include 'comercio/views/inicioComercio.php';
    } else {
        include 'cliente/views/inicioCliente.php';
    }
    exit;
}

// Mapa de rutas 
$mapaRutas = [
    //Cliente
    'inicio'      => 'cliente/views/inicioCliente.php',
    'login'       => 'public/php/inicioSesion.php',
    'registro'    => 'public/php/diferenciacionRegistro.php',
    'catalogo'    => 'cliente/php/productos.php',
    'recetario'   => 'cliente/php/recetario.php',
    'receta'      => 'cliente/php/receta.php',
    'perfil'      => 'cliente/php/perfil.php',
    'carrito'     => 'cliente/php/carrito.php',
    'subir'       => 'cliente/php/crear.php',
    //Comercio
    'comercio'    => 'comercio/php/perfil.php',
    'comercio-productos' => 'comercio/php/comercio-productos.php',
    'comercio-agregar-producto' => 'comercio/php/comercio-agregar-producto.php',
    //Admin
    'admin'       => 'administrador/php/panelAdministracion.php',
    'admin-productos' => 'administrador/php/productos.php',
    'admin-usuarios'  => 'administrador/php/usuarios.php',
    // Agrega aca
];

if (isset($mapaRutas[$url]) && file_exists($mapaRutas[$url])) {
    include $mapaRutas[$url];
    exit;
}

// Si no existe la ruta, mostrar 404
http_response_code(404);
echo '404 - Página no encontrada';
exit;


