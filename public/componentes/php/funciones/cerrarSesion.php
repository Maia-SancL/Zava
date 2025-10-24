<?php
session_start();
session_destroy();
echo '
<form id="postRedirect" action="/Zava/php/componentes/pantallaCarga.php" method="POST">
    <input type="hidden" name="mensaje" value="Cerrando sesión...">
    <input type="hidden" name="destino" value="/Zava/index.php">
</form>
<script>document.getElementById("postRedirect").submit();</script>';
exit;
?>