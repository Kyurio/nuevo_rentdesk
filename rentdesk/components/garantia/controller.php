<?php
$view = @$_GET["view"];

if ($view == "garantia") {
    include("models/garantia.php");
    include("views/garantia.php");
}

if ($view == "garantia_list") {
    include("models/garantia_list.php");
    include("views/garantia_list.php");
}

if ($view == "garantia_iframe") {
    include("models/garantia.php");
    include("views/garantia_iframe.php");
}

if ($view == "garantia_ficha_tecnica") {
    include("models/garantia_ficha_tecnica.php");
    include("views/garantia_ficha_tecnica.php");
}
