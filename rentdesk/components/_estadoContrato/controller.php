<?php
$view = @$_GET["view"];

if($view=="estadoContrato"){
include("models/estadoContrato.php");
include("views/estadoContrato.php");
}

if($view=="estadoContrato_list"){
include("models/estadoContrato_list.php");
include("views/estadoContrato_list.php");
}

?>