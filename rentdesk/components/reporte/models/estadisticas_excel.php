<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$url_reportes_eje = $config->url_reportes_eje;

$id_company 	= $_SESSION["rd_company_id"]; 
//$rol_usuario 	= $_SESSION["usuario_rol"];
$id_usuario    	= $_SESSION["rd_usuario_id"];


/////////////////////////José




$num_reg = 10000;
$inicio = 0;
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;


if(isset($_POST['fechaInicioHidden']) && isset($_POST['fechaTerminoHidden'])){
    $fecha_inicio=$_POST['fechaInicioHidden'];
	$fecha_termino=$_POST['fechaTerminoHidden'];
$query_mov_comiciones=	"SELECT 
    tipo_comision , pcl.monto, pcl.iva
FROM 
    propiedades.propiedad_liquidaciones pl 
INNER JOIN  
    propiedades.propiedad_comision_liquidacion pcl 
ON 
    pl.id = pcl.id_propiedad_liquidacion 
    WHERE pl.fecha_liquidacion BETWEEN '$fecha_inicio' AND '$fecha_termino'
";

}
else{

$query_mov_comiciones=	"SELECT 
    tipo_comision , pcl.monto, pcl.iva
FROM 
    propiedades.propiedad_liquidaciones pl 
INNER JOIN  
    propiedades.propiedad_comision_liquidacion pcl 
ON 
    pl.id = pcl.id_propiedad_liquidacion 
";

}


$data = array("consulta" => $query_mov_comiciones, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objComision = json_decode($resultado);


require_once "../../../includes/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Tipo Comision');
$sheet->setCellValue('B1', 'Monto');
$sheet->setCellValue('C1', 'Iva');
$i=2;
foreach ($objComision as $comisiones){
    $sheet->setCellValue('A'.$i, ''.$comisiones->tipo_comision);
    $sheet->setCellValue('B'.$i, '$'.number_format($comisiones->monto, 0, ',', '.'));
    $sheet->setCellValue('C'.$i, '$'.number_format($comisiones->iva, 0, ',', '.'));
    $i++;
}
// Generar el archivo Excel en memoria
$writer = new Xlsx($spreadsheet);

// Configurar los encabezados HTTP para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="archivo.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1'); // Evitar caching en algunos navegadores

// Enviar el archivo Excel al navegador
$writer->save('php://output');
exit;

?>