<?php

session_start();

require '../../../includes/fpdf/fpdf.php';
require '../../../includes/fpdf/morepagestable.php';
require 'PDF_MC_Table.php'; // Esta clase es útil si tienes celdas con múltiples líneas
include "../../../includes/sql_inyection.php";
include "../../../configuration.php";
include "../../../includes/funciones.php";
include "../../../includes/services_util.php";
require "../../../includes/re-code/phpqrcode/phpqrcode/phpqrcode.php";



$config = new Config;
$services = new ServicesRestful;
$url_services = $config->url_services;

//variables globales
$fecha = date('d-m-Y');
$token = $_GET['token'];


//obtenga la url del qr
$query = "SELECT id_propiedad from propiedades.ficha_arriendo WHERE token = '$token'";
$data = array("consulta" => $query);
$resultado = $services->sendPostDirecto($url_services . '/util/objeto', $data);
$id = json_decode($resultado, true); // Cambi


//genera la imagen de qr
$filename = 'qr_temp.png';
$contenidoQR = 'file:///home/kyaria/Descargas/factura-4.pdf';
QRcode::png($contenidoQR, $filename);

//obtenga el id de la pripiedad
$query = "SELECT id_propiedad from propiedades.ficha_arriendo WHERE token = '$token'";
$data = array("consulta" => $query);
$resultado = $services->sendPostDirecto($url_services . '/util/objeto', $data);
$id = json_decode($resultado, true); // Cambia aquí a true
$id_propiedad = $id[0]['id_propiedad'];


//obtenga el id de arriedno
$query = "SELECT id FROM propiedades.ficha_arriendo  where id_propiedad = $id_propiedad AND id_estado_contrato = 1";
$data = array("consulta" => $query);
$resultado = $services->sendPostDirecto($url_services . '/util/objeto', $data);
$id = json_decode($resultado, true); // Cambia aquí a true
$id_arriendo = $id[0]['id'];


// extrae los datos de los propietarios
$queryPropietarios = "SELECT pc.porcentaje_participacion_base, vp.nombre_1 ,vp.nombre_2 ,vp.nombre_3, pc.id_propietario  from propiedades.propiedad p
inner join propiedades.propiedad_copropietarios pc on p.id =pc.id_propiedad  and pc.habilitado = true
inner join propiedades.persona_propietario pp on pp.id_persona = pc.id_propietario
inner join propiedades.vis_propietarios vp on vp.id = pp.id_persona where nivel_propietario= 1 and p.id=" . $id_propiedad;
$data = array("consulta" => $queryPropietarios);
$resultado = $services->sendPostDirecto($url_services . '/util/objeto', $data);
$resultadoPropietarios = json_decode($resultado, true); // Cambia aquí a true
$objPropietarios = $resultadoPropietarios;



// extrae los datos de los arrendatarios
$queryArrendatario = "SELECT va.nombre_1 , va.nombre_2 , va.nombre_3, fa.id, num_cuotas_garantia  from propiedades.propiedad p
 inner join propiedades.ficha_arriendo fa  on p.id = fa.id_propiedad
 left join propiedades.ficha_arriendo_arrendadores faa on faa.id_ficha_arriendo = fa.id
 left join propiedades.vis_arrendatarios va on va.id = faa.id_arrendatario
 where p.id =$id_propiedad and fa.id_estado_contrato =1 ";
$data = array("consulta" => $queryArrendatario);
$resultado = $services->sendPostDirecto($url_services . '/util/objeto', $data);
$resultadoArrendatario = json_decode($resultado, true); // Cambia aquí a true


// obtenga las garantia de la propeidad 0 abono
$query = "SELECT id, fecha_movimiento, razon, monto, tipo_movimiento  
FROM propiedades.garantia_movimientos WHERE id_arriendo = $id_arriendo 
ORDER BY fecha_movimiento DESC";
$data = array("consulta" => $query);
$resultado = $services->sendPostDirecto($url_services . '/util/objeto', $data);
$resultadoAbono = json_decode($resultado, true); // Cambia aquí a true
$id_garantia = $id[0]['id'];



//datos de la propiedad
$queryPropiedad = "SELECT p.direccion as direccion, p.numero as numero, p.numero_depto , p.piso,
  tc.nombre as comuna, tr.nombre as region, tp.nombre as pais, p.codigo_propiedad as codigo_prop , p.id as id
  from propiedades.propiedad p
  inner join propiedades.propiedad_copropietarios pc on pc.id_propiedad = p.id
  left join  propiedades.persona_propietario pp on pc.id_propietario = pp.id_persona
  left join propiedades.persona ps on ps.id = pp.id_persona
  left join propiedades.persona_natural pn on pn.id_persona = ps.id
  left join propiedades.persona_juridica pj on pj.id_persona = ps.id
  inner join propiedades.tp_comuna tc on tc.id = p.id_comuna
  inner join propiedades.tp_region tr on tr.id = tc.id_region
  inner join propiedades.tp_pais tp on tp.id  = tr.id_pais  where  nivel_propietario = 1 and p.id=" . $id_propiedad;
$data = array("consulta" => $queryPropiedad);
$resultado = $services->sendPostDirecto($url_services . '/util/objeto', $data);
$resultadoPropiedad = json_decode($resultado, true);



// //Direccion de la propiedad
$propiedad = $resultadoPropiedad[0]['direccion'] . " #" . $resultadoPropiedad[0]['numero']  . ", "
    . $resultadoPropiedad[0]['comuna']  . ", " . $resultadoPropiedad[0]['direccion'] . ", " . $resultadoPropiedad[0]['pais'];;


foreach ($objPropietarios as $liq_por_pro) {



    $pdf = new PDF_MC_Table();

    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(0, 10, mb_convert_encoding("LIQUIDACIÓN DE GARANTIA", "ISO-8859-1", "UTF-8"), 0, 0, 'C');
    $pdf->Ln(7);
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell(0, 10, mb_convert_encoding("DEPARTAMENTO DE ADMINISTRACIONES", "ISO-8859-1", "UTF-8"), 0, 0, 'C');
    $pdf->Ln(15);
    $pdf->Cell(0, 10, mb_convert_encoding("FOLIO " . $id_garantia, "ISO-8859-1", "UTF-8"), 0, 0, 'L');
    $pdf->Ln(7);
    $pdf->Cell(0, 10, mb_convert_encoding("Fecha: " . $fecha, "ISO-8859-1", "UTF-8"), 0, 0, 'C');
    $pdf->Ln(9);

    // datos propiedad

    //obtiene  datos del propietario
    $pdf->SetFont('Arial', 'B', 8.5);
    $pdf->SetWidths(array(28, 82, 82));
    $pdf->SetAligns(array('C', 'C', 'C'));
    $pdf->SetFillColor(217, 237, 247);
    $pdf->Row(array("CODIGO", "PROPIEDAD", "ARRENDATARIO"), 7, 'C', true);
    $pdf->SetFont('Arial', '', 8);

    $i = 0; //Flag para ver si se repite y no repetir codigo ni nada
    foreach ($resultadoArrendatario as $item) {


        if ($i == 0) {
            $propiedad = mb_convert_encoding(strtoupper($propiedad), "ISO-8859-1", "UTF-8");
        } else {
            $codigo = ""; // Celda para el código
            $propiedad = ""; // Celda para la propiedad
        }
        $i++;
        $nombreCompleto = $item['nombre_1'] . " " . $item['nombre_2'] . " " . $item['nombre_3'] . " ";
        $nombreCompletoMayusculas = strtoupper($nombreCompleto);
        $arrenatario = mb_convert_encoding($nombreCompletoMayusculas, "ISO-8859-1", "UTF-8");
        $pdf->SetWidths(array(28, 82, 82));
        $pdf->SetAligns(array('C', 'L', 'L'));
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Row(array($id_propiedad, $propiedad, $arrenatario), 6.5, 'C', true);
    }


    // Configuración de la fuente
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'REPORTE MOVIMIENTOS GARANTIA', 0, 1, 'C');
    $pdf->Ln(5);





    // titulo detalle
    $pdf->Ln(9);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, mb_convert_encoding("DETALLE DE MOVIMIENTOS", "ISO-8859-1", "UTF-8"), 0, 0, 'L');

    $pdf->Ln(9);


    /// Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 8.5);
    $pdf->SetWidths(array(38, 58, 58, 38));
    $pdf->SetFillColor(217, 237, 247);
    $pdf->SetAligns(array('C', 'L', 'R', 'R'));
    $data = array("FECHA MOVIMIENTO", "DETALLE DEL PAGO", "CARGOS", "ABONOS");
    $pdf->Row($data, 7, '', true);




    // Inicializa la variable total
    $totalAbono = 0;
    $totalCargo = 0;


    // Verifica si hay resultados si hay los mouestra
    if (!empty($resultadoAbono) && is_array($resultadoAbono)) {
        foreach ($resultadoAbono as $row) {


            $pdf->SetFont('Arial', '', 7);

            if ($row['tipo_movimiento'] === 0) {  // 0 es abono 

                $totalAbono += $row['monto'];
                $Monotabono = '$' . number_format($totalAbono, 0, ',', '.');
            } else {
                $Monotabono = 0;
            }

            if ($row['tipo_movimiento'] === 1) {  // 1 es CARGOS

                $totalCargo += $row['monto'];
                $MonoDescuento = '$' . number_format($totalCargo, 0, ',', '.');
            } else {
                $MonoDescuento = "0";
            }



            // Insertar el QR code en el PDF
            $qrSize = 30; // Tamaño del QR en mm
            $pageWidth = $pdf->GetPageWidth();
            $pageHeight = $pdf->GetPageHeight();
            $qrX = ($pageWidth - $qrSize) / 2; // Centra horizontalmente
            $qrY = $pageHeight - $qrSize - 10; // Posición en Y (10 mm de margen inferior)
            $pdf->Image($filename, $qrX, $qrY, $qrSize);





            $pdf->Row(array(
                (new DateTime($row['fecha_movimiento']))->format('d-m-Y'),
                strtoupper($row['razon']),
                $Monotabono,
                $MonoDescuento
            ), 6, array('L', 'L', 'R', 'R'), '', true); // Especifica las alineaciones aquí



        }
    } else {
        $pdf->Cell(0, 10, 'No hay datos disponibles', 0, 1, 'C');
    }

    // totales
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Cell(38, 6, "", 1, 0, 'L', true);
    $pdf->SetFont('Arial', 'B', 8.5);
    $pdf->Cell(58, 6, "TOTAL:", 1, 0, 'R', true);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(58, 6, "" . number_format($totalAbono, 0, '', '.'), 1, 0, 'R', true);
    $pdf->Cell(38, 6, "" . number_format($totalCargo, 0, '', '.'), 1, 1, 'R', true);


    $pdf->Cell(38, 6, "", 1, 0, 'L', true);
    $pdf->SetFont('Arial', 'B', 8.5);
    $pdf->Cell(58, 6, mb_convert_encoding("SALDO:", "ISO-8859-1", "UTF-8"), 1, 0, 'R', true);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(96, 6, "" . number_format(($totalAbono - $totalCargo), 0, '', '.'), 1, 0, 'R', true);
}
// Salida del PDF
$pdf->Output('I', 'reporte_movimientos_garantia.pdf');
