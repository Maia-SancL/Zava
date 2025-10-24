<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';
include_once 'conexion.php';

$mensaje = '';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];


$id_receta = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_receta <= 0) {
    header("Location: perfil/perfilRecetas.php");
    exit;
}


$query_verificar = "SELECT * FROM Recetas WHERE id_receta = $id_receta AND id_usuario = $id_usuario";
$resultado_verificar = mysqli_query($conexion, $query_verificar);
$receta = mysqli_fetch_assoc($resultado_verificar);

if (!$receta) {
    header("Location: perfil/perfilRecetas.php");
    exit;
}


$nombre = htmlspecialchars($receta['nombre']);
$descripcion = htmlspecialchars($receta['descripcion']);
$ingredientes = array_filter(array_map('trim', explode(',', $receta['ingredientes'])));
$pasos = array_filter(array_map('trim', explode('.', $receta['pasos'])));
$tipo_comida = $receta['tipo_comida'];
$porciones = $receta['porciones'];
$tipo_dieta = $receta['tipo_dieta'];
$tiempo = intval($receta['tiempo_preparacion']);
$dificultad = $receta['dificultad'];
$id_categoria = $receta['id_categoria'];
$imagen_principal = $receta['imagen_principal'];


$imagenes_existentes = [];
if (!empty($receta['imagen_principal'])) {
    $imagenes_existentes[] = $receta['imagen_principal'];
}


$query_imagenes = "SELECT ruta_imagen FROM Receta_Imagenes WHERE id_receta = $id_receta";
$resultado_imagenes = mysqli_query($conexion, $query_imagenes);
while ($fila_imagen = mysqli_fetch_assoc($resultado_imagenes)) {
        $imagenes_existentes[] = $fila_imagen['ruta_imagen'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (
        !empty($_POST['nombre']) &&
        !empty($_POST['descripcion']) &&
        !empty($_POST['ingredientes']) &&
        !empty($_POST['pasos']) &&
        !empty($_POST['tipo_comida'])
    ) {
        
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $ingredientes = mysqli_real_escape_string($conexion, implode(', ', $_POST['ingredientes']));
        $pasos = mysqli_real_escape_string($conexion, implode('. ', $_POST['pasos']));
        $tipo_comida = mysqli_real_escape_string($conexion, $_POST['tipo_comida']);
        $porciones = intval($_POST['porciones']);
        $tipo_dieta = mysqli_real_escape_string($conexion, $_POST['tipo_dieta']);
        $tiempo = intval($_POST['tiempo']);
        $dificultad = mysqli_real_escape_string($conexion, $_POST['dificultad']);
        $id_categoria = intval($_POST['categoria']);

        $imagenes_finales = $imagenes_existentes;

        if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
            foreach ($imagenes_existentes as $imagen_vieja) {
                $ruta_imagen_vieja = $_SERVER['DOCUMENT_ROOT'] . '/Zava/imagenes/recetas/' . $imagen_vieja;
                if (file_exists($ruta_imagen_vieja)) {
                    unlink($ruta_imagen_vieja);
                }
            }

            $imagenes_guardadas = [];
            $directorio_destino = $_SERVER['DOCUMENT_ROOT'] . "/Zava/imagenes/recetas/";
            if (!is_dir($directorio_destino)) mkdir($directorio_destino, 0777, true);

            foreach ($_FILES['imagenes']['name'] as $key => $nombre_archivo_original) {
                if ($_FILES['imagenes']['error'][$key] == 0) {
                    $nombre_archivo = time() . '_' . uniqid() . '_' . basename($nombre_archivo_original);
                    $archivo_destino = $directorio_destino . $nombre_archivo;
                    if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$key], $archivo_destino)) {
                        $imagenes_guardadas[] = $nombre_archivo;
                    }
                }
            }
            $imagenes_finales = $imagenes_guardadas;
        
        } else {
            $imagenes_a_mantener = [];
            for ($i = 0; $i < count($imagenes_existentes); $i++) {
                if (isset($_POST['eliminar_imagen_' . $i]) && $_POST['eliminar_imagen_' . $i] == '1') {
                    $ruta_a_borrar = $_SERVER['DOCUMENT_ROOT'] . '/Zava/imagenes/recetas/' . $imagenes_existentes[$i];
                    if (file_exists($ruta_a_borrar)) {
                        unlink($ruta_a_borrar);
                    }
                } else {
                    $imagenes_a_mantener[] = $imagenes_existentes[$i];
                }
            }
            $imagenes_finales = $imagenes_a_mantener;
        }

        $imagen_principal_final = !empty($imagenes_finales) ? array_shift($imagenes_finales) : '';
        $imagenes_secundarias = $imagenes_finales;

        $sql_actualizar = "UPDATE Recetas SET 
            nombre = '$nombre', 
            descripcion = '$descripcion', 
            ingredientes = '$ingredientes', 
            pasos = '$pasos', 
            tipo_comida = '$tipo_comida',
            porciones = $porciones,
            tipo_dieta = '$tipo_dieta',
            tiempo_preparacion = $tiempo,
            dificultad = '$dificultad',
            id_categoria = $id_categoria,
            imagen_principal = '$imagen_principal_final'
            WHERE id_receta = $id_receta AND id_usuario = $id_usuario";

        if (mysqli_query($conexion, $sql_actualizar)) {
            mysqli_query($conexion, "DELETE FROM Receta_Imagenes WHERE id_receta = $id_receta");
            if (!empty($imagenes_secundarias)) {
                foreach ($imagenes_secundarias as $imagen_sec) {
                    $imagen_sec_esc = mysqli_real_escape_string($conexion, $imagen_sec);
                    $sql_insertar_secundaria = "INSERT INTO Receta_Imagenes (id_receta, ruta_imagen) VALUES ($id_receta, '$imagen_sec_esc')";
                    mysqli_query($conexion, $sql_insertar_secundaria);
                }
            }

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'id_receta' => $id_receta]);
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Error al actualizar la receta: ' . mysqli_error($conexion)]);
            exit;
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Faltan datos requeridos en el formulario.']);
        exit;
    }
}
?>
<link rel="stylesheet" href="/Zava/css/crear-receta.css">

<main>
    <div class="crear-receta">
        <form action="modificarReceta.php?id=<?php echo $id_receta; ?>" method="POST" enctype="multipart/form-data" class="form-receta">
            <div class="columna-izq">
                <div id="zona-imagenes" class="zona-imagenes">
                    <?php if (empty($imagenes_existentes)): ?>
                        <div class="icono-imagen" id="icono-imagen">
                            <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 24 24">
                                <path class="icon" fill="currentColor" d="M18 15v3h-3v2h3v3h2v-3h3v-2h-3v-3zm-4.7 6H5c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v8.3c-.6-.2-1.3-.3-2-.3c-1.1 0-2.2.3-3.1.9L14.5 12L11 16.5l-2.5-3L5 18h8.1c-.1.3-.1.7-.1 1c0 .7.1 1.4.3 2"/>
                            </svg>
                        </div>
                        <p id="zona-texto">Agregar imágenes de tu plato (máximo 3)</p>
                    <?php else: ?>
                        <p id="zona-texto">Cambiar imágenes de tu plato (máximo 3)</p>
                    <?php endif; ?>
                    
                    <input type="file" id="input-imagenes" name="imagenes[]" accept="image/*" style="display:none;">
                    <p class="error-message" id="errorMessage" style="color:red; display:none;">¡Solo puedes seleccionar hasta 3 imágenes!</p>
                    
                    <div class="preview-container" id="previewContainer" style="<?php echo !empty($imagenes_existentes) ? 'display:grid;' : 'display:none;'; ?> grid-template-columns:240px 110px; gap:8px; margin-top:12px; height:240px;">
                        <div class="preview-slot" id="slot-0" style="width:240px;height:240px;grid-row:1/span 2;border:2px dashed #bbb;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;">
                            <?php if (isset($imagenes_existentes[0])): ?>
                                <img src="/Zava/imagenes/recetas/<?php echo htmlspecialchars($imagenes_existentes[0]); ?>" 
                                     alt="Imagen principal" 
                                     style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                                <button type="button" class="remove-image" data-slot="0" style="position:absolute;top:5px;right:5px;background:rgba(255,0,0,0.8);color:white;border:none;border-radius:50%;width:25px;height:25px;cursor:pointer;font-size:12px;">×</button>
                            <?php else: ?>
                                <span style="color:#999;font-size:14px;">Imagen principal</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="preview-slot" id="slot-1" style="width:110px;height:110px;border:2px dashed #bbb;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;">
                            <?php if (isset($imagenes_existentes[1])): ?>
                                <img src="/Zava/imagenes/recetas/<?php echo htmlspecialchars($imagenes_existentes[1]); ?>" 
                                     alt="Segunda imagen" 
                                     style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                                <button type="button" class="remove-image" data-slot="1" style="position:absolute;top:5px;right:5px;background:rgba(255,0,0,0.8);color:white;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:10px;">×</button>
                            <?php else: ?>
                                <span style="color:#999;font-size:12px;">+</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="preview-slot" id="slot-2" style="width:110px;height:110px;border:2px dashed #bbb;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;">
                            <?php if (isset($imagenes_existentes[2])): ?>
                                <img src="/Zava/imagenes/recetas/<?php echo htmlspecialchars($imagenes_existentes[2]); ?>" 
                                     alt="Tercera imagen" 
                                     style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                                <button type="button" class="remove-image" data-slot="2" style="position:absolute;top:5px;right:5px;background:rgba(255,0,0,0.8);color:white;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:10px;">×</button>
                            <?php else: ?>
                                <span style="color:#999;font-size:12px;">+</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <label class="subtitulo">Ingredientes</label>
                <div id="contenedor-ingredientes" class="cont-ingredientes">
                    <?php foreach($ingredientes as $ingrediente): ?>
                        <div class="ingrediente-item">
                            <input type="text" name="ingredientes[]" value="<?php echo htmlspecialchars($ingrediente); ?>" required>
                            <button type="button" class="eliminar-ingrediente btn-eliminar"><svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zm3-4q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17"/></svg></button>
                        </div>
                    <?php endforeach; ?>
                    <?php if(empty($ingredientes)): ?>
                        <div class="ingrediente-item">
                            <input type="text" name="ingredientes[]" value="" required>
                            <button type="button" class="eliminar-ingrediente btn-eliminar"><svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zm3-4q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17"/></svg></button>
                        </div>
                    <?php endif; ?>
                </div>
                    <div class="cont-btn">
                        <button type="button" id="agregar-ingrediente" class="btn-secundario">+ Ingrediente</button>
                    </div>
            </div>
            <div class="columna-der">
                <div class="botones">
                    <button type="submit" class="btn-principal">Guardar Cambios</button>
                    <button type="reset" id="cancelar-boton" class="btn-secundario"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zM9 17h2V8H9zm4 0h2V8h-2zM7 6v13z"/></svg> Borrar</button>
                </div>
                <div class="cont-superior">
                    <div class="fila">
                        <div class="campo">
                            <label for="nombre"  class="subtitulo" >Nombre</label>
                            <input type="text" name="nombre" id="nombre" required placeholder="Nombre de la receta" value="<?php echo $nombre; ?>">
                        </div>

                        <div class="campo">
                            <label for="descripcion" class="subtitulo">Descripción</label>
                            <textarea name="descripcion" id="descripcion" required placeholder="Comparte un poco más acerca de este plato." wrap="hard"><?php echo $descripcion; ?></textarea>
                        </div>
                    </div>
                    <div class="fila etiquetas">

                        <div class="campo">
                            <label for="tipo_comida" class="subtitulo">Categoría</label>
                            <select name="tipo_comida" id="tipo_comida" required>
                                <option value="" disabled>Seleccioná una opción</option>
                                <option value="desayuno" <?php if($tipo_comida=='desayuno') echo 'selected'; ?>>Desayuno</option>
                                <option value="almuerzo" <?php if($tipo_comida=='almuerzo') echo 'selected'; ?>>Almuerzo</option>
                                <option value="merienda" <?php if($tipo_comida=='merienda') echo 'selected'; ?>>Merienda</option>
                                <option value="cena" <?php if($tipo_comida=='cena') echo 'selected'; ?>>Cena</option>
                                <option value="snack" <?php if($tipo_comida=='snack') echo 'selected'; ?>>Snack</option>
                                <option value="evento especial" <?php if($tipo_comida=='evento especial') echo 'selected'; ?>>Evento especial</option>
                            </select>
                        </div>

                        <div class="campo">
                            <label for="porciones" class="subtitulo">Porciones</label>
                            <select name="porciones" id="porciones" required>
                                <option value="" disabled>Seleccioná una opción</option>
                                <?php for($i=1; $i<=10; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php if($porciones==$i) echo 'selected'; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="campo">
                            <label for="tipo_dieta" class="subtitulo">Tipo de dieta</label>
                            <select name="tipo_dieta" id="tipo_dieta" required>
                                <option value="" disabled>Seleccioná una opción</option>
                                <option value="vegetariana" <?php if($tipo_dieta=='vegetariana') echo 'selected'; ?>>Vegetariana</option>
                                <option value="vegana" <?php if($tipo_dieta=='vegana') echo 'selected'; ?>>Vegana</option>
                                <option value="sin lactosa" <?php if($tipo_dieta=='sin lactosa') echo 'selected'; ?>>Sin lactosa</option>
                                <option value="otra" <?php if($tipo_dieta=='otra') echo 'selected'; ?>>Otra</option>
                            </select>
                        </div>
                        <div class="campo">
                            <label for="tiempo" class="subtitulo">Tiempo</label>
                            <div class="tiempo-slider-container">
                                <input type="range" name="tiempo" id="tiempo" min="0" max="300" step="5" value="<?php echo $tiempo; ?>">
                                <div class="tiempo-labels">
                                    <span id="tiempo-valor"><?php echo $tiempo; ?> min</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pasos-section">
                    <label class="subtitulo">Pasos</label>
                    <div id="contenedor-pasos" class="cont-pasos">
                        <?php foreach($pasos as $index => $paso): ?>
                            <div class="paso-item" bis_skin_checked="1" style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span class="numero-paso" style="display: inline-block; width: 32px; height: 32px; border-radius: 50%; background: rgb(237, 229, 218); color: rgb(125, 90, 74); text-align: center; line-height: 32px; margin-right: 12px; font-weight: bold;"><?php echo ($index + 1); ?></span>
                                <input type="text" name="pasos[]" value="<?php echo htmlspecialchars($paso); ?>" required="" style="flex: 1 1 0%; margin-right: 8px;">
                                <button type="button" class="btn-eliminar" disabled="" style="opacity: 0.3; pointer-events: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" width="1024" height="1024" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zm3-4q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17"></path></svg>
                                </button>
                            </div>
                            
                        <?php endforeach; ?>
                        <?php if(empty($pasos)): ?>
                            <div class="paso-item">
                                <span class="numero-paso">1</span>
                                <input type="text" name="pasos[]" value="" required>
                                <button type="button" class="eliminar-paso">Eliminar</button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="cont-btn">
                        <button type="button" id="agregar-paso" class="btn-secundario">+ Paso</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>



<script src="/Zava/js/cliente/crearReceta.js"></script>
<script src="/Zava/js/cliente/preview-imagenes-receta.js"></script>