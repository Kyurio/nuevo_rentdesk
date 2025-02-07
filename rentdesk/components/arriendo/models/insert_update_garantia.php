<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$config = new Config;
$services = new ServicesRestful; 
$url_services = $config->url_services;
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_usuario = $_SESSION["rd_usuario_id"];

$hoy = date("Y-m-d");
$carpeta = "upload/garantias";


// con un if else verifica  si es abono o descuento 
$token = $_POST['token'];

$modalGarantiatipoMovimiento = $_POST['tipo_movimiento'];
$modalGarantiaRazon = $_POST['modalGarantiaRazonAbono'];
$modalMontoGarantia = $_POST['modalMontoGarantiaAbono'];
$modalMonedaGarantia = $_POST['modalMonedaGarantiaAbono'];
$modalGarantiaPagado = $_POST['modalGarantiaPagadoAbono'];
$modalGarantiaFecha = $_POST['modalGarantiaFechaAbono'];


$modalMontoGarantia = str_replace(".", "", $modalMontoGarantia);
$estado = ($modalGarantiaPagado == "Si") ? "PAGADO" : "NO PAGADO";

$query = "SELECT id, id_propiedad FROM propiedades.ficha_arriendo fa WHERE token = '$token'";

$data = array("consulta" => $query);
$resultado = $services->sendPostDirecto($url_services . '/util/objeto', $data);

$result_json = json_decode($resultado);
if ($result_json) {
	foreach ($result_json as $result) {
		$id_arriendo = $result->id;
		$id_propiedad = $result->id_propiedad;
	}
}

// Subida de archivos
$patronIMG = "%\.(jpg|jpeg|png|doc|docx|xls|xlsx)$%i";
$fis_arch = $_FILES["archivoDG"]["name"];
$doc_ima_fisico = null;

if ($fis_arch != "") {
	if (preg_match($patronIMG, $fis_arch)) {
		$aleatorio = rand(9999, 99999999);
		$doc_ima_fisico = date('Ymd_his') . "_garantia_$aleatorio." . pathinfo($fis_arch, PATHINFO_EXTENSION);
		if (!move_uploaded_file($_FILES["archivoDG"]["tmp_name"], "../../../$carpeta/" . $doc_ima_fisico)) {
			die("Error al subir el archivo.");
		}
	} else {
		die("Archivo no válido.");
	}
}

$token_nuevo = md5(date("Y-m-d-h-i-s") . rand(9999, 99999999) . $modalMontoGarantia);

if ($doc_ima_fisico) {

	$query = "INSERT INTO propiedades.garantia_movimientos (id_arriendo, id_propiedad, fecha_movimiento, razon, monto, moneda, archivo, estado, token, tipo_movimiento)
	VALUES('$id_arriendo','$id_propiedad','$hoy','$modalGarantiaRazon','$modalMontoGarantia', '$modalMonedaGarantia','$doc_ima_fisico', '$estado', '$token_nuevo', 0)";
} else {

	$query = "INSERT INTO propiedades.garantia_movimientos (id_arriendo, id_propiedad, fecha_movimiento, razon, monto, moneda, estado, token, tipo_movimiento)
	VALUES('$id_arriendo','$id_propiedad','$hoy','$modalGarantiaRazon','$modalMontoGarantia', '$modalMonedaGarantia', '$estado', '$token_nuevo', 0)";
}
$data = array("consulta" => $query);
$resultado = $services->sendPostDirecto($url_services . '/util/dml', $data);

echo $query;

echo "";

if ($resultado) {
	echo "OK";
} else {
	echo "Error en la inserción: " . $resultado;
}
