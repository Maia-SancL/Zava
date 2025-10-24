<?php
$query_historial = "
    (SELECT 
        h.fecha_vista, 
        'receta' as tipo, 
        r.id_receta as id, 
        r.nombre, 
        r.imagen, 
        u.nickname as autor
    FROM Historial_Vistas h
    JOIN recetas r ON h.id_contenido = r.id_receta AND h.tipo_contenido = 'receta'
    JOIN usuarios u ON r.id_usuario = u.id_usuario
    WHERE h.id_usuario = $id_usuario)
    UNION ALL
    (SELECT 
        h.fecha_vista, 
        'producto' as tipo, 
        p.id_producto as id, 
        p.nombre, 
        p.imagen, 
        c.nickname as comercio
    FROM Historial_Vistas h
    JOIN productos p ON h.id_contenido = p.id_producto AND h.tipo_contenido = 'producto'
    JOIN usuarios c ON p.id_usuario = c.id_usuario
    WHERE h.id_usuario = $id_usuario)
    ORDER BY fecha_vista DESC
    LIMIT 20;
";

$result_historial = mysqli_query($conexion, $query_historial);
?>

<section class="section-tabla-productos">
    <?php if (mysqli_num_rows($result_historial) > 0): ?>
        <?php while ($item = mysqli_fetch_assoc($result_historial)): ?>
            <article class="producto">
                <div class="informacion-principal">
                    <div class="informacion">
                        <?php if ($item['tipo'] === 'receta'): ?>
                            <img src="<?php echo BASE_URL; ?>public/img/recetas/<?= htmlspecialchars($item['imagen']) ?>" alt="Imagen de <?= htmlspecialchars($item['nombre']) ?>" class="imagen-item">
                            <span class="nombre"><?= htmlspecialchars($item['nombre']) ?></span>
                            <span class="vendedor">Autor: <?= htmlspecialchars($item['autor']) ?></span>
                        <?php else: ?>
                            <img src="<?php echo BASE_URL; ?>public/img/productos/<?= htmlspecialchars($item['imagen']) ?>" alt="Imagen de <?= htmlspecialchars($item['nombre']) ?>" class="imagen-item">
                            <span class="nombre"><?= htmlspecialchars($item['nombre']) ?></span>
                            <span class="vendedor">Vendido por: <?= htmlspecialchars($item['comercio']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="btns">
                        <a href="<?= BASE_URL ?><?= $item['tipo'] === 'receta' ? 'receta?id_receta=' . $item['id'] : 'producto?id_producto=' . $item['id'] ?>" class="btn-ver">Ver</a>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No has visto ningún producto o receta recientemente.</p>
    <?php endif; ?>
</section>
