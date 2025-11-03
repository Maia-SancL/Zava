<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Zava/');
}
?>
<link rel="stylesheet" href="/Zava/css/usuario/navegadorLateralUsuario.css">
<div class="contenedor-2">
    <nav class="menu-lateral-usuario">
        <a href="/Zava/index.php" class="contenedor-nombre-logo">
            <div class="contenedor-logo">
                <img src="/Zava/css/recursos/logos/Logo 4.0.png">
            </div>
            <h6 class="media-negrita color-primario">Zava</h6>
        </a>
        <ul class="menu-lateral-lista">
            <a href="<?php echo BASE_URL; ?>inicio">
                <li>
                    <iconify-icon icon="tabler:home-filled" class="icon color-primario icon-h6"
                        title="Inicio"></iconify-icon>
                    <p class="pequenio medium color-primario">Inicio</p>
                </li>
            </a>
            <a href="/Zava/public/public/recetario.php">
                <li>
                    <iconify-icon icon="solar:chef-hat-bold" class="icon color-primario icon-h6"
                        title="Recetas"></iconify-icon>
                    <p class="pequenio medium color-primario">Recetas</p>
                </li>
            </a>
            <a href="/Zava/public/public/productos.php">
                <li>
                    <iconify-icon icon="solar:shop-bold" class="icon color-primario icon-h6"
                        title="Productos"></iconify-icon>
                    <p class="pequenio medium color-primario">Productos</p>
                </li>
            </a>
            <a>
                <li>
                    <iconify-icon icon="material-symbols:favorite" class="icon color-primario icon-h6"
                        title="Favoritos"></iconify-icon>
                    <p class="pequenio medium color-primario">Favoritos</p>
                </li>
            </a>
            <div class="sub-menu">
                <li>
                    <iconify-icon icon="tabler:clock-filled" class="icon color-primario icon-h6"
                        title="Mi actividad"></iconify-icon>
                    <p class="pequenio medium color-primario">Mi actividad</p>
                </li>
                <ul>
                    <a>
                        <li>
                            <iconify-icon icon="mdi:eye" class="icon color-primario icon-h6"
                                title="Ultimo visto"></iconify-icon>
                            <p class="pequenio medium color-primario">Ultimo visto</p>
                        </li>
                    </a>
                    <a>
                        <li>
                            <iconify-icon icon="material-symbols:box-rounded" class="icon color-primario icon-h6"
                                title="Pedidos"></iconify-icon>
                            <p class="pequenio medium color-primario">Pedidos</p>
                        </li>
                    </a>
                    <a>
                        <li>
                            <iconify-icon icon="iconamoon:comment-fill" class="icon color-primario icon-h6"
                                title="Opiniones"></iconify-icon>
                            <p class="pequenio medium color-primario">Opiniones</p>
                        </li>
                    </a>
                    <a>
                        <li>
                            <iconify-icon icon="icon-park-solid:cook" class="icon color-primario icon-h6"
                                title="Recetas creadas"></iconify-icon>
                            <p class="pequenio medium color-primario">Recetas creadas</p>
                        </li>
                    </a>

                </ul>
            </div>

        </ul>
    </nav>
</div>