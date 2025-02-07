<?php
$view = @$_GET["view"];

if($view=="tipoMenu"){
include("models/tipoMenu.php");
include("views/tipoMenu.php");
}

if($view=="tipoMenu_list"){
include("models/tipoMenu_list.php");
include("views/tipoMenu_list.php");
}

?>