<!--
    para entrar a la receta id=x, se incluye un 'input.hidden' que valga x,
    asi se puede hacer la query correspondiente
-->

<?php
    include_once('conexion.php');
    $id = $_POST['id'];
    $query = "SELECT * FROM recetas WHERE id = $id";
    $resultado = mysqli_query($conexion, $query);
    $receta = mysqli_fetch_array($resultado);

    $id_usuario = $receta['id_usuario'];
    $query_usuario = "SELECT * FROM usuarios WHERE id = $id_usuario";
    $resultado_usuario = mysqli_query($conexion, $query_usuario);
    $usuario = mysqli_fetch_array($resultado_usuario);

    $nombre_usuario = $usuario['nombre'];
    $apellido_usuario = $usuario['apellido'];
    $nickname_usuario = $usuario['nickname'];
    $foto_usuario = $usuario['foto de perfil'];

    $nombre = $receta['nombre'];
    $descripcion = $receta['descripcion'];
    $ingredientes = $receta['ingredientes'];
    $ingredientes = explode(",", $ingredientes);
    $pasos = $receta['pasos'];
    $pasos = explode(",", $ingredientes);
    $tiempo = $receta['tiempo'];
    // $imagenes = ???
?>
<h1 class="titulo-receta"><?php echo $nombre; ?></h1>

<?php
    //titulo de la receta
    echo "<h1 class='titulo-receta'>".$nombre."</h1>";
    
    // tags de la receta
    echo "<div class='tags-receta'>".$tiempo."</div>";
    if ($receta['tipo de dieta'] != ""){
        $tipo_de_dieta = $receta['tipo_de_dieta'];
        echo "<div class='tags-receta'>".$tipo_de_dieta."</div>";
    }
    if ($receta['tipo de comida'] != ""){
        $tipo_de_comida = $receta['tipo_de_comida'];
        echo "<div class='tags-receta'>".$tipo_de_comida."</div>";
    }
    if ($receta['porciones'] != ""){
        $porciones = $receta['porciones'];
        echo "<div class='tags-receta'>".$porciones."</div>";
    }

    // usuario que subio la receta
    echo "<div class='usuario-receta'>
        <img src='". $foto_usuario . "' alt='Foto de perfil' class='foto-perfil'>
        <p class='nombre-usuario'>". $nombre_usuario . "' '" . $apellido_usuario . "</p>
        <p class='nickname'>" . "'@'" . $nickname_usuario . "</p>
    </div>";

    // descripcion de la receta
    echo "<div class='descripcion-receta'> <p class='descripcion'>".$descripcion."</p> </div>";

    // ingredientes de la receta
    echo "<ul>";
    foreach ($ingredientes as $i) {
        echo "<li>" . $i . "</li>";
    }
    echo "</ul>";

    // pasos de la receta
    echo "<ol>";
    foreach ($pasos as $p) {
        echo "<li>" . $p . "</li>";
    }
    echo "</ol>";
?>
