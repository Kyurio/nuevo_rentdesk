<?php
session_start();
include("../../../includes/sql_inyection_salto_textarea.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$codigo = $_POST['codigo'];
$tipo = $_POST['tipo'];

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$current_sucursal = unserialize($_SESSION["rd_current_sucursal"]);

$config = new Config;
$services = new ServicesRestful;
$url_services = $config->url_services;

$respuesta = 0;
$html = "";

$num_reg = 10; // No se usa en este script pero lo dejo por si lo necesitas.
$inicio = 0;

// Consulta optimizada
$queryProp = "SELECT dni, upper(nombre_1) AS nombre, upper(nombre_2) AS apellido_paterno, upper(nombre_3) AS apellido_materno from propiedades.vis_propietarios WHERE 
    nombre_1 ILIKE '%$codigo%'
    OR nombre_2 ILIKE '%$codigo%'
	OR nombre_3 ILIKE '%$codigo%'
    OR dni ILIKE '%$codigo%'
    LIMIT 20
";

$dataRegPagos = array("consulta" => $queryProp);
$resultadoRegPagos = $services->sendPostDirecto($url_services . '/util/objeto', $dataRegPagos);
$result_jsonRegPagos = json_decode($resultadoRegPagos);

if ($result_jsonRegPagos) {
    foreach ($result_jsonRegPagos as $key => $result) {
        $respuesta = 1; 
        $dni = @$result->dni;

        // Renderización única para personas naturales y jurídicas
        $html .= '<div>
            <a style="color:#2c699e;" class="suggest-element" id="' . $dni . '" name="' . $dni . '">
            ' . $dni . ' || ' . $result->nombre . 
            (isset($result->apellido_paterno) ? ' ' . $result->apellido_paterno : '') .
            (isset($result->apellido_materno) ? ' ' . $result->apellido_materno : '') .
            '</a>
        </div>';
    }
}

echo $html;
?>
