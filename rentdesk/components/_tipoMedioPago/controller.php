<?php
$view = @$_GET["view"];

if($view=="tipoMedioPago"){
include("models/tipoMedioPago.php");
include("views/tipoMedioPago.php");
}

if($view=="tipoMedioPago_list"){
include("models/tipoMedioPago_list.php");
include("views/tipoMedioPago_list.php");
}

?>