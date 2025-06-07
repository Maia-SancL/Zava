<?php

session_start();
include_once 'php/componentes/header.php';
include_once 'php/componentes/navegador.php';
include_once 'conexion.php';


$mensaje = '';  // Variable para mensajes de error o éxito


if (!isset($_SESSION['id'])) {  // Verifica si el usuario ha iniciado sesión
    header("Location: login.php");
    exit;
}


$id_usuario = $_SESSION['id'];  // Obtiene el ID del usuario de la sesión


if ($_SERVER['REQUEST_METHOD'] === 'POST') {    // Si el formulario fue enviado por POST
    
    if (        // Verifica que los campos requeridos estén presentes
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

        
        $imagenes = []; // Manejo de imágenes subidas (soporta hasta 3 imágenes)
        for ($i = 1; $i <= 3; $i++) {
            if (!empty($_FILES["imagen$i"]['name'])) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["imagen$i"]['name']);
                if (move_uploaded_file($_FILES["imagen$i"]['tmp_name'], $target_file)) {
                    $imagenes[] = $target_file;
                }
            }
        }

        
        $imagenes_json = json_encode($imagenes);    // Convierte el array de imágenes a JSON para guardar en la base de datos


        // Inserta la receta en la base de datos
        $query = "INSERT INTO recetas (id_usuario, nombre, descripcion, ingredientes, pasos, tipo_comida, porciones, tipo_dieta, tiempo, imagenes) 
          VALUES ($id_usuario, '$nombre', '$descripcion', '$ingredientes', '$pasos', '$tipo_comida', $porciones, '$tipo_dieta', $tiempo, '$imagenes_json')";
        $resultado = mysqli_query($conexion, $query);


        // Verifica si la inserción fue exitosa
        if ($resultado) {
            $mensaje = "Receta creada exitosamente.";
        } else {
            $mensaje = "Error al crear la receta.";
        }
    } else {
        // Si faltan campos requeridos
        $mensaje = "Por favor, completa todos los campos.";
    }
}
?>


<div class="crear-receta">
    <form action="crear.php" method="POST" enctype="multipart/form-data" class="form-receta">
        <div class="columna-izq">
            <label>Imágenes:</label>
            <div id="zona-imagenes" class="zona-imagenes">
                <div class="icono-imagen">
                    <!-- SVG de icono de imagen -->
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none">
                        <rect x="4" y="4" width="16" height="16" rx="2" stroke="#621111" stroke-width="2"/>
                        <polyline points="8 16 12 12 16 16" stroke="#621111" stroke-width="2" fill="none"/>
                        <line x1="12" y1="12" x2="12" y2="16" stroke="#621111" stroke-width="2"/>
                        <line x1="12" y1="8" x2="12" y2="12" stroke="#621111" stroke-width="2"/>
                        <circle cx="12" cy="12" r="1.5" fill="#621111"/>
                    </svg>
                </div>
                <p>Agregar imagenes de tu plato ya listo</p>
                <input type="file" id="input-imagenes" name="imagenes[]" accept="image/*" multiple style="display:none;">
            </div>
            <div id="preview-imagenes" class="preview-imagenes"></div>
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
                        <option value="Sin especificar">Sin especificar</option>
                        <option value="Desayuno">Desayuno</option>
                        <option value="Almuerzo">Almuerzo</option>
                        <option value="Merienda">Merienda</option>
                        <option value="Cena">Cena</option>
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
                        <option value="Sin especificar">Sin especificar</option>
                        <option value="Vegetariana">Vegetariana</option>
                        <option value="Vegana">Vegana</option>
                        <option value="Otra">Otra</option>
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
