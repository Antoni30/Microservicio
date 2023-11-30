<?php
if (isset($_POST["submit"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
	include('conexion/config.php');
	$emailUser 		= trim($_REQUEST['emailUser']);
	$passwordUser   = trim($_REQUEST['passwordUser']);
	$nameUser  		= trim($_REQUEST['nameUser']);

	date_default_timezone_set("America/Guayaquil");
	$createUser              = date("Y-m-d H:i:A");

	//funcion hash
	$PasswordHash = password_hash($passwordUser, PASSWORD_BCRYPT); //cifrar clave


	//generar un token o variable aleatoria
	function TokenAleatorio($length = 50)
	{
		return substr(str_shuffle(str_repeat($x = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
	}
	$miToken  = TokenAleatorio();

	//Verificar correo repetido
	$SqlVerificandoEmail = ("SELECT emailUser FROM usuarios WHERE emailUser COLLATE utf8_bin='$emailUser'");
	$jqueryEmail         = mysqli_query($con, $SqlVerificandoEmail);
	if (mysqli_num_rows($jqueryEmail) > 0) {
		header("location:formLogin.php?errorC=" . $miToken);
	} else {
		$queryInsertUser  = ("INSERT INTO usuarios(emailUser,passwordUser, nameUser,createUser) 
							VALUES ('$emailUser','$PasswordHash','$nameUser','$createUser')");
		$resultInsertUser = mysqli_query($con, $queryInsertUser);
		header("location:index.php?fineS=" . $miToken);
	}
}
