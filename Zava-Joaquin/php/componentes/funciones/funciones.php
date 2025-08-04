<?php
function registrarVista($id_usuario, $tipo_contenido, $id_contenido, $conexion) {
    $fecha_vista = date('Y-m-d H:i:s');
    $query = "INSERT INTO historial_vistas (id_usuario, tipo_contenido, id_contenido, fecha_vista) 
              VALUES ($id_usuario, '$tipo_contenido', $id_contenido, '$fecha_vista')";
    mysqli_query($conexion, $query);
}
?>