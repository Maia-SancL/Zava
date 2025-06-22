<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/header.php';
?>
<form action="registrarCuenta.php" method="post">
    <input type="hidden" name="rol" value="2">
    <button type="submit" class="btn-cliente">Cliente</button>
</form>
<form action="registrarCuenta.php" method="post">
    <input type="hidden" name="rol" value="3">
    <button type="submit" class="btn-vendedor">Vendedor</button>
</form>