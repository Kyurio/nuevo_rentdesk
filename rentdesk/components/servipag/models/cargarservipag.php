<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../app/model/QuerysBuilder.php");
include("../../../configuration.php");

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();
$config = new Config();

// Consulta para obtener las fechas contables registradas en la BD
$fechasContables = $QueryBuilder->selectAdvanced(
    'propiedades.servipag',     // Tabla
    'DISTINCT fecha_contab',    // Columnas (usamos DISTINCT para obtener valores únicos)
    [],                         // No hay JOINs
    [],                         // No hay condiciones
    '',                         // No se requiere GROUP BY
    'fecha_contab'              // ORDER BY
);

// Se convierte el resultado en un arreglo simple de fechas
$fechasRegistradas = array_column($fechasContables, 'fecha_contab');

$response = ["success" => false, "message" => ""];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];

    if (($handle = fopen($file, "r")) !== false) {

        // Leer la primera línea para validar la fecha contable
        $firstLine = fgets($handle);
        if ($firstLine === false) {
            $response["message"] = "El archivo está vacío.";
            echo json_encode($response);
            exit;
        }

        // Extraer la fecha contable de la primera línea (posición 174, longitud 8)
        // Ejemplo del valor extraído: "20250129"
        $fecha_contab_raw = trim(substr($firstLine, 174, 8));

        // Convertir el valor obtenido (formato YYYYMMDD) a formato YYYY-MM-DD
        $fechaContabObj = DateTime::createFromFormat("Ymd", $fecha_contab_raw);
        if (!$fechaContabObj) {
            $response["message"] = "Formato de fecha contable incorrecto en el archivo: " . $fecha_contab_raw;
            echo json_encode($response);
            exit;
        }
        $fecha_contab_formateada = $fechaContabObj->format("Y-m-d");

        // Validar si la fecha contable del archivo ya se encuentra en la BD
        if (in_array($fecha_contab_formateada, $fechasRegistradas)) {
            $response["message"] = "Este Pago proporcionado ya fue efectuado";
            echo json_encode($response);
            exit;
        }

        /* 
         * Si la validación pasa (la fecha no se encuentra registrada), se procede a procesar el archivo.
         * Dado que ya se leyó la primera línea para la validación, ésta también se procesa.
         */

        // Procesar el primer registro (ya leído)
        $data = [
            'canal_de_pago'   => (int)trim(substr($firstLine, 0, 3)),          // Canal de Pago (3)
            'oficina'         => (int)trim(substr($firstLine, 3, 3)),          // Oficina (3)
            'txcliente'       => trim(substr($firstLine, 6, 30)),              // TxCliente (30)
            'id_documento'    => trim(substr($firstLine, 36, 20)),             // Id_Documento (20)
            'boleta'          => trim(substr($firstLine, 56, 40)),             // Boleta (40)
            'rut_cliente'     => formatearRut(trim(substr($firstLine, 96, 11))), // Rut Cliente (11)
            'id_pago'         => (int)trim(substr($firstLine, 107, 12)),         // Id Pago (12)
            'monto'           => (float)trim(substr($firstLine, 119, 8)),        // Monto (8)
            'medio_pago'      => trim(substr($firstLine, 127, 2)),             // Medio Pago (2)
            'nro_serie_doc'   => trim(substr($firstLine, 129, 12)),            // Nro_serie_doc (12)
            'banco'           => trim(substr($firstLine, 141, 3)),             // Banco (3)
            'plaza_banco'     => trim(substr($firstLine, 144, 4)),             // Plaza_banco (4)
            'ctacte'          => trim(substr($firstLine, 148, 12)),            // Ctacte (12)
            'fecha_pago'      => trim(substr($firstLine, 160, 8)),             // Fecha Pago (8)
            'hora'            => trim(substr($firstLine, 168, 6)),             // Hora (6)
            'fecha_contab'    => trim(substr($firstLine, 174, 8)),             // Fecha Contab (8)
            'tipo_trx'        => trim(substr($firstLine, 182, 1)),             // Tipo Trx (1)
            'procesado'       => 0
        ];

        try {
            $result = $QueryBuilder->insert('propiedades.servipag', $data);
            if (!$result) {
                throw new Exception("Error al insertar el primer registro.");
            }
        } catch (Exception $e) {
            $response["message"] = $e->getMessage();
            echo json_encode($response);
            exit;
        }

        // Procesar el resto de las líneas del archivo
        while (($line = fgets($handle)) !== false) {
            $data = [
                'canal_de_pago'   => (int)trim(substr($line, 0, 3)),
                'oficina'         => (int)trim(substr($line, 3, 3)),
                'txcliente'       => trim(substr($line, 6, 30)),
                'id_documento'    => trim(substr($line, 36, 20)),
                'boleta'          => trim(substr($line, 56, 40)),
                'rut_cliente'     => formatearRut(trim(substr($line, 96, 11))),
                'id_pago'         => (int)trim(substr($line, 107, 12)),
                'monto'           => (float)trim(substr($line, 119, 8)),
                'medio_pago'      => trim(substr($line, 127, 2)),
                'nro_serie_doc'   => trim(substr($line, 129, 12)),
                'banco'           => trim(substr($line, 141, 3)),
                'plaza_banco'     => trim(substr($line, 144, 4)),
                'ctacte'          => trim(substr($line, 148, 12)),
                'fecha_pago'      => trim(substr($line, 160, 8)),
                'hora'            => trim(substr($line, 168, 6)),
                'fecha_contab'    => trim(substr($line, 174, 8)),
                'tipo_trx'        => trim(substr($line, 182, 1)),
                'procesado'       => 0
            ];

            try {
                $result = $QueryBuilder->insert('propiedades.servipag', $data);
                if (!$result) {
                    throw new Exception("Error al insertar datos.");
                }
            } catch (Exception $e) {
                $response["message"] = $e->getMessage();
                echo json_encode($response);
                exit;
            }
        }
        fclose($handle);
        $response["success"] = true;
        $response["message"] = "Archivo procesado con éxito.";
    } else {
        $response["message"] = "Error al abrir el archivo.";
    }
} else {
    $response["message"] = "No se recibió un archivo válido.";
}

echo json_encode($response);

/**
 * Función para formatear el RUT, agregando el guión antes del dígito verificador.
 *
 * @param string $rut
 * @return string
 */
function formatearRut($rut)
{
    $rut = preg_replace('/[^0-9kK]/', '', $rut);
    $cuerpo = ltrim(substr($rut, 0, -1), '0');
    $dv = substr($rut, -1);
    return $cuerpo . '-' . strtoupper($dv);
}
