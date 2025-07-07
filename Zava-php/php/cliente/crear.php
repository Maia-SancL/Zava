<?php

session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/navegador.php';
include_once 'conexion.php';

$mensaje = '';  // Variable para mensajes de error o exito

if (!isset($_SESSION['id'])) {  // Verifica si el usuario ha iniciado sesion
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];  // Obtiene el ID del usuario de la sesion

if ($_SERVER['REQUEST_METHOD'] === 'POST') {    // Si el formulario fue enviado por POST
    if (
        isset($_POST['nombre']) &&
        isset($_POST['descripcion']) &&
        isset($_POST['ingredientes']) &&
        isset($_POST['pasos']) &&
        isset($_POST['tipo_comida'])
    ) {
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $ingredientes = isset($_POST['ingredientes']) ? mysqli_real_escape_string($conexion, implode(', ', $_POST['ingredientes'])) : '';
        $pasos = isset($_POST['pasos']) ? mysqli_real_escape_string($conexion, implode('. ', $_POST['pasos'])) : '';
        $tipo_comida = mysqli_real_escape_string($conexion, $_POST['tipo_comida']);
        $porciones = isset($_POST['porciones']) ? intval($_POST['porciones']) : 1;
        $tipo_dieta = isset($_POST['tipo_dieta']) ? mysqli_real_escape_string($conexion, $_POST['tipo_dieta']) : '';
        $tiempo = isset($_POST['tiempo']) ? intval($_POST['tiempo']) : 0;

        // Manejo de una sola imagen subida
        $imagen = '';
        if (!empty($_FILES['imagen']['name'])) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES['imagen']['name']);
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
                $imagen = $target_file;
            }
        }

        // Inserta la receta en la base de datos
        $query = "INSERT INTO recetas (id_usuario, nombre, descripcion, ingredientes, pasos, tipo_comida, porciones, tipo_dieta, tiempo_preparacion, imagen) 
        VALUES ($id_usuario, '$nombre', '$descripcion', '$ingredientes', '$pasos', '$tipo_comida', $porciones, '$tipo_dieta', SEC_TO_TIME($tiempo*60), '$imagen')";
        $resultado = mysqli_query($conexion, $query);

        if ($resultado) {
            $id_receta = mysqli_insert_id($conexion); // OBTIENE EL ID DE LA NUEVA RECETA
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
                Receta creada exitosamente.
            </div>
            <script>
                setTimeout(function(){
                    window.location.href = 'mostrarReceta.php?id=$id_receta';
                }, 1800);
            </script>
            ";
            exit; // Detiene el resto del HTML
        } else {
            $mensaje = "Error al crear la receta.";
        }
    }
}
?>
<link rel="stylesheet" href="/Zava-php/css/crear-receta.css">
<script src="/Zava-php/js/main.js"></script>
<main>
    <div class="crear-receta">
        <form action="crear.php" method="POST" enctype="multipart/form-data" class="form-receta">
            <div class="columna-izq">
                <label>Imágenes:</label>
                <div id="zona-imagenes" class="zona-imagenes">
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
                    <input type="file" id="input-imagenes" name="imagen" accept="image/*" style="display:none;">
                    <div id="preview-imagenes" class="preview-imagenes"></div>
                </div>
                <label>Ingredientes:</label>
                <div id="contenedor-ingredientes"></div>
                <button type="button" id="agregar-ingrediente" class="btn-secundario">+ Ingrediente</button>
            </div>
            <div class="columna-der">
                <div class="fila">
                    <div class="campo">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" required placeholder="Nombre de la receta">
                    </div>
                    <div class="campo">
                        <label for="tipo_comida">Categoría</label>
                        <select name="tipo_comida" id="tipo_comida" required>
                            <option value="sin especificar">Sin especificar</option>
                            <option value="desayuno">Desayuno</option>
                            <option value="almuerzo">Almuerzo</option>
                            <option value="merienda">Merienda</option>
                            <option value="cena">Cena</option>
                            <option value="snack">Snack</option>
                            <option value="evento especial">Evento especial</option>
                        </select>
                    </div>
                </div>
                <div class="fila">
                    <div class="campo">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" id="descripcion" required placeholder="Comparte un poco más acerca de este plato."></textarea>
                    </div>
                    <div class="campo">
                        <label for="porciones">Porciones</label>
                        <select name="porciones" id="porciones" required>
                            <option value="Sin especificar">Sin especificar</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="campo">
                        <label for="tipo_dieta">Tipo de dieta</label>
                        <select name="tipo_dieta" id="tipo_dieta" required>
                            <option value="sin especificar">Sin especificar</option>
                            <option value="vegetariana">Vegetariana</option>
                            <option value="vegana">Vegana</option>
                            <option value="sin lactosa">Sin lactosa</option>
                            <option value="otra">Otra</option>
                        </select>
                    </div>
                    <div class="campo">
                        <label for="tiempo">Tiempo</label>
                        <div class="tiempo-slider-container">
                            <input type="range" name="tiempo" id="tiempo" min="0" max="300" step="5" value="0">
                            <div class="tiempo-labels">
                                <span id="tiempo-valor">0 min</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pasos-section">
                    <label>Pasos</label>
                    <div id="contenedor-pasos"></div>
                    <button type="button" id="agregar-paso" class="btn-secundario">+ Pasos</button>
                </div>
                <div class="botones">
                    <button type="submit" class="btn-principal">Publicar</button>
                    <button type="reset" id="cancelar-boton" class="btn-secundario">Borrar</button>
                </div>
            </div>
        </form>
    </div>
</main>