<?php
$view = @$_GET["view"];
$token = @$_GET["token"];

if ($view == "arriendo") {
    include("models/arriendo.php");
    include("views/arriendo.php");
}

if ($view == "arriendo_list") {
    include("models/arriendo_list.php");
    include("views/arriendo_list.php");
}

if ($view == "arriendo_iframe") {
    include("models/arriendo.php");
    include("views/arriendo_iframe.php");
}

if ($view == "arriendo_ficha_tecnica") {
    include("models/arriendo_ficha_tecnica.php");
    include("views/arriendo_ficha_tecnica.php");
}

if ($view == "arriendo_pago_cheques_list") {
    include("models/arriendo_pago_cheques_list.php");
    include("views/arriendo_pago_cheques_list.php");
}


if ($view == "arriendo_morosos_list") {

   // include("models/arriendo_morosos_list.php");
    include("views/arriendo_morosos_list.php");
}

if ($view == "arriendo_editar") {
    include("models/arriendo_editar.php");
    include("views/arriendo_editar.php");
}
