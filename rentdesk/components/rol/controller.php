<?php
$view = @$_GET["view"];

if ($view == "rol") {
    include("models/rol.php");
    include("views/rol.php");
}

if ($view == "rol_list") {

    include("models/rol_list.php");
    include("views/rol_list.php");
}

if ($view == "rol_iframe") {
    include("models/rol.php");
    include("views/rol_iframe.php");
}

if ($view == "rol_ficha_tecnica") {
    include("models/rol_ficha_tecnica.php");
    include("views/rol_ficha_tecnica.php");
}
