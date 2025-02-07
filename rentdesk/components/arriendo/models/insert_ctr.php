<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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

$patronIMG = "/\.(jpg|png|jpeg|doc|docx|pdf)$/i";

function validarArchivo($archivo, $patronIMG)
{
    return preg_match($patronIMG, $archivo) ? true : false;
}

function subirArchivo($archivo, $destino)
{
    return move_uploaded_file($archivo["tmp_name"], $destino);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $razon = $_POST['razon'] ?? '';
    $monto = $_POST['monto'] ?? 0;
    $anio = $_POST['anio'] ?? date('Y');
    $mes = $_POST['mes'] ?? 1;
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $mes_imputado = date('Y-m-d', strtotime($anio . '-' . $mes . '-' . date('d')));
    $token = $_POST['token'];


    $fis_arch = $_FILES["archivo"]["name"] ?? '';
    $aleatorio = rand(9999, 99999999);
    $doc_ima_fisico = 'CargoArenta';

    // guarda el archivo
    if (!empty($token)) {


        $num_reg = 10;
        $inicio = 0;

        $query = "SELECT fa.id, fa.id_propiedad FROM propiedades.ficha_arriendo fa WHERE fa.token = '$token'";
        $cant_rows = $num_reg;
        $num_pagina = round($inicio / $cant_rows) + 1;
        $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
        $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
        $response = json_decode($resultado)[0];


        $id_propiedad = $response->id_propiedad;
        $id_ficha_arriendo = $response->id;

        $date = date('Y-m-d');
        $time = date('H:i:s');

        $insert = "INSERT INTO propiedades.ficha_arriendo_cta_cte_movimientos
            (id_propiedad, id_ficha_arriendo, fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, razon, nro_cuotas, editar, eliminar, mes_imputado)
            VALUES ($id_propiedad, $id_ficha_arriendo, '$fecha', '$time', 8, $monto, '$razon', 0, true, true, '$mes_imputado')";
        $dataCab = array("consulta" => $insert);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

        echo "true";
    }

    if (!empty($_FILES["archivo"]["name"])) {
        $fis_arch = $_FILES["archivo"]["name"];

        if (validarArchivo($fis_arch, $patronIMG)) {

            $extension = pathinfo($fis_arch, PATHINFO_EXTENSION);
            $doc_ima_fisico = "_cargoarenta_"  . date('Ymd_his') . $extension;
            $destino = "../../../upload/arriendo/" . $doc_ima_fisico;

            // Asegúrate de que el directorio de destino exista y tenga permisos de escritura
            if (!file_exists("../../../upload/arriendo/")) {
                mkdir("../../../upload/arriendo/", 0777, true);
            }

            // Subir archivo
            if (subirArchivo($_FILES["archivo"], $destino)) {
                echo "Archivo subido con éxito";
            } else {
                echo "Error al subir el archivo";
            }
        } else {
            echo "Archivo no válido";
        }
    } else {
        echo "No se ha seleccionado ningún archivo para subir";
    }
} else {
    echo "Método no permitido";
}
