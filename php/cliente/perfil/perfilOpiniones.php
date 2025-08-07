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

// Paginación
$comentarios_por_pagina = 7;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $comentarios_por_pagina;

// Contar total de comentarios del usuario
$query_total = "SELECT COUNT(*) as total FROM Comentarios_Recetas WHERE id_usuario = $id_usuario AND activo = 1";
$resultado_total = mysqli_query($conexion, $query_total);
$total_comentarios = mysqli_fetch_assoc($resultado_total)['total'];
$total_paginas = ceil($total_comentarios / $comentarios_por_pagina);

// Obtener comentarios del usuario con información de la receta
$query_comentarios = "SELECT 
    c.id_comentario,
    c.comentario,
    c.fecha_comentario,
    r.id_receta,
    r.nombre as nombre_receta,
    r.imagen_principal,
    u.nombre as nombre_autor,
    u.apellido as apellido_autor
    FROM Comentarios_Recetas c
    INNER JOIN Recetas r ON c.id_receta = r.id_receta
    INNER JOIN Usuarios u ON r.id_usuario = u.id_usuario
    WHERE c.id_usuario = $id_usuario AND c.activo = 1
    ORDER BY c.fecha_comentario DESC
    LIMIT $comentarios_por_pagina OFFSET $offset";

$resultado_comentarios = mysqli_query($conexion, $query_comentarios);
$comentarios = [];
while ($row = mysqli_fetch_assoc($resultado_comentarios)) {
    $comentarios[] = $row;
}

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
            <div  onclick="location.href='/Zava/php/cliente/perfil/perfilHistorial.php'" class="caja-nav">
                <a>Ultimo Visto</a>
            </div>
            <div  onclick="location.href='/Zava/php/cliente/perfil/perfilPedidos.php'" class="caja-nav">
                <a>Pedidos</a>
            </div>
            <div class="caja-nav seleccionado">
                <a>Opiniones</a>
            </div>
            <div  onclick="location.href='/Zava/php/cliente/perfil/perfilRecetas.php'" class="caja-nav">
                <a>Mis Recetas</a>
            </div>
        </div>

        <!-- Sección de Opiniones -->
        <div class="cont-opiniones">
            <div class="titulo-seccion">
                <h3>Mis Opiniones</h3>
                <span class="contador-opiniones"><?php echo $total_comentarios; ?> comentarios</span>
            </div>

            <?php if (empty($comentarios)): ?>
                <div class="sin-opiniones">
                    <div class="icono-vacio">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <h4>No has publicado opiniones aún</h4>
                    <p>Tus comentarios en recetas aparecerán aquí</p>
                </div>
            <?php else: ?>
                <div class="lista-opiniones">
                    <?php foreach ($comentarios as $comentario): ?>
                        <div class="opinion-item">
                            <div class="opinion-header">
                                <div class="receta-info">
                                    <img src="/Zava/imagenes/recetas/<?php echo htmlspecialchars($comentario['imagen_principal'] ?: 'default.jpg'); ?>" 
                                         alt="<?php echo htmlspecialchars($comentario['nombre_receta']); ?>" 
                                         class="receta-miniatura">
                                    <div class="receta-detalles">
                                        <h4 class="nombre-receta">
                                            <a href="/Zava/php/cliente/mostrarReceta.php?id=<?php echo $comentario['id_receta']; ?>">
                                                <?php echo htmlspecialchars($comentario['nombre_receta']); ?>
                                            </a>
                                        </h4>
                                        <span class="autor-receta">
                                            por <?php echo htmlspecialchars($comentario['nombre_autor'] . ' ' . $comentario['apellido_autor']); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="fecha-comentario">
                                    <?php 
                                    $fecha = new DateTime($comentario['fecha_comentario']);
                                    echo $fecha->format('d/m/Y H:i');
                                    ?>
                                </div>
                            </div>
                            <div class="opinion-contenido">
                                <p><?php echo htmlspecialchars($comentario['comentario']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginación -->
                <?php if ($total_paginas > 1): ?>
                    <div class="paginacion">
                        <?php if ($pagina_actual > 1): ?>
                            <a href="?pagina=<?php echo $pagina_actual - 1; ?>" class="btn-paginacion">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="15,18 9,12 15,6"></polyline>
                                </svg>
                                Anterior
                            </a>
                        <?php endif; ?>

                        <div class="numeros-pagina">
                            <?php 
                            $inicio = max(1, $pagina_actual - 2);
                            $fin = min($total_paginas, $pagina_actual + 2);
                            
                            for ($i = $inicio; $i <= $fin; $i++): 
                            ?>
                                <a href="?pagina=<?php echo $i; ?>" 
                                   class="numero-pagina <?php echo $i == $pagina_actual ? 'activa' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                        </div>

                        <?php if ($pagina_actual < $total_paginas): ?>
                            <a href="?pagina=<?php echo $pagina_actual + 1; ?>" class="btn-paginacion">
                                Siguiente
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9,18 15,12 9,6"></polyline>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>

    <style>
    .cont-opiniones {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .titulo-seccion {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e0e0e0;
    }

    .titulo-seccion h3 {
        color: #333;
        font-size: 1.8rem;
        margin: 0;
    }

    .contador-opiniones {
        color: #666;
        font-size: 0.9rem;
        background: #f5f5f5;
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }

    .sin-opiniones {
        text-align: center;
        padding: 4rem 2rem;
        color: #666;
    }

    .icono-vacio {
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .sin-opiniones h4 {
        margin: 1rem 0 0.5rem 0;
        color: #333;
    }

    .lista-opiniones {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .opinion-item {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .opinion-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .opinion-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .receta-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .receta-miniatura {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #f0f0f0;
    }

    .receta-detalles h4 {
        margin: 0 0 0.25rem 0;
        font-size: 1.1rem;
    }

    .nombre-receta a {
        color: #333;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .nombre-receta a:hover {
        color: #ff6b35;
    }

    .autor-receta {
        color: #666;
        font-size: 0.85rem;
    }

    .fecha-comentario {
        color: #999;
        font-size: 0.8rem;
        text-align: right;
    }

    .opinion-contenido {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #ff6b35;
    }

    .opinion-contenido p {
        margin: 0;
        line-height: 1.6;
        color: #333;
    }

    .paginacion {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e0e0e0;
    }

    .btn-paginacion {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-paginacion:hover {
        background: #ff6b35;
        color: white;
        border-color: #ff6b35;
    }

    .numeros-pagina {
        display: flex;
        gap: 0.25rem;
    }

    .numero-pagina {
        padding: 0.75rem 1rem;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
        min-width: 45px;
        text-align: center;
    }

    .numero-pagina:hover {
        background: #f0f0f0;
    }

    .numero-pagina.activa {
        background: #ff6b35;
        color: white;
        border-color: #ff6b35;
    }

    @media (max-width: 768px) {
        .cont-opiniones {
            margin: 1rem auto;
            padding: 0 0.5rem;
        }

        .opinion-header {
            flex-direction: column;
            gap: 1rem;
        }

        .receta-info {
            width: 100%;
        }

        .fecha-comentario {
            text-align: left;
        }

        .paginacion {
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .btn-paginacion {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        .numero-pagina {
            padding: 0.5rem 0.75rem;
            min-width: 40px;
        }
    }
    </style>