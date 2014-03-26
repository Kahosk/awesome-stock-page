<?php

function conectaBaseDatos(){
	include("adodb/adodb.inc.php");  
	/* Incluimos el archivo de funciones */  
  
	$conexion = &ADONewConnection('odbc_mssql');  
	/* Creamos un objeto de conexión a SQL Server */  
  
	$datos = "Driver={SQL Server};Server=sbo9;Database=dasben;";  
	/* Definimos nuestro DSN */  
  
	$conexion->Connect($datos,'sa','B1Admin');  
	/* Hacemos la conexión con los parámetros correspondientes */  
	return $conexion;
	}

function crearTabla(){
	$conexion = conectaBaseDatos();
	$Ustock = &$conexion->Execute("select * from U_MAC_STOCK where IC ='$_SESSION[misesionmac]'");

	if(!$Ustock)  
		print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
	
	

	
		print '<table border="1" cellspacing="0" cellpadding="0" class="StockTable" style="">
			<tbody><tr valign="middle" id="header">
				<th class="variedad">Variedad</th>
				<th class="confeccion">Confección</th>
				<th class="marca">Marca</th>
				<th class="cat">CAT</th>
				<th class="calibre">Calibre</th>
				<th class="trazabilidad">Trazabilidad</th>
				<th class="cajas">Cajas</th>
				<th class="lineNum">lineNum</th>
			</tr>';
		while(!$Ustock->EOF) {  
			  print '<tr id="Tr1" class="rowcolor1">';
			  print '<td class="variedad">';
			  print $Ustock->fields[3];
			  print '</td>';
			  

			  print '<td class="confeccion">';
			  print $Ustock->fields[4];
			  print '</td>';
			  

			  print '<td class="marca">';
			  print $Ustock->fields[5];
			  print '</td>';

			  print '<td class="cat">';
			  print $Ustock->fields[6];
			  print '</td>';
			  

			  print '<td class="calibre">';
			  print $Ustock->fields[7];
			  print '</td>';
			  

			  print '<td class="trazabilidad">';
			  print $Ustock->fields[8];
			  print '</td>';
	
			  
			  print '<td class="cajas">';
			  print $Ustock->fields[2];
			  print '</td>';
	
			  print '<td class="lineNum">';
			  print $Ustock->fields[0];
			  print '</td>';
			  
			print '</tr>';								
			//print $Ustock->fields[0]." ";
			//print $Ustock->fields[1]." ";  
			//print $Ustock->fields[2]." ";
			//print $Ustock->fields[3]." ";
			//print $Ustock->fields[4]." ";
			//print $Ustock->fields[5]." ";			
			//header('Location: almacen.php');
			//EXIT();
			
			$Ustock->MoveNext( );  
			/* Avanzamos a la fila siguiente */
		}
			//header('Location: almacen.html');	
	print '<!-- repeating rows end -->
		</tbody></table>';	
	}  
												                      

				
  
	$Ustock->Close( );
	$conexion->Close( );
}




function dameConfecciones($variedad = ''){
	$htmlCode = '';
	$conexion = conectaBaseDatos();
	$consulta = "SELECT t0.U_MAC_con as confeccion
	FROM [@MAC_TIPOS_con] t0
	inner join OITB t1 on t0.code=t1.U_MAC_Tipo 
	inner join OITM t2 on t1.ItmsGrpCod =t2.ItmsGrpCod";
	
	if($variedad != ''){
        $consulta .= " WHERE t2.ItemName= '".$variedad."'";
    }
	$confeccion = &$conexion->Execute($consulta);
	if(!$confeccion)  
		print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
		
		while(!$confeccion->EOF) { 
			
			$htmlCode .= '<option value="';
			$htmlCode .= $confeccion->fields[0];
			$htmlCode .= '">';
			$htmlCode .= $confeccion->fields[0];
			$htmlCode .= '</option>';
			$confeccion->MoveNext( ); 
		}
	
	//print $htmlCode;
	}
	$confeccion->Close( );
	$conexion->Close ( );
	return $htmlCode;
}
function dameArticulos(){
    $conexion = conectaBaseDatos();
	$articulos = &$conexion->Execute("select t0.ItemName from OITM t0 inner join OITB t1 on t0.ItmsGrpCod=t1.ItmsGrpCod where isnull(t1.U_MAC_Tipo, '') <> ''");
	if(!$articulos)  
    print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
	print '<select name="variedad" id="variedad"><option value="">- Seleccione una variedad -</option>';
	while(!$articulos->EOF) { 
	
	print '<option value="';
	print $articulos->fields[0];
	print '">';
	print $articulos->fields[0];
	print '</option>';
    $articulos->MoveNext( ); 
	}
	print '</select>';
	}
	$articulos->Close( );
	$conexion->Close( );
}
function dameMarca(){
    $conexion = conectaBaseDatos();
	$marca = &$conexion->Execute("select * from [@MAC_MARCAS]");
	if(!$marca)  
    print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
	print '<select name="marca">';
	while(!$marca->EOF) { 
	
	print '<option>';
	print $marca->fields[0];
	print '</option>';
    $marca->MoveNext( ); 
	}
	print '</select>';
	}
	$marca->Close( );
	$conexion->Close( );
}
function dameCategoria(){
    $conexion = conectaBaseDatos();
	$categoria = &$conexion->Execute("select * from [@MAC_CATEGORIA]");
	if(!$categoria)  
    print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
	print '<select name="cat">';
	while(!$categoria->EOF) { 
	
	print '<option value="';
	print $categoria->fields[0];
	print '">';
	print $categoria->fields[0];
	print '</option>';
    $categoria->MoveNext( ); 
	}
	print '</select>';
	}
	$categoria->Close( );
	$conexion->Close( );
}

function dameCalibre($variedad = ''){
    $htmlCode = '';
	$conexion = conectaBaseDatos();
	$consulta = "SELECT t0.U_MAC_Cal as Calibre
	FROM [@MAC_TIPOS_CAL] t0
	inner join OITB t1 on t0.code=t1.U_MAC_Tipo 
	inner join OITM t2 on t1.ItmsGrpCod =t2.ItmsGrpCod";
	if($variedad != ''){
        $consulta .= " WHERE t2.ItemName= '".$variedad."'";
	}
	$calibre = &$conexion->Execute($consulta);
	
	if(!$calibre)  
		print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
		
		while(!$calibre->EOF) { 
			
			$htmlCode.= '<option value="';
			$htmlCode.= $calibre->fields[0];
			$htmlCode.= '">';
			$htmlCode.= $calibre->fields[0];
			$htmlCode.= '</option>';
			$calibre->MoveNext( ); 
		}
	//print $htmlCode;
	}
	$calibre->Close( );
	$conexion->Close( );
	return $htmlCode;
}


function eliminarFila($eliminar = ''){
    $htmlCode = '';
	$conexion = conectaBaseDatos();
	$consulta = "DELETE FROM U_MAC_STOCK
      WHERE IC ='$_SESSION[misesionmac]' and lineNum='".$eliminar."'";
	$htmlCode.= $consulta;
	$conect = &$conexion->Execute($consulta);
	
	if(!$conect)  
		print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
			$htmlCode.= $consulta;
	//print $htmlCode;
	}
	$conect->Close( );
	$conexion->Close( );
	return $htmlCode;
}

function anyadirFila($row = ''){
    $htmlCode = '';
	$conexion = conectaBaseDatos();
	$consulta = "INSERT INTO [dbo].[U_MAC_STOCK]
           ([IC]
           ,[cajas]
           ,[variedad]
           ,[confeccion]
           ,[marca]
           ,[cat]
           ,[calibre]
           ,[trazabilidad])
     VALUES
           ('$_SESSION[misesionmac]'
           ,'".$row[6]."'
           ,'".$row[0]."'
           ,'".$row[1]."'
           ,'".$row[2]."'
           ,'".$row[3]."'
           ,'".$row[4]."'
           ,'".$row[5]."')";
	$htmlCode.= $consulta;
	$conect = &$conexion->Execute($consulta);
	
	if(!$conect)  
		print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
			$htmlCode.= $consulta;
	//print $htmlCode;
	}
	$conect->Close( );
	$conexion->Close( );
	return $htmlCode;
}

function editaFila($row = ''){
    $htmlCode = '';
	$conexion = conectaBaseDatos();
	$consulta = "UPDATE [dbo].[U_MAC_STOCK]
		SET[IC] = '$_SESSION[misesionmac]'
           ,[cajas] = '".$row[6]."'
           ,[variedad] = '".$row[0]."'
           ,[confeccion] = '".$row[1]."'
           ,[marca] = '".$row[2]."'
           ,[cat] = '".$row[3]."'
           ,[calibre] = '".$row[4]."'
           ,[trazabilidad] = '".$row[5]."'
		WHERE lineNum='".$row[7]."'";
	$htmlCode.= $consulta;
	$conect = &$conexion->Execute($consulta);
	
	if(!$conect)  
		print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
			$htmlCode.= $consulta;
	//print $htmlCode;
	}
	$conect->Close( );
	$conexion->Close( );
	return $htmlCode;
}


/* agrega
INSERT INTO [dbo].[U_MAC_STOCK]
           ([IC]
           ,[cajas]
           ,[variedad]
           ,[confeccion]
           ,[marca]
           ,[cat]
           ,[calibre]
           ,[trazabilidad])
     VALUES
           (<IC, nvarchar(15),>
           ,<cajas, numeric(19,0),>
           ,<variedad, nvarchar(100),>
           ,<confeccion, nvarchar(50),>
           ,<marca, nvarchar(30),>
           ,<cat, nvarchar(30),>
           ,<calibre, nvarchar(30),>
           ,<trazabilidad, numeric(18,0),>)
edita
UPDATE [dbo].[U_MAC_STOCK]
   SET [IC] = <IC, nvarchar(15),>
      ,[cajas] = <cajas, numeric(19,0),>
      ,[variedad] = <variedad, nvarchar(100),>
      ,[confeccion] = <confeccion, nvarchar(50),>
      ,[marca] = <marca, nvarchar(30),>
      ,[cat] = <cat, nvarchar(30),>
      ,[calibre] = <calibre, nvarchar(30),>
      ,[trazabilidad] = <trazabilidad, numeric(18,0),>
 WHERE <Search Conditions,,>
elimina
DELETE FROM [dbo].[U_MAC_STOCK]
      WHERE <Search Conditions,,> */

?>