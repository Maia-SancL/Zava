<?php
session_start();
include_once 'c:/xampp/htdocs/Zava/administrador/php/conexion.php';

if (!isset($_SESSION['id'])) {
    header('Location: /Zava/login.php');
    exit();
}

$id_producto = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_usuario = $_SESSION['id'];


$sql = "SELECT p.*, c.nombre AS nombre_categoria FROM Productos p LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria WHERE p.id_producto = $id_producto AND p.id_usuario = $id_usuario";
$resultado = mysqli_query($conexion, $sql);
$producto = mysqli_fetch_assoc($resultado);

if (!$producto) {
    echo "Producto no encontrado o no tienes permiso para editarlo.";
    exit();
}


$sql_categorias = "SELECT * FROM Categorias";
$resultado_categorias = mysqli_query($conexion, $sql_categorias);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Producto</title>
    <link rel="stylesheet" href="/Zava/comercio/css/modificar-producto.css">
</head>
<body>
    <?php include_once 'c:/xampp/htdocs/Zava/public/componentes/php/header.php'; ?>

    <main>
        <section class="modificar-producto-container">
            <h2>Modificar Producto</h2>
            <form action="/Zava/comercio/php/funciones/actualizarProducto.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_producto" value="<?= htmlspecialchars($producto['id_producto']) ?>">

                <div class="form-group">
                    <label for="nombre">Nombre del Producto:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="4" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="precio">Precio:</label>
                    <input type="number" id="precio" name="precio" step="0.01" value="<?= htmlspecialchars($producto['precio']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock:</label>
                    <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($producto['stock']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select id="categoria" name="id_categoria">
                        <option value="">Sin categoría</option>
                        <?php while ($categoria = mysqli_fetch_assoc($resultado_categorias)) : ?>
                            <option value="<?= $categoria['id_categoria'] ?>" <?= ($producto['id_categoria'] == $categoria['id_categoria']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria['nombre'])
                                ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="imagen_principal">Imagen Principal:</label>
                    <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*">
                    <p>Imagen actual: <?= htmlspecialchars($producto['imagen_principal']) ?></p>
                    <img src="/Zava/public/img/productos/<?= htmlspecialchars($producto['imagen_principal']) ?>" alt="Imagen actual" width="100">
                </div>

                <div class="form-group">
                    <label for="activo">Estado:</label>
                    <select id="activo" name="activo">
                        <option value="1" <?= ($producto['activo'] == 1) ? 'selected' : '' ?>>Activa</option>
                        <option value="0" <?= ($producto['activo'] == 0) ? 'selected' : '' ?>>Pausada</option>
                    </select>
                </div>

                <button type="submit" class="btn-guardar">Guardar Cambios</button>
            </form>
        </section>
    </main>

    <?php include_once 'c:/xampp/htdocs/Zava/public/componentes/php/footer.php'; ?>
</body>
</html>
