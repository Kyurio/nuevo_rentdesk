<?php 

session_start();
include("sql_inyection.php");
include("../configuration.php");
include("funciones.php");
include("services_util.php");
$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];


$textoDiferencia = @$_POST["textoDiferencia"];
$tipoRegistro = @$_POST["tipoRegistro"];
$component = @$_POST["component"];
$view = @$_POST["view"];
$token = @$_POST["token"];
$item = @$_POST["item"];
$id_recurso = @$_POST["id_recurso"];
$id_item = @$_POST["id_item"];
// Obtener el objeto de sesión y convertirlo en un objeto PHP
$sesion_rd_login = unserialize($_SESSION['sesion_rd_login']);
// Acceder a la dirección de correo electrónico
$correo = $sesion_rd_login->correo;


$queryHistorial = "INSERT INTO propiedades.historial 
(responsable, accion, item,components, view,descripcion,id_recurso, id_item)
 VALUES ('$correo','$tipoRegistro','$item','$component','$view','$textoDiferencia', $id_recurso,$id_item)";
$dataCab = array("consulta" => $queryHistorial);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

echo  "Historial Guardado";

//echo "insert ".$textoDiferencia." ".$tipoRegistro." ".$component." ".$view." ".$token;

?>