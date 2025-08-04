<?php
include_once('conexion.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];

// Vistos de hoy
$q_hoy = "
    SELECT hv.*, 
        CASE hv.tipo_contenido
            WHEN 'receta' THEN r.nombre
            WHEN 'restaurante' THEN res.nombre
            WHEN 'producto' THEN p.nombre
        END AS nombre_contenido,
        CASE hv.tipo_contenido
            WHEN 'receta' THEN r.imagen
            WHEN 'restaurante' THEN res.imagen
            WHEN 'producto' THEN p.imagen
        END AS imagen_contenido
    FROM historial_vistas hv
    LEFT JOIN recetas r ON hv.tipo_contenido = 'receta' AND hv.id_contenido = r.id_receta
    LEFT JOIN restaurantes res ON hv.tipo_contenido = 'restaurante' AND hv.id_contenido = res.id_restaurante
    LEFT JOIN productos p ON hv.tipo_contenido = 'producto' AND hv.id_contenido = p.id_producto
    WHERE hv.id_usuario = $id_usuario
      AND DATE(hv.fecha_vista) = CURDATE()
    ORDER BY hv.fecha_vista DESC
    LIMIT 6
";
$res_hoy = mysqli_query($conexion, $q_hoy);

// Vistos de ayer
$q_ayer = "
    SELECT hv.*, 
        CASE hv.tipo_contenido
            WHEN 'receta' THEN r.nombre
            WHEN 'restaurante' THEN res.nombre
            WHEN 'producto' THEN p.nombre
        END AS nombre_contenido,
        CASE hv.tipo_contenido
            WHEN 'receta' THEN r.imagen
            WHEN 'restaurante' THEN res.imagen
            WHEN 'producto' THEN p.imagen
        END AS imagen_contenido
    FROM historial_vistas hv
    LEFT JOIN recetas r ON hv.tipo_contenido = 'receta' AND hv.id_contenido = r.id_receta
    LEFT JOIN restaurantes res ON hv.tipo_contenido = 'restaurante' AND hv.id_contenido = res.id_restaurante
    LEFT JOIN productos p ON hv.tipo_contenido = 'producto' AND hv.id_contenido = p.id_producto
    WHERE hv.id_usuario = $id_usuario
      AND DATE(hv.fecha_vista) = CURDATE() - INTERVAL 1 DAY
    ORDER BY hv.fecha_vista DESC
    LIMIT 6
";
$res_ayer = mysqli_query($conexion, $q_ayer);
?>

<h2>Últimos vistos de hoy</h2>
<section class="section-tabla-productos">
    <article class="producto guia">
        <div class="informacion-principal">
            <div class="informacion">
                <span class="lbl-informacion">Imagen</span>
                <span class="lbl-informacion">Nombre</span>
                <span class="lbl-informacion">Tipo</span>
                <span class="lbl-informacion">Fecha</span>
            </div>
        </div>
    </article>
    <?php while ($h = mysqli_fetch_assoc($res_hoy)): ?>
        <article class="producto">
            <div class="informacion-principal">
                <div class="informacion">
                    <span class="lbl-informacion">
                        <img src="<?= !empty($h['imagen_contenido']) ? '/Zava-php/php/cliente/' . $h['imagen_contenido'] : '/Zava-php/css/recursos/galletitas-receta-2.jpg' ?>" alt="Imagen" style="width:60px;height:40px;object-fit:cover;border-radius:8px;">
                    </span>
                    <span class="lbl-informacion"><?= htmlspecialchars($h['nombre_contenido']) ?></span>
                    <span class="lbl-informacion"><?= ucfirst($h['tipo_contenido']) ?></span>
                    <span class="lbl-informacion"><?= $h['fecha_vista'] ?></span>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
    <?php if (mysqli_num_rows($res_hoy) === 0): ?>
        <article class="producto">
            <div class="informacion-principal">
                <div class="informacion">
                    <span class="lbl-informacion">No hay vistas de hoy.</span>
                </div>
            </div>
        </article>
    <?php endif; ?>
</section>

<h2>Últimos vistos de ayer</h2>
<section class="section-tabla-productos">
    <article class="producto guia">
        <div class="informacion-principal">
            <div class="informacion">
                <span class="lbl-informacion">Imagen</span>
                <span class="lbl-informacion">Nombre</span>
                <span class="lbl-informacion">Tipo</span>
                <span class="lbl-informacion">Fecha</span>
            </div>
        </div>
    </article>
    <?php while ($h = mysqli_fetch_assoc($res_ayer)): ?>
        <article class="producto">
            <div class="informacion-principal">
                <div class="informacion">
                    <span class="lbl-informacion">
                        <img src="<?= !empty($h['imagen_contenido']) ? '/Zava-php/php/cliente/' . $h['imagen_contenido'] : '/Zava-php/css/recursos/galletitas-receta-2.jpg' ?>" alt="Imagen" style="width:60px;height:40px;object-fit:cover;border-radius:8px;">
                    </span>
                    <span class="lbl-informacion"><?= htmlspecialchars($h['nombre_contenido']) ?></span>
                    <span class="lbl-informacion"><?= ucfirst($h['tipo_contenido']) ?></span>
                    <span class="lbl-informacion"><?= $h['fecha_vista'] ?></span>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
    <?php if (mysqli_num_rows($res_ayer) === 0): ?>
        <article class="producto">
            <div class="informacion-principal">
                <div class="informacion">
                    <span class="lbl-informacion">No hay vistas de ayer.</span>
                </div>
            </div>
        </article>
    <?php endif; ?>
</section>