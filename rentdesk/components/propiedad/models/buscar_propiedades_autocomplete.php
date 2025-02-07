<?php
session_start();
include("../../../includes/sql_inyection_salto_textarea.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$codigo				= $_POST['codigo'];
$tipo				= $_POST['tipo'];

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

//Aqui hacer la consulta a la base de datos:  *****************************************
$respuesta = 0;
$direccion 	= "";
$contrato 	= "";
$html = "";
//$busqueda = formato_busqueda($codigo);

/*$queryProp = " SELECT distinct concat(codigo_propiedad,' / ',coalesce(direccion,''),' / ' ,id_contrato) as texto,codigo_propiedad,coalesce(direccion,'') direccion
				FROM arpis.propiedad
				   WHERE ( upper(direccion) like '%$busqueda%'
						  or codigo_propiedad like '%$busqueda%'
						  ) AND id_estado_propiedad IN ('1','2','3',4)
				   ORDER BY codigo_propiedad ASC, direccion ASC
				   LIMIT 20
				   ";*/
$queryProp = " SELECT distinct CONCAT(id, ' / ', codigo_propiedad, ' / ', COALESCE(CONCAT(direccion, ' ', numero), ''), ' / ', id_contrato) ||
     CONCAT(CASE 
         WHEN numero_depto IS NOT NULL AND numero_depto <> '' THEN concat(' Dpto ', numero_depto) 
         ELSE '' 
     END, 
     CASE 
         WHEN piso IS NOT NULL AND piso <> 0 THEN concat(' Piso ', piso) 
         ELSE '' 
     END
 )  AS texto,codigo_propiedad,coalesce(concat(direccion,' ',numero),'') direccion
				FROM propiedades.propiedad
				   WHERE ( upper(concat(direccion,' ',numero)) like UPPER('%$codigo%')
						  or CAST(codigo_propiedad AS VARCHAR) like '%$codigo%'
						  or CAST(id AS VARCHAR) like '%$codigo%'
						  or CAST(direccion AS VARCHAR) like '%$codigo%'
						  )
				   ORDER BY codigo_propiedad ASC, direccion ASC
				   LIMIT 20
				   ";
$dataRegPagos = array("consulta" => $queryProp);
$resultadoRegPagos = $services->sendPostDirecto($url_services . '/util/objeto', $dataRegPagos);
//var_dump($queryProp);
$result_jsonRegPagos = json_decode($resultadoRegPagos);
if ($result_jsonRegPagos) {
	foreach ($result_jsonRegPagos as $result) {
		$respuesta = 1;
		$id_select = $result->codigo_propiedad;
		$direccion = @$result->direccion;
		$html = $html . '<div><a style="color:#2c699e;" class="suggest-element" id="' . $id_select . '" onClick="ingresaBusqueda(this);" >' . $result->texto . '</a></div>';
		//$html = $html.'<div><a class="suggest-element" id="'.$id_select.'" onClick="ingresaBusqueda(this,\''.$tipo.'\',\''.$direccion.'\');" >'.$result->texto.'</a></div>';
		//$html = $html.'<div><a class="suggest-element" id="'.$result->codigo_propiedad.'" onClick="ingresaBusqueda(this);" >'.$result->direccion.'</a></div>';
		//$html = $html.'<div><a class="suggest-element" id="'.$result->codigo_propiedad.'" onClick="ingresaBusqueda(this);" >'.$result->direccion.'</a></div>';
		//$html = $html. 'value <?php echo "'.$id_select.'";;

	} //while
	//$html = $html. 'value <?php echo "'.$id_select.'"';
}



//*************************************************************************************


echo $html;
