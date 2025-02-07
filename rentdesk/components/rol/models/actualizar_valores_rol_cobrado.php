<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services  = new ServicesRestful;
$url_services = $config->url_services;


var_dump($_POST);

$idEdit = isset($_POST['id']) ? $_POST['id'] : null;
$cobrado =isset($_POST['cobrado']) ? ($_POST['cobrado'] === 'true' ? 1 : 0) : null;

// Asegurarse de que los valores estén capturados correctamente
if (isset($idEdit) && isset($cobrado)) {
//     // Escapar los valores para evitar inyecciones SQL

    $query = "UPDATE propiedades.valores_roles SET cobrado = '$cobrado' WHERE id= $idEdit";
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

