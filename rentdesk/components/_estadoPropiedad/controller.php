<?php
$view = @$_GET["view"];

if($view=="estadoPropiedad"){
include("models/estadoPropiedad.php");
include("views/estadoPropiedad.php");
}

if($view=="estadoPropiedad_list"){
include("models/estadoPropiedad_list.php");
include("views/estadoPropiedad_list.php");
}

?>