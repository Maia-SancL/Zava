<?php 
include_once 'php/componentes/header.php';
?>
<form action="register.php" method="post">
    <input type="hidden" name="rol" value="2">
    <input type="submit" value="cliente">
</form>
<form action="register.php" method="post">
    <input type="hidden" name="rol" value="3">
    <input type="submit" value="vendedor">
</form>
