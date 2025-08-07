<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';
include_once('conexion.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];
$tabla = $_GET['tabla'] ?? 'inicio';

// Datos usuario
$query_usuario = "SELECT nombre, apellido, nickname, foto FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);
$nombre = htmlspecialchars($usuario['nombre']);
$apellido = htmlspecialchars($usuario['apellido']);
$nickname = htmlspecialchars($usuario['nickname']);
$foto = $usuario['foto'] ? htmlspecialchars($usuario['foto']) : 'perfil.png';
$rutaImg="/Zava/img/perfiles/".$foto;
?>
<link rel="stylesheet" href="/Zava/css/perfil-inicio.css">
<link rel="stylesheet" href="/Zava/css/perfil-recetas.css">

    <?php require_once($_SERVER['DOCUMENT_ROOT'].'/Zava/php/componentes/funciones/tags.php'); ?>
<main>
        <div class="cont-perfil">
            <div class="img-info">
                <img class="img-perfil" src="<?php echo $rutaImg;?>">
            </div>
            <div class="cont-info">
                <div class="nombre-info">
                    <h4><?php echo $nombre." ".$apellido;?> </h4>
                    <h5><?php echo $nickname;?></h5>
                </div>
                <a> <button class="btn-editar"> Editar perfil
                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="icon" d="M9.49993 15.1341L15.6633 8.9708C14.6264 8.53766 13.6847 7.90514 12.8916 7.10914C12.0952 6.31584 11.4624 5.37385 11.0291 4.33664L4.86577 10.5C4.38493 10.9808 4.1441 11.2216 3.93743 11.4866C3.69359 11.7995 3.48432 12.1379 3.31327 12.4958C3.1691 12.7991 3.0616 13.1225 2.8466 13.7675L1.7116 17.17C1.65936 17.3258 1.65161 17.493 1.68922 17.653C1.72683 17.8129 1.80831 17.9592 1.92449 18.0754C2.04068 18.1916 2.18697 18.2731 2.34692 18.3107C2.50688 18.3483 2.67415 18.3405 2.82993 18.2883L6.23243 17.1533C6.87827 16.9383 7.20077 16.8308 7.5041 16.6866C7.86355 16.5155 8.19993 16.3075 8.51327 16.0625C8.77827 15.8558 9.0191 15.615 9.49993 15.1341ZM17.3733 7.2608C17.9878 6.64628 18.333 5.8128 18.333 4.94372C18.333 4.07465 17.9878 3.24117 17.3733 2.62664C16.7587 2.01211 15.9253 1.66687 15.0562 1.66687C14.1871 1.66687 13.3536 2.01211 12.7391 2.62664L11.9999 3.3658L12.0316 3.4583C12.3958 4.50062 12.9919 5.44663 13.7749 6.22497C14.5765 7.03149 15.5557 7.63934 16.6341 7.99997L17.3733 7.2608Z" fill="#FBF6EE"/>
                    </svg>
                </button>
            </a>
        </div>
    </div>

    <div class="cont-nav">
        <div onclick="location.href='/Zava/php/cliente/perfil/perfil.php'" class="caja-nav">
            <a>Favoritos</a>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilHistorial.php'" class="caja-nav">
            <a>Ultimo Visto</a>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilPedidos.php'" class="caja-nav">
            <a>Pedidos</a>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilOpiniones.php'" class="caja-nav">
            <a>Opiniones</a>
        </div>
        <div onclick="location.href='/Zava/php/cliente/perfil/perfilRecetas.php'" class="caja-nav seleccionado">
            <a>Mis Recetas</a>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const filtroTipo = document.getElementById('filtro-tipo-comida');
    const filtroTiempo = document.getElementById('filtro-tiempo');
    const recetas = document.querySelectorAll('.globalReceta');

    function filtrarRecetas() {
        const tipo = filtroTipo.value;
        const tiempo = filtroTiempo.value;

        recetas.forEach(receta => {
            const recetaTipo = receta.getAttribute('data-tipo-comida');
            const recetaTiempo = parseInt(receta.getAttribute('data-tiempo'), 10);
            let mostrar = true;

            if (tipo && recetaTipo !== tipo) mostrar = false;
            if (tiempo) {
                if (tiempo == 15 && recetaTiempo > 15) mostrar = false;
                else if (tiempo == 30 && recetaTiempo > 30) mostrar = false;
                else if (tiempo == 60 && recetaTiempo > 60) mostrar = false;
                else if (tiempo == 61 && recetaTiempo <= 60) mostrar = false;
            }

            receta.style.display = mostrar ? '' : 'none';
        });
    }

    filtroTipo.addEventListener('change', filtrarRecetas);
    filtroTiempo.addEventListener('change', filtrarRecetas);
});
</script>
    <?php
            $query_recetas = "SELECT id_receta, nombre, descripcion, tiempo_preparacion, imagen_principal, fecha_publicacion, tipo_comida, tipo_dieta FROM Recetas WHERE id_usuario = $id_usuario ORDER BY fecha_publicacion DESC";
            $resultado_recetas = mysqli_query($conexion, $query_recetas);

            if (mysqli_num_rows($resultado_recetas) > 0) {
                while ($receta = mysqli_fetch_assoc($resultado_recetas)) {
                    $ruta_imagen_receta = "/Zava/imagenes/recetas/" . htmlspecialchars($receta['imagen_principal']);
                    $fecha_formateada = date("d/m/Y", strtotime($receta['fecha_publicacion']));
            ?>
    <div class="global">
                <div class="globalReceta" data-tipo-comida="<?php echo strtolower($receta['tipo_comida']); ?>" data-tiempo="<?php echo (int)$receta['tiempo_preparacion']; ?>">
                    <div class="listaGlobal">
                        <div class="receta">
                            <div class="contImagen">
                            <img class="imagenGlobal" src="<?php echo $ruta_imagen_receta; ?>" alt="<?php echo htmlspecialchars($receta['nombre']); ?>">
                            </div>
                            <div class="contGlobal">
                                <div class="contEtiquetas">
                                    <div class="etiquetas">
                                        <div class="etiquetaGlobal">
                                        <?php filtrarTags(strtolower($receta['tipo_comida'])); ?>
                                        <p><?php echo htmlspecialchars(ucfirst($receta['tipo_comida'])); ?></p>
                                    </div>
                                        
                                        <div class="etiquetaGlobal">
                                        <?php filtrarTags(strtolower($receta['tipo_dieta'])); ?>
                                        <p><?php echo htmlspecialchars(ucfirst($receta['tipo_dieta'])); ?></p>
                                    </div>
                                    </div>

                                    <div class="fechaAniadida">
                                        <p><?php echo $fecha_formateada; ?></p>
                                    </div>
                                </div>
                                
                                <h5><?php echo htmlspecialchars($receta['nombre']); ?></h5>
                                <p><?php echo htmlspecialchars($receta['descripcion']); ?></p>

                                <div class="tiempoReceta">
                                    <svg viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.625 2.75C10.5147 2.75 12.3269 3.50067 13.6631 4.83686C14.9993 6.17306 15.75 7.98533 15.75 9.875C15.75 11.7647 14.9993 13.5769 13.6631 14.9131C12.3269 16.2493 10.5147 17 8.625 17C6.73533 17 4.92306 16.2493 3.58686 14.9131C2.25067 13.5769 1.5 11.7647 1.5 9.875C1.5 7.98533 2.25067 6.17306 3.58686 4.83686C4.92306 3.50067 6.73533 2.75 8.625 2.75ZM8.625 3.5C6.93425 3.5 5.31274 4.17165 4.11719 5.36719C2.92165 6.56274 2.25 8.18425 2.25 9.875C2.25 11.5658 2.92165 13.1873 4.11719 14.3828C5.31274 15.5784 6.93425 16.25 8.625 16.25C9.46218 16.25 10.2912 16.0851 11.0646 15.7647C11.8381 15.4444 12.5408 14.9748 13.1328 14.3828C13.7248 13.7908 14.1944 13.0881 14.5147 12.3146C14.8351 11.5412 15 10.7122 15 9.875C15 8.18425 14.3284 6.56274 13.1328 5.36719C11.9373 4.17165 10.3158 3.5 8.625 3.5ZM8.25 5.75H9V9.815L12.525 11.8475L12.15 12.5L8.25 10.25V5.75Z" fill="#1F1F1F"/>
                                    </svg>
                                    <p><?php echo htmlspecialchars($receta['tiempo_preparacion']); ?> min</p>
                                </div>
                                
                                <div class="botonGlobal">
                                    <div class="botonModificar" onclick="location.href='/Zava/php/cliente/modificarReceta.php?id=<?php echo $receta['id_receta']; ?>'">
                                        <svg viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.11572 12L3.36572 15L6.36572 14.25L15.0552 5.56052C15.3364 5.27923 15.4944 4.89776 15.4944 4.50002C15.4944 4.10227 15.3364 3.72081 15.0552 3.43952L14.9262 3.31052C14.6449 3.02931 14.2635 2.87134 13.8657 2.87134C13.468 2.87134 13.0865 3.02931 12.8052 3.31052L4.11572 12Z" stroke="#F4F4F4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M4.11572 12L3.36572 15L6.36572 14.25L13.8657 6.75L11.6157 4.5L4.11572 12Z" fill="#F4F4F4"/>
                                        <path d="M11.6157 4.5L13.8657 6.75M10.1157 15H16.1157" stroke="#F4F4F4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <p>Modificar</p>
                                    </div>
                                    <div class="botonEliminar" onclick="location.href='/Zava/php/cliente/eliminarReceta.php?id=<?php echo $receta['id_receta']; ?>'">
                                        <svg viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.36573 21C6.81573 21 6.34506 20.8043 5.95373 20.413C5.56239 20.0217 5.36639 19.5507 5.36573 19V6C5.08239 6 4.84506 5.904 4.65373 5.712C4.46239 5.52 4.36639 5.28267 4.36573 5C4.36506 4.71733 4.46106 4.48 4.65373 4.288C4.84639 4.096 5.08373 4 5.36573 4H9.36573C9.36573 3.71667 9.46173 3.47933 9.65373 3.288C9.84573 3.09667 10.0831 3.00067 10.3657 3H14.3657C14.6491 3 14.8867 3.096 15.0787 3.288C15.2707 3.48 15.3664 3.71733 15.3657 4H19.3657C19.6491 4 19.8867 4.096 20.0787 4.288C20.2707 4.48 20.3664 4.71733 20.3657 5C20.3651 5.28267 20.2691 5.52033 20.0777 5.713C19.8864 5.90567 19.6491 6.00133 19.3657 6V19C19.3657 19.55 19.1701 20.021 18.7787 20.413C18.3874 20.805 17.9164 21.0007 17.3657 21H7.36573ZM10.3657 17C10.6491 17 10.8867 16.904 11.0787 16.712C11.2707 16.52 11.3664 16.2827 11.3657 16V9C11.3657 8.71667 11.2697 8.47933 11.0777 8.288C10.8857 8.09667 10.6484 8.00067 10.3657 8C10.0831 7.99933 9.84573 8.09533 9.65373 8.288C9.46173 8.48067 9.36573 8.718 9.36573 9V16C9.36573 16.2833 9.46173 16.521 9.65373 16.713C9.84573 16.905 10.0831 17.0007 10.3657 17ZM14.3657 17C14.6491 17 14.8867 16.904 15.0787 16.712C15.2707 16.52 15.3664 16.2827 15.3657 16V9C15.3657 8.71667 15.2697 8.47933 15.0777 8.288C14.8857 8.09667 14.6484 8.00067 14.3657 8C14.0831 7.99933 13.8457 8.09533 13.6537 8.288C13.4617 8.48067 13.3657 8.718 13.3657 9V16C13.3657 16.2833 13.4617 16.521 13.6537 16.713C13.8457 16.905 14.0831 17.0007 14.3657 17Z" fill="#621111"/>
                                        </svg>
                                        <p>Eliminar</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            <?php
                }
            } else {
                echo "<div class='no-recetas'><p>Aún no has creado ninguna receta. ¡Animate a compartir tus propias recetas!</p> <a href='/Zava/php/cliente/crear.php' class='btn-crear-receta'>Crear mi primera receta</a></div>";
            }
            ?>
            <div class="filtroVistos">
                <div class="tipoContenido">
    <p>Tipo de contenido</p>
    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.07748 7.74415C6.23376 7.58793 6.44568 7.50016 6.66665 7.50016C6.88762 7.50016 7.09954 7.58793 7.25582 7.74415L9.99998 10.4883L12.7442 7.74415C12.821 7.66456 12.913 7.60108 13.0146 7.5574C13.1163 7.51373 13.2257 7.49074 13.3363 7.48978C13.447 7.48882 13.5567 7.5099 13.6591 7.5518C13.7615 7.5937 13.8546 7.65558 13.9328 7.73382C14.0111 7.81207 14.0729 7.90511 14.1148 8.00752C14.1567 8.10994 14.1778 8.21967 14.1769 8.33032C14.1759 8.44097 14.1529 8.55032 14.1092 8.65199C14.0656 8.75366 14.0021 8.84561 13.9225 8.92249L10.5892 12.2558C10.4329 12.412 10.221 12.4998 9.99998 12.4998C9.77901 12.4998 9.56709 12.412 9.41082 12.2558L6.07748 8.92249C5.92126 8.76621 5.8335 8.55429 5.8335 8.33332C5.8335 8.11235 5.92126 7.90043 6.07748 7.74415Z" fill="#1F1F1F"/>
    </svg>
    <select id="filtro-tipo-comida">
        <option value="">Todos</option>
        <option value="desayuno">Desayuno</option>
        <option value="almuerzo">Almuerzo</option>
        <option value="cena">Cena</option>
        <option value="snack">Snack</option>
        <option value="postre">Postre</option>
        <option value="bebida">Bebida</option>
        <option value="panaderia">Panadería</option>
    </select>
</div>
                <div class="tiempoContenido">
    <p>Tiempo</p>
    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.07748 7.74415C6.23376 7.58793 6.44568 7.50016 6.66665 7.50016C6.88762 7.50016 7.09954 7.58793 7.25582 7.74415L9.99998 10.4883L12.7442 7.74415C12.821 7.66456 12.913 7.60108 13.0146 7.5574C13.1163 7.51373 13.2257 7.49074 13.3363 7.48978C13.447 7.48882 13.5567 7.5099 13.6591 7.5518C13.7615 7.5937 13.8546 7.65558 13.9328 7.73382C14.0111 7.81207 14.0729 7.90511 14.1148 8.00752C14.1567 8.10994 14.1778 8.21967 14.1769 8.33032C14.1759 8.44097 14.1529 8.55032 14.1092 8.65199C14.0656 8.75366 14.0021 8.84561 13.9225 8.92249L10.5892 12.2558C10.4329 12.412 10.221 12.4998 9.99998 12.4998C9.77901 12.4998 9.56709 12.412 9.41082 12.2558L6.07748 8.92249C5.92126 8.76621 5.8335 8.55429 5.8335 8.33332C5.8335 8.11235 5.92126 7.90043 6.07748 7.74415Z" fill="#1F1F1F"/>
    </svg>
    <select id="filtro-tiempo">
        <option value="">Todos</option>
        <option value="15">≤ 15 min</option>
        <option value="30">≤ 30 min</option>
        <option value="60">≤ 60 min</option>
        <option value="61">Más de 60 min</option>
    </select>
</div>
            </div>
        </div>
        </div>
        
    </div>
    