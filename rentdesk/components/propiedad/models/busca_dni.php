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
$existe = 0;
$num_reg = 10;
$inicio = 0;
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_subsidiaria = $current_subsidiaria->id;

$result = null;

if ($dni != "") {

  $query = "SELECT ps.dni as dni, ps.token as token, upper(ttp.nombre) as tipo_persona,
  upper(pnt.nombres) as nombres, upper(pnt.apellido_paterno) as apellido_paterno, 
  upper(pnt.apellido_materno) as apellido_materno, upper(pj.razon_social) as razon_social, 
  upper(pj.nombre_fantasia) as nombre_fantasia, upper(pd.direccion) as direccion, 
  pd.numero, pd.numero_depto, upper(pd.comentario) as comentario, 
  upper(pd.comentario2) as comentario2, ps.telefono_fijo as telefono_fijo, 
  ps.telefono_movil as telefono_movil, ps.correo_electronico,
  upper(tc.nombre) as comuna, upper(tr.nombre) as region, upper(tp.nombre) as pais
FROM propiedades.persona ps 
  left join propiedades.persona_natural pnt on ps.id = pnt.id_persona
  left join propiedades.persona_juridica pj on ps.id = pj.id_persona
  inner join propiedades.tp_tipo_persona ttp on ttp.id = ps.id_tipo_persona 
  inner join propiedades.persona_direcciones pd on ps.id = pd.id_persona
  inner join propiedades.tp_comuna tc on tc.id = pd.id_comuna
  inner join propiedades.tp_region tr on tc.id_region = tr.id 
  inner join propiedades.tp_pais tp on tr.id_pais = tp.id
  WHERE ps.id_subsidiaria = $id_subsidiaria and REPLACE(REPLACE(dni,'.',''),'-','') = REPLACE(REPLACE('$dni','.',''),'-','')  ";
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
  /*SI EXISTE SIGUE CON EL FLUJO A PROPIETARIO */
  $existe = 0;
} else {
  echo "ERROR||No se encuentra DNI $dni||persona";
  return;
}

/************************* */

if ($dni != "") {

  $query = "SELECT ps.id, ps.dni as dni, ps.token as token, upper(ttp.nombre) as tipo_persona,
  upper(pnt.nombres) as nombres, upper(pnt.apellido_paterno) as apellido_paterno, 
  upper(pnt.apellido_materno) as apellido_materno, upper(pj.razon_social) as razon_social, 
  upper(pj.nombre_fantasia) as nombre_fantasia, upper(pd.direccion) as direccion, 
  pd.numero, pd.numero_depto, upper(pd.comentario) as comentario, upper(pd.comentario2) as comentario2,
  ps.telefono_fijo as telefono_fijo, ps.telefono_movil as telefono_movil, ps.correo_electronico,
  upper(tc.nombre) as comuna, upper(tr.nombre) as region, upper(tp.nombre) as pais, pp.token as token_propietario
FROM propiedades.persona ps 
  left join propiedades.persona_natural pnt on ps.id = pnt.id_persona
  left join propiedades.persona_juridica pj on ps.id = pj.id_persona
  inner join propiedades.tp_tipo_persona ttp on ttp.id = ps.id_tipo_persona 
  inner join propiedades.persona_direcciones pd on ps.id = pd.id_persona
  inner join propiedades.tp_comuna tc on tc.id = pd.id_comuna
  inner join propiedades.tp_region tr on tc.id_region = tr.id 
  inner join propiedades.tp_pais tp on tr.id_pais = tp.id 
  inner join propiedades.persona_propietario pp on pp.id_persona = ps.id

  WHERE ps.id_subsidiaria = $id_subsidiaria and REPLACE(REPLACE(dni,'.',''),'-','') = REPLACE(REPLACE('$dni','.',''),'-','')  ";
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
  echo "OK||existe DNI||" . $result->token_propietario."||".$resultado;
} else {
  echo "ERROR||No se encuentra DNI $dni||propietario";
}
