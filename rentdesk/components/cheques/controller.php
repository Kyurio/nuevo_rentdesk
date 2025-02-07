<?php
$view = @$_GET["view"];

if ($view == "cheques") {
    include("models/cheques.php");
    include("views/cheques.php");
}

if ($view == "cheques_list") {

    include("models/cheques_list.php");
    include("views/cheques_list.php");
}


