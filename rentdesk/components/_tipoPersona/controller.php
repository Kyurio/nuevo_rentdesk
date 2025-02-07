<?php
$view = @$_GET["view"];

if($view=="tipoPersona"){
include("models/tipoPersona.php");
include("views/tipoPersona.php");
}

if($view=="tipoPersona_list"){
include("models/tipoPersona_list.php");
include("views/tipoPersona_list.php");
}

?>