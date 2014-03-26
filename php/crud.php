<?php

session_start(); //necesario para sesiones
include("funciones.php");

if(isset($_POST['delete'])){
 
    $html = eliminarFila($_POST['delete']);
 
    $respuesta = array("html"=>$html);
    echo json_encode($respuesta);
}

if(isset($_POST['add'])){
 
    $html = anyadirFila($_POST['add']);
 
    $respuesta = array("html"=>$html);
    echo json_encode($respuesta);
}

if(isset($_POST['edit'])){
 
    $html = editaFila($_POST['edit']);
 
    $respuesta = array("html"=>$html);
    echo json_encode($respuesta);
}
?>