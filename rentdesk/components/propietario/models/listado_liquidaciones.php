<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$num_reg = 50;
$inicio = 0;

$token_prop = $_POST["token"];
$queryLiq="    
select * from propiedades.propiedad_liquidaciones pl 
  inner join propiedades.propiedad_copropietarios pc on pl.id_ficha_propiedad = pc.id_propiedad 
  inner join propiedades.vis_propietarios vp on vp.id =pc.id_propietario 
  where vp.token_propietario ='$token_prop'  and pc.nivel_propietario = 1
  and pc.habilitado = true" ;
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryLiq, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objLiq = json_decode($resultado);

echo json_encode($objLiq);