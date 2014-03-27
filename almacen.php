<?php 
session_start(); //necesario para sesiones
include("php/conex.php");
include("php/funciones.php");
?>

<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Almacen</title>
	<link rel="shortcut icon" href="favicon.ico" />
<!--jQuery dependencies-->
	<link href="css/south-street/jquery-ui-1.10.4.custom.css" rel="stylesheet">
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>
<!--PQ Grid files-->
    <link rel="stylesheet" href="pqgrid.min.css" />
    <script src="pqgrid.min.js"></script>
<!--PQ Grid Office theme-->
<script src="js/jquery.ui.touch-punch.js"></script>
	<script>
	$(function() {
		$( "#tabs" ).tabs();
		$( "#radioset" ).buttonset();
	});
	</script>
	<style>
	body{
		font: 62.5% "Trebuchet MS", sans-serif;
		margin: 50px;
	}

	#logo-events	aside {
	float: right;
	margin-bottom: -18px;
	}
	
	#top_dc button{
		float: right;
	}
	
	
	.ui-dialog *
	{
		font-family:Tahoma;
		font-size:11px;
	}
	.ui-dialog form#crud-form
	{
		margin-top:20px;
	}
	.ui-dialog form#crud-form input
	{
		width:230px;
		overflow:visible;/*fix for IE*/
	}
	
	.ui-dialog form#crud-form select
	{
		width:230px;
		overflow:visible;/*fix for IE*/
	}
	
	.ui-dialog form#crud-form td.label
	{
		font-weight:bold;padding-right:5px;
	}
	
	div.pq-grid td.checkboxColumn
	{
		/*give a separate background to the checkbox column*/
		background:#ddf;
	}
	td.checkboxColumn div
	{
		padding:2px! important;        
	}
	.PedidosTable
	{
		display:none;
	}
	.StockTable
	{
		display:none;
	}
	.pedido_table
	{
		display:none;
	}
	</style>
	<script src="js/almacenes.js"></script>
   
</head>
<body>

	<div id=top_dc >
		<button id = "desconectar" class="desconectar" >Desconectar</button>	
		<script>
		$("button").button({ icons: { primary: "ui-icon-circle-close"} }).click(function (evt) {
			desvalidar();
			
		});
		$( "button" ).position({
			  my: "right top",
			  at: "right top+20"
			 });
		</script>
	</div>
	<div id="logo-events" class="constrain clearfix">
		<div class="logo">
			<img src="img/logo.png" />
		</div>
		<aside><form style="margin-top: 1em;">
			<div id="radioset">
				<input type="radio" id="radio1" name="radio" checked="checked"><label for="radio1">Stock</label>
				<input type="radio" id="radio2" name="radio"><label for="radio2">Pedidos</label>
			</div>

		</form></aside>
	</div>


	<!-- Proveedor -->
	<h2 class="proveedor">
		<?php
		print $_SESSION["minombremac"];
		?>
	</h2>
	<div id="tabs">
	<!-- Contenido -->
		<ul> <!--Almacen Tab -->
			<li><a href="#tabs-1">Almacen</a></li>
		</ul>
		<!--Tabla Stock -->
		<div id="tabs-1">
			<div id="pedido_table" class="pedido_table"></div>
			<div id="grid_table"></div>
		</div>
	<!-- End Contenido -->
	</div>

	<!-- Tabla html oculta -->
	<?php 
	crearTabla();
	crearPedidos();
	?>
	<!-- End Tabla html oculta -->
	<!-- Dialogo crud -->
	<div id="popup-dialog-crud" >
		<form id="crud-form" class="pq-grid-crud-popup" method="post" name="crud-form" action="validar.php">
			<input type="hidden" name="recId" value="">
			<table align="center"><tbody>
				<tr>            
					<td class="label">Variedad:</td>
					<td>
					<?php	
					dameArticulos();
					?>
					</td>
				</tr><tr>            
					<td class="label">Confección:</td>            
					<td><select name="confeccion" id="confeccion">
					<option value=''>- Primero seleccione una variedad -</option>	
					<?php
					//dameConfecciones('CEBOLLA DOUX');	
					?></td>           
				</tr><tr>            
					<td class="label">Marca:</td>
					<td>
					<?php	
					dameMarca();
					?>
					</td>           
				</tr><tr>            
					<td class="label">CAT:</td>
					<td>
					<?php	
					dameCategoria();
					?>
					</td>           
				</tr><tr>            
					<td class="label">Calibre:</td>
					<td><select name="calibre" id="calibre">
					<option value=''>- Primero seleccione una variedad -</option>
					</select>
					</td>           
				</tr><tr>            
					<td class="label">Trazabilidad:</td>
					<td><input type="text" name="trazabilidad"></td>           
				</tr><tr>
					<td class="label">Cajas:</td>
					<td><input type="number" name="cajas" min=0 value="0"></td>
				</tr>
			</tbody></table>
		</form>
	</div>
	<!-- End Dialogo crud -->
	<!-- Dialogo eliminar -->
	<div id="popup-dialog-eliminar" ></div>
	<!-- End Dialogo eliminar -->
	<!-- Script para calibre y confeccion dinamicos -->
	<script>
	$("#variedad").on("change", buscarCalibre);
	$("#variedad").on("change", buscarConfeccion);
	</script>
</body>
</html>
