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
$port_rentdesk = '5432'; // en el servidor es 5432 y localhost 5433
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

	$checkQuery = "SELECT vc.id_vou_cierre, 
						vv.fecha, 
						vv.id_voucher, 
						vv.nombre_usuario, 
						vv.codigo_propiedad,
						vv.tipo, vv.direccion,
						vv.comuna,
						vv.region,
						vv.observacion,
						vv.id_propiedad,
						vv.monto,
						vv.glosa,
						vv.doc_pagado, 
						vv.num_cuotas,
						vv.tipo_voucher, 
						vvd.id_voucher_detalle,
						vvd.numero_cliente, 
						vvd.descripcion,
						vvd.valor, 
						vvd.cuenta_proveedor,
						p.rol,
						vmp.docto_default, 
						vcc.cta_banco, 
						vcc.codigo, 
						vcc.tipo as tipo_cta
						FROM 
						arpis.vou_cierre vc,
						arpis.vou_voucher vv,
						arpis.vou_voucher_detalle vvd,
						arpis.propiedad p,
						arpis.vou_med_pago vmp,
						arpis.vou_cta_contable vcc
						WHERE vc.id_vou_cierre  = vv.id_vou_cierre 
						AND vv.id_voucher  = vvd.id_voucher 
						AND vmp.id_vou_med_pago  = vvd.medio_pago 
						AND vcc.id_vou_cta_contable  = vvd.ctacontable 
						AND vc.fecha >= CURRENT_TIMESTAMP - INTERVAL '365 days' 
						AND p.codigo_propiedad = vv.codigo_propiedad 
						ORDER BY vc.id_vou_cierre  DESC";

	$checkResult = pg_query($conn_intranet, $checkQuery);

	if ($checkResult) {
		$rows = pg_fetch_all($checkResult);
	} else {
		throw new Exception("Error al ejecutar la consulta: " . pg_last_error($conn_intranet));
	}

	// Verificar si hay resultados
	if (!empty($rows)) {
		foreach ($rows as $row) {


			// Preparar la consulta SQL para verificar si ya existe
			$query_existe = "SELECT 'si' as existe FROM propiedades.accion_varios_acreedores WHERE id_vou_cierre = $1 AND id_voucher = $2 AND id_voucher_detalle = $3";
			$result_existe = pg_query_params($conn_rentdesk, $query_existe, array($row['id_vou_cierre'], $row['id_voucher'], $row['id_voucher_detalle']));

			if (pg_num_rows($result_existe) > 0) {
				echo "Ya existe id_vou_cierre : " . $row['id_vou_cierre'] . ", id_voucher : " . $row['id_voucher'] . ", id_voucher_detalle:  " . $row['id_voucher_detalle'] . "<br>";
			} else {


				$query_insert = "INSERT INTO propiedades.accion_varios_acreedores 
					(tipo, razon, monto, habilitado, id_vou_cierre, fecha_cierre, id_voucher, nombre_usuario, codigo_propiedad, direccion, comuna, region, observacion, 
					id_propiedad_intranet, doc_pagado, num_cuotas, tipo_voucher, id_voucher_detalle, numero_cliente, descripcion, valor, cuenta_proveedor, 
					id_rol_propiedad, cta_default, cta_contable, cta_contable_old, tipo_cta) 
					VALUES($1, $2, $3, true, $4, $5::timestamp, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16, $17, $18, $19, $20, $21, $22, $23, $24, $25, $26)";

				$fecha_formateada = date('Y-m-d H:i:s', strtotime($row['fecha']));


				$params = array(
					$row['tipo'], $row['glosa'], $row['monto'], $row['id_vou_cierre'],
					$fecha_formateada, $row['id_voucher'], $row['nombre_usuario'],
					$row['codigo_propiedad'], $row['direccion'], $row['comuna'],
					$row['region'], $row['observacion'], $row['id_propiedad'],
					$row['doc_pagado'], $row['num_cuotas'], $row['tipo_voucher'],
					$row['id_voucher_detalle'], $row['numero_cliente'], $row['descripcion'],
					$row['valor'], $row['cuenta_proveedor'], $row['rol'], $row['docto_default'],
					$row['cta_banco'], $row['codigo'], $row['tipo_cta']
				);




				$result_insert = pg_query_params($conn_rentdesk, $query_insert, $params);


				if (!$result_insert) {
					throw new Exception('Error en la ejecución de la consulta: ' . pg_last_error($conn_rentdesk));
				} else {
					echo json_encode(['status' => 'success', 'message' => 'Datos insertados correctamente.<br>']);
				}
			}
		}
	}

	// Se inserta en tabla ficha_arriendo_cta_cte_movimientos
	$checkQueryRentdesk = "SELECT valor, razon, id, cta_contable
								FROM propiedades.accion_varios_acreedores a
								WHERE NOT EXISTS (
								SELECT 1
								FROM propiedades.ficha_arriendo_cta_cte_movimientos x
								WHERE x.id_varios_acreedores = a.id)";

	$checkRentdesk = pg_query($conn_rentdesk, $checkQueryRentdesk);

	if ($checkRentdesk) {
		$rows2 = pg_fetch_all($checkRentdesk);
	} else {
		throw new Exception("Error al ejecutar la consulta accion_varios_acreedores: " . pg_last_error($conn_rentdesk));
	}

	// Verificar si hay resultados
	if (!empty($rows2)) {
		foreach ($rows2 as $row) {

			// Preparar la consulta SQL para insertar en ficha_arriendo_cta_cte_movimientos
			$query_insert = "INSERT INTO propiedades.ficha_arriendo_cta_cte_movimientos 
				(fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, saldo, razon, cobro_comision, nro_cuotas, pago_arriendo, id_varios_acreedores)
				VALUES(now(), now()::time without time zone, 3, $1, 0, $2, false, 0, false, $3)";

			$params = array($row['valor'], $row['razon'], $row['id']);
			$result_insert = pg_query_params($conn_rentdesk, $query_insert, $params);

			// if (!$result_insert) {
			// 	echo json_encode(['status' => 'error', 'message' => 'Error en la ejecución de la consulta: ' . pg_last_error()]);
			// } else {
			// 	echo json_encode(['status' => 'success', 'message' => 'Datos insertados correctamente.']);
			// }

			if (!$result_insert) {
				throw new Exception('Error en la ejecución de la consulta: ' . pg_last_error($conn_rentdesk));
			}
		}
	}

	// Confirmar la transacción
	pg_query($conn_rentdesk, "COMMIT");
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
