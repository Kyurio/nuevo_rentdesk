<?php
$view = @$_GET["view"];

if ($view == "dashboard") {
    include("models/dashboard.php");
    include("views/dashboard.php");
}
