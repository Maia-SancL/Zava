<?php

include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/navegador.php';

include_once('conexion.php');


// Obtener el ID de la receta por POST o GET
$id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);
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
$imagen = !empty($receta['imagen']) ? $receta['imagen'] : './css/recursos/galletitas-receta-2.jpg';
$tiempo = isset($receta['tiempo_preparacion']) ? $receta['tiempo_preparacion'] : '';
$tipo_dieta = isset($receta['tipo_dieta']) ? $receta['tipo_dieta'] : '';
$tipo_comida = isset($receta['tipo_comida']) ? $receta['tipo_comida'] : '';
$porciones = isset($receta['porciones']) ? intval($receta['porciones']) : 1;
$foto_usuario = !empty($usuario['foto']) ? $usuario['foto'] : './css/recursos/perfil.png';
$nickname = isset($usuario['nickname']) ? $usuario['nickname'] : '';
$fullname = trim(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido'] ?? ''));

// Formatea el tiempo (de TIME a minutos/horas)
function formatoTiempo($tiempo) {
    if (!$tiempo) return '';
    $partes = explode(':', $tiempo);
    $horas = intval($partes[0]);
    $minutos = intval($partes[1]);
    if ($horas > 0) {
        return $horas . ' hr' . ($horas > 1 ? 's' : '') . ($minutos > 0 ? " $minutos min" : '');
    } else {
        return $minutos . ' min';
    }
}

// --- AGREGAR A FAVORITOS ---
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
?>

<link rel="stylesheet" href="/Zava-php/css/mostrarReceta.css">
<article class="cont-imagenes-receta">
    <div class="cont-img-izquierda">
        <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Imagen receta">
    </div>
   
</article>

<div class="cont-titulo-receta">
    <h1 class="titulo-receta"><?php echo htmlspecialchars($nombre); ?></h1>
</div>

<section class="cont-tags-favorito">
    <article class="cont-tags">
        <div class="tag tiempo">
            <!-- Icono -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M17 3.34a10 10 0 1 1-14.995 8.984L2 12l.005-.324A10 10 0 0 1 17 3.34M12 6a1 1 0 0 0-.993.883L11 7v5l.009.131a1 1 0 0 0 .197.477l.087.1l3 3l.094.082a1 1 0 0 0 1.226 0l.094-.083l.083-.094a1 1 0 0 0 0-1.226l-.083-.094L13 11.585V7l-.007-.117A1 1 0 0 0 12 6"/></svg>
            <span class="lbl-tiempo"><?php echo formatoTiempo($tiempo); ?></span>
        </div>
        <div class="tag porciones">
            <!-- Icono -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"><circle cx="152" cy="184" r="72" fill="currentColor" class="icon"/><path class="icon" fill="currentColor" d="M234 296c-28.16-14.3-59.24-20-82-20c-44.58 0-136 27.34-136 82v42h150v-16.07c0-19 8-38.05 22-53.93c11.17-12.68 26.81-24.45 46-34"/><path class=icon fill="currentColor" d="M340 288c-52.07 0-156 32.16-156 96v48h312v-48c0-63.84-103.93-96-156-96"/><circle class="icon" cx="340" cy="168" r="88" fill="currentColor"/></svg>
            <span class="lbl-porciones"><?php echo $porciones; ?></span>
        </div>
        <div class="tag tipo">
            <!-- Icono -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12q0-2.025.838-3.937T5.163 4.7T8.7 2.5t4.5-.45q.375.05.575.313t.225.712q.05 1.6 1.188 2.738T17.9 7q.525.025.8.3t.3.85q.05 1.05.638 1.725t1.637 1.025q.35.125.538.363t.187.587q.05 2.075-.725 3.925t-2.125 3.238t-3.2 2.187T12 22m-1.5-12q.625 0 1.063-.437T12 8.5t-.437-1.062T10.5 7t-1.062.438T9 8.5t.438 1.063T10.5 10m-2 5q.625 0 1.063-.437T10 13.5t-.437-1.062T8.5 12t-1.062.438T7 13.5t.438 1.063T8.5 15m6.5 1q.425 0 .713-.288T16 15t-.288-.712T15 14t-.712.288T14 15t.288.713T15 16"/></svg>
            <span class="lbl-tipo"><?php echo ucfirst(htmlspecialchars($tipo_comida)); ?></span>
        </div>
        <div class="tag dieta">
            <span><?php echo ucfirst(htmlspecialchars($tipo_dieta)); ?></span>
        </div>
    </article>
    
</section>

<section class="info-princiapl">
    <div class="info-user">
        <div class="cont-img">
            <img src="<?php echo htmlspecialchars($foto_usuario); ?>" alt="Foto de perfil">
        </div>
        <div class="info">
            <div class="fullname-username">
                <span class="fullname"><?php echo htmlspecialchars($fullname); ?></span>
                <span class="username">@<?php echo htmlspecialchars($nickname); ?></span>
            </div>
          
        </div>
    </div>

    <article class="cont-descripcion">
        <p class="descripcion"><?php echo nl2br(htmlspecialchars($descripcion)); ?></p>
    </article>

    <article class="cont-ingredientes-pasos">
        <div class="cont-ingredientes">
            <div class="subtitulo">
                <!-- Icono -->
                <h4>Ingredientes</h4>
            </div>
            <ul>
                <?php foreach ($ingredientes as $ing): ?>
                    <?php if (trim($ing) !== ''): ?>
                        <li><?php echo htmlspecialchars(trim($ing)); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="cont-pasos">
            <div class="subtitulo">
                <!-- Icono -->
                <h4>Pasos</h4>
            </div>
            <ol>
                <?php foreach ($pasos as $paso): ?>
                    <?php if (trim($paso) !== ''): ?>
                        <li><?php echo htmlspecialchars(trim($paso)); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </div>
    </article>
</section>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/mostrarComentarios.php';
?>