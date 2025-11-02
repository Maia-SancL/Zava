<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <title>Zava</title>
    <link rel="stylesheet" href="/Zava/css/public/general.css">
    <link rel="stylesheet" href="/Zava/css/public/accesoUsuario.css">

</head>

<body>
    <main class="contenedor-principal">
        <section class="contenedor-general registrarse">
            <div class="imagen-titulo">
                <img src="/Zava/css/recursos/logos/Principal 2.0.png">
            </div>
            <form class="contenedor-form" action="inicioSesion.php" method="POST">
                <h6 class="media-negrita color-primario">Registrarse</h6>
                <div class="contenedor-inputs">

                    <div class="contenedor-input-doble">
                        <div class="contenedor-input">
                            <p class="pequenio medium color-primario">Nombre</p>
                            <input type="text" class="input input-nombre" name="nombre" maxlength="50" required>
                        </div>
                        <div class="contenedor-input">
                            <p class="pequenio medium color-primario">Apellido</p>
                            <input type="text" class="input input-apellido" name="contrasenia" maxlength="50" required>
                        </div>
                    </div>

                    <div class="contenedor-input">
                        <p class="pequenio medium color-primario">Nombre usuario</p>
                        <input type="password" class="input input-nombreDeUsuario" name="nombreDeUsuario" maxlength="50"
                            required>
                    </div>

                    <div class="contenedor-input">
                        <p class="pequenio medium color-primario">Email</p>
                        <input type="email" class="input input-email" name="correo" maxlength="100" required>
                    </div>

                    <div class="contenedor-input">
                        <p class="pequenio medium color-primario">Contraseña</p>
                        <input type="password" class="input input-contrasenia" name="contrasenia" maxlength="255"
                            required>
                    </div>

                    <div class="contenedor-input">
                        <p class="pequenio medium color-primario">Confirmar contraseña</p>
                        <input type="password" class="input input-contrasenia" name="confirmarContrasenia"
                            maxlength="255" required>
                    </div>
                </div>
                <a href="iniciarSesion.php" class="pequenio light color-primario vinculo-registrarse">¿Ya tienes cuenta? <b class="color-primario">Iniciar sesión</b></a>
                <button type="submit" class="btn btn-enviar-formulario pequenio color-secundario">Registrarse</button>
            </form>
        </section>
    </main>
</body>

</html>