<?php


include_once 'php/componentes/header.php';
include_once('conexion.php');

// Obtener el ID de la receta por POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo "<p>Receta no encontrada.</p>";
    exit;
}

// Obtener datos de la receta
$query = "SELECT * FROM recetas WHERE id_receta = $id";
$resultado = mysqli_query($conexion, $query);
$receta = mysqli_fetch_assoc($resultado);

if (!$receta) {
    echo "<p>Receta no encontrada.</p>";
    exit;
}

// Obtener datos del usuario
$id_usuario = $receta['id_usuario'];
$query_usuario = "SELECT nombre, apellido, nickname, foto FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);

// Datos principales
$nombre = $receta['nombre'];
$descripcion = $receta['descripcion'];
$ingredientes = isset($receta['ingredientes']) ? explode(',', $receta['ingredientes']) : [];
$pasos = isset($receta['pasos']) ? explode('.', $receta['pasos']) : [];
$imagenes = isset($receta['imagenes']) ? json_decode($receta['imagenes'], true) : [];
$tiempo = isset($receta['tiempo']) ? intval($receta['tiempo']) : 0;
$tipo_dieta = isset($receta['tipo_dieta']) ? $receta['tipo_dieta'] : '';
$tipo_comida = isset($receta['tipo_comida']) ? $receta['tipo_comida'] : '';
$porciones = isset($receta['porciones']) ? intval($receta['porciones']) : 1;
$foto_usuario = $usuario['foto'] ? $usuario['foto'] : 'perfil.png';

function formatoTiempo($min) {
    return $min < 60 ? "$min min" : (floor($min/60) . " hr" . (($min % 60) ? ' ' . ($min % 60) . ' min' : ''));
}

// --- AGREGAR A FAVORITOS ---
session_start();
$favorito_exito = '';
if (isset($_POST['agregar_favorito']) && isset($_SESSION['id'])) {
    $id_usuario = $_SESSION['id'];
    $id_receta = $id;
    // Evitar duplicados
    $existe = mysqli_query($conexion, "SELECT 1 FROM Favoritos_Recetas WHERE id_usuario=$id_usuario AND id_receta=$id_receta");
    if (!mysqli_fetch_assoc($existe)) {
        mysqli_query($conexion, "INSERT INTO Favoritos_Recetas (id_usuario, id_receta) VALUES ($id_usuario, $id_receta)");
        $favorito_exito = "¡Receta agregada a favoritos!";
    } else {
        $favorito_exito = "Ya está en tus favoritos.";
    }
}

// ...existing code...
?>

<!-- BOTONES DE ACCIÓN -->
<div style="margin: 16px 0; display: flex; gap: 1rem;">
    <form method="post" style="display:inline;">
        <input type="hidden" name="agregar_favorito" value="1">
        <button type="submit" style="background:#fff;border:1px solid #d2696f;padding:8px 18px;border-radius:8px;cursor:pointer;display:flex;align-items:center;gap:6px;">
            <span style="font-size:1.2em;">❤️</span> Favorito
        </button>
    </form>
    <button type="button" style="background:#fff;border:1px solid #d2696f;padding:8px 18px;border-radius:8px;cursor:pointer;display:flex;align-items:center;gap:6px;">
        <span style="font-size:1.2em;">🔗</span> Compartir
    </button>
</div>
<?php if ($favorito_exito): ?>
    <div style="color:#821717;font-weight:600;margin-bottom:10px;"><?= $favorito_exito ?></div>
<?php endif; ?>

<!-- ...resto del código de la receta... -->
<h1><?= htmlspecialchars($nombre) ?></h1>
<div>
    <img src="<?= htmlspecialchars($foto_usuario) ?>" alt="Perfil" style="width:40px;height:40px;border-radius:50%;vertical-align:middle;">
    <b><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?></b>
    <span>@<?= htmlspecialchars($usuario['nickname']) ?></span>
</div>
<p><?= htmlspecialchars($descripcion) ?></p>

<?php if (!empty($imagenes)): ?>
    <div>
    <?php foreach ($imagenes as $img): ?>
        <img src="<?= htmlspecialchars($img) ?>" alt="Imagen receta" style="max-width:200px;margin:8px 4px;">
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<!--TIEMPO-->
<p><b>Tiempo:</b> <?= formatoTiempo($tiempo) ?></p>

<!--PORCIONES-->
<p><b>Porciones:</b> <?= $porciones ?></p>

<!--TIPO DE DIETA-->
<p><b>Tipo de dieta:</b> <?= htmlspecialchars($tipo_dieta) ?></p>

<!--CATEGORIA-->
<p><b>Categoría:</b> <?= htmlspecialchars($tipo_comida) ?></p>

<!--INGREDIENTES-->
<h3>Ingredientes</h3>
<ul>
    <?php foreach ($ingredientes as $i): if (trim($i)): ?>
        <li><?= htmlspecialchars(trim($i)) ?></li>
    <?php endif; endforeach; ?>
</ul>

<!--PASOS-->
<h3>Pasos</h3>
<ol>
    <?php foreach ($pasos as $p): if (trim($p)): ?>
        <li><?= htmlspecialchars(trim($p)) ?></li>
    <?php endif; endforeach; ?>
</ol>
