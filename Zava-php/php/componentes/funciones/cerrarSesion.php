<?php
session_start();
session_destroy();
header("Location: /Zava-php/index.php");
exit;
?>