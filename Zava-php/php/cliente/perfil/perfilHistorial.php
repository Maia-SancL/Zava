<?php

session_start();

include_once('./php/componentes/header.php'); 
include_once('./php/componentes/navegador.php'); 
include_once('./php/componentes/funciones/funciones.php'); 
include_once('conexion.php'); 

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];

// Consulta para obtener el historial del día actual
$query_hoy = "
    SELECT hv.*, 
           CASE hv.tipo_contenido
               WHEN 'receta' THEN r.nombre                              -- Si el tipo es 'receta', toma el nombre de la tabla 'recetas'
               WHEN 'restaurante' THEN res.nombre                       -- Si el tipo es 'restaurante', toma el nombre de la tabla 'restaurantes'
               WHEN 'producto' THEN p.nombre                            -- Si el tipo es 'producto', toma el nombre de la tabla 'productos'
           END AS nombre_contenido,
           CASE hv.tipo_contenido
               WHEN 'receta' THEN r.imagen                              -- Si el tipo es 'receta', toma la imagen de la tabla 'recetas'
               WHEN 'restaurante' THEN res.imagen                       -- Si el tipo es 'restaurante', toma la imagen de la tabla 'restaurantes'
               WHEN 'producto' THEN p.imagen                            -- Si el tipo es 'producto', toma la imagen de la tabla 'productos'
           END AS imagen_contenido
    FROM historial_vistas hv
    LEFT JOIN recetas r ON hv.tipo_contenido = 'receta' AND hv.id_contenido = r.id_receta                           -- Une con la tabla 'recetas' si el tipo es 'receta'
    LEFT JOIN restaurantes res ON hv.tipo_contenido = 'restaurante' AND hv.id_contenido = res.id_restaurante        -- Une con la tabla 'restaurantes' si el tipo es 'restaurante'
    LEFT JOIN productos p ON hv.tipo_contenido = 'producto' AND hv.id_contenido = p.id_producto                     -- Une con la tabla 'productos' si el tipo es 'producto'
    WHERE hv.id_usuario = $id_usuario AND DATE(hv.fecha_vista) = CURDATE()                                          -- Filtra por el usuario actual y la fecha de hoy
    ORDER BY hv.fecha_vista DESC                                                                                    -- Ordena por la fecha de vista en orden descendente
    LIMIT 5"; // Limita los resultados a los últimos 5
$resultado_hoy = mysqli_query($conexion, $query_hoy); // Ejecuta la consulta

// Consulta para obtener el historial del día anterior
$query_ayer = "
    SELECT hv.*, 
           CASE hv.tipo_contenido
               WHEN 'receta' THEN r.nombre                              -- Si el tipo es 'receta', toma el nombre de la tabla 'recetas'
               WHEN 'restaurante' THEN res.nombre                       -- Si el tipo es 'restaurante', toma el nombre de la tabla 'restaurantes'
               WHEN 'producto' THEN p.nombre                            -- Si el tipo es 'producto', toma el nombre de la tabla 'productos'
           END AS nombre_contenido,
           CASE hv.tipo_contenido
               WHEN 'receta' THEN r.imagen                              -- Si el tipo es 'receta', toma la imagen de la tabla 'recetas'
               WHEN 'restaurante' THEN res.imagen                       -- Si el tipo es 'restaurante', toma la imagen de la tabla 'restaurantes'
               WHEN 'producto' THEN p.imagen                            -- Si el tipo es 'producto', toma la imagen de la tabla 'productos'
           END AS imagen_contenido
    FROM historial_vistas hv
    LEFT JOIN recetas r ON hv.tipo_contenido = 'receta' AND hv.id_contenido = r.id_receta                           -- Une con la tabla 'recetas' si el tipo es 'receta'
    LEFT JOIN restaurantes res ON hv.tipo_contenido = 'restaurante' AND hv.id_contenido = res.id_restaurante        -- Une con la tabla 'restaurantes' si el tipo es 'restaurante'
    LEFT JOIN productos p ON hv.tipo_contenido = 'producto' AND hv.id_contenido = p.id_producto                     -- Une con la tabla 'productos' si el tipo es 'producto'
    WHERE hv.id_usuario = $id_usuario AND DATE(hv.fecha_vista) = CURDATE() - INTERVAL 1 DAY                         -- Filtra por el usuario actual y la fecha de ayer
    ORDER BY hv.fecha_vista DESC                                                                                    -- Ordena por la fecha de vista en orden descendente
    LIMIT 5";                                                                                                       // Limita los resultados a los últimos 5
$resultado_ayer = mysqli_query($conexion, $query_ayer); 

$query_usuario = "SELECT nombre, apellido, nickname, foto FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);

// Datos del usuario
$nombre = htmlspecialchars($usuario['nombre']);
$apellido = htmlspecialchars($usuario['apellido']);
$nickname = htmlspecialchars($usuario['nickname']);
$foto = $usuario['foto'] ? htmlspecialchars($usuario['foto']) : 'perfil.png';
?>

<!-- Información del usuario -->
    <div class="perfil-info">
        <h1 class="perfil-nombre"><?= $nombre ?> <?= $apellido ?></h1>
        <p class="perfil-nickname">@<?= $nickname ?></p>
        <p class="perfil-ubicacion">Ubicación</p>
    </div>
   <button class="btn-editar-perfil" onclick="location.href='profile-editar.php'">Editar perfil</button>
</div>
<!-- Opciones del perfil -->
<div class="perfil-opciones">
    <button class="btn-opcion" onclick="location.href='profile-inicio.php'">Favoritos</button>
    <button class="btn-opcion activo" onclick="location.href='profile-historial.php'">Último visto</button>
    <button class="btn-opcion" onclick="location.href='profile-pedidos.php'">Pedidos</button>
    <button class="btn-opcion">Comentarios y reseñas</button>
    <button class="btn-opcion">Mis recetas</button>
    <button class="btn-opcion">Mis restaurantes</button>
</div>

<div class="historial-titulo">
    <h1>Historial de Vistas</h1>
</div>
<!-- Historial del día actual -->
<section class="historial">
    <h2>Últimas 24 horas</h2>
    <div class="contenedor-historial">
        <?php while ($vista = mysqli_fetch_assoc($resultado_hoy)): ?>
            <div class="tarjeta-historial">
                <!-- Imagen del contenido -->
                <img src="<?= $vista['imagen_contenido'] ?? 'default.png' ?>" alt="Imagen del contenido">
                <div class="info-historial">
                    <!-- Nombre del contenido -->
                    <h3><?= htmlspecialchars($vista['nombre_contenido']) ?></h3>
                    <!-- Tipo de contenido -->
                    <p><strong>Tipo:</strong> <?= ucfirst($vista['tipo_contenido']) ?></p>
                    <!-- Fecha de la vista -->
                    <p><strong>Fecha:</strong> <?= $vista['fecha_vista'] ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>
<!-- Historial del día anterior -->
<section class="historial">
    <h2>Ayer</h2>
    <div class="contenedor-historial">
        <?php while ($vista = mysqli_fetch_assoc($resultado_ayer)): ?>
            <div class="tarjeta-historial">
                <!-- Imagen del contenido -->
                <img src="<?= $vista['imagen_contenido'] ?? 'default.png' ?>" alt="Imagen del contenido">
                <div class="info-historial">
                    <!-- Nombre del contenido -->
                    <h3><?= htmlspecialchars($vista['nombre_contenido']) ?></h3>
                    <!-- Tipo de contenido -->
                    <p><strong>Tipo:</strong> <?= ucfirst($vista['tipo_contenido']) ?></p>
                    <!-- Fecha de la vista -->
                    <p><strong>Fecha:</strong> <?= $vista['fecha_vista'] ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>
