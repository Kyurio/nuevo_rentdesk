<?php 
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$num_reg = 50;
$inicio = 0;



$token = $_POST["token"];
$queryctacorriente="select tb.nombre  as nombre_banco, tcb.nombre as tipo_cta, pcb.numero as numero_cuenta,
  pcb.rut_titular as rut_titular, pcb.nombre_titular as nombre_titular, pcb.correo_electronico  as correo_electronico 
  from propiedades.persona p 
  inner join propiedades.persona_propietario pp on p.id = pp.id_persona 
  inner join propiedades.propietario_ctas_bancarias pcb on pcb.id_propietario = pp.id_persona 
  inner join  propiedades.tp_cta_bancaria tcb on tcb.id = pcb.id_tipo_cta_bancaria 
  inner join propiedades.tp_banco tb on tb.id = pcb.id_banco 
  where pp.token= '$token'";

 $cant_rows = $num_reg;
  $num_pagina = round($inicio / $cant_rows) + 1;
  $data = array("consulta" => $queryctacorriente, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
  $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
  print_r($resultado);


?>