<?php
session_start();
include("../../../includes/sql_inyection_salto_textarea.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$codigo                = $_POST['codigo'];
$tipo                = $_POST['tipo'];

$current_sucursal = unserialize($_SESSION["rd_current_sucursal"]);
//var_dump($current_sucursal->sucursalToken);

$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

//Aqui hacer la consulta a la base de datos:  *****************************************
$respuesta = 0;
$direccion     = "";
$contrato     = "";
$html = "";
//$busqueda = formato_busqueda($codigo);

$num_reg = 10;
$inicio = 0;

$query = "SELECT id from propiedades.cuenta_sucursal cs where token = '$current_sucursal->sucursalToken' ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado_sucursal = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_sucursal = json_decode($resultado_sucursal)[0];



$queryProp = "SELECT DISTINCT 
CONCAT(p.id, ' / ', codigo_propiedad, ' / ', COALESCE(CONCAT(direccion, ' ', numero), ''), ' / ', id_contrato) ||
     CONCAT(CASE 
         WHEN numero_depto IS NOT NULL AND numero_depto <> '' THEN concat(' Dpto ', numero_depto) 
         ELSE '' 
     END, 
     CASE 
         WHEN piso IS NOT NULL AND piso <> 0 THEN concat(' Piso ', piso) 
         ELSE '' 
     END
 )  AS texto,
 codigo_propiedad,
 COALESCE(CONCAT(direccion, ' ', numero), '') AS direccion,
 p.token, 
 CONCAT(CASE 
         WHEN numero_depto IS NOT NULL AND numero_depto <> '' THEN concat(' Dpto ', numero_depto) 
         ELSE '' 
     END, 
     CASE 
         WHEN piso IS NOT NULL AND piso <> 0 THEN concat(' Piso ', piso) 
         ELSE '' 
     END
 ) as direccion_concat,
 p.id
FROM 
 propiedades.propiedad p
 INNER JOIN propiedades.propiedad_copropietarios pc
 ON pc.id_propiedad = p.id
WHERE 
 (UPPER(CONCAT(direccion, ' ', numero)) LIKE UPPER('%$codigo%')
  OR CAST(codigo_propiedad AS VARCHAR) LIKE '%$codigo%'
  OR CAST(p.id AS VARCHAR) LIKE '%$codigo%')  
  AND codigo_propiedad is not null
ORDER BY 
 codigo_propiedad ASC, 
 direccion ASC
";




$dataRegPagos = array("consulta" => $queryProp);
$resultadoRegPagos = $services->sendPostDirecto($url_services . '/util/objeto', $dataRegPagos);
//var_dump($queryProp);
$result_jsonRegPagos = json_decode($resultadoRegPagos);
if ($result_jsonRegPagos) {
    foreach ($result_jsonRegPagos as $result) {
        $respuesta = 1;
        $id_select = $result->id;
        $direccion = @$result->direccion;
        $html = $html . '<div><a style="color:#2c699e;" class="suggest-element" id="' . $id_select . '" onClick="ingresaBusqueda(this);" >' . $result->texto . '</a></div>';
    }
}


echo $html;


//*************************************************************************************

//echo $html;
