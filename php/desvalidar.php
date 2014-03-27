<?php
	session_start(); //necesario para crear la sesión

		$_SESSION["usuario"] = '';
		$_SESSION["misesionmac"] = ''; 
		$_SESSION["minombremac"] = '';
		$_SESSION["autentificado"]= "no"; 
		$_SESSION["ultimoAcceso"]= date("j-n-Y H:i:s");
		$_SESSION["error"]="1";
		echo "Vale";
?>