<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';

$numero_pedido = htmlspecialchars($_GET['pedido'] ?? 'N/A');

?>
<style>
    .confirmacion-container {
        text-align: center;
        padding: 50px;
        margin: 50px auto;
        max-width: 600px;
        background-color: #f9f4ef;
        border-radius: 15px;
    }
    .confirmacion-container h1 {
        color: #6b2b2b;
    }
    .confirmacion-container p {
        font-size: 1.2em;
    }
    .confirmacion-container a {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #6b2b2b;
        color: white;
        text-decoration: none;
        border-radius: 8px;
    }
</style>

<div class="layout">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/menuLateral.php';?>
    <link rel="stylesheet" href="/Zava/css/mostrarReceta.css">
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
