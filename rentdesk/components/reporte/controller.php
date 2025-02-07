<?php
$view = @$_GET["view"];
$nav  = @$_GET["nav"];


if($view=="reporte"){
include("models/reporte.php");
include("views/reporte.php");
}

if($view=="reporte_list"){
include("models/reporte_list.php");
include("views/reporte_list.php");
}
if($view=="reporte_estadistica"){
include("models/reporte_estadistica.php");
include("views/reporte_estadistica.php");
}

?>