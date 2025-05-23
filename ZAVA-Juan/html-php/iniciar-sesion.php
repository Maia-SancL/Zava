<?php
	include('conexion.php');
	if(!isset($_POST['correo']) && !isset($_SESSION['correo'])){
		header('location:iniciar-sesion.html');
	}else{

	    $correo = $_POST['correo'];
    	$pass = $_POST['password'];
        $pass_MD5 = md5($pass);
    	$sql = "SELECT * FROM `usuarios` WHERE correo = '$correo'";
	    $result = mysqli_query($conexion,$sql);
    	$result1 = mysqli_num_rows($result);
	    if ($result1 == 1) {
		    $result2 = mysqli_fetch_assoc($result);
            if ($pass_MD5 == $result2) {
                session_start();
	        	$_SESSION['id'] = $result2['id_usuario'];
		        $_SESSION['correo'] = $result2['correo'];
        		$_SESSION['rol']= $result2['rol'];
	        	header("Location: index.php");
            }else{
                echo 'contraseña incorrecta';
            }
	    }else{
            echo 'no existe la cuenta';
        }
    } 
?>