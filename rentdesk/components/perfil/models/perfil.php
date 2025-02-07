<?php
@include("../../includes/sql_inyection.php");


$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
$current_empresa = unserialize($_SESSION["sesion_rd_empresa"]);




// $config		= new Config;
// $services   = new ServicesRestful;
// $url_services = $config->url_services;

// $id_company = $_SESSION["rd_company_id"];
// $id_usuario = $_SESSION["rd_usuario_id"];


// $data = array("idUsuario" => $id_usuario, "idEmpresa" => $id_company);
// $resultado = $services->sendPostNoToken($url_services . '/usuario/perfil', $data);


// if ($resultado) {
// 	$result_json = json_decode($resultado);
// 	foreach ($result_json as $result_r) {
// 		$result = $result_r;
// 	} //foreach($result_json as $result)
// }


$foto = "";
if (@$result->foto != "") {
	$foto = "$result->foto";
} else {
	$foto = "no-foto.png";
}
