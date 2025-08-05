<?php ?>
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
    <?php while ($receta = mysqli_fetch_assoc($result)): ?>
        <article class="producto" style="cursor:pointer;" onclick="if(event.target.tagName !== 'BUTTON' && event.target.tagName !== 'FORM' && event.target.tagName !== 'A'){ window.location.href='/Zava/php/cliente/mostrarReceta.php?id=<?= $receta['id_receta'] ?>'; }">
            <div class="informacion-principal">
                <div class="informacion">
                    <span class="lbl-informacion">
                        <img src="<?= !empty($receta['imagen']) ? '/Zava/php/cliente/' . $receta['imagen'] : '/Zava/css/recursos/galletitas-receta-2.jpg' ?>" alt="Imagen receta" style="width:60px;height:40px;object-fit:cover;border-radius:8px;">
                    </span>
                    <span class="lbl-informacion"><?= htmlspecialchars($receta['nombre']) ?></span>
                    <span class="lbl-informacion"><?= ucfirst(htmlspecialchars($receta['tipo_comida'])) ?></span>
                    <span class="lbl-informacion"><?= formatoTiempo($receta['tiempo_preparacion']) ?></span>
                    <span class="lbl-informacion"><?= isset($receta['fecha_publicacion']) && $receta['fecha_publicacion'] ? date('d/m/Y', strtotime($receta['fecha_publicacion'])) : '' ?></span>
                    <div class="btns">
                        <a href="/Zava/php/cliente/perfil/modificarReceta.php?id=<?= $receta['id_receta'] ?>" class="btn-modificar" onclick="event.stopPropagation();">✏️ Modificar</a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta receta?');" onclick="event.stopPropagation();">
                            <input type="hidden" name="eliminar_receta" value="<?= $receta['id_receta'] ?>">
                            <button type="submit" class="btn-eliminar">🗑️ Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
    <?php if (mysqli_num_rows($result) === 0): ?>
        <article class="producto">
            <div class="informacion-principal">
                <div class="informacion">
                    <span class="lbl-informacion">No has creado ninguna receta aún.</span>
                </div>
            </div>
        </article>
    <?php endif; ?>
</section>