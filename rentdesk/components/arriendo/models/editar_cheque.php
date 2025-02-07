<?php

//bruno

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];


$id_cheque = @$_POST['idCheque'];
$monto = @$_POST['Cheque_Monto_Editar'];
$monto = str_replace(",", "", $monto);
$monto = str_replace(".", "", $monto);
$razon = @$_POST['Cheque_Razon_Editar'];
$banco = @$_POST['tipo_banco_editar'];
$fecha_cobro = @$_POST['Cheque_Fecha_Editar'];
$girador = @$_POST['Cheque_Girador_Editar'];
$numero_documento = @$_POST['Cheque_Numero_Doc_Editar'];
$ID_Cheque_Editar = @$_POST['ID_Cheque_Editar'];
$Comentario_Cheque = @$_POST['Comentario_Cheque_Editar'];

$queryUpdateCheque = "UPDATE propiedades.ficha_arriendo_cheques
SET monto = '$monto',
    razon  = '$razon',
    banco  = '$banco',
    fecha_cobro = '$fecha_cobro',
    girador = '$girador',
    numero_documento= '$numero_documento',
    comentario = '$Comentario_Cheque'
WHERE id = $ID_Cheque_Editar";

$dataCab = array("consulta" => $queryUpdateCheque);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

echo $resultadoCab;