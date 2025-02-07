<?php
$view = @$_GET["view"];

if($view=="estadoLiquidacion"){
include("models/estadoLiquidacion.php");
include("views/estadoLiquidacion.php");
}

if($view=="estadoLiquidacion_list"){
include("models/estadoLiquidacion_list.php");
include("views/estadoLiquidacion_list.php");
}

?>