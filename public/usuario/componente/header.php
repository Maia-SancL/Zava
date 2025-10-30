<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <title>Zava</title>
    <link rel="stylesheet" href="/Zava/css/public/general.css">
</head>

<body>
    <main class="layout">
        <div class="overflow-capa oculto" id="overflow-capa"></div>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/usuario/componente/navegadorLateralUsuario.php'; ?>
        <div class="area-principal">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/usuario/componente/navegadorUsuario.php'; ?>