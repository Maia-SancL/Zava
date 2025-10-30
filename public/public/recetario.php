<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/usuario/componente/header.php';
?>

<link rel="stylesheet" href="/Zava/css/public/recetario.css">
<div class="contenedor-3">
    <section class="section-filtros">
        <div class="boton-filtros-tablet oculto activa-flotante" onclick="mostrarFlotante(this)">
            <iconify-icon icon="lucide:settings-2" class="icon icon-h6"></iconify-icon>
            <p class="medium">Filtros</p>
        </div>
        <div class="contenedor-filtros responsive flotante modo-responsive">
            <div class="header-filtros oculto">
                <p class="medium">Filtros</p>
                <iconify-icon icon="ic:round-close" class="icon icon-h6 btn-cerrar btn-cerrar-flotante"></iconify-icon>
            </div>
            <div class="caja-filtro responsive">
                <div class="caja-filtro-principal borde-redondeado">
                    <p class="pequenio medium">Tipo</p>
                    <iconify-icon icon="si:expand-more-line" class="icon icon-h6"></iconify-icon>
                </div>
                <div class="caja-filtro-contenido filtro oculto" name="tipo_comida">
                    <p class="pequenio light" value="">Almuerzo</p>
                    <p class="pequenio light" value="">Merienda</p>
                    <p class="pequenio light" value="">Cena</p>
                    <p class="pequenio light" value="">Desayuno</p>
                </div>
            </div>

            <div class="caja-filtro responsive">
                <div class="caja-filtro-principal borde-redondeado">
                    <p class="pequenio medium">Tipo</p>
                    <iconify-icon icon="si:expand-more-line" class="icon icon-h6"></iconify-icon>
                </div>
                <div class="caja-filtro-contenido filtro oculto" name="tipo_comida">
                    <p class="pequenio light" value="">Almuerzo</p>
                    <p class="pequenio light" value="">Merienda</p>
                    <p class="pequenio light" value="">Cena</p>
                    <p class="pequenio light" value="">Desayuno</p>
                </div>
            </div>

            <div class="caja-filtro responsive">
                <div class="caja-filtro-principal borde-redondeado">
                    <p class="pequenio medium">Tipo</p>
                    <iconify-icon icon="si:expand-more-line" class="icon icon-h6"></iconify-icon>
                </div>
                <div class="caja-filtro-contenido filtro oculto" name="tipo_comida">
                    <p class="pequenio light" value="">Almuerzo</p>
                    <p class="pequenio light" value="">Merienda</p>
                    <p class="pequenio light" value="">Cena</p>
                    <p class="pequenio light" value="">Desayuno</p>
                </div>
            </div>

            <div class="btns-filtros oculto">
                <button class="btn btn-primario">
                    <p class="pequenio color-secundario">Aplicar</p>
                </button>
                <button class="btn btn-secundario">
                    <p class="pequenio color-primario">Limpiar</p>
                </button>
            </div>
        </div>
    </section>
    <section class="resultado-busqueda">
        <h6 class="negrita texto-truncado">Resultados por "Almuerzo"</h6>
        <p class="pequenio light">100+ resultados entonctrados</p>
    </section>
    <section class="section-recetas">
        <article class="receta-especifica">
            <div class="imagen-receta">
                <img src="../css/imagenes/COMIDA-2.jpg" alt="" class="borde-redondeado">
                <button class="btn-agregar-favorito" title="Agregar a favorito">
                    <iconify-icon icon="solar:heart-linear" class="icon icon-h6 color-primario"></iconify-icon>
                </button>
            </div>
            <div class="info-receta">
                <p class="pequenio texto-titulo medium texto-truncado doble-linea">Ensalada aaaaaaaaaaaade fideos</p>
                <div class="contenedor-tiempo-porciones">
                    <div class="tiempo">
                        <iconify-icon icon="iconamoon:clock" class="icon icon-parrafo"></iconify-icon>
                        <p class="pequenio medium">45min</p>
                    </div>
                    <div class="porciones">
                        <iconify-icon icon="iconamoon:clock" class="icon icon-parrafo"></iconify-icon>
                        <p class="pequenio medium">45min</p>
                    </div>
                </div>
            </div>
        </article>
        <article class="receta-especifica">
            <div class="imagen-receta">
                <img src="../css/imagenes/COMIDA-2.jpg" alt="" class="borde-redondeado">
                <button class="btn-agregar-favorito" title="Agregar a favorito">
                    <iconify-icon icon="solar:heart-linear" class="icon icon-h6 color-primario"></iconify-icon>
                </button>
            </div>
            <div class="info-receta">
                <p class="pequenio texto-titulo medium texto-truncado doble-linea">Ensalada aaaaaaaaaaaade fideos</p>
                <div class="contenedor-tiempo-porciones">
                    <div class="tiempo">
                        <iconify-icon icon="iconamoon:clock" class="icon icon-parrafo"></iconify-icon>
                        <p class="pequenio medium">45min</p>
                    </div>
                    <div class="porciones">
                        <iconify-icon icon="iconamoon:clock" class="icon icon-parrafo"></iconify-icon>
                        <p class="pequenio medium">45min</p>
                    </div>
                </div>
            </div>
        </article>
    </section>
</div>
</div>
</main>
<script src="/Zava/js/actualizarClases.js"></script>
<script src="/Zava/js//mostrarFlotantes.js"></script>
<script src="/Zava/js/desplegarSelect.js"></script>
</body>

</html>