<?php
$tipo_favorito = $_GET['tipo'] ?? 'recetas';
?>

<div class="seleccionar-tipo-tabla">
    <a href="<?php echo BASE_URL; ?>perfil/favoritos?tipo=recetas" class="tabla <?php echo ($tipo_favorito === 'recetas') ? 'seleccionado' : ''; ?>">Recetas</a>
    <a href="<?php echo BASE_URL; ?>perfil/favoritos?tipo=productos" class="tabla <?php echo ($tipo_favorito === 'productos') ? 'seleccionado' : ''; ?>">Productos</a>
</div>

<section class="section-tabla-productos">
    <?php
    if ($tipo_favorito === 'recetas') {
        $query = "SELECT r.id_receta, r.nombre, r.imagen, u.nickname as autor FROM Favoritos_Recetas rf JOIN Recetas r ON rf.id_receta = r.id_receta JOIN Usuarios u ON r.id_usuario = u.id_usuario WHERE rf.id_usuario = $id_usuario";
    } else {
        $query = "SELECT p.id_producto, p.nombre, p.imagen, c.nickname as comercio FROM Favoritos_Productos pf JOIN Productos p ON pf.id_producto = p.id_producto JOIN Usuarios c ON p.id_usuario = c.id_usuario WHERE pf.id_usuario = $id_usuario";
    }
    $result = mysqli_query($conexion, $query);

    if ($result->num_rows > 0):
        while ($item = $result->fetch_assoc()):
    ?>
            <article class="producto">
                <div class="informacion-principal">
                    <div class="informacion">
                        <?php if ($tipo_favorito === 'recetas'): ?>
                            <span class="id">#<?= htmlspecialchars($item['id_receta']) ?></span>
                            <img src="<?php echo BASE_URL; ?>public/img/recetas/<?= htmlspecialchars($item['imagen']) ?>" alt="Imagen de <?= htmlspecialchars($item['nombre']) ?>" class="imagen-item">
                            <span class="nombre"><?= htmlspecialchars($item['nombre']) ?></span>
                            <span class="vendedor">Autor: <?= htmlspecialchars($item['autor']) ?></span>
                        <?php else: ?>
                            <span class="id">#<?= htmlspecialchars($item['id_producto']) ?></span>
                            <img src="<?php echo BASE_URL; ?>public/img/productos/<?= htmlspecialchars($item['imagen']) ?>" alt="Imagen de <?= htmlspecialchars($item['nombre']) ?>" class="imagen-item">
                            <span class="nombre"><?= htmlspecialchars($item['nombre']) ?></span>
                            <span class="vendedor">Vendido por: <?= htmlspecialchars($item['comercio']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="btns">
                        <a href="<?= BASE_URL ?><?= $tipo_favorito === 'recetas' ? 'receta?id_receta=' . $item['id_receta'] : 'producto?id_producto=' . $item['id_producto'] ?>" class="btn-ver">Ver</a>
                    </div>
                </div>
            </article>
    <?php 
        endwhile;
    else:
        echo "<p>No tienes favoritos en esta sección.</p>";
    endif;
    ?>
</section>
