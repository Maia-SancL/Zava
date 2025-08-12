<div class="layout">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/menuLateral.php';?>
    <main>  
    <link rel="stylesheet" href="/Zava/css/index.css">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/navegador.php';?>
  <header class="header-titulo" id="inicio">
            <div class="cont-titulo">
                <img class="img-logo select-none" src="/Zava/css/recursos/Principal 2.0.png">
            </div>
            <h2 class="subtitulo-principal">Encuentra recetas y productos aptos<br>para celiacos.</h2>
            <div class="contenedor-busqueda-principal">
                <div class="barra-buscar-index">
                    <div class="btn-buscar-index">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="barra-busqueda-icono"fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/></svg>
                    </div>
                    <input type="text" id="busqueda-principal" placeholder="Buscar recetas, productos" autocomplete="off">
                </div>
            </div>
        </header>

        <section id="recetas">
            <div class="cont-subtitulo-descripcion">
                <h2 class="subtitulo">Elegí una opción para cada momento del día</h2>
                <p class="descripcion">Te acompañamos en cada comida del día con recetas simples, seguras y deliciosas, adaptadas a una alimentación libre de gluten</p>
            </div>
            <div class="cont-btns-recetas">
                <div class="btn-receta">
                    <form method="GET" action="/Zava/php/cliente/recetario.php" style="height:100%;">
                        <input type="hidden" name="tipo_comida" value="desayuno">
                        <button type=submit style="background: none; border: none; padding: 0; cursor: pointer; height:100%">
                        <div class="tarjeta-receta">
                                <div class="overlay"></div> <!-- Capa oscura -->
                                <img src="/Zava/css/recursos/Desayuno.jpg">
                            </div>
                        </button>
                    </form>
                    <p class="lbl-tipo-receta">Desayuno</p> 
                </div>

                <div class="btn-receta">
                    <form method="GET" action="/Zava/php/cliente/recetario.php" style="height:100%;">
                        <input type="hidden" name="tipo_comida" value="almuerzo">
                        <button type=submit style="background: none; border: none; padding: 0; cursor: pointer; height:100%">
                            <div class="tarjeta-receta">
                                <div class="overlay"></div> <!--Capa oscura -->
                                <img src="/Zava/css/recursos/Almuerzo.jpg">
                            </div> 
                        </button>
                    </form>
                    <p class="lbl-tipo-receta">Almuerzo</p>
                </div>
                
                <div class="btn-receta">
                    <form method="GET" action="/Zava/php/cliente/recetario.php" style="height:100%;">
                        <input type="hidden" name="tipo_comida" value="merienda">
                        <button type=submit style="background: none; border: none; padding: 0; cursor: pointer; height:100%">
                            <div class="tarjeta-receta">
                                <div class="overlay"></div> <!-- Capa oscura -->
                                <img src="/Zava/css/recursos/Merienda.jpg">
                            </div> 
                        </button>
                    </form>
                    <p class="lbl-tipo-receta">Merienda</p>
                </div>

                <div class="btn-receta">
                    <form method="GET" action="/Zava/php/cliente/recetario.php" style="height:100%;">
                        <input type="hidden" name="tipo_comida" value="cena">
                         <button type=submit style="background: none; border: none; padding: 0; cursor: pointer;  height:100%">
                            <div class="tarjeta-receta">
                                <div class="overlay"></div> <!-- Capa oscura -->
                                <img src="/Zava/css/recursos/Cena.jpg">
                            </div> 
                        </button>
                     </form>
                    <p class="lbl-tipo-receta">Cena</p>
                </div>

                <div class="btn-receta">
                    <form method="GET" action="/Zava/php/cliente/recetario.php" style="height:100%;">
                        <input type="hidden" name="tipo_comida" value="postre">
                        <button type=submit style="background: none; border: none; padding: 0; cursor: pointer;  height:100%">
                            <div class="tarjeta-receta">
                                <div class="overlay"></div> <!-- Capa oscura -->
                                <img src="/Zava/css/recursos/Postre.jpg">
                            </div>
                        </button>
                    </form>
                    <p class="lbl-tipo-receta">Postre</p>
                 </div>
                    
                <div class="btn-receta">
                    <form method="GET" action="/Zava/php/cliente/recetario.php" style="height:100%;">
                        <input type="hidden" name="tipo_comida" value="panaderia">
                         <button type=submit style="background: none; border: none; padding: 0; cursor: pointer;  height:100%">
                            <div class="tarjeta-receta">
                                <div class="overlay"></div> <!-- Capa oscura -->
                                <img src="/Zava/css/recursos/Panaderia.jpg">
                            </div> 
                            </button>
                        </form>
                    <p class="lbl-tipo-receta">Panaderia</p>
                </div>

                <div class="btn-receta">
                    <form method="GET" action="/Zava/php/cliente/recetario.php" style="height:100%;">
                        <input type="hidden" name="tipo_comida" value="snack">
                        <button type=submit style="background: none; border: none; padding: 0; cursor: pointer;  height:100%">
                            <div class="tarjeta-receta">
                                <div class="overlay"></div> <!-- Capa oscura -->
                                <img src="/Zava/css/recursos/Snack.jpg">
                            </div> 
                        </button>
                    </form>
                    <p class="lbl-tipo-receta">Snack</p>
                </div>

                <div class="btn-receta">
                     <form method="GET" action="/Zava/php/cliente/recetario.php" style="height:100%;">
                        <input type="hidden" name="tipo_comida" value="bebida">
                        <button type=submit style="background: none; border: none; padding: 0; cursor: pointer; height:100%">
                        <div class="tarjeta-receta">
                            <div class="overlay"></div> <!-- Capa oscura -->
                            <img src="/Zava/css/recursos/Bebidas.jpg"> 
                        </div>
                    </button>
                </form>
                <p class="lbl-tipo-receta">Bebidas</p>
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
                                    <img src="/Zava//css/recursos/Harinas--y-premezclas.png" alt="Imgagen-premezcla">
                                </div>
                                <h4 class="producto-seccion">Harinas y premezclas</h4>
                            </div>
                            
                            <div class="cont-productos-chicos">
                                <div class="producto-item chico">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                     <div class="cont-img">
                                        <img src="/Zava//css/recursos/galletitas.png" alt="Imgagen-galletitas">
                                    </div>
                                    <h4 class="producto-seccion">Galletitas</h4>
                                </div>
                                <div class="producto-item chico">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                     <div class="cont-img">
                                        <img src="/Zava//css/recursos/Golosinas.png" alt="Imgagen-golosinas">
                                    </div>
                                    <h4 class="producto-seccion">Golosinas</h4>
                                </div>
                                <div class="producto-item">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                      <h4 class="producto-seccion">Cereales</h4>
                                     <div class="cont-img">
                                        <img src="/Zava//css/recursos/Cereales.png" alt="Imgagen-cereales">
                                    </div>
                                </div>
                                <div class="producto-item">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                     <h4 class="producto-seccion">Infusiones</h4>
                                     <div class="cont-img">
                                        <img src="/Zava//css/recursos/Infusiones.png" alt="Imgagen-infusiones">
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
                                        <img src="/Zava//css/recursos/Aderezos.png" alt="Imgagen-aderezos">
                                    </div>
                                    
                                </div>
                                <div class="producto-item chico">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                     <div class="cont-img">
                                        <img src="/Zava//css/recursos/Congelados.png" alt="Imgagen-congelados">
                                    </div>
                                    <h4 class="producto-seccion">Congelados</h4>
                                </div>
                                <div class="producto-item">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                      <h4 class="producto-seccion">Snacks</h4>
                                     <div class="cont-img">
                                        <img src="/Zava//css/recursos/Snack.png" alt="Imgagen-snacks">
                                    </div>
                                </div>
                                <div class="producto-item">
                                     <svg class="semicirculo-fondo"" viewBox="0 0 270 110" fill="none">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                     </svg>
                                      <h4 class="producto-seccion">Bebidas</h4>
                                     <div class="cont-img">
                                        <img src="/Zava//css/recursos/Bebidas.png" alt="Imgagen-bebidas">
                                    </div>
                                </div>
                            </div>
                             <div class="producto-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 207" fill="none" class="semicirculo-fondo">
                                        <path d="M452 226C452 196.321 446.154 166.933 434.797 139.514C423.439 112.094 406.792 87.1799 385.806 66.1939C364.82 45.2078 339.906 28.5608 312.486 17.2032C285.067 5.84566 255.679 -1.2973e-06 226 0C196.321 1.2973e-06 166.933 5.84566 139.514 17.2032C112.094 28.5608 87.1799 45.2078 66.1939 66.1939C45.2078 87.1799 28.5608 112.094 17.2032 139.514C5.84566 166.933 -2.5946e-06 196.321 0 226H226H452Z" fill="#ECDCC1"/>
                                    </svg>
                                <div class="cont-img">
                                    <img src="/Zava//css/recursos/Pastas-y-arroces.png" alt="Imgagen-pastas-y-arroces">
                                </div>
                                <h4 class="producto-seccion">Pastas y arroces</h4>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </main>
</div>
<script src="/Zava/js/cliente/busqueda.js"></script>