<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<?php
	session_start();
	include('conexion.php');
	$nombre = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$telefono = $_POST['telefono'];
	$direccion = $_POST['direccion'];
	$consulta="SELECT email FROM usuario WHERE email='$email'";
	$consulta1=mysqli_query($conexion,$consulta);
	if (mysqli_num_rows($consulta1) > 0){
		echo "Error en la consulta: el correo ya está registrado. <a href='index.html'>
		Vuelve</a> y registra otro o ";
	}
	else {
		echo "Ingresando . . .";
		header("Location: login.html");
		$sql = "INSERT INTO usuario (nombre, apellido, email, password, telefono, direccion)
		VALUES ('$nombre', '$apellido', '$email', '$password', '$telefono', '$direccion')";
		$result = mysqli_query($conexion, $sql);
	}
	
?>
</body>
</html><a href='login.html'>Inicia sesión</a>