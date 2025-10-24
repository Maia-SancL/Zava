<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/Zava/administrador/php/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id'])) {
        $_SESSION['mensaje'] = 'Usuario no autenticado.';
        $_SESSION['mensaje_tipo'] = 'error';
        header('Location: ' . BASE_URL . 'comercio/productos/agregar');
        exit();
    }

    $id_usuario = $_SESSION['id'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre'] ?? '');
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion'] ?? '');
    $marca = mysqli_real_escape_string($conexion, $_POST['marca'] ?? '');
    $peso = floatval($_POST['peso'] ?? 0);
    $precio = floatval($_POST['precio'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $categoria_nombre = mysqli_real_escape_string($conexion, $_POST['categoria'] ?? '');
    $estado = mysqli_real_escape_string($conexion, $_POST['estado'] ?? 'Disponible');
    $activo = ($estado === 'Disponible') ? 1 : 0;

    $descuento_activo = isset($_POST['oferta']);
    $porcentaje_descuento = $descuento_activo ? intval($_POST['descuento'] ?? 0) : NULL;
    $descuento_bool = $descuento_activo ? 1 : 0;

    $imagen_principal = 'producto.jpg';
    if (!empty($_FILES['imagen-producto']['name'])) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/img/productos/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $nombre_archivo = time() . '_' . basename($_FILES['imagen-producto']['name']);
        $ruta_completa = $target_dir . $nombre_archivo;
        if (move_uploaded_file($_FILES['imagen-producto']['tmp_name'], $ruta_completa)) {
            $imagen_principal = $nombre_archivo;
        }
    }

    $id_categoria_sql = "(SELECT id_categoria FROM Categorias WHERE nombre = '$categoria_nombre' LIMIT 1)";

    $porcentaje_descuento_sql = is_null($porcentaje_descuento) ? 'NULL' : $porcentaje_descuento;

    $sql = "INSERT INTO Productos (id_usuario, nombre, descripcion, marca, peso, precio, stock, descuento, porcentaje_descuento, id_categoria, activo, imagen_principal) 
            VALUES ('$id_usuario', '$nombre', '$descripcion', '$marca', '$peso', '$precio', '$stock', $descuento_bool, $porcentaje_descuento_sql, $id_categoria_sql, '$activo', '$imagen_principal')";

    if (mysqli_query($conexion, $sql)) {
        $_SESSION['mensaje'] = '¡Producto agregado con éxito!';
        $_SESSION['mensaje_tipo'] = 'exito';
    } else {
        $_SESSION['mensaje'] = 'Error al agregar el producto: ' . mysqli_error($conexion);
        $_SESSION['mensaje_tipo'] = 'error';
    }
    mysqli_close($conexion);
    header('Location: ' . BASE_URL . 'comercio/productos/agregar');
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/componentes/php/header.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>comercio/css/comercio-agregar-producto.css">
    <main>
        <section class="section-titulo-btns">
            <h4>Agregar producto</h4>
            <?php
            if (isset($_SESSION['mensaje'])) {
                $tipo_mensaje = $_SESSION['mensaje_tipo'] ?? 'error';
                $bg_color = $tipo_mensaje === 'exito' ? '#d4edda' : '#f8d7da';
                $color = $tipo_mensaje === 'exito' ? '#155724' : '#721c24';
                echo '<div id="mensaje-respuesta" style="margin-bottom: 15px; padding: 10px; border-radius: 5px; background-color: ' . $bg_color . '; color: ' . $color . ';">' . htmlspecialchars($_SESSION['mensaje']) . '</div>';
                unset($_SESSION['mensaje']);
                unset($_SESSION['mensaje_tipo']);
            }
            ?>
            <!-- BOTONES -->
            <div class="btns">
                <button type="submit" form="form-agregar-productos" class="btn-publicar">
                    Publicar
                </button>
                <button class="btn-borrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path class="icon" fill="currentColor"
                            d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zm3-4q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17" />
                    </svg>
                    Borrar
                </button>
            </div>
        </section>

        <section class="section-agregar-productos">
            <form action="<?php echo BASE_URL; ?>comercio/productos/agregar" method="POST" enctype="multipart/form-data"
                class="form-agregar-productos" id="form-agregar-productos">
                <div class="datos cont-izquierda">
                    <label class="cont-upload">
                        <input type="file" name="imagen-producto" id="imagen-producto">
                        <div class="contenido-upload">
                            <svg class="subir-img-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path fill="currentColor" class="icon"
                                    d="M18 15v3h-3v2h3v3h2v-3h3v-2h-3v-3zm-4.7 6H5c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v8.3c-.6-.2-1.3-.3-2-.3c-1.1 0-2.2.3-3.1.9L14.5 12L11 16.5l-2.5-3L5 18h8.1c-.1.3-.1.7-.1 1c0 .7.1 1.4.3 2" />
                            </svg>
                            <span>Agrega una imagen del producto</span>
                            <img class="preview-img"/>
                        </div>
                    </label>
                    <div class="cont-input">
                        <label class="lbl-producto" for="descripcion">Descripción</label>
                        <textarea class="input-producto" name="descripcion" id="descripcion" rows="6" maxlength="500"
                            placeholder="Escribe una breve descripción" required></textarea>
                    </div>
                </div>
                <div class="datos cont-derecha">
                    <div class="cont-input">
                        <label class="lbl-producto" for="nombre">Nombre del producto</label>
                        <input class="input-producto" type="text" name="nombre" id="nombre" required maxlength="50">
                    </div>
                    <div class="cont-input">
                        <label class="lbl-producto" for="marca">Marca</label>
                        <input class="input-producto" type="text" name="marca" id="marca" maxlength="100">
                    </div>
                    <div class="cont-input">
                        <label class="lbl-producto" for="peso">Peso (en gr)</label>
                        <input class="input-producto" type="number" name="peso" id="peso" min="0" step="0.01">
                    </div>
                    <div class="cont-input">
                        <label class="lbl-producto" for="precio">Precio</label>
                        <input class="input-producto" type="number" name="precio" id="precio" required min="1" max="9999999" step="0.01">
                    </div>
                    <div class="cont-input">
                        <label class="lbl-producto" for="stock">Stock</label>
                        <input class="input-producto" type="number" name="stock" id="stock" required min="1" max="99999">
                    </div>
                    <div class="cont-oferta">
                        <label class="lbl-producto" for="Oferta">Oferta</label>
                        <div class="btn-oferta">
                            <input type="checkbox" name="oferta" id="btn-switch" class="btn-switch" />
                            <label for="btn-switch" class="lbl-switch">
                                <span class="icono-svg">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                        <path fill="currentColor" class="icon"
                                            d="M20.749 12l1.104-1.908a1 1 0 0 0-.365-1.366l-1.91-1.104v-2.2a1 1 0 0 0-1-1h-2.199l-1.103-1.909a1 1 0 0 0-.607-.466a1 1 0 0 0-.759.1L12 3.251l-1.91-1.105a1 1 0 0 0-1.366.366L7.62 4.422H5.421a1 1 0 0 0-1 1v2.199l-1.91 1.104a1 1 0 0 0-.365 1.367L3.25 12l-1.104 1.908a1.004 1.004 0 0 0 .364 1.367l1.91 1.104v2.199a1 1 0 0 0 1 1h2.2l1.104 1.91a1.01 1.01 0 0 0 .866.5c.174 0 .347-.046.501-.135l1.908-1.104l1.91 1.104a1 1 0 0 0 1.366-.365l1.103-1.91h2.199a1 1 0 0 0 1-1v-2.199l1.91-1.104a1 1 0 0 0 .365-1.367zM9.499 6.99a1.5 1.5 0 1 1-.001 3.001a1.5 1.5 0 0 1 .001-3.001m.3 9.6l-1.6-1.199l6-8l1.6 1.199zm4.7.4a1.5 1.5 0 1 1 .001-3.001a1.5 1.5 0 0 1-.001 3.001" />
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="cont-input oculto">
                        <label class="lbl-producto" for="descuento">Descuento</label>
                        <input class="input-producto" type="number" name="descuento" required min="1" max="99">
                    </div>
                    <div class="cont-input doble">
                        <div class="cont-input">
                            <label class="lbl-producto" for="categoria">Categoría</label>
                            <select class="input-producto" name="categoria" id="categoria" required>
                                <option value="" disabled selected>Selecciona una categoría</option>
                            </select>
                        </div>
                        <div class="cont-input">
                            <label class="lbl-producto" for="categoria">Tipo</label>
                            <select class="input-producto" name="tipo" id="tipo" required>
                                <option value="" disabled selected>Selecciona un tipo de producto</option>
                            </select>
                        </div>
                    </div>
                    <div class="cont-input">
                        <label class="lbl-producto" for="estado">Estado</label>
                        <select class="input-producto" name="estado" id="estado" required>
                            <option value="" disabled selected>Selecciona el estado del producto</option>
                            <option value="Disponible">Disponible</option>
                            <option value="Sin stock">Sin stock</option>
                        </select>
                    </div>
                </div>
            </form>
        </section>
    </main>
</body>
</body>
<script src="<?php echo BASE_URL; ?>comercio/js/agregarProducto.js"></script>