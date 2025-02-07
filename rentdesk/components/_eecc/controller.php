<?php
$view = @$_GET["view"];
$token_contrato = @$_GET["token_contrato"];

if($view=="eecc"){
include("models/eecc.php");
include("views/eecc.php");
}

if($view=="eecc_pago"){
include("models/eecc_pago.php");
include("views/eecc_pago.php");
}


if($view=="eecc_list"){
include("models/eecc_list.php");
include("views/eecc_list.php");
}


?>