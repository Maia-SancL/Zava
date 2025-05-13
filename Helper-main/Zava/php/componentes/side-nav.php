<?php 
include_once('conexion.php');
session_start();
if(isset($_SESSION['usuario'])){
echo'   <nav class="side">
            <section class = "logo-side-nav">
                <img id="logo-side" src="" alt="">
                <img id="logo-nombre" src="" alt="">
            </section>
        <ul class = "links">
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Recetas</a></li>
            <li><a href="#">Restaurantes</a></li>
            <li><a href="#">Productos</a></li>
            <li><a href="#">Favoritos</a></li>
            <li><a href="#">Mi Actividad</a></li>
                <li><a href="#">Ultimo Visto</a></li>
                <li><a href="#">Pedidos</a></li>
                <li><a href="#">Comentarios y reseñas</a></li>
            <li><a href="#">Mi colección</a></li>
                <li><a href="#">Recetas</a></li>
                <li><a href="#">Restaurantes</a></li>
        </ul>
    </nav>
    '
}else{
        
echo'   
<nav class="side">
    <section class = "logo-side-nav">
        <img id="logo-side" src="" alt="">
        <img id="logo-nombre" src="" alt="">
    </section>
        <ul class = "links">
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Recetas</a></li>
            <li><a href="#">Restaurantes</a></li>
            <li><a href="#">Productos</a></li>
            <li><a href="#">Favoritos</a></li>
            <li><a href="#">Mi Actividad</a></li>
                <li><a href="#">Ultimo Visto</a></li>
                <li><a href="#">Pedidos</a></li>
                <li><a href="#">Comentarios y reseñas</a></li>
            <li><a href="#">Mi colección</a></li>
                <li><a href="#">Recetas</a></li>
                <li><a href="#">Restaurantes</a></li>
        </ul>
</nav>
'
;
}