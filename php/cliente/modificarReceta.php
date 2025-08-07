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

// Obtener el ID de la receta a modificar
$id_receta = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_receta <= 0) {
    header("Location: perfil/perfilRecetas.php");
    exit;
}

// Verificar que la receta pertenece al usuario
$query_verificar = "SELECT * FROM Recetas WHERE id_receta = $id_receta AND id_usuario = $id_usuario";
$resultado_verificar = mysqli_query($conexion, $query_verificar);
$receta = mysqli_fetch_assoc($resultado_verificar);

if (!$receta) {
    header("Location: perfil/perfilRecetas.php");
    exit;
}

// Preparar datos para el formulario
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

// Las imágenes ya se obtuvieron en la consulta principal de la receta ($receta)
$imagenes_existentes = [];
if (!empty($receta['imagen_principal'])) {
    $imagenes_existentes[] = $receta['imagen_principal'];
}
if (!empty($receta['imagenes_secundarias'])) {
    $secundarias = json_decode($receta['imagenes_secundarias'], true);
    if (is_array($secundarias)) {
        $imagenes_existentes = array_merge($imagenes_existentes, $secundarias);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {    // Si el formulario fue enviado por POST
    if (
        isset($_POST['nombre']) &&
        isset($_POST['descripcion']) &&
        isset($_POST['ingredientes']) &&
        isset($_POST['pasos']) &&
        isset($_POST['tipo_comida'])
    ) {
        $nombre_nuevo = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $descripcion_nueva = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $ingredientes_nuevos = isset($_POST['ingredientes']) ? mysqli_real_escape_string($conexion, implode(', ', $_POST['ingredientes'])) : '';
        $pasos_nuevos = isset($_POST['pasos']) ? mysqli_real_escape_string($conexion, implode('. ', $_POST['pasos'])) : '';
        $tipo_comida_nuevo = mysqli_real_escape_string($conexion, $_POST['tipo_comida']);
        $porciones_nuevas = isset($_POST['porciones']) ? intval($_POST['porciones']) : 1;
        $tipo_dieta_nuevo = mysqli_real_escape_string($conexion, $_POST['tipo_dieta']);
        $tiempo_nuevo = isset($_POST['tiempo']) ? intval($_POST['tiempo']) : 0;
        $dificultad_nueva = mysqli_real_escape_string($conexion, $_POST['dificultad']);
        $id_categoria_nueva = intval($_POST['categoria']);

        // Manejar imágenes eliminadas
        $imagenes_actualizadas = $imagenes_existentes;
        for ($i = 0; $i < 3; $i++) {
            if (isset($_POST['eliminar_imagen_' . $i]) && $_POST['eliminar_imagen_' . $i] == '1') {
                if (isset($imagenes_actualizadas[$i])) {
                    // Aquí podrías eliminar el archivo del servidor si lo deseas
                    // unlink($_SERVER['DOCUMENT_ROOT'] . '/Zava/imagenes/recetas/' . $imagenes_actualizadas[$i]);
                    $imagenes_actualizadas[$i] = null;
                }
            }
        }
        $imagenes_actualizadas = array_filter($imagenes_actualizadas);

        // Guardar nuevas imágenes
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

        // Combinar imágenes existentes (no eliminadas) con las nuevas
        $imagenes_finales = array_merge($imagenes_actualizadas, $imagenes_guardadas);
        $imagenes_finales = array_slice($imagenes_finales, 0, 3); // Limitar a 3 imágenes

        $imagen_principal_nueva = !empty($imagenes_finales) ? $imagenes_finales[0] : '';
        


        // Actualizar receta principal
        $query = "UPDATE Recetas SET 
                    nombre = '$nombre_nuevo', 
                    descripcion = '$descripcion_nueva', 
                    ingredientes = '$ingredientes_nuevos', 
                    pasos = '$pasos_nuevos', 
                    tiempo_preparacion = '$tiempo_nuevo', 
                    porciones = '$porciones_nuevas', 
                    dificultad = '$dificultad_nueva', 
                    tipo_comida = '$tipo_comida_nuevo', 
                    tipo_dieta = '$tipo_dieta_nuevo', 
                    id_categoria = '$id_categoria_nueva', 
                    imagen_principal = '$imagen_principal_nueva'
                  WHERE id_receta = $id_receta AND id_usuario = $id_usuario";
        
        if (mysqli_query($conexion, $query)) {
            // Actualizar imágenes secundarias
            $query_delete_img = "DELETE FROM Receta_Imagenes WHERE id_receta = $id_receta";
            mysqli_query($conexion, $query_delete_img);
            
            if (count($imagenes_finales) > 1) {
                for ($i = 1; $i < count($imagenes_finales); $i++) {
                    $imagen_secundaria = $imagenes_finales[$i];
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
                    window.location.href = 'mostrarReceta.php?id=$id_receta';
                }, 1800);
            </script>
            ";
            exit; // Detiene el resto del HTML
        } else {
            $mensaje = "Error al modificar la receta: " . mysqli_error($conexion);
        }
    }
}
?>
<link rel="stylesheet" href="/Zava/css/crear-receta.css">
<script src="/Zava/js/main.js"></script>
<main>
    <div class="crear-receta">
        <form action="modificarReceta.php?id=<?php echo $id_receta; ?>" method="POST" enctype="multipart/form-data" class="form-receta">
            <div class="columna-izq">
                <div id="zona-imagenes" class="zona-imagenes">
                    <?php if (empty($imagenes_existentes)): ?>
                        <div class="icono-imagen" id="icono-imagen">
                            <!-- SVG de icono de imagen -->
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
                        <!-- Slot 0 - Imagen principal -->
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
                        
                        <!-- Slot 1 - Segunda imagen -->
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
                        
                        <!-- Slot 2 - Tercera imagen -->
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
                            <div class="paso-item">
                                <span class="numero-paso"><?php echo ($index + 1); ?></span>
                                <input type="text" name="pasos[]" value="<?php echo htmlspecialchars($paso); ?>" required>
                                <button type="button" class="eliminar-paso">Eliminar</button>
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
                <div class="botones">
                    <button type="submit" class="btn-principal">Guardar Cambios</button>
                    <button type="reset" id="cancelar-boton" class="btn-secundario">Borrar</button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
// Configurar funcionalidad de ingredientes y pasos después de que se cargue la página
window.addEventListener('load', function() {
    // Actualizar el valor del slider de tiempo
    const tiempoSlider = document.getElementById('tiempo');
    const tiempoValor = document.getElementById('tiempo-valor');
    if (tiempoSlider && tiempoValor) {
        tiempoValor.textContent = tiempoSlider.value + ' min';
    }
    
    // Configurar eventos para eliminar ingredientes
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('eliminar-ingrediente')) {
            const ingredienteItems = document.querySelectorAll('.ingrediente-item');
            if (ingredienteItems.length > 1) {
                e.target.parentElement.remove();
            }
        }
        
        if (e.target.classList.contains('eliminar-paso')) {
            const pasoItems = document.querySelectorAll('.paso-item');
            if (pasoItems.length > 1) {
                e.target.parentElement.remove();
                actualizarNumerosPasos();
            }
        }
    });
    
    // Función para actualizar números de pasos
    function actualizarNumerosPasos() {
        const pasos = document.querySelectorAll('.paso-item');
        pasos.forEach(function(paso, index) {
            const numeroSpan = paso.querySelector('.numero-paso');
            if (numeroSpan) {
                numeroSpan.textContent = index + 1;
            }
        });
    }
    
    // Configurar botones de agregar
    const botonAgregarIngrediente = document.getElementById('agregar-ingrediente');
    if (botonAgregarIngrediente) {
        botonAgregarIngrediente.addEventListener('click', function() {
            const contenedor = document.getElementById('contenedor-ingredientes');
            const nuevoIngrediente = document.createElement('div');
            nuevoIngrediente.className = 'ingrediente-item';
            nuevoIngrediente.innerHTML = `
                <input type="text" name="ingredientes[]" value="" required>
                <button type="button" class="eliminar-ingrediente">Eliminar</button>
            `;
            contenedor.appendChild(nuevoIngrediente);
        });
    }
    
    const botonAgregarPaso = document.getElementById('agregar-paso');
    if (botonAgregarPaso) {
        botonAgregarPaso.addEventListener('click', function() {
            const contenedor = document.getElementById('contenedor-pasos');
            const pasoItems = document.querySelectorAll('.paso-item');
            const numeroNuevo = pasoItems.length + 1;
            
            const nuevoPaso = document.createElement('div');
            nuevoPaso.className = 'paso-item';
            nuevoPaso.innerHTML = `
                <span class="numero-paso">${numeroNuevo}</span>
                <input type="text" name="pasos[]" value="" required>
                <button type="button" class="eliminar-paso">Eliminar</button>
            `;
            contenedor.appendChild(nuevoPaso);
        });
    }
    
    // Deshabilitar el botón eliminar del primer ingrediente y primer paso
    setTimeout(function() {
        const primerIngrediente = document.querySelector('.ingrediente-item .eliminar-ingrediente');
        const primerPaso = document.querySelector('.paso-item .eliminar-paso');
        
        if (primerIngrediente) {
            primerIngrediente.style.opacity = '0.5';
            primerIngrediente.style.pointerEvents = 'none';
        }
        
        if (primerPaso) {
            primerPaso.style.opacity = '0.5';
            primerPaso.style.pointerEvents = 'none';
        }
    }, 100);
    
    // Funcionalidad para eliminar imágenes existentes
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-image')) {
            const slot = e.target.getAttribute('data-slot');
            const slotElement = document.getElementById('slot-' + slot);
            
            // Limpiar el slot
            if (slot === '0') {
                slotElement.innerHTML = '<span style="color:#999;font-size:14px;">Imagen principal</span>';
            } else {
                slotElement.innerHTML = '<span style="color:#999;font-size:12px;">+</span>';
            }
            
            // Agregar input hidden para marcar la imagen como eliminada
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'eliminar_imagen_' + slot;
            hiddenInput.value = '1';
            document.querySelector('form').appendChild(hiddenInput);
        }
    });
});

// Inicializar el preview de imágenes si ya existen imágenes
window.addEventListener('load', function() {
    const previewContainer = document.getElementById('previewContainer');
    const zonaTexto = document.getElementById('zona-texto');
    const iconoImagen = document.getElementById('icono-imagen');
    
    // Si hay imágenes existentes, mostrar el contenedor y ocultar el icono
    if (previewContainer && previewContainer.style.display !== 'none') {
        if (iconoImagen) iconoImagen.style.display = 'none';
    }
});
</script>
<script src="/Zava/js/preview-imagenes-receta.js"></script>