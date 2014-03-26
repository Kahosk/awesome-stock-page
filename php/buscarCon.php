<?php
include("funciones.php");

if(isset($_POST['variedad'])){
 
    $html = dameConfecciones($_POST['variedad']);
 
    $respuesta = array("html"=>$html);
    echo json_encode($respuesta);
}
?>