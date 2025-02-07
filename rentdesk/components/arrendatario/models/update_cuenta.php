<?php
session_start();
include("../../../includes/sql_inyection_salto_textarea.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$nombreTitular = @$_POST["nombreTitularEdit"];
$rutTitular = @$_POST["rutTitularEdit"];
$emailTitular = @$_POST["emailTitularEdit"];
$numCuenta = @$_POST["numCuentaEdit"];
$banco = @$_POST["bancoEdit"];
$ctabanco = @$_POST["ctabancoEdit"];

$idCuenta = @$_POST["persona"];
$num_reg = 50;
$inicio = 0;


       
       $updatecta = "  update propiedades.arrendatario_ctas_bancarias
                       set id_banco='$banco',
                       id_tipo_cta_bancaria='$ctabanco',
                       numero= '$numCuenta',
                       correo_electronico='$emailTitular',
                       rut_titular = '$rutTitular',
                       nombre_titular = '$nombreTitular'
                       where id = $idCuenta";
       
       $dataCab = array("consulta" => $updatecta);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
       
        echo "OK||".$resultadoCab;