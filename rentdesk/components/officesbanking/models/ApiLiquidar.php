<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

use app\database\QueryBuilder;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Round;


// Inclusión de dependencias y configuraciones básicas
require '../../../includes/fpdf/fpdf.php';
require '../../../includes/fpdf/morepagestable.php';
require 'PDF_MC_Table.php';
session_start();
include "../../../includes/sql_inyection.php";
include "../../../configuration.php";
include "../../../includes/funciones.php";
include "../../../includes/services_util.php";
require "../../../includes/re-code/phpqrcode/phpqrcode/phpqrcode.php";
require "../../../app/model/QuerysBuilder.php";

//configuracion conexion bd
$QueryBuilder = new QueryBuilder();

// Configuración de la aplicación
$config = new Config;
$services = new ServicesRestful;
$url_services = $config->url_services;
date_default_timezone_set('America/Santiago');



// Función para obtener el valor de la UF
function obtenerValorUF($services, $url_services, $fechaActual)
{
    $fechaUF = $fechaActual->format('Y-m-d');
    $sql_valor_uf = "SELECT * FROM propiedades.indicadores WHERE indicador = 'uf' AND fecha = '$fechaUF'";
    $data = array("consulta" => $sql_valor_uf, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    $objUF = json_decode($resultado);
    return $objUF[0]->valor;
}

// Función para obtener el IVA de la subsidiaria
function obtenerIVA($services, $url_services, $id_subsidiaria)
{
    $queryConfig = "SELECT * FROM propiedades.tp_configuracion_subsidiaria WHERE id_subisidiaria = " . $id_subsidiaria;
    $data = array("consulta" => $queryConfig, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    $objConfig = json_decode($resultado);
    return $objConfig[0]->iva;
}

// Función para obtener información de la propiedad
function obtenerInfoPropiedad($services, $url_services, $ficha_tecnica_propiedad)
{
    $queryPropiedad = "SELECT p.direccion AS direccion, p.numero AS numero, p.numero_depto, p.piso,
        tc.nombre AS comuna, tr.nombre AS region, tp.nombre AS pais, p.codigo_propiedad AS codigo_prop, p.id AS id
        FROM propiedades.propiedad p
        INNER JOIN propiedades.propiedad_copropietarios pc ON pc.id_propiedad = p.id
        INNER JOIN propiedades.tp_comuna tc ON tc.id = p.id_comuna
        INNER JOIN propiedades.tp_region tr ON tr.id = tc.id_region
        INNER JOIN propiedades.tp_pais tp ON tp.id = tr.id_pais
        WHERE nivel_propietario = 1 AND p.id = " . $ficha_tecnica_propiedad;
    $data = array("consulta" => $queryPropiedad, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    return json_decode($resultado);
}

function obtenerDatosPropietario($services, $url_services, $ficha_tecnica_propiedad)
{
    $queryPropiedad = "SELECT p.direccion AS direccion, p.numero AS numero, p.numero_depto, p.piso,
    tc.nombre AS comuna, tr.nombre AS region, tp.nombre AS pais, p.codigo_propiedad AS codigo_prop, p.id AS id
    FROM propiedades.propiedad p
    INNER JOIN propiedades.propiedad_copropietarios pc ON pc.id_propiedad = p.id
    INNER JOIN propiedades.tp_comuna tc ON tc.id = p.id_comuna
    INNER JOIN propiedades.tp_region tr ON tr.id = tc.id_region
    INNER JOIN propiedades.tp_pais tp ON tp.id = tr.id_pais
    WHERE nivel_propietario = 1 AND p.id = " . $ficha_tecnica_propiedad;
    $data = array("consulta" => $queryPropiedad, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    return json_decode($resultado);
}

// Función para obtener información del arrendatario
function obtenerInfoArrendatario($services, $url_services, $ficha_tecnica_propiedad)
{
    $queryArrendatario = "SELECT va.nombre_1, va.nombre_2, va.nombre_3, fa.id, num_cuotas_garantia
        FROM propiedades.propiedad p
        INNER JOIN propiedades.ficha_arriendo fa ON p.id = fa.id_propiedad
        LEFT JOIN propiedades.ficha_arriendo_arrendadores faa ON faa.id_ficha_arriendo = fa.id
        LEFT JOIN propiedades.vis_arrendatarios va ON va.id = faa.id_arrendatario
        WHERE p.id = $ficha_tecnica_propiedad AND fa.id_estado_contrato = 1";
    $data = array("consulta" => $queryArrendatario, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    return json_decode($resultado);
}

// Función para obtener la comisión de la propiedad
function ObtenerFichaArriendo($services, $url_services, $id_ficha_arriendo)
{
    $queryComision = "SELECT * FROM propiedades.ficha_arriendo WHERE id = $id_ficha_arriendo";
    $data = array("consulta" => $queryComision, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    return json_decode($resultado);
}

// Función para obtener los movimientos financieros
function obtenerMovimientos($services, $url_services, $ficha_tecnica_propiedad)
{
    $queryMovimientos = "SELECT propiedades.fn_propiedades_por_liquidar($ficha_tecnica_propiedad)";

    $data = array("consulta" => $queryMovimientos, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    return json_decode($resultado);
}

// funcion para obtener el orden de prioridades
function obteneOrdenPrioridades($services, $url_services)
{
    $queryComision = "SELECT * FROM propiedades.calculo_prioridad_liquidacion";
    $data = array("consulta" => $queryComision, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    return json_decode($resultado);
}

function ObtenerCuotasPorPagarGarantia($services, $url_services, $id_ficha_arriendo)
{

    $queryComision = "SELECT count(id) AS CuotasNoPagas
                    FROM propiedades.ficha_arriendo_cuotas_garantia
                    WHERE id_ficha_arriendo = $id_ficha_arriendo
                    AND (estado_garantia IS NULL OR estado_garantia = '')
                    AND (
                        (garantia_ano < EXTRACT(YEAR FROM CURRENT_DATE)) 
                        OR 
                        (garantia_ano = EXTRACT(YEAR FROM CURRENT_DATE) 
                        AND mes_garantia <= EXTRACT(MONTH FROM CURRENT_DATE))
                    )";
    $data = array("consulta" => $queryComision, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    return json_decode($resultado);
}

function PagarCuotaGarantia($services, $url_services, $idCuota)
{

    $sql_update_cuota = "UPDATE  propiedades.ficha_arriendo_cuotas_garantia  SET estado_garantia = 'PAGADO' WHERE id  = $idCuota";
    $data = array("consulta" => $sql_update_cuota);
    $resultado = $services->sendPostDirecto($url_services . '/util/dml', $data);
    return json_decode($resultado);
}

function ObtenerIdCuotaGarantia($services, $url_services, $id_ficha_arriendo)
{

    $queryPorcentajeParticipacion = "SELECT * FROM propiedades.ficha_arriendo_cuotas_garantia WHERE id_ficha_arriendo = $id_ficha_arriendo";

    $data = array("consulta" => $queryPorcentajeParticipacion, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    return json_decode($resultado);
}

function ObtenerPorcentajeParticipacion($services, $url_services, $ficha_tecnica_propiedad)
{
    $queryPorcentajeParticipacion = "SELECT * FROM propiedades.propiedad_copropietarios WHERE id_propiedad = $ficha_tecnica_propiedad AND nivel_propietario = 1";

    $data = array("consulta" => $queryPorcentajeParticipacion, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    return json_decode($resultado);
}

function ObtenerPropietario($services, $url_services, $ficha_tecnica_propiedad)
{

    $queryPorcentajeParticipacion = "SELECT pc.porcentaje_participacion_base, vp.nombre_1 ,vp.nombre_2 ,vp.nombre_3, pc.id_propietario  FROM propiedades.propiedad p
                                    INNER JOIN propiedades.propiedad_copropietarios pc ON p.id =pc.id_propiedad  AND pc.habilitado = true
                                    INNER JOIN propiedades.persona_propietario pp ON pp.id_persona = pc.id_propietario
                                    INNER JOIN propiedades.vis_propietarios vp ON vp.id = pp.id_persona WHERE nivel_propietario= 1 AND p.id= $ficha_tecnica_propiedad";

    $data = array("consulta" => $queryPorcentajeParticipacion, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    return json_decode($resultado);
}

function ObtenerCantidadLiquidaciones($services, $url_services, $id_ficha_arriendo)
{

    $query_count = "SELECT count(id) AS cantidad_liquidaciones FROM propiedades.propiedad_liquidaciones where id_ficha_arriendo = $id_ficha_arriendo";
    $data = array("consulta" => $query_count, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);

    return json_decode($resultado);
}

function ObtenerUltimoIDLiquidacion($services, $url_services)
{

    $query = "SELECT (last_value + 1) as id_liquidacion FROM propiedades.ficha_propiedad_liquidaciones_id_seq";
    $data = array("consulta" => $query, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);

    return json_decode($resultado);
}

function ActualizarEstadosCuentasCorriente($services, $url_services, $ficha_tecnica_propiedad)
{

    $query = "UPDATE propiedades.ficha_arriendo_cta_cte_movimientos SET estado = 'L' WHERE estado = 'I' AND id_propiedad = $ficha_tecnica_propiedad";
    $dataCab = array("consulta" => $query);
    $resultado = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

    return json_decode($resultado);
}

function ActualizarIDMovimientosCtaCte($services, $url_services, $NumeroCierre, $id_ficha_arriendo, $ficha_tecnica_propiedad)
{
    // $query = "UPDATE propiedades.ficha_arriendo_cta_cte_movimientos SET id_liquidacion = $id_liquidacion  WHERE  (id_liquidacion is null OR id_liquidacion = 0) AND id_propiedad = $ficha_tecnica_propiedad";
    $query = "UPDATE propiedades.ficha_arriendo_cta_cte_movimientos SET id_liquidacion = $NumeroCierre  WHERE  id_ficha_arriendo = $id_ficha_arriendo AND id_propiedad = $ficha_tecnica_propiedad AND estado = 'I' ";
    $dataCab = array("consulta" => $query);
    $resultado = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
    return json_decode($resultado);
}

function ActualizarLiquidacionCierre($services, $url_services, $NumeroCierre, $ficha_tecnica_propiedad)
{

    $query = "UPDATE propiedades.propiedad_liquidaciones SET cierre = $NumeroCierre WHERE id_ficha_propiedad = $ficha_tecnica_propiedad";
    $dataCab = array("consulta" => $query);
    $resultado = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
    return json_decode($resultado);
}

function GuardarMovimientosCtaCte($services, $url_services, $id_ficha_arriendo, $fecha_movimiento, $hora_movimiento, $id_tipo_movimiento_cta_cte, $monto, $saldo, $razon, $cobro_comision, $nro_cuotas, $id_propiedad, $pago_arriendo, $id_varios_acreedores, $cta_contable, $id_liquidacion, $editar, $eliminar, $mes_imputado, $codigo_propiedad, $id_cierre_conciliacion, $id_responsable, $estado)
{

    // Consulta preparada
    $query = "INSERT INTO propiedades.ficha_arriendo_cta_cte_movimientos(
            id_ficha_arriendo, fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, saldo, 
            razon, cobro_comision, nro_cuotas, id_propiedad, pago_arriendo, id_varios_acreedores, cta_contable, 
            id_liquidacion, editar, eliminar, mes_imputado, codigo_propiedad, id_cierre_conciliacion, id_responsable, estado
        ) VALUES ($id_ficha_arriendo, '$fecha_movimiento', '$hora_movimiento', $id_tipo_movimiento_cta_cte, $monto, $saldo, 
            '$razon', $cobro_comision, $nro_cuotas, $id_propiedad, $pago_arriendo, $id_varios_acreedores, (select cuentaco from propiedades.tp_tipo_movimiento_cta_cte where id = $id_tipo_movimiento_cta_cte), 
            $id_liquidacion, $editar, $eliminar, '$mes_imputado', '$codigo_propiedad', $id_cierre_conciliacion, $id_responsable, '$estado')";
    // Preparar datos para el envío del servicio
    $data = array("consulta" => $query, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostDirecto($url_services . '/util/dml', $data, []);

    return json_decode($resultado);
}

function GuardarLiquidacion($services, $url_services, $id_ficha_propiedad, $id_propietario, $monto, $fecha_liquidacion, $id_ficha_arriendo, $url_liquidacion, $comision, $iva, $abonos, $descuentos, $total, $estado, $cierre, $participacion)
{

    $query = "INSERT INTO propiedades.propiedad_liquidaciones(id_ficha_propiedad, id_propietario, monto, fecha_liquidacion, id_ficha_arriendo, url_liquidacion, comision, iva, abonos, descuentos, total, estado, cierre, porcentaje_participacion)
    VALUES 
    ($id_ficha_propiedad, $id_propietario, $monto, '$fecha_liquidacion', $id_ficha_arriendo, '$url_liquidacion', $comision, $iva, $abonos,$descuentos, $total, $estado, $cierre, $participacion)";
    $data = array("consulta" => $query);
    $resultado = $services->sendPostDirecto($url_services . '/util/dml', $data);
    return json_decode($resultado);
}

function GuardarDetalleLiquidacion($services, $url_services, $id_propiedad, $tipo_comision, $monto, $iva, $id_liquidacion)
{


    $query = "INSERT INTO propiedades.propiedad_comision_liquidacion(id_propiedad_liquidacion, tipo_comision, monto, iva, id_liquidacion)
	VALUES ($id_propiedad, '$tipo_comision', $monto, $iva, $id_liquidacion)";
    $data = array("consulta" => $query);
    $resultado = $services->sendPostDirecto($url_services . '/util/dml', $data);

    return json_decode($resultado);
}

function ObtenerValorGarantias($QueryBuilder, $id_ficha_arriendo)
{

    // Definimos las condiciones de la consulta
    $table = 'propiedades.ficha_arriendo_cuotas_garantia';
    $columns = 'monto_garantia';
    $conditions = [
        'id_ficha_arriendo' => $id_ficha_arriendo
    ];

    // Añadimos la condición adicional de que 'estado_garantia' no sea NULL
    $extraConditions = "estado_garantia IS NOT NULL";

    // Ejecutamos la consulta
    $resultado = $QueryBuilder->selectAdvanced(
        $table,
        $columns,
        [],  // No hay JOINs
        $conditions,
        '',  // No hay GROUP BY
        '',  // No hay ORDER BY
        null,  // Sin límite
        false // No es una consulta COUNT
    );

    // Mostrar el resultado
    return $resultado;
}




/**
 * 
 * 
 *  ejeccion de funciones de pgsql -
 * 
 * 
 */


function CobrarAnticipos($services, $url_services, $ficha_tecnica_propiedad, $monto_disponible): mixed
{

    $queryMovimientos = "SELECT propiedades.fn_cobra_anticipo($ficha_tecnica_propiedad, $monto_disponible)";
    $data = array("consulta" => $queryMovimientos, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
    return json_decode($resultado);
}

function CobroRetenciones($services, $url_services, $ficha_tecnica_propiedad, $monto_retenido)
{

    $query = "SELECT propiedades.fn_cobra_retencion($ficha_tecnica_propiedad, $monto_retenido, 0)";
    $data = array("consulta" => $query);
    $resultado = $services->sendPostDirecto($url_services . '/util/objeto', $data);
    return json_decode($resultado);
}


/**
 * 
 * 
 *  funcion calcular
 * 
 * 
 */

function calcularComisionAdmIVA($saldo, $comision_administracion, $iva, $tipo_moneda_administracion)
{

    $comision_adm = 0;
    $IVA_comision_adm = 0;

    switch ($tipo_moneda_administracion) {
        case 1: // Porcentaje de comisión
            $comision_adm = round($saldo * $comision_administracion / 100);
            break;
        case 2: // Suma directa de valor
            $comision_adm = round($saldo + $comision_administracion);
            break;
        case 3: // UF (Unidad de Fomento en Chile)
            $comision_adm = round($saldo + $comision_administracion);
            break;
        case 4: // USD (dólares)
            $comision_adm = round($saldo + $comision_administracion);
            break;
        default:

            $comision_adm = 0;
            // Manejar un caso inesperado si el tipo de moneda no es válido
            // throw new Exception("Tipo de moneda de administración no reconocido.");
    }

    $IVA_comision_adm = round($comision_adm - ($comision_adm / (1 + ($iva / 100))), 2);

    return [$comision_adm, $IVA_comision_adm];
}

function calcularComisionArriendoIVA($valorArriendo, $monto_comision_arriendo, $iva, $tipo_moneda_arrinedo)
{


    $comision_arriendo = 0;
    $IVA_comision_adm = 0;

    switch ($tipo_moneda_arrinedo) {
        case 1: // Porcentaje de comisión
            $comision_arriendo = round($valorArriendo * $monto_comision_arriendo / 100);
            break;
        case 2: // Suma directa de valor
            $comision_arriendo = round($valorArriendo + $monto_comision_arriendo);
            break;
        case 3: // UF (Unidad de Fomento en Chile)
            $comision_arriendo = round($valorArriendo + $monto_comision_arriendo);
            break;
        case 4: // USD (dólares)
            $comision_arriendo = round($valorArriendo + $monto_comision_arriendo);
            break;
        default:

            $comision_arriendo = 0;
            // Manejar un caso inesperado si el tipo de moneda no es válido
            //throw new Exception("Tipo de moneda de administración no reconocido.");
    }

    $IVA_comision_arrinedo = round($comision_arriendo - ($comision_arriendo / (1 + ($iva / 100))), 2);

    return [$comision_arriendo, $IVA_comision_arrinedo];
}

function calcularGarantia($valor_garantia = 0, $porcentaje_participacion = 0)
{
    if ($valor_garantia > 0  &&  $porcentaje_participacion > 0) {
        $montoGarantia = ($valor_garantia * $porcentaje_participacion) / 100;
        return $montoGarantia;
    } else {
        return 0;
    }
}

function calcularMovimientos($dataMovimientos)
{

    $sumCargos = 0;
    $sumAbonos = 0;
    $filas_movimientos = [];

    if ($dataMovimientos) {
        foreach ($dataMovimientos as $mov) {
            $cargos = $mov->haber != 0 ? $mov->haber : 0;
            $abonos = $mov->debe != 0 ? $mov->debe : 0;
            $sumCargos += $cargos;
            $sumAbonos += $abonos;

            // Guardar las filas de movimientos
            $filas_movimientos[] = [
                'fecha' => date("d-m-Y", strtotime($mov->fecha_movimiento)),
                'razon' => strtoupper($mov->razon),
                'cargos' => "$" . number_format($cargos, 0, '', '.'),
                'abonos' => "$" . number_format($abonos, 0, '', '.')
            ];
        }
    }

    return [
        'sumCargos' => $sumCargos,
        'sumAbonos' => $sumAbonos,
        'filas_movimientos' => $filas_movimientos
    ];
}

function calcularTotales($comision_administracion = 0,  $comision_arriendo = 0, $garantia = 0)
{
    // Asegurarse de que los valores sean números
    $comision_administracion = $comision_administracion ?? 0;
    $comision_arriendo = $comision_arriendo ?? 0;
    $garantia = $garantia ?? 0;

    // Sumar comisiones y garantía
    $total_comisiones = $comision_administracion + $comision_arriendo + $garantia;
    return $total_comisiones;
}

function calcularSaldo($sumAbonos = 0, $comision_administracion = 0, $comision_arriendo = 0, $garantia = 0)
{
    // Calcular total de comisiones
    $total_comisiones = calcularTotales($comision_administracion, $comision_arriendo, $garantia);
    $saldo = ($sumAbonos - $total_comisiones); // Garantía incluida en cargos

    return $saldo;
}

function SumaAbonos($dataMovimientos)
{

    $sumAbonos = 0;

    foreach ($dataMovimientos[0]->fn_propiedades_por_liquidar as $mov) {
        // Verificar si el objeto tiene la propiedad "detalle" para evitar errores
        if (isset($mov->detalle)) {
            // Clasificar entre "DEBE" y "HABER"
            if ($mov->detalle->id_tipo_movimiento_cta_cte == 1) {
                $abonos = $mov->detalle->monto;
                $cargos = 0; // No hay cargos cuando es "HABER"
            } elseif ($mov->detalle->id_tipo_movimiento_cta_cte == 2) {
                $cargos = $mov->detalle->monto;
                $abonos = 0; // No hay abonos cuando es "DEBE"
            } else {
                // Manejar otros tipos de movimientos si existen
                $cargos = 0;
                $abonos = 0;
            }

            $sumAbonos += $abonos;
        }
    }

    return $sumAbonos;
}

function GenerarCorrelativo($QueryBuilder)
{
    // Asegúrate de tener una conexión activa a la base de datos en $this->db
    $functionName = "propiedades.correlativo"; // Nombre de la función SQL
    $params = []; // No se necesitan parámetros para esta función

    try {
        // Llamamos a la función usando executeFunction
        $result = $QueryBuilder->executeFunction($functionName, $params);

        return json_encode($result);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}


/***
 * 
 * 
 *  
 * crea el pdf
 * 
 * 
 * 
 */

function generarPDF(
    $services,
    $url_services,
    $ficha_tecnica_propiedad,
    $dataPropiedad,
    $dataArrendatario,
    $dataMovimientos,
    $datosPropietarios,
    $valor_UF,
    $iva,
    $comision_administracion,        // Comisión de administración
    $iva_comision_administracion,    // IVA de la comisión de administración
    $comision_arriendo,              // Comisión de arriendo
    $iva_comision_arriendo,          // IVA de la comisión de arriendo
    $tipo_moneda_administracion,
    $tipo_movimiento_cta_cte,
    $prioridad_calculos_liquidaciones,
    $tipo_moneda_arriendo,
    $adm_comision_monto,
    $arriendo_comision_monto,
    $numero_cuotas_garantia,
    $cuotasPorPagar,
    $garantia,
    $id_ficha_arriendo,
    $nombreArchivo, // Nuevo parámetro para el nombre del archivo
    $FichaArriendo,
    $idCuota,
    $NumeroLiquidaciones,
    $id_liquidacion,
    $precio,
    $url_liquidacion,
    $valorGarantia,
    $QueryBuilder,
    $flagCantidadRegistrosGrabados,
    $NumeroCierre

) {

    try {

        // Iniciar la transacción
        $QueryBuilder->beginTransaction();



        // define los valores a calcular
        $sumCargos = 0;
        $sumAbonos = 0;
        $TotalAnticipos = 0;
        $TotalRetenciones = 0;

        $pdf = new PDF_MC_Table();
        $pdf->AddPage();

        // Generar el código QR
        $filename = 'qr_temp.png';
        $contenidoQR = 'file:///home/kyaria/Descargas/factura-4.pdf';
        QRcode::png($contenidoQR, $filename);

        // Definir una fuente y color para todo el PDF
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0); // Color negro para el texto
        $pdf->SetFillColor(217, 237, 247);  // Color de fondo para cabeceras (celeste claro)


        // Verificar que la dataPropiedad tiene información válida
        if ($dataPropiedad && isset($dataPropiedad[0]->direccion)) {


            // Encabezado del PDF
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', "LIQUIDACIÓN DE ARRIENDO"), 0, 1, 'C');
            $pdf->SetFont('Arial', 'B', 13);
            $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', "DEPARTAMENTO DE ADMINISTRACIONES"), 0, 1, 'C');
            $pdf->Ln(5); // Espacio
            $pdf->SetFont('Arial', 'B', 13);
            $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', "FOLIO: " . $NumeroCierre), 0, 1, 'L');
            $pdf->Ln(5); // Espacio

            // Información de la propiedad y arrendatario
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetFillColor(217, 237, 247);  // Color celeste para el encabezado

            /****
             * 
             * a
             *  tabla de propiedad
             * 
             * 
             */

            // Cabecera de la tabla de propiedad
            $pdf->Cell(50, 7, 'CODIGO', 1, 0, 'C', true);  // Ancho ajustado a 50 mm
            $pdf->Cell(90, 7, 'PROPIEDAD', 1, 0, 'C', true);  // Ancho ajustado a 90 mm
            $pdf->Cell(50, 7, 'ARRENDATARIO', 1, 1, 'C', true);  // Ancho ajustado a 50 mm


            // Contenido de la tabla
            $pdf->SetFont('Arial', '', 8);

            // Dirección y detalles de la propiedad
            $direccion = iconv('UTF-8', 'ISO-8859-1', strtoupper($dataPropiedad[0]->direccion . " #" . $dataPropiedad[0]->numero . ", " . $dataPropiedad[0]->comuna . ", " . $dataPropiedad[0]->region . ", " . $dataPropiedad[0]->pais));
            // Nombre completo del arrendatario
            $nombreArrendatario = iconv('UTF-8', 'ISO-8859-1', strtoupper($dataArrendatario[0]->nombre_1 . ' ' . $dataArrendatario[0]->nombre_2 . ' ' . $dataArrendatario[0]->nombre_3));
            // Relleno de los datos en la tabla propietarios
            $pdf->Cell(50, 7, $dataPropiedad[0]->codigo_prop, 1, 0, 'C');
            $pdf->Cell(90, 7, $direccion,  1, 0, 'C');
            $pdf->Cell(50, 7, substr($nombreArrendatario, 0, 15), 1, 1, 'C');

            // Espacio antes de la siguiente sección
            $pdf->Ln(5);

            /****
             * 
             * 
             *  tabla de propietarios
             * 
             * 
             */

            // Cabecera de la tabla de propiedad
            $pdf->SetFont('Arial', 'B', 10); // Negritas para cabecera
            $pdf->Cell(40, 7, 'PARTICIPACION', 1, 0, 'C', true);
            $pdf->Cell(150, 7, 'NOMBRE', 1, 1, 'C', true);

            // Contenido de la tabla de propietarios
            $pdf->SetFont('Arial', '', 8); // Fuente normal para el contenido
            foreach ($datosPropietarios as $propietario) {
                $participacion = $propietario->porcentaje_participacion_base;
                $nombrePropietario = iconv('UTF-8', 'ISO-8859-1', strtoupper($propietario->nombre_1 . ' ' . $propietario->nombre_2 . ' ' . $propietario->nombre_3));

                $pdf->Cell(40, 7, $participacion . '%', 1, 0, 'C');
                $pdf->Cell(150, 7, $nombrePropietario, 1, 1, 'C');
            }

            // Espacio antes de la siguiente sección
            $pdf->Ln(5);

            // Encabezado de la tabla de movimientos
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 10, "DETALLE MOVIMIENTOS", 0, 1);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(217, 237, 247); // Color de fondo azul pálido
            $pdf->SetWidths(array(38, 78, 38, 38)); // Ancho de las columnas
            $pdf->SetAligns(array('C', 'C', 'C', 'C')); // Alineación centrada para cada columna
            $pdf->Row(array("FECHA", "DETALLE", "CARGO", "ABONO"), 7, 'C', true);

            // Movimientos financieros
            $pdf->SetFont('Arial', '', 8); // Fuente normal para los datos
            $sumCargos = 0;
            $sumAbonos = 0;

            // Almacena temporalmente las filas de la tabla para imprimir al final
            $filas_movimientos = [];

            if ($dataMovimientos) {

                $filas_movimientos = [];

                foreach ($dataMovimientos[0]->fn_propiedades_por_liquidar as $mov) {
                    // Verificar si el objeto tiene la propiedad "detalle" para evitar errores
                    if (isset($mov->detalle)) {
                        // Clasificar entre "DEBE" y "HABER"
                        if ($mov->detalle->id_tipo_movimiento_cta_cte == 1) {
                            $abonos = $mov->detalle->monto;
                            $cargos = 0; // No hay cargos cuando es "HABER"
                        } elseif ($mov->detalle->id_tipo_movimiento_cta_cte == 2) {
                            $cargos = $mov->detalle->monto;
                            $abonos = 0; // No hay abonos cuando es "DEBE"
                        } else {
                            // Manejar otros tipos de movimientos si existen
                            $cargos = 0;
                            $abonos = 0;
                        }

                        // Acumular sumas
                        $sumCargos += $cargos;
                        $sumAbonos += $abonos;

                        // Guardar las filas de movimientos
                        $filas_movimientos[] = [
                            'fecha' => date("d-m-Y", strtotime($mov->detalle->fecha_movimiento)),
                            'razon' => strtoupper($mov->detalle->razon),
                            'cargos' => "$" . number_format($cargos, 0, '', '.'),
                            'abonos' => "$" . number_format($abonos, 0, '', '.')
                        ];
                    }
                }

                if ($FichaArriendo[0]->pago_garantia_propietario == 1) {

                    if ($cuotasPorPagar >  0) {

                        $garantia = $valorGarantia;
                        $TotalGarantiaConIva =  ($valorGarantia * $iva);

                        $filas_movimientos[] = [
                            'fecha' => date("d-m-Y", strtotime($mov->detalle->fecha_movimiento)),
                            'razon' => strtoupper('Garantia IVA INCLUIDO'),
                            'cargos' => "$" . number_format(0, 0, '', '.'),
                            'abonos' => "$" . number_format($TotalGarantiaConIva, 0, '', '.')
                        ];

                        $sumAbonos =  $sumAbonos + $garantia;
                    }
                }
            }

            // Imprimir todas las filas almacenadas de movimientos
            foreach ($filas_movimientos as $fila) {
                $pdf->Cell(38, 6, $fila['fecha'], 1, 0, 'C');
                $pdf->Cell(78, 6, $fila['razon'], 1, 0, 'L');
                $pdf->Cell(38, 6, $fila['cargos'], 1, 0, 'R');
                $pdf->Cell(38, 6, $fila['abonos'], 1, 1, 'R');
            }

            // Espacio antes de mostrar comisiones y totales
            $pdf->Ln(5); // Salto de línea+

            /***
             * 
             * 
             *  calculos  saldo_cobordos
             * 
             * 
             * 
             */
            // Calcular total y calculo saldo
            if ($FichaArriendo[0]->pago_garantia_propietario == 1) {
                if ($NumeroLiquidaciones === 0) {
                    $total_comisiones = calcularTotales($comision_administracion,  $comision_arriendo);
                    $saldo = calcularSaldo($sumAbonos, $comision_administracion, $comision_arriendo);
                } else {
                    $total_comisiones = calcularTotales($comision_arriendo);
                    $saldo = calcularSaldo($sumAbonos, $comision_arriendo);
                }
            } else {
                if ($NumeroLiquidaciones === 0) {
                    $total_comisiones = calcularTotales($comision_administracion,  $comision_arriendo);
                    $saldo = calcularSaldo($sumAbonos, $comision_administracion, $comision_arriendo);
                } else {
                    $total_comisiones = calcularTotales($comision_arriendo);
                    $saldo = calcularSaldo($sumAbonos, $comision_arriendo);
                }
            }

            /***
             * 
             * 
             *  comision adminsitracion
             * 
             * 
             * 
             */

            // Mostrar la comisión de corretaje  -- se cambio el nombre del texto ESTO ES CORRETAJE!!!
            $totalComisionCorretaje = 0;
            if ($comision_administracion) {

                if ($NumeroLiquidaciones === 0) {

                    $totalComisionCorretaje = ($comision_administracion + $iva_comision_administracion);
                    $totalComisionNeto = ($comision_administracion - $iva_comision_administracion);

                    // Si tienes el porcentaje guardado en una variable, por ejemplo $porcentaje_administracion
                    $pdf->Cell(38, 6, date('d-m-Y'), 1, 0, 'C');
                    // Concatenar el porcentaje o el valor en el texto
                    $texto_comision_administracion = "COMISIÓN CORRETAJE (" . $adm_comision_monto  . "%) IVA INCLUIDO";
                    // Mostrar el texto con el valor concatenado
                    $pdf->Cell(78, 6, iconv('UTF-8', 'ISO-8859-1', $texto_comision_administracion), 1, 0, 'L');
                    // Mostrar el valor de la comisión en formato moneda
                    $pdf->Cell(38, 6, "$" . number_format($totalComisionCorretaje, 0, '', '.'), 1, 0, 'R');
                    $pdf->Cell(38, 6, "", 1, 1, 'R');


                    // guarda el calculo como ficha_arriendo_cta_cte_movimientos
                    if ($flagCantidadRegistrosGrabados == 1) {
                        GuardarMovimientosCtaCte(
                            $services,
                            $url_services,
                            $id_ficha_arriendo,
                            date("Y-m-d"),
                            date("H:i:s"),
                            21,
                            $totalComisionNeto,
                            $saldo,
                            "COMISIÓN CORRETAJE ",
                            'false',
                            0,
                            $ficha_tecnica_propiedad,
                            'false',
                            0,
                            0,
                            $NumeroCierre,
                            'false',
                            'false',
                            date("Y-m-d"),
                            $ficha_tecnica_propiedad,
                            0,
                            3,
                            "I",
                        );
                    }


                    // Mostrar el IVA de la comisión de administración
                    if ($iva_comision_administracion > 0) {

                        //     // Concatenar el porcentaje o el valor en el texto
                        //     $texto_comision_administracion_iva = "IVA COMISIÓN CORRETAJE (" . $iva  . "%)";

                        //     $pdf->Cell(38, 6, date('d-m-Y'), 1, 0, 'C');
                        //     $pdf->Cell(58, 6, iconv('UTF-8', 'ISO-8859-1', $texto_comision_administracion_iva), 1, 0, 'L');
                        //     $pdf->Cell(58, 6, "$" . number_format($iva_comision_administracion, 0, '', '.'), 1, 0, 'R');
                        //     $pdf->Cell(38, 6, "", 1, 1, 'R');
                        if ($flagCantidadRegistrosGrabados == 1) {
                            GuardarMovimientosCtaCte(
                                $services,
                                $url_services,
                                $id_ficha_arriendo,
                                date("Y-m-d"),
                                date("H:i:s"),
                                29,
                                $iva_comision_administracion,
                                $saldo,
                                "IVA COMISIÓN CORRETAJE",
                                'false',
                                0,
                                $ficha_tecnica_propiedad,
                                'false',
                                0,
                                0,
                                $NumeroCierre,
                                'false',
                                'false',
                                date("Y-m-d"),
                                $ficha_tecnica_propiedad,
                                0,
                                3,
                                "I",
                            );
                        }
                    }
                }
            }





            /***
             * 
             * 
             *  comision ADMINISTRACIÓN
             * 
             * 
             */

            // Mostrar la comisión de arriendo
            $TotaComsionConIva = 0;
            //if ($comision_arriendo) {

            // guarda el calculo como ficha_arriendo_cta_cte_movimientos
            $TotaComsionConIva = ($comision_arriendo + $iva_comision_arriendo);
            $totalComisionNeto = ($comision_arriendo - $iva_comision_arriendo);

            // Concatenar el porcentaje o el valor en el texto
            $texto_comision_arriendo_iva = "COMISIÓN ADMINISTRACIÓN (" . $arriendo_comision_monto . "%) IVA INCLUIDO";

            $pdf->Cell(38, 6, date('d-m-Y'), 1, 0, 'C');
            $pdf->Cell(78, 6, iconv('UTF-8', 'ISO-8859-1', $texto_comision_arriendo_iva), 1, 0, 'L');
            $pdf->Cell(38, 6, "$" . number_format($TotaComsionConIva, 0, '', '.'), 1, 0, 'R');
            $pdf->Cell(38, 6, "", 1, 1, 'R');


            if ($flagCantidadRegistrosGrabados == 1) {
                GuardarMovimientosCtaCte(
                    $services,
                    $url_services,
                    $id_ficha_arriendo,
                    date("Y-m-d"),
                    date("H:i:s"),
                    10,
                    $totalComisionNeto,
                    $saldo,
                    "COMISIÓN ADMINISTRACIÓN",
                    'false',
                    0,
                    $ficha_tecnica_propiedad,
                    'false',
                    0,
                    0,
                    $NumeroCierre,
                    'false',
                    'false',
                    date("Y-m-d"),
                    $ficha_tecnica_propiedad,
                    0,
                    3,
                    "I",

                );
            }


            //}

            // Mostrar el IVA de la comisión de arriendo
            if ($iva_comision_arriendo > 0) {

                //     // Concatenar el porcentaje o el valor en el texto
                //     $texto_comision_arriendo_iva = "IVA COMISIÓN ADMINISTRACIÓN (" . $iva  . "%)";

                //     $pdf->Cell(38, 6, date('d-m-Y'), 1, 0, 'C');
                //     $pdf->Cell(58, 6, iconv('UTF-8', 'ISO-8859-1',      $texto_comision_arriendo_iva), 1, 0, 'L');
                //     $pdf->Cell(58, 6, "$" . number_format($iva_comision_arriendo, 0, '', '.'), 1, 0, 'R');
                //     $pdf->Cell(38, 6, "", 1, 1, 'R');

                if ($flagCantidadRegistrosGrabados == 1) {
                    GuardarMovimientosCtaCte(
                        $services,
                        $url_services,
                        $id_ficha_arriendo,
                        date("Y-m-d"),
                        date("H:i:s"),
                        24,
                        $iva_comision_arriendo,
                        $saldo,
                        "IVA COMISIÓN ADMINISTRACIÓN",
                        'false',
                        0,
                        $ficha_tecnica_propiedad,
                        'false',
                        0,
                        0,
                        $NumeroCierre,
                        'false',
                        'false',
                        date("Y-m-d"),
                        $ficha_tecnica_propiedad,
                        0,
                        3,
                        "I",
                    );
                }
            }



            /***
             * 
             * 
             *  garantias
             * 
             * 
             */


            // cobrar garantia
            // Solo continuar si aún quedan cuotas por cobrar
            if ($numero_cuotas_garantia > 0 && $cuotasPorPagar > 0) {

                if ($garantia > 0) {

                    if ($cuotasPorPagar >  0) {

                        // 0 true 1 false


                        if ($FichaArriendo[0]->pago_garantia_propietario == 0) {
                            // Concatenar el porcentaje o el valor en el texto
                            $texto_comision_arriendo_iva = "GARANTIA IVA INCLUIDO";
                            $pdf->Cell(38, 7, "", 1, 0, 'L');
                            $pdf->Cell(78, 7, iconv('UTF-8', 'ISO-8859-1',      $texto_comision_arriendo_iva), 1, 0, 'L');
                            $pdf->Cell(38, 7, "$" . number_format($garantia + $iva_comision_arriendo, 0, '', '.'), 1, 0, 'R');
                            $pdf->Cell(38, 7, "", 1, 1, 'R');
                        }

                        if ($flagCantidadRegistrosGrabados == 1) {
                            // actualiza la cuota a pagado
                            PagarCuotaGarantia($services, $url_services, $idCuota);
                            // guarda el calculo de garantia como ficha_arriendo_cta_cte_movimientos
                            // GuardarMovimientosCtaCte(
                            //     $services,
                            //     $url_services,
                            //     $id_ficha_arriendo,
                            //     date("Y-m-d"),
                            //     date("H:i:s"),
                            //     11,
                            //     $comision_arriendo,
                            //     $saldo,
                            //     "COBRO GARANTIA",
                            //     'false',
                            //     0,
                            //     $ficha_tecnica_propiedad,
                            //     'false',
                            //     0,
                            //     0,
                            //     0,
                            //     'false',
                            //     'false',
                            //     date("Y-m-d"),
                            //     $ficha_tecnica_propiedad,
                            //     0,
                            //     2,
                            //     "I",
                            // );
                        }
                    }
                }
            }
            /**
             * 
             * 
             *  cobra los anticipos y las retenciones
             * 
             * 
             */

            // cobrar anticipos
            if ($saldo > 0) {

                if ($flagCantidadRegistrosGrabados == 1) {

                    $Anticipos =  CobrarAnticipos($services, $url_services, $ficha_tecnica_propiedad, $saldo);
                    $TotalAnticipos = $Anticipos[0]->fn_cobra_anticipo;


                    // Concatenar el porcentaje o el valor en el texto
                    if ($TotalAnticipos) {
                        $texto_comision_arriendo_iva = "ANTICIPOS";
                        $pdf->Cell(38, 7, "", 1, 0, 'L');
                        $pdf->Cell(78, 7, iconv('UTF-8', 'ISO-8859-1',      $texto_comision_arriendo_iva), 1, 0, 'L');
                        $pdf->Cell(38, 7, "$" . number_format($TotalAnticipos, 0, '', '.'), 1, 0, 'R');
                        $pdf->Cell(38, 7, "", 1, 1, 'R');
                    }

                    GuardarMovimientosCtaCte(
                        $services,
                        $url_services,
                        $id_ficha_arriendo,
                        date("Y-m-d"),
                        date("H:i:s"),
                        26, // tipo de movimiento 
                        $TotalAnticipos,
                        $saldo,
                        "COBRO ANTICIPOS",
                        'false',
                        NULL,
                        $ficha_tecnica_propiedad,
                        'false',
                        NULL,
                        NULL,
                        $NumeroCierre,
                        'false',
                        'false',
                        date("Y-m-d"),
                        $ficha_tecnica_propiedad,
                        0,
                        1,
                        "I",
                    );
                }
            }

            if ($saldo > 0) {

                if ($flagCantidadRegistrosGrabados == 1) {

                    $Retenciones =  CobroRetenciones($services, $url_services, $ficha_tecnica_propiedad, $saldo);
                    $TotalRetenciones = $Retenciones[0]->fn_cobra_retencion;

                    // Concatenar el porcentaje o el valor en el texto
                    if ($TotalRetenciones > 0) {
                        $texto_comision_arriendo_iva = "RETENCIÓN";
                        $pdf->Cell(38, 7, "", 1, 0, 'L');
                        $pdf->Cell(78, 7, iconv('UTF-8', 'ISO-8859-1',      $texto_comision_arriendo_iva), 1, 0, 'L');
                        $pdf->Cell(38, 7, "$" . number_format($TotalRetenciones, 0, '', '.'), 1, 0, 'R');
                        $pdf->Cell(38, 7, "", 1, 1, 'R');
                    }

                    GuardarMovimientosCtaCte(
                        $services,
                        $url_services,
                        $id_ficha_arriendo,
                        date("Y-m-d"),
                        date("H:i:s"),
                        25, // tipo de movimiento 
                        $TotalRetenciones,
                        $saldo,
                        "COBRO RETENCIONES",
                        'false',
                        NULL,
                        $ficha_tecnica_propiedad,
                        'false',
                        NULL,
                        NULL,
                        $NumeroCierre,
                        'false',
                        'false',
                        date("Y-m-d"),
                        $ficha_tecnica_propiedad,
                        0,
                        1,
                        "I",
                    );
                }
            }


            // Mostrar totales
            $totales = ($total_comisiones + $sumCargos + $TotalAnticipos + $TotalRetenciones);

            $pdf->Cell(38, 6, "", 1, 0, 'L');
            $pdf->SetFont('Arial', 'B', 8.5);
            $pdf->Cell(78, 6, "TOTALES:", 1, 0, 'R');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(38, 6, "$" . number_format($totales, 0, '', '.'), 1, 0, 'R');
            $pdf->Cell(38, 6, "$" . number_format($sumAbonos, 0, '', '.'), 1, 1, 'R');


            $totalSaldos = ($sumAbonos - $totales);

            $pdf->Cell(38, 6, "", 1, 0, 'L');
            $pdf->SetFont('Arial', 'B', 8.5);
            $pdf->Cell(78, 6, "SALDO:", 1, 0, 'R');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(76, 6, "$" . number_format($totalSaldos, 0, '', '.'), 1, 1, 'R');

            /***
             * 
             * 
             * si tiene % de participacion paga segun el %
             * 
             * 
             */
            // Espacio antes de mostrar comisiones y totales


            $pdf->Ln(5); // Salto de línea+
            $pdf->Cell(38, 6, "", 1, 0, 'L');
            $pdf->SetFont('Arial', 'B', 8.5);
            $pdf->Cell(58, 6, "PORCENTAJE DE PARTICIPACION:", 1, 0, 'R');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(96, 6, $participacion . "%", 1, 1, 'R');

            $totalPagar = Round($totalSaldos  * ($participacion / 100));
            $pdf->Cell(38, 6, "", 1, 0, 'L');
            $pdf->SetFont('Arial', 'B', 8.5);
            $pdf->Cell(58, 6, "TOTAL A PAGAR:", 1, 0, 'R');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(96, 6, "$" . number_format($totalPagar, 0, '', '.'), 1, 1, 'R');



            // Insertar el QR code en el PDF
            $qrSize = 30; // Tamaño del QR en mm
            $pageWidth = $pdf->GetPageWidth();
            $pageHeight = $pdf->GetPageHeight();
            $qrX = ($pageWidth - $qrSize) / 2; // Centra horizontalmente
            $qrY = $pageHeight - $qrSize - 10; // Posición en Y (10 mm de margen inferior)
            $pdf->Image($filename, $qrX, $qrY, $qrSize);

            /**
             * 
             * 
             *   guarda la liquidacion en la bd
             *  
             * 
             */

            $id_propietario = $datosPropietarios[0]->id_propietario;
            GuardarLiquidacion(
                $services,
                $url_services,
                $ficha_tecnica_propiedad,
                $id_propietario,
                $precio,
                date("Y-m-d"),
                $id_ficha_arriendo,
                $url_liquidacion,
                $comision_arriendo,
                $iva,
                $sumAbonos,
                $totales,
                $totalPagar,
                0, // 0 no pagado 1 pagado 2 depositado
                $NumeroCierre,
                $participacion
            );

            // guarda el detalle de liquidacion de  comsion arriendo
            GuardarDetalleLiquidacion(
                $services,
                $url_services,
                $ficha_tecnica_propiedad,
                'COMISIÓN ARRIENDO',
                $comision_administracion,
                $iva,
                $id_liquidacion
            );

            // guarda el detalle de liquidacion de iva comsion corretaje
            GuardarDetalleLiquidacion(
                $services,
                $url_services,
                $ficha_tecnica_propiedad,
                'IVA COMISIÓN CORRETAJE',
                $iva_comision_administracion,
                $iva,
                $id_liquidacion
            );

            // guarda el detalle de liquidacion de COMISIÓN ADMINISTRACIÓN
            GuardarDetalleLiquidacion(
                $services,
                $url_services,
                $ficha_tecnica_propiedad,
                'COMISIÓN ADMINISTRACIÓN',
                $comision_arriendo,
                $iva,
                $id_liquidacion
            );

            // guarda el detalle de liquidacion IVA COMISIÓN ADMINISTRACIÓN
            GuardarDetalleLiquidacion(
                $services,
                $url_services,
                $ficha_tecnica_propiedad,
                'IVA COMISIÓN ADMINISTRACIÓN',
                $iva_comision_arriendo,
                $iva,
                $id_liquidacion
            );

            // guarda el detalle de liquidacion COBRO GARANTIA
            GuardarDetalleLiquidacion(
                $services,
                $url_services,
                $ficha_tecnica_propiedad,
                'COBRO GARANTIA',
                $comision_arriendo,
                $iva,
                $id_liquidacion
            );

            // guarda el detalle de liquidacion COBRO ANTICIPOS
            GuardarDetalleLiquidacion(
                $services,
                $url_services,
                $ficha_tecnica_propiedad,
                'COBRO ANTICIPOS',
                $TotalAnticipos,
                $iva,
                $id_liquidacion
            );

            // guarda el detalle de liquidacion COBRO RETENCIONES
            GuardarDetalleLiquidacion(
                $services,
                $url_services,
                $ficha_tecnica_propiedad,
                'COBRO RETENCIONES',
                $TotalRetenciones,
                $iva,
                $id_liquidacion
            );



            /**
             * 
             * 
             *  actualiza con el id insertado de la ultima liquidacion todos los movimientos
             *  
             * 
             */

            ActualizarIDMovimientosCtaCte($services, $url_services, $NumeroCierre, $id_ficha_arriendo, $ficha_tecnica_propiedad);
            //ActualizarLiquidacionCierre($services, $url_services, $NumeroCierre, $ficha_tecnica_propiedad);

            /**
             * 
             * 
             *  actualiza los estados a L (liquidado) en la cuenta corriente. 
             *  
             * 
             */

            ActualizarEstadosCuentasCorriente($services, $url_services, $ficha_tecnica_propiedad);


            // Confirmar la transacción
            $QueryBuilder->commit();
        } else {
            $pdf->Cell(0, 10, "No se encontraron datos de la propiedad.", 0, 1);
        }

        // Mostrar el PDF en pantalla
        $pdf->Output('F', $nombreArchivo); // Guardar el archivo en el sistema de archivos

    } catch (\Throwable $th) {

        $QueryBuilder->rollback();
        echo $th->getMessage();
    }
}

function procesarLiquidacion($ficha_tecnica_propiedad, $services, $url_services, $QueryBuilder)
{

    try {

        // flag cantidad de registros grabados por iteracion
        $flagCantidadRegistrosGrabados = 1;


        // Carpeta donde se guardarán los PDFs generados
        $carpetaPDFs = '../../../upload/liquidaciones/';

        // Crear la carpeta si no existe
        if (!is_dir($carpetaPDFs)) {
            mkdir($carpetaPDFs, 0777, true);
        }

        // Obtener fecha actual
        $fechaActual = new DateTime();

        // Validar que el valor de la UF se obtiene correctamente
        $valor_UF = obtenerValorUF($services, $url_services, $fechaActual);
        if (empty($valor_UF)) {
            throw new Exception("Faltan datos: No se pudo obtener el valor de la UF.");
        }

        // Obtener el IVA de la subsidiaria
        $current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
        if (empty($current_subsidiaria) || empty($current_subsidiaria->id)) {
            throw new Exception("Faltan datos: No se pudo obtener la subsidiaria actual o su ID.");
        }

        $iva = obtenerIVA($services, $url_services, $current_subsidiaria->id);
        if (empty($iva)) {
            throw new Exception("Faltan datos: No se pudo obtener el IVA de la subsidiaria.");
        }

        // Obtener información de la propiedad
        $dataPropiedad = obtenerInfoPropiedad($services, $url_services, $ficha_tecnica_propiedad);
        if (empty($dataPropiedad)) {
            throw new Exception("Faltan datos: No se pudo obtener la información de la propiedad.");
        }


        // Obtener información del arrendatario
        $dataArrendatario = obtenerInfoArrendatario($services, $url_services, $ficha_tecnica_propiedad);
        if (empty($dataArrendatario) || empty($dataArrendatario[0]->id)) {
            throw new Exception("Faltan datos: No se pudo obtener la información del arrendatario o su ID.");
        }
        $id_ficha_arriendo = $dataArrendatario[0]->id;

        // Obtener la prioridad de cálculos de liquidaciones
        $prioridad_calculos_liquidaciones = obteneOrdenPrioridades($services, $url_services);
        if (empty($prioridad_calculos_liquidaciones)) {
            throw new Exception("Faltan datos: No se pudo obtener la prioridad de cálculos de liquidaciones.");
        }

        // Obtener movimientos financieros de la propiedad
        $dataMovimientos = obtenerMovimientos($services, $url_services, $ficha_tecnica_propiedad);
        if (empty($dataMovimientos)) {
            throw new Exception("Faltan datos: No se pudo obtener los movimientos financieros de la propiedad.");
        }
        $numero_cuotas_garantia = $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->num_cuotas_garantia;


        // Obtener los datos de la ficha de arriendo
        $FichaArriendo = ObtenerFichaArriendo($services, $url_services, $id_ficha_arriendo);
        if (empty($FichaArriendo)) {
            throw new Exception("Faltan datos: No se pudo obtener la ficha de arriendo.");
        }
        $precio = $FichaArriendo[0]->precio;


        // Obtener los datos de los propietarios
        $datosPropietarios = ObtenerPropietario($services, $url_services, $ficha_tecnica_propiedad);
        if (empty($datosPropietarios)) {
            throw new Exception("Faltan datos: No se pudo obtener los datos de los propietarios.");
        }

        // Obtener ID cuota garantía
        $idCuotas = ObtenerIdCuotaGarantia($services, $url_services, $id_ficha_arriendo);
        if (empty($idCuotas) || empty($idCuotas[0]->id)) {
            throw new Exception("Faltan datos: No se pudo obtener el ID de la cuota de garantía.");
        }
        $idCuota = $idCuotas[0]->id;

        // Obtener Valores Garantia
        $MontoGarantiaCuota = ObtenerValorGarantias($QueryBuilder, $id_ficha_arriendo);
        $valorGarantia = $MontoGarantiaCuota[0]["monto_garantia"];

        // obtiene el numero de liquidaciones del arriendo
        $CantidadLiquidacionesArriendo = ObtenerCantidadLiquidaciones($services, $url_services, $id_ficha_arriendo);
        $NumeroLiquidaciones =  $CantidadLiquidacionesArriendo[0]->cantidad_liquidaciones;

        // Obtener cantidad de cuotas por pagar
        $cuotasPorPagarGarantias = ObtenerCuotasPorPagarGarantia($services, $url_services, $id_ficha_arriendo);
        $cuotasPorPagar = $cuotasPorPagarGarantias[0]->cuotasnopagas;


        // Array para almacenar los enlaces a los PDFs generados
        $enlacesPDFs = [];

        // Sumar abonos
        $TotalAbono = SumaAbonos($dataMovimientos);


        // Calcular los montos ajustados para cada propietario según su porcentaje de participación
        $comision_administracion = calcularComisionAdmIVA(
            $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->precio,
            $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->adm_comision_monto,
            $iva,
            $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->adm_comision_id_moneda
        )[0];

        $iva_comision_administracion = calcularComisionAdmIVA(
            $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->precio,
            $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->adm_comision_monto,
            $iva,
            $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->adm_comision_id_moneda
        )[1];

        $comision_arriendo = calcularComisionArriendoIVA(
            $TotalAbono,
            $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->arriendo_comision_monto,
            $iva,
            $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->arriendo_comision_id_moneda
        )[0];

        $iva_comision_arriendo = calcularComisionArriendoIVA(
            $TotalAbono,
            $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->arriendo_comision_monto,
            $iva,
            $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->arriendo_comision_id_moneda
        )[1];



        $NumeroCierre = GenerarCorrelativo($QueryBuilder);

        // Recorrer cada propietario para generar una liquidación separada
        foreach ($datosPropietarios  as $index => $propietario) {

            // ultimo id liquidacion de la propiedad
            $UltimoIdLiquidacion = ObtenerUltimoIDLiquidacion($services, $url_services);
            $id_liquidacion = $UltimoIdLiquidacion[0]->id_liquidacion;

            // Calcular comisiones y otros valores específicos para cada propietario
            $porcentajeParticipacion = $propietario->porcentaje_participacion_base / 100;
            // Calcular la garantía proporcional
            $garantia = calcularGarantia($dataMovimientos[0]->fn_propiedades_por_liquidar[0]->monto_garantia, $porcentajeParticipacion * 100);
            $random = rand(1, 999999);
            // Generar un nombre único para el PDF basado en el nombre del propietario
            $nombreArchivo = $carpetaPDFs . $random . date('YmdHsmm') . '.pdf';
            // Agregar el enlace al archivo generado al array de enlaces
            $enlacesPDFs[] = $nombreArchivo;
            $indexes[] = $index;


            // Generar el PDF para el propietario actual
            generarPDF(
                $services,
                $url_services,
                $ficha_tecnica_propiedad,
                $dataPropiedad,
                $dataArrendatario,
                $dataMovimientos,
                [$propietario], // Enviar solo el propietario actual para el PDF
                $valor_UF,
                $iva,
                $comision_administracion,
                $iva_comision_administracion,
                $comision_arriendo,
                $iva_comision_arriendo,
                $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->adm_comision_id_moneda,
                $dataMovimientos[0]->fn_propiedades_por_liquidar[1]->detalle->id_tipo_movimiento_cta_cte,
                $prioridad_calculos_liquidaciones,
                $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->arriendo_comision_id_moneda,
                $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->adm_comision_monto,
                $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->arriendo_comision_monto,
                $numero_cuotas_garantia,
                $cuotasPorPagar,
                $garantia,
                $id_ficha_arriendo,
                $nombreArchivo, // Pasar el nombre del archivo para guardarlo
                $FichaArriendo,
                $idCuota,
                $NumeroLiquidaciones,
                $id_liquidacion,
                $precio,
                $nombreArchivo,
                $valorGarantia,
                $QueryBuilder,
                $flagCantidadRegistrosGrabados,
                $NumeroCierre
            );


            $flagCantidadRegistrosGrabados += 1;
        }






        // Mostrar los enlaces generados para que el usuario pueda abrirlos
        echo '
        <style>
            .pdf-list {
                font-family: Arial, sans-serif;
                margin-top: 20px;
                padding: 10px;
                border: 1px solid #ddd;
                background-color: #f9f9f9;
                border-radius: 5px;
            }
            .pdf-list h2 {
                color: #333;
                text-align: center;
            }
            .pdf-list ul {
                list-style: none;
                padding: 0;
            }
            .pdf-list ul li {
                margin: 10px 0;
            }
            .pdf-link {
                display: inline-block;
                padding: 10px 15px;
                background-color: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }
            .pdf-link:hover {
                background-color: #0056b3;
            }
        </style>
    ';

        // Mostrar los enlaces generados dentro de una lista estilizada
        echo '<div class="pdf-list">';
        echo '<h2>Liquidaciones Generadas</h2>';
        echo '<ul>';
        foreach ($enlacesPDFs as $enlace) {
            echo "<li><a class='pdf-link' href='$enlace' target='_blank'>Ver Liquidacion PDF " . $ficha_tecnica_propiedad . "</a></li>";
        }
        echo '</ul>';
        echo '</div>';
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}

/****
 * 
 * Procesa las liquidaciones
 * 
 */
$id = $_POST['ficha_tecnica'];
$rutasPDF = []; // Array para almacenar las rutas de los PDFs generados

// Verificar si $id es un array o un valor único
if (is_array($id)) {
    // Recorrer los IDs si se recibe un array
    foreach ($id as $ficha_tecnica) {
        $archivoGenerado = procesarLiquidacion($ficha_tecnica, $services, $url_services, $QueryBuilder);

        if ($archivoGenerado) {
            // Aquí se asume que $archivoGenerado contiene la ruta del PDF
            $rutasPDF[] = $archivoGenerado;
        }
    }
} else {
    // Si $id es un valor único, procesarlo directamente
    $archivoGenerado = procesarLiquidacion($id, $services, $url_services, $QueryBuilder);

    if ($archivoGenerado) {
        $rutasPDF[] = $archivoGenerado;
    }
}
