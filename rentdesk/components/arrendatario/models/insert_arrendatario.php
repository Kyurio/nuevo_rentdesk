<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("../../../includes/sql_inyection_salto_textarea.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$config      = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$nombreTitular = @$_POST["nombreT"];
$rutTitular = @$_POST["rutT"];
$emailTitular = @$_POST["mailT"];
$numCuenta = @$_POST["numCta"];
$banco = @$_POST["bank"];
$ctabanco = @$_POST["cta"];
$token_persona = @$_POST["persona"];

$resultadoCabCta = "";
if ($rutTitular) {
     $cantidad = count($rutTitular);
}else{
     $cantidad = 0;
}




$num_reg = 50;
$inicio = 0;

$vueltas = "";

$queryPersona = "
        select id from  
        propiedades.persona 
        where  
        token = '$token_persona'";


$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryPersona, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCheques = json_decode($resultado);
$objeto = $objCheques[0]; // Accede al primer elemento del array
$id = $objeto->id; // Accede al valor de la propiedad 'id' dentro del objeto stdClass


$queryinsertarrendatario = "insert into propiedades.persona_arrendatario ( id_persona ) values ('$id')";

$dataCab = array("consulta" => $queryinsertarrendatario);
$resultadoCabProp = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

for ($i = 0; $i < $cantidad; $i++) {



     if ($rutTitular) {

          $queryinsertcta = "  Insert into propiedades.arrendatario_ctas_bancarias
          (id_arrendatario,id_banco, id_tipo_cta_bancaria, numero, correo_electronico, principal, habilitado, rut_titular, nombre_titular) 
          values('$id', '$banco[$i]', '$ctabanco[$i]','$numCuenta[$i]','$emailTitular[$i]','true','true','$rutTitular[$i]' ,'$nombreTitular[$i]')";


          $dataCab = array("consulta" => $queryinsertcta);
          $resultadoCabCta = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
     }
}

if($resultadoCabCta < 0){
     $resultadoCabCta = "";
}

echo "OK||" . $resultadoCabProp . "||" .  $resultadoCabCta;
