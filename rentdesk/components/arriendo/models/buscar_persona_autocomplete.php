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

if ($tipo == "Propietario") {
	$queryProp = " SELECT distinct concat(nombre_1||' '||nombre_2 ,' | ',dni,' | ' ,correo_electronico) as texto,nombre_1 as nombre, correo_electronico,token_propietario
				FROM propiedades.vis_propietarios va
				   WHERE ( upper(concat(nombre_1,' ',nombre_2)) like UPPER('%$codigo%')
						  or UPPER(correo_electronico) like UPPER('%$codigo%')
						  or UPPER(REPLACE(REPLACE(dni,'.',''),'-','')) like UPPER(REPLACE(REPLACE('%$codigo%','.',''),'-',''))
						  ) 
				   ORDER BY nombre ASC, correo_electronico ASC
				   LIMIT 20
				   ";
}

if ($tipo == "Arrendatario") {
	$queryProp = " SELECT distinct concat(nombre_1||' '||nombre_2  ,' | ',dni,' | ' ,correo_electronico) as texto,nombre_1 as nombre, correo_electronico,token_arrendatario
				FROM propiedades.vis_arrendatarios va
				   WHERE ( upper(concat(nombre_1,' ',nombre_2)) like UPPER('%$codigo%')
						  or UPPER(correo_electronico) like UPPER('%$codigo%')
						  or UPPER(REPLACE(REPLACE(dni,'.',''),'-','')) like UPPER(REPLACE(REPLACE('%$codigo%','.',''),'-',''))
						  ) 
				   ORDER BY nombre ASC, correo_electronico ASC
				   LIMIT 20
				   ";
}

if ($tipo == "FichaArriendo") {
	$queryProp = "SELECT distinct concat(b.direccion||' | '|| a.id) AS texto, a.id as nombre from propiedades.ficha_arriendo a inner join propiedades.propiedad b on a.id_propiedad = b.id where CAST(a.id AS VARCHAR) like '%$codigo%' LIMIT 20";
}

//var_dump($queryProp);			   
$dataRegPagos = array("consulta" => $queryProp);
$resultadoRegPagos = $services->sendPostDirecto($url_services . '/util/objeto', $dataRegPagos);
//var_dump($queryProp);
$result_jsonRegPagos = json_decode($resultadoRegPagos);
if ($result_jsonRegPagos) {
	foreach ($result_jsonRegPagos as $result) {
		$respuesta = 1;
		$id_select = $result->nombre;
		//$direccion = @$result->direccion;
		$html = $html . '<div><a style="color:#2c699e;" class="suggest-element" id="' . $id_select . '"  >' . $result->texto . '</a></div>';
		//$html = $html.'<div><a class="suggest-element" id="'.$id_select.'" onClick="ingresaBusqueda(this,\''.$tipo.'\',\''.$direccion.'\');" >'.$result->texto.'</a></div>';
		//$html = $html.'<div><a class="suggest-element" id="'.$result->codigo_propiedad.'" onClick="ingresaBusqueda(this);" >'.$result->direccion.'</a></div>';
		//$html = $html.'<div><a class="suggest-element" id="'.$result->codigo_propiedad.'" onClick="ingresaBusqueda(this);" >'.$result->direccion.'</a></div>';
		//$html = $html. 'value <?php echo "'.$id_select.'";;

	} //while
	//$html = $html. 'value <?php echo "'.$id_select.'"';
}



//*************************************************************************************


echo $html;
