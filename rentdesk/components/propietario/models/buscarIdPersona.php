<?php

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$token    = @$_GET["token"];


// Obtener el método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

// Validar si el método no es DELETE
if ($metodo !== 'GET') {
    // Manejar el error
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido. Solo se permite GET.']);
    exit;
}

// tipo movimeintos cargos arrendatarios
$query_count = "SELECT ps.id
  FROM propiedades.persona ps 
  left join propiedades.persona_natural pnt on ps.id  = pnt.id_persona
  left  join propiedades.persona_juridica pj  on ps.id = pj.id_persona
  inner join propiedades.tp_tipo_persona ttp on ttp.id =ps.id_tipo_persona 
  inner join propiedades.persona_direcciones pd on ps.id = pd.id_persona
  inner join propiedades.tp_comuna tc on tc.id = pd.id_comuna
  inner join propiedades.tp_region tr on tc.id_region = tr.id 
  inner join propiedades.tp_pais tp on tr.id_pais = tp.id
  inner join propiedades.tp_tipo_dni ttd  on ttd.id = ps.id_tipo_dni 
  left join propiedades.persona_propietario pp on pp.id_persona  = ps.id 
  where pp.id_persona is not null and pp.token='$token'";

$data = array("consulta" => $query_count);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);



echo $resultado;