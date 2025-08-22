<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';

$numero_pedido = htmlspecialchars($_GET['pedido'] ?? 'N/A');

?>
<link rel="stylesheet" href="/Zava/css/general/confirmacion.css">
<div class="layout">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/menuLateral.php';?>
    <main>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';?>
        <div class="confirmacion-container">
            <h1>¡Gracias por tu compra!</h1>
            <p>Tu pedido ha sido confirmado.</p>
            <p><strong>Número de pedido:</strong> <?php echo $numero_pedido; ?></p>
            <a href="/Zava/index.php">Volver al inicio</a>
        </div>
    </main>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/footer.php';?>
