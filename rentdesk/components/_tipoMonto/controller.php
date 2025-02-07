<?php
$view = @$_GET["view"];

if($view=="tipoMonto"){
include("models/tipoMonto.php");
include("views/tipoMonto.php");
}

if($view=="tipoMonto_list"){
include("models/tipoMonto_list.php");
include("views/tipoMonto_list.php");
}

?>