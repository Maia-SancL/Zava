<?php
	session_start();
	include('conexion.php');
	if(!isset($_POST['email'])){
		header('location:login.html');
	}

	$email = $_POST['email'];
	$pass = $_POST['password'];
	$sql = "SELECT * FROM `usuario` WHERE email = '$email' AND password = '$pass'";
	$result = mysqli_query($conexion,$sql);
	$result1 = mysqli_num_rows($result);
	if ($result1 == 1) {
		
		$result2 = mysqli_fetch_assoc($result);
		$_SESSION['id'] = $result2['id_usuario'];
		$_SESSION['email'] = $result2['email'];
		$_SESSION['rol']= $result2['rol'];
		 header("Location: index-mila.php");

	}
	if ($result1 == 0){
		echo "El usuario y la contraseña no coinciden o no existen. 
		<a href='login.html'>Intente de nuevo</a>
		o <a href='index.html'>regístrese</a>.<br>";
	}


 
?>