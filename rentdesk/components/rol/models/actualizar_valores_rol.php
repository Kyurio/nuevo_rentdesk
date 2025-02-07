<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services  = new ServicesRestful;
$url_services = $config->url_services;


$idEdit = $_POST['idEdit'];
$valorRolAño = $_POST['valorRolAñoEdit'];
$ValorRol = $_POST['ValorRolEdit'];
$mes = $_POST['mesEdit'];

// Asegurarse de que los valores estén capturados correctamente
 if (isset($valorRolAño) && isset($ValorRol) && isset($mes)) {
//     // Escapar los valores para evitar inyecciones SQL

    $query = "UPDATE propiedades.valores_roles SET año='$valorRolAño', valor='$ValorRol' , cuota='$mes' WHERE id= $idEdit";
    $data = array("consulta" => $query);
    $result = $services->sendPostDirecto($url_services . '/util/dml', $data);

    if ($result != "OK") {
        echo "false";
        return;
    }

    echo "Exito";


} else {
    echo "Faltan valores en el POST.";
}

