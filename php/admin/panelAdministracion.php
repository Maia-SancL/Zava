<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php');
$tabla_seleccionada= $_GET['tabla_seleccionada'] ?? 'productos';
?>
<div class="layout">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/menuLateral.php'; ?>
    <main>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php'; ?>
            <link rel="stylesheet" href="/Zava/css/admin/panelAdministracion.css">
            <div class="seleccionar-tipo-tabla">
                <a href="panelAdministracion.php?tabla_seleccionada=productos" class="tabla <?php echo ($tabla_seleccionada === 'productos') ? 'seleccionado' : ''; ?>">Productos</a>
                <a href="panelAdministracion.php?tabla_seleccionada=recetas" class="tabla <?php echo ($tabla_seleccionada === 'recetas') ? 'seleccionado' : ''; ?>">Recetas</a>
                <a href="panelAdministracion.php?tabla_seleccionada=usuarios" class="tabla  <?php echo ($tabla_seleccionada === 'usuarios') ? 'seleccionado' : ''; ?>">Usuarios</a>
                <a href="panelAdministracion.php?tabla_seleccionada=comentarios" class="tabla  <?php echo ($tabla_seleccionada === 'comentarios') ? 'seleccionado' : ''; ?>">Comentarios</a>
                <a href="panelAdministracion.php?tabla_seleccionada=usuarios_baneados" class="tabla  <?php echo ($tabla_seleccionada === 'usuarios_baneados') ? 'seleccionado' : ''; ?>">Usuarios Baneados</a>
            </div>
<?php

    if (isset($_GET['tabla_seleccionada'])) {
        $tabla_seleccionada = $_GET['tabla_seleccionada'];
          switch ($tabla_seleccionada) {
            case('productos'):
               
                $query = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, c.nombre as categoria 
                        FROM productos p
                        LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria AND c.tipo = 'producto'";
                $filters = [];
    
               
                if (isset($_GET['tipo-producto']) && $_GET['tipo-producto'] !== '') {
                    $filters[] = "c.nombre = '" . mysqli_real_escape_string($conexion, $_GET['tipo-producto']) . "'";
                }
    
                if (isset($_GET['precio']) && $_GET['precio'] !== '') {
                    $filters[] = "p.precio <= " . intval($_GET['precio']);
                }
    
                
                if (!empty($filters)) {
                    $query .= " WHERE " . implode(" AND ", $filters);
                }
    
                $result = mysqli_query($conexion, $query);
    
               
                $tipoProductoQuery = "SELECT DISTINCT nombre FROM Categorias WHERE tipo = 'producto' ORDER BY nombre ASC";
                $tipoProductoResult = mysqli_query($conexion, $tipoProductoQuery);
    
                $precioQuery = "SELECT DISTINCT precio FROM productos ORDER BY precio ASC";
                $precioResult = mysqli_query($conexion, $precioQuery);
    
                include 'administracion/productos.php';
                break;
            case('recetas'):
                $query = "SELECT r.id_receta, r.nombre, r.descripcion, u.nombre as autor FROM Recetas r JOIN Usuarios u ON r.id_usuario = u.id_usuario";
                $result = mysqli_query($conexion, $query);
                include 'administracion/recetas.php';
                break;
    
            case('usuarios'):
                $query = "SELECT id_usuario, nombre, apellido, nickname, correo, activo FROM Usuarios";
                $result = mysqli_query($conexion, $query);
                include 'administracion/usuarios.php';
                break;
    
            case('comentarios'):
                $query = "SELECT cr.id_comentario, cr.comentario, cr.fecha_comentario, u.nickname as usuario, r.nombre as receta FROM Comentarios_Recetas cr JOIN Usuarios u ON cr.id_usuario = u.id_usuario JOIN Recetas r ON cr.id_receta = r.id_receta";
                $result = mysqli_query($conexion, $query);
                include 'administracion/comentarios.php';
                break;
            
            case('usuarios_baneados'):
                $query = "SELECT ub.id_baneo, ub.motivo, ub.fecha_baneo, u.nickname, u.correo FROM Usuarios_Baneados ub JOIN Usuarios u ON ub.id_usuario = u.id_usuario";
                $result = mysqli_query($conexion, $query);
                include 'administracion/usuarios_baneados.php';
                break;
        
        }
    } else {
        
                $query = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, c.nombre as categoria 
                        FROM productos p
                        LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria AND c.tipo = 'producto'";
                $filters = [];
    
         
                if (isset($_GET['tipo-producto']) && $_GET['tipo-producto'] !== '') {
                    $filters[] = "c.nombre = '" . mysqli_real_escape_string($conexion, $_GET['tipo-producto']) . "'";
                }
    
                if (isset($_GET['precio']) && $_GET['precio'] !== '') {
                    $filters[] = "p.precio <= " . intval($_GET['precio']);
                }
    
       
                if (!empty($filters)) {
                    $query .= " WHERE " . implode(" AND ", $filters);
                }
    
                $result = mysqli_query($conexion, $query);
    
            
                $tipoProductoQuery = "SELECT DISTINCT nombre FROM Categorias WHERE tipo = 'producto' ORDER BY nombre ASC";
                $tipoProductoResult = mysqli_query($conexion, $tipoProductoQuery);
    
                $precioQuery = "SELECT DISTINCT precio FROM productos ORDER BY precio ASC";
                $precioResult = mysqli_query($conexion, $precioQuery);
    
                include 'administracion/productos.php';
        }
        ?>