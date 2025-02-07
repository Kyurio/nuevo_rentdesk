<?php

// inputs requeridos para el funcionamiento de la app
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

//configuracion de la conexion a la bd
$config     = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

// captura de datos post
$id = $_POST['id_garantia'];
$razon = $_POST['razonEditar'];
$monto = $_POST['montoEditar'];
$archivo = $_POST['archivo_bd'];
$moneda = $_POST['monedaEditar'];
$fecha = $_POST['fechaEditar'];

// funcionalidad del updte
try {
    if ($archivo) {
        $query = "UPDATE propiedades.garantia_movimientos
        SET fecha_movimiento='$fecha', razon='$razon', monto=$monto, archivo='$archivo'
        WHERE id = $id";
    } else {
        $query = "UPDATE propiedades.garantia_movimientos
        SET fecha_movimiento='$fecha', razon='$razon', monto=$monto
        WHERE id = $id";
    }

    $dataCab = array("consulta" => $query);
    $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

    if ($resultadoCab == true) {
        echo "1"; // Cambiado a "1"
    } else {
        echo "0"; // Cambiado a "0"
    }

} catch (\Throwable $th) {
    echo $th->getMessage(); // Ahora se muestra el mensaje de error
}

