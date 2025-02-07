<?php
$view = @$_GET["view"];
$nav  = @$_GET["nav"];


if($view=="contrato"){
include("models/contrato.php");
include("views/contrato.php");
}

if($view=="contrato_list"){
include("models/contrato_list.php");
include("views/contrato_list.php");
}

if($view=="contrato_producto"){
include("models/contrato_producto.php");
include("views/contrato_producto.php");
}

if($view=="contrato_pack"){
include("models/contrato_pack.php");
include("views/contrato_pack.php");
}

?>