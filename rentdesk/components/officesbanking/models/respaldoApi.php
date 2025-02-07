<?php

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

function ObtenerCantidadLiquidaciones($services, $url_services, $ficha_tecnica_propiedad)
{

    $query_count = "SELECT count(id) AS cantidad_liquidaciones FROM propiedades.propiedad_liquidaciones where id_ficha_propiedad = $ficha_tecnica_propiedad";
    $data = array("consulta" => $query_count, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);

    return json_decode($resultado);
}

function ObtenerUltimoIDLiquidacion($services, $url_services, $ficha_tecnica_propiedad)
{

    $query = "SELECT id As id_liquidacion FROM propiedades.propiedad_liquidaciones WHERE id_ficha_propiedad = $ficha_tecnica_propiedad ORDER BY id DESC LIMIT 1";
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

function ActualizarIDMovimientosCtaCte($services, $url_services, $id_liquidacion, $ficha_tecnica_propiedad)
{

    $query = "UPDATE propiedades.ficha_arriendo_cta_cte_movimientos SET id_liquidacion = $id_liquidacion  WHERE  (id_liquidacion is null OR id_liquidacion = 0) AND id_propiedad = $ficha_tecnica_propiedad";
    $dataCab = array("consulta" => $query);
    $resultado = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
    return json_decode($resultado);
}


function GuardarMovimientosCtaCte(
    $services,
    $url_services,
    $id_ficha_arriendo,
    $fecha_movimiento,
    $hora_movimiento,
    $id_tipo_movimiento_cta_cte,
    $monto,
    $saldo,
    $razon,
    $cobro_comision,
    $nro_cuotas,
    $id_propiedad,
    $pago_arriendo,
    $id_varios_acreedores,
    $cta_contable,
    $id_liquidacion,
    $editar,
    $eliminar,
    $mes_imputado,
    $codigo_propiedad,
    $id_cierre_conciliacion,
    $id_responsable,
    $estado
) {


    // Consulta preparada
    $query = "INSERT INTO propiedades.ficha_arriendo_cta_cte_movimientos(
            id_ficha_arriendo, fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, saldo, 
            razon, cobro_comision, nro_cuotas, id_propiedad, pago_arriendo, id_varios_acreedores, cta_contable, 
            id_liquidacion, editar, eliminar, mes_imputado, codigo_propiedad, id_cierre_conciliacion, id_responsable, estado
        ) VALUES ($id_ficha_arriendo, '$fecha_movimiento', '$hora_movimiento', $id_tipo_movimiento_cta_cte, $monto, $saldo, 
            '$razon', $cobro_comision, $nro_cuotas, $id_propiedad, $pago_arriendo, $id_varios_acreedores, $cta_contable, 
            $id_liquidacion, $editar, $eliminar, '$mes_imputado', '$codigo_propiedad', $id_cierre_conciliacion, $id_responsable, '$estado')";

    // Preparar datos para el envío del servicio
    $data = array("consulta" => $query, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostDirecto($url_services . '/util/dml', $data, []);

    return json_decode($resultado);
}


function GuardarLiquidacion($services, $url_services, $id_ficha_propiedad, $id_propietario, $monto, $fecha_liquidacion, $id_ficha_arriendo, $url_liquidacion, $comision, $iva, $abonos, $descuentos, $total)
{

    $query = "INSERT INTO propiedades.propiedad_liquidaciones(id_ficha_propiedad, id_propietario, monto, fecha_liquidacion, id_ficha_arriendo, url_liquidacion, comision, iva, abonos, descuentos, total)VALUES ($id_ficha_propiedad, $id_propietario, $monto, '$fecha_liquidacion', $id_ficha_arriendo, '$url_liquidacion', $comision, $iva, $abonos,$descuentos, $total)";
    $data = array("consulta" => $query);
    $resultado = $services->sendPostDirecto($url_services . '/util/dml', $data);

    return json_decode($resultado);
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

    $queryMovimientos = "SELECT propiedades.fn_cobra_retencion($ficha_tecnica_propiedad,$monto_retenido)";
    $data = array("consulta" => $queryMovimientos, "cantRegistros" => 50, "numPagina" => 1);
    $resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data, []);
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


/**
 * 
 *  
 * ejecuta la funcion por orden de prioridades
 * 
 * 
 * 
 */

//  Obtener las prioridades de las operaciones
// $ordenPrioridades = obteneOrdenPrioridades($services, $url_services);

// // Crear un array donde almacenaremos el orden basado en la prioridad
// $orden = [];

// // Recorrer el array de prioridades para construir el orden dinámicamente
// foreach ($ordenPrioridades as $prioridad) {
//     $orden[] = $prioridad->nro_funcion;  // Basado en la propiedad 'nro_funcion' o la que necesites
// }

// // Funciones permitidas
// $funciones_permitidas = [
//     1 => 'funcion1',
//     2 => 'funcion2',
//     3 => 'funcion3',
//     4 => 'funcion4',
//     5 => 'funcion5'
// ];

// // Recorrer el orden generado y ejecutar las funciones en el orden adecuado
// foreach ($orden as $numero) {
//     if (array_key_exists($numero, $funciones_permitidas)) {
//         $funcion = $funciones_permitidas[$numero];

//         // Asegúrate de que la función existe y es callable antes de intentar ejecutarla
//         if (is_callable($funcion)) {
//             $funcion(); // Ejecuta la función permitida
//         } else {
//             echo "La función $funcion no es ejecutable\n";
//         }
//     } else {
//         echo "La función para el número $numero no está permitida\n";
//     }
// }


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
    $precio

) {

    try {

        // define los valores a calcular
        $sumCargos = 0;
        $sumAbonos = 0;



        $pdf = new PDF_MC_Table();
        $pdf->AddPage();

        // Generar el código QR
        $filename = 'qr_temp.png';
        $contenidoQR = 'file:///home/kyaria/Descargas/factura-4.pdf';
        QRcode::png($contenidoQR, $filename);

        // Definir una fuente y color para todo el PDF
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0); // Color negro para el texto
        $pdf->SetFillColor(173, 216, 230); // Color de fondo para cabeceras (celeste claro)


        // Verificar que la dataPropiedad tiene información válida
        if ($dataPropiedad && isset($dataPropiedad[0]->direccion)) {

            // Encabezado del PDF
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', "LIQUIDACIÓN DE ARRIENDO"), 0, 1, 'C');
            $pdf->SetFont('Arial', 'B', 13);
            $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', "DEPARTAMENTO DE ADMINISTRACIONES"), 0, 1, 'C');
            $pdf->Ln(5); // Espacio
            $pdf->SetFont('Arial', 'B', 13);
            $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', "FOLIO: " . $id_liquidacion), 0, 1, 'L');
            $pdf->Ln(5); // Espacio

            // Información de la propiedad y arrendatario
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetFillColor(173, 216, 230); // Color celeste para el encabezado

            /****
             * 
             * 
             *  tabla de propiedad
             * 
             * 
             */

            // Cabecera de la tabla de propiedad
            $pdf->Cell(30, 7, 'CODIGO', 1, 0, 'C', true);
            $pdf->Cell(100, 7, 'PROPIEDAD', 1, 0, 'C', true);
            $pdf->Cell(60, 7, 'ARRENDATARIO', 1, 1, 'C', true);

            // Contenido de la tabla
            $pdf->SetFont('Arial', '', 8);

            // Dirección y detalles de la propiedad
            $direccion = iconv('UTF-8', 'ISO-8859-1', strtoupper($dataPropiedad[0]->direccion . " #" . $dataPropiedad[0]->numero . ", " . $dataPropiedad[0]->comuna . ", " . $dataPropiedad[0]->region . ", " . $dataPropiedad[0]->pais));
            // Nombre completo del arrendatario
            $nombreArrendatario = iconv('UTF-8', 'ISO-8859-1', strtoupper($dataArrendatario[0]->nombre_1 . ' ' . $dataArrendatario[0]->nombre_2 . ' ' . $dataArrendatario[0]->nombre_3));
            // Relleno de los datos en la tabla propietarios
            $pdf->Cell(30, 7, $dataPropiedad[0]->codigo_prop, 1, 0, 'C');
            $pdf->Cell(100, 7, $direccion,  1, 0, 'C');
            $pdf->Cell(60, 7, $nombreArrendatario, 1, 1, 'C');

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
            $pdf->SetWidths(array(38, 58, 58, 38)); // Ancho de las columnas
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

                        $garantia = $FichaArriendo[0]->monto_garantia;
                        $filas_movimientos[] = [
                            'fecha' => date("d-m-Y", strtotime($mov->detalle->fecha_movimiento)),
                            'razon' => strtoupper('Garantia'),
                            'cargos' => "$" . number_format(0, 0, '', '.'),
                            'abonos' => "$" . number_format($garantia, 0, '', '.')
                        ];

                        $sumAbonos =  $sumAbonos + $garantia;
                    }
                }
            }

            // Imprimir todas las filas almacenadas de movimientos
            foreach ($filas_movimientos as $fila) {
                $pdf->Cell(38, 6, $fila['fecha'], 1, 0, 'C');
                $pdf->Cell(58, 6, $fila['razon'], 1, 0, 'L');
                $pdf->Cell(58, 6, $fila['cargos'], 1, 0, 'R');
                $pdf->Cell(38, 6, $fila['abonos'], 1, 1, 'R');
            }

            // Espacio antes de mostrar comisiones y totales
            $pdf->Ln(5); // Salto de línea+

            /***
             * 
             * 
             *  calculos  salfn_cobordos
             * 
             * 
             * 
             */
            // Calcular total y calculo saldo
            if ($FichaArriendo[0]->pago_garantia_propietario == 1) {
                if ($NumeroLiquidaciones === 0) {
                    $total_comisiones = calcularTotales($comision_administracion,  $comision_arriendo);
                    $saldo = calcularSaldo($sumAbonos, $comision_administracion, $comision_arriendo);
                }else{
                    $total_comisiones = calcularTotales($comision_arriendo);
                    $saldo = calcularSaldo($sumAbonos, $comision_arriendo);
                }

            } else {
                if ($NumeroLiquidaciones === 0) {
                    $total_comisiones = calcularTotales($comision_administracion,  $comision_arriendo);
                    $saldo = calcularSaldo($sumAbonos, $comision_administracion, $comision_arriendo);
                }else{
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

            // Mostrar la comisión de corretaje  -- se cambio el nombre del texto
            $totalComisionCorretaje = 0;
            if ($comision_administracion) {


                if ($NumeroLiquidaciones === 0) {

                    // Si tienes el porcentaje guardado en una variable, por ejemplo $porcentaje_administracion
                    $pdf->Cell(38, 6, date('d-m-Y'), 1, 0, 'C');
                    // Concatenar el porcentaje o el valor en el texto
                    $texto_comision_administracion = "COMISIÓN CORRETAJE (" . $adm_comision_monto  . "%)";
                    // Mostrar el texto con el valor concatenado
                    $pdf->Cell(58, 6, iconv('UTF-8', 'ISO-8859-1', $texto_comision_administracion), 1, 0, 'L');
                    // Mostrar el valor de la comisión en formato moneda
                    $pdf->Cell(58, 6, "$" . number_format($comision_administracion, 0, '', '.'), 1, 0, 'R');
                    $pdf->Cell(38, 6, "", 1, 1, 'R');

                    $totalComisionCorretaje =    ($comision_administracion + $iva_comision_administracion);
                    // guarda el calculo como ficha_arriendo_cta_cte_movimientos
                    GuardarMovimientosCtaCte(
                        $services,
                        $url_services,
                        $id_ficha_arriendo,
                        date("Y-m-d"),
                        date("H:i:s"),
                        10, // tipo movimiento
                        $totalComisionCorretaje ,
                        $saldo,
                        "COMISIÓN ARRIENDO.",
                        'false',
                        null,
                        $ficha_tecnica_propiedad,
                        'false',
                        null,
                        null,
                        null,
                        'false',
                        'false',
                        date("Y-m-d"),
                        $ficha_tecnica_propiedad,
                        null,
                        1,
                        "L",
                    );
                }
            }

            // Mostrar el IVA de la comisión de administración
            // if ($iva_comision_administracion > 0) {

            //     // Concatenar el porcentaje o el valor en el texto
            //     $texto_comision_administracion_iva = "IVA COMISIÓN CORRETAJE (" . $iva  . "%)";

            //     $pdf->Cell(38, 6, date('d-m-Y'), 1, 0, 'C');
            //     $pdf->Cell(58, 6, iconv('UTF-8', 'ISO-8859-1', $texto_comision_administracion_iva), 1, 0, 'L');
            //     $pdf->Cell(58, 6, "$" . number_format($iva_comision_administracion, 0, '', '.'), 1, 0, 'R');
            //     $pdf->Cell(38, 6, "", 1, 1, 'R');
            // }




            /***
             * 
             * 
             *  comision ADMINISTRACIÓN
             * 
             * 
             */

            // Mostrar la comisión de arriendo
            $TotaComsionConIva = 0;
            if ($comision_arriendo) {

                // Concatenar el porcentaje o el valor en el texto
                $texto_comision_arriendo_iva = "COMISIÓN ADMINISTRACIÓN (" . $arriendo_comision_monto . "%)";

                $pdf->Cell(38, 6, date('d-m-Y'), 1, 0, 'C');
                $pdf->Cell(58, 6, iconv('UTF-8', 'ISO-8859-1', $texto_comision_arriendo_iva), 1, 0, 'L');
                $pdf->Cell(58, 6, "$" . number_format($comision_arriendo, 0, '', '.'), 1, 0, 'R');
                $pdf->Cell(38, 6, "", 1, 1, 'R');

                // guarda el calculo como ficha_arriendo_cta_cte_movimientos
                $TotaComsionConIva = ($comision_arriendo + $iva_comision_arriendo);
                GuardarMovimientosCtaCte(
                    $services,
                    $url_services,
                    $id_ficha_arriendo,
                    date("Y-m-d"),
                    date("H:i:s"),
                    $id_tipo_movimiento_cta_cte = 10,
                    $TotaComsionConIva,
                    $saldo,
                    "COMISIÓN ADMINISTRACIÓN.",
                    'false',
                    NULL,
                    $ficha_tecnica_propiedad,
                    'false',
                    NULL,
                    NULL,
                    NULL,
                    'false',
                    'false',
                    date("Y-m-d"),
                    $ficha_tecnica_propiedad,
                    0,
                    1,
                    "L",
                );
            }

            // Mostrar el IVA de la comisión de arriendo
            // if ($iva_comision_arriendo > 0) {

            //     // Concatenar el porcentaje o el valor en el texto
            //     $texto_comision_arriendo_iva = "IVA COMISIÓN ADMINISTRACIÓN (" . $iva  . "%)";

            //     $pdf->Cell(38, 6, date('d-m-Y'), 1, 0, 'C');
            //     $pdf->Cell(58, 6, iconv('UTF-8', 'ISO-8859-1',      $texto_comision_arriendo_iva), 1, 0, 'L');
            //     $pdf->Cell(58, 6, "$" . number_format($iva_comision_arriendo, 0, '', '.'), 1, 0, 'R');
            //     $pdf->Cell(38, 6, "", 1, 1, 'R');
            // }






            // cobrar anticipos
            if ($saldo > 0) {

                CobrarAnticipos($services, $url_services, $ficha_tecnica_propiedad, $saldo);
            }

            if ($saldo > 0) {

                CobroRetenciones($services, $url_services, $ficha_tecnica_propiedad, $saldo);
            }

            /***
             * 
             * 
             *  garantias
             * 
             * 
             */


            // cobrar garantia
            if ($garantia > 0) {

                if ($cuotasPorPagar >  0) {

                    if ($FichaArriendo[0]->pago_garantia_propietario == 0) {
                        // Concatenar el porcentaje o el valor en el texto
                        $texto_comision_arriendo_iva = "GARANTIA";
                        $pdf->Cell(38, 6, "", 1, 0, 'L');
                        $pdf->Cell(58, 6, iconv('UTF-8', 'ISO-8859-1',      $texto_comision_arriendo_iva), 1, 0, 'L');
                        $pdf->Cell(58, 6, "$" . number_format($garantia, 0, '', '.'), 1, 0, 'R');
                        $pdf->Cell(38, 6, "", 1, 1, 'R');
                    }

                    // actualiza la cuota a pagado
                    PagarCuotaGarantia($services, $url_services, $idCuota);
                    // guarda el calculo de garantia como ficha_arriendo_cta_cte_movimientos
                    GuardarMovimientosCtaCte(
                        $services,
                        $url_services,
                        $id_ficha_arriendo,
                        date("Y-m-d"),
                        date("H:i:s"),
                        $id_tipo_movimiento_cta_cte = 12,
                        ($comision_arriendo + $iva_comision_arriendo),
                        $saldo,
                        "COBRO GARANTIA.",
                        'false',
                        0,
                        $ficha_tecnica_propiedad,
                        'false',
                        0,
                        0,
                        0,
                        'false',
                        'false',
                        date("Y-m-d"),
                        $ficha_tecnica_propiedad,
                        0,
                        3,
                        "L",
                    );
                }
            }






            // Mostrar totales
            $totales = ($total_comisiones +   $sumCargos);

            $pdf->Cell(38, 6, "", 1, 0, 'L');
            $pdf->SetFont('Arial', 'B', 8.5);
            $pdf->Cell(58, 6, "TOTALES:", 1, 0, 'R');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(58, 6, "$" . number_format($totales, 0, '', '.'), 1, 0, 'R');
            $pdf->Cell(38, 6, "$" . number_format($sumAbonos, 0, '', '.'), 1, 1, 'R');


            $totalSaldos = ($totales - $sumCargos);

            $pdf->Cell(38, 6, "", 1, 0, 'L');
            $pdf->SetFont('Arial', 'B', 8.5);
            $pdf->Cell(58, 6, "SALDO:", 1, 0, 'R');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(96, 6, "$" . number_format($totalSaldos, 0, '', '.'), 1, 1, 'R');

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
            $resultTest = GuardarLiquidacion(
                $services,
                $url_services,
                $ficha_tecnica_propiedad,
                $id_propietario,
                $precio,
                date("Y-m-d"),
                $id_ficha_arriendo,
                '',
                $TotaComsionConIva,
                $iva,
                $sumAbonos,
                $totales,
                $totalPagar
            );



            /**
             * 
             * 
             *  actualiza los estados a L (liquidado) en la cuenta corriente. 
             *  
             * 
             */

            ActualizarEstadosCuentasCorriente($services, $url_services, $ficha_tecnica_propiedad);


            /**
             * 
             * 
             *  actualiza con el id insertado de la ultima liquidacion todos los movimientos
             *  
             * 
             */
            ObtenerUltimoIDLiquidacion($services, $url_services, $ficha_tecnica_propiedad);
            ActualizarIDMovimientosCtaCte($services, $url_services, $id_liquidacion, $ficha_tecnica_propiedad);


        } else {
            $pdf->Cell(0, 10, "No se encontraron datos de la propiedad.", 0, 1);
        }

        // Mostrar el PDF en pantalla
        $pdf->Output('F', $nombreArchivo); // Guardar el archivo en el sistema de archivos

    } catch (\Throwable $th) {

        echo $th->getMessage();
    }
}

function procesarLiquidacion($ficha_tecnica_propiedad, $services, $url_services)
{

    try {

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

        // obtiene el numero de liquidaciones del arriendo
        $CantidadLiquidacionesArriendo = ObtenerCantidadLiquidaciones($services, $url_services, $ficha_tecnica_propiedad);
        $NumeroLiquidaciones =  $CantidadLiquidacionesArriendo[0]->cantidad_liquidaciones;

        // Obtener cantidad de cuotas por pagar
        $cuotasPorPagarGarantias = ObtenerCuotasPorPagarGarantia($services, $url_services, $id_ficha_arriendo);
        // if (empty($cuotasPorPagarGarantias) || empty($cuotasPorPagarGarantias[0]->cuotasnopagas)) {
        //     throw new Exception("Faltan datos: No se pudo obtener la cantidad de cuotas por pagar de la garantía.");
        // }
        $cuotasPorPagar = $cuotasPorPagarGarantias[0]->cuotasnopagas;


        // ultimo id liquidacion de la propiedad
        $UltimoIdLiquidacion = ObtenerUltimoIDLiquidacion($services, $url_services, $ficha_tecnica_propiedad);
        // if (empty($UltimoIdLiquidacion) || empty($UltimoIdLiquidacion[0]->id_liquidacion)) {
        //     throw new Exception("Faltan datos: No se pudo obtener el ultimo ID de la liquidacion.");
        // }
        $id_liquidacion = $UltimoIdLiquidacion[0]->id_liquidacion;



        // Array para almacenar los enlaces a los PDFs generados
        $enlacesPDFs = [];

        // Sumar abonos
        $TotalAbono = SumaAbonos($dataMovimientos);


        // Recorrer cada propietario para generar una liquidación separada
        foreach ($datosPropietarios as $propietario) {
            // Calcular comisiones y otros valores específicos para cada propietario
            $porcentajeParticipacion = $propietario->porcentaje_participacion_base / 100;

            // Calcular los montos ajustados para cada propietario según su porcentaje de participación
            $comision_administracion = calcularComisionAdmIVA($dataMovimientos[0]->fn_propiedades_por_liquidar[0]->precio, $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->adm_comision_monto, $iva, $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->adm_comision_id_moneda)[0] * $porcentajeParticipacion;
            $iva_comision_administracion = calcularComisionAdmIVA($dataMovimientos[0]->fn_propiedades_por_liquidar[0]->precio, $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->adm_comision_monto, $iva, $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->adm_comision_id_moneda)[1] * $porcentajeParticipacion;
            $comision_arriendo = calcularComisionArriendoIVA($TotalAbono, $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->arriendo_comision_monto, $iva, $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->arriendo_comision_id_moneda)[0] * $porcentajeParticipacion;
            $iva_comision_arriendo = calcularComisionArriendoIVA($TotalAbono, $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->arriendo_comision_monto, $iva, $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->arriendo_comision_id_moneda)[1] * $porcentajeParticipacion;

            // Calcular la garantía proporcional
            $garantia = calcularGarantia($dataMovimientos[0]->fn_propiedades_por_liquidar[0]->monto_garantia, $porcentajeParticipacion * 100);

            // Generar un nombre único para el PDF basado en el nombre del propietario
            $nombreArchivo = $carpetaPDFs . date('Y-m-dH:s:mm') . strtoupper($propietario->nombre_1 . '_' . $propietario->nombre_2 . '_' . $propietario->nombre_3) . '.pdf';

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
                $dataMovimientos[0]->fn_propiedades_por_liquidar[0]->num_cuotas_garantia,
                $cuotasPorPagar,
                $garantia,
                $id_ficha_arriendo,
                $nombreArchivo, // Pasar el nombre del archivo para guardarlo
                $FichaArriendo,
                $idCuota,
                $NumeroLiquidaciones,
                $id_liquidacion,
                $precio
            );

            // Agregar el enlace al archivo generado al array de enlaces
            $enlacesPDFs[] = $nombreArchivo;
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
            echo "<li><a class='pdf-link' href='$enlace' target='_blank'>Ver Liquidacion PDF</a></li>";
        }
        echo '</ul>';
        echo '</div>';
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}







// Ejecución del proceso de liquidación
$ficha_tecnica_propiedad = $_POST["ficha_tecnica"];
$archivoGenerado = procesarLiquidacion($ficha_tecnica_propiedad, $services, $url_services);


// registrar en la cuenta corriente todos los cobros que se hacen todos los calculos y comisiones
// guardar valores con iva incluido
// guardar los movimientos con cobro de adminsitracion
// una vez termina el proceso de lioquidacion queda en estado L de liquidado ficha_arriendo_cta_cte_movimientos
// el corretaje es sobre solo 1 pago






// actualizar todos los movimientos de la cuenta correinte una vez se inserte la liquidacion con el id insertado