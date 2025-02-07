<?php
$view = @$_GET["view"];
$token = @$_GET["token"];

if ($view == "arrendatario") {
    include("models/arrendatario.php");
    include("views/arrendatario.php");
}

if ($view == "arrendatario_list") {
    include("models/arrendatario_list.php");
    include("views/arrendatario_list.php");
}

if ($view == "arrendatario_list_procesa_prop_pago") {
    include("models/arrendatario_list_prop_pago.php");
    include("views/arrendatario_list_prop_pago.php");
}

if ($view == "arrendatario_iframe") {
    include("models/arrendatario.php");
    include("views/arrendatario_iframe.php");
}


if ($view == "arrendatario_ficha_tecnica") {
    include("models/arrendatario_ficha_tecnica.php");
    include("views/arrendatario_ficha_tecnica.php");
}
