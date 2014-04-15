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
		margin-top: 0px;
	}
	
	#tabs{
	
	heigth:500px;
	
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

	</style>
	<script src="js/almacenes.js"></script>
 </head>
<body>

	<div id=top_dc class=top_dc >
	<table width=100%><tbody><tr>
	<td width=30%></td><td width=70%>
	<button id = "desconectar" class="desconectar" >Desconectar</button>	
	</td></tr><tr ><td width=30%>
	<h2 class="proveedor"><!-- Proveedor -->
		<?php
		print $_SESSION["minombremac"];
		?>
	</h2></td><td width=70%>
	<form>
		<div id="radioset" align=right>
			<input type="radio" id="radio1" name="radio" checked value='off'><label for="radio1">Stock</label>
			<input type="radio" id="radio2" name="radio" value='on'><label for="radio2">Orden de carga</label>
		</div>
	</form>
	</td></tr></tbody></table>
	</div>
	<script>
		$("button").button({ icons: { primary: "ui-icon-circle-close"} }).click(function (evt) {
			desvalidar();
			
		});
		
	</script>
	
	<div id="tabs">
	<!-- Contenido -->
		<ul> <!--Almacen Tab -->
			<li><a href="#tabs-1">Almacen</a></li>
		</ul>
		<!--Tabla Stock -->
		<div id="tabs-1">
			<div id="pedido_table" class="pedido_table"></div>
			<div class=select_row_display_div id=select_row_display_div></div>
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
		<form id="crud-form" class="pq-grid-crud-popup" method="post" name="crud-form">
			<input type="hidden" name="recId" value="">
			<table align="center"><tbody>
				<tr>            
					<td class="label">Variedad:</td>
					<td>
					<select name="variedad" id="variedad"><option value="">- Seleccione una variedad -</option>
					<?php	
					dameArticulos();
					?>
					</select>
					</td>
				</tr><tr>            
					<td class="label">Confección:</td>            
					<td><select name="confeccion" id="confeccion">
					<option value=''>- Primero seleccione una variedad -</option>	
					<?php
					//dameConfecciones('CEBOLLA DOUX');	
					?>
					</select>
					</td>           
				</tr><tr>            
					<td class="label">Marca:</td>
					<td>
					<select name="marca">
					<?php	
					dameMarca();
					?>
					</select>
					</td>           
				</tr><tr>            
					<td class="label">CAT:</td>
					<td>
					<select name="cat">
					<?php	
					dameCategoria();
					?>
					</select>
					</td>           
				</tr><tr>            
					<td class="label">Calibre:</td>
					<td><select name="calibre" id="calibre" >
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
	<!-- Barra add -->
	<div id="add-crud" style="display:none">
		<form id="crud-form-add" name="crud-form-add">
			<input type="hidden" name="recId" value="">
			<table align="center"><tbody>
				<tr>            
					<td>
					<select name="variedad-add" id="variedad-add"><option value="">- Seleccione una variedad -</option>
					<?php
					dameArticulos();
					?>
					</select>
					</td>

					<td>
					<select name="confeccion-add" id="confeccion-add">
					<option value=''>- Primero seleccione una variedad -</option>	
					<?php
					//dameConfecciones('CEBOLLA DOUX');	
					?>
					</select>
					</td>           
			           
		
					<td>
					<select name="marca-add">
					<?php	
					dameMarca();
					?>
					</select>
					</td>           
			          
		
					<td>
					<select name="cat-add">
					<?php	
					dameCategoria();
					?>
					</select>
					</td>           
			          
			
					<td><select name="calibre-add" id="calibre-add">
					<option value=''>- Primero seleccione una variedad -</option>
					</select>
					</td>           
			          
			
					<td><input type="text" name="trazabilidad-add" id="trazabilidad-add" placeholder="Trazabilidad"></td>           
		
				
					<td><input type="number" name="cajas-add" id="cajas-add" min=0 placeholder="Cajas"></td>

				</tr>
			</tbody></table>
		</form>
							<div id="add-form-button">
					
					</div>
	</div>
	<!-- End Barra add -->
	<!-- Dialogo eliminar -->
	<div id="popup-dialog-eliminar" ></div>
	<!-- End Dialogo eliminar -->
	<!-- Script para calibre y confeccion dinamicos -->
	<script>
	$("#variedad").on("change", buscarCalibre);
	$("#variedad").on("change", buscarConfeccion);
	
	$("#variedad-add").on("change", buscarCalibreAdd);
	$("#variedad-add").on("change", buscarConfeccionAdd);
	
	
	
	if($('#radioset :radio:checked').val()=='off'){
		$(".pedido_table").css({ display:"none" });
		$(".toolbar-pedido").css({ display:"none" });
	}
	$("input:radio[name=radio]").click(function(){
		var value = $(this).val();
		var $grid = $("#grid_table").pqGrid();
		var colM = $grid.pqGrid("option", "colModel");
		if (value=='on') {
			$(".pedido_table").css({ display:"block" });
			$(".toolbar").css({ display:"none" });
			colM[8].hidden = false;
			$grid.pqGrid("option", "colModel", colM);
			new_size();
			//pq-grid-toolbar display:none
			//alert(value);
		}else{
			$(".pedido_table").css({ display:"none" });
			$(".toolbar").css({ display:"block" });
			$(".toolbar-pedido").css({ display:"none" });
			colM[8].hidden = true;
			var tbl = $("table.StockTable");
			var obj = $.paramquery.tableToArray(tbl);
			$grid.pqGrid("option", "colModel", colM);
			var DM = $grid.pqGrid("option", "dataModel");
			DM.data = obj.data;
			$grid.pqGrid("refreshDataAndView");
			new_size();
			
			//window.location.href = 'almacen.php'
			
			//alert(value);
			//.pedido_table{display:none;}
		}
	});
	
	</script>
</body>
</html>
