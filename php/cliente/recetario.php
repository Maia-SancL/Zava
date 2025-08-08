<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/conexion.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/funciones/tags.php';

// include_once 'php/componentes/navegador.php'; //Menú superior
// include_once 'php/componentes/menuLateral.php'; //Menú lateral

//Verificamos si se recibip una categoria por GET desde el index
if (isset($_GET['tipo_comida'])) {
    $tipo_comida = $_GET['tipo_comida'];
}

function formatoTiempo($tiempo) {
    if (is_numeric($tiempo)) {
        return $tiempo . ' min'; // Ya es numero de minutos
    }

    //Si viene en otro fomato formato como hHH:MM:SS
    if (strpos($tiempo, ':') !== false) {
        list($horas, $minutos, $segundos) = explode(':', $tiempo);
        $minTotal = ((int)$horas * 60) + (int)$minutos;
        return $minTotal . ' min';
    }

    return 'Tiempo invalido';
}

// $query_recetas = "SELECT id_receta, nombre, descripcion, imagen, tipo_comida FROM recetas ORDER BY RAND() LIMIT 6";
// $resultado_recetas = mysqli_query($conexion, $query_recetas);

// // Consulta para obtener las categorías de recetas
// $query_categorias = "SELECT DISTINCT tipo_comida FROM recetas";
// $resultado_categorias = mysqli_query($conexion, $query_categorias);

$tipo_comida = isset($_GET['tipo_comida']) ? $_GET['tipo_comida'] : '';
$tipo_dieta = isset($_GET['tipo_dieta']) ? $_GET['tipo_dieta'] : '';
$tiempo = isset($_GET['tiempo']) && is_numeric($_GET['tiempo']) ? (int)$_GET['tiempo'] : 0;

?>
<div class="layout">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/menuLateral.php';?>
    <link rel="stylesheet" href="/Zava/css/recetario.css">
    <main>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';?>
        <section class="section-filtros">
            <form action="" method="GET" id="filtros-recetas">
            <select name="tipo_comida" class="filtro">
                <option disabled <?php echo ($tipo_comida == '') ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
                <option value="desayuno" <?php echo ($tipo_comida == 'desayuno') ? 'selected' : ''; ?>>Desayuno</option>
                <option value="almuerzo" <?php echo ($tipo_comida == 'almuerzo') ? 'selected' : ''; ?>>Almuerzo</option>
                <option value="merienda" <?php echo ($tipo_comida == 'merienda') ? 'selected' : ''; ?>>Merienda</option>
                <option value="cena" <?php echo ($tipo_comida == 'cena') ? 'selected' : ''; ?>>Cena</option>
                <option value="postre" <?php echo ($tipo_comida == 'postre') ? 'selected' : ''; ?>>Postre</option>
                <option value="panaderia" <?php echo ($tipo_comida == 'panaderia') ? 'selected' : ''; ?>>Panadería</option>
                <option value="snack" <?php echo ($tipo_comida == 'snack') ? 'selected' : ''; ?>>Snack</option>
                <option value="bebida" <?php echo ($tipo_comida == 'bebida') ? 'selected' : ''; ?>>Bebida</option>
            </select>

            <select name="tipo_dieta" class="filtro">
                <option disabled <?php echo ($tipo_dieta == '') ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
                <option value="vegetariana" <?php echo ($tipo_dieta == 'vegetariana') ? 'selected' : ''; ?>>Vegetariana</option>
                <option value="vegana" <?php echo ($tipo_dieta == 'vegana') ? 'selected' : ''; ?>>Vegana</option> 
                <option value="todo" <?php echo ($tipo_dieta == 'todo') ? 'selected' : ''; ?>>Todas</option>
            </select>

                <input class="filtro" type="number" name="tiempo" id="tiempo" value="<?php echo htmlspecialchars        ($tiempo); ?>" min="0" max="300" step="5" placeholder="Tiempo (min)" />

                <button type="submit" class="btn-filtrar" value="filtrar">Filtrar</button>
            </form>
        </section>

        <?php
        $filtrar = isset($_GET['filtrar']) ? $_GET['filtrar'] : '';

        //Escribimos el principio de la consulta
        $query = "SELECT * FROM recetas WHERE 1=1";

        if ($tipo_comida != '') { //Si la var tipo_comida esta con algun dato entonces busca el seleccionado  
            $query .= " AND tipo_comida = '" . mysqli_real_escape_string($conexion, $tipo_comida) . "'";
        }

        if ($tipo_dieta != '' && $tipo_dieta != 'todo') { //Si el tipo_dieta es diferente a todo y existe entonces busca el seleccionado
            $query .= " AND tipo_dieta = '" . mysqli_real_escape_string($conexion, $tipo_dieta) . "'";
        }

        if ($tiempo > 0) { //Si el tiempo es mayor a 0 agrega el filtro
            $query .= " AND TIME_TO_SEC(tiempo_preparacion)/60 <= " . $tiempo;
        }
            
       $mostrar_resultados = ($tipo_comida !== '' || $tipo_dieta !== '' || $tiempo > 0);
       $texto_resultado = 'Resultados';

        if ($tipo_comida !== '') {
            $texto_resultado .= ' por "' . ucfirst($tipo_comida) . '"';
        } elseif ($tipo_dieta !== '' && $tipo_dieta !== 'todo') {
            $texto_resultado .= ' para dieta "' . ucfirst($tipo_dieta) . '"';
        } elseif ($tiempo > 0) {
            $texto_resultado .= ' por tiempo de preparación ' . formatoTiempo($tiempo);
        }

        $orden = isset($_GET['orden']) ? $_GET['orden'] : ''; //Filtro que esta dentro del section-resultados-busqueda
        if ($orden === 'alfabetico') {
            $query .= " ORDER BY nombre ASC";
        } elseif ($orden === 'z-a') {
            $query .= " ORDER BY nombre DESC";
        } elseif ($orden === 'tiempo') {
            $query .= " ORDER BY tiempo_preparacion ASC";
        }

    if ($result = $conexion->query($query)) {
    ?>
    <section class="resultado-busqueda <?php echo $mostrar_resultados ? '' : 'oculto'; ?>">
    <div class="cont-lbl-resultado">
        <h3> <?php echo $texto_resultado; ?></h3>
    </div>
    <div class="cont-resultados-select">
        <?php if (isset($result)) { $cant_resultados = $result->num_rows; } else { $cant_resultados = 0; } ?>
        <span class="lbl-resultados"><?php echo $cant_resultados; ?> Resultados encontrados</span>

        <form method="get" id="form-orden">
            <!-- Filtros actuales en campos ocultos para que no se pierdan uando se actualiza-->
            <input type="hidden" name="tipo_comida" value="<?php echo htmlspecialchars($tipo_comida); ?>">
            <input type="hidden" name="tipo_dieta" value="<?php echo htmlspecialchars($tipo_dieta); ?>">
            <input type="hidden" name="tiempo" value="<?php echo htmlspecialchars($tiempo); ?>">

            <select name="orden" class="filtro" onchange="document.getElementById('form-orden').submit();"> <!--Se activa automaticamente cuando el usuario elige una nueva opcion y envia el formulario-->
                <option value="alfabetico" <?php echo ($orden == 'alfabetico') ? 'selected' : ''; ?>>A-Z</option>
                <option value="z-a" <?php echo ($orden == 'z-a') ? 'selected' : ''; ?>>Z-A</option>
                <option value="tiempo" <?php echo ($orden == 'tiempo') ? 'selected' : ''; ?>>Menor tiempo</option>
            </select>
        </form>
    </div>
</section>


    <section class="section-recetas">
    <?php
    while ($fila = $result->fetch_assoc()) {
        $ruta_imagen= '/Zava/imagenes/recetas/' . $fila['imagen'];
        ?>
         <form action="mostrarReceta.php" method="GET">
           <article onclick="location.href='mostrarReceta.php?id_receta=<?php echo $fila['id_receta']; ?>'" class="receta-especifica">
                <div class="cont-img">
                    <!-- Imagen de la receta-->
                    <img src="<?php echo $ruta_imagen; ?>" alt="<?php echo $fila['nombre']; ?>"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="..."/></svg>
                </div>
                <div class="info-receta">
                    <div class="cont-lbl-titulo">
                       <h3><?php echo ucfirst($fila["nombre"]); ?></h3>
                    </div>
                    <div class="cont-tags">
                        <div class="cont-tiempo">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"><path class="icon-tiempo" fill="currentColor" d="M11.5 3a9.5 9.5 0 0 1 9.5 9.5a9.5 9.5 0 0 1-9.5 9.5A9.5 9.5 0 0 1 2 12.5A9.5 9.5 0 0 1 11.5 3m0 1A8.5 8.5 0 0 0 3 12.5a8.5 8.5 0 0 0 8.5 8.5a8.5 8.5 0 0 0 8.5-8.5A8.5 8.5 0 0 0 11.5 4M11 7h1v5.42l4.7 2.71l-.5.87l-5.2-3z"/></svg>
                            <div class="tiempo">
                                <p><?php echo formatoTiempo($fila["tiempo_preparacion"]); ?></p>
                            </div>
                        </div>
                        <div class="tags-especificos">
                            <div class="tag tipo">
                                 <?php echo filtrarTags($fila['tipo_comida']);?>
                                <span class="lbl-tipo"><?php echo ucfirst($fila["tipo_comida"]); ?></span>
                            </div>
                            <?php if(isset($fila['tipo_dieta'])){
                                    switch ($fila['tipo_dieta']){
                                        case('vegano'):?>
                                            <div class="tag">
                                                <?php echo filtrarTags($fila['tipo_dieta']);?>
                                                <span>Vegano</span>
                                            </div> 
                                            <?php break;
                                        case ('vegetariano'):
                                            echo filtrarTags($fila['tipo_dieta']);?>
                                            <span>Vegetariana</span>
                                            <?php break;
                                        default:
                                        break;
                                    }
                                 }?>
                        </div>
                    </div>
                </div>
                <input type="hidden" value="<?php echo $fila['id_receta']?>" name="id_receta">
            </article>
        </form>
        <?php
    } // cierre while
}   // cierre iff ?>
</section>
</main>
</div>
<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/footer.php';
?>