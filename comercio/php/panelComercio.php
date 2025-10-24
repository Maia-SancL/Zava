<?php 
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/componentes/php/header.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Zava/administrador/php/conexion.php';
?>
<link rel="stylesheet" href="/Zava/comercio/css/comercio-productos.css">
    <main>
        <section class="section-titulo-btn">
            <h2 class="subtitulo">Productos</h2>
            <a href="<?php echo BASE_URL; ?>comercio/productos/agregar" class="btn-agregar-producto">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M11 13H6q-.425 0-.712-.288T5 12t.288-.712T6 11h5V6q0-.425.288-.712T12 5t.713.288T13 6v5h5q.425 0 .713.288T19 12t-.288.713T18 13h-5v5q0 .425-.288.713T12 19t-.712-.288T11 18z"/></svg>
                Agregar producto
            </a>
        </section>
        <section class="section-tabla-productos">
            <?php
            if (isset($_SESSION['id'])) {
                $id_usuario = $_SESSION['id'];
                $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                        FROM Productos p 
                        LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria
                        WHERE p.id_usuario = $id_usuario 
                        ORDER BY p.id_producto DESC";
                
                $resultado = mysqli_query($conexion, $sql);

                if (mysqli_num_rows($resultado) > 0) {
                    while ($producto = mysqli_fetch_assoc($resultado)) {
                        $precio_final = $producto['descuento'] && $producto['porcentaje_descuento'] > 0
                            ? $producto['precio'] * (1 - $producto['porcentaje_descuento'] / 100)
                            : $producto['precio'];
                        
                        $estado = $producto['activo'] ? 'Activa' : 'Pausada';
                        ?>
                        <article class="producto">
                            <div class="informacion-principal">
                                <button class="btn-desplegable">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M4 18q-.425 0-.712-.288T3 17t.288-.712T4 16h16q.425 0 .713.288T21 17t-.288.713T20 18zm0-5q-.425 0-.712-.288T3 12t.288-.712T4 11h16q.425 0 .713.288T21 12t-.288.713T20 13zm0-5q-.425 0-.712-.288T3 7t.288-.712T4 6h16q.425 0 .713.288T21 7t-.288.713T20 8z"/></svg>
                                </button>
                                <div class="informacion">
                                    <span class="nombre"><?= htmlspecialchars($producto['nombre']) ?></span>
                                    <span class="precio">$<?= number_format($precio_final, 2, ',', '.') ?></span>
                                    <span class="stock"><?= htmlspecialchars($producto['stock']) ?></span>
                                    <span class="estado"><?= $estado ?></span>
                                </div>
                                <div class="btns">
                                    <a href="<?php echo BASE_URL; ?>comercio/productos/editar?id=<?= $producto['id_producto'] ?>" class="btn-modificar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path stroke="currentColor" class="icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 16l-1 4l4-1L19.586 7.414a2 2 0 0 0 0-2.828l-.172-.172a2 2 0 0 0-2.828 0z"/><path class="icon" fill="currentColor" d="m5 16l-1 4l4-1L18 9l-3-3z"/><path class="icon" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 6l3 3m-5 11h8"/></g></svg>
                                    </a>
                                    <form action="<?php echo BASE_URL; ?>comercio/productos/eliminar" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este producto?');" style="display: inline;">
                                        <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                                        <button type="submit" class="btn-eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zm3-4q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17"/></svg>
                                        </button>
                                    </form>
                                    <form action="<?php echo BASE_URL; ?>comercio/productos/stock/agregar" method="POST" class="form-agregar-stock" style="display: inline;">
                                        <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                                        <input type="hidden" name="cantidad" class="cantidad-a-agregar" value="">
                                        <button type="submit" class="btn-agregar-stock" title="Agregar Stock">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path  class="icon" fill="currentColor" d="M11 13H6q-.425 0-.712-.288T5 12t.288-.712T6 11h5V6q0-.425.288-.712T12 5t.713.288T13 6v5h5q.425 0 .713.288T19 12t-.288.713T18 13h-5v5q0 .425-.288-.713T12 19t-.712-.288T11 18z"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="detalles-producto oculto">
                                <div class="cont-descripcion">
                                    <h5>Descripción</h5>
                                    <p class="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></p>
                                </div>
                                <?php if ($producto['descuento'] && $producto['porcentaje_descuento'] > 0): ?>
                                <div class="cont-oferta">
                                    <span class="descuento">Oferta: <?= htmlspecialchars($producto['porcentaje_descuento']) ?>% off</span>
                                    <span class="precio-original">Precio original: $<?= number_format($producto['precio'], 2, ',', '.') ?></span>
                                </div>
                                <?php endif; ?>
                                <span class="tipo-producto"><?= htmlspecialchars($producto['categoria_nombre']) ?></span>
                                <span class="peso"><?= htmlspecialchars($producto['peso']) ?>g</span>
                                <div class="cont-img">
                                    <img src="/Zava/public/img/productos/<?= htmlspecialchars($producto['imagen_principal']) ?>" alt="Imagen de <?= htmlspecialchars($producto['nombre']) ?>">
                                </div>
                            </div>
                        </article>
                    <?php }
                } else {
                    echo "<p>Aún no has agregado ningún producto.</p>";
                }
                mysqli_close($conexion);
            } else {
                echo "<p>Inicia sesión para ver tus productos.</p>";
            }
            ?>
        </section>
    </main>
    <script src="/Zava/comercio/js/panelComercio.js"></script>
</body>
</html>