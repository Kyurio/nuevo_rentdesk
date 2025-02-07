<?php
$view = @$_GET["view"];

if($view=="tipoResposable"){
include("models/tipoResposable.php");
include("views/tipoResposable.php");
}

if($view=="tipoResposable_list"){
include("models/tipoResposable_list.php");
include("views/tipoResposable_list.php");
}

?>