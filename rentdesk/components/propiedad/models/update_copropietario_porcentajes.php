<?php

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
// $current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
// $current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

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

$num_reg = 10;
$inicio = 0;


/*BUSQUEDA USUARIO POR TOKEN ACTUAL */
$query = "SELECT id FROM propiedades.cuenta_usuario cu where token = '$id_usuario' ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objUsuarioId = json_decode($resultado)[0];

if (isset($_POST["token"])) {
    $token = $_POST["token"];

    $queryIdArriendo = "select p.id from propiedades.propiedad p where p.token = '$token' ";
    // var_dump($queryIdArriendo);

    $cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $queryIdArriendo, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    // var_dump($resultado);

    $objIdPropiedad = json_decode($resultado)[0];
}


// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the form data sent via POST
    $formData = $_POST;

    // Process the form data as needed
    // For example, you can access individual input values using their names
    foreach ($formData as $inputName => $inputValue) {
        if (strpos($inputName, '|')) {
            // Process each input value here

            $partsName = explode('|', $inputName);

            // Check if $partsName[3] (id_beneficiario) exists


            $idRegistroCondition = (isset($partsName[3]) && !empty($partsName[3])) ? "AND id_relacion=$partsName[4]" : "";


            if ($partsName[2] === 'porc_part_base') {
                $queryUpdateCoPropPorcentajeBase = "UPDATE propiedades.propiedad_copropietarios
                SET porcentaje_participacion_base=$inputValue
                WHERE id_propiedad=$objIdPropiedad->id 
                AND id_propietario=$partsName[0] 
                AND habilitado=true
                AND id_beneficiario=$partsName[3]";
   

                $dataCab = array("consulta" => $queryUpdateCoPropPorcentajeBase);
                $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
            }

            if ($partsName[2] === 'porc_part') {
                $queryUpdateCoPropPorcentaje = "UPDATE propiedades.propiedad_copropietarios
                SET porcentaje_participacion=$inputValue
                WHERE id_propiedad=$objIdPropiedad->id 
                AND id_propietario=$partsName[0] 
                AND habilitado=true
                AND id_beneficiario=$partsName[3]";
     
                $dataCab = array("consulta" => $queryUpdateCoPropPorcentaje);
                $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
            }
        }
    }


   

    // After processing, you can send a response back to the client if required
    echo  "Porcentajes Actualizados";
} else {
    // If the request method is not POST, handle the error accordingly
    http_response_code(405); // Method Not Allowed
    echo "Error: Method not allowed";
}