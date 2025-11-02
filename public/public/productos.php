<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/usuario/componente/header.php';
?>

<link rel="stylesheet" href="/Zava/css/public/productos.css">
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
                    <p class="pequenio medium">Categoria</p>
                    <iconify-icon icon="si:expand-more-line" class="icon icon-h6"></iconify-icon>
                </div>
                <div class="caja-filtro-contenido filtro oculto" name="tipo_comida">
                    <p class="pequenio light" value="">Golosinas</p>
                </div>
            </div>

            <div class="caja-filtro responsive">
                <div class="caja-filtro-principal borde-redondeado">
                    <p class="pequenio medium">Marca</p>
                    <iconify-icon icon="si:expand-more-line" class="icon icon-h6"></iconify-icon>
                </div>
                <div class="caja-filtro-contenido filtro oculto" name="tipo_comida">
                    <p class="pequenio light" value="">Arcor</p>
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
        <h6 class="negrita texto-truncado">Resultados por "Gomitas"</h6>
        <p class="pequenio light">100+ resultados entonctrados</p>
    </section>
    <section class="section-productos">
        <article class="producto-especifico">
            <div class="imagen-producto">
                <img src="../css/imagenes/COMIDA-2.jpg" alt="" class="borde-redondeado">
                <div class="contenedor-oferta-favorito">
                    <p class="muy-pequenio texto-oferta color-blanco">%20 off</p>
                    <button class="btn-agregar-favorito" title="Agregar a favorito">
                        <iconify-icon icon="solar:heart-linear" class="icon icon-h6 color-primario"></iconify-icon>
                    </button>
                </div>
            </div>
            <div class="info-producto">
                <div class="contenedor-oferta-favorito-responsive oculto">
                    <p class="muy-pequenio light texto-prodcuto-resopnsive texto-truncado oculto">Arcor</p>
                    <button class="btn-agregar-favorito" title="Agregar a favorito">
                        <iconify-icon icon="solar:heart-linear" class="icon icon-h6 color-primario"></iconify-icon>
                    </button>
                </div>
                <p class="muy-pequenio light texto-prodcuto texto-truncado">Arcor</p>
                <p class="texto-titulo pequenio medium texto-truncado doble-linea">Barrita de cereal chocolate - 12g</p>
                <div class="contenedor-precios">
                    <p class="media-negrita texto-precio texto-truncado">$900,00</p>
                    <p class="muy-pequenio light texto-precio-oferta tachado texto-truncado">$1.2000,00</p>
                    <p class="muy-pequenio color-blanco texto-oferta-resopnsive oculto">-20%</p>
                </div>
            </div>
        </article>

        <article class="producto-especifico">
            <div class="imagen-producto">
                <img src="../css/imagenes/COMIDA-2.jpg" alt="" class="borde-redondeado">
                <div class="contenedor-oferta-favorito">
                    <p class="muy-pequenio texto-oferta color-blanco">%20 off</p>
                    <button class="btn-agregar-favorito" title="Agregar a favorito">
                        <iconify-icon icon="solar:heart-linear" class="icon icon-h6 color-primario"></iconify-icon>
                    </button>
                </div>
            </div>
            <div class="info-producto">
                <div class="contenedor-oferta-favorito-responsive oculto">
                    <p class="muy-pequenio light texto-prodcuto-resopnsive texto-truncado oculto">Arcor</p>
                    <button class="btn-agregar-favorito" title="Agregar a favorito">
                        <iconify-icon icon="solar:heart-linear" class="icon icon-h6 color-primario"></iconify-icon>
                    </button>
                </div>
                <p class="muy-pequenio light texto-prodcuto texto-truncado">Arcor</p>
                <p class="texto-titulo pequenio medium texto-truncado doble-linea">Barrita de cereal chocolate - 12g</p>
                <div class="contenedor-precios">
                    <p class="media-negrita texto-precio texto-truncado">$900,00</p>
                    <p class="muy-pequenio light texto-precio-oferta tachado texto-truncado">$1.2000,00</p>
                    <p class="muy-pequenio color-blanco texto-oferta-resopnsive oculto">-20%</p>
                </div>
            </div>
        </article>

    </section>
</div>
</div>
</main>
<script src="/Zava/js/actualizarClases.js"></script>
<script src="/Zava/js/mostrarFlotantes.js"></script>
<script src="/Zava/js/desplegarSelect.js"></script>

</body>

</html>