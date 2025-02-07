<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$dni    = @$_POST["dni"];
//$id_company = $_SESSION["rd_company_id"];
$id_tipo_persona = 1;
$existe = 0;
$num_reg = 10;
$inicio = 0;


$result = null;

if ($dni != "") {

  $query = " SELECT ps.dni as dni, ps.token as token, ttp.nombre as tipo_persona,
  pnt.nombres as nombres, pnt.apellido_paterno as apellido_paterno, 
  pnt.apellido_materno as apellido_materno ,pj.razon_social as razon_social, pj.nombre_fantasia  as nombre_fantasia ,
  pd.direccion as direccion, pd.numero, pd.numero_depto, pd.comentario , pd.comentario2,
  ps.telefono_fijo as telefono_fijo , ps.telefono_movil as telefono_movil, ps.correo_electronico,
  tc.nombre as comuna, tr.nombre as region,tp.nombre as pais, pa.id_persona as id_propietario, pa.token as token_arrendatario
  FROM propiedades.persona ps 
  left join propiedades.persona_natural pnt on ps.id  = pnt.id_persona
  left  join propiedades.persona_juridica pj  on ps.id = pj.id_persona
  inner join propiedades.tp_tipo_persona ttp on ttp.id =ps.id_tipo_persona 
  inner join propiedades.persona_direcciones pd on ps.id = pd.id_persona
  inner join propiedades.tp_comuna tc on tc.id = pd.id_comuna
  inner join propiedades.tp_region tr on tc.id_region = tr.id 
  inner join propiedades.tp_pais tp on tr.id_pais = tp.id 
  left join propiedades.persona_arrendatario pa on pa.id_persona  = ps.id 
   where REPLACE(REPLACE(dni,'.',''),'-','') = REPLACE(REPLACE('$dni','.',''),'-','')  ";
  $cant_rows = $num_reg;
  $num_pagina = round($inicio / $cant_rows) + 1;
  $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
  $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
  $json = json_decode($resultado);
  if ($json != "" || $json != null) {
    $existe = 1;
    $json = json_decode($resultado)[0];
    $result = $json;
  }
}

if ($existe > 0) {
  echo "OK||existe DNI||" . $result->token."||".$resultado;
} else {
  echo "ERROR||No se encuentra DNI $dni";
}