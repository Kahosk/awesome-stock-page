<?php
	session_start(); //necesario para crear la sesión
	//Conectar a la base de datos
	include("conex.php");
	//include("funciones.php"); 
	 
	echo("Aqui");
	$login= $_POST['name'];
	$clave= $_POST['password'];

	// $login=$login;
	// $clave=$clave;

	//codifica el password
	//$clavecodif=md5($clave);
	
	//Consulta en bd
	//$result = mysql_query("SELECT  cardCode ,U_MAC_Pass FROM OCRD WHERE cardCode='$login'");
	$_SESSION["error"]= "1";
	$resultado = &$conexion->Execute("SELECT  cardCode ,U_MAC_Pass, cardName  FROM OCRD WHERE cardCode='$login'"); 
	
	if(!$resultado){
		
		print $conexion->ErrorMsg( );
	
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */  
	}else {  
		while(!$resultado->EOF) {  
			/* Ejecutamos un ciclo de tipo While que realizará las operaciones hasta que el resultado de la consulta (los registros rescatados) llegue al EOF o End of File (final de los registros recuperados) */  
		if($resultado->fields[1] == $clave){
			$_SESSION["usuario"] = "$login";
	  		$_SESSION["misesionmac"] = "$login"; 
			$_SESSION["minombremac"] = $resultado->fields[2];
			$_SESSION["autentificado"]= "si"; 
			$_SESSION["ultimoAcceso"]= date("j-n-Y H:i:s");
			$_SESSION["error"]="0";
			print $resultado->fields[0]." ";
			print $resultado->fields[1];  
			//print $resultado->fields[2]." ";  
			header('Location: ../almacen.php');
			EXIT();
			}
			$resultado->MoveNext( );  
			/* Avanzamos a la fila siguiente */  
		} 
		$_SESSION["autentificado"]= "no";
		$_SESSION["error"]= "2";
		//header('Location: almacen.html');	
	}  
	  
	$resultado->Close( );  
	$conexion->Close( );
	header('Location: ../index.php');	
	EXIT();
?>