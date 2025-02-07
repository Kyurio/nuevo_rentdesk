<?php
$view = @$_GET["view"];

if($view=="tipoProducto"){
include("models/tipoProducto.php");
include("views/tipoProducto.php");
}

if($view=="tipoProducto_list"){
include("models/tipoProducto_list.php");
include("views/tipoProducto_list.php");
}

?>