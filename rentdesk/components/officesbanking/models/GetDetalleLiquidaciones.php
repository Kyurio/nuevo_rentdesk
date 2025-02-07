<?php

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config         = new Config;
$services       = new ServicesRestful;
$url_services   = $config->url_services;

// Obtener el método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

// Validar si el método no es DELETE
if ($metodo !== 'GET') {
    // Manejar el error
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido. Solo se permite GET.']);
    exit;
}

$cierre = $_GET["cierre"];

/*Consulta Cantidad de registros*/
$query_count = "SELECT pl.id    as liquidacion
     , pl.id_ficha_propiedad   as ficha_propiedad
     , pl.id_propietario 
     , case 
     	 when per.id_tipo_persona = 1
     	     then trim(pn.nombres || ' ' || pn.apellido_paterno || ' ' || pn.apellido_paterno ) 
     	     else trim(pj.razon_social)
       end   as nombre
     ,  upper(CONCAT(p.direccion, ' ', p.numero, CASE 
					WHEN p.numero_depto IS NOT NULL AND p.numero_depto <> '' THEN concat(' Dpto ', p.numero_depto) 
					ELSE '' 
				END, 
				CASE 
					WHEN p.piso IS NOT NULL AND p.piso <> 0 THEN concat(' Piso ', p.piso) 
					ELSE '' 
				END
			)) as direccion
     , to_char(pl.fecha_liquidacion ::DATE, 'DD/MM/YYYY') as fecha_liquidacion
     , pl.id_ficha_arriendo  as ficha_arriendo
     , pl.cierre             as cierre
  from propiedades.propiedad_liquidaciones pl
  inner join propiedades.propiedad p        on p.id = pl.id_ficha_propiedad 
  inner join propiedades.persona per        on per.id = pl.id_propietario 
  left join propiedades.persona_natural pn  on pn.id_persona = pl.id_propietario 
  left join propiedades.persona_juridica pj on pj.id_persona = pl.id_propietario 
  where estado = 1
  and pl.cierre = $cierre order by pl.id";

$data = array("consulta" => $query_count);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);



echo $resultado;
