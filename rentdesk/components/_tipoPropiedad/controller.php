<?php
$view = @$_GET["view"];

if($view=="tipoPropiedad"){
include("models/tipoPropiedad.php");
include("views/tipoPropiedad.php");
}

if($view=="tipoPropiedad_list"){
include("models/tipoPropiedad_list.php");
include("views/tipoPropiedad_list.php");
}

?>