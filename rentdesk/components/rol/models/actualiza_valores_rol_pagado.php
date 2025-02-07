<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services  = new ServicesRestful;
$url_services = $config->url_services;

$idEdit = $_POST["id"];//isset($_POST['id']) ? $_POST['id'] : null;
$pagado = isset($_POST['pagado']) ? ($_POST['pagado'] === 'true' ? 1 : 0) : null;


//Asegurarse de que los valores estén capturados correctamente
if (isset($idEdit) && isset($pagado)) {
//     // Escapar los valores para evitar inyecciones SQL

    $query = "UPDATE propiedades.valores_roles SET pagado = '$pagado' WHERE id= $idEdit";
    $data = array("consulta" => $query);
    var_dump($data);
    $result = $services->sendPostDirecto($url_services . '/util/dml', $data);

    if ($result != "OK") {
        echo "false";
        return;
    }

    echo "true";
} else {
    echo "Faltan valores en el POST.";
}
