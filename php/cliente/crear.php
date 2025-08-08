<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';
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
        $ingredientes = isset($_POST['ingredientes']) ? mysqli_real_escape_string($conexion, implode('; ', $_POST['ingredientes'])) : '';
        $pasos = isset($_POST['pasos']) ? mysqli_real_escape_string($conexion, implode('. ', $_POST['pasos'])) : '';
        $tipo_comida = mysqli_real_escape_string($conexion, $_POST['tipo_comida']);
        $porciones = isset($_POST['porciones']) ? intval($_POST['porciones']) : 1;
        $tipo_dieta = mysqli_real_escape_string($conexion, $_POST['tipo_dieta']);
        $tiempo = isset($_POST['tiempo']) ? intval($_POST['tiempo']) : 0;
        $dificultad = mysqli_real_escape_string($conexion, $_POST['dificultad']);
        $id_categoria = intval($_POST['categoria']);

        // Guardar imágenes
        $imagenes_guardadas = [];
        if (!empty($_FILES['imagenes']['name'][0])) {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/Zava/imagenes/recetas/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            foreach ($_FILES['imagenes']['name'] as $key => $name) {
                if ($_FILES['imagenes']['error'][$key] == 0) {
                    $nombre_archivo = time() . '_' . basename($name);
                    $ruta_completa = $target_dir . $nombre_archivo;
                    if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$key], $ruta_completa)) {
                        $imagenes_guardadas[] = $nombre_archivo;
                    }
                }
            }
        }

        $imagen_principal = !empty($imagenes_guardadas) ? $imagenes_guardadas[0] : 'receta_default.png';

        // Insertar receta principal
        $query = "INSERT INTO Recetas (id_usuario, nombre, descripcion, ingredientes, pasos, tiempo_preparacion, porciones, dificultad, tipo_comida, tipo_dieta, id_categoria, imagen_principal)
                  VALUES ('$id_usuario', '$nombre', '$descripcion', '$ingredientes', '$pasos', '$tiempo', '$porciones', '$dificultad', '$tipo_comida', '$tipo_dieta', '$id_categoria', '$imagen_principal')";
        
        if (mysqli_query($conexion, $query)) {
            $id_receta = mysqli_insert_id($conexion);

            // Insertar imágenes secundarias
            if (count($imagenes_guardadas) > 1) {
                for ($i = 1; $i < count($imagenes_guardadas); $i++) {
                    $imagen_secundaria = $imagenes_guardadas[$i];
                    $query_img = "INSERT INTO Receta_Imagenes (id_receta, url_imagen) VALUES ('$id_receta', '$imagen_secundaria')";
                    mysqli_query($conexion, $query_img);
                }
            }
            
            // Enviar respuesta JSON de éxito
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'id_receta' => $id_receta]);

        } else {
            // Enviar respuesta JSON de error
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
        }
        exit; // Detener la ejecución para no renderizar el HTML
        $resultado = mysqli_query($conexion, $query);

        if ($resultado) {
            $id_receta = mysqli_insert_id($conexion); // OBTIENE EL ID DE LA NUEVA RECETA
            
            // Guardar imágenes adicionales en la tabla Receta_Imagenes
            if (!empty($imagenes_subidas)) {
                foreach ($imagenes_subidas as $index => $nombre_imagen) {
                    $es_principal = ($index === 0) ? 1 : 0; // La primera imagen es principal
                    $query_imagen = "INSERT INTO Receta_Imagenes (id_receta, ruta_imagen, es_principal) VALUES ($id_receta, '$nombre_imagen', $es_principal)";
                    mysqli_query($conexion, $query_imagen);
                }
            }
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
            $mensaje = "Error al crear la receta: " . mysqli_error($conexion);
        }
    }
}
?>
<link rel="stylesheet" href="/Zava/css/crear-receta.css">
<script src="/Zava/js/main.js"></script>
<main>
    <div class="crear-receta">
        <form action="crear.php" method="POST" enctype="multipart/form-data" class="form-receta">
            <div class="columna-izq">
                <div id="zona-imagenes" class="zona-imagenes">
                    <div class="icono-imagen" id="icono-imagen">
                        <!-- SVG de icono de imagen -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 24 24">
                            <path class="icon" fill="currentColor" d="M18 15v3h-3v2h3v3h2v-3h3v-2h-3v-3zm-4.7 6H5c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v8.3c-.6-.2-1.3-.3-2-.3c-1.1 0-2.2.3-3.1.9L14.5 12L11 16.5l-2.5-3L5 18h8.1c-.1.3-.1.7-.1 1c0 .7.1 1.4.3 2"/>
                        </svg>
                    </div>
                    <p id="zona-texto">Agregar imágenes de tu plato (máximo 3)</p>
                    <input type="file" id="input-imagenes" name="imagenes[]" accept="image/*" style="display:none;">
                    <p class="error-message" id="errorMessage" style="color:red; display:none;">¡Solo puedes seleccionar hasta 3 imágenes!</p>
                    <div class="preview-container" id="previewContainer" style="display:none; grid-template-columns:240px 110px; gap:8px; margin-top:12px; height:240px;">
                        <div class="preview-slot" id="slot-0" style="width:240px;height:240px;grid-row:1/span 2;border:2px dashed #bbb;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;"></div>
                        <div class="preview-slot" id="slot-1" style="width:110px;height:110px;border:2px dashed #bbb;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;"></div>
                        <div class="preview-slot" id="slot-2" style="width:110px;height:110px;border:2px dashed #bbb;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;"></div>
                    </div>
                </div>
                <label class="subtitulo">Ingredientes</label>
                <div id="contenedor-ingredientes" class="cont-ingredientes"></div>
                    <div class="cont-btn">
                        <button type="button" id="agregar-ingrediente" class="btn-secundario">+ Ingrediente</button>
                    </div>
            </div>
            <div class="columna-der">
                <div class="botones">
                    <button type="submit" class="btn-principal">Publicar</button>
                    <button type="reset" id="cancelar-boton" class="btn-secundario"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zM9 17h2V8H9zm4 0h2V8h-2zM7 6v13z"/></svg> Borrar</button>
                </div>
                <div class="cont-superior">
                    <div class="fila">
                        <div class="campo">
                            <label for="nombre"  class="subtitulo" >Nombre</label>
                            <input type="text" name="nombre" id="nombre" required placeholder="Nombre de la receta">
                        </div>

                        <div class="campo">
                            <label for="descripcion" class="subtitulo">Descripción</label>
                            <textarea name="descripcion" id="descripcion" required placeholder="Comparte un poco más acerca de este plato." wrap="hard"></textarea>
                        </div>
                    </div>
                    <div class="fila etiquetas">

                        <div class="campo">
                            <label for="tipo_comida" class="subtitulo">Categoría</label>
                            <select name="tipo_comida" id="tipo_comida" required>
                                <option value="" disabled selected>Seleccioná una opción</option>
                                <option value="desayuno">Desayuno</option>
                                <option value="almuerzo">Almuerzo</option>
                                <option value="merienda">Merienda</option>
                                <option value="cena">Cena</option>
                                <option value="snack">Snack</option>
                                <option value="evento especial">Evento especial</option>
                            </select>
                        </div>

                        <div class="campo">
                            <label for="porciones" class="subtitulo">Porciones</label>
                            <select name="porciones" id="porciones" required>
                                <option value="" disabled selected>Seleccioná una opción</option>
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
                            <label for="tipo_dieta" class="subtitulo">Tipo de dieta</label>
                            <select name="tipo_dieta" id="tipo_dieta" required>
                                <option value="" disabled selected>Seleccioná una opción</option>
                                <option value="vegetariana">Vegetariana</option>
                                <option value="vegana">Vegana</option>
                                <option value="sin lactosa">Sin lactosa</option>
                                <option value="otra">Otra</option>
                            </select>
                        </div>
                        <div class="campo">
                            <label for="tiempo" class="subtitulo">Tiempo</label>
                            <div class="tiempo-slider-container">
                                <input type="range" name="tiempo" id="tiempo" min="0" max="300" step="5" value="0">
                                <div class="tiempo-labels">
                                    <span id="tiempo-valor">0 min</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pasos-section">
                    <label class="subtitulo">Pasos</label>
                    <div id="contenedor-pasos" class=cont-pasos></div>
                    <div class="cont-btn">
                        <button type="button" id="agregar-paso" class="btn-secundario">+ Paso</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</main>

<script src="/Zava/js/preview-imagenes-receta.js"></script>