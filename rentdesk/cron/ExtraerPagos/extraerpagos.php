<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de la conexión intranet
$host_intranet = 'localhost';
$port_intranet = '5431'; // en el servidor es 5432 y localhost 5433
$dbname_intranet = 'postgres';
$user_intranet = 'arpis';
$password_intranet = 'arpis';

// Configuración de la conexión rentdesk
$host_rentdesk = 'localhost';
$port_rentdesk = '5431'; // en el servidor es 5432 y localhost 5433
$dbname_rentdesk = 'postgres';
$user_rentdesk = 'arpis';
$password_rentdesk = 'arpis';

try {
    // Conexión a la base de datos Intranet
    $conn_intranet = pg_connect("host=$host_intranet port=$port_intranet dbname=$dbname_intranet user=$user_intranet password=$password_intranet");

    // Conexión a la base de datos Rentdesk
    $conn_rentdesk = pg_connect("host=$host_rentdesk port=$port_rentdesk dbname=$dbname_rentdesk user=$user_rentdesk password=$password_rentdesk");

    // Verificar la conexión
    if (!$conn_intranet) {
        die(json_encode(['status' => 'error', 'message' => 'Error en la conexión Intranet: ' . pg_last_error()]));
    }
    if (!$conn_rentdesk) {
        die(json_encode(['status' => 'error', 'message' => 'Error en la conexión Rentdesk: ' . pg_last_error()]));
    }

    // Iniciar la transacción en la base de datos Rentdesk
    pg_query($conn_rentdesk, "BEGIN");

    // Consulta para obtener datos de la base de datos Intranet
    $checkQuery = "INSERT INTO propiedades.pago (fecha_pago, id_tipo_pago, monto, codigo_propiedad, rut_pagador, id_cierre, medio_pago)
                   SELECT cm.fecha_movimiento,
                          CASE cm.medio_pago 
                              WHEN 'SERVIPAG'      THEN 3
                              WHEN 'RentalPartner' THEN 2
                              WHEN 'BCI'           THEN 2
                              WHEN 'KHIPU'         THEN 3
                              WHEN 'OTROS_PAGOS'   THEN 3
                              WHEN 'Santander 2'   THEN 2
                              WHEN 'Santander 1'   THEN 2
                              WHEN 'SANTANDER'     THEN 2
                          END AS id_tipo_pago,
                          cm.monto,
                          cm.codigo_propiedad,
                          cm.rut_quien_pago AS rut_pagador,
                          cm.id_cierre,
                          cm.medio_pago
                   FROM arpis.con_movimiento cm
                   WHERE cm.id_cierre <> 0 
                   AND cm.id_cierre NOT IN (SELECT id_cierre FROM propiedades.pago)
                   AND cm.medio_pago NOT IN ('VariosAcreedores')";

    // Ejecutar la consulta en la conexión Intranet
    $checkResult = pg_query($conn_intranet, $checkQuery);

    if (!$checkResult) {
        throw new Exception('Error en la consulta Intranet: ' . pg_last_error($conn_intranet));
    }

    // Confirmar la transacción en la base de datos Rentdesk
    pg_query($conn_rentdesk, "COMMIT");
    echo json_encode(['status' => 'success', 'message' => 'Datos insertados correctamente.']);
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    pg_query($conn_rentdesk, "ROLLBACK");
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    // Cerrar las conexiones si son válidas
    if (is_resource($conn_intranet)) {
        pg_close($conn_intranet);
    }
    if (is_resource($conn_rentdesk)) {
        pg_close($conn_rentdesk);
    }
}
