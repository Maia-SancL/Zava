<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/general/conexion.php';

// include_once 'php/componentes/navegador.php'; //Menú superior
// include_once 'php/componentes/menuLateral.php'; //Menú lateral

//Verificamos si se recibip una categoría por GET desde el index
if (isset($_GET['tipo_comida'])) {
    $tipo_comida = $_GET['tipo_comida'];
}



function formatoTiempo($tiempo) {
    list($horas, $minutos, $segundos) = explode(":", $tiempo);
    return ((int)$horas * 60) + (int)$minutos;
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
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/menuLateral.php';?>
    <link rel="stylesheet" href="/Zava-php/css/recetario.css">
    <main>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/navegador.php';?>
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
            $query .= " AND tiempo_preparacion <= " . $tiempo;
        }
            
       $mostrar_resultados = ($tipo_comida !== '' || $tipo_dieta !== '' || $tiempo > 0);
       $texto_resultado = 'Resultados';

        if ($tipo_comida !== '') {
            $texto_resultado .= ' por "' . ucfirst($tipo_comida) . '"';
        } elseif ($tipo_dieta !== '' && $tipo_dieta !== 'todo') {
            $texto_resultado .= ' para dieta "' . ucfirst($tipo_dieta) . '"';
        } elseif ($tiempo > 0) {
            $texto_resultado .= ' por tiempo de preparación ' . formatoTiempo($tiempo); ' minutos';
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
        ?>
            <article class="receta-especifica">
                <div class="cont-img">
                    <!-- Imagen de la receta-->
                    <img src="/Zava-php/css/recursos/Desayuno.jpg" alt="<?php echo $fila['nombre']; ?>"> 
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
                            <?php// tagTipoComida($fila["tipo_comida"])?>
                            <div class="tag tipo">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="..."/></svg>
                                <span class="lbl-tipo"><?php echo ucfirst($fila["tipo_comida"]); ?></span>
                            </div>

                            <?php if ($fila["tipo_dieta"] == "vegana") { ?>
                                <div class="tag vegana">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path class="icon" fill="currentColor" d="M2 18.43c1.51 1.36 11.64-4.67 13.14-7.21c.72-1.22-.13-3.01-1.52-4.44C15.2 5.73 16.59 9 17.91 8.31c.6-.32.99-1.31.7-1.92c-.52-1.08-2.25-1.08-3.42-1.21c.83-.2 2.82-1.05 2.86-2.25c.04-.92-1.13-1.97-2.05-1.86c-1.21.14-1.65 1.88-2.06 3c-.05-.71-.2-2.27-.98-2.95c-1.04-.91-2.29-.05-2.32 1.05c-.04 1.33 2.82 2.07 1.92 3.67C11.04 4.67 9.25 4.03 8.1 4.7c-.49.31-1.05.91-1.63 1.69c.89.94 2.12 2.07 3.09 2.72c.2.14.26.42.11.62c-.14.21-.42.26-.62.12c-.99-.67-2.2-1.78-3.1-2.71c-.45.67-.91 1.43-1.34 2.23c.85.86 1.93 1.83 2.79 2.41c.2.14.25.42.11.62c-.14.21-.42.26-.63.12c-.85-.58-1.86-1.48-2.71-2.32C2.4 13.69 1.1 17.63 2 18.43"/></svg>
                                    <span>Vegana</span>
                                </div>
                            <?php } elseif ($fila["tipo_dieta"] == "vegetariana") { ?>
                                <div class="tag vegetariana">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"><path  class="icon" fill="currentColor" fill-rule="evenodd" d="M200.736 482.822c2.368 4.849 6.381 10.175 10.645 13.842c5.211 4.481 11.704 7.65 18.576 10.194c4.798 1.776 11.137 3.324 16.264 4.245c5.313.954 11.837.937 17.986.87c10.926-.12 19.55-1.137 29.283-3.508c6.132-1.494 11.957-3.153 16.685-5.013c8.927-3.513 14.619-7.413 15.806-9.943c-.891-4.183-2.293-10.77-3.337-16.69a272 272 0 0 1-2.318-16.001c-1.337-11.6-.649-19.762.833-33.255c2.183-19.873 6.427-28.926 12.624-40.735c5.918-11.28 15.687-27.045 23.216-37.2c7.849-10.588 27.5-31.28 34.668-38.969c2.5.74 7.328 2.35 14.795 3.824c5.69 1.123 12.316 2.462 20.654 2.181c19.852-.668 27.079-4.464 38.627-11.993c10.478-6.83 20.503-20.498 25.752-33.596c4.036-10.07 5.278-20.324 3.522-32.357c-1.182-8.094-3.797-17.679-5.887-24.017c-2.274-6.894-5.843-14.3-8.84-19.903c0 0 12.051-14.4 16.874-26.412c4.152-10.344 6.088-23.654 5.257-31.973c-.862-8.634-8.191-24.474-12.798-30.127c-6.069-7.448-12.002-15.17-19.499-21.224c-7.348-5.936-15.67-10.794-24.385-14.435c-9.031-3.772-19.835-5.956-28.398-7.46c-1.286-6.86-3.058-11.643-7.778-19.897c-5.894-10.6-12.368-15.388-22.543-22.838c-12.625-9.243-30.221-18.778-47.3-20.084c-7.358-.562-18.144-.794-31.708 2.123c-11.794 2.536-23.42 7.328-32.26 11.845c-17.75-6.798-34.333-5.794-47.415-2.241c-11.033 2.995-13.387 4.793-23.481 10.733c-9.91 4.281-20.676 14.236-29.012 25.762c-31.051.881-56.898 11.803-75.3 22.246c-20.93 11.878-39.713 34.582-47.067 49.024c-8.208 16.118-16.406 38.917-9.48 74.176c-45.742 28.123-41.038 73.108-15.323 98.226c22.552 22.03 59.913 28.893 95.3 25.324c10.572 10.717 50.566 59.17 50.566 59.17s40.285 45.399 26.196 106.086M157.337 294.89c5.474-5.473 10.166-13.293 13.815-19.288c4.141.23 12.645.788 21.007.404c8.384-.386 17.269-1.624 20.821-3.7c.997 2.99 7.935 27.758 8.375 42.978c.578 19.954.022 23.491-3.203 30.495c-1.756.83-3.464 1.192-5.619.418c-7.677-2.756-20.47-12.343-29.642-20.9c-14.205-13.25-25.554-30.407-25.554-30.407m158.127-4.215c-.275 2.424-.451 11.125.72 18.725c1.191 7.719 2.359 14.891 5.27 17.677c10.23-1.843 17.374-8.406 23.549-13.014c0 0 20.04-15.647 23.911-19.24c-2.949-2.673-7.096-8.94-8.57-12.718c0 0-14.103 4.131-21.2 5.606c-8.938 1.935-19.441 2.78-23.68 2.964" clip-rule="evenodd"/></svg>
                                    <span>Vegetariana</span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </article>
        <?php
    } // cierre while
}   // cierre iff ?>
</section>
</main>
</div>
<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/footer.php';
?>