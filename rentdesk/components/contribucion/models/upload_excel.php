<?php
header('Content-Type: application/json');

require "../../../app/model/QuerysBuilder.php"; 
use app\database\QueryBuilder;

// Obtiene la entrada JSON
$input = json_decode(file_get_contents('php://input'), true);

// Verifica que se hayan recibido datos
if (isset($input['data'])) {
    $data = $input['data'];
    $queryBuilder = new QueryBuilder();

    try {
        // Inicia la transacción
        $queryBuilder->beginTransaction();

        // Primero, limpia la tabla antes de insertar nuevos datos
        $queryBuilder->truncate('propiedades.propiedad_contribuciones_temp');
        
        // Insertar datos en la tabla
        foreach ($data as $index => $row) {
            // Saltar la fila del encabezado
            if ($index === 0 || (isset($row[0]) && trim($row[0]) === 'Rol')) {
                continue;
            }

            // Validar que la fila tenga las columnas necesarias
            if (count($row) < 13) {
                throw new Exception("La fila {$index} no contiene suficientes columnas.");
            }

            // Mapear datos correctamente
            $contribucionData = [
                'id_propiedad'       => isset($row[9]) && is_numeric($row[9]) ? (int)$row[9] : 0,  // ID Propiedad
                'rol'                => isset($row[0]) ? substr(trim($row[0]), 0, 11) : '',         // Rol
                'fecha_contribucion' => isset($row[2]) && !empty($row[2]) ? date('Y-m-d', strtotime(trim($row[2]))) : null,
                'num_cuota'          => isset($row[1]) && is_numeric($row[1]) ? (int)trim($row[1]) : 0, // Número de cuota
                'valor_cuota'        => isset($row[11]) ? (float)str_replace(['$', '.', ','], ['', '', '.'], trim($row[11])) : 0,
                'fecha_pago'         => isset($row[6]) && !empty($row[6]) ? date('Y-m-d', strtotime(trim($row[6]))) : null,
                'monto_contrib'      => isset($row[12]) ? (float)str_replace(['$', '.', ','], ['', '', '.'], trim($row[12])) : 0,
                'mes_contrib'        => isset($row[10]) ? trim($row[10]) : '',                      // Mes de Contribución
                'ano_contrib'        => isset($row[7]) && is_numeric($row[7]) ? (int)trim($row[7]) : 0, // Año de Contribución
                'estado'             => isset($row[5]) ? substr(trim($row[3]), 0, 1) : ''          // Estado
            ];

            // Validar datos importantes
            if (empty($contribucionData['rol'])) {
                continue; // Saltar si no hay rol
            }

            // Validar la longitud del rol
            if (strlen($contribucionData['rol']) > 11) {
                throw new Exception("El valor del rol en la fila {$index} excede los 11 caracteres: " . $contribucionData['rol']);
            }

            // Insertar en la base de datos
            $queryBuilder->insert('propiedades.propiedad_contribuciones_temp', $contribucionData);
        }

        // Llamada a la función sin parámetros
        $resultado = $queryBuilder->executeFunction('propiedades.fn_actualiza_contribuciones', []);

        // Verificar si la función devolvió un mensaje de éxito
        if ($resultado && strpos($resultado, 'OK') !== false) {
            $queryBuilder->commit(); // Commit de la transacción
            echo json_encode(['success' => true, 'message' => 'Datos procesados correctamente.']);
        } else {
            throw new Exception("Error al ejecutar la función 'fn_actualiza_contribuciones'.");
        }
    } catch (Exception $e) {
        $queryBuilder->rollBack(); // Si hubo un error, deshacer cambios
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos.']);
}
