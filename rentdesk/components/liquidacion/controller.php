<?php
$view = @$_GET["view"];
$token_contrato = @$_GET["token_contrato"];
$token = @$_GET["token"];

if($view=="liquidacion"){
include("models/liquidacion.php");
include("views/liquidacion.php");
}

if($view=="liquidacion_list"){
include("models/liquidacion_list.php");
include("views/liquidacion_list.php");
}


?>