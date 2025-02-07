<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../app/model/QuerysBuilder.php");
include("../../../configuration.php");
use app\database\QueryBuilder;


$QueryBuilder = new QueryBuilder();


$idLiquidacion = $_POST['id_liquidacion'];
$url = $url = $config->url_DTE; // URL del servicio SOAP


try {

    $table = 'propiedades.propiedad_liquidaciones pl';
    $columns = '
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
    UPPER(p.direccion) AS direccion,
    p.numero AS numero,
    CASE WHEN p.numero_depto IS NOT NULL AND p.numero_depto <> \'\' THEN CONCAT(\'Dpto \', p.numero_depto) ELSE NULL END AS depto,
    CASE WHEN p.piso IS NOT NULL AND p.piso <> 0 THEN CONCAT(\'Piso \', p.piso) ELSE NULL END AS piso,
    TO_CHAR(pl.fecha_liquidacion::DATE, \'DD/MM/YYYY\') AS fecha_liquidacion,
    pl.id_ficha_arriendo AS ficha_arriendo,
    pl.cierre AS cierre,
    pcl.iva AS iva,
    pcl.tipo_comision AS razon,
    pcl.monto AS monto,
    pcl.id_liquidacion
';

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
            'table' => 'propiedades.tp_region tre',
            'on' => 'tre.id = tco.id_region'
        ]
    ];

    // Condiciones WHERE
    $conditions = [
        'id_liquidacion' => $idLiquidacion,
        'tipo_comision' => ['IN', ['COMISIÓN CORRETAJE', 'COMISIÓN ADMINISTRACIÓN']]
    ];

    // Llamada a `selectAdvanced`
    $resultado = $QueryBuilder->selectAdvanced(
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


    // extrae el numero de forlio
    try {

        $Folio = new SoapClient(trim($url));
        $resultdte = $Folio->Solicitar_Folio(array('RutEmpresa' => '77367969K', 'TipoDocto' => $tipo_doc));
        $status_dte = $resultdte->Solicitar_FolioResult->Estatus;
        $NroFolio = $resultdte->Solicitar_FolioResult->Folio;
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }


    // captura de datos
    $datosFinales = []; // Array para almacenar los resultados procesados de cada fila

    for ($i = 0; $i < count($resultado); $i++) {
        $tipo_doc = 61; // 39: boletas, 33: factura, 61: nota crédito

        $rut = $config->rut;
        $rut_empresa = $config->rut_empresa;

        // Convertir y concatenar el nombre completo del propietario
        $NombrePropietario = mb_convert_encoding(
            $resultado[$i]['nombre'] . " " . $resultado[$i]['apellido_paterno'] . " " . $resultado[$i]['apellido_materno'],
            'UTF-8',
            'ISO-8859-1'
        );

        $rutPropietario = $resultado[$i]['dni'];
        $cantidadItems = 1;
        $descripcionCobro =  mb_convert_encoding($resultado[$i]['razon'], 'UTF-8', 'ISO-8859-1');
        $precio_item = 0;
        $mnt_neto = $resultado[$i]['monto'];
        $iva = $resultado[$i]['iva'];
        $mnt_total = $iva + $mnt_neto;
        $CdgIntRecep = 1;
        $Contacto = isset($resultado[$i]['correo_electronico']) ? $resultado[$i]['correo_electronico'] : '';
        $DirPostal = $resultado[$i]['direccion'];

        // Convertir comuna y ciudad a UTF-8
        $CmnaPostal = mb_convert_encoding($resultado[$i]['comuna'], 'UTF-8', 'ISO-8859-1');
        $CiudadRecep = mb_convert_encoding($resultado[$i]['comuna'], 'UTF-8', 'ISO-8859-1');

        // Datos adicionales
        $CmnaRecep = '';
        $DirRecep = '';
        $razon_social_emisor = '';
        $giro_emisor = ''; // Fuenzalida
        $dir_origen = ''; // Fuenzalida
        $comuna_origen = '';
        $ciudad_origen = '';

        // Guardar los datos procesados en el array $datosFinales
        $datosFinales[] = [
            'tipo_doc' => $tipo_doc,
            'rut' => $rut,
            'rut_empresa' => $rut_empresa,
            'NombrePropietario' => $NombrePropietario,
            'rutPropietario' => $rutPropietario,
            'cantidadItems' => $cantidadItems,
            'descripcionCobro' => $descripcionCobro,
            'precio_item' => $precio_item,
            'mnt_neto' => $mnt_neto,
            'iva' => $iva,
            'mnt_total' => $mnt_total,
            'CdgIntRecep' => $CdgIntRecep,
            'Contacto' => $Contacto,
            'DirPostal' => $DirPostal,
            'CmnaPostal' => $CmnaPostal,
            'CiudadRecep' => $CiudadRecep,
            'CmnaRecep' => $CmnaRecep,
            'DirRecep' => $DirRecep,
            'razon_social_emisor' => $razon_social_emisor,
            'giro_emisor' => $giro_emisor,
            'dir_origen' => $dir_origen,
            'comuna_origen' => $comuna_origen,
            'ciudad_origen' => $ciudad_origen
        ];
    }


    // Datos de la factura
    $data = [

        'rut_emisor' => $rut, // rut fuenzalida
        'rut_envia' => '6285461-8', // rut certificado
        'rut_receptor' => '60803000-K', // rut sii
        'fch_resol' => '2014-08-22',
        'nro_resol' => '80',
        'folio' => $NroFolio,
        'fch_emis' => date('Y-m-d'),
        'razon_social_emisor' => $razon_social_emisor,
        'giro_emisor' => $giro_emisor,
        'dir_origen' =>  $dir_origen,
        'comuna_origen' => $comuna_origen,
        'ciudad_origen' => $ciudad_origen,
        'nombre_item' =>   $descripcionCobro,
        'cantidad_item' => $cantidadItems,
        'precio_item' => $precio_item,
        'mnt_neto' => $mnt_neto,
        'iva' => $iva,
        'mnt_total' => $mnt_total,
        'razon_social_receptor' => $NombrePropietario, //nombre del propietario
        'fechahora' => $fecha,
        'rutrecep' => $rutPropietario, // rut del pripietario  
        'CdgIntRecep' => '1',
        'Contacto' => '', // datos de contacto
        'DirPostal' => $DirPostal,
        'CmnaPostal' => $CmnaPostal,
        'fecha_vencmimeinto'  => date('Y-m-d'), //
        'CiudadRecep' => $CiudadRecep,
        'CmnaRecep' => $CmnaRecep,
        'DirRecep' => $DirRecep,
    ];

    // genera el xml de la factura
    function generarXMLNotaCredito($NroFolio)
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
        $documento->setAttribute('ID', 'R77367969-KT61F' . $NroFolio);; // Cambiar ID según el formato deseado
        $dte->appendChild($documento);

        // Crear el Encabezado
        $encabezado = $dom->createElement('Encabezado');
        $documento->appendChild($encabezado);

        // Crear IdDoc
        $idDoc = $dom->createElement('IdDoc');
        $encabezado->appendChild($idDoc);
        $idDoc->appendChild($dom->createElement('TipoDTE', '61')); // Tipo de DTE para nota de crédito
        $idDoc->appendChild($dom->createElement('Folio', $NroFolio));
        $idDoc->appendChild($dom->createElement('FchEmis', '2024-08-09')); // Cambiar según la fecha deseada
        $idDoc->appendChild($dom->createElement('FchVenc', '2024-08-09')); // Cambiar según la fecha deseada
        $idDoc->appendChild($dom->createElement('IndServicio', '3')); // Agregar el indicador de servicio

        // Crear Emisor
        $emisor = $dom->createElement('Emisor');
        $encabezado->appendChild($emisor);
        $emisor->appendChild($dom->createElement('RUTEmisor', '77367969-K'));
        $emisor->appendChild($dom->createElement('RznSoc', 'Fuenzalida Rentas Inmobiliarias Spa'));
        $emisor->appendChild($dom->createElement('GiroEmis', 'Negocios Inmobiliarios'));
        $emisor->appendChild($dom->createElement('Acteco', '682000'));
        $emisor->appendChild($dom->createElement('DirOrigen', 'Av. Andres Bello 2777 Oficina 1902'));
        $emisor->appendChild($dom->createElement('CmnaOrigen', 'Las Condes'));

        // Crear Receptor
        $receptor = $dom->createElement('Receptor');
        $encabezado->appendChild($receptor);
        $receptor->appendChild($dom->createElement('RUTRecep', '12224377-K'));
        $receptor->appendChild($dom->createElement('RznSocRecep', 'SERGIO CRISTIAN GOMEZ MARTINEZ'));
        $receptor->appendChild($dom->createElement('GiroRecep', 'Persona Natural'));
        $receptor->appendChild($dom->createElement('DirRecep', 'CHILE ESPANA 394 Departamento DEPTO. 101- BX 7 BD 3'));
        $receptor->appendChild($dom->createElement('CmnaRecep', 'Nunoa'));
        $receptor->appendChild($dom->createElement('CiudadRecep', 'Nunoa'));

        // Crear Totales
        $totales = $dom->createElement('Totales');
        $encabezado->appendChild($totales);
        $totales->appendChild($dom->createElement('MntNeto', '32661'));
        $totales->appendChild($dom->createElement('MntExe', '0'));
        $totales->appendChild($dom->createElement('TasaIVA', '19.0'));
        $totales->appendChild($dom->createElement('IVA', '6206'));
        $totales->appendChild($dom->createElement('MntTotal', '38867'));

        // Crear Detalle
        $detalle = $dom->createElement('Detalle');
        $documento->appendChild($detalle);
        $detalle->appendChild($dom->createElement('NroLinDet', '1'));
        $detalle->appendChild($dom->createElement('NmbItem', 'Boleta comision de administracion'));
        $detalle->appendChild($dom->createElement('DscItem', 'CHILE ESPANA 394 Departamento DEPTO. 101- BX 7 BD 3'));
        $detalle->appendChild($dom->createElement('QtyItem', '1.0'));
        $detalle->appendChild($dom->createElement('PrcItem', '32661.0'));
        $detalle->appendChild($dom->createElement('MontoItem', '32661'));

        // Crear Referencia
        $referencia = $dom->createElement('Referencia');
        $documento->appendChild($referencia);
        $referencia->appendChild($dom->createElement('NroLinRef', '1'));
        $referencia->appendChild($dom->createElement('TpoDocRef', '39')); // Tipo de documento de referencia
        $referencia->appendChild($dom->createElement('FolioRef', '77')); // Folio de referencia
        $referencia->appendChild($dom->createElement('FchRef', '2024-08-09')); // Fecha de referencia
        $referencia->appendChild($dom->createElement('CodRef', '1')); // Código de referencia
        $referencia->appendChild($dom->createElement('RazonRef', 'No corresponde pago ni emision')); // Razón de referencia

        // Crear TED
        $ted = $dom->createElement('TED');
        $ted->setAttribute('version', '1.0');
        $documento->appendChild($ted);
        $dd = $dom->createElement('DD');
        $ted->appendChild($dd);
        $dd->appendChild($dom->createElement('RE', '77367969-K'));
        $dd->appendChild($dom->createElement('TD', '61'));
        $dd->appendChild($dom->createElement('F', $NroFolio));
        $dd->appendChild($dom->createElement('FE', '2024-08-02'));
        $dd->appendChild($dom->createElement('RR', '12224377-K'));
        $dd->appendChild($dom->createElement('RSR', 'SERGIO CRISTIAN GOMEZ MARTINEZ'));
        $dd->appendChild($dom->createElement('MNT', '38867'));
        $dd->appendChild($dom->createElement('IT1', 'Boleta comision de administracion'));

        // Crear CAF
        $caf = $dom->createElement('CAF');
        $caf->setAttribute('version', '1.0');
        $dd->appendChild($caf);
        $da = $dom->createElement('DA');
        $caf->appendChild($da);
        $da->appendChild($dom->createElement('RE', '77367969-K'));
        $da->appendChild($dom->createElement('RS', 'MEDITERRANEO RENTAS INMOBILIARIAS SPA'));
        $da->appendChild($dom->createElement('TD', '61'));
        $rng = $dom->createElement('RNG');
        $da->appendChild($rng);
        $rng->appendChild($dom->createElement('D', '1037'));
        $rng->appendChild($dom->createElement('H', '1053'));
        $da->appendChild($dom->createElement('FA', '2024-07-05'));
        $rsapk = $dom->createElement('RSAPK');
        $caf->appendChild($rsapk);
        $rsapk->appendChild($dom->createElement('M', 'raG4OrRu8vfeR8z7LJHi6dlpwMzv100wd/caoEUHCrVwP2SxQvyJt8gSMJDLnSGJLzIk38r7N38SqEwLKcIF4Q=='));
        $rsapk->appendChild($dom->createElement('E', 'Aw=='));
        $da->appendChild($dom->createElement('IDK', '300'));

        // Agregar TSTED y FRMT
        $dd->appendChild($dom->createElement('TSTED', '2024-08-02T12:59:04'));
        $ted->appendChild($dom->createElement('FRMT', 'ZHuBDfj65rpmLgdDqvU1BIKE1gaPvZU+V1l2dnf0eup9LjULech55C9ZLMwL/mr6G1FHjOun06wULLH8FQYHSw=='));

        // Imprimir el XML
        return $dom->saveXML();
    }


    // ejecuta la funcion ppara crear el xml de la factura
    $xml = generarXMLNotaCredito($NroFolio);

    // envia el xml al web service para esperar una respuesta
    try {

        $factura = new SoapClient(trim($url));
        $soap_data = array('ArchivoTXT' => $xml, 'TipoArchivo' => 'XML');
        $resultdte2 = $factura->Carga_TXTDTE($soap_data);
        $status_dte2 = $resultdte2->Carga_TXTfacturaResult->Estatus;
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }

    if ($resultdte2) {


        echo true;
    } else {
        echo false;
    }
} catch (\Throwable $th) {

    echo $th->getMessage();
}
