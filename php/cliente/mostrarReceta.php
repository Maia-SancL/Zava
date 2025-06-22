<?php

include_once 'php/componentes/header.php';
include_once('conexion.php');
include_once 'php/componentes/mostrarComentarios.php';

// Obtener el ID de la receta por POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo "<p>Receta no encontrada.</p>";
    exit;
}

// Obtener datos de la receta
$query = "SELECT * FROM recetas WHERE id_receta = $id";
$resultado = mysqli_query($conexion, $query);
$receta = mysqli_fetch_assoc($resultado);

if (!$receta) {
    echo "<p>Receta no encontrada.</p>";
    exit;
}

// Obtener datos del usuario
$id_usuario = $receta['id_usuario'];
$query_usuario = "SELECT nombre, apellido, nickname, foto FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);

// Datos principales
$nombre = $receta['nombre'];
$descripcion = $receta['descripcion'];
$ingredientes = isset($receta['ingredientes']) ? explode(',', $receta['ingredientes']) : [];
$pasos = isset($receta['pasos']) ? explode('.', $receta['pasos']) : [];
$imagenes = isset($receta['imagenes']) ? json_decode($receta['imagenes'], true) : [];
$tiempo = isset($receta['tiempo']) ? intval($receta['tiempo']) : 0;
$tipo_dieta = isset($receta['tipo_dieta']) ? $receta['tipo_dieta'] : '';
$tipo_comida = isset($receta['tipo_comida']) ? $receta['tipo_comida'] : '';
$porciones = isset($receta['porciones']) ? intval($receta['porciones']) : 1;
$foto_usuario = $usuario['foto'] ? $usuario['foto'] : 'perfil.png';

function formatoTiempo($min) {
    return $min < 60 ? "$min min" : (floor($min/60) . " hr" . (($min % 60) ? ' ' . ($min % 60) . ' min' : ''));
}

// --- AGREGAR A FAVORITOS ---
session_start();
$favorito_exito = '';
if (isset($_POST['agregar_favorito']) && isset($_SESSION['id'])) {
    $id_usuario = $_SESSION['id'];
    $id_receta = $id;
    // Evitar duplicados
    $existe = mysqli_query($conexion, "SELECT 1 FROM Favoritos_Recetas WHERE id_usuario=$id_usuario AND id_receta=$id_receta");
    if (!mysqli_fetch_assoc($existe)) {
        mysqli_query($conexion, "INSERT INTO Favoritos_Recetas (id_usuario, id_receta) VALUES ($id_usuario, $id_receta)");
        $favorito_exito = "¡Receta agregada a favoritos!";
    } else {
        $favorito_exito = "Ya está en tus favoritos.";
    }
}
?>

<article class="cont-imagenes-receta">
            <div class="cont-img-izquierda">
                <img src="./css/recursos/galletitas-receta-2.jpg" alt="Imagen 1">
            </div>
            <div class="cont-img-derecha">
                <div class="cont-img">
                    <img src="./css/recursos/galletitas-receta.jpg" alt="Imagen 2">
                </div>
                <div class="cont-img">
                    <img src="./css/recursos/galletitas-receta.jpg" alt="Imagen 3">
                </div>
            </div>
        </article>

        <div class="cont-titulo-receta">
            <h1 class="titulo-receta">Galletitas con chispas de chocolate</h1>
        </div>

        <section class="cont-tags-favorito">
            <article class="cont-tags">
                <div class="tag tiempo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M17 3.34a10 10 0 1 1-14.995 8.984L2 12l.005-.324A10 10 0 0 1 17 3.34M12 6a1 1 0 0 0-.993.883L11 7v5l.009.131a1 1 0 0 0 .197.477l.087.1l3 3l.094.082a1 1 0 0 0 1.226 0l.094-.083l.083-.094a1 1 0 0 0 0-1.226l-.083-.094L13 11.585V7l-.007-.117A1 1 0 0 0 12 6"/></svg>
                    <span class="lbl-tiempo">45min</span>
                </div>
                <div class="tag porciones">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"><circle cx="152" cy="184" r="72" fill="currentColor" class="icon"/><path class="icon" fill="currentColor" d="M234 296c-28.16-14.3-59.24-20-82-20c-44.58 0-136 27.34-136 82v42h150v-16.07c0-19 8-38.05 22-53.93c11.17-12.68 26.81-24.45 46-34"/><path class=icon fill="currentColor" d="M340 288c-52.07 0-156 32.16-156 96v48h312v-48c0-63.84-103.93-96-156-96"/><circle class="icon" cx="340" cy="168" r="88" fill="currentColor"/></svg>
                    <span class="lbl-porciones">3</span>
                </div>
                <div class="tag vegetariana">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"><path  class="icon" fill="currentColor" fill-rule="evenodd" d="M200.736 482.822c2.368 4.849 6.381 10.175 10.645 13.842c5.211 4.481 11.704 7.65 18.576 10.194c4.798 1.776 11.137 3.324 16.264 4.245c5.313.954 11.837.937 17.986.87c10.926-.12 19.55-1.137 29.283-3.508c6.132-1.494 11.957-3.153 16.685-5.013c8.927-3.513 14.619-7.413 15.806-9.943c-.891-4.183-2.293-10.77-3.337-16.69a272 272 0 0 1-2.318-16.001c-1.337-11.6-.649-19.762.833-33.255c2.183-19.873 6.427-28.926 12.624-40.735c5.918-11.28 15.687-27.045 23.216-37.2c7.849-10.588 27.5-31.28 34.668-38.969c2.5.74 7.328 2.35 14.795 3.824c5.69 1.123 12.316 2.462 20.654 2.181c19.852-.668 27.079-4.464 38.627-11.993c10.478-6.83 20.503-20.498 25.752-33.596c4.036-10.07 5.278-20.324 3.522-32.357c-1.182-8.094-3.797-17.679-5.887-24.017c-2.274-6.894-5.843-14.3-8.84-19.903c0 0 12.051-14.4 16.874-26.412c4.152-10.344 6.088-23.654 5.257-31.973c-.862-8.634-8.191-24.474-12.798-30.127c-6.069-7.448-12.002-15.17-19.499-21.224c-7.348-5.936-15.67-10.794-24.385-14.435c-9.031-3.772-19.835-5.956-28.398-7.46c-1.286-6.86-3.058-11.643-7.778-19.897c-5.894-10.6-12.368-15.388-22.543-22.838c-12.625-9.243-30.221-18.778-47.3-20.084c-7.358-.562-18.144-.794-31.708 2.123c-11.794 2.536-23.42 7.328-32.26 11.845c-17.75-6.798-34.333-5.794-47.415-2.241c-11.033 2.995-13.387 4.793-23.481 10.733c-9.91 4.281-20.676 14.236-29.012 25.762c-31.051.881-56.898 11.803-75.3 22.246c-20.93 11.878-39.713 34.582-47.067 49.024c-8.208 16.118-16.406 38.917-9.48 74.176c-45.742 28.123-41.038 73.108-15.323 98.226c22.552 22.03 59.913 28.893 95.3 25.324c10.572 10.717 50.566 59.17 50.566 59.17s40.285 45.399 26.196 106.086M157.337 294.89c5.474-5.473 10.166-13.293 13.815-19.288c4.141.23 12.645.788 21.007.404c8.384-.386 17.269-1.624 20.821-3.7c.997 2.99 7.935 27.758 8.375 42.978c.578 19.954.022 23.491-3.203 30.495c-1.756.83-3.464 1.192-5.619.418c-7.677-2.756-20.47-12.343-29.642-20.9c-14.205-13.25-25.554-30.407-25.554-30.407m158.127-4.215c-.275 2.424-.451 11.125.72 18.725c1.191 7.719 2.359 14.891 5.27 17.677c10.23-1.843 17.374-8.406 23.549-13.014c0 0 20.04-15.647 23.911-19.24c-2.949-2.673-7.096-8.94-8.57-12.718c0 0-14.103 4.131-21.2 5.606c-8.938 1.935-19.441 2.78-23.68 2.964" clip-rule="evenodd"/></svg>
                    <span>Vegetariana</span>
                </div>
                <div class="tag vegana">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path class="icon" fill="currentColor" d="M2 18.43c1.51 1.36 11.64-4.67 13.14-7.21c.72-1.22-.13-3.01-1.52-4.44C15.2 5.73 16.59 9 17.91 8.31c.6-.32.99-1.31.7-1.92c-.52-1.08-2.25-1.08-3.42-1.21c.83-.2 2.82-1.05 2.86-2.25c.04-.92-1.13-1.97-2.05-1.86c-1.21.14-1.65 1.88-2.06 3c-.05-.71-.2-2.27-.98-2.95c-1.04-.91-2.29-.05-2.32 1.05c-.04 1.33 2.82 2.07 1.92 3.67C11.04 4.67 9.25 4.03 8.1 4.7c-.49.31-1.05.91-1.63 1.69c.89.94 2.12 2.07 3.09 2.72c.2.14.26.42.11.62c-.14.21-.42.26-.62.12c-.99-.67-2.2-1.78-3.1-2.71c-.45.67-.91 1.43-1.34 2.23c.85.86 1.93 1.83 2.79 2.41c.2.14.25.42.11.62c-.14.21-.42.26-.63.12c-.85-.58-1.86-1.48-2.71-2.32C2.4 13.69 1.1 17.63 2 18.43"/></svg>
                    <span>Vegana</span>
                </div>
                <div class="tag tipo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12q0-2.025.838-3.937T5.163 4.7T8.7 2.5t4.5-.45q.375.05.575.313t.225.712q.05 1.6 1.188 2.738T17.9 7q.525.025.8.3t.3.85q.05 1.05.638 1.725t1.637 1.025q.35.125.538.363t.187.587q.05 2.075-.725 3.925t-2.125 3.238t-3.2 2.187T12 22m-1.5-12q.625 0 1.063-.437T12 8.5t-.437-1.062T10.5 7t-1.062.438T9 8.5t.438 1.063T10.5 10m-2 5q.625 0 1.063-.437T10 13.5t-.437-1.062T8.5 12t-1.062.438T7 13.5t.438 1.063T8.5 15m6.5 1q.425 0 .713-.288T16 15t-.288-.712T15 14t-.712.288T14 15t.288.713T15 16"/></svg>
                    <span class="lbl-tipo">Merienda</span>
                </div>
            </article>
            <article class="cont-fav-compartir">
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path  class="icon" fill="currentColor" d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125"/></svg>
                </button>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81c1.66 0 3-1.34 3-3s-1.34-3-3-3s-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65c0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92"/></svg>
            </article>
        </section>

        <section class="info-princiapl">
            <div class="info-user">
                <div class="cont-img">
                    <img src="./css/recursos/Almuerzo.jpg" alt="Foto de perfil">
                </div>
                <div class="info">
                    <div class="fullname-username">
                        <span class="fullname">Lucas Heraaaaaaaaaaaaaaaaaaaaaaaaaaaaaarera</span>
                        <span class="username">@LucasHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaerr01</span>
                    </div>
                    <div class="fecha-publicacion">
                        <span class="fecha">11/01/2001</span>
                    </div>
                </div>
            </div>

            <article class="cont-descripcion">
                <p class="descripcion">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea reprehenderit eaque possimus quae tempore corrupti earum? Exercitationem quaerat eveniet dolore odio necessitatibus beatae quo sed minima fuga quibusdam. Ratione, reprehenderit?</p>
            </article>

            <article class="cont-ingredientes-pasos">
                <div class="cont-ingredientes">
                    <div class="subtitulo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path class="icon" d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z"/><path class="icon" fill="currentColor" d="M12.03 13.739L4.422 7.705a2.337 2.337 0 1 1 3.283-3.283l6.034 7.608c1.307-.571 3.3-.648 4.979 1.03c1.015 1.016 1.647 2.258 1.863 3.44c.21 1.15.049 2.426-.803 3.278c-.851.852-2.128 1.013-3.277.803c-1.182-.216-2.425-.848-3.44-1.864c-1.68-1.679-1.602-3.671-1.031-4.978"/></g></svg>               
                        <h4>Ingredientes</h4>
                    </div>
                    <ul>
                        <li>1</li>
                    </ul>
                </div>

                <div class="cont-pasos">
                    <div class="subtitulo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 14 14"><path fill="currentColor" class="icon" fill-rule="evenodd" d="M.658.44A1.5 1.5 0 0 1 1.718 0h5.587a1.5 1.5 0 0 1 1.06.44l3.414 3.414a1.5 1.5 0 0 1 .44 1.06V12.5a1.5 1.5 0 0 1-1.5 1.5h-9a1.5 1.5 0 0 1-1.5-1.5v-11c0-.398.158-.78.44-1.06ZM5.33 4.527a.75.75 0 0 1 .175 1.047L4.108 7.53a.75.75 0 0 1-1.14.094l-.838-.838a.75.75 0 0 1 1.06-1.06l.212.211l.882-1.234a.75.75 0 0 1 1.046-.175Zm.95 1.847a.75.75 0 0 1 .75-.75h2.5a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75m0 3.969a.75.75 0 0 1 .75-.75h2.5a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75m-.775-.738a.75.75 0 1 0-1.22-.872l-.883 1.235l-.212-.212a.75.75 0 0 0-1.06 1.06l.838.838a.75.75 0 0 0 1.14-.094z" clip-rule="evenodd"/></svg>
                        <h4>Pasos</h4>
                    </div>
                    <ol>
                        <li>2aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</li>
                        <li>2</li>
                        <li>2</li>
                        <li>2</li>
                        <li>2</li>
                        <li>2</li>
                        <li>2</li>
                        <li>2</li>
                    </ol>
                </div>
            </article>
        </section>
        <section class="cont-comentarios">
            <div class="subtitulo">
                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12a10 10 0 0 0 .951 4.262l-.93 4.537a1 1 0 0 0 1.18 1.18l4.537-.93c1.294.61 2.74.95 4.262.95c5.523 0 10-4.476 10-10c0-5.522-4.477-10-10-10" clip-rule="evenodd"/></svg>
                <h4>Comentarios</h4>
            </div>
            <div class="comentario-user">
                <div class="info-user">
                    <div class="cont-img">
                        <img src="./css/recursos/Almuerzo.jpg" alt="Foto de perfil">
                    </div>
                    <div class="info">
                        <div class="fullname-username">
                            <span class="fullname">Lucas Heraaaaaaaaaaaaaaaaaaaaaaaaaaaaaarera</span>
                            <span class="username">@LucasHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaerr01</span>
                        </div>
                        <div class="fecha-publicacion">
                            <span class="fecha">11/01/2001</span>
                        </div>
                    </div>
                </div>
                <div class="cont-comentario">
                    <p class="comentario"></p>
                </div>
            </div>

             <div class="comentario-user">
                <div class="info-user">
                    <div class="cont-img">
                        <img src="./css/recursos/Almuerzo.jpg" alt="Foto de perfil">
                    </div>
                    <div class="info">
                        <div class="fullname-username">
                            <span class="fullname">Lucas Heraaaaaaaaaaaaaaaaaaaaaaaaaaaaaarera</span>
                            <span class="username">@LucasHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaerr01</span>
                        </div>
                        <div class="fecha-publicacion">
                            <span class="fecha">11/01/2001</span>
                        </div>
                    </div>
                </div>
                <div class="cont-comentario">
                    <p class="comentario">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi assumenda et beatae at velit commodi tempora iusto sed nobis nisi, odio accusantium, ducimus accusamus iste cumque quaerat maiores architecto! Quam.</p>
                </div>
            </div>
            <div class="cont-agregar-comentario">
                <div class="cont-img">
                    <img src="./css/recursos/Bebidas.jpg" alt="Foto de perfil">
                </div>
                <div class="agregar-comentario">
                    <input type="text" placeholder="Agregar un comentario">
                    <button class="btn-enviar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="nav-icono"fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/></svg>
                    </button>
                </div>
             </div>
        </section>
