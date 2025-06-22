<?php

include_once('conexion.php');
session_start();

// Detectar el tipo y el id del contenido
$tipo = '';
$id = 0;

if (isset($id_receta)) {
    $tipo = 'receta';
    $id = intval($id_receta);
} elseif (isset($id_restaurante)) {
    $tipo = 'restaurante';
    $id = intval($id_restaurante);
}

// Obtener comentarios según el tipo
$comentarios = [];
if ($tipo && $id > 0) {
    if ($tipo === 'receta') {
        $sql = "SELECT c.*, u.nombre, u.nickname, u.foto 
                FROM comentarios_receta c
                JOIN usuarios u ON c.id_usuario = u.id_usuario
                WHERE c.id_receta = $id
                ORDER BY c.fecha_comentario DESC";
    } elseif ($tipo === 'restaurante') {
        $sql = "SELECT c.*, u.nombre, u.nickname, u.foto 
                FROM comentarios_restaurante c
                JOIN usuarios u ON c.id_usuario = u.id_usuario
                WHERE c.id_restaurante = $id
                ORDER BY c.fecha_comentario DESC";
    }
    $res = mysqli_query($conexion, $sql);
    while ($row = mysqli_fetch_assoc($res)) {
        $comentarios[] = $row;
    }
}

?>
<section class="cont-comentarios">
    <div class="subtitulo">
         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12a10 10 0 0 0 .951 4.262l-.93 4.537a1 1 0 0 0 1.18 1.18l4.537-.93c1.294.61 2.74.95 4.262.95c5.523 0 10-4.476 10-10c0-5.522-4.477-10-10-10" clip-rule="evenodd"/></svg>
        <h4>Comentarios</h4>
    </div>
    <?php if (empty($comentarios)): ?>
        <div class="comentario-user">
            <div class="info-user">
                <div class="cont-img">
                    <img src="./css/recursos/Almuerzo.jpg" alt="Foto de perfil">
                </div>
                <div class="info">
                    <div class="fullname-username">
                        <span class="fullname">Sin comentarios</span>
                        <span class="username"></span>
                    </div>
                    <div class="fecha-publicacion">
                        <span class="fecha"></span>
                    </div>
                </div>
            </div>
            <div class="cont-comentario">
                <p class="comentario">No hay comentarios aún.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($comentarios as $comentario): ?>
            <div class="comentario-user">
                <div class="info-user">
                    <div class="cont-img">
                        <img src="<?= htmlspecialchars($comentario['foto'] ?? './css/recursos/Almuerzo.jpg') ?>" alt="Foto de perfil">
                    </div>
                    <div class="info">
                        <div class="fullname-username">
                            <span class="fullname"><?= htmlspecialchars($comentario['nombre']) ?></span>
                            <span class="username">@<?= htmlspecialchars($comentario['nickname']) ?></span>
                        </div>
                        <div class="fecha-publicacion">
                            <span class="fecha"><?= date('d/m/Y', strtotime($comentario['fecha_comentario'])) ?></span>
                        </div>
                    </div>
                </div>
                <div class="cont-comentario">
                    <p class="comentario"><?= htmlspecialchars($comentario['comentario']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="cont-agregar-comentario">
        <div class="cont-img">
            <img src="./css/recursos/Bebidas.jpg" alt="Foto de perfil">
        </div>
        <div class="agregar-comentario">
            <input type="text" placeholder="Agregar un comentario">
            <button class="btn-enviar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="nav-icono"fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/></svg>
            </button>
        </div>
     </div>
</section>
