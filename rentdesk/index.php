<?php
session_start();
include("includes/sql_inyection.php");
include("configuration.php");
include("includes/listar_directorio.php");
include("includes/funciones.php");
include("includes/services_util.php");
$token_recuperar_clave     = @$_GET["toc"];


if (@$_SESSION["rd_usuario_valido_arpis"] != "true") {

    if ($token_recuperar_clave == "") {
        include("login/login.php");
    } else {
        include("login/recuperar.php");
    }
} else {


    $config      = new Config;
    $archivos    = new archivos;
    $component   = @$_GET["component"];
    $view        = @$_GET["view"];

    //Aquí leemos los archivos js del componente para incluirlos
    $version_app = $config->version_app;
    $incluir_js        = "";
    $incluir_css    = "";

    $document_js    = @$archivos->listar("components/$component/js/");
    if ($document_js != "")
        foreach ($document_js as $archivo_js)
            $incluir_js         = $incluir_js . "<script src=\"components/$component/js/$archivo_js?$version_app\"></script>

";
    $archivos->limpiar();

    //Aquí leemos los archivos css del componente para incluirlos
    $document_css    = @$archivos->listar("components/$component/css/");
    if ($document_css != "")
        foreach ($document_css as $archivo_css)
            $incluir_css     = @$incluir_css . "<link href=\"components/$component/css/$archivo_css?$version_app\" rel=\"stylesheet\">
";


    include("template/index.php");
} //if($_SESSION["rd_usuario_valido_arpis"]!="true")

$url_logout = "login/models/logout.php";
