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
$data = ["consulta" => $queryIdPropiedad];
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);
$objIdPropiedad = json_decode($resultado)[0] ?? null;

if (!$objIdPropiedad) {
    http_response_code(404);
    echo json_encode(["error" => "Propiedad no encontrada"]);
    exit;
}

$idPropiedad = $objIdPropiedad->id;
$consultas = [];

foreach ($_POST as $key => $value) {
    $key = rtrim($key, "|"); // 🔹 Normalizar clave

    if (strpos($key, "||") !== false) {
        $parts = explode("||", $key);
        $tipo = $parts[1]; // Usamos solo el tipo como validación

        echo "Procesando: ";
        var_dump($parts);
        echo "<br>";

        // 🔹 Actualización de propietarios (% de participación base)
        if ($tipo === "porc_part_base") {
            echo "✔ Actualizando porc_part_base<br>";
            $idPropietario = intval($parts[0]);
            $porcentaje = floatval($value);

            $consultas[] = "UPDATE propiedades.propiedad_copropietarios 
                            SET porcentaje_participacion_base = $porcentaje 
                            WHERE id_propiedad = $idPropiedad 
                            AND id_propietario = $idPropietario 
                            AND habilitado = true";
        } 

        // 🔹 Actualización de beneficiarios (% de participación)
        elseif ($tipo === "porc_part" && is_array($value)) {
            foreach ($value as $idBeneficiario => $porcentaje) {
                echo "✔ Actualizando porc_part - Beneficiario ID $idBeneficiario<br>";

                $idPropietario = intval($parts[0]);
                $idBeneficiario = intval($idBeneficiario);
                $porcentaje = floatval($porcentaje);

                $consultas[] = "UPDATE propiedades.persona_beneficiario 
                                SET porcentaje_participacion = $porcentaje 
                                WHERE id_propiedad = $idPropiedad 
                                AND id_propietario = $idPropietario 
                                AND id = $idBeneficiario 
                                AND habilitado = true";
            }
        } else {
            echo "⚠ No entró en ninguna condición, valores recibidos:<br>";
            var_dump($parts);
            echo "<br>";
        }
    }
}

// 🔹 Ejecutar todas las consultas SQL generadas
foreach ($consultas as $query) {
    echo "<br>Ejecutando consulta:<br>$query<br>";
    $services->sendPostDirecto($url_services . '/util/dml', ["consulta" => $query]);
}

// 🔹 Enviar respuesta final
echo json_encode(["mensaje" => "Porcentajes actualizados correctamente"]);
?>
