<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$token				= $_POST['token'];
$id_company 	= $_SESSION["rd_company_id"];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


$mandato_anterior 	= "";
	
$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/propiedad/token',$data);	

if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result = $result_r;
	$id_propiedad 		= @$result->id_propiedad;
	$mandato_anterior 	= @$result->mandato;
	
	try {
		if( delete_file("../../../upload/mandato/".$mandato_anterior) === true ){
			$msg = "OK";
		}	
	}catch (Exception $e) {
			$msg = $e->getMessage(); 
	}
		
}//foreach($result_json as $result)
}

if($token!=""){
$mandato = "";	
$data = array("banos" => @$result->banos,
			  "banosVisita" => @$result->banos_visita,
			  "bodegas" => @$result->bodegas,
			  "codigoPropiedad" => @$result->codigo_propiedad,
			  "coordenadas" => @$result->coordenadas,
			  "direccion" => @$result->direccion,
			  "dormitorios" => @$result->dormitorios,
			  "dormitoriosServicio" => @$result->dormitorios_servicio,
			  "edificado" => @$result->edificado,
			  "estacionamientos" => @$result->estacionamientos,
			  "fechaIngreso" => @$result->fecha_ingreso,
			  "logia" => @$result->logia,
			  "numero" => @$result->numero,
			  "numeroDepto" => @$result->numero_depto,
			  "piscina" => @$result->piscina,
			  "piso" => @$result->piso,
			  "precio" => @$result->precio,
			  "rol" => @$result->rol,
			  "terreno" => @$result->terreno,
			  "idEstadoPropiedad" => @$result->id_estado_propiedad,
			  "idTipoMoneda" => @$result->id_moneda,
			  "idTipoPropiedad" => @$result->id_tipo_propiedad,
			  "terreno" => @$result->terreno,
			  "token" => @$result->token,
			  "idSucursal" => @$result->id_sucursal,
			  "idEmpresa" => @$result->id_empresa,
			  "idComuna"=> @$result->id_comuna,
			  "mandato" => $mandato);				  							
$resultado = $services->sendPut($url_services.'/propiedad',$data);		

$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,Archivo Eliminado,xxx,$token,xxx,";
	
	
}//if($token!="" && $nombre!=""){
?>