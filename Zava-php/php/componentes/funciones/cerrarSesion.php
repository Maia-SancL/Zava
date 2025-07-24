<?php
session_start();
session_destroy();
echo '
<form id="postRedirect" action="/Zava-php/php/componentes/pantallaCarga.php" method="POST">
    <input type="hidden" name="mensaje" value="Cerrando sesión...">
    <input type="hidden" name="destino" value="/Zava-php/index.php">
</form>
<script>document.getElementById("postRedirect").submit();</script>';
exit;
?>