<?php
$view = @$_GET["view"];

if ($view == "derecho_aseo") {
    include("models/derecho_aseo.php");
    include("views/derecho_aseo.php");
}

if ($view == "derecho_aseo_list") {
    include("models/derecho_aseo_list.php");
    include("views/derecho_aseo_list.php");
}

if ($view == "derecho_aseo_iframe") {
    include("models/derecho_aseo.php");
    include("views/derecho_aseo_iframe.php");
}

if ($view == "derecho_aseo_ficha_tecnica") {
    include("models/derecho_aseo_ficha_tecnica.php");
    include("views/derecho_aseo_ficha_tecnica.php");
}
