<?php
    //$dbcnx=mysql_connect("sbo9","sa","B1Admin"); //usuario y contraseña de la base de datos
    
	
	include("adodb/adodb.inc.php");  
	/* Incluimos el archivo de funciones */  
  
	$conexion = &ADONewConnection('odbc_mssql');  
	/* Creamos un objeto de conexión a SQL Server */  
  
	$datos = "Driver={SQL Server};Server=sbo9;Database=dasben;";  
	/* Definimos nuestro DSN */  
  
	$conexion->Connect($datos,'sa','B1Admin');  
	/* Hacemos la conexión con los parámetros correspondientes */  
	
	
	$bd="dasben"; 
	$tabla1="usuarios"; 
	$tabla2="incidencias";  
	$tabla3="historico_incidencias";  
    //mysql_select_db($bd) or die("Error, No se puede acceder a la base de datos en estos momentos, Inténtelo de nuevo más tarde");
?>