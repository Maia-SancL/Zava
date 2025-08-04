
<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/navegador.php';
include_once('conexion.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];
$tabla = $_GET['tabla'] ?? 'inicio';

// Datos usuario
$query_usuario = "SELECT nombre, apellido, nickname, foto FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);
$nombre = htmlspecialchars($usuario['nombre']);
$apellido = htmlspecialchars($usuario['apellido']);
$nickname = htmlspecialchars($usuario['nickname']);
$foto = $usuario['foto'] ? htmlspecialchars($usuario['foto']) : 'perfil.png';

function formatoTiempo($tiempo) {
    if (!$tiempo) return '';
    $partes = explode(':', $tiempo);
    $horas = intval($partes[0]);
    $minutos = intval($partes[1]);
    if ($horas > 0) {
        return $horas . ' hr' . ($horas > 1 ? 's' : '') . ($minutos > 0 ? " $minutos min" : '');
    } else {
        return $minutos . ' min';
    }
}
?>
<link rel="stylesheet" href="/Zava-php/css/perfil-recetas.css">
<main>
    <div class="perfil">
        <div class="perfil-foto">
            <img src="<?= $foto ?>" alt="Foto de perfil">
        </div>
        <div class="perfil-info">
            <h1 class="perfil-nombre"><?= $nombre ?> <?= $apellido ?></h1>
            <p class="perfil-nickname">@<?= $nickname ?></p>
        </div>
        <button class="btn-editar-perfil" onclick="location.href='perfilEditar.php'">Editar perfil</button>
    </div>
    <div class="seleccionar-tipo-tabla" style="margin-bottom:2rem;">
        <a href="?tabla=favoritos" class="tabla <?= $tabla === 'favoritos' ? 'seleccionado' : '' ?>">Favoritos</a>
        <a href="?tabla=historial" class="tabla <?= $tabla === 'historial' ? 'seleccionado' : '' ?>">Ultimo Visto</a>
        <a href="?tabla=recetas" class="tabla <?= $tabla === 'recetas' ? 'seleccionado' : '' ?>">Mis Recetas</a>
        <a href="?tabla=pedidos" class="tabla <?= $tabla === 'pedidos' ? 'seleccionado' : '' ?>">Mis Pedidos</a>
        <a href="?tabla=opiniones" class="tabla <?= $tabla === 'opiniones' ? 'seleccionado' : '' ?>">Mis Opiniones</a>
        <a href="?tabla=reseñas" class="tabla <?= $tabla === 'reseñas' ? 'seleccionado' : '' ?>">Mis Reseñas</a>
    </div>

    <?php
    switch ($tabla) {
        case 'inicio':
            ?>
            <div class="perfil-secciones">
                <div class="seccion" onclick="location.href='perfilInicio.php?tabla=favoritos&tipo=recetas'">
                    <h2>Recetas favoritas</h2>
                </div>
                <div class="seccion" onclick="location.href='perfilInicio.php?tabla=favoritos&tipo=restaurantes'">
                    <h2>Restaurantes favoritos</h2>
                </div>
                <div class="seccion" onclick="location.href='perfilInicio.php?tabla=favoritos&tipo=productos'">
                    <h2>Productos favoritos</h2>
                </div>
            </div>
            <?php
            break;

        case 'favoritos':
            $tipo = $_GET['tipo'] ?? 'recetas';
            ?>
            <div class="seleccionar-tipo-tabla" style="margin-bottom:1rem;">
                <a href="?tabla=favoritos&tipo=recetas" class="tabla <?= $tipo === 'recetas' ? 'seleccionado' : '' ?>">Recetas</a>
                <a href="?tabla=favoritos&tipo=restaurantes" class="tabla <?= $tipo === 'restaurantes' ? 'seleccionado' : '' ?>">Restaurantes</a>
                <a href="?tabla=favoritos&tipo=productos" class="tabla <?= $tipo === 'productos' ? 'seleccionado' : '' ?>">Productos</a>
            </div>
            <?php
            if ($tipo === 'recetas') {
                $q = "SELECT r.id_receta, r.nombre, r.descripcion, r.imagen, r.tiempo_preparacion
                      FROM favoritos_recetas f
                      JOIN recetas r ON f.id_receta = r.id_receta
                      WHERE f.id_usuario = $id_usuario";
                $res = mysqli_query($conexion, $q);
                ?>
                <section class="section-tabla-productos">
                    <article class="producto guia">
                        <div class="informacion-principal">
                            <div class="informacion">
                                <span class="lbl-informacion">Imagen</span>
                                <span class="lbl-informacion">Nombre</span>
                                <span class="lbl-informacion">Tiempo</span>
                                <span class="lbl-informacion">Acciones</span>
                            </div>
                        </div>
                    </article>
                    <?php while ($receta = mysqli_fetch_assoc($res)): ?>
                        <article class="producto" style="cursor:pointer;" onclick="if(event.target.tagName !== 'BUTTON' && event.target.tagName !== 'FORM'){ window.location.href='/Zava-php/php/cliente/mostrarReceta.php?id=<?= $receta['id_receta'] ?>'; }">
                            <div class="informacion-principal">
                                <div class="informacion">
                                    <span class="lbl-informacion">
                                        <img src="<?= !empty($receta['imagen']) ? '/Zava-php/php/cliente/' . $receta['imagen'] : '/Zava-php/css/recursos/galletitas-receta-2.jpg' ?>" alt="Imagen receta" style="width:60px;height:40px;object-fit:cover;border-radius:8px;">
                                    </span>
                                    <span class="lbl-informacion"><?= htmlspecialchars($receta['nombre']) ?></span>
                                    <span class="lbl-informacion"><?= formatoTiempo($receta['tiempo_preparacion']) ?></span>
                                    <div class="btns">
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Quitar de favoritos?');" onclick="event.stopPropagation();">
                                            <input type="hidden" name="eliminar_favorito_receta" value="<?= $receta['id_receta'] ?>">
                                            <button type="submit" class="btn-eliminar">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    <?php if (mysqli_num_rows($res) === 0): ?>
                        <article class="producto">
                            <div class="informacion-principal">
                                <div class="informacion">
                                    <span class="lbl-informacion">No tienes recetas favoritas.</span>
                                </div>
                            </div>
                        </article>
                    <?php endif; ?>
                </section>
                <?php
            } elseif ($tipo === 'restaurantes') {
                $q = "SELECT r.id_restaurante, r.nombre, r.descripcion, r.imagen
                      FROM favoritos_restaurantes f
                      JOIN restaurantes r ON f.id_restaurante = r.id_restaurante
                      WHERE f.id_usuario = $id_usuario";
                $res = mysqli_query($conexion, $q);
                ?>
                <section class="section-tabla-productos">
                    <article class="producto guia">
                        <div class="informacion-principal">
                            <div class="informacion">
                                <span class="lbl-informacion">Imagen</span>
                                <span class="lbl-informacion">Nombre</span>
                                <span class="lbl-informacion">Acciones</span>
                            </div>
                        </div>
                    </article>
                    <?php while ($rest = mysqli_fetch_assoc($res)): ?>
                        <article class="producto" style="cursor:pointer;" onclick="if(event.target.tagName !== 'BUTTON' && event.target.tagName !== 'FORM'){ window.location.href='/Zava-php/php/cliente/mostrarRestaurante.php?id=<?= $rest['id_restaurante'] ?>'; }">
                            <div class="informacion-principal">
                                <div class="informacion">
                                    <span class="lbl-informacion">
                                        <img src="<?= !empty($rest['imagen']) ? '/Zava-php/php/cliente/' . $rest['imagen'] : '/Zava-php/css/recursos/restaurante-default.jpg' ?>" alt="Imagen restaurante" style="width:60px;height:40px;object-fit:cover;border-radius:8px;">
                                    </span>
                                    <span class="lbl-informacion"><?= htmlspecialchars($rest['nombre']) ?></span>
                                    <div class="btns">
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Quitar de favoritos?');" onclick="event.stopPropagation();">
                                            <input type="hidden" name="eliminar_favorito_restaurante" value="<?= $rest['id_restaurante'] ?>">
                                            <button type="submit" class="btn-eliminar">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    <?php if (mysqli_num_rows($res) === 0): ?>
                        <article class="producto">
                            <div class="informacion-principal">
                                <div class="informacion">
                                    <span class="lbl-informacion">No tienes restaurantes favoritos.</span>
                                </div>
                            </div>
                        </article>
                    <?php endif; ?>
                </section>
                <?php
            } else {
                $q = "SELECT p.id_producto, p.nombre, p.descripcion, p.imagen, p.precio
                      FROM favoritos_productos f
                      JOIN productos p ON f.id_producto = p.id_producto
                      WHERE f.id_usuario = $id_usuario";
                $res = mysqli_query($conexion, $q);
                ?>
                <section class="section-tabla-productos">
                    <article class="producto guia">
                        <div class="informacion-principal">
                            <div class="informacion">
                                <span class="lbl-informacion">Imagen</span>
                                <span class="lbl-informacion">Nombre</span>
                                <span class="lbl-informacion">Precio</span>
                                <span class="lbl-informacion">Acciones</span>
                            </div>
                        </div>
                    </article>
                    <?php while ($prod = mysqli_fetch_assoc($res)): ?>
                        <article class="producto" style="cursor:pointer;" onclick="if(event.target.tagName !== 'BUTTON' && event.target.tagName !== 'FORM'){ window.location.href='/Zava-php/php/cliente/mostrarProducto.php?id=<?= $prod['id_producto'] ?>'; }">
                            <div class="informacion-principal">
                                <div class="informacion">
                                    <span class="lbl-informacion">
                                        <img src="<?= !empty($prod['imagen']) ? '/Zava-php/php/cliente/' . $prod['imagen'] : '/Zava-php/css/recursos/producto-default.jpg' ?>" alt="Imagen producto" style="width:60px;height:40px;object-fit:cover;border-radius:8px;">
                                    </span>
                                    <span class="lbl-informacion"><?= htmlspecialchars($prod['nombre']) ?></span>
                                    <span class="lbl-informacion">$<?= number_format($prod['precio'], 2) ?></span>
                                    <div class="btns">
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Quitar de favoritos?');" onclick="event.stopPropagation();">
                                            <input type="hidden" name="eliminar_favorito_producto" value="<?= $prod['id_producto'] ?>">
                                            <button type="submit" class="btn-eliminar">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    <?php if (mysqli_num_rows($res) === 0): ?>
                        <article class="producto">
                            <div class="informacion-principal">
                                <div class="informacion">
                                    <span class="lbl-informacion">No tienes productos favoritos.</span>
                                </div>
                            </div>
                        </article>
                    <?php endif; ?>
                </section>
                <?php
            }
            break;

        case 'historial':
            $q = "SELECT hv.*, 
                        CASE hv.tipo_contenido
                            WHEN 'receta' THEN r.nombre
                            WHEN 'restaurante' THEN res.nombre
                            WHEN 'producto' THEN p.nombre
                        END AS nombre_contenido
                  FROM historial_vistas hv
                  LEFT JOIN recetas r ON hv.tipo_contenido = 'receta' AND hv.id_contenido = r.id_receta
                  LEFT JOIN restaurantes res ON hv.tipo_contenido = 'restaurante' AND hv.id_contenido = res.id_restaurante
                  LEFT JOIN productos p ON hv.tipo_contenido = 'producto' AND hv.id_contenido = p.id_producto
                  WHERE hv.id_usuario = $id_usuario
                  ORDER BY hv.fecha_vista DESC
                  LIMIT 20";
            $res = mysqli_query($conexion, $q);
            ?>
            <section class="section-tabla-productos">
                <article class="producto guia">
                    <div class="informacion-principal">
                        <div class="informacion">
                            <span class="lbl-informacion">Nombre</span>
                            <span class="lbl-informacion">Tipo</span>
                            <span class="lbl-informacion">Fecha</span>
                        </div>
                    </div>
                </article>
                <?php while ($h = mysqli_fetch_assoc($res)): ?>
                    <article class="producto">
                        <div class="informacion-principal">
                            <div class="informacion">
                                <span class="lbl-informacion"><?= htmlspecialchars($h['nombre_contenido']) ?></span>
                                <span class="lbl-informacion"><?= ucfirst($h['tipo_contenido']) ?></span>
                                <span class="lbl-informacion"><?= $h['fecha_vista'] ?></span>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </section>
            <?php
            break;

        case 'recetas':
            // Eliminar receta propia
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_receta'])) {
                $id_receta = intval($_POST['eliminar_receta']);
                $img_q = mysqli_query($conexion, "SELECT imagen FROM recetas WHERE id_receta = $id_receta AND id_usuario = $id_usuario");
                if ($img_row = mysqli_fetch_assoc($img_q)) {
                    $ruta_img = $img_row['imagen'];
                    if ($ruta_img && file_exists($_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/cliente/' . $ruta_img)) {
                        unlink($_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/cliente/' . $ruta_img);
                    }
                }
                mysqli_query($conexion, "DELETE FROM recetas WHERE id_receta = $id_receta AND id_usuario = $id_usuario");
                header("Location: perfilInicio.php?tabla=recetas");
                exit;
            }
            $q = "SELECT id_receta, nombre, descripcion, imagen, tipo_comida, tiempo_preparacion, fecha_publicacion
                  FROM recetas WHERE id_usuario = $id_usuario ORDER BY id_receta DESC";
            $res = mysqli_query($conexion, $q);
            ?>
            <section class="section-tabla-productos">
                <article class="producto guia">
                    <div class="informacion-principal">
                        <div class="informacion">
                            <span class="lbl-informacion">Imagen</span>
                            <span class="lbl-informacion">Nombre</span>
                            <span class="lbl-informacion">Tipo</span>
                            <span class="lbl-informacion">Tiempo</span>
                            <span class="lbl-informacion">Fecha</span>
                            <span class="lbl-informacion">Acciones</span>
                        </div>
                    </div>
                </article>
                <?php while ($rec = mysqli_fetch_assoc($res)): ?>
                    <article class="producto" style="cursor:pointer;" onclick="if(event.target.tagName !== 'BUTTON' && event.target.tagName !== 'FORM' && event.target.tagName !== 'A'){ window.location.href='/Zava-php/php/cliente/mostrarReceta.php?id=<?= $rec['id_receta'] ?>'; }">
                        <div class="informacion-principal">
                            <div class="informacion">
                                <span class="lbl-informacion">
                                    <img src="<?= !empty($rec['imagen']) ? '/Zava-php/php/cliente/' . $rec['imagen'] : '/Zava-php/css/recursos/galletitas-receta-2.jpg' ?>" alt="Imagen receta" style="width:60px;height:40px;object-fit:cover;border-radius:8px;">
                                </span>
                                <span class="lbl-informacion"><?= htmlspecialchars($rec['nombre']) ?></span>
                                <span class="lbl-informacion"><?= ucfirst(htmlspecialchars($rec['tipo_comida'])) ?></span>
                                <span class="lbl-informacion"><?= formatoTiempo($rec['tiempo_preparacion']) ?></span>
                                <span class="lbl-informacion"><?= isset($rec['fecha_publicacion']) && $rec['fecha_publicacion'] ? date('d/m/Y', strtotime($rec['fecha_publicacion'])) : '' ?></span>
                                <div class="btns">
                                    <a href="/Zava-php/php/cliente/perfil/modificarReceta.php?id=<?= $rec['id_receta'] ?>" class="btn-modificar" onclick="event.stopPropagation();">✏️ Modificar</a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta receta?');" onclick="event.stopPropagation();">
                                        <input type="hidden" name="eliminar_receta" value="<?= $rec['id_receta'] ?>">
                                        <button type="submit" class="btn-eliminar">🗑️ Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($res) === 0): ?>
                    <article class="producto">
                        <div class="informacion-principal">
                            <div class="informacion">
                                <span class="lbl-informacion">No has creado ninguna receta aún.</span>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>
            </section>
            <?php
            break;

        case 'pedidos':
            $q = "SELECT * FROM pedidos WHERE id_usuario = $id_usuario ORDER BY fecha DESC";
            $res = mysqli_query($conexion, $q);
            ?>
            <section class="section-tabla-productos">
                <article class="producto guia">
                    <div class="informacion-principal">
                        <div class="informacion">
                            <span class="lbl-informacion">Pedido</span>
                            <span class="lbl-informacion">Fecha</span>
                            <span class="lbl-informacion">Estado</span>
                        </div>
                    </div>
                </article>
                <?php while ($ped = mysqli_fetch_assoc($res)): ?>
                    <article class="producto">
                        <div class="informacion-principal">
                            <div class="informacion">
                                <span class="lbl-informacion">#<?= $ped['id_pedido'] ?></span>
                                <span class="lbl-informacion"><?= $ped['fecha'] ?></span>
                                <span class="lbl-informacion"><?= $ped['estado'] ?></span>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </section>
            <?php
            break;

        case 'opiniones':
        include __DIR__ . '/tablas/opiniones.php';
        break;

        case 'reseñas':
            $q = "SELECT * FROM resenas WHERE id_usuario = $id_usuario ORDER BY fecha DESC";
            $res = mysqli_query($conexion, $q);
            ?>
            <section class="section-tabla-productos">
                <article class="producto guia">
                    <div class="informacion-principal">
                        <div class="informacion">
                            <span class="lbl-informacion">Contenido</span>
                            <span class="lbl-informacion">Reseña</span>
                            <span class="lbl-informacion">Fecha</span>
                        </div>
                    </div>
                </article>
                <?php while ($rs = mysqli_fetch_assoc($res)): ?>
                    <article class="producto">
                        <div class="informacion-principal">
                            <div class="informacion">
                                <span class="lbl-informacion"><?= htmlspecialchars($rs['contenido']) ?></span>
                                <span class="lbl-informacion"><?= htmlspecialchars($rs['resena']) ?></span>
                                <span class="lbl-informacion"><?= $rs['fecha'] ?></span>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </section>
            <?php
            break;
    }
    ?>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/footer.php'; ?>