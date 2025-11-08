<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/usuario/componente/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/conexion.php';

$mensaje = '';

// Verificar que el usuario tenga sesión iniciada
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Usuario') {
    header('Location: /Zava/login');
    exit;
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $tiempo = intval($_POST['tiempo']);
    
    // Obtener valores de los filtros
    $tipo_comida = isset($_POST['tipo_comida']) ? mysqli_real_escape_string($conexion, $_POST['tipo_comida']) : 'desayuno';
    $porciones = isset($_POST['porciones']) ? intval($_POST['porciones']) : 1;
    $tipo_dieta = isset($_POST['tipo_dieta']) ? mysqli_real_escape_string($conexion, $_POST['tipo_dieta']) : 'omnívora';
    
    // Convertir ingredientes y pasos a texto (temporalmente, hasta procesarlos)
    $ingredientes_texto = isset($_POST['ingredientes']) ? implode(', ', $_POST['ingredientes']) : '';
    $pasos_texto = isset($_POST['instruccion']) ? implode(' | ', $_POST['instruccion']) : '';
    
    // Insertar la receta (usando nombres de columnas correctos)
    $sql = "INSERT INTO Recetas (id_usuario, nombre, descripcion, ingredientes, pasos, tiempo_preparacion, porciones, tipo_comida, tipo_dieta, fecha_publicacion) 
            VALUES ($id_usuario, '$nombre', '$descripcion', '$ingredientes_texto', '$pasos_texto', $tiempo, $porciones, '$tipo_comida', '$tipo_dieta', NOW())";
    
    if (mysqli_query($conexion, $sql)) {
        $id_receta = mysqli_insert_id($conexion);
        
        // Procesar imágenes si se subieron
        if (isset($_FILES['imagenes']) && $_FILES['imagenes']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            $directorio_imagenes = $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/img/recetas/';
            if (!file_exists($directorio_imagenes)) {
                mkdir($directorio_imagenes, 0777, true);
            }
            
            $primera_imagen = true;
            foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['imagenes']['error'][$key] === UPLOAD_ERR_OK) {
                    $nombre_archivo = uniqid() . '_' . basename($_FILES['imagenes']['name'][$key]);
                    $ruta_destino = $directorio_imagenes . $nombre_archivo;
                    
                    if (move_uploaded_file($tmp_name, $ruta_destino)) {
                        // Guardar referencia de imagen en la base de datos (tabla correcta: Receta_Imagenes)
                        $es_principal = $primera_imagen ? 1 : 0;
                        $sql_img = "INSERT INTO Receta_Imagenes (id_receta, ruta_imagen, es_principal) VALUES ($id_receta, '$nombre_archivo', $es_principal)";
                        mysqli_query($conexion, $sql_img);
                        
                        // Actualizar imagen_principal en la tabla Recetas si es la primera
                        if ($primera_imagen) {
                            $sql_update = "UPDATE Recetas SET imagen_principal = '$nombre_archivo' WHERE id_receta = $id_receta";
                            mysqli_query($conexion, $sql_update);
                            $primera_imagen = false;
                        }
                    }
                }
            }
        }
        
        // Redirigir al detalle de la receta recién creada
        echo '<form id="postRedirect" action="/Zava/public/public/pantallaCarga.php" method="POST">
                <input type="hidden" name="mensaje" value="¡Receta publicada exitosamente!">
                <input type="hidden" name="destino" value="/Zava/receta?id=' . $id_receta . '">
              </form>
              <script>document.getElementById("postRedirect").submit();</script>';
        exit;
    } else {
        $mensaje = "Error al crear la receta: " . mysqli_error($conexion);
    }
}
?>
<link rel="stylesheet" href="/Zava/css/usuario/crear.css">
<?php if (!empty($mensaje)): ?>
    <div class="mensaje-error">
        <p><?php echo $mensaje; ?></p>
    </div>
<?php endif; ?>
<div class="contenedor-3">
    <form class="form-crear-receta" action="" method="POST" enctype="multipart/form-data">
        <div class="contenedor-superior">
            <div class="agregar-imagenes">
                <div class="contenedor-agregar-imagen borde-redondeado">
                    <img src="/css/imagenes/COMIDA-2.jpg" class="oculto">
                    <div class="contenedor-texto-icon">
                        <iconify-icon icon="mdi:image-add-outline"
                            class="icon color-primario icon-grande"></iconify-icon>
                        <p class="pequenio light color-primario">Agrega imagenes de tu plato ya listo!</p>
                    </div>
                    <input type="file" id="input-imagenes" name="imagenes[]" multiple accept="image/*"
                        class="input-imagene">
                </div>
                <div class="contenedor-preview-imagenes">
                    <div class="cont-img-preview borde-redondeado oculto">
                        <img src="">
                    </div>
                    <div class="cont-img-preview borde-redondeado oculto">
                        <img src="">
                    </div>
                </div>
            </div>
            <div class="contenedor-informacion-principal">
                <div class="contenedor-btns">
                    <button class="btn btn-primario" type="submit">Publicar</button>
                    <button class="btn btn-secundario">
                        <iconify-icon icon="ic:baseline-delete" class="icon color-primario icon-h6"></iconify-icon>
                        Borrar
                    </button>
                </div>
                <div class="contenedor-informacion">
                    <div class="contenedor-titulo-descripcion">
                        <div class="contenedor-input contenedor-titulo">
                            <span class="pequenio">Titulo</span>
                            <input type="text" name="nombre" id="nombre" required placeholder="Titulo de tu receta"
                                class="input-texto" maxlength="150">
                        </div>

                        <div class="contenedor-input contenedor-descripcion">
                            <span class="pequenio">Descripcion</span>
                            <textarea name="descripcion" id="descripcion" required
                                placeholder="Comparte un poco más acerca de este plato." wrap="hard"
                                class="input-texto descripcion" maxlength="500"></textarea>
                        </div>
                    </div>
                    <div class="contenedor-filtros">
                        <div class="caja-filtro contenedor-input">
                            <span class="pequenio">Categoria</span>
                            <div class="caja-filtro-principal borde-redondeado">
                                <p class="muy-pequenio medium">Categoria</p>
                                <iconify-icon icon="si:expand-more-line" class="icon icon-h6"></iconify-icon>
                            </div>
                            <div class="caja-filtro-contenido filtro oculto" name="tipo_comida" id="tipo_comida">
                                <p class="muy-pequenio light" value="">Desayuno</p>
                                <p class="muy-pequenio light" value="">Almuerzo</p>
                                <p class="muy-pequenio light" value="">Merienda</p>
                                <p class="muy-pequenio light" value="">Cena</p>
                                <p class="muy-pequenio light" value="">Postre</p>
                                <p class="muy-pequenio light" value="">Snack</p>
                                <p class="muy-pequenio light" value="">Bebida</p>
                            </div>
                        </div>

                        <div class="caja-filtro contenedor-input">
                            <span class="pequenio">Porciones</span>
                            <div class="caja-filtro-principal borde-redondeado">
                                <p class="muy-pequenio medium">Porciones</p>
                                <iconify-icon icon="si:expand-more-line" class="icon icon-h6"></iconify-icon>
                            </div>
                            <div class="caja-filtro-contenido filtro oculto" name="porciones" id="porciones">
                                <p class="muy-pequenio light" value="">1</p>
                                <p class="muy-pequenio light" value="">2</p>
                                <p class="muy-pequenio light" value="">3</p>
                                <p class="muy-pequenio light" value="">4</p>
                                <p class="muy-pequenio light" value="">5</p>
                                <p class="muy-pequenio light" value="">6</p>
                                <p class="muy-pequenio light" value="">7</p>
                                <p class="muy-pequenio light" value="">8</p>
                                <p class="muy-pequenio light" value="">9</p>
                                <p class="muy-pequenio light" value="">10</p>
                                <p class="muy-pequenio light" value=""> más de 10</p>
                            </div>
                        </div>

                        <div class="caja-filtro contenedor-input">
                            <span class="pequenio">Tipo de dieta</span>
                            <div class="caja-filtro-principal borde-redondeado">
                                <p class="muy-pequenio medium">Tipo dieta</p>
                                <iconify-icon icon="si:expand-more-line" class="icon icon-h6"></iconify-icon>
                            </div>
                            <div class="caja-filtro-contenido filtro oculto" name="tipo_dieta" id="tipo_dieta">
                                <p class="muy-pequenio light" value="">Vegetariana</p>
                                <p class="muy-pequenio light" value="">Vegana</p>
                                <p class="muy-pequenio light" value="">Sin gluten</p>

                            </div>
                        </div>

                        <div class="contenedor-timepo-slider contenedor-input">
                            <span class="pequenio">Tiempo</span>
                            <input type="range" name="tiempo" id="tiempo" min="0" max="480" step="5" value="0"
                                class="input-tiempo">
                            <div class="tiempo-labels">
                                <span id="tiempo-valor" class="muy-pequenio light">0 min</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="contenedor-inferior">
            <div class="contenedor-instrucciones contenedor-input">
                <span class="pequenio">Ingredientes</span>
                <div class="zona-instruccion ingredientes">
                    <div class="contenedor-instruccion">

                        <input class="input-texto" name="ingredientes[]" placeholder="Ingrediente" maxlength="50"
                            required>
                    </div>
                </div>
                <p class="pequenio light color-primario btn btn-agregar btn-agregar-ingrediente">+ Ingrediente</p>
            </div>

            <div class="contenedor-instrucciones contenedor-input">
                <span class="pequenio">Pasos</span>
                <div class="zona-instruccion pasos">
                    <div class="contenedor-instruccion">
                        <p class="pequenio numero-paso">1</p>
                        <textarea name="descripcion" id="descripcion" name="instruccion[]" required
                            placeholder="Describe el paso 1" wrap="hard" class="input-texto descripcion"
                            maxlength="500"></textarea>
                    </div>

                </div>

                <p class="pequenio light color-primario btn btn-agregar btn-agregar-paso">+ Paso</p>
            </div>

        </div>
    </form>
</div>
</div>
</main>
<script src="/Zava/js/receta.js"></script>
<script src="/Zava/js/desplegarSelect.js"></script>
</body>




</html>