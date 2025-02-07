<?php
$view = @$_GET["view"];
$token = @$_GET["token"];


if ($view == "propiedad") {
    include("models/propiedad.php");
    include("views/propiedad.php");
}

if ($view == "propiedad_list") {
    include("models/propiedad_list.php");
    include("views/propiedad_list.php");
}

if ($view == "propiedad_iframe") {
    include("models/propiedad.php");
    include("views/propiedad_iframe.php");
}

if ($view == "propiedad_ficha_tecnica") {
    include("models/propiedad_ficha_tecnica.php");
    include("views/propiedad_ficha_tecnica.php");
}



if ($view == "propiedad_pago_arriendo_eliminar_moras_list") {
    include("models/propiedad_pago_arriendo_eliminar_moras_list.php");
    include("views/propiedad_pago_arriendo_eliminar_moras_list.php");
}



if ($view == "propiedad_revision_cuentas_servicio_list") {
    include("models/propiedad_revision_cuentas_servicio_list.php");
    include("views/propiedad_revision_cuentas_servicio_list.php");
}



if ($view == "propiedad_liquidaciones_generacion_masiva_list") {
    include("models/propiedad_liquidaciones_generacion_masiva_list.php");
    include("views/propiedad_liquidaciones_generacion_masiva_list.php");
}

if ($view == "propiedad_liquidaciones_pago_a_propietarios_list") {
    include("models/propiedad_liquidaciones_pago_a_propietarios_list.php");
    include("views/propiedad_liquidaciones_pago_a_propietarios_list.php");
}

if ($view == "propiedad_por_liquidar_list") {
    include("models/propiedad_por_liquidar_list.php");
    include("views/propiedad_por_liquidar_list.php");
}


if ($view == "propiedad_por_pagar_list") {
    include("models/propiedad_por_pagar_list.php");
    include("views/propiedad_por_pagar_list.php");
}

if ($view == "liquidacion_historico") {
    include("models/liquidacion_historico.php");
    include("views/liquidacion_historico.php");
}
