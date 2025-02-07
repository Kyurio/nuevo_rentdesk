<?php
session_start();
include("../../../includes/sql_inyection_salto_textarea.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


$token_persona = @$_POST["tokenPersona"];

$num_reg = 50;
$inicio = 0;



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
     
     
        $queryinsertcodeudor = "insert into propiedades.persona_codeudor ( id_persona ) values ('$id')";

        $dataCab = array("consulta" => $queryinsertcodeudor);
        $resultadoCabProp = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

        echo "OK||".$resultadoCabProp;

