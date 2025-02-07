<?php
$view = @$_GET["view"];
$token = @$_GET["token"];

if ($view == "persona") {
    include("models/persona.php");
    include("views/persona.php");
}

if ($view == "persona_list") {
    include("models/persona_list.php");
    include("views/persona_list.php");
}

if ($view == "persona_list_procesa_prop_pago") {
    include("models/persona_list_prop_pago.php");
    include("views/persona_list_prop_pago.php");
}

if ($view == "persona_ficha_tecnica") {
    include("models/persona_ficha_tecnica.php");
    include("views/persona_ficha_tecnica.php");
}

if ($view == "persona_facturas_generacion_masiva_list") {
    include("models/persona_facturas_generacion_masiva_list.php");
    include("views/persona_facturas_generacion_masiva_list.php");
}
