<?php
$view = @$_GET["view"];

if($view=="pack"){
include("models/pack.php");
include("views/pack.php");
}

if($view=="pack_list"){
include("models/pack_list.php");
include("views/pack_list.php");
}

?>