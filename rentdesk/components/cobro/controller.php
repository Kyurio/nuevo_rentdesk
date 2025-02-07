<?php
$view = @$_GET["view"];

if ($view == "cobro") {
    include("models/cobro.php");
    include("views/cobro.php");
}

if ($view == "cobro_list") {
    include("models/cobro_list.php");
    include("views/cobro_list.php");
}

if ($view == "cobro_iframe") {
    include("models/cobro.php");
    include("views/cobro_iframe.php");
}

if ($view == "cobro_ficha_tecnica") {
    include("models/cobro_ficha_tecnica.php");
    include("views/cobro_ficha_tecnica.php");
}


if ($view == "cobro_arriendo_otros_pagos_list") {
    include("models/cobro_arriendo_otros_pagos_list.php");
    include("views/cobro_arriendo_otros_pagos_list.php");
}
