<?php
    //$dbcnx=mysql_connect("xxxxx","xxxxx","xxxxxx"); //usuario y contrase�a de la base de datos
    
	
	include("adodb/adodb.inc.php");  
	/* Incluimos el archivo de funciones */  
  
	$conexion = &ADONewConnection('odbc_mssql');  
	/* Creamos un objeto de conexi�n a SQL Server */  
  
	$datos = "xxxx";  
	/* Definimos nuestro DSN */  
  
	$conexion->Connect($datos,'xxxxx','xxxxxx');  
	/* Hacemos la conexi�n con los par�metros correspondientes */  
	
	
	$bd="dasben"; 
	$tabla1="usuarios"; 
	$tabla2="incidencias";  
	$tabla3="historico_incidencias";  
    //mysql_select_db($bd) or die("Error, No se puede acceder a la base de datos en estos momentos, Int�ntelo de nuevo m�s tarde");
?>