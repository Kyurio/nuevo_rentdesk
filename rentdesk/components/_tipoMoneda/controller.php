<?php
$view = @$_GET["view"];

if($view=="tipoMoneda"){
include("models/tipoMoneda.php");
include("views/tipoMoneda.php");
}

if($view=="tipoMoneda_list"){
include("models/tipoMoneda_list.php");
include("views/tipoMoneda_list.php");
}

?>