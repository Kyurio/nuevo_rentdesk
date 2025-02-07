<?php
$view = @$_GET["view"];
$token = @$_GET["token"];

if ($view == "codeudor") {
    include("models/codeudor.php");
    include("views/codeudor.php");
}

if ($view == "codeudor_list") {
    include("models/codeudor_list.php");
    include("views/codeudor_list.php");
}


if ($view == "codeudor_ficha_tecnica") {
    include("models/codeudor_ficha_tecnica.php");
    include("views/codeudor_ficha_tecnica.php");
}

