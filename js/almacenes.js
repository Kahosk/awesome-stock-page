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

$(function () {
	var tbl = $("table.PedidosTable");
	
	var ht = $("#tabs").innerHeight()-35;		
	var wd = $("#tabs").innerWidth()-50;

	var obj = $.paramquery.tableToArray(tbl);
	var newObj = { width: wd, ht: ht,title:"Pedido",
		resizable:false,
		draggable:true,
		numberCell: true,
		columnBorders: true,
        selectionModel: { type: 'cell', mode: 'block' },
        editModel: { clicksToEdit: 2, saveKey: 13 },
        hoverMode: 'cell'};
	for (var i = 0; i < obj.data.length; i++) {
        obj.data[i].push("");
    }		

	
	newObj.dataModel = { data: obj.data};
	newObj.colModel = [{ title: "Variedad", width: 250, dataType: "string", resizable:true },
        { title: "Confección", width: 100, dataType: "string", align: "right", resizable:true },
        { title: "Marca", width: 100, dataType: "string",align: "right", resizable:true},
        { title: "CAT",width: 50, dataType: "string",align: "right", resizable:true},
        { title: "Calibre", width: 50, dataType: "string",align: "right", resizable:true },
        { title: "Cajas", width: 50, dataType: "integer",align: "right", resizable:true },
		{ title: "DocEntry", hidden: true, dataType: "integer"},
		{ title: "lineNum", hidden: true, dataType: "integer"}];
	//$("#pedido_table").pqGrid(newObj);

	newObj.colModel[8] = { dataIndx: 9, editable: false, sortable: false, title: "", width: 30, align: "center", resizable: false, render: function (ui) {
        var rowData = ui.rowData, dataIndx = ui.dataIndx;
        var val = rowData[dataIndx];
        str = "";
        if (val) {
            str = "checked='checked'";
        }
        return "<input type='checkbox' " + str + " />";
    }, className: "checkboxColumn"
    };
	
	
	newObj.rowSelect = function (evt, ui) {            
        var rowIndx = ui.rowIndx;
        newObj.dataModel.data[rowIndx][9] = true;
        $grid1.pqGrid("refreshCell", { rowIndx: rowIndx, dataIndx: 9 });
    }
    newObj.rowUnSelect = function (evt, ui) {
        var rowIndx = ui.rowIndx,
            data = ui.dataModel.data,
            evt = ui.evt;
        data[rowIndx][9] = false;            
        $grid1.pqGrid("refreshCell", { rowIndx: rowIndx, dataIndx: 9 });
    }
	
	
	tbl.css("display", "none");
    var $grid = $("#pedido_table").pqGrid(newObj);
	
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

function desvalidar(){
    $.ajax({
            url:   'php/desvalidar.php',
			success: function(respuesta){
                //lo que se si el destino devuelve algo
				//alert('OK');
				window.location.href = 'index.php';
               },
            error:    function(xhr,err){ 
                alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
            }
        });
    
}

$( window ).resize(function() {
 		var ht = $("#tabs").innerHeight()-35;		
		var wd = $("#tabs").innerWidth()-35;
		$( "#grid_table" ).pqGrid( {width:wd} );
		$( "#pedido_table" ).pqGrid( {width:wd} );
		
});