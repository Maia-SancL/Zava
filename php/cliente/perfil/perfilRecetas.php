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
<link rel="stylesheet" href="/Zava/css/editarPerfil.css">

    <?php require_once($_SERVER['DOCUMENT_ROOT'].'/Zava/php/componentes/funciones/tags.php'); ?>
<main>
    <?php
    if (isset($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        echo "<div class='alerta " . htmlspecialchars($mensaje['tipo']) . "'>" . htmlspecialchars($mensaje['texto']) . "</div>";
        unset($_SESSION['mensaje']);
    }
    ?>
        <div class="cont-perfil">
            <div class="img-info">
                <img class="img-perfil" src="<?php echo $rutaImg;?>">
            </div>
            <div class="cont-info">
                <div class="nombre-info">
                    <h4><?php echo $nombre." ".$apellido;?> </h4>
                    <h5><?php echo $nickname;?></h5>
                </div>
                <a> <button class="btn-editar" id="openModalBtn"> Editar perfil
                    
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

    <?php
            $query_recetas = "SELECT id_receta, nombre, descripcion, tiempo_preparacion, imagen_principal, fecha_publicacion, tipo_comida, tipo_dieta FROM Recetas WHERE id_usuario = $id_usuario ORDER BY fecha_publicacion DESC";
            $resultado_recetas = mysqli_query($conexion, $query_recetas);

            if (mysqli_num_rows($resultado_recetas) > 0) {
                while ($receta = mysqli_fetch_assoc($resultado_recetas)) {
                    $ruta_imagen_receta = "/Zava/img/recetas/" . $receta['imagen_principal'];
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
                                    <button class="botonEliminar" data-id="<?php echo $receta['id_receta']; ?>">
                                        <svg viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.25 2.25C10.25 1.83579 10.5858 1.5 11 1.5H14C14.4142 1.5 14.75 1.83579 14.75 2.25V3H18.25C18.6642 3 19 3.33579 19 3.75V4.5C19 4.91421 18.6642 5.25 18.25 5.25H6.75C6.33579 5.25 6 4.91421 6 4.5V3.75C6 3.33579 6.33579 3 6.75 3H10.25V2.25ZM8.13039 6.75H16.8696L16.1935 19.3305C16.1473 20.2173 15.419 21 14.5299 21H10.4701C9.58099 21 8.85272 20.2173 8.80651 19.3305L8.13039 6.75ZM10.25 9.75C10.25 9.33579 9.91421 9 9.5 9C9.08579 9 8.75 9.33579 8.75 9.75V16.5C8.75 16.9142 9.08579 17.25 9.5 17.25C9.91421 17.25 10.25 16.9142 10.25 16.5V9.75ZM13 9.75C13 9.33579 12.6642 9 12.25 9C11.8358 9 11.5 9.33579 11.5 9.75V16.5C11.5 16.9142 11.8358 17.25 12.25 17.25C12.6642 17.25 13 16.9142 13 16.5V9.75ZM15.5 9C15.9142 9 16.25 9.33579 16.25 9.75V16.5C16.25 16.9142 15.9142 17.25 15.5 17.25C15.0858 17.25 14.75 16.9142 14.75 16.5V9.75C14.75 9.33579 15.0858 9 15.5 9Z" fill="#1F1F1F"/>
                                        </svg>
                                        <p>Eliminar</p>
                                    </button>
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
        </main>
        <!-- El Modal -->
        <div id="editProfileModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Editar Perfil</h2>
            <span class="close-btn">&times;</span>
        </div>
        <div class="modal-body">
            <form action="editarPerfil.php" method="POST" enctype="multipart/form-data" class="edit-form">
                <div class="form-group profile-pic-group">
                    <label for="foto">Foto de Perfil:</label>
                    <img src="<?php echo $rutaImg; ?>" alt="Foto de perfil actual" class="current-pic">
                    <input type="file" id="foto" name="foto" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo $apellido; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nickname">Nickname:</label>
                    <input type="text" id="nickname" name="nickname" value="<?php echo $nickname; ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-guardar">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/Zava/js/cliente/modalPerfil.js"></script>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/footer.php'; ?>
    <script src="/Zava/js/cliente/perfilRecetas.js"></script>
    