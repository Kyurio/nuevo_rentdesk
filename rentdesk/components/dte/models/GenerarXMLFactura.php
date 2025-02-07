<?php
date_default_timezone_set('America/Santiago');


// Desactiva errores visibles en la salida JSON
ini_set('display_errors', 0);
error_reporting(0);

include("../../../app/model/QuerysBuilder.php");
include("../../../configuration.php");

use app\database\QueryBuilder;


$QueryBuilder = new QueryBuilder();
$config = new Config();

try {

    $idLiquidacion = $_POST['id_liquidacion'];

    // Definir la tabla y las condiciones
    // Tabla base y columnas especificadas
    $table = 'propiedades.propiedad_liquidaciones pl';
    $columns = "
        pl.id AS liquidacion,
        pl.id_ficha_propiedad AS ficha_propiedad,
        pl.id_propietario,
        per.dni AS dni,
        tre.nombre AS region,
        tco.nombre AS comuna,
        CASE WHEN per.id_tipo_persona = 1 THEN TRIM(pn.nombres) ELSE NULL END AS nombre,
        CASE WHEN per.id_tipo_persona = 1 THEN TRIM(pn.apellido_paterno) ELSE NULL END AS apellido_paterno,
        CASE WHEN per.id_tipo_persona = 1 THEN TRIM(pn.apellido_materno) ELSE NULL END AS apellido_materno,
        CASE WHEN per.id_tipo_persona <> 1 THEN TRIM(pj.razon_social) ELSE NULL END AS razon_social,
        UPPER(
            COALESCE(p.direccion, '') || ' ' || 
            COALESCE(p.numero, '') || 
            CASE 
                WHEN p.numero_depto IS NOT NULL AND p.numero_depto <> '' THEN ' Dpto ' || p.numero_depto 
                ELSE '' 
            END || 
            CASE 
                WHEN p.piso IS NOT NULL AND p.piso <> 0 THEN ' Piso ' || p.piso 
                ELSE '' 
            END
        ) AS direccion,
        p.numero AS numero,
        CASE WHEN p.numero_depto IS NOT NULL AND p.numero_depto <> '' THEN 'Dpto ' || p.numero_depto ELSE NULL END AS depto,
        CASE WHEN p.piso IS NOT NULL AND p.piso <> 0 THEN 'Piso ' || p.piso ELSE NULL END AS piso,
        TO_CHAR(pl.fecha_liquidacion::DATE, 'DD/MM/YYYY') AS fecha_liquidacion,
        pl.id_ficha_arriendo AS ficha_arriendo,
        pl.cierre AS cierre,
        pcl.iva AS iva,
        pcl.tipo_comision AS razon,
        pcl.monto AS monto,
        pcl.id_liquidacion,
        pl.porcentaje_participacion,
	    pcl.id AS id_liquidacion_comision,
        per_dir.direccion AS direccion_persona,
        tco_per.nombre AS comuna_persona

    ";

    // Definir los JOINs
    $joins = [
        [
            'type' => 'INNER',
            'table' => 'propiedades.propiedad_comision_liquidacion pcl',
            'on' => 'pcl.id_liquidacion = pl.id'
        ],
        [
            'type' => 'INNER',
            'table' => 'propiedades.propiedad p',
            'on' => 'p.id = pl.id_ficha_propiedad'
        ],
        [
            'type' => 'INNER',
            'table' => 'propiedades.persona per',
            'on' => 'per.id = pl.id_propietario'
        ],
        [
            'type' => 'INNER',
            'table' => 'propiedades.persona_direcciones per_dir',
            'on' => 'per.id = per_dir.id_persona'
        ],
        [
            'type' => 'LEFT',
            'table' => 'propiedades.persona_natural pn',
            'on' => 'pn.id_persona = pl.id_propietario'
        ],
        [
            'type' => 'LEFT',
            'table' => 'propiedades.persona_juridica pj',
            'on' => 'pj.id_persona = pl.id_propietario'
        ],
        [
            'type' => 'INNER',
            'table' => 'propiedades.tp_comuna tco',
            'on' => 'tco.id = p.id_comuna'
        ],
        [
            'type' => 'INNER',
            'table' => 'propiedades.tp_comuna tco_per',
            'on' => 'tco_per.id = per_dir.id_comuna'
        ],
        [
            'type' => 'INNER',
            'table' => 'propiedades.tp_region tre',
            'on' => 'tre.id = tco.id_region'
        ]
    ];

    // Condiciones WHERE
    $conditions = [
        'id_liquidacion' => $idLiquidacion,
        'tipo_comision' => ['IN', ['COMISIÓN CORRETAJE', 'COMISIÓN ARRIENDO', 'COMISIÓN ADMINISTRACIÓN']]
    ];


    // Llamada a `selectAdvanced`
    $resultadoQuery = $QueryBuilder->selectAdvanced(
        $table,
        $columns,
        $joins,
        $conditions,
        '',           // groupBy
        '',           // orderBy
        null,         // limit
        false,        // isCount
        false          // debug para ver el SQL generado
    );


    // url del servicio
    $url = $config->url_DTE;
    $fecha =  date('Y-m-d') . 'T' . date('H:i:s');
    $pdfFiles = []; // Almacenar rutas de los PDFs generados
    $responses = [];

    // Crear carpeta para guardar PDFs si no existe
    if (!file_exists('boletas')) {
        mkdir('boletas', 0777, true);
    }

    // genera el xml de la boleta
    function generarXMLFactura($data)
    {
        // Crear el objeto DOMDocument
        $dom = new DOMDocument('1.0', 'ISO-8859-1');
        $dom->formatOutput = true;

        // Crear el elemento raíz
        $dte = $dom->createElement('DTE');
        $dte->setAttribute('version', '1.0');
        $dte->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $dte->setAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $dte->setAttribute('xmlns', 'http://www.sii.cl/SiiDte');
        $dom->appendChild($dte);

        // Crear el Documento
        $documento = $dom->createElement('Documento');
        $documento->setAttribute('ID', 'R' . $data['rut_emisor'] . 'T' . $data['folio']); // Cambiar ID según el formato deseado
        $dte->appendChild($documento);

        // Crear el Encabezado
        $encabezado = $dom->createElement('Encabezado');
        $documento->appendChild($encabezado);

        // Crear IdDoc
        $idDoc = $dom->createElement('IdDoc');
        $encabezado->appendChild($idDoc);
        $idDoc->appendChild($dom->createElement('TipoDTE', 33));
        $idDoc->appendChild($dom->createElement('Folio', $data['folio']));
        $idDoc->appendChild($dom->createElement('FchEmis', $data['fch_emis']));
        $idDoc->appendChild($dom->createElement('FchVenc', $data['fecha_vencmimeinto']));

        // Crear Emisor
        $emisor = $dom->createElement('Emisor');
        $encabezado->appendChild($emisor);
        $emisor->appendChild($dom->createElement('RUTEmisor', $data['rut_emisor']));
        $emisor->appendChild($dom->createElement('RznSoc', $data['razon_social_emisor']));
        $emisor->appendChild($dom->createElement('GiroEmis', $data['giro_emisor']));
        $emisor->appendChild($dom->createElement('Acteco', '620200'));
        $emisor->appendChild($dom->createElement('DirOrigen', $data['dir_origen']));
        $emisor->appendChild($dom->createElement('CmnaOrigen', $data['comuna_origen']));
        $emisor->appendChild($dom->createElement('CiudadOrigen', $data['ciudad_origen']));

        // Crear Receptor
        $receptor = $dom->createElement('Receptor');
        $encabezado->appendChild($receptor);
        $receptor->appendChild($dom->createElement('RUTRecep', $data['rutrecep']));
        $receptor->appendChild($dom->createElement('RznSocRecep', $data['razon_social_receptor']));
        $receptor->appendChild($dom->createElement('GiroRecep', 'Actividades Inmobiliarias'));
        $receptor->appendChild($dom->createElement('DirRecep', $data['DirRecep']));
        $receptor->appendChild($dom->createElement('CmnaRecep', $data['CmnaRecep']));
        $receptor->appendChild($dom->createElement('CiudadRecep', $data['CiudadRecep']));

        // Crear Totales
        $totales = $dom->createElement('Totales');
        $encabezado->appendChild($totales);
        $totales->appendChild($dom->createElement('MntNeto', $data['mnt_bruto']));
        $totales->appendChild($dom->createElement('TasaIVA', '19.0'));
        $totales->appendChild($dom->createElement('IVA', $data['iva']));
        $totales->appendChild($dom->createElement('MntExe', '0'));
        $totales->appendChild($dom->createElement('MntTotal', $data['mnt_total']));

        // Crear Detalle
        $detalle = $dom->createElement('Detalle');
        $documento->appendChild($detalle);
        $detalle->appendChild($dom->createElement('NroLinDet', '1'));

        $cdgItem = $dom->createElement('CdgItem');
        $detalle->appendChild($cdgItem);
        $cdgItem->appendChild($dom->createElement('TpoCodigo', 'INTERNO'));
        $cdgItem->appendChild($dom->createElement('VlrCodigo', $data['cdg_item_valor']));

        $detalle->appendChild($dom->createElement('NmbItem', $data['nombre_item']));
        $detalle->appendChild($dom->createElement('DscItem', $data['descripcion_item']));
        $detalle->appendChild($dom->createElement('QtyItem', $data['cantidad_item']));
        $detalle->appendChild($dom->createElement('UnmdItem', 'UNI'));
        $detalle->appendChild($dom->createElement('PrcItem', $data['precio_item']));
        $detalle->appendChild($dom->createElement('MontoItem', $data['mnt_bruto']));

        // Imprimir el XML
        return $dom->saveXML();
    }

    function eliminarTildes($cadena)
    {
        $reemplazos = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'ñ' => 'n',
            'Ñ' => 'N'
        ];
        return strtr($cadena, $reemplazos);
    }


    foreach ($resultadoQuery as $index => $row) {


        $tipo_doc = 33; // 39: boletas, 33: factura, 61: nota crédito
        $rut = $config->rut;
        $rut_empresa = $config->rut_empresa;

        $url_DTE =  $config->url_DTE;
        $rut_certificado =  $config->rut_certificado;
        $rut_receptor =  $config->rut_receptor;
        $rut_sii =  $config->rut_sii;


        // Extrae el número de folio
        try {
            $Folio = new SoapClient(trim($url));
            $resultdte = $Folio->Solicitar_Folio(['RutEmpresa' => '77367969K', 'TipoDocto' => $tipo_doc]);
            $status_dte = $resultdte->Solicitar_FolioResult->Estatus;
            $NroFolio = $resultdte->Solicitar_FolioResult->Folio;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

        if (!$NroFolio) {
            //throw new Exception("");

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'errro',
                'message' => 'Folios agotados o con problemas para generar la factura.',

            ]);
            exit;
        } else {

            /**
             * 
             *  guarda el  folio y el tipo doc 
             * 
             */

            $table = "propiedades.propiedad_comision_liquidacion";
            $data = [
                "folio" => $NroFolio,
                "tipo_documento" => $tipo_doc,
            ];
            $conditions = [
                "id" => $row['id_liquidacion_comision'], // Condición de ejemplo
            ];


            $ResultadoUpdate = $QueryBuilder->update($table, $data, $conditions);

            if (!$ResultadoUpdate) {
                throw new Exception("Error al guardar el folio para id_liquidacion {$idLiquidacion}");
            } else {

                // Convertir y concatenar el nombre completo del propietario
                $NombrePropietario = mb_convert_encoding(
                    $row['nombre'] . " " . $row['apellido_paterno'] . " " . $row['apellido_materno'],
                    'UTF-8',
                    'ISO-8859-1'
                );

                $rutPropietario = $row['dni'];
                $cantidadItems = 1;
                $descripcionCobro = mb_convert_encoding($row['razon'], 'UTF-8', 'ISO-8859-1');
                $porcentaje_iva = $row['iva'];

                // Cálculos
                $porcentaje_participacion = $row['porcentaje_participacion'];
                $mnt_porcentaje = $row['monto'] * ($porcentaje_participacion / 100);

                $porcentaje_participacion = $row['porcentaje_participacion'];
                $porcentaje_iva = $row['iva'];
                // Calcular monto neto basado en la participación
                $monto_neto = $row['monto'] * ($porcentaje_participacion / 100);
                // Calcular IVA
                $monto_iva = round($monto_neto * ($porcentaje_iva / 100));
                // Calcular monto total bruto (neto + IVA)
                $mnt_bruto = $monto_neto + $monto_iva;
                $precio_item = $monto_neto;

                $CdgIntRecep = 1;
                $Contacto = isset($row['correo_electronico']) ? $row['correo_electronico'] : '';

                $row['direccion'] = preg_replace('/[^A-Za-z0-9\s]/', '', $row['direccion']);
                $DirPostal = $row['direccion'];

                $descripcion_item = $row['direccion'];

                // Convertir comuna y ciudad a UTF-8
                $CmnaPostal = mb_convert_encoding($row['comuna'], 'UTF-8', 'ISO-8859-1');
                $CiudadRecep = mb_convert_encoding($row['comuna'], 'UTF-8', 'ISO-8859-1');
                $comuna_persona = mb_convert_encoding($row['comuna_persona'], 'UTF-8', 'ISO-8859-1');

                $tipo_doc = 33; // 39: boletas, 33: factura, 61: nota crédito
                $rut = $config->rut;
                $rut_empresa = $config->rut_empresa;
                $url_DTE =  $config->url_DTE;
                $rut_certificado =  $config->rut_certificado;
                $rut_receptor =  $config->rut_receptor;
                $rut_sii =  $config->rut_sii;
                $razon_social_emisor = $config->razon_social_emisor;
                $giro_emisor = $config->giro_emisor;


                // Datos adicionales
                $razon_social_emisor = $config->razon_social_emisor;
                $giro_emisor = $config->giro_emisor; // Fuenzalida
                $dir_origen = $config->dir_origen;; // Fuenzalida
                $comuna_origen = $config->comuna_origen;
                $ciudad_origen = $config->ciudad_origen;
                $cdg_item_tipo = '';

                // Convertir comuna y ciudad a UTF-8
                $CmnaRecep = $row['comuna'];
                $DirRecep = $row['comuna'];
                $direccion_persona = $row['direccion_persona'];



                $razon_social_emisor = preg_replace('/[^A-Za-z0-9\s]/', '', $razon_social_emisor);
                $giro_emisor = preg_replace('/[^A-Za-z0-9\s]/', '', $giro_emisor);
                $dir_origen = preg_replace('/[^A-Za-z0-9\s]/', '', $dir_origen);
                $comuna_origen = preg_replace('/[^A-Za-z0-9\s]/', '', $comuna_origen);
                $ciudad_origen = preg_replace('/[^A-Za-z0-9\s]/', '', $ciudad_origen);
                $descripcionCobro = preg_replace('/[^A-Za-z0-9\s]/', '', $descripcionCobro);
                $descripcion_item = preg_replace('/[^A-Za-z0-9\s]/', '', $descripcion_item);
                $NombrePropietario = preg_replace('/[^A-Za-z0-9\s]/', '', $NombrePropietario);
                $DirPostal = preg_replace('/[^A-Za-z0-9\s]/', '', $DirPostal);
                $comuna_persona = preg_replace('/[^A-Za-z0-9\s]/', '', $comuna_persona);
                $DirRecep = preg_replace('/[^A-Za-z0-9\s]/', '', $DirRecep);


                $rut = str_replace(".","",$rut);
                $rut_certificado = str_replace(".","",$rut_certificado);
                $rut_sii = str_replace(".","",$rut_sii);
                $rutPropietario = str_replace(".","",$rutPropietario);


                // Datos de la factura
                $data = [
                    'rut_emisor' => $rut,
                    'rut_envia' => $rut_certificado,
                    'rut_receptor' => $rut_receptor,
                    'fch_resol' => '2014-08-22',
                    'nro_resol' => '80',
                    'folio' => $NroFolio,
                    'fch_emis' => date('Y-m-d'),
                    'razon_social_emisor' => eliminarTildes(strtoupper($razon_social_emisor)),
                    'giro_emisor' => eliminarTildes(strtoupper($giro_emisor)),
                    'dir_origen' => eliminarTildes(strtoupper($dir_origen)),
                    'comuna_origen' => eliminarTildes(strtoupper($comuna_origen)),
                    'ciudad_origen' => eliminarTildes(strtoupper($ciudad_origen)),
                    'nombre_item' =>  eliminarTildes(strtoupper($descripcionCobro)),
                    'descripcion_item' => eliminarTildes(strtoupper($descripcion_item)),
                    'cantidad_item' => $cantidadItems,
                    'precio_item' => $precio_item,
                    'mnt_bruto' => $monto_neto,
                    'iva' => $monto_iva,
                    'mnt_total' => $mnt_bruto,
                    'razon_social_receptor' => eliminarTildes(strtoupper($NombrePropietario)),
                    'fechahora' => $fecha,
                    'rutrecep' => $rutPropietario,
                    'CdgIntRecep' => eliminarTildes(strtoupper($CdgIntRecep)),
                    'Contacto' => '',
                    'DirPostal' => eliminarTildes(strtoupper($DirPostal)),
                    'CmnaPostal' => eliminarTildes(strtoupper($CmnaPostal)),
                    'fecha_vencmimeinto' => date('Y-m-d'),
                    'CiudadRecep' => eliminarTildes(strtoupper($comuna_persona)),
                    'CmnaRecep' => eliminarTildes(strtoupper($comuna_persona)),
                    'DirRecep' => eliminarTildes(strtoupper($direccion_persona)),
                    'cdg_item_tipo' => $cdg_item_tipo,
                    'cdg_item_valor' => $cdg_item_tipo,

                ];


                // Generar el XML
                $xml = generarXMLFactura($data);

                // envia el xml al web service para esperar una respuesta
                try {

                    /**
                     * 
                     *  actualiza el estado de pago
                     * 
                     */

                    // actualiza el estado de la liquidaciona  a pagado
                    $table = 'propiedades.propiedad_liquidaciones';
                    $data = [
                        'estado' => 1, // estado 1 es pagado
                    ];

                    $conditions = [
                        'id' => $idLiquidacion // Cambia esto según tu condición
                    ];


                    // Llamar a la función update
                    $result = $QueryBuilder->update($table, $data, $conditions);

                    /**
                     * 
                     * 
                     * envia el dte
                     * 
                     * 
                     */

                    try {

                        $factura = new SoapClient(trim($url));
                        $soap_data = array('ArchivoTXT' => $xml, 'TipoArchivo' => 'XML');
                        $resultdte2 = $factura->Carga_TXTDTE($soap_data);

                        if (!$resultdte2 || !isset($resultdte2->Carga_TXTDTEResult)) {
                            throw new Exception("Respuesta inválida del servicio SOAP.");
                        } else {

                            $pdfContent = $resultdte2->Carga_TXTDTEResult->PDF ?? null;

                            if (!$pdfContent) {
                                $errorMsg = $resultdte2->Carga_TXTDTEResult->MsgEstatus ?? "Error desconocido en el servicio.";
                                throw new Exception("Error en el servicio: $errorMsg");
                            } else {

                                // Respuesta exitosa
                                $responses[] = [
                                    'status' => 'success',
                                    'message' => 'Factura procesada correctamente para el folio: ' . $NroFolio,
                                ];

                            }
                        }
                    } catch (Exception $e) {

                        $errorMsg = $resultdte2->Carga_TXTDTEResult->MsgEstatus ?? "Error desconocido en el servicio.";
                        $responses[] = [
                            'status' => 'error',
                            'message' => "Error en el servicio: $errorMsg",
                        ];

                    }
                } catch (\Throwable $th) {
                    echo $th->getMessage();
                }
            }
        }
    } // end for 

    // imprime el resultado
    echo json_encode($responses); 

} catch (Exception $e) {
    // Manejo de errores generales
    header('Content-Type: application/json', true, 500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'error' => $e->getMessage(),
    ]);
}
