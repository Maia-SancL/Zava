<?php
$query_pedidos = "SELECT numero_pedido, fecha_pedido, total, estado FROM Pedidos WHERE id_usuario = $id_usuario ORDER BY fecha_pedido DESC";
$result_pedidos = mysqli_query($conexion, $query_pedidos);
?>

<style>
.info-pedido {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.info-pedido span {
    font-size: 1em;
}
.estado {
    font-weight: bold;
}
</style>

<section class="section-tabla-productos">
    <?php if (mysqli_num_rows($result_pedidos) > 0): ?>
        <?php while ($pedido = mysqli_fetch_assoc($result_pedidos)):
            $estado_clase = strtolower(str_replace(' ', '-', $pedido['estado']));
        ?>
            <article class="producto">
                <div class="informacion-principal">
                    <div class="informacion info-pedido">
                        <span class="id"><b>Pedido:</b> #<?= htmlspecialchars($pedido['numero_pedido']) ?></span>
                        <span class="fecha"><b>Fecha:</b> <?= htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['fecha_pedido']))) ?></span>
                        <span class="total"><b>Total:</b> $<?= htmlspecialchars(number_format($pedido['total'], 2)) ?></span>
                        <span class="estado estado-<?= $estado_clase ?>"><b>Estado:</b> <?= htmlspecialchars(ucfirst($pedido['estado'])) ?></span>
                    </div>
                    <div class="btns">
                        <a href="<?php echo BASE_URL; ?>pedido/detalle?numero_pedido=<?= $pedido['numero_pedido'] ?>" class="btn-ver">Ver Detalle</a>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No has realizado ningún pedido todavía.</p>
    <?php endif; ?>
</section>
