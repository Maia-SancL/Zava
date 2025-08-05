<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';
include_once 'conexion.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];
$id_receta = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_receta <= 0) {
    echo "<p>Receta no encontrada.</p>";
    exit;
}

// Obtener datos actuales de la receta
$query = "SELECT * FROM recetas WHERE id_receta = $id_receta AND id_usuario = $id_usuario";
$resultado = mysqli_query($conexion, $query);
$receta = mysqli_fetch_assoc($resultado);

if (!$receta) {
    echo "<p>Receta no encontrada o no tienes permisos.</p>";
    exit;
}

// Procesar formulario de modificación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $ingredientes = isset($_POST['ingredientes']) ? mysqli_real_escape_string($conexion, implode(', ', $_POST['ingredientes'])) : '';
    $pasos = isset($_POST['pasos']) ? mysqli_real_escape_string($conexion, implode('. ', $_POST['pasos'])) : '';
    $tipo_comida = mysqli_real_escape_string($conexion, $_POST['tipo_comida']);
    $porciones = isset($_POST['porciones']) ? intval($_POST['porciones']) : 1;
    $tipo_dieta = isset($_POST['tipo_dieta']) ? mysqli_real_escape_string($conexion, $_POST['tipo_dieta']) : '';
    $tiempo = isset($_POST['tiempo']) ? intval($_POST['tiempo']) : 0;

    // Manejo de imagen
    $imagen = $receta['imagen'];
    if (!empty($_FILES['imagen']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES['imagen']['name']);
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
            // Elimina la imagen anterior si existe y es diferente
            if ($imagen && file_exists($imagen) && $imagen !== $target_file) {
                unlink($imagen);
            }
            $imagen = $target_file;
        }
    } elseif (isset($_POST['eliminar_imagen']) && $_POST['eliminar_imagen'] == '1') {
        if ($imagen && file_exists($imagen)) {
            unlink($imagen);
        }
        $imagen = '';
    }

    // Actualizar la receta
    $query = "UPDATE recetas SET 
        nombre='$nombre',
        descripcion='$descripcion',
        ingredientes='$ingredientes',
        pasos='$pasos',
        tipo_comida='$tipo_comida',
        porciones=$porciones,
        tipo_dieta='$tipo_dieta',
        tiempo_preparacion=SEC_TO_TIME($tiempo*60),
        imagen='$imagen'
        WHERE id_receta=$id_receta AND id_usuario=$id_usuario";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        echo "
        <div id='mensaje-exito' style='
            position:fixed;
            top:30px;right:30px;
            background:#e0ffe0;
            color:#27632a;
            border:1px solid #b2e2b2;
            border-radius:8px;
            padding:18px 28px;
            font-size:1.1rem;
            z-index:9999;
            box-shadow:0 2px 8px #0002;
            '>
            Receta modificada exitosamente.
        </div>
        <script>
            setTimeout(function(){
                window.location.href = '/Zava/index.php';
            }, 1800);
        </script>
        ";
        exit;
    } else {
        $mensaje = "Error al modificar la receta.";
    }
}

// Prepara los datos para mostrar en el formulario
$nombre = htmlspecialchars($receta['nombre']);
$descripcion = htmlspecialchars($receta['descripcion']);
$ingredientes = array_map('trim', explode(',', $receta['ingredientes']));
$pasos = array_map('trim', explode('.', $receta['pasos']));
$tipo_comida = $receta['tipo_comida'];
$porciones = $receta['porciones'];
$tipo_dieta = $receta['tipo_dieta'];
$tiempo = 0;
if (!empty($receta['tiempo_preparacion'])) {
    $partes = explode(':', $receta['tiempo_preparacion']);
    $tiempo = intval($partes[0]) * 60 + intval($partes[1]);
}
$imagen = $receta['imagen'];
?>

<link rel="stylesheet" href="/Zava/css/crear-receta.css">
<script src="/Zava/js/main.js"></script>
<main>
    <div class="crear-receta">
        <form action="modificarReceta.php?id=<?= $id_receta ?>" method="POST" enctype="multipart/form-data" class="form-receta">
            <div class="columna-izq">
                <label>Imágenes:</label>
                <div id="zona-imagenes" class="zona-imagenes">
                    <?php if ($imagen && file_exists($imagen)): ?>
                        <div id="preview-imagenes" class="preview-imagenes">
                            <img src="<?= htmlspecialchars($imagen) ?>" alt="Imagen actual" style="width:100%;height:100%;object-fit:cover;border-radius:16px;">
                        </div>
                        <label style="margin-top:0.5rem;display:block;">
                            <input type="checkbox" name="eliminar_imagen" value="1"> Eliminar imagen actual
                        </label>
                    <?php else: ?>
                        <div class="icono-imagen" id="icono-imagen">
                            <!-- SVG de icono de imagen -->
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none">
                                <rect x="4" y="4" width="16" height="16" rx="2" stroke="#621111" stroke-width="2"/>
                                <polyline points="8 16 12 12 16 16" stroke="#621111" stroke-width="2" fill="none"/>
                                <line x1="12" y1="12" x2="12" y2="16" stroke="#621111" stroke-width="2"/>
                                <line x1="12" y1="8" x2="12" y2="12" stroke="#621111" stroke-width="2"/>
                                <circle cx="12" cy="12" r="1.5" fill="#621111"/>
                            </svg>
                        </div>
                        <p>Agregar imagen de tu plato ya listo</p>
                    <?php endif; ?>
                    <input type="file" id="input-imagenes" name="imagen" accept="image/*" style="display:none;">
                </div>
                <label>Ingredientes:</label>
                <div id="contenedor-ingredientes">
                    <?php foreach ($ingredientes as $i => $ing): ?>
                        <?php if (trim($ing) !== ''): ?>
                            <div class="ingrediente-item">
                                <input type="text" name="ingredientes[]" value="<?= htmlspecialchars($ing) ?>" required>
                                <button type="button" class="eliminar-ingrediente">Eliminar</button>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="agregar-ingrediente" class="btn-secundario">+ Ingrediente</button>
            </div>
            <div class="columna-der">
                <div class="fila">
                    <div class="campo">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" required placeholder="Nombre de la receta" value="<?= $nombre ?>">
                    </div>
                    <div class="campo">
                        <label for="tipo_comida">Categoría</label>
                        <select name="tipo_comida" id="tipo_comida" required>
                            <option value="sin especificar" <?= $tipo_comida == 'sin especificar' ? 'selected' : '' ?>>Sin especificar</option>
                            <option value="desayuno" <?= $tipo_comida == 'desayuno' ? 'selected' : '' ?>>Desayuno</option>
                            <option value="almuerzo" <?= $tipo_comida == 'almuerzo' ? 'selected' : '' ?>>Almuerzo</option>
                            <option value="merienda" <?= $tipo_comida == 'merienda' ? 'selected' : '' ?>>Merienda</option>
                            <option value="cena" <?= $tipo_comida == 'cena' ? 'selected' : '' ?>>Cena</option>
                            <option value="snack" <?= $tipo_comida == 'snack' ? 'selected' : '' ?>>Snack</option>
                            <option value="evento especial" <?= $tipo_comida == 'evento especial' ? 'selected' : '' ?>>Evento especial</option>
                        </select>
                    </div>
                </div>
                <div class="fila">
                    <div class="campo">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" id="descripcion" required placeholder="Comparte un poco más acerca de este plato."><?= $descripcion ?></textarea>
                    </div>
                    <div class="campo">
                        <label for="porciones">Porciones</label>
                        <select name="porciones" id="porciones" required>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <option value="<?= $i ?>" <?= $porciones == $i ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label for="tipo_dieta">Tipo de dieta</label>
                        <select name="tipo_dieta" id="tipo_dieta" required>
                            <option value="sin especificar" <?= $tipo_dieta == 'sin especificar' ? 'selected' : '' ?>>Sin especificar</option>
                            <option value="vegetariana" <?= $tipo_dieta == 'vegetariana' ? 'selected' : '' ?>>Vegetariana</option>
                            <option value="vegana" <?= $tipo_dieta == 'vegana' ? 'selected' : '' ?>>Vegana</option>
                            <option value="sin lactosa" <?= $tipo_dieta == 'sin lactosa' ? 'selected' : '' ?>>Sin lactosa</option>
                            <option value="otra" <?= $tipo_dieta == 'otra' ? 'selected' : '' ?>>Otra</option>
                        </select>
                    </div>
                    <div class="campo">
                        <label for="tiempo">Tiempo</label>
                        <div class="tiempo-slider-container">
                            <input type="range" name="tiempo" id="tiempo" min="0" max="300" step="5" value="<?= $tiempo ?>">
                            <div class="tiempo-labels">
                                <span id="tiempo-valor"><?= $tiempo ?> min</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pasos-section">
                    <label>Pasos</label>
                    <div id="contenedor-pasos">
                        <?php foreach ($pasos as $i => $paso): ?>
                            <?php if (trim($paso) !== ''): ?>
                                <div class="paso-item">
                                    <input type="text" name="pasos[]" value="<?= htmlspecialchars($paso) ?>" required>
                                    <button type="button" class="eliminar-paso">Eliminar</button>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="agregar-paso" class="btn-secundario">+ Pasos</button>
                </div>
                <div class="botones">
                    <button type="submit" class="btn-principal">Guardar cambios</button>
                    <a href="mostrarReceta.php?id=<?= $id_receta ?>" class="btn-secundario" style="text-align:center;">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</main>
<script>
// Eliminar ingrediente/paso
document.querySelectorAll('.eliminar-ingrediente').forEach(btn => {
    btn.onclick = function() {
        this.parentElement.remove();
    }
});
document.querySelectorAll('.eliminar-paso').forEach(btn => {
    btn.onclick = function() {
        this.parentElement.remove();
    }
});
</script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/footer.php'; ?>