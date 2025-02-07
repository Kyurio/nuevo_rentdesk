<?php
$view = @$_GET["view"];

if($view=="producto"){
include("models/producto.php");
include("views/producto.php");
}

if($view=="producto_list"){
include("models/producto_list.php");
include("views/producto_list.php");
}

?>