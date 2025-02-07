<?php
$view = @$_GET["view"];

if ($view == "movimiento") {
    include("models/movimiento.php");
    include("views/movimiento.php");
}

if ($view == "movimiento_list") {
    include("models/movimiento_list.php");
    include("views/movimiento_list.php");
}

if ($view == "movimiento_iframe") {
    include("models/movimiento.php");
    include("views/movimiento_iframe.php");
}

if ($view == "movimiento_ficha_tecnica") {
    include("models/movimiento_ficha_tecnica.php");
    include("views/movimiento_ficha_tecnica.php");
}

if ($view == "movimiento_varios_acreedores_list") {
    include("models/movimiento_varios_acreedores_list.php");
    include("views/movimiento_varios_acreedores_list.php");
}
