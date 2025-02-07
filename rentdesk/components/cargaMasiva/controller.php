<?php
$view = @$_GET["view"];
$nav  = @$_GET["nav"];


if($view=="cargaMasiva"){
include("models/cargaMasiva.php");
include("views/cargaMasiva.php");
}

if($view=="cargaMasiva_list"){
include("models/cargaMasiva_list.php");
include("views/cargaMasiva_list.php");
}

if($view=="cargaMasiva_list_log"){
include("models/cargaMasiva_list_log.php");
include("views/cargaMasiva_list_log.php");
}

?>