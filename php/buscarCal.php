<?php
include("funciones.php");
if(isset($_POST['variedad'])){
 
    $html = dameCalibre($_POST['variedad']);
 
    $respuesta = array("html"=>$html);
    echo json_encode($respuesta);
}

?>