<?php
$view = @$_GET["view"];

if ($view == "contribucion") {
    include("models/contribucion.php");
    include("views/contribucion.php");
}


if ($view == "contribuciones_list") {

    include("views/contribuciones_list.php");
}



