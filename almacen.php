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
	</style>
	<script>
    $(function () {
	var tbl = $("table.StockTable");
	
	var ht = $("#tabs").innerHeight()-35;		
	var wd = $("#tabs").innerWidth()-50;

	var obj = $.paramquery.tableToArray(tbl);
	var newObj = { width: wd, ht: ht,title:"Stock",resizable:false,draggable:true };
	newObj.dataModel = { data: obj.data};
	newObj.colModel = [{ title: "Variedad", width: 250, dataType: "string", resizable:true },
        { title: "Confección", width: 100, dataType: "string", align: "right", resizable:true },
        { title: "Marca", width: 100, dataType: "string",align: "right", resizable:true},
        { title: "CAT",width: 50, dataType: "string",align: "right", resizable:true},
        { title: "Calibre", width: 50, dataType: "string",align: "right", resizable:true },
        { title: "Trazabilidad", width: 150, dataType: "string",align: "right", resizable:true },
		{ title: "Cajas", width: 50, dataType: "integer",align: "right", resizable:true },
		{ title: "lineNum", hidden: true, dataType: "integer"}];
	//$("#grid_table").pqGrid(newObj);
	
	tbl.css("display", "none");
        

     //append or prepend the CRUD toolbar to .pq-grid-top or .pq-grid-bottom
    $("#grid_table").on("pqgridrender", function (evt, newObj) {
        var $toolbar = $("<div class='pq-grid-toolbar pq-grid-toolbar-crud'></div>").appendTo($(".pq-grid-top", this));
 
        $("<span>Añadir</span>").appendTo($toolbar).button({ icons: { primary: "ui-icon-circle-plus"} }).click(function (evt) {
			addRow();
        });
        $("<span>Editar</span>").appendTo($toolbar).button({ icons: { primary: "ui-icon-pencil"} }).click(function (evt) {
			
			editRow();
			buscarCalibre();
			buscarConfeccion();
			
        });
        $("<span>Eliminar</span>").appendTo($toolbar).button({ icons: { primary: "ui-icon-circle-minus"} }).click(function () {
            deleteRow();
        });
        $toolbar.disableSelection();
    });
 
    
    // create popup dialog.
    $("#popup-dialog-crud").dialog({ width: 400, modal: true,
        open: function () { $(".ui-dialog").position({ of: "#grid_table" }); },
        autoOpen: false,
		show: {
			effect: "scale",
			duration: 500
		},
		hide: {
			effect: "scale",
			duration: 500
		}
    });
	// create popup dialog.
    $("#popup-dialog-eliminar").dialog({ width: 300, modal: true,
        open: function () { $(".ui-dialog").position({ of: "#grid_table" }); },
        autoOpen: false,
		show: {
                    effect: "scale",
                    duration: 500
                },
		hide: {
			effect: "scale",
			duration: 500
		}
    });
	var $grid = $("#grid_table").pqGrid(newObj);
    // edit Row
    function editRow() {
        var rowIndx = getRowIndx();

        if (rowIndx != null) {
            var DM = $grid.pqGrid("option", "dataModel");
            var data = DM.data;
            var row = data[rowIndx];
            var $frm = $("form#crud-form");
            
            $frm.find("select[name='variedad']").val(row[0]);
            $frm.find("select[name='confeccion']").val(row[1]);
            $frm.find("select[name='marca']").val(row[2]);
            $frm.find("select[name='cat']").val(row[3]);
            $frm.find("select[name='calibre']").val(row[4]);
			$frm.find("input[name='trazabilidad']").val(row[5]);
			$frm.find("input[name='cajas']").val(row[6]);
 
            $("#popup-dialog-crud").dialog({ title: "Editar fila (" + (rowIndx + 1) + ")", buttons: {
                Actualizar: function () {
                    // save the record in DM.data.
					
					if ($frm.find("select[name='variedad']").val()=='' || $frm.find("select[name='confeccion']").val()=='' || 
					$frm.find("select[name='marca']").val()=='' || $frm.find("select[name='cat']").val()=='' ||
                    $frm.find("select[name='calibre']").val()=='' || $frm.find("input[name='trazabilidad']").val()=='' || $frm.find("input[name='cajas']").val() =='' ){
					alert('Hay que rellenar todos los campos');
					}else{
                    
                    row[0] = $frm.find("select[name='variedad']").val();
                    row[1] = $frm.find("select[name='confeccion']").val();
                    row[2] = $frm.find("select[name='marca']").val();
                    row[3] = $frm.find("select[name='cat']").val();
                    row[4] = $frm.find("select[name='calibre']").val();
					row[5] = $frm.find("input[name='trazabilidad']").val();
					row[6] = $frm.find("input[name='cajas']").val();
					EditarLinea(row);

					$grid.pqGrid("refreshRow", { rowIndx: rowIndx }).pqGrid('setSelection', { rowIndx: rowIndx });
					$(this).dialog("close");
					}
                },
                Cancelar: function () {
                    $(this).dialog("close");
                }
            }
            }).dialog("open");
        }
    }
    // append Row
    function addRow() {
        // debugger;
        var DM = $grid.pqGrid("option", "dataModel");
        var data = DM.data;
 
        var $frm = $("form#crud-form");
        $frm.find("input").val("");
 
        $("#popup-dialog-crud").dialog({ title: "Añadir", buttons: {
            Añadir: function () {                    
                var row = [];
                // save the record in DM.data.
				row[0] = $frm.find("select[name='variedad']").val();
				row[1] = $frm.find("select[name='confeccion']").val();
				row[2] = $frm.find("select[name='marca']").val();
				row[3] = $frm.find("select[name='cat']").val();
				row[4] = $frm.find("select[name='calibre']").val();
				row[5] = $frm.find("input[name='trazabilidad']").val();
				row[6] = $frm.find("input[name='cajas']").val();
                
				if (row[2]=='' || row[3]=='' || row[4]=='' || row[5]=='' || row[6]=='') {
					
					alert('Hay que rellenar todos los campos');
					
				}else{
				AnyadirLinea(row);
				data.push(row);
                $grid.pqGrid("refreshDataAndView");
                $(this).dialog("close");
				}
            },
            Cancelar: function () {
                $(this).dialog("close");
            }
        }
        });
        $("#popup-dialog-crud").dialog("open");
    }
    // delete Row.
    function deleteRow() {

		var rowIndx = getRowIndx();
		if (rowIndx != null) {
		
	    $("#popup-dialog-eliminar").dialog({ title: "Eliminar fila (" + (rowIndx + 1) + ")", buttons: {
            Si: function () {                    
                
					var DM = $grid.pqGrid("option", "dataModel");
					var data = DM.data;
					var row = data[rowIndx];
					
					//alert(row[7]);
					EliminarFila(row[7]);
					DM.data.splice(rowIndx, 1);
					$grid.pqGrid("refreshDataAndView");
					$grid.pqGrid("setSelection", { rowIndx: rowIndx });
				
					$(this).dialog("close");
            },
            Cancelar: function () {
			
                $(this).dialog("close");
            }
        }
        });
		
        $("#popup-dialog-eliminar").dialog("open");	
		}
		
    }
    function getRowIndx() {
        // var $grid = $("#grid_render_cells");
 
        // var obj = $grid.pqGrid("getSelection");
        // debugger;
        var arr = $grid.pqGrid("selection", { type: 'row', method: 'getSelection' });
        if (arr && arr.length > 0) {
            var rowIndx = arr[0].rowIndx;
 
            // if (rowIndx != null && colIndx == null) {
            return rowIndx;
        }
        else {
            alert("Selecciona una fila.");
            return null;
        }
    }

});

$( window ).resize(function() {
 		var ht = $("#tabs").innerHeight()-35;		
		var wd = $("#tabs").innerWidth()-35;
		$( "#grid_table" ).pqGrid( {width:wd} );
		
});

function buscarCalibre(){
    $variedad = $("#variedad").val();
 
    if($variedad == ""){
            $("#calibre").html("<option value=''>- Primero seleccione una variedad -</option>");
    }
    else {
        $.ajax({
            dataType: "json",
            data: {"variedad": $variedad},
            url:   'php/buscarCal.php',
            type:  'post',
            beforeSend: function(){
                //Lo que se hace antes de enviar el formulario
                },
            success: function(respuesta){
                //lo que se si el destino devuelve algo
				//alert('OK');
                $("#calibre").html(respuesta.html);
            },
            error:    function(xhr,err){ 
                alert("Calibre\nreadyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
            }
        });
    }
}

function buscarConfeccion(){
    $variedad = $("#variedad").val();
 
    if($variedad == ""){
            $("#confeccion").html("<option value=''>- Primero seleccione una variedad -</option>");
    }
    else {
        $.ajax({
            dataType: "json",
            data: {"variedad": $variedad},
            url:   'php/buscarCon.php',
            type:  'post',
            beforeSend: function(){
                //Lo que se hace antes de enviar el formulario
                },
            success: function(respuesta){
                //lo que se si el destino devuelve algo
				//alert('OK');
                $("#confeccion").html(respuesta.html);
            },
            error:    function(xhr,err){ 
                alert("Confeccion\nreadyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
            }
        });
    }
}

function EliminarFila($eliminar){

    if($eliminar == ""){
            alert('Vacio');
    }
    else {
        $.ajax({
            dataType: "json",
            data: {"delete": $eliminar},
            url:   'php/crud.php',
            type:  'post',
            beforeSend: function(){
                //Lo que se hace antes de enviar el formulario
                },
            success: function(respuesta){
                //lo que se si el destino devuelve algo
				//alert('OK');
               },
            error:    function(xhr,err){ 
                alert("Eliminar\nreadyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
            }
        });
    }
}


function AnyadirLinea($row){

    if($row == ""){
            alert('Vacio');
    }
    else {
        $.ajax({
            dataType: "json",
            data: {"add": $row},
            url:   'php/crud.php',
            type:  'post',
            beforeSend: function(){
                //Lo que se hace antes de enviar el formulario
                },
            success: function(respuesta){
                //lo que se si el destino devuelve algo
				//alert('OK');
               },
            error:    function(xhr,err){ 
                alert("Eliminar\nreadyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
            }
        });
    }
}

function EditarLinea($row){

    if($row == ""){
            alert('Vacio');
    }
    else {
        $.ajax({
            dataType: "json",
            data: {"edit": $row},
            url:   'php/crud.php',
            type:  'post',
            beforeSend: function(){
                //Lo que se hace antes de enviar el formulario
                },
            success: function(respuesta){
                //lo que se si el destino devuelve algo
				//alert('OK');
               },
            error:    function(xhr,err){ 
                alert("Eliminar\nreadyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
            }
        });
    }
}




</script>
   
</head>
<body>
	<div id=top_dc >
	<button id = "desconectar" class="desconectar">Desconectar</button>	
	<script>
	$("button").button({ icons: { primary: "ui-icon-circle-close"} }).click(function (evt) {

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
		<input type="radio" id="radio2" name="radio"><label for="radio2">Pedido</label>
	</div>

</form></aside>
	</div>


<!-- Tabs -->
<h2 class="proveedor"><?php
	print $_SESSION["minombremac"];
	?>
	</h2>
<div id="tabs">
	<!-- Tabs-1 -->
	<ul>
		<li><a href="#tabs-1">Almacen</a></li>
	</ul>
	<div id="tabs-1">
		<div id="grid_table"></div>

	</div>
	<!-- End Tabs-1 -->
</div>
<!-- End Tabs -->
<!-- Tabs -->
	<?php 
	crearTabla();
	?>
	<div id="popup-dialog-crud" >
	<form id="crud-form" class="pq-grid-crud-popup" method="post" name="crud-form" action="validar.php">
	<input type="hidden" name="recId" value="">
	<table align="center"><tbody><tr>            
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
	</tr></tbody></table>

	</form>
</div>

<div id="popup-dialog-eliminar" >

</div>

<script>
$("#variedad").on("change", buscarCalibre);
$("#variedad").on("change", buscarConfeccion);
</script>
</body>
</html>
