$(function () {
	
	//Cambiar por conexion php para mas precision
	var tbl = $("table.StockTable");
	

	tbl.css("display", "none");
	
	var ht = $("#tabs").innerHeight()-35;		
	var wd = $("#tabs").innerWidth()-35;

	var obj = $.paramquery.tableToArray(tbl);
	var newObj = { width: wd, 
		height:500, 
		title:"Stock",
		resizable:true,
		draggable:false,
		bottomVisible:true,
		editModel: { clicksToEdit: 0, saveKey: 13 }, 
		};
	newObj.dataModel = { data: obj.data, 
		filterIndx: 1, 
		filterValue: '',
		getData: function(fv){
			this.filterValue = fv;
			var fi = this.filterIndx;
			var arr = jQuery.grep($.paramquery.tableToArray(tbl).data,function( n, i ) {
				
				if (fv!=''){
					return ( n[fi] == fv );
				}else return true
			});
	return {data:arr};
		}
	};
	//alert(obj.data);

	//alert(newObj.dataModel.getData().data);
	//newObj.dataModel.data = newObj.dataModel.getData().data
	
	newObj.colModel = [{ title: "lineNum", hidden: true, dataType: "integer"},
	    { title: "Variedad", width: 250, dataType: "string", resizable:true },
		{ title: "Cajas", width: 50, dataType: "integer",align: "right", resizable:true },
		{ title: "CAT",width: 50, dataType: "string",align: "right", resizable:true},
		{ title: "Calibre", width: 50, dataType: "string",align: "right", resizable:true },
        { title: "Confección", width: 100, dataType: "string", align: "right", resizable:true },
        { title: "Bruto", width: 50, dataType: "float",align: "right", resizable:true},
		{ title: "Tara", width: 50, dataType: "float",align: "right", resizable:true},
		{ title: "Neto", width: 59, dataType: "float",align: "right", resizable:true},
		{ title: "Marca", width: 100, dataType: "string",align: "right", resizable:true},
        { title: "Trazabilidad", width: 150, dataType: "string",align: "right", resizable:true }];
	//$("#grid_table").pqGrid(newObj);
	newObj.colModel[11] = { dataIndx: 12, editable: false, sortable: false, title: "", width: 30, align: "center",hidden: true, resizable: false, render: function (ui) {
        var rowData = ui.rowData, dataIndx = ui.dataIndx;
        var val = rowData[dataIndx];
        str = "";
        if (val) {
            str = "checked='checked'";
        }return "<input type='checkbox' " + str + " />";
    }, className: "checkboxColumn"};

	
	newObj.rowSelect = function (evt, obj) {
        var dataCell = obj.dataModel.data[obj.rowIndx][1];
        var rowIndx = parseInt(obj.rowIndx) + 1;
        //alert(dataCell);
    };
	
     //append or prepend the CRUD toolbar to .pq-grid-top or .pq-grid-bottom
    $("#grid_table").on("pqgridrender", function (evt, newObj) {
        var $toolbar = $("<div class='toolbar pq-grid-toolbar pq-grid-toolbar-crud'></div>").appendTo($(".pq-grid-top", this));
 
        $("<span>Añadir</span>").appendTo($toolbar).button({ icons: { primary: "ui-icon-circle-plus"} }).click(function (evt) {
			$(".toolbar").css({ display:"none" });
			addRow();
			// $("#add-crud").css({ display:"block" });
			// new_size();
        });
        $("<span>Editar</span>").appendTo($toolbar).button({ icons: { primary: "ui-icon-pencil"} }).click(function (evt) {
			
			editRow();
			buscarCalibre();
			buscarConfeccion();
        });
        $("<span>Eliminar</span>").appendTo($toolbar).button({ icons: { primary: "ui-icon-circle-minus"} }).click(function () {
            deleteRow();
        });
		
		
		//<input type='checkbox' id='grid_parts_paging'><label for='grid_parts_paging'>Mostrar en paginas</label>
		
		$("<input type='checkbox' id='grid_parts_paging'><label for='grid_parts_paging'>Mostrar en paginas</label>").appendTo($toolbar);
		 $("#grid_parts_paging").button();
		
        $toolbar.disableSelection();
    });
	
		
    $("#grid_table").on("pqgridrender", function (evt, newObj) {
        var $toolbar = $("<div class='toolbar-pedido pq-grid-toolbar pq-grid-toolbar-crud' style='display:none'></div>").appendTo($(".pq-grid-top", this));
 
        $("<div>Mostrar todos</div>").appendTo($toolbar).button({ icons: { primary: "ui-icon-circle-plus"} }).click(function (evt) {
			var $grid = $("#grid_table").pqGrid();
			var tbl = $("table.StockTable");
			var obj = $.paramquery.tableToArray(tbl);
			var DM = $grid.pqGrid("option", "dataModel");
			DM.data = obj.data;
			$(".toolbar-pedido").css({ display:"none" });
			$grid.pqGrid("refreshDataAndView");
        });
		// var $toolbar = $("<div class='toolbar-add pq-grid-toolbar pq-grid-toolbar-crud'></div>").appendTo($(".pq-grid-bottom", this));
		// $("<div>Añadir</div>").appendTo($("#add-form-button")).button({ icons: { primary: "ui-icon-circle-plus"} }).click(function (evt) {
		// addRow();
		// }); 
        // $("#add-crud").appendTo($toolbar);
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
            
            $frm.find("select[name='variedad']").val(row[1]);
			
			$frm.find("input[name='cajas']").val(row[2]);
			$frm.find("select[name='cat']").val(row[3]);
			$frm.find("select[name='calibre']").val(row[4]);
            $frm.find("select[name='confeccion']").val(row[5]);
            
			$frm.find("input[name='bruto']").val(row[6]);
			$frm.find("input[name='tara']").val(row[7]);
			$frm.find("input[name='neto']").val(row[8]);
			
			$frm.find("select[name='marca']").val(row[9]);
			$frm.find("input[name='trazabilidad']").val(row[10]);
			
 
            $("#popup-dialog-crud").dialog({ title: "Editar fila (" + (rowIndx + 1) + ")", buttons: {
                Actualizar: function () {
                    // save the record in DM.data.
					
					if ($frm.find("select[name='variedad']").val()=='' || $frm.find("select[name='confeccion']").val()=='' || 
					$frm.find("select[name='marca']").val()=='' || $frm.find("select[name='cat']").val()=='' ||
                    $frm.find("select[name='calibre']").val()=='' || $frm.find("input[name='trazabilidad']").val()=='' || $frm.find("input[name='cajas']").val() ==''
					|| $frm.find("input[name='bruto']").val()==''|| $frm.find("input[name='tara']").val()==''|| $frm.find("input[name='neto']").val()==''){
					alert('Hay que rellenar todos los campos');
					}else{
                    
					row[1] = $frm.find("select[name='variedad']").val();
					row[2] = $frm.find("input[name='cajas']").val();
					row[3] = $frm.find("select[name='cat']").val();
					row[4] = $frm.find("select[name='calibre']").val();
					row[5] = $frm.find("select[name='confeccion']").val();
					
					row[6] = $frm.find("input[name='bruto']").val();
					row[7] = $frm.find("input[name='tara']").val();
					row[8] = $frm.find("input[name='neto']").val();
					
					row[9] = $frm.find("select[name='marca']").val();
					row[10] = $frm.find("input[name='trazabilidad']").val();
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

	
	/*function addRow() {
				var DM = $grid.pqGrid("option", "dataModel");
				var data = DM.data;
 
				var $frm = $("form#crud-form-add");
				$frm.find("input").val("");
				
				var row = [];
                // save the record in DM.data.
				row[0] = $frm.find("select[name='variedad-add']").val();
				row[1] = $frm.find("select[name='confeccion-add']").val();
				row[2] = $frm.find("select[name='marca-add']").val();
				row[3] = $frm.find("select[name='cat-add']").val();
				row[4] = $frm.find("select[name='calibre-add']").val();
				row[5] = $frm.find("input[name='trazabilidadadd']").val();
				row[6] = $frm.find("input[name='cajasadd']").val();
                
				
				
				if (row[2]=='' || row[3]=='' || row[4]=='' || row[5]=='' || row[6]=='') {
					
					alert('Hay que rellenar todos los campos' + $frm.find("input[name='trazabilidadadd']").val());
					
				}else{
				AnyadirLinea(row);
				data.push(row);
                $grid.pqGrid("refreshDataAndView");
				$("#add-crud").css({ display:"none" });
				new_size();
                }
	}
    */
	// append Row
	function addRow() {
        // debugger;
		
        var DM = $grid.pqGrid("option", "dataModel");
        var data = DM.data;
 
        var $frm = $("form#crud-form-add");
        $frm.find("input").val("");
 
        $("#add-crud").dialog({
			position:{at:"top"},
			width:$("#tabs").innerWidth()-35,
			close: function( event, ui ) {$(".toolbar").css({ display:"block" });},
			show: {
			effect: "scale",
			duration: 500},
		hide: {effect: "scale",
			duration: 500},title: "Añadir", buttons: {
            Añadir: function () {                    
                var row = [];
                // save the record in DM.data.
				row[1] = $frm.find("select[name='variedad-add']").val();
				row[2] = $frm.find("input[name='cajas-add']").val();
				row[3] = $frm.find("select[name='cat-add']").val();
				row[4] = $frm.find("select[name='calibre-add']").val();
				row[5] = $frm.find("select[name='confeccion-add']").val();
				
				row[6] = $frm.find("input[name='bruto-add']").val();
				row[7] = $frm.find("input[name='tara-add']").val();
				row[8] = $frm.find("input[name='neto-add']").val();
				
				row[9] = $frm.find("select[name='marca-add']").val();
				row[10] = $frm.find("input[name='trazabilidad-add']").val();
				               
				if (row[10]=='' || row[1]=='' || row[2]=='' || row[3]=='' || row[4]=='' || row[5]=='' || row[6]=='' || row[7]=='' || row[8]=='' || row[9]=='') {
					
					alert('Hay que rellenar todos los campos');
				//arg	
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
        $("#add-crud").dialog("open");
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
					EliminarFila(row[0]);
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

	
	   $("#grid_parts_paging").change(function (evt) {
        var paging="";
        if ($(this).is(":checked")) {
            paging = "local";
        }
        $grid.pqGrid("option", "dataModel.paging", paging);
		}).attr("checked", ($grid.pqGrid("option", "dataModel.paging")=="local")?true:false);
 
	new_size_stock();
});

$(function () {
	var tbl = $("table.ListaPedidosTable");
	
	var ht = $("#tabs").innerHeight()-35;		
	var wd = $("#tabs").innerWidth()-35;

	var obj = $.paramquery.tableToArray(tbl);
	
	var newObj = { width: wd*0.25,height:200,title:"Ordenes de carga",
		resizable:true,
		draggable:false,
        selectionModel: { type: 'row', mode: 'block' },
        editModel: { clicksToEdit: 0, saveKey: 13 },
        hoverMode: 'row'};
	for (var i = 0; i < obj.data.length; i++) {
        obj.data[i].push("");
    }		

	newObj.dataModel = { data: obj.data};
	newObj.colModel = [{ title: "DocEntry", hidden: true, dataType: "integer"},
		{ title: "Nº Orden", width: 65, dataType: "string", resizable:true },
		{ title: "Matricula", width: 60, dataType: "string", resizable:true },
		{ title: "Fecha de carga", width: 70, dataType: "string", resizable:true }];
	//$("#pedido_table").pqGrid(newObj);

    //rowSelect callback.
	
	
	//Para lineasPedido
    newObj.rowSelect = function (evt, obj) {
        var dataCell = obj.dataModel.data[obj.rowIndx][1];
        var rowIndx = parseInt(obj.rowIndx) + 1;
		var colIndx = 1; //Variedad
		alert(dataCell);
		
		var $gridStock = $("#grid_table").pqGrid();
		var DM = $gridStock.pqGrid("option", "dataModel");
		DM.data = DM.getData(dataCell).data;
		$(".toolbar-pedido").css({ display:"block" });
		//alert(DM.data);
		$gridStock.pqGrid("refreshDataAndView");
		
    }
	
	
	tbl.css("display", "none");
    var $grid = $("#pedidos_table").pqGrid(newObj);
	
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
	var wd = $("#tabs").innerWidth()-35;

	var obj = $.paramquery.tableToArray(tbl);
	var newObj = { width: wd*0.74, height:200,title:"Líneas de la orden de carga",
		resizable:true,
		draggable:false,
        selectionModel: { type: 'row', mode: 'block' },
        editModel: { clicksToEdit: 0, saveKey: 13 },
        hoverMode: 'row'};
	for (var i = 0; i < obj.data.length; i++) {
        obj.data[i].push("");
    }		

	
	newObj.dataModel = { data: obj.data};
	newObj.colModel = [{ title: "Nº Pedido", width: 70, dataType: "string", resizable:true },
		{ title: "Matricula", width: 70, dataType: "string", resizable:true },
		{ title: "Variedad", width: 250, dataType: "string", resizable:true },
        { title: "Confección", width: 100, dataType: "string", align: "right", resizable:true },
        { title: "Marca", width: 100, dataType: "string",align: "right", resizable:true},
        { title: "CAT",width: 50, dataType: "string",align: "right", resizable:true},
        { title: "Calibre", width: 50, dataType: "string",align: "right", resizable:true },
        { title: "Cajas", width: 50, dataType: "integer",align: "right", resizable:true },
		{ title: "DocEntry", hidden: true, dataType: "integer"},
		{ title: "lineNum", hidden: true, dataType: "integer"}];
	//$("#pedido_table").pqGrid(newObj);

    //rowSelect callback.
	
    newObj.rowSelect = function (evt, obj) {
        var dataCell = obj.dataModel.data[obj.rowIndx][2];
        var rowIndx = parseInt(obj.rowIndx) + 1;
		var colIndx = 2; //Variedad
		//alert(dataCell);
		
		var $gridStock = $("#grid_table").pqGrid();
		var DM = $gridStock.pqGrid("option", "dataModel");
		DM.data = DM.getData(dataCell).data;
		$(".toolbar-pedido").css({ display:"block" });
		//alert(DM.data);
		$gridStock.pqGrid("refreshDataAndView");
		
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
	
	//$variedad-add = $("#variedad-add").val();
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

function buscarCalibreAdd(){
    $variedad = $("#variedad-add").val();
	//$variedad-add = $("#variedad-add").val();
    if($variedad == ""){
            $("#calibre-add").html("<option value=''>- Primero seleccione una variedad -</option>");
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
                $("#calibre-add").html(respuesta.html);
            },
            error:    function(xhr,err){ 
                alert("CalibreAdd\nreadyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
            }
        });
    }
}

function bCalibre(valor){
    
	$variedad = valor;
	
	//$variedad-add = $("#variedad-add").val();
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

function buscarConfeccionAdd(){
    $variedad = $("#variedad-add").val();
 
    if($variedad == ""){
            $("#confeccion-add").html("<option value=''>- Primero seleccione una variedad -</option>");
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
                $("#confeccion-add").html(respuesta.html);
            },
            error:    function(xhr,err){ 
                alert("ConfeccionAdd\nreadyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
            }
        });
    }
}

function bConfeccion(valor){
	$variedad = valor;
 
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
                alert("Añadir\nreadyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
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

function new_size_stock(){

	var ht = $("#tabs").innerHeight()-35;		
	var wd = $("#tabs").innerWidth()-35;
	$( "#grid_table" ).pqGrid( {width:wd} );
	$( "#grid_table" ).pqGrid( "refresh" );

}

function new_size_pedido(){

	var ht = $("#tabs").innerHeight()-35;		
	var wd = $("#tabs").innerWidth()-35;
	
	$( "#pedidos_table" ).pqGrid( {width:wd*0.25} );
	$( "#pedido_table" ).pqGrid( {width:wd*0.74} );
	$( "#pedido_table" ).pqGrid( "refresh" );
	$( "#pedidos_table" ).pqGrid( "refresh" );

}

function new_size(){

	var ht = $("#tabs").innerHeight()-35;		
	var wd = $("#tabs").innerWidth()-35;
	$( "#grid_table" ).pqGrid( {width:wd} );
	$( "#grid_table" ).pqGrid( "refresh" );
	$( "#pedido_table" ).pqGrid( {width:wd*0.74} );
	$( "#pedido_table" ).pqGrid( "refresh" );
	$( "#pedidos_table" ).pqGrid( {width:wd*0.25} );
	$( "#pedido_table" ).pqGrid( "refresh" );

}

$( window ).resize(function() {
	new_size();
});
