<?php
session_start();
include("../../../includes/sql_inyection_salto_textarea.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$nombreTitular = @$_POST["nombreTitular"];
$rutTitular = @$_POST["rutTitular"];
$emailTitular = @$_POST["emailTitular"];
$numCuenta = @$_POST["numCuenta"];
$banco = @$_POST["banco"];
$ctabanco = @$_POST["cta-banco"];

$token_persona = @$_POST["persona"];

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


        $queryinsertpropietario = "insert into propiedades.persona_propietario ( id_persona ) values ('$id')";

        $dataCab = array("consulta" => $queryinsertpropietario);
        $resultadoCabProp = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
       
       $queryinsertcta = "  Insert into propiedades.propietario_ctas_bancarias
        (id_propietario,id_banco, id_tipo_cta_bancaria, numero, correo_electronico, principal, habilitado, rut_titular, nombre_titular) 
        values('$id', '$banco', '$ctabanco','$numCuenta','$emailTitular','true','true','$rutTitular' ,'$nombreTitular')";
       
        $dataCab = array("consulta" => $queryinsertcta);
        $resultadoCabCta = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
        echo "OK||".$resultadoCabProp."||".$resultadoCabCta;