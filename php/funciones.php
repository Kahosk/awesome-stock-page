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
	$htmlCode = '';
	
	if(!$Ustock)  
		print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
	
	

	
		$htmlCode .= '<table border="1" cellspacing="0" cellpadding="0" class="StockTable" id="StockTable" style="">
			<tbody><tr valign="middle" id="header">
				<th class="lineNum">lineNum</th>
				<th class="variedad">Variedad</th>
				<th class="cajas">Cajas</th>
				<th class="cat">CAT</th>
				<th class="calibre">Calibre</th>
				<th class="confeccion">Confección</th>
				<th class="bruto">Bruto</th>
				<th class="tara">Tara</th>
				<th class="neto">Neto</th>
				<th class="marca">Marca</th>
				<th class="trazabilidad">Trazabilidad</th>
				
			</tr>';
		while(!$Ustock->EOF) {
			  $htmlCode .= '<tr id="Tr1" class="rowcolor1">';
			  $htmlCode .= '<td class="lineNum">';
			  $htmlCode .= $Ustock->fields[0];
			  $htmlCode .= '</td>';
			  
			  $htmlCode .= '<td class="variedad">';
			  $htmlCode .= $Ustock->fields[2];
			  $htmlCode .= '</td>';

			  $htmlCode .= '<td class="cajas">';
			  $htmlCode .= $Ustock->fields[3];
			  $htmlCode .= '</td>';	
			  
			  $htmlCode .= '<td class="cat">';
			  $htmlCode .= $Ustock->fields[4];
			  $htmlCode .= '</td>';

			  $htmlCode .= '<td class="calibre">';
			  $htmlCode .= $Ustock->fields[5];
			  $htmlCode .= '</td>';
			  
			  $htmlCode .= '<td class="confeccion">';
			  $htmlCode .= $Ustock->fields[6];
			  $htmlCode .= '</td>';

			  $htmlCode .= '<td class="bruto">';
			  $htmlCode .= $Ustock->fields[7];
			  $htmlCode .= '</td>';

			  $htmlCode .= '<td class="tara">';
			  $htmlCode .= $Ustock->fields[8];
			  $htmlCode .= '</td>';

			  $htmlCode .= '<td class="neto">';
			  $htmlCode .= $Ustock->fields[9];
			  $htmlCode .= '</td>';			  

			  $htmlCode .= '<td class="marca">';
			  $htmlCode .= $Ustock->fields[10];
			  $htmlCode .= '</td>';

			  $htmlCode .= '<td class="trazabilidad">';
			  $htmlCode .= $Ustock->fields[11];
			  $htmlCode .= '</td>';

			$htmlCode .= '</tr>';								
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
	$htmlCode .= '<!-- repeating rows end -->
		</tbody></table>';	
	}  
												                      

				
  
	$Ustock->Close( );
	$conexion->Close( );
	return $htmlCode;
}

function crearPedidos(){
	$htmlCode = '';
	$conexion = conectaBaseDatos();
	//**** Cambiar igual que la tabla de arriba
	//$Ustock = &$conexion->Execute("select t1.DocNum as DocNum, t0.dscription as variedad, t0.u_mac_conf as conf, t0.u_mac_marca as marca, t0.u_mac_cat as cat, t0.u_mac_cal as cal, CAST(t0.U_MAC_BULTOS as int) as cajas, t0.DocEntry as DocEntry, t0.LineNum as LineNum, t0.U_MAC_ProveedorN as U_MAC_ProveedorN, t1.U_MAC_Matricula as matricula from rdr1 t0 inner join ordr t1 on t0.DocEntry=t1.DocEntry where t0.U_MAC_ProveedorN = '$_SESSION[minombremac]' and t1.DocStatus != 'C'");
	
	//$Ustock = &$conexion->Execute("select t0.DocEntry as DocEntry, t0.DocNum as DocNum, t0.U_MAC_Matricula as matricula, t0.DocDueDate from ordr t0 left join rdr1 t1 on t0.DocEntry=t1.DocEntry where t1.U_Mac_ProveedorN = '$_SESSION[minombremac]' group by t0.DocEntry, t0.DocNum, t0.U_MAC_Matricula, t0.DocDueDate");

	$Ustock = &$conexion->Execute("select t1.DocNum as DocNum, t0.dscription as variedad, t0.u_mac_conf as conf, t0.u_mac_marca as marca, t0.u_mac_cat as cat, t0.u_mac_cal as cal, CAST(t0.U_MAC_BULTOS as int) as cajas, t0.DocEntry as DocEntry, t0.LineNum as LineNum, t0.U_MAC_ProveedorN as U_MAC_ProveedorN, t1.U_MAC_Matricula as matricula from rdr1 t0 inner join ordr t1 on t0.DocEntry=t1.DocEntry where t0.U_MAC_ProveedorN = '$_SESSION[minombremac]'");

	if(!$Ustock)  
		print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {

		$htmlCode .=  '<table border="1" cellspacing="0" cellpadding="0" class="PedidosTable" style="">
			<tbody><tr valign="middle" id="header">
				<th class="npedido">Nº Pedido</th>
				<th class="matricula">Matricula</th>
				<th class="variedad">Variedad</th>
				<th class="confeccion">Confección</th>
				<th class="marca">Marca</th>
				<th class="cat">CAT</th>
				<th class="calibre">Calibre</th>
				<th class="cajas">Cajas</th>
				<th class="docentry">DocEntry</th>
				<th class="linenum">LineNum</th>
			</tr>';
		while(!$Ustock->EOF) {  
			  $htmlCode .= '<tr id="Tr1" class="rowcolor1">';
			  $htmlCode .=  '<td class="npedido">';
			  $htmlCode .=  $Ustock->fields[0];
			  $htmlCode .=  '</td>';
			  
			  $htmlCode .=  '<td class="matricula">';
			  $htmlCode .= $Ustock->fields[10];
			  $htmlCode .= '</td>';
			  
			  $htmlCode .= '<td class="variedad">';
			  $htmlCode .= $Ustock->fields[1];
			  $htmlCode .= '</td>';
			  
			  

			  $htmlCode .= '<td class="confeccion">';
			  $htmlCode .= $Ustock->fields[2];
			  $htmlCode .= '</td>';
			  

			  $htmlCode .= '<td class="marca">';
			  $htmlCode .= $Ustock->fields[3];
			  $htmlCode .= '</td>';

			  $htmlCode .= '<td class="cat">';
			  $htmlCode .= $Ustock->fields[4];
			  $htmlCode .= '</td>';
			  

			  $htmlCode .= '<td class="calibre">';
			  $htmlCode .= $Ustock->fields[5];
			  $htmlCode .= '</td>';
			  			  
			  $htmlCode .= '<td class="cajas">';
			  $htmlCode .= $Ustock->fields[6];
			  $htmlCode .= '</td>';
			  
			  $htmlCode .= '<td class="docentry">';
			  $htmlCode .= $Ustock->fields[7];
			  $htmlCode .= '</td>';
			  
			  $htmlCode .= '<td class="linenum">';
			  $htmlCode .= $Ustock->fields[8];
			  $htmlCode .= '</td>';
			  
			$htmlCode .= '</tr>';								
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
	$htmlCode .= '<!-- repeating rows end -->
		</tbody></table>';
	//print $htmlCode;
	}  
												                      

				
  
	$Ustock->Close( );
	$conexion->Close( );
	return $htmlCode;
}

function crearListaPedidos(){
	$htmlCode = '';
	$conexion = conectaBaseDatos();
	//**** Cambiar igual que la tabla de arriba
	//$Ustock = &$conexion->Execute("select t1.DocNum as DocNum, t0.dscription as variedad, t0.u_mac_conf as conf, t0.u_mac_marca as marca, t0.u_mac_cat as cat, t0.u_mac_cal as cal, CAST(t0.U_MAC_BULTOS as int) as cajas, t0.DocEntry as DocEntry, t0.LineNum as LineNum, t0.U_MAC_ProveedorN as U_MAC_ProveedorN, t1.U_MAC_Matricula as matricula from rdr1 t0 inner join ordr t1 on t0.DocEntry=t1.DocEntry where t0.U_MAC_ProveedorN = '$_SESSION[minombremac]' and t1.DocStatus != 'C'");
	
	$Ustock = &$conexion->Execute("select t0.DocEntry as DocEntry, t0.DocNum as DocNum, t0.U_MAC_Matricula as matricula, Convert(varchar(10),CONVERT(date,t0.DocDueDate,106),103) as fecha from ordr t0 left join rdr1 t1 on t0.DocEntry=t1.DocEntry where t1.U_Mac_ProveedorN = '$_SESSION[minombremac]' group by t0.DocEntry, t0.DocNum, t0.U_MAC_Matricula, t0.DocDueDate");

	//$Ustock = &$conexion->Execute("select t1.DocNum as DocNum, t0.dscription as variedad, t0.u_mac_conf as conf, t0.u_mac_marca as marca, t0.u_mac_cat as cat, t0.u_mac_cal as cal, CAST(t0.U_MAC_BULTOS as int) as cajas, t0.DocEntry as DocEntry, t0.LineNum as LineNum, t0.U_MAC_ProveedorN as U_MAC_ProveedorN, t1.U_MAC_Matricula as matricula from rdr1 t0 inner join ordr t1 on t0.DocEntry=t1.DocEntry where t0.U_MAC_ProveedorN = '$_SESSION[minombremac]'");

	if(!$Ustock)  
		print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {

		$htmlCode .=  '<table border="1" cellspacing="0" cellpadding="0" class="ListaPedidosTable" style="">
			<tbody><tr valign="middle" id="header">
				<th class="docentry">DocEntry</th>
				<th class="npedido">Nº Orden de carga</th>
				<th class="matricula">Matricula</th>
				<th class="linenum">Fecha de carga</th>
			</tr>';
		while(!$Ustock->EOF) {  
			  $htmlCode .= '<tr id="Tr1" class="rowcolor1">';
			  
			  $htmlCode .= '<td class="docentry">';
			  $htmlCode .= $Ustock->fields[0];
			  $htmlCode .= '</td>';
			  
			  $htmlCode .=  '<td class="npedido">';
			  $htmlCode .=  $Ustock->fields[1];
			  $htmlCode .=  '</td>';
			  
			  $htmlCode .=  '<td class="matricula">';
			  $htmlCode .= $Ustock->fields[2];
			  $htmlCode .= '</td>';
			  
			  $htmlCode .= '<td class="fecha">';
			  $htmlCode .= $Ustock->fields[3];
			  $htmlCode .= '</td>';
			  
			$htmlCode .= '</tr>';								
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
	$htmlCode .= '<!-- repeating rows end -->
		</tbody></table>';
	
	}  
												                      			
  
	$Ustock->Close( );
	$conexion->Close( );
	return $htmlCode;
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
	
	$consulta .= " order by confeccion";
	$confeccion = &$conexion->Execute($consulta);
	if(!$confeccion)  
		print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
		$htmlCode.= '<option value="">- Seleccione una confección -</option>';
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
	$articulos = &$conexion->Execute("select t0.ItemName from OITM t0 inner join OITB t1 on t0.ItmsGrpCod=t1.ItmsGrpCod where isnull(t1.U_MAC_Tipo, '') <> '' order by t0.ItemName");
	if(!$articulos)  
    print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
	
	while(!$articulos->EOF) { 
	
	print '<option value="';
	print $articulos->fields[0];
	print '">';
	print $articulos->fields[0];
	print '</option>';
    $articulos->MoveNext( ); 
	}

	}
	$articulos->Close( );
	$conexion->Close( );
}

function dameMarca(){
    $conexion = conectaBaseDatos();
	$marca = &$conexion->Execute("select Code from [@MAC_MARCAS] order by Code");
	if(!$marca)  
    print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {

	while(!$marca->EOF) { 
	
	print '<option>';
	print $marca->fields[0];
	print '</option>';
    $marca->MoveNext( ); 
	}

	}
	$marca->Close( );
	$conexion->Close( );
}

function dameCategoria(){
    $conexion = conectaBaseDatos();
	$categoria = &$conexion->Execute("select Code from [@MAC_CATEGORIA] order by Code");
	if(!$categoria)  
    print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {

	while(!$categoria->EOF) { 
	
	print '<option value="';
	print $categoria->fields[0];
	print '">';
	print $categoria->fields[0];
	print '</option>';
    $categoria->MoveNext( ); 
	}

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
	$consulta .= " order by Calibre"; 
	$calibre = &$conexion->Execute($consulta);
	
	if(!$calibre)  
		print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
		$htmlCode.= '<option value="">- Seleccione un calibre -</option>';
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
           ,[variedad]
           ,[cajas]
           ,[cat]
           ,[calibre]
           ,[confeccion]
           ,[bruto]
           ,[tara]
           ,[neto]
           ,[marca]
           ,[trazabilidad]
           ,[familia])
     VALUES
           ('$_SESSION[misesionmac]'
           ,'".$row[1]."'
           ,'".$row[2]."'
           ,'".$row[3]."'
           ,'".$row[4]."'
           ,'".$row[5]."'
           ,'".$row[6]."'
           ,'".$row[7]."'
		   ,'".$row[8]."'
           ,'".$row[9]."'
           ,'".$row[10]."'
		   ,'NO')";
	$htmlCode.= $consulta;
	$conect = &$conexion->Execute($consulta);
	
	if(!$conect)  
		print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
			$htmlCode.= $consulta;
			$conect->Close( );
	//print $htmlCode;
	}
	
	$conexion->Close( );
	return $htmlCode;
}

function editaFila($row = ''){
    $htmlCode = '';
	$conexion = conectaBaseDatos();
	$consulta = "UPDATE [dbo].[U_MAC_STOCK]
		SET[IC] = '$_SESSION[misesionmac]'
           ,[variedad] = '".$row[1]."'
		   ,[cajas] = '".$row[2]."'
           ,[cat] = '".$row[3]."'
		   ,[calibre] = '".$row[4]."'
           ,[confeccion] = '".$row[5]."'
		   ,[bruto] = '".$row[6]."'
		   ,[tara] = '".$row[7]."'
		   ,[neto] = '".$row[8]."'
		   ,[marca] = '".$row[9]."'
           ,[trazabilidad] = '".$row[10]."'
		WHERE lineNum='".$row[0]."'";
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

function buscar($row = ''){
    $htmlCode = '';
	$conexion = conectaBaseDatos();
	$consulta = "select variedad,confeccion,marca,cat,calibre,trazabilidad,cajas from U_MAC_STOCK where IC ='$_SESSION[misesionmac]' and variedad = '".$row."'";
	$htmlCode.= $consulta;
	$conect = &$conexion->Execute($consulta);
	
	if(!$conect){ 
		print $conexion->ErrorMsg( );  
    }/* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
		$htmlCode = $conect->GetRows();
		//print_r($htmlCode);
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