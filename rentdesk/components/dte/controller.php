<?php
$view = @$_GET["view"];
$token = @$_GET["token"];

if ($view == "dte") {
    include("views/dte.php");
}

