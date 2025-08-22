<?php session_start(); ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php'; ?>
<aside>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/menuLateral.php'; ?>
    <layout>   
        <main>
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php'; ?>
            <link rel="stylesheet" href="/Zava/css/admin/pruebaProductos.css">
            <div class="cont-filtros">
                <form method="get" action="" class="form-filtros">
                    <input type="hidden" name="tabla_seleccionada" value="productos">
                    <div class="filtros">
                        <select class="select-filtro" name="tipo_producto">
                            <option value="" selected>Seleccione un tipo de producto</option>
                            <option value="Lacteos">Lacteos</option>
                            <option value="Frutas">Frutas</option>
                            <option value="Carnes">Carnes</option>
                        </select>
                        <select class="select-filtro" name="precio">
                            <option value="" selected>Seleccione un rango de precio</option>
                            <option value="50000">Hasta $50,000</option>
                            <option value="100000">Hasta $100,000</option>
                            <option value="150000">Hasta $150,000</option>
                        </select>
                        <div class="barra-buscar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0s.41-1.08 0-1.49zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/></svg>
                            <input type="search" name="buscar" class="input-buscar" value="">
                        </div>
                    </div>
                    <button class="btn-filtrar" name="filtrar" type="submit">Filtrar</button>
                </form>
            </div>

            <section class="section-tabla-productos productos">
                <article class="producto guia">
                    <div class="informacion-principal">
                        <div class="informacion">
                            <span class="lbl-informacion"> </span>
                            <span class="lbl-informacion">ID</span>
                            <span class="lbl-informacion">NOMBRE</span>
                            <span class="lbl-informacion">TIPO</span>
                            <span class="lbl-informacion">PRECIO</span>
                            <span class="lbl-informacion">ACCIONES</span>
                        </div>
                    </div>
                </article>

                <article class="producto" data-id="1" data-tipo="productos">
                    <div class="informacion-principal">
                        <div class="informacion">
                            <button class="btn-desplegable">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M4 18q-.425 0-.712-.288T3 17t.288-.712T4 16h16q.425 0 .713.288T21 17t-.288.713T20 18zm0-5q-.425 0-.712-.288T3 12t.288-.712T4 11h16q.425 0 .713.288T21 12t-.288.713T20 13zm0-5q-.425 0-.712-.288T3 7t.288-.712T4 6h16q.425 0 .713.288T21 7t-.288.713T20 8z"/></svg>
                            </button>
                            <span class="lbl-informacion">1</span>
                            <span class="lbl-informacion">Leche Entera</span>
                            <span class="lbl-informacion">Lacteos</span>
                            <span class="lbl-informacion">$1.500,00</span>
                            <div class="btns">
                                <form method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que desea eliminar este producto?');">
                                    <input type="hidden" name="eliminar_id" value="1">
                                    <button type="submit" class="btn-eliminar" title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path class="icon" fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zm3-4q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="detalles-producto oculto">
                        <div class="cont-img">
                            <img src="/Zava/img/productos/perfil.png" alt="Imagen de Leche Entera" style="height:80px;max-width:80px;object-fit:cover;">
                        </div>
                        <div class="cont-descripcion">
                            <h5>Descripción</h5>
                            <p class="descripcion">Leche entera de vaca, pasteurizada. Ideal para toda la familia.</p>
                        </div>
                    </div>
                </article>

                <article class="producto" data-id="2" data-tipo="productos">
                    <div class="informacion-principal">
                        <div class="informacion">
                            <button class="btn-desplegable">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M4 18q-.425 0-.712-.288T3 17t.288-.712T4 16h16q.425 0 .713.288T21 17t-.288.713T20 18zm0-5q-.425 0-.712-.288T3 12t.288-.712T4 11h16q.425 0 .713.288T21 12t-.288.713T20 13zm0-5q-.425 0-.712-.288T3 7t.288-.712T4 6h16q.425 0 .713.288T21 7t-.288.713T20 8z"/></svg>
                            </button>
                            <span class="lbl-informacion">2</span>
                            <span class="lbl-informacion">Manzanas Rojas</span>
                            <span class="lbl-informacion">Frutas</span>
                            <span class="lbl-informacion">$2.500,00</span>
                            <div class="btns">
                                <form method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que desea eliminar este producto?');">
                                    <input type="hidden" name="eliminar_id" value="2">
                                    <button type="submit" class="btn-eliminar" title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path class="icon" fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zm3-4q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="detalles-producto oculto">
                        <div class="cont-img">
                            <img src="/Zava/img/productos/perfil.png" alt="Imagen de Manzanas Rojas" style="height:80px;max-width:80px;object-fit:cover;">
                        </div>
                        <div class="cont-descripcion">
                            <h5>Descripción</h5>
                            <p class="descripcion">Manzanas rojas frescas y jugosas, por kilo.</p>
                        </div>
                    </div>
                </article>

            </section>
            <script>
                document.querySelectorAll('.btn-desplegable').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const producto = btn.closest('.producto');
                        const detalleActual = producto.querySelector('.detalles-producto');
                        document.querySelectorAll('.detalles-producto').forEach(detalle => {
                            if (detalle !== detalleActual) {
                                detalle.classList.remove('activo');
                                detalle.classList.add('oculto');
                            }
                        });
                        detalleActual.classList.toggle('activo');
                        detalleActual.classList.toggle('oculto');
                    });
                });
            </script>
