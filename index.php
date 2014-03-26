<!doctype html>
<?php
	session_start(); //necesario para crear la sesión
?>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Login</title>
	<link rel="shortcut icon" href="favicon.ico" />
	<script src="development-bundle/ui/jquery.ui.core.js"></script>
	<script src="development-bundle/ui/jquery.ui.widget.js"></script>
	<script src="development-bundle/ui/jquery.ui.mouse.js"></script>
	<script src="development-bundle/ui/jquery.ui.button.js"></script>
	<script src="development-bundle/ui/jquery.ui.draggable.js"></script>
	<script src="development-bundle/ui/jquery.ui.position.js"></script>
	<script src="development-bundle/ui/jquery.ui.resizable.js"></script>
	<script src="development-bundle/ui/jquery.ui.button.js"></script>
	<script src="development-bundle/ui/jquery.ui.dialog.js"></script>
	<script src="development-bundle/ui/jquery.ui.effect.js"></script>
	<link href="css/south-street/jquery-ui-1.10.4.custom.css" rel="stylesheet">
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>
	<style>

	body{
		font: 62.5% "Trebuchet MS", sans-serif;
		margin: 50px;
	}

		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
	</style>
	<script>
	$(function() {
		var name = $( "#name" ),
			password = $( "#password" ),
			allFields = $( [] ).add( name ).add( password ),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}

		$( "#dialog-form" ).dialog({
			autoOpen: true,
			height: 300,
			width: 350,
			modal: true,

			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
		$( "#login" ).position({
		  my: "right top",
		  at: "right bottom+20",
		  of: "#password"
		});

	
	});
	</script>
</head>
<body>

<div id="dialog-form" title="Login">
	<?php
			
			if(isset($_SESSION["autentificado"]) and isset($_SESSION["error"]))
			{   $auth=$_SESSION["autentificado"];
				$error=$_SESSION["error"];
				if($auth=="no" and $error=="1" ){
				$_SESSION["autentificado"]='';
				echo"No conectado";
				}
				if($auth=="no" and $error=="2" ){
				$_SESSION["autentificado"]='';
				echo"<p class='validateTips ui-state-error'>Usuario o contraseña incorrectos</p>";
				} 
/* 				if ($auth=="si"){
					header('Location: almacen.php');
					EXIT();
				} */
			}
		?>
	

	<form id="form1" method="post" name="form1" action="php/validar.php">
	<fieldset>
		<label for="name">Usuario</label>
		<input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
		<label for="password">Contraseña</label>
		<input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />
		<label for="login"></label>
		<input type="submit" id="login" name="login" value="Login" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" >
	</fieldset>
	</form>


</div>


<div class="logo">
<img src="img/logo.png" />
</div>
</body>
</html>
