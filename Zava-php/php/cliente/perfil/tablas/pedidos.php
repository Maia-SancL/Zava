<?php
$q = "SELECT * FROM pedidos WHERE id_usuario = $id_usuario ORDER BY fecha_pedido DESC";
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
                    <span class="lbl-informacion"><?= $ped['fecha_pedido'] ?></span>
                    <span class="lbl-informacion"><?= isset($ped['estado']) ? htmlspecialchars($ped['estado']) : '' ?></span>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
    <?php if (mysqli_num_rows($res) === 0): ?>
        <article class="producto">
            <div class="informacion-principal">
                <div class="informacion">
                    <span class="lbl-informacion">No tienes pedidos registrados.</span>
                </div>
            </div>
        </article>
    <?php endif; ?>
</section>