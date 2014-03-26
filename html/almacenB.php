<?php 
session_start(); //necesario para sesiones
include("conex.php");
//include("funciones.php"); 
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
	#logo-events aside {
	float: right;
	margin-bottom: -18px;
	}
	</style>
	<script>
    $(function () {
        var data = [[1, 'Manzana', '32', 'DASBEN', "I", "63","4"],
            [1, 'Manzana', '32', 'DASBEN', "I", "63","4"],
			[1, 'Pera', '3', 'DASBEN', "I", "23","4"],
			[1, 'Manzana', '3', 'DASBEN', "I", "63","4"],
			[1, 'Manzana', '32', 'DASBEN', "II", "43","4"],
			[1, 'Manzana', '32', 'DASBEN', "I", "23","4"],
			[1, 'Manzana', '3', 'DASBEN', "I", "46","4"],
			[1, 'Pera', '32', 'DASBEN', "I", "43","4"],
			[1, 'Cereza', '32', 'DASBEN', "I", "43","4"],
			[1, 'Manzana', '32', 'DASBEN', "II", "26","4"],
			[1, 'Cereza', '32', 'DASBEN', "I", "43","4"],
			[1, 'Pera', '32', 'DASBEN', "I", "43","4"],
			[1, 'Manzana', '3', 'DASBEN', "II", "43","4"],
			[1, 'Manzana', '32', 'DASBEN', "I", "26","4"],
			[1, 'Pera', '32', 'DASBEN', "I", "46","4"],
			[1, 'Cereza', '3', 'DASBEN', "III", "23","4"],
			[1, 'Manzana', '32', 'DASBEN', "I", "63","4"],
			[1, 'Manzana', '32', 'DASBEN', "I", "43","4"],
			[1, 'Pera', '32', 'DASBEN', "III", "43","4"],
			[1, 'Manzana', '32', 'DASBEN', "II", "43","4"],
			[1, 'Manzana', '3', 'DASBEN', "III", "23","4"],
			[1, 'Pera', '32', 'DASBEN', "I", "23","4"],
			[1, 'Cereza', '32', 'DASBEN', "I", "43","4"],
			[1, 'Manzana', '32', 'DASBEN', "I", "23","4"]];

		var ht = $("#divcontainer").innerHeight();		
		var wd = $("#divcontainer").innerWidth();
        var obj = { width: wd, ht: ht,title:"Stock",resizable:false,draggable:true };
        obj.colModel = [{ title: "Cajas", width: 100, dataType: "integer" },
        { title: "Variedad", width: 200, dataType: "string" },
        { title: "Confección", width: 100, dataType: "string", align: "right" },
        { title: "Marca", width: 100, dataType: "string"},
        { title: "CAT", dataType: "string"},
        { title: "Calibre", width: 50, dataType: "string",align: "right" },
        { title: "Trazabilidad", width: 150, dataType: "string",align: "right" }];
        obj.dataModel = { data: data };
        

     //append or prepend the CRUD toolbar to .pq-grid-top or .pq-grid-bottom
    $("#grid_array").on("pqgridrender", function (evt, obj) {
        var $toolbar = $("<div class='pq-grid-toolbar pq-grid-toolbar-crud'></div>").appendTo($(".pq-grid-top", this));
 
        $("<span>Add</span>").appendTo($toolbar).button({ icons: { primary: "ui-icon-circle-plus"} }).click(function (evt) {
            addRow();
        });
        $("<span>Edit</span>").appendTo($toolbar).button({ icons: { primary: "ui-icon-pencil"} }).click(function (evt) {
            editRow();
        });
        $("<span>Delete</span>").appendTo($toolbar).button({ icons: { primary: "ui-icon-circle-minus"} }).click(function () {
            deleteRow();
        });
        $toolbar.disableSelection();
    });
 
    var $grid = $("#grid_array").pqGrid(obj);
    //create popup dialog.
    $("#popup-dialog-crud").dialog({ width: 400, modal: true,
        open: function () { $(".ui-dialog").position({ of: "#grid_array" }); },
        autoOpen: false
    });
    //edit Row
    function editRow() {
        var rowIndx = getRowIndx();
        if (rowIndx != null) {
            var DM = $grid.pqGrid("option", "dataModel");
            var data = DM.data;
            var row = data[rowIndx];
            var $frm = $("form#crud-form");
            $frm.find("input[name='company']").val(row[0]);
            $frm.find("input[name='symbol']").val(row[1]);
            $frm.find("input[name='price']").val(row[3]);
            $frm.find("input[name='change']").val(row[4]);
            $frm.find("input[name='pchange']").val(row[5]);
            $frm.find("input[name='volume']").val(row[6]);
 
            $("#popup-dialog-crud").dialog({ title: "Edit Record (" + (rowIndx + 1) + ")", buttons: {
                Update: function () {
                    //save the record in DM.data.
                    row[0] = $frm.find("input[name='company']").val();
                    row[1] = $frm.find("input[name='symbol']").val();
                    row[3] = $frm.find("input[name='price']").val();
                    row[4] = $frm.find("input[name='change']").val();
                    row[5] = $frm.find("input[name='pchange']").val();
                    row[6] = $frm.find("input[name='volume']").val();
                    //$grid.pqGrid("refreshDataAndView").pqGrid('setSelection',{ rowIndx:rowIndx});
                    $grid.pqGrid("refreshRow", { rowIndx: rowIndx }).pqGrid('setSelection', { rowIndx: rowIndx });
                    $(this).dialog("close");
                },
                Cancel: function () {
                    $(this).dialog("close");
                }
            }
            }).dialog("open");
        }
    }
    //append Row
    function addRow() {
        //debugger;
        var DM = $grid.pqGrid("option", "dataModel");
        var data = DM.data;
 
        var $frm = $("form#crud-form");
        $frm.find("input").val("");
 
        $("#popup-dialog-crud").dialog({ title: "Add Record", buttons: {
            Add: function () {                    
                var row = [];
                //save the record in DM.data.
                row[0] = $frm.find("input[name='company']").val();
                row[1] = $frm.find("input[name='symbol']").val();
                row[3] = $frm.find("input[name='price']").val();
                row[4] = $frm.find("input[name='change']").val();
                row[5] = $frm.find("input[name='pchange']").val();
                row[6] = $frm.find("input[name='volume']").val();
                data.push(row);
                $grid.pqGrid("refreshDataAndView");
                $(this).dialog("close");
            },
            Cancel: function () {
                $(this).dialog("close");
            }
        }
        });
        $("#popup-dialog-crud").dialog("open");
    }
    //delete Row.
    function deleteRow() {
        var rowIndx = getRowIndx();
        if (rowIndx != null) {
            var DM = $grid.pqGrid("option", "dataModel");
            DM.data.splice(rowIndx, 1);
            $grid.pqGrid("refreshDataAndView");
            $grid.pqGrid("setSelection", { rowIndx: rowIndx });
        }
    }
    function getRowIndx() {
        //var $grid = $("#grid_render_cells");
 
        //var obj = $grid.pqGrid("getSelection");
        //debugger;
        var arr = $grid.pqGrid("selection", { type: 'row', method: 'getSelection' });
        if (arr && arr.length > 0) {
            var rowIndx = arr[0].rowIndx;
 
            //if (rowIndx != null && colIndx == null) {
            return rowIndx;
        }
        else {
            alert("Select a row.");
            return null;
        }
    }

});
function changeToGrid() {
	var tbl = $("table.StockTable");
	
	var ht = $("#divcontainer").innerHeight();		
	var wd = $("#divcontainer").innerWidth();

	var obj = $.paramquery.tableToArray(tbl);
	var newObj = { width: wd, ht: ht,title:"Stock",resizable:false,draggable:true };
	newObj.dataModel = { data: obj.data, rPP: 100};
	newObj.colModel = [{ title: "Cajas", width: 100, dataType: "integer" },
        { title: "Variedad", width: 200, dataType: "string" },
        { title: "Confección", width: 100, dataType: "string", align: "right" },
        { title: "Marca", width: 100, dataType: "string"},
        { title: "CAT", dataType: "string"},
        { title: "Calibre", width: 50, dataType: "string",align: "right" },
        { title: "Trazabilidad", width: 150, dataType: "string",align: "right" }];
	$("#grid_table").pqGrid(newObj);
	
	tbl.css("display", "none");
};
$( window ).resize(function() {
 		var ht = $("#tabs").innerHeight()-35;		
		var wd = $("#tabs").innerWidth()-35;
		$( "#grid_array" ).pqGrid( {width:wd} );
		
});
      
</script>    
</head>
<body>
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
<h2 class="proveedor">Proveedor</h2>
<div id="tabs">
	<!-- Tabs-1 -->
	<ul>
		<li><a href="#tabs-1">Almacen</a></li>
	</ul>
	<div id="tabs-1">
		<div id="grid_array"></div>
	</div>
	<!-- End Tabs-1 -->
</div>
<!-- End Tabs -->
<!-- Tabs -->


<div id="grid_table" style="margin: auto;" class=""></div>


<div>
<?php 
print "Aqui";
$Ustock = &$conexion->Execute("select * from U_MAC_STOCK where IC ='$_SESSION[misesionmac]'");
print "Aqui";
if(!$Ustock)  
    print $conexion->ErrorMsg( );  
    /* Declaramos un if en caso de que la consulta no se haya ejecutado bien, para que nos muestre el error */
	else {
	
	

	
		print '<table border="1" cellspacing="0" cellpadding="0" class="StockTable" style="">
			<tbody><tr valign="middle" id="header">
				<th class="cajas">Cajas</th>
				<th class="variedad">Variedad</th>
				<th class="confeccion">Confección</th>
				<th class="marca">Marca</th>
				<th class="cat">CAT</th>
				<th class="calibre">Calibre</th>
				<th class="trazabilidad">Trazabilidad</th>
			</tr>';
		while(!$Ustock->EOF) {  
			  print '<tr id="Tr1" class="rowcolor1">';
			  print '<td class="cajas">';
			  print $Ustock->fields[2];
			  print '</td>';

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

?>
</div>


<script>
changeToGrid(); 
</script>
</div>
<div>


</body>
</html>
