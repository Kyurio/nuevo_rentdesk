<?php
$view = @$_GET["view"];

if($view=="banco"){
include("models/banco.php");
include("views/banco.php");
}

if($view=="banco_list"){
include("models/banco_list.php");
include("views/banco_list.php");
}

?>