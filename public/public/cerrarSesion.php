<?php
session_start();
session_destroy();
echo '
<form id="postRedirect" action="/Zava/public/public/pantallaCarga.php" method="POST">
    <input type="hidden" name="mensaje" value="Cerrando sesión...">
    <input type="hidden" name="destino" value="/Zava/inicio">
</form>
<script>document.getElementById("postRedirect").submit();</script>';
exit;
?>