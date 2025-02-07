<?php
$view = @$_GET["view"];

if($view=="estadoPersona"){
include("models/estadoPersona.php");
include("views/estadoPersona.php");
}

if($view=="estadoPersona_list"){
include("models/estadoPersona_list.php");
include("views/estadoPersona_list.php");
}

?>