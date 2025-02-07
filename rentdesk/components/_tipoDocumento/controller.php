<?php
$view = @$_GET["view"];

if($view=="tipoDocumento"){
include("models/tipoDocumento.php");
include("views/tipoDocumento.php");
}

if($view=="tipoDocumento_list"){
include("models/tipoDocumento_list.php");
include("views/tipoDocumento_list.php");
}

?>