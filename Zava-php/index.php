<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/header.php';

// include_once 'php/componentes/navegador.php'; //Menú superior
// include_once 'php/componentes/menuLateral.php'; //Menú lateral
?>
<div class="layout">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/menuLateral.php';?>
    <main>  
    <link rel="stylesheet" href="/Zava-php/css/index.css">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/navegador.php';?>
  <header class="header-titulo" id="inicio">
            <div class="cont-titulo">
                <img class="img-logo select-none" src="/Zava-php/css/recursos/Principal 2.0.png">
            </div>
            <h2 class="subtitulo-principal">Encuentra recetas, restaurantes y productos aptos<br>para celiacos.</h2>
            <div class="barra-buscar-index">
                <div class="btn-buscar-index">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="barra-busqueda-icono"fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/></svg>
                </div>
                <input type="text" placeholder="Buscar recetas, productos, restaurantes">
            </div>
        </header>

        <section id="recetas">
            <div class="cont-subtitulo-descripcion">
                <h2 class="subtitulo">Elegí una opción para cada momento del día</h2>
                <p class="descripcion">Te acompañamos en cada comida del día con recetas simples, seguras y deliciosas, adaptadas a una alimentación libre de gluten</p>
            </div>
            <div class="cont-btns-recetas">
                <div class="btn-receta">
                    <div class="tarjeta-receta">
                        <div class="overlay"></div> <!-- Capa oscura -->
                        <img src="/Zava-php/css/recursos/Desayuno.jpg">
                    </div> 
                    <p class="lbl-tipo-receta">Desayuno</p>
                </div>
                <div class="btn-receta">
                     <div class="tarjeta-receta">
                        <div class="overlay"></div> <!-- Capa oscura -->
                        <img src="/Zava-php/css/recursos/Almuerzo.jpg">
                    </div> 
                    <p class="lbl-tipo-receta">Almuerzo</p>
                </div>
                <div class="btn-receta">
                     <div class="tarjeta-receta">
                        <div class="overlay"></div> <!-- Capa oscura -->
                        <img src="/Zava-php/css/recursos/Merienda.jpg">
                    </div> 
                    <p class="lbl-tipo-receta">Merienda</p>
                </div>
                <div class="btn-receta">
                     <div class="tarjeta-receta">
                        <div class="overlay"></div> <!-- Capa oscura -->
                        <img src="/Zava-php/css/recursos/Cena.jpg">
                    </div> 
                    <p class="lbl-tipo-receta">Cena</p>
                </div>
                <div class="btn-receta">
                     <div class="tarjeta-receta">
                        <div class="overlay"></div> <!-- Capa oscura -->
                        <img src="/Zava-php/css/recursos/Postre.jpg">
                    </div> 
                    <p class="lbl-tipo-receta">Postre</p>
                </div>
                <div class="btn-receta">
                     <div class="tarjeta-receta">
                        <div class="overlay"></div> <!-- Capa oscura -->
                        <img src="/Zava-php/css/recursos/Panaderia.jpg">
                    </div> 
                    <p class="lbl-tipo-receta">Panaderia</p>
                </div>
                <div class="btn-receta">
                     <div class="tarjeta-receta">
                        <div class="overlay"></div> <!-- Capa oscura -->
                        <img src="/Zava-php/css/recursos/Snack.jpg">
                    </div> 
                    <p class="lbl-tipo-receta">Snack</p>
                </div>
                <div class="btn-receta">
                     <div class="tarjeta-receta">
                        <div class="overlay"></div> <!-- Capa oscura -->
                        <img src="/Zava-php/css/recursos/Bebidas.jpg">
                    </div> 
                    <p class="lbl-tipo-receta">Bebidas</p>
                </div>
            </div>
        </section>

        <section id="productos">
            <div class="cont-subtitulo-descripcion">
                <h2 class="subtitulo">Encontra productos certificados sin gluten</h2>
                <p class="descripcion">Encuentra alimentos y productos sin gluten validados para celíacos.</p>
            </div>
                <div class="cont-productos-ofertas">
                    <div class="cont-ofertas">
                        <h2>¡Ofertas!</h2>
                    </div>
                    <div class="cont-productos">
                        <div class="cont-general-productos superior">
                            <div class="producto-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 207" fill="none" class="semicirculo-fondo">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                    </svg>
                                <div class="cont-img">
                                    <img src="/Zava-php//css/recursos/Harinas--y-premezclas.png" alt="Imgagen-premezcla">
                                </div>
                                <h4 class="producto-seccion">Harinas y premezclas</h4>
                            </div>
                            <div class="cont-productos-chicos">
                                <div class="producto-item chico">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                     <div class="cont-img">
                                        <img src="/Zava-php//css/recursos/galletitas.png" alt="Imgagen-galletitas">
                                    </div>
                                    <h4 class="producto-seccion">Galletitas</h4>
                                </div>
                                <div class="producto-item chico">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                     <div class="cont-img">
                                        <img src="/Zava-php//css/recursos/Golosinas.png" alt="Imgagen-golosinas">
                                    </div>
                                    <h4 class="producto-seccion">Golosinas</h4>
                                </div>
                                <div class="producto-item">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                      <h4 class="producto-seccion">Cereales</h4>
                                     <div class="cont-img">
                                        <img src="/Zava-php//css/recursos/Cereales.png" alt="Imgagen-cereales">
                                    </div>
                                </div>
                                <div class="producto-item">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                     <h4 class="producto-seccion">Infusiones</h4>
                                     <div class="cont-img">
                                        <img src="/Zava-php//css/recursos/Infusiones.png" alt="Imgagen-infusiones">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cont-general-productos inferior">
                           
                            <div class="cont-productos-chicos">
                                <div class="producto-item chico">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                     <h4 class="producto-seccion">Aderezos</h4>
                                     <div class="cont-img">
                                        <img src="/Zava-php//css/recursos/Aderezos.png" alt="Imgagen-aderezos">
                                    </div>
                                    
                                </div>
                                <div class="producto-item chico">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                     <div class="cont-img">
                                        <img src="/Zava-php//css/recursos/Congelados.png" alt="Imgagen-congelados">
                                    </div>
                                    <h4 class="producto-seccion">Congelados</h4>
                                </div>
                                <div class="producto-item">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                      <h4 class="producto-seccion">Snacks</h4>
                                     <div class="cont-img">
                                        <img src="/Zava-php//css/recursos/Snack.png" alt="Imgagen-snacks">
                                    </div>
                                </div>
                                <div class="producto-item">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                      <h4 class="producto-seccion">Bebidas</h4>
                                     <div class="cont-img">
                                        <img src="/Zava-php//css/recursos/Bebidas.png" alt="Imgagen-bebidas">
                                    </div>
                                </div>
                            </div>
                             <div class="producto-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 207" fill="none" class="semicirculo-fondo">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                    </svg>
                                <div class="cont-img">
                                    <img src="/Zava-php//css/recursos/Pastas-y-arroces.png" alt="Imgagen-pastas-y-arroces">
                                </div>
                                <h4 class="producto-seccion">Pastas y arroces</h4>
                            </div>
                        </div>
                    </div>
                </div>
        </section>

        <section id="restaurantes">
            <div class="cont-subtitulo-descripcion">
                <h2 class="subtitulo">Comer afuera sin preocuparte por el menú</h2>
                <p class="descripcion">Salir a comer también es posible sin gluten. Descubrí lugares recomendados, revisá reseñas y sumá tu experiencia para ayudar a otros.</p>
            </div>
            <div class="cont-restaurantes-grupo">
                <div class="restaurante">
                    <!--SE MOSTRARAN 6 RESTAURANTES (CUALQUIERA) DESDE LA BDD-->
                    <div class="restaurante-imagen">
                        <img src="/Zava-php//css/recursos/restaurante.jpg">
                    </div>
                    <div class="descripcion">
                        <div class="cont-des-superior">
                            <div class="nombre-restaurante">
                                <h4>NOMBRE RESTAURANTE</h4> <!--NOMBRE DEL RESTAURANTE DESDE LA BDD-->
                            </div>
                            <div class="cont-tipo-estrellas">
                                <div class="tipo-cocina">
                                    <p>TIPO DE COCINA</p> <!--TIPO DE COCINA DEL RESTAURANTE DESDE LA BDD-->
                                </div>
                                <div class="cont-estrellas">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M13.51 3.139c-.652-1.185-2.368-1.185-3.021 0a28 28 0 0 0-2.114 4.894a.35.35 0 0 1-.33.223a30 30 0 0 0-4.375.436c-1.337.233-1.926 1.837-.91 2.83q.192.188.388.374a32 32 0 0 0 3.103 2.587a.274.274 0 0 1 .11.31a27.6 27.6 0 0 0-1.172 5.065c-.19 1.424 1.318 2.298 2.495 1.694a29.3 29.3 0 0 0 4.085-2.537a.4.4 0 0 1 .462 0a29 29 0 0 0 4.085 2.537c1.177.604 2.685-.27 2.495-1.694a27.6 27.6 0 0 0-1.171-5.065a.274.274 0 0 1 .11-.31a32 32 0 0 0 3.49-2.96c1.016-.994.427-2.598-.91-2.831a30 30 0 0 0-4.376-.436a.35.35 0 0 1-.329-.223a27.7 27.7 0 0 0-2.114-4.894"/></svg>
                                    <div class="calificacion">
                                        <p>4.7</p> <!--CALIFICACION DESDE LA BDD-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cont-des-inferior">
                            <div class="cont-ubicacion-hora">
                                <div class="ubicacion">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" class="icon"><path class="icon" d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path class="icon" fill="currentColor" d="M12 2a9 9 0 0 1 9 9c0 3.074-1.676 5.59-3.442 7.395a20.4 20.4 0 0 1-2.876 2.416l-.426.29l-.2.133l-.377.24l-.336.205l-.416.242a1.87 1.87 0 0 1-1.854 0l-.416-.242l-.52-.32l-.192-.125l-.41-.273a20.6 20.6 0 0 1-3.093-2.566C4.676 16.589 3 14.074 3 11a9 9 0 0 1 9-9m0 6a3 3 0 1 0 0 6a3 3 0 0 0 0-6"/></g></svg>
                                    <div class="direccion">
                                        <p>Calle 9 de Julio 3700, Recoleta, Buenos Aires, Argentina</p> <!--DIRECCION DESDE LA BBD-->
                                    </div>
                                </div>
                                 <div class="horarios">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M17 3.34a10 10 0 1 1-14.995 8.984L2 12l.005-.324A10 10 0 0 1 17 3.34M12 6a1 1 0 0 0-.993.883L11 7v5l.009.131a1 1 0 0 0 .197.477l.087.1l3 3l.094.082a1 1 0 0 0 1.226 0l.094-.083l.083-.094a1 1 0 0 0 0-1.226l-.083-.094L13 11.585V7l-.007-.117A1 1 0 0 0 12 6"/></svg>
                                    <div class="hora">
                                        <p>12:00 - 15:30 / 18:30 - 23:00</p> <!--HORARIOS DESDE LA BBD-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cont-btn-restaurantes">
                <button class="btn-ver-restaurantes">Ver más restaurantes</button>
            </div>
        </section>
    </main>
</div>
<?php 

include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/footer.php';
?>
</body>