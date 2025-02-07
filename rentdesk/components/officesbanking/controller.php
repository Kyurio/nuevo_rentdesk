<?php

$view = @$_GET["view"];
$token = @$_GET["token"];

if ($view == "liquidaciones_archivo") {

    include("views/Cierres.php");

}

