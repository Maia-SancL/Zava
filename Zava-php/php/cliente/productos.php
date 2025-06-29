<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/conexion.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/header.php';

if (isset($_GET['tipo_producto'])) {
    $tipo_producto = $_GET['tipo_producto'];
}

$tipo_producto = isset($_GET['tipo_producto']) ? $_GET['tipo_producto'] : '';
$marca = isset($_GET['marca']) ? $_GET['marca'] : '';
$descuento = isset($_GET['descuento']) ? true : false;

?>
<div class="layout">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/menuLateral.php';?>
    <main>
        <link rel="stylesheet" href="/Zava-php/css/resultados-productos.css">
         <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/navegador.php';?>
         <section class="section-filtros">
            <form method="get" id="filtros-restaurantes" action="">
                <select name="tipo_producto" class="filtro">

                    <option disabled <?php echo ($tipo_producto == '') ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
                    <option value="harinas_y_premezclas" <?php echo ($tipo_producto == 'harinas_y_premezclas') ? 'selected' : ''; ?>>Harinas y premezclas</option>
                    <option value="galletitas" <?php echo ($tipo_producto == 'galletitas') ? 'selected' : ''; ?>>Galletitas</option>
                    <option value="golosinas" <?php echo ($tipo_producto == 'golosinas') ? 'selected' : ''; ?>>Golosinas</option>
                    <option value="cereales" <?php echo ($tipo_producto == 'cereales') ? 'selected' : ''; ?>>Cereales</option>
                    <option value="infusiones" <?php echo ($tipo_producto == 'infusiones') ? 'selected' : ''; ?>>Infusiones</option>
                    <option value="aderezos" <?php echo ($tipo_producto == 'aderezos') ? 'selected' : ''; ?>>Aderezos</option>
                    <option value="congelados" <?php echo ($tipo_producto == 'congelados') ? 'selected' : ''; ?>>Congelados</option>
                    <option value="pastas_y_arroces" <?php echo ($tipo_producto == 'pastas_y_arroces') ? 'selected' : ''; ?>>Pastas y arroces</option>
                    <option value="snacks" <?php echo ($tipo_producto == 'snacks') ? 'selected' : ''; ?>>Snacks</option>
                    <option value="bebidas" <?php echo ($tipo_producto == 'bebidas') ? 'selected' : ''; ?>>Bebidas</option>

                </select>

                <select name="marca" class="filtro">
                    <option value="">Marca</option>
                    <!--QUE APAREZCA DE FORMA DINAMICA LAS MARCAS DISPONIBLES-->
                </select>
                
                <div class="btn-oferta">
                    <input type="checkbox" id="btn-switch" class="btn-switch" name="descuento"/>
                    <label for="btn-switch" class="lbl-switch">
                        <span class="icono-svg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="currentColor" class="icon"
                            d="m20.749 12l1.104-1.908a1 1 0 0 0-.365-1.366l-1.91-1.104v-2.2a1 1 0 0 0-1-1h-2.199l-1.103-1.909a1 1 0 0 0-.607-.466a1 1 0 0 0-.759.1L12 3.251l-1.91-1.105a1 1 0 0 0-1.366.366L7.62 4.422H5.421a1 1 0 0 0-1 1v2.199l-1.91 1.104a1 1 0 0 0-.365 1.367L3.25 12l-1.104 1.908a1.004 1.004 0 0 0 .364 1.367l1.91 1.104v2.199a1 1 0 0 0 1 1h2.2l1.104 1.91a1.01 1.01 0 0 0 .866.5c.174 0 .347-.046.501-.135l1.908-1.104l1.91 1.104a1 1 0 0 0 1.366-.365l1.103-1.91h2.199a1 1 0 0 0 1-1v-2.199l1.91-1.104a1 1 0 0 0 .365-1.367zM9.499 6.99a1.5 1.5 0 1 1-.001 3.001a1.5 1.5 0 0 1 .001-3.001m.3 9.6l-1.6-1.199l6-8l1.6 1.199zm4.7.4a1.5 1.5 0 1 1 .001-3.001a1.5 1.5 0 0 1-.001 3.001" />
                        </svg>
                        </span>
                    </label>
                </div>
                <button type="submit" class="btn-filtar">Filtrar</button>
            </form>
        </section>
        <?php
        $filtrar = isset($_GET['filtrar']) ? $_GET['filtrar'] : '';

        //Escribimos el principio de la consulta
        $query = "SELECT * FROM productos WHERE 1=1";

        if ($tipo_producto != '') { //Si la var tipo_producto esta con algun dato entonces busca el seleccionado  
            $query .= " AND tipo = '" . mysqli_real_escape_string($conexion, $tipo_producto) . "'";
        }

        if ($marca != '' && $marca != 'todo') { //Si la marca es diferente a todo y existe entonces busca el seleccionado
            $query .= " AND marca = '" . mysqli_real_escape_string($conexion, $marca) . "'";
        }

        if ($descuento === 'true') {
            $query .= " AND descuento > 0"; 
        }

            
       $mostrar_resultados = ($tipo_producto !== '' || $marca !== '' || $descuento == 'true');
       $texto_resultado = 'Resultados';

        if ($tipo_producto !== '') {
            $texto_resultado .= ' por "' . ucfirst($tipo_producto) . '"';
        } elseif ($marca !== '' && $marca !== 'todo') {
            $texto_resultado .= ' para' . ucfirst($marca) . '"';
        } elseif ($descuento == 'true' ) {
            $texto_resultado .= ' con ofertas ';
        }

        $orden = isset($_GET['orden']) ? $_GET['orden'] : ''; //Filtro que esta dentro del section-resultados-busqueda
        if ($orden === 'alfabetico') {
            $query .= " ORDER BY nombre ASC";
        } elseif ($orden === 'z-a') {
            $query .= " ORDER BY nombre DESC";
        } elseif ($orden === 'tiempo') {
            $query .= " ORDER BY tiempo_preparacion ASC";
        }

    if ($result = $conexion->query($query)) {?>

        <section class="resultado-busqueda" <?php echo $mostrar_resultados ? '' : 'oculto'; ?>">
            <div class="cont-lbl-resultado">
                <h3><?php echo $texto_resultado; ?></h3>
            </div>
            <div class="cont-resultados-select">
                <?php if (isset($result)) { $cant_resultados = $result->num_rows; } else { $cant_resultados = 0; } ?>
                <span class="lbl-resultados"><?php echo $cant_resultados; ?> Resultados encontrados</span>

                <form method="get" id="form-orden">
                    <input type="hidden" name="tipo_producto" value="<?php echo htmlspecialchars($tipo_producto); ?>">
                    <input type="hidden" name="marca" value="<?php echo htmlspecialchars($marca); ?>">
                    <input type="hidden" name="descuento" value="<?php echo htmlspecialchars($descuento); ?>">

                   <select name="orden" class="filtro" onchange="document.getElementById('form-orden').submit();"> <!--Se activa automaticamente cuando el usuario elige una nueva opcion y envia el formulario-->
                    <option value="alfabetico" <?php echo ($orden == 'alfabetico') ? 'selected' : ''; ?>>A-Z</option>
                    <option value="z-a" <?php echo ($orden == 'z-a') ? 'selected' : ''; ?>>Z-A</option>
                    <option value="tiempo" <?php echo ($orden == 'tiempo') ? 'selected' : ''; ?>>Menor tiempo</option>
                </select>
                </form>
            </div>
        </section>

        <section class="section-productos">
            <article class="producto">
                <div class="cont-img">
                    <img src="../css/recursos/Almuerzo.jpg" alt="Imagen producto">
                    <div class="cont-oferta-favorito">
                        <div class="cont-oferta">
                            <p>%20 off</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125"/></svg>
                    </div>
                </div>
                <div class="informacion-producto"> 
                    <span class="tipo-producto">tipo productaaaaaaaaaaaaaaaaaao</span>
                    <span class="titulo-producto">AAAAA</span>
                    <span class="peso-producto">15g</span>
                    <div class="cont-precio-btn">
                            <span class="precio">$1.000.000,99</span>
                            <span class="precio-oferta">$1.000.000</span> <!--SOLO SE MOSTRARA SI EL PRODUCTO ESTA EN OFERTA--> 
                        <button class="btn-agregar-carrito"> 
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path  class="icon" path fill="currentColor" fill-rule="evenodd" d="M10 2.25a1.75 1.75 0 0 0-1.582 1c-.684.006-1.216.037-1.692.223A3.25 3.25 0 0 0 5.3 4.563c-.367.493-.54 1.127-.776 1.998l-.047.17l-.513 2.964q-.277.191-.486.459c-.901 1.153-.472 2.87.386 6.301c.545 2.183.818 3.274 1.632 3.91C6.31 21 7.435 21 9.685 21h4.63c2.25 0 3.375 0 4.189-.635c.814-.636 1.086-1.727 1.632-3.91c.858-3.432 1.287-5.147.386-6.301a2.2 2.2 0 0 0-.487-.46l-.513-2.962l-.046-.17c-.237-.872-.41-1.506-.776-2a3.25 3.25 0 0 0-1.426-1.089c-.476-.186-1.009-.217-1.692-.222A1.75 1.75 0 0 0 14 2.25zm8.418 6.896l-.362-2.088c-.283-1.04-.386-1.367-.56-1.601a1.75 1.75 0 0 0-.768-.587c-.22-.086-.486-.111-1.148-.118A1.75 1.75 0 0 1 14 5.75h-4a1.75 1.75 0 0 1-1.58-.998c-.663.007-.928.032-1.148.118a1.75 1.75 0 0 0-.768.587c-.174.234-.277.56-.56 1.6l-.362 2.089C6.58 9 7.91 9 9.685 9h4.63c1.775 0 3.105 0 4.103.146M8 12.25a.75.75 0 0 1 .75.75v4a.75.75 0 0 1-1.5 0v-4a.75.75 0 0 1 .75-.75m8.75.75a.75.75 0 0 0-1.5 0v4a.75.75 0 0 0 1.5 0zM12 12.25a.75.75 0 0 1 .75.75v4a.75.75 0 0 1-1.5 0v-4a.75.75 0 0 1 .75-.75" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                </div>
            </article>

    <?php } ?>


        </section>
    </main>
</div>
<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/footer.php';
?>
