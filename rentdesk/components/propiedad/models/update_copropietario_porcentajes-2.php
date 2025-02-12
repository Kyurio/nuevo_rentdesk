<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config      = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_usuario = $_SESSION["rd_usuario_id"];

// Validar si se recibe un token
if (!isset($_POST["token"])) {
    http_response_code(400);
    echo json_encode(["error" => "Token faltante"]);
    exit;
}

$token = $_POST["token"];


// Obtener ID de propiedad desde el token
$queryIdPropiedad = "SELECT id FROM propiedades.propiedad WHERE token = '$token'";
$data = array("consulta" => $queryIdPropiedad);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);
$objIdPropiedad = json_decode($resultado)[0] ?? null;

if (!$objIdPropiedad) {
    http_response_code(404);
    echo json_encode(["error" => "Propiedad no encontrada"]);
    exit;
}

$idPropiedad = $objIdPropiedad->id;

// Array para almacenar las consultas correctamente
$consultas = [];

// foreach ($_POST as $key => $value) {
//     // 🔹 Normalizar clave
//     $key = rtrim($key, "|");

//     if (strpos($key, "||") !== false) {
//         $parts = explode("||", $key);   

//         if (count($parts) === 3 && $parts[1] === "porc_part_base") {
//             // Actualización de porcentaje de participación base (copropietario)
//             $idPropietario = intval($parts[0]);
//             $porcentaje = floatval($value);

//             $consultas[] = "UPDATE propiedades.propiedad_copropietarios 
//                             SET porcentaje_participacion_base = $porcentaje 
//                             WHERE id_propiedad = $idPropiedad 
//                             AND id_propietario = $idPropietario 
//                             AND habilitado = true";
   
        
//         } elseif (count($parts) === 4 && $parts[1] === "porc_part") {

//             // Actualización de porcentaje de participación de beneficiario
//             $idPropietario = intval($parts[0]);
//             $idBeneficiario = intval($parts[2]);
//             $porcentaje = floatval($value);

//             $consultas[] = "UPDATE propiedades.propiedad_copropietarios 
//                             SET porcentaje_participacion = $porcentaje 
//                             WHERE id_propiedad = $idPropiedad 
//                             AND id_propietario = $idPropietario 
//                             AND id_beneficiario = $idBeneficiario 
//                             AND habilitado = true";
//         }
//     }
// }

foreach ($_POST as $key => $value) {
    // 🔹 Normalizar clave
    $key = rtrim($key, "|");

    if (strpos($key, "||") !== false) {
        $parts = explode("||", $key);



        if (count($parts) === 3 && $parts[1] === "porc_part_base") {
            echo "✔ Entró en porc_part_base<br>";
            // Actualización de porcentaje de participación base (copropietario)
            $idPropietario = intval($parts[0]);
            $porcentaje = floatval($value);

            $consultas[] = "UPDATE propiedades.propiedad_copropietarios 
                            SET porcentaje_participacion_base = $porcentaje 
                            WHERE id_propiedad = $idPropiedad 
                            AND id_propietario = $idPropietario 
                            AND habilitado = true";
   
        } elseif (count($parts) === 4 && $parts[1] === "porc_part") {
            echo "✔ Entró en porc_part (else if)<br>";
            // Actualización de porcentaje de participación de beneficiario
            $idPropietario = intval($parts[0]);
            $idBeneficiario = intval($parts[2]);
            $porcentaje = floatval($value);

            $consultas[] = "UPDATE propiedades.propiedad_copropietarios 
                            SET porcentaje_participacion = $porcentaje 
                            WHERE id_propiedad = $idPropiedad 
                            AND id_propietario = $idPropietario 
                            AND id_beneficiario = $idBeneficiario 
                            AND habilitado = true";
        } else {
            echo "⚠ No entró en ningún if, valores recibidos:<br>";
            var_dump($parts);
            echo "<br>";
        }
    }
}



// Ejecutar todas las consultas una por una
foreach ($consultas as $query) {

    echo "<br>";
    echo $query;
    echo "<br>";

    $services->sendPostDirecto($url_services . '/util/dml', ["consulta" => $query]);
}

// Enviar respuesta de éxito
echo json_encode(["mensaje" => "Porcentajes actualizados correctamente"]);
