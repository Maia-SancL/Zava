
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
            <div class="caja-nav seleccionado">
                <a>Favoritos</a>
            </div>
            <div class="caja-nav">
                <a>Ultimo Visto</a>
            </div>
            <div class="caja-nav">
                <a>Pedidos</a>
            </div>
            <div class="caja-nav">
                <a>Opiniones</a>
            </div>
            <div class="caja-nav">
                <a>Mis Recetas</a>
            </div>
            <div class="caja-nav">
                <a>Mis Reseñas</a>
            </div>
        </div>
        
        <div class="cont-opciones">
            <div class="caja-opciones" onclick="mostrarFavoritos('recetas')" data-tipo="recetas">
                <h3>Recetas</h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="1024" height="1024" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M7 5a5 5 0 0 0-2 9.584v2.666h14v-2.666a5.001 5.001 0 0 0-2.737-9.53a4.502 4.502 0 0 0-8.526 0A5 5 0 0 0 7 5m11.998 13.75H5.002c.01 1.397.081 2.162.584 2.664C6.172 22 7.114 22 9 22h6c1.886 0 2.828 0 3.414-.586c.503-.502.574-1.267.584-2.664"/></svg>
            </div>
            <div class="caja-opciones" onclick="mostrarFavoritos('restaurantes')" data-tipo="restaurantes">
                <h3>Restaurantes</h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="1024" height="1024" viewBox="0 0 512 512"><path class="icon" fill="currentColor" d="M357.57 223.94a79.48 79.48 0 0 0 56.58-23.44l77-76.95c6.09-6.09 6.65-16 .85-22.39a16 16 0 0 0-23.17-.56l-68.63 68.58a12.29 12.29 0 0 1-17.37 0c-4.79-4.78-4.53-12.86.25-17.64l68.33-68.33a16 16 0 0 0-.56-23.16A15.62 15.62 0 0 0 440.27 56a16.7 16.7 0 0 0-11.81 4.9l-68.27 68.26a12.29 12.29 0 0 1-17.37 0c-4.78-4.78-4.53-12.86.25-17.64l68.33-68.31a16 16 0 0 0-.56-23.16A15.62 15.62 0 0 0 400.26 16a16.73 16.73 0 0 0-11.81 4.9L311.5 97.85a79.5 79.5 0 0 0-23.44 56.59v8.23a16 16 0 0 1-4.69 11.33l-35.61 35.62a4 4 0 0 1-5.66 0L68.82 36.33a16 16 0 0 0-22.58-.06C31.09 51.28 23 72.47 23 97.54c-.1 41.4 21.66 89 56.79 124.08l85.45 85.45A64.8 64.8 0 0 0 211 326a64 64 0 0 0 16.21-2.08a16.2 16.2 0 0 1 4.07-.53a15.93 15.93 0 0 1 10.83 4.25l11.39 10.52a16.12 16.12 0 0 1 4.6 11.23v5.54a47.73 47.73 0 0 0 13.77 33.65l90.05 91.57l.09.1a53.29 53.29 0 0 0 75.36-75.37L302.39 269.9a4 4 0 0 1 0-5.66L338 228.63a16 16 0 0 1 11.32-4.69Z"/><path class="icon" fill="currentColor" d="M211 358a97.32 97.32 0 0 1-68.36-28.25l-13.86-13.86a8 8 0 0 0-11.3 0l-85 84.56c-15.15 15.15-20.56 37.45-13.06 59.29a31 31 0 0 0 1.49 3.6C31 484 50.58 496 72 496a55.68 55.68 0 0 0 39.64-16.44L225 365.66a4.69 4.69 0 0 0 1.32-3.72v-.26a4.63 4.63 0 0 0-5.15-4.27A97 97 0 0 1 211 358"/></svg>
            </div>
            <div class="caja-opciones" onclick="mostrarFavoritos('productos')" data-tipo="productos">
                <h3>Productos</h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="1024" height="1024" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M3.778 3.655c-.181.36-.27.806-.448 1.696l-.598 2.99a3.06 3.06 0 1 0 6.043.904l.07-.69a3.167 3.167 0 1 0 6.307-.038l.073.728a3.06 3.06 0 1 0 6.043-.904l-.598-2.99c-.178-.89-.267-1.335-.448-1.696a3 3 0 0 0-1.888-1.548C17.944 2 17.49 2 16.582 2H7.418c-.908 0-1.362 0-1.752.107a3 3 0 0 0-1.888 1.548M18.269 13.5a4.53 4.53 0 0 0 2.231-.581V14c0 3.771 0 5.657-1.172 6.828c-.943.944-2.348 1.127-4.828 1.163V18.5c0-.935 0-1.402-.201-1.75a1.5 1.5 0 0 0-.549-.549C13.402 16 12.935 16 12 16s-1.402 0-1.75.201a1.5 1.5 0 0 0-.549.549c-.201.348-.201.815-.201 1.75v3.491c-2.48-.036-3.885-.22-4.828-1.163C3.5 19.657 3.5 17.771 3.5 14v-1.081a4.53 4.53 0 0 0 2.232.581a4.55 4.55 0 0 0 3.112-1.228A4.64 4.64 0 0 0 12 13.5a4.64 4.64 0 0 0 3.156-1.228a4.55 4.55 0 0 0 3.112 1.228"/></svg>
            </div>
        </div>
        
        <!-- Contenedor dinamico XD -->
        <div id="contenido-favoritos" class="contenido-favoritos" style="display: none;">
            <div class="header-favoritos">
                <h3 id="titulo-favoritos">Favoritos</h3>
                <button onclick="ocultarFavoritos()" class="btn-volver">← Volver</button>
            </div>
            <div id="grid-favoritos" class="grid-favoritos-dinamico">
                <!-- Aca se carga dinamicamente -->
            </div>
        </div>
    </main>

<style> <!-- Estilos para cambiar estoy gaga ya ke sueño -->
.contenido-favoritos {
    margin-top: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 20px;
}

.header-favoritos {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 2px solid #e74c3c;
    padding-bottom: 15px;
}

.header-favoritos h3 {
    color: #2c3e50;
    margin: 0;
    font-size: 1.5em;
}

.btn-volver {
    background: #95a5a6;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    transition: background 0.3s ease;
}

.btn-volver:hover {
    background: #7f8c8d;
}

.grid-favoritos-dinamico {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.item-favorito-dinamico {
    background: #f8f9fa;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.item-favorito-dinamico:hover {
    transform: translateY(-5px);
}

.imagen-favorito-dinamico {
    height: 200px;
    overflow: hidden;
}

.imagen-favorito-dinamico img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.info-favorito-dinamico {
    padding: 15px;
}

.info-favorito-dinamico h4 {
    color: #2c3e50;
    margin: 0 0 10px 0;
    font-size: 1.2em;
}

.info-favorito-dinamico p {
    margin: 5px 0;
    color: #7f8c8d;
    font-size: 0.9em;
}

.precio-dinamico {
    font-weight: bold;
    color: #e74c3c !important;
    font-size: 1.1em !important;
}

.acciones-dinamico {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
}

.btn-ver-dinamico {
    background: #3498db;
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.9em;
    transition: background 0.3s ease;
}

.btn-ver-dinamico:hover {
    background: #2980b9;
}

.btn-eliminar-dinamico {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1.2em;
    transition: background 0.3s ease;
}

.btn-eliminar-dinamico:hover {
    background: #c0392b;
}

.sin-favoritos-dinamico {
    text-align: center;
    color: #7f8c8d;
    font-style: italic;
    padding: 40px;
    background: #f8f9fa;
    border-radius: 10px;
}

.sin-favoritos-dinamico a {
    color: #3498db;
    text-decoration: none;
}

.sin-favoritos-dinamico a:hover {
    text-decoration: underline;
}

.caja-opciones {
    cursor: pointer;
    transition: all 0.3s ease;
}

.caja-opciones:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.loading {
    text-align: center;
    padding: 40px;
    color: #7f8c8d;
    font-style: italic;
}
</style>

<script>
function mostrarFavoritos(tipo) {
    // Ocultar las opciones y mostrar el contenedor de favoritos
    document.querySelector('.cont-opciones').style.display = 'none';
    document.getElementById('contenido-favoritos').style.display = 'block';
    
    // Actualizar el título
    const titulos = {
        'recetas': 'Recetas Favoritas',
        'restaurantes': 'Restaurantes Favoritos',
        'productos': 'Productos Favoritos'
    };
    document.getElementById('titulo-favoritos').textContent = titulos[tipo];
    
    // Mostrar loading
    document.getElementById('grid-favoritos').innerHTML = '<div class="loading">Cargando favoritos...</div>';
    
    // Hacer petición AJAX para obtener los favoritos
    fetch('obtener_favoritos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'tipo=' + encodeURIComponent(tipo)
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('grid-favoritos').innerHTML = data;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('grid-favoritos').innerHTML = '<div class="sin-favoritos-dinamico">Error al cargar los favoritos. Inténtalo de nuevo.</div>';
    });
}

function ocultarFavoritos() {
    // Mostrar las opciones y ocultar el contenedor de favoritos
    document.querySelector('.cont-opciones').style.display = 'flex';
    document.getElementById('contenido-favoritos').style.display = 'none';
}

function eliminarFavoritoDinamico(tipo, id) {
    if(confirm('¿Estás seguro de que quieres eliminar este elemento de tus favoritos?')) {
        fetch('eliminar_favorito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'tipo=' + encodeURIComponent(tipo) + '&id=' + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Recargar los favoritos de la categoría actual
                const tipoActual = document.getElementById('titulo-favoritos').textContent.toLowerCase().includes('recetas') ? 'recetas' :
                                 document.getElementById('titulo-favoritos').textContent.toLowerCase().includes('restaurantes') ? 'restaurantes' : 'productos';
                mostrarFavoritos(tipoActual);
            } else {
                alert('Error al eliminar el favorito: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el favorito');
        });
    }
}
</script>

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/footer.php';
?>
