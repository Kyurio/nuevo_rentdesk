<?php
session_start();
include("../../../includes/sql_inyection_salto_textarea.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$codigo				= $_POST['codigo'];
$tipo				= $_POST['tipo'];

$current_sucursal = unserialize($_SESSION["rd_current_sucursal"]);
//var_dump($current_sucursal->sucursalToken);

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

//Aqui hacer la consulta a la base de datos:  *****************************************
$respuesta = 0;
$direccion 	= "";
$contrato 	= "";
$html = "";
//$busqueda = formato_busqueda($codigo);

$num_reg = 10;
$inicio = 0;

$query = " select id from propiedades.cuenta_sucursal cs where token = '$current_sucursal->sucursalToken' ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado_sucursal = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_sucursal = json_decode($resultado_sucursal)[0];

/*$queryProp = " SELECT distinct concat(codigo_propiedad,' / ',coalesce(direccion,''),' / ' ,id_contrato) as texto,codigo_propiedad,coalesce(direccion,'') direccion
				FROM arpis.propiedad
				   WHERE ( upper(direccion) like '%$busqueda%'
						  or codigo_propiedad like '%$busqueda%'
						  ) AND id_estado_propiedad IN ('1','2','3',4)
				   ORDER BY codigo_propiedad ASC, direccion ASC

				    concat(codigo_propiedad,' | ',coalesce(concat(direccion,' ',numero),''),' | ' ,id) as texto
				   LIMIT 20
				   ";*/
$queryProp = "SELECT distinct CONCAT(codigo_propiedad,' | ',
                direccion, 
                ' #', numero, 
                CASE 
                    WHEN numero_depto IS NOT NULL AND numero_depto <> '' THEN CONCAT(' | Dpto ', numero_depto) 
                    ELSE '' 
                END, 
                CASE 
                    WHEN piso IS NOT NULL AND piso <> 0 THEN CONCAT(' | Piso ', piso) 
                    ELSE '' 
                END
            ) as texto 

,codigo_propiedad,coalesce(concat(direccion,' ',numero),'') direccion,token, id
				FROM propiedades.propiedad
				   WHERE ( upper(concat(direccion,' ',numero)) like UPPER('%$codigo%')
						  or CAST(codigo_propiedad AS VARCHAR) like '%$codigo%'
						  or CAST(id AS VARCHAR) like '%$codigo%'
						  ) --AND id_sucursal = $json_sucursal->id
				   ORDER BY codigo_propiedad ASC, direccion ASC
				   LIMIT 25
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
		$html = $html . '<div><a style="color:#2c699e;" class="suggest-element" id="' . $id_select . '" >' . $result->texto . '</a></div>';
		//$html = $html.'<div><a class="suggest-element" id="'.$id_select.'" onClick="ingresaBusqueda(this,\''.$tipo.'\',\''.$direccion.'\');" >'.$result->texto.'</a></div>';
		//$html = $html.'<div><a class="suggest-element" id="'.$result->codigo_propiedad.'" onClick="ingresaBusqueda(this);" >'.$result->direccion.'</a></div>';
		//$html = $html.'<div><a class="suggest-element" id="'.$result->codigo_propiedad.'" onClick="ingresaBusqueda(this);" >'.$result->direccion.'</a></div>';
		//$html = $html. 'value <?php echo "'.$id_select.'";;

	} //while
	//$html = $html. 'value <?php echo "'.$id_select.'"';
}



//*************************************************************************************


echo $html;
