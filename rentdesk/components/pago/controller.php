<?php
$view = @$_GET["view"];
$token_contrato = @$_GET["token_contrato"];

if($view=="pago"){
include("models/pago.php");
include("views/pago.php");
}

if($view=="pago_list"){
include("models/pago_list.php");
include("views/pago_list.php");
}


?>