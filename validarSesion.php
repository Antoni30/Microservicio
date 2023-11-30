<?php
if (($_SERVER["REQUEST_METHOD"] == "POST")) {
	include('conexion/config.php');
	date_default_timezone_set("America/Bogota");
	$sesionDesde   = date("Y-m-d H:i:A");

	//Limpiar variables para evitar inyeccion SQL
	$email = filter_var($_REQUEST['emailUser'], FILTER_SANITIZE_EMAIL);
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$emailUser 	= ($_REQUEST['emailUser']);
	}
	$passwordUser   = trim($_REQUEST['passwordUser']);



	$sqlVerificandoLogin = ("SELECT IdUser, nameUser, emailUser, passwordUser  FROM usuarios WHERE emailUser COLLATE utf8_bin='$emailUser'");
	$resultLogin = mysqli_query($con, $sqlVerificandoLogin) or die(mysqli_error($con));;
	$numLogin    = mysqli_num_rows($resultLogin);


	if ($numLogin != 0) {
		while ($rowData  = mysqli_fetch_assoc($resultLogin)) {
			$passwordBD = $rowData['passwordUser'];
			//verificar hash
			if (password_verify($passwordUser, $passwordBD)) {
				session_start(); //Inicia sesion
				$_SESSION['IdUser'] 	= $rowData['IdUser'];
				$_SESSION['nameUser']	= $rowData['nameUser'];
				$_SESSION['emailUser'] 	= $rowData['emailUser'];

				//Hora de logeo
				$Update = ("UPDATE usuarios SET sesionDesde='$sesionDesde' WHERE emailUser='$emailUser' ");
				$resultado = mysqli_query($con, $Update);

				header("location:home.php?a=1");
			} else {
				//error login
				header("location:index.php?b=1");
			}
		}
	} else {
		//error correo
		header("location:./?e=1");
	}
}
