<?php 
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$token	= @$_POST["token"];

$query_propiedades = "    SELECT p.codigo_propiedad , p.direccion , p.numero, p.token as token_prop,
pc.porcentaje_participacion_base ,
tc.nombre  as comuna, tr.nombre as region
FROM propiedades.propiedad p 
INNER JOIN propiedades.propiedad_copropietarios pc ON pc.id_propiedad = p.id 
INNER JOIN propiedades.vis_propietarios vp ON vp.id = pc.id_propietario  
inner join propiedades.tp_comuna tc on p.id_comuna  = tc.id 
inner join propiedades.tp_region tr on tc.id_region = tr.id 
WHERE vp.token_propietario = '$token' 
  AND p.codigo_propiedad IS NOT NULL 
  AND p.id_ejecutivo IS NOT null
  and pc.nivel_propietario = 1
  and pc.habilitado = true
 ";
 $data_propiedades = array("consulta" => $query_propiedades );	
 $resultado_propiedades= $services->sendPostDirecto($url_services.'/util/objeto',$data_propiedades);
 print_r($resultado_propiedades);
?>