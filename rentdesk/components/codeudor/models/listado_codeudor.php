<?php 
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$num_reg = 500;
$inicio = 0;

$dni =  @$_POST["dniCodeudor"];

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_subsidiaria = $current_subsidiaria->id;

$query = "SELECT ps.dni as dni, ps.token as token, ps.id_tipo_persona as id_tipo_persona ,
  ttp.nombre as tipo_persona,
  pnt.nombres as nombres, pnt.apellido_paterno as apellido_paterno, 
  pnt.apellido_materno as apellido_materno ,pj.razon_social as razon_social, pj.nombre_fantasia  as nombre_fantasia ,
  pd.direccion as direccion, pd.numero, pd.numero_depto, pd.comentario , pd.comentario2,
  ps.telefono_fijo as telefono_fijo , ps.telefono_movil as telefono_movil, ps.correo_electronico,
  tc.nombre as comuna, tr.nombre as region,tp.nombre as pais, ps.id as id_persona,
  pc.token as tokencodeudor, ttd.nombre  as tipo_dni
  FROM propiedades.persona ps 
  left join propiedades.persona_natural pnt on ps.id  = pnt.id_persona
  left  join propiedades.persona_juridica pj  on ps.id = pj.id_persona
  inner join propiedades.tp_tipo_persona ttp on ttp.id =ps.id_tipo_persona 
  inner join propiedades.persona_direcciones pd on ps.id = pd.id_persona
  inner join propiedades.tp_comuna tc on tc.id = pd.id_comuna
  inner join propiedades.tp_region tr on tc.id_region = tr.id 
  inner join propiedades.tp_pais tp on tr.id_pais = tp.id
  inner join propiedades.tp_tipo_dni ttd  on ttd.id = ps.id_tipo_dni 
  left join propiedades.persona_codeudor pc on pc.id_persona  = ps.id 
  where  pc.id_persona is not null and ps.id_subsidiaria = $id_subsidiaria ";


 
  if(isset($dni) && $dni != "" ){
  $query= $query ." AND dni = '$dni' ";
   
  }
 

 
 
  $cant_rows = $num_reg;
  $num_pagina = round($inicio / $cant_rows) + 1;
  $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
  $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
   
  if ($resultado==""){
    echo "ERROR";
  }else{
  print_r($resultado);
  }
  
  
  ?>