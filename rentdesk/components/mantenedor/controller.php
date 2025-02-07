<?php
$view = @$_GET["view"];
$token = @$_GET["token"];

if ($view == "mantenedor") {
    include("models/mantenedor.php");
    include("views/mantenedor.php");
}

if ($view == "mant_cta_contable_list") {
    include("models/mant_cta_contable_list.php");
    include("views/mant_cta_contable_list.php");
}


if ($view == "mant_roles_list") {
    include("models/mant_roles_list.php");
    include("views/mant_roles_list.php");
}

if ($view == "mant_usuarios_list") {
    include("models/mant_usuarios_list.php");
    include("views/mant_usuarios_list.php");
}

if ($view == "mant_sucursal_list") {
    include("models/mant_sucursal_list.php");
    include("views/mant_sucursal_list.php");
}

if ($view == "mant_subsidiaria_list") {
    include("models/mant_subsidiaria_list.php");
    include("views/mant_subsidiaria_list.php");
}
