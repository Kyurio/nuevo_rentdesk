<?php
$view = @$_GET["view"];


if($view=="perfil"){
include("models/perfil.php");
include("views/perfil.php");
}



?>