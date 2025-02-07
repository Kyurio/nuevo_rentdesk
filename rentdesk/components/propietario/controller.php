<?php
$view = @$_GET["view"];
$token = @$_GET["token"];

if ($view == "propietario") {

    include("models/propietario.php");
    include("views/propietario.php");
}

if ($view == "propietario_list") {
    include("models/propietario_list.php");
    include("views/propietario_list.php");
}

if ($view == "propietario_list_procesa_prop_pago") {
    include("models/propietario_list_prop_pago.php");
    include("views/propietario_list_prop_pago.php");
}

if ($view == "propietario_ficha_tecnica") {
    include("models/propietario_ficha_tecnica.php");
    include("views/propietario_ficha_tecnica.php");
}

if ($view == "facturas_generacion_masiva_list") {
    include("models/facturas_generacion_masiva_list.php");
    include("views/facturas_generacion_masiva_list.php");
}
