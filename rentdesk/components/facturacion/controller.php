<?php
$view = @$_GET["view"];

if ($view == "facturacion") {
    include("models/facturacion.php");
    include("views/facturacion.php");
}

if ($view == "facturacion_list") {
    include("models/facturacion_list.php");
    include("views/facturacion_list.php");
}

if ($view == "facturacion_iframe") {
    include("models/facturacion.php");
    include("views/facturacion_iframe.php");
}

if ($view == "facturacion_ficha_tecnica") {
    include("models/facturacion_ficha_tecnica.php");
    include("views/facturacion_ficha_tecnica.php");
}

if ($view == "facturacion_notas_credito_list") {
    include("models/facturacion_notas_credito_list.php");
    include("views/facturacion_notas_credito_list.php");
}
