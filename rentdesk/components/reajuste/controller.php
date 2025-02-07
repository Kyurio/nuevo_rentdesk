<?php
$view = @$_GET["view"];

if ($view == "reajuste") {
    include("models/reajuste.php");
    include("views/reajuste.php");
}

if ($view == "reajuste_list") {
    include("models/reajuste_list.php");
    include("views/reajuste_list.php");
}

if ($view == "reajuste_iframe") {
    include("models/reajuste.php");
    include("views/reajuste_iframe.php");
}

if ($view == "reajuste_ficha_tecnica") {
    include("models/reajuste_ficha_tecnica.php");
    include("views/reajuste_ficha_tecnica.php");
}
