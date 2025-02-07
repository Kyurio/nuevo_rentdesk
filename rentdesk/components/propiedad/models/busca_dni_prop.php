<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

$dni    = @$_POST["dni"];
$idCtaBanc    = @$_POST["cta_banc"];

//$id_company = $_SESSION["rd_company_id"];
$id_tipo_persona = 1;
$existe = 0;
$num_reg = 10;
$inicio = 0;


//var_dump("CURRENT SUB: ",$current_subsidiaria );
$result = null;



// if ($dni != "" && $idCtaBanc != "") {

$dni1 = str_replace(".", "", $dni);
$dni2 = str_replace("-", "", $dni);

// $query = " SELECT pcb.id, pcb.rut_titular, pcb.nombre_titular, pcb.numero as numero_cta_banc, ps.dni as dni, ps.token as token, ttp.nombre as tipo_persona,
// pnt.nombres as nombres, pnt.apellido_paterno as apellido_paterno, 
// pnt.apellido_materno as apellido_materno ,pj.razon_social as razon_social, pj.nombre_fantasia  as nombre_fantasia ,
// pd.direccion as direccion, pd.numero, pd.numero_depto, pd.comentario , pd.comentario2,
// ps.telefono_fijo as telefono_fijo , ps.telefono_movil as telefono_movil, ps.correo_electronico,
// tc.nombre as comuna, tr.nombre as region,tp.nombre as pais, pp.id_persona as id_propietario, pp.token as token_prop
// FROM propiedades.persona ps 
// left join propiedades.persona_natural pnt on ps.id  = pnt.id_persona
// left  join propiedades.persona_juridica pj  on ps.id = pj.id_persona
// inner join propiedades.tp_tipo_persona ttp on ttp.id =ps.id_tipo_persona 
// inner join propiedades.persona_direcciones pd on ps.id = pd.id_persona
// inner join propiedades.tp_comuna tc on tc.id = pd.id_comuna
// inner join propiedades.tp_region tr on tc.id_region = tr.id 
// inner join propiedades.tp_pais tp on tr.id_pais = tp.id 
// left join propiedades.persona_propietario pp on pp.id_persona  = ps.id 
// inner join propiedades.propietario_ctas_bancarias pcb  on pcb.id_propietario = pp.id_persona
// inner join propiedades.vis_propietarios vp on vp.id = ps.id
// where vp.token_subsidiaria = '$current_subsidiaria->token'
// AND  (ps.dni  =  '$dni' OR ps.dni  =  '$dni1' OR ps.dni  =  '$dni2' ) ";

$query = "SELECT 
    pcb.id, pcb.rut_titular, pcb.nombre_titular, pcb.numero AS numero_cta_banc, 
    ps.dni, ps.token, ttp.nombre AS tipo_persona, pnt.nombres, pnt.apellido_paterno, pnt.apellido_materno, 
    pj.razon_social, pj.nombre_fantasia, pd.direccion, pd.numero, pd.numero_depto, pd.comentario, pd.comentario2, 
    ps.telefono_fijo, ps.telefono_movil, ps.correo_electronico, 
    tc.nombre AS comuna, tr.nombre AS region, tp.nombre AS pais, 
    pp.id_persona AS id_propietario, pp.token AS token_prop
FROM propiedades.persona ps
LEFT JOIN propiedades.persona_natural pnt ON ps.id = pnt.id_persona
LEFT JOIN propiedades.persona_juridica pj ON ps.id = pj.id_persona
INNER JOIN propiedades.tp_tipo_persona ttp ON ttp.id = ps.id_tipo_persona
INNER JOIN propiedades.persona_direcciones pd ON ps.id = pd.id_persona
INNER JOIN propiedades.tp_comuna tc ON tc.id = pd.id_comuna
INNER JOIN propiedades.tp_region tr ON tc.id_region = tr.id
INNER JOIN propiedades.tp_pais tp ON tr.id_pais = tp.id
LEFT JOIN propiedades.persona_propietario pp ON pp.id_persona = ps.id
LEFT JOIN propiedades.propietario_ctas_bancarias pcb ON pcb.id_propietario = pp.id_persona
INNER JOIN propiedades.vis_propietarios vp ON vp.id = ps.id
WHERE 
    vp.token_subsidiaria = '$current_subsidiaria->token'
    AND ps.dni IN ('$dni', '$dni1', '$dni2' )";

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


if ($existe > 0) {
    echo "OK||existe DNI||" . $result->token . "||" . $resultado;
} else {
    echo "ERROR||No se encuentra DNI $dni||propietario";
}
