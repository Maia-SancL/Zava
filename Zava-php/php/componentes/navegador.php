<?php

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// Para probar diferentes sesiones:
//$_SESSION['tipo_usuario'] = 'cliente';
//$_SESSION['tipo_usuario'] = 'vendedor';
// $_SESSION['tipo_usuario'] = 'administrador';
// Para probar sin sesion iniciada:
//unset($_SESSION['tipo_usuario']);

if (isset($_SESSION['tipo_usuario'])):  // Verificamos si existe una sesión iniciada con un tipo de usuario definido 
    if ($_SESSION['tipo_usuario'] === 'cliente'): //Dependiendo del tipo de usuario, mostramos un menú personalizado
            include_once('conexion.php');
            // Verificar si el usuario ha iniciado sesión
        if (isset($_SESSION['id'])) {
            $id_usuario = $_SESSION['id'];

            // Consulta para obtener los datos del usuario
            $query = "SELECT nombre, nickname, foto FROM usuarios WHERE id_usuario = $id_usuario";
            $resultado = mysqli_query($conexion, $query);

            if ($resultado && mysqli_num_rows($resultado) > 0) {
            $usuario = mysqli_fetch_assoc($resultado);
            $nombre = htmlspecialchars($usuario['nombre']);
            $nickname = htmlspecialchars($usuario['nickname']);
            $foto = $usuario['foto'] ? htmlspecialchars($usuario['foto']) : 'perfil.png';
            } else {
                $nombre = 'Usuario';
                $nickname = 'Usuario';
                $foto = 'perfil.png';
            }
        } else {
    $nombre = 'Usuario';
    $nickname = 'Usuario';
    $foto = 'perfil.png';
}
?>
<!-- Menú para clientes -->
 <link rel="stylesheet" href="/Zava-php/css/navegador.css">
        <nav class="nav">
            <div class="barra-buscar">
                <div class="btn-buscar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="nav-icono"fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/></svg>
                </div>
                <input type="text" placeholder="Buscar" required>
            </div>
            <div class="cont-btns">
                <ul class="nav-lista-btns">
                    <li class="btns"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="nav-icono" fill="currentColor" fill-rule="evenodd" d="M10 2.25a1.75 1.75 0 0 0-1.582 1c-.684.006-1.216.037-1.692.223A3.25 3.25 0 0 0 5.3 4.563c-.367.493-.54 1.127-.776 1.998l-.047.17l-.513 2.964q-.277.191-.486.459c-.901 1.153-.472 2.87.386 6.301c.545 2.183.818 3.274 1.632 3.91C6.31 21 7.435 21 9.685 21h4.63c2.25 0 3.375 0 4.189-.635c.814-.636 1.086-1.727 1.632-3.91c.858-3.432 1.287-5.147.386-6.301a2.2 2.2 0 0 0-.487-.46l-.513-2.962l-.046-.17c-.237-.872-.41-1.506-.776-2a3.25 3.25 0 0 0-1.426-1.089c-.476-.186-1.009-.217-1.692-.222A1.75 1.75 0 0 0 14 2.25zm8.418 6.896l-.362-2.088c-.283-1.04-.386-1.367-.56-1.601a1.75 1.75 0 0 0-.768-.587c-.22-.086-.486-.111-1.148-.118A1.75 1.75 0 0 1 14 5.75h-4a1.75 1.75 0 0 1-1.58-.998c-.663.007-.928.032-1.148.118a1.75 1.75 0 0 0-.768.587c-.174.234-.277.56-.56 1.6l-.362 2.089C6.58 9 7.91 9 9.685 9h4.63c1.775 0 3.105 0 4.103.146M8 12.25a.75.75 0 0 1 .75.75v4a.75.75 0 0 1-1.5 0v-4a.75.75 0 0 1 .75-.75m8.75.75a.75.75 0 0 0-1.5 0v4a.75.75 0 0 0 1.5 0zM12 12.25a.75.75 0 0 1 .75.75v4a.75.75 0 0 1-1.5 0v-4a.75.75 0 0 1 .75-.75" clip-rule="evenodd"/></svg></li>

                    <li class="btns"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g class="nav-icono" fill="none"><path d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z"/><path class="nav-icono" fill="currentColor" d="M12 2a7 7 0 0 0-7 7v3.528a1 1 0 0 1-.105.447l-1.717 3.433A1.1 1.1 0 0 0 4.162 18h15.676a1.1 1.1 0 0 0 .984-1.592l-1.716-3.433a1 1 0 0 1-.106-.447V9a7 7 0 0 0-7-7m0 19a3 3 0 0 1-2.83-2h5.66A3 3 0 0 1 12 21"/></g></svg></li>

                    <li class="btns user-dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" onclick="bandejaUsuario(event)"><circle cx="12" cy="6" r="4" fill="currentColor " class="nav-icono"/><path class="nav-icono"fill="currentColor" d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5"/></svg>
                        <ul class="user-dropdown-content">
                            <li>
                                <div class="user-info">
                                    <div class="cont-user-img">
                                        <img src="<?= isset($_SESSION['foto']) ? $_SESSION['foto'] : 'perfil.png' ?>" alt="Foto de perfil" class="user-avatar">
                                    </div> 
                                    <span class="user-name"><?= isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Usuario' ?></span>
                                    <span class="user-username"><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Usuario' ?></span>
                                </div>
                            </li>
                            <li>
                                <div class="btn-dropdown perfil">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" onclick="bandejaUsuario(event)"><circle cx="12" cy="6" r="4" fill="currentColor " class="nav-icono"/><path class="nav-icono"fill="currentColor" d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5"/></svg>
                                    <a onclick="location.href='perfilInicio.php'" class="profile-btn">Ir a mi perfil</a>
                                </div>
                             </li>
                             <li>
                                <div class="btn-dropdown logout">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="nav-icono" fill="currentColor" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h6q.425 0 .713.288T12 4t-.288.713T11 5H5v14h6q.425 0 .713.288T12 20t-.288.713T11 21zm12.175-8H10q-.425 0-.712-.288T9 12t.288-.712T10 11h7.175L15.3 9.125q-.275-.275-.275-.675t.275-.7t.7-.313t.725.288L20.3 11.3q.3.3.3.7t-.3.7l-3.575 3.575q-.3.3-.712.288t-.713-.313q-.275-.3-.262-.712t.287-.688z"/></svg>
                                    <form action="cerrarSesion.php" method="POST"> 
                                        <button type="submit" class="logout-btn">Cerrar sesión</button>
                                    </form>
                                </div>
                             </li>
                        </ul>
                        
                    
                    
                    </li>
                


                </ul>
                <div class="cont-btn-agregar-receta">
                    <button class="btn-agregar-receta"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="btn-agregar-icono"fill="currentColor" d="M11 13H6q-.425 0-.712-.288T5 12t.288-.712T6 11h5V6q0-.425.288-.712T12 5t.713.288T13 6v5h5q.425 0 .713.288T19 12t-.288.713T18 13h-5v5q0 .425-.288.713T12 19t-.712-.288T11 18z"/></svg>Crear</button>
                </div>
            </div>

        </nav>



































    <?php elseif ($_SESSION['tipo_usuario'] === 'vendedor'): ?>
    <!-- Menú para vendedores -->
        Navegador Vendedor | Inicio | Ventas | Inventario | Perfil | Cerrar sesión
    <?php elseif ($_SESSION['tipo_usuario'] === 'administrador'): ?>
    <!-- Menú para administradores -->
        Navegador Administrador | Inicio | Usuarios | Reportes | Configuración | Cerrar sesión

    <?php endif;
else: ?>
<link rel="stylesheet" href="/Zava-php/css/navegador.css">
        <nav class="nav">
            <div class="barra-buscar">
                <div class="btn-buscar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="nav-icono"fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/></svg>
                </div>
                <input type="text" placeholder="Buscar" required>
            </div>
            <div class="cont-btns">
                <ul class="nav-lista-btns">
                    <li class="btns">
                        <button class="iniciar-sesion" onclick="location.href='/Zava-php/php/general/inicioSesion.php'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon-iniciar-sesion" fill="currentColor" d="M12 2a5 5 0 1 1-5 5l.005-.217A5 5 0 0 1 12 2m2 12a5 5 0 0 1 5 5v1a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-1a5 5 0 0 1 5-5z"/></svg>
                            Iniciar sesión
                        </button>
                    </li>

                    <li class="btns"> 
                        <button class="registrarse" onclick="location.href='/Zava-php/php/general/diferenciacionRegistro.php'">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g class="icon-registrarse" fill="none"><path class="icon-registrarse" d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path class="icon-registrarse"  fill="currentColor" d="M16 14a5 5 0 0 1 5 5v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1a5 5 0 0 1 5-5zm4-6a1 1 0 0 1 1 1v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 1 1 0-2h1V9a1 1 0 0 1 1-1m-8-6a5 5 0 1 1 0 10a5 5 0 0 1 0-10"/></g></svg>
                            Registrarse
                            
                        </button> 
                    </li>

                </ul>
            </div>
        </nav>
        

<?php endif; ?>
