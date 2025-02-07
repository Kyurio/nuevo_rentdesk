<?php
$view = @$_GET["view"];

if($view=="visita"){
include("models/visita.php");
include("views/visita.php");
}

if($view=="visita_list"){
include("models/visita_list.php");
include("views/visita_list.php");
}


if($view=="visita_detalle"){
include("models/visita_detalle.php");
include("views/visita_detalle.php");
}

?>