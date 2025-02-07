<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$url_reportes_eje = $config->url_reportes_eje;

$token			= @$_GET["t"];
$id_company 	= $_SESSION["rd_company_id"]; 
//$rol_usuario 	= $_SESSION["usuario_rol"];
$id_usuario    	= $_SESSION["rd_usuario_id"];


/////////////////////////José




$num_reg = 10000;
$inicio = 0;
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$query_tipo_comision="    
SELECT DISTINCT tipo_comision 
FROM propiedades.propiedad_comision_liquidacion";
$data = array("consulta" => $query_tipo_comision, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado_tipo_com = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$obj_tipo_com = json_decode($resultado_tipo_com);


$query_mov_comiciones=	"SELECT 
    TO_CHAR(pl.fecha_liquidacion, 'YYYY-MM') AS mes,
    SUM(CASE WHEN pcl.tipo_comision = 'Administracion' THEN pcl.monto ELSE 0 END) AS Administracion,
    SUM(CASE WHEN pcl.tipo_comision = 'Arriendo' THEN pcl.monto ELSE 0 END) AS Arriendo
FROM 
    propiedades.propiedad_liquidaciones pl 
INNER JOIN  
    propiedades.propiedad_comision_liquidacion pcl 
ON 
    pl.id = pcl.id_propiedad_liquidacion 
GROUP BY 
    TO_CHAR(pl.fecha_liquidacion, 'YYYY-MM')
ORDER BY 
    mes";


$data = array("consulta" => $query_mov_comiciones, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objMovimientos = json_decode($resultado);

// Inicializar un array vacío para almacenar los meses
$meses = [];
$administracion = [];
$arriendo = [];
// Recorrer el array de objetos y extraer los meses
foreach ($objMovimientos as $movimiento) {
    $meses[] = $movimiento->mes;
	$administracion[] = $movimiento->administracion;
	$arriendo[] = $movimiento->arriendo;
}
$meses_str = "['" . implode("', '", $meses) . "']";
$administracion_str = "['" . implode("', '", $administracion) . "']";
$arriendo_str = "['" . implode("', '", $arriendo) . "']";

echo $meses_str."||".$administracion_str."||".$arriendo_str;