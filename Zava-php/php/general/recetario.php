<?php

session_start();
include_once 'php/componentes/header.php';
include_once 'php/componentes/top-menu.php';
include_once 'php/componentes/side-menu.php';
include_once 'conexion.php';

// Consulta para obtener recetas aleatorias
$query_recetas = "SELECT id_receta, nombre, descripcion, imagen, tipo_comida FROM recetas ORDER BY RAND() LIMIT 6";
$resultado_recetas = mysqli_query($conexion, $query_recetas);

// Consulta para obtener las categorías de recetas
$query_categorias = "SELECT DISTINCT tipo_comida FROM recetas";
$resultado_categorias = mysqli_query($conexion, $query_categorias);
?>

<div class="recetario">
    <h1>Recetario</h1>

    <!-- Categorías -->
    <section class="categorias">
        <h2>Categorías</h2>
        <ul>
            <?php while ($categoria = mysqli_fetch_assoc($resultado_categorias)): ?>
                <li>
                    <a href="recetario.php?categoria=<?= urlencode($categoria['tipo_comida']) ?>">
                        <?= htmlspecialchars($categoria['tipo_comida']) ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </section>

    <!-- Recetas aleatorias -->
    <section class="recetas">
        <h2>Recetas Aleatorias</h2>
        <div class="contenedor-recetas">
            <?php while ($receta = mysqli_fetch_assoc($resultado_recetas)): ?>
                <div class="tarjeta-receta">
                    <img src="<?= $receta['imagen'] ?? 'default-receta.png' ?>" alt="Imagen de la receta">
                    <div class="info-receta">
                        <h3><?= htmlspecialchars($receta['nombre']) ?></h3>
                        <p><?= htmlspecialchars($receta['descripcion']) ?></p>
                        <p><strong>Categoría:</strong> <?= htmlspecialchars($receta['tipo_comida']) ?></p>
                        <a href="mostrar-receta.php?id=<?= $receta['id_receta'] ?>">Ver receta</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</div>
            