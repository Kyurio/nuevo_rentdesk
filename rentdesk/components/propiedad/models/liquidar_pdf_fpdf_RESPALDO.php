<?php
require('../../../includes/fpdf/fpdf.php');
require('../../../includes/fpdf/morepagestable.php');
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
date_default_timezone_set('America/Santiago');
$valor_comision_administracion = 0;
/******************************Consulta Valores configuracion Subsidiaria************************************ */


$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_subsidiaria = $current_subsidiaria->id;

$num_reg = 50;
$inicio = 0;
$cant_rows = $num_reg;
$queryConfig = "SELECT * FROM propiedades.tp_configuracion_subsidiaria where id_subisidiaria=".$id_subsidiaria;
$num_pagina = round($inicio / $cant_rows) + 1;
  $data = array("consulta" => $queryConfig, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
  $resultadoCuentas = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
  $objConfig = json_decode($resultadoCuentas);
  $objeto = $objConfig[0]; // Accede al primer elemento del array
  $iva = $objeto->iva;

/************************CONSUNLTA INFO DE LA PROPIEDAD************************************* */
$ficha_tecnica_propiedad= $_POST["ficha_tecnica"];
$queryPropiedad = "  select p.direccion as direccion, p.numero as numero, p.numero_depto , p.piso,
  tc.nombre as comuna, tr.nombre as region, tp.nombre as pais, p.codigo_propiedad as codigo_prop , p.id as id 
  from propiedades.propiedad p
  inner join propiedades.propiedad_copropietarios pc on pc.id_propiedad = p.id 
  left join  propiedades.persona_propietario pp on pc.id_propietario = pp.id_persona 
  left join propiedades.persona ps on ps.id = pp.id_persona 
  left join propiedades.persona_natural pn on pn.id_persona = ps.id 
  left join propiedades.persona_juridica pj on pj.id_persona = ps.id 
  inner join propiedades.tp_comuna tc on tc.id = p.id_comuna 
  inner join propiedades.tp_region tr on tr.id = tc.id_region 
  inner join propiedades.tp_pais tp on tp.id  = tr.id_pais  where  nivel_propietario = 1 and p.id=".$ficha_tecnica_propiedad;
$num_reg = 50;
$inicio = 0;
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryPropiedad, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objPropiedad = json_decode($resultado);
$objeto = $objPropiedad[0]; // Accede al primer elemento del array
$id = $objeto->id;

//Direccion de la propiedad
$propiedad = $objeto->direccion." #".$objeto->numero. ", "
.$objeto->comuna .", ".$objeto->region. ", ".$objeto->pais;

/************************CONSUNLTA INFO DEL ARRENDATARIO************************************* */

$queryArrendatario =" select va.nombre_1 , va.nombre_2 , va.nombre_3, fa.id  from propiedades.propiedad p 
 inner join propiedades.ficha_arriendo fa  on p.id = fa.id_propiedad 
 left join propiedades.ficha_arriendo_arrendadores faa on faa.id_ficha_arriendo = fa.id 
 left join propiedades.vis_arrendatarios va on va.id = faa.id_arrendatario 
 where p.id =$ficha_tecnica_propiedad and fa.id_estado_contrato =1 ";

$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryArrendatario, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objArrendatario = json_decode($resultado);
$objetoArrendatario = $objArrendatario[0];

$id_ficha_arriendo = $objetoArrendatario-> id;//Ficha Arriedo


/************************CONSUNLTA INFO DE LOS MOVIMIENTOS************************************* */

$queryMovimientos ="SELECT * FROM (
    SELECT 
        ccm.id, 
        ccm.id_ficha_arriendo, 
        ccm.fecha_movimiento, 
        ccm.hora_movimiento, 
        ccm.id_tipo_movimiento_cta_cte, 
        ccm.monto,
        ccm.razon,
        ccm.cobro_comision,
        ttm.id AS id_tipo_movimiento,
        SUM(CASE WHEN ttm.id = 1 THEN ccm.monto ELSE 0 END) AS haber,
        SUM(CASE WHEN ttm.id = 2 THEN ccm.monto ELSE 0 END) AS debe,
        SUM(CASE WHEN ttm.id = 1 THEN ccm.monto ELSE ccm.monto END) OVER (PARTITION BY ccm.id_ficha_arriendo ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) AS saldo,
        CASE WHEN SUM(CASE WHEN ttm.id = 1 THEN ccm.monto ELSE ccm.monto END) OVER (PARTITION BY ccm.id_ficha_arriendo ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) >= 0
        THEN '+' ELSE '-' END AS signo_saldo
    FROM 
        propiedades.ficha_arriendo_cta_cte_movimientos ccm
    INNER JOIN 
        propiedades.tp_tipo_movimiento_cta_cte ttmcc ON ccm.id_tipo_movimiento_cta_cte = ttmcc.id
    INNER JOIN 
        propiedades.tp_tipo_movimiento ttm ON ttmcc.id_tipo_movimiento = ttm.id
    WHERE 
        ccm.id_ficha_arriendo = $id_ficha_arriendo
        and id_tipo_movimiento_cta_cte != 2
    GROUP BY 
        ccm.id, 
        ccm.id_ficha_arriendo, 
        ccm.fecha_movimiento, 
        ccm.hora_movimiento, 
        ccm.id_tipo_movimiento_cta_cte, 
        ccm.monto,
        ttm.id
) subquery 
WHERE id_ficha_arriendo = $id_ficha_arriendo
ORDER BY id DESC";
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryMovimientos, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objMovimientos = json_decode($resultado);

/***********************SQL PROPIETARIOS****************** */

$queryPropietarios ="select pc.porcentaje_participacion_base, vp.nombre_1 ,vp.nombre_2 ,vp.nombre_3  from propiedades.propiedad p  
inner join propiedades.propiedad_copropietarios pc on p.id =pc.id_propiedad 
inner join propiedades.persona_propietario pp on pp.id_persona = pc.id_propietario 
inner join propiedades.vis_propietarios vp on vp.id = pp.id_persona where nivel_propietario= 1 and p.id=".$ficha_tecnica_propiedad;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryPropietarios, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objPropietarios = json_decode($resultado);




/******************SQL COMISIONES*********************** */

$queryComision = "SELECT precio, arriendo_comision_cobro, adm_comision_cobro,
 ttm1.nombre AS nombre_arriendo, arriendo_comision_id_moneda, arriendo_comision_monto, 
       ttm2.nombre AS nombre_adm, adm_comision_id_moneda, adm_comision_monto 
FROM propiedades.ficha_arriendo fa
INNER JOIN propiedades.tp_tipo_moneda ttm1 ON ttm1.id = fa.arriendo_comision_id_moneda
INNER JOIN propiedades.tp_tipo_moneda ttm2 ON ttm2.id = fa.adm_comision_id_moneda
where  fa.id =".$id_ficha_arriendo;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryComision, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objComision = json_decode($resultado);
$objeto = $objComision[0]; // Accede al primer elemento del array
$id_tp_arriendo_comision = $objeto->arriendo_comision_id_moneda;/////Datos comision por arriendo
$monto_arriendo_comision =$objeto->arriendo_comision_monto;/////Datos comision por arriendo
$arriendo_comision_cobro = $objeto->arriendo_comision_cobro;/////Datos comision por arriendo
$id_tp_administracion_comision = $objeto->adm_comision_id_moneda;/////Datos comision por Administracion
$monto_administracion_comision =$objeto->adm_comision_monto;/////Datos comision por Administracion
$adm_comision_cobro = $objeto->adm_comision_cobro;/////Datos comision por Administracion

$precio = $objeto->precio;

$sql_existe_cobro_comision_arriendo ="select count(pcl.id) as cantidad_comision from propiedades.propiedad_comision_liquidacion pcl 
inner join propiedades.propiedad_liquidaciones pl on pl.id  = pcl.id_propiedad_liquidacion 
where  tipo_comision = 'Arriendo'  and pl.id_ficha_propiedad = $ficha_tecnica_propiedad and pl.id_ficha_arriendo = $id_ficha_arriendo";
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $sql_existe_cobro_comision_arriendo, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objPropiedad = json_decode($resultado);
$objeto = $objPropiedad[0]; // Accede al primer elemento del array
$cantidad_comision = $objeto->cantidad_comision;

if($cantidad_comision >= 1 ){

  $arriendo_comision_cobro = 0;
}
if($arriendo_comision_cobro == 1){ //////// Validador si se cobra comision al arrendar (primer arriendo)
    if($id_tp_arriendo_comision == 1){
      $valor_comision_arriendo =  ($monto_arriendo_comision / 100) * $precio;
    }
}


 
////////////////////////////////////////////Variables
$folio = "0000000444325";
$fechaActual = new DateTime();
$fecha = $fechaActual->format('d/m/Y');
$codigo =$id ;

$num_documento = "B-0000029976";

$tipo_transaccion = "Dep.Elec.C.Cte.";

$monto = "500.273";

$suma_cargos = "52.637";
/**************************************** */
$pdf=new PDF('P', 'mm', 'Letter');
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Image('../../../upload/logos/logo-rental-parther-fuenzalida.png',10,10,50);
$pdf->Ln(20);
$pdf->SetFont('Arial','B',20);
$pdf->Cell(0,10,  mb_convert_encoding("LIQUIDACIÓN DE ARRIENDO", "ISO-8859-1", "UTF-8")  ,0,0,'C');
$pdf->Ln(7);
$pdf->SetFont('Arial','B',13);
$pdf->Cell(0,10,  mb_convert_encoding("DEPARTAMENTO DE ADMINISTRACIONES", "ISO-8859-1", "UTF-8")  ,0,0,'C');
$pdf->Ln(15);
$pdf->Cell(0,10, mb_convert_encoding("FOLIO ".$folio, "ISO-8859-1", "UTF-8") ,0,0,'L');
$pdf->Ln(7);
$pdf->Cell(0,10,  mb_convert_encoding("Fecha: ".$fecha, "ISO-8859-1", "UTF-8")  ,0,0,'C');
$pdf->Ln(9);
$pdf->SetFont('Arial','B',8.5);
$pdf->SetFillColor(217, 237, 247);
$pdf->Cell(28,6,"CODIGO",1,0,'C',true);
$pdf->Cell(82,6,"PROPIEDAD",1,0,'C',true);
$pdf->Cell(82,6,"Arrendatario",1,1,'C',true);

/*
$pdf->Cell(68,6,"PROPIETARIO",1,0,'C',true);
$pdf->Cell(28,6,"%PART",1,1,'C',true);
*/
$pdf->SetFont('Arial','',8);

$i=0; //Flag para ver si se repite y no repetir codigo ni nada
foreach($objArrendatario as $item){
    if($i ==0){
$pdf->Cell(28,6,$codigo,1,0,'C'); // Celda para el código
$pdf->Cell(82,6,  mb_convert_encoding($propiedad, "ISO-8859-1", "UTF-8")  ,1,0,'L'); // Celda para la propiedad
}
else{
  $pdf->Cell(28,6,"",1,0,'C'); // Celda para el código
$pdf->Cell(82,6,"",1,0,'L'); // Celda para la propiedad  
}
$i++;


$pdf->Cell(82,6,  mb_convert_encoding($item->nombre_1." ".$item->nombre_2." ".$item->nombre_3." ", "ISO-8859-1", "UTF-8")  ,1,0,'L'); // Celda para el propietario

}


$pdf->Ln(9);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,10, mb_convert_encoding("DETALLE DE MOVIMIENTOS", "ISO-8859-1", "UTF-8") ,0,0,'L');
/*****************Info desde Ficha arriendo Cuenta Corriente****************
$pdf->Ln(8);
$pdf->SetFillColor(217, 237, 247);
$pdf->SetFont('Arial','B',8.5);
$pdf->Cell(38,6,"Arriendos",1,0,'C',true);
$pdf->Cell(58,6,"Detalle del Pago",1,0,'C',true);
$pdf->Cell(58,6,"Cargos ",1,0,'C',true);
$pdf->Cell(38,6,"Abonos",1,1,'C',true);
$pdf->SetFillColor(255, 255, 255) ;
$pdf->SetFont('Arial','',8);



$pdf->Cell(38,6,"Julio 2022",1,0,'L',true);
$pdf->Cell(58,6,"codigo / direccion",1,0,'L',true);
$pdf->Cell(58,6,"",1,0,'R',true);
$pdf->Cell(38,6,"".number_format($precio , 0, '', '.'),1,1,'R',true);

**************************************** */



$pdf->Ln(9);
$pdf->SetFillColor(217, 237, 247);
$pdf->SetFont('Arial','B',8.5);
$pdf->Cell(38,6,"Cuenta",1,0,'C',true);
$pdf->Cell(58,6,"Detalle del Pago",1,0,'C',true);
$pdf->Cell(58,6,"Cargos ",1,0,'C',true);
$pdf->Cell(38,6,"Abonos",1,1,'C',true);
$pdf->SetFillColor(255, 255, 255) ;
$pdf->SetFont('Arial','',8);
/*****************Info desde Ficha arriendo Cuenta Corriente********************* */
$sum_cargos= 0;
$sum_abon= 0;
$total_comision_adm =0;
foreach ($objMovimientos as $mov){
$comision = 0;
$pdf->Cell(38,6,"",1,0,'L',true);
$pdf->Cell(58,6,"".$mov->razon ,1,0,'L',true);
if($mov->haber == 0){
$pdf->Cell(58,6,"",1,0,'R',true);
}else{
$pdf->Cell(58,6,"".number_format($mov->haber, 0, '', '.'),1,0,'R',true);
 $comision = round($mov->haber * ($monto_administracion_comision / 100));
  if($mov->cobro_comision == true){
 $total_comision_adm = $total_comision_adm + $comision;
 }

}
if($mov->debe == 0){
$pdf->Cell(38,6,"" ,1,1,'R',true);
}else{
$pdf->Cell(38,6,"".number_format($mov->debe, 0, '', '.') ,1,1,'R',true);
 $comision = round($mov->debe * ($monto_administracion_comision / 100));
 if($mov->cobro_comision == true){
 $total_comision_adm = $total_comision_adm + $comision;
 }
}


$sum_cargos= $sum_cargos+$mov->haber;
$sum_cargos = $sum_cargos+$comision;
$sum_abon= $sum_abon+$mov->debe;
}
if($total_comision_adm != 0){
$pdf->Cell(38,6,"",1,0,'L',true);
$pdf->Cell(58,6,"Comision Administracion ".$monto_administracion_comision."%" ,1,0,'L',true);
$pdf->Cell(58,6,"".number_format($total_comision_adm, 0, '', '.'),1,0,'R',true);
$pdf->Cell(38,6,"" ,1,1,'R',true);

$pdf->Cell(38,6,"",1,0,'L',true);
$cobro_iva_admin =  round($total_comision_adm * ($iva / 100));
$pdf->Cell(58,6,"IVA Comision Administracion" ,1,0,'L',true);
$pdf->Cell(58,6,"".number_format($cobro_iva_admin, 0, '', '.'),1,0,'R',true);
$pdf->Cell(38,6,"" ,1,1,'R',true);

}
/***********Info de Comisiones por Arriendo*********************** */
if($arriendo_comision_cobro == 1){
    
        $pdf->Cell(38,6,"",1,0,'L',true);
        $pdf->Cell(58,6,"".$monto_arriendo_comision."% Comision Arriendo",1,0,'L',true);
        $pdf->Cell(58,6,"".number_format($valor_comision_arriendo, 0, '', '.'),1,0,'R',true);
        $pdf->Cell(38,6,"" ,1,1,'R',true);
        $sum_cargos= $sum_cargos+$valor_comision_arriendo;
         ///////////Valor Iva de comison por Arriendo
        $pdf->Cell(38,6,"",1,0,'L',true);
        $pdf->Cell(58,6,"Iva Comision Arriendo",1,0,'L',true);
        $cobro_iva_arr =  $valor_comision_arriendo * ($iva / 100);
        $pdf->Cell(58,6,"".number_format($cobro_iva_arr, 0, '', '.'),1,0,'R',true);
        $pdf->Cell(38,6,"" ,1,1,'R',true);
        $sum_cargos= $sum_cargos+$cobro_iva_arr;
}
/***********Info de Comisiones por Administracion***********************
if($adm_comision_cobro == 1){
    
        $pdf->Cell(38,6,"",1,0,'L',true);
        $pdf->Cell(58,6,"".$monto_administracion_comision."% Comision Administracion",1,0,'L',true);
        $pdf->Cell(58,6,"".number_format($valor_comision_administracion, 0, '', '.'),1,0,'R',true);
        $pdf->Cell(38,6,"" ,1,1,'R',true);
        $sum_cargos= $sum_cargos+$valor_comision_administracion;
        ///////////Valor Iva de comison por Administracion
        $pdf->Cell(38,6,"",1,0,'L',true);
        $pdf->Cell(58,6,"Iva Comision Administracion",1,0,'L',true);
        $cobro_iva_adm =  $valor_comision_administracion * ($iva / 100);
        $pdf->Cell(58,6,"".number_format($cobro_iva_adm, 0, '', '.'),1,0,'R',true);
        $pdf->Cell(38,6,"" ,1,1,'R',true);
        $sum_cargos= $sum_cargos+$cobro_iva_adm;
       
}
 */
/****************************************** */

$pdf->Cell(38,6,"",1,0,'L',true);
$pdf->SetFont('Arial','B',8.5);
$pdf->Cell(58,6,"Total:",1,0,'R',true);
$pdf->SetFont('Arial','',8);
$pdf->Cell(58,6,"".number_format($sum_cargos, 0, '', '.'),1,0,'R',true);
$pdf->Cell(38,6,"".number_format($sum_abon, 0, '', '.') ,1,1,'R',true);

$monto_final = $sum_abon - $sum_cargos;
$pdf->Cell(38,6,"",1,0,'L',true);
$pdf->SetFont('Arial','B',8.5);
$pdf->Cell(58,6, mb_convert_encoding("Saldo:", "ISO-8859-1", "UTF-8")    ,1,0,'R',true);
$pdf->SetFont('Arial','',8);
$pdf->Cell(96,6,"".number_format($monto_final, 0, '', '.'),1,0,'R',true);

$pdf->Ln(8);
/***************Info PDETALLE DEL PAGO****************** 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,10,utf8_decode("DETALLE DEL PAGO"),0,0,'L');
$pdf->Ln(8);
$pdf->SetFillColor(217, 237, 247);
$pdf->SetFont('Arial','B',8.5);
$pdf->Cell(38,6,utf8_decode("Comisión + Iva"),1,0,'C',true);
$pdf->Cell(58,6,utf8_decode("N°Documento"),1,0,'C',true);
$pdf->Cell(58,6,utf8_decode("Tipo Transacción "),1,0,'C',true);
$pdf->Cell(38,6,"Monto",1,1,'C',true);
$pdf->SetFillColor(255, 255, 255) ;
$pdf->SetFont('Arial','',8);
$pdf->Cell(38,6,"".$suma_cargos,1,0,'C',true);
$pdf->Cell(58,6,"".$num_documento,1,0,'C',true);
$pdf->Cell(58,6,"".$tipo_transaccion,1,0,'C',true);
$pdf->Cell(38,6,"".$monto  ,1,1,'C',true);
$pdf->Ln(8);
$pdf->SetFillColor(217, 237, 247);
$pdf->SetFont('Arial','B',8.5);
$pdf->Cell(28,6,utf8_decode("Cheque N°"),1,0,'C',true);
$pdf->Cell(28,6,"Tipo Cheque",1,0,'C',true);
$pdf->Cell(28,6,"Tipo Cuenta ",1,0,'C',true);
$pdf->Cell(28,6,utf8_decode("N° Cuenta"),1,0,'C',true);
$pdf->Cell(40,6,"Banco",1,0,'C',true);
$pdf->Cell(40,6,"Sucursal",1,1,'C',true);
$pdf->SetFillColor(255, 255, 255) ;
$pdf->SetFont('Arial','',8);
$pdf->Cell(28,6,"".$suma_cargos,1,0,'C',true);
$pdf->Cell(28,6,"".$num_documento,1,0,'C',true);
$pdf->Cell(28,6,"".$tipo_transaccion,1,0,'C',true);
$pdf->Cell(28,6,"".$tipo_transaccion,1,0,'C',true);
$pdf->Cell(40,6,"".$tipo_transaccion,1,0,'C',true);
$pdf->Cell(40,6,"".$monto  ,1,1,'C',true);
$pdf->Ln(9);
*/
/***************Info Propietarios****************** */
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,10,  mb_convert_encoding("Propietarios", "ISO-8859-1", "UTF-8")   ,0,0,'L');
$pdf->Ln(8);
$pdf->SetFillColor(217, 237, 247);
$pdf->SetFont('Arial','B',8.5);
$pdf->Cell(154,6,  mb_convert_encoding("Propietario", "ISO-8859-1", "UTF-8")  ,1,0,'C',true);
$pdf->Cell(38,6, mb_convert_encoding("Porcentaje", "ISO-8859-1", "UTF-8")   ,1,1,'C',true);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial','',8);
foreach ($objPropietarios as $propietarios){
   $pdf->Cell(154,6,  mb_convert_encoding("".$propietarios->nombre_1." ".$propietarios->nombre_2." ".$propietarios->nombre_3." ", "ISO-8859-1", "UTF-8")   ,1,0,'L',true);
   $pdf->Cell(38,6,  mb_convert_encoding("".$propietarios->porcentaje_participacion_base."%", "ISO-8859-1", "UTF-8")  ,1,1,'R',true); 
}
$pdf->Ln(9);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,10,  mb_convert_encoding("Notas", "ISO-8859-1", "UTF-8")  ,0,0,'L');


/*************************Insert Liquidacion******************************** */
////Insert en liquidacion
$fecha_liquidacion = $fechaActual->format('Y-m-d H:i:s.v');
///////////////creacion Url ARchivo
$mesNumero = $fechaActual->format('n');
$anioNumero = $fechaActual->format('Y');
$numeroUnico = strtotime($fecha_liquidacion);
$nombre_archivo = md5($numeroUnico);
$ruta_guardado = '../../../upload/liquidaciones/'.$mesNumero.'-'.$anioNumero.'-'.$ficha_tecnica_propiedad.'-'.$nombre_archivo.'.pdf';
$ruta_info = 'upload/liquidaciones/'.$mesNumero.'-'.$anioNumero.'-'.$ficha_tecnica_propiedad.'-'.$nombre_archivo.'.pdf';


$sql_insert_liquidacion ="INSERT INTO propiedades.propiedad_liquidaciones 
(id_ficha_propiedad, monto, fecha_liquidacion, id_ficha_arriendo, url_liquidacion)
values( '$ficha_tecnica_propiedad', '$monto_final', '$fecha_liquidacion','$id_ficha_arriendo', '$ruta_info' ) RETURNING id";

$dataCab = array("consulta" => $sql_insert_liquidacion );
$resultadoLiquidacion = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
////Obtiene ID de liquidacion
$queryLiq = "select * from propiedades.propiedad_liquidaciones where fecha_liquidacion = '$fecha_liquidacion' 
and id_ficha_arriendo=".$id_ficha_arriendo;

$data = array("consulta" => $queryLiq, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objLiq= json_decode($resultado);
$objeto = $objLiq[0]; // Accede al primer elemento del array
$idLiq = $objeto->id;
if($adm_comision_cobro == 1){
$sql_comision_liq="INSERT INTO propiedades.propiedad_comision_liquidacion(id_propiedad_liquidacion, tipo_comision ,monto, iva, habilitado)
values($idLiq, 'Administracion' ,'$total_comision_adm', '$cobro_iva_admin', true)";
$dataCab = array("consulta" => $sql_comision_liq);
$resultadoLiquidacion = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
}
if($arriendo_comision_cobro == 1){
$sql_comision_liq="INSERT INTO propiedades.propiedad_comision_liquidacion(id_propiedad_liquidacion, tipo_comision ,monto, iva, habilitado)
values($idLiq, 'Arriendo' ,'$valor_comision_arriendo', '$cobro_iva_arr', true)";
$dataCab = array("consulta" => $sql_comision_liq);
$resultadoLiquidacion = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
}




//$pdf->Output('F', $ruta_guardado);
/// Abrir el archivo PDF después de guardarlo
//header("Content-type: application/pdf");
//header("Content-Disposition: inline; filename=archivo.pdf");
//readfile($ruta_guardado);

$pdf->Output();
?>