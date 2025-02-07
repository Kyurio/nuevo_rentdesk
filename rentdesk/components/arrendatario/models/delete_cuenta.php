<?php
session_start();
include("../../../includes/sql_inyection_salto_textarea.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_cuenta = @$_POST["id_cta"];



$num_reg = 50;
$inicio = 0;


       
       $deletecta = "  update propiedades.arrendatario_ctas_bancarias
                       set habilitado='false'
                       where id = $id_cuenta";
       
      $dataCab = array("consulta" => $deletecta);
       $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

       echo "OK||".$resultadoCab;