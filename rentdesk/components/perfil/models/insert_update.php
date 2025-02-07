<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$nombre_usuario	= $_POST['nombre_usuario'];
$direccion		= $_POST['direccion'];
$telefono		= $_POST['telefono'];
$observacion	= $_POST['observacion'];

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


if($nombre_usuario!=""){
$data = array("nombre_usuario" => $nombre_usuario,
			  "direccion" => $direccion,
			  "telefono" => $telefono,
			  "observacion" => $observacion,
			  "id_usuario" => $id_usuario,
			  "idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/usuario/update',$data);		

$result = json_decode($resultado); 

echo ",xxx,OK,xxx,Perfil Actualizado exitosamente,xxx,";

}//if( $cantidad_locales <= $plan_locales ){
	
echo ",xxx,ERROR,xxx,Error al actualizar el perfil,xxx,";

?>