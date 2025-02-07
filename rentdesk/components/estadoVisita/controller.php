<?php
$view = @$_GET["view"];

if($view=="estadoVisita"){
include("models/estadoVisita.php");
include("views/estadoVisita.php");
}

if($view=="estadoVisita_list"){
include("models/estadoVisita_list.php");
include("views/estadoVisita_list.php");
}

?>