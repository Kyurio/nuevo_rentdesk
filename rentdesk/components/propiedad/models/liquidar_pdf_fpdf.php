<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require '../../../includes/fpdf/fpdf.php';
require '../../../includes/fpdf/morepagestable.php';
require 'PDF_MC_Table.php';
session_start();
include "../../../includes/sql_inyection.php";
include "../../../configuration.php";
include "../../../includes/funciones.php";
include "../../../includes/services_util.php";
$config = new Config;
$services = new ServicesRestful;
$url_services = $config->url_services;
date_default_timezone_set('America/Santiago');
$valor_comision_administracion = 0;

// Obtén la fecha en formato YYYY-MM-DD
$fecha_mov_arriendo = date("Y-m-d");
$anio_mov_arriendo = date("Y");

// Obtén la hora en formato HH:MM:SS
$hora_mov_arriendo = date("H:i:s");

// Obtén el número del mes actual
$numeroMes = date("m");
$numeroanio = date("Y");
/////Fecha DE HOY
$fechaActual = new DateTime();
////Inidicadores que vendran de Tabla de datos
$num_reg = 50;
$inicio = 0;
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;

/************Comentario Liquidacion************* */
$comentarioLiq = $_POST["comentario-liquidacion"];

$fechaUF = $fechaActual->format('Y-m-d');
$sql_valor_uf = "select * from propiedades.indicadores i where  indicador  ='uf' and fecha = '$fechaUF'";
$data = array("consulta" => $sql_valor_uf, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultadouf = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objUF = json_decode($resultadouf);
$objetouf = $objUF[0]; // Accede al primer elemento del array
$valor_UF = $objetouf->valor;


$comision_arriendo_fija = 0;
$comision_administracion_fija = 0;
/******************************Consulta Valores configuracion Subsidiaria************************************ */

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_subsidiaria = $current_subsidiaria->id;


$queryConfig = "SELECT * FROM propiedades.tp_configuracion_subsidiaria where id_subisidiaria=" . $id_subsidiaria;
$data = array("consulta" => $queryConfig, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultadoCuentas = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objConfig = json_decode($resultadoCuentas);
$objeto = $objConfig[0]; // Accede al primer elemento del array
$iva = $objeto->iva;



/************************CONSUNLTA INFO DE LA PROPIEDAD************************************* */
$ficha_tecnica_propiedad = $_POST["ficha_tecnica"];
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
  inner join propiedades.tp_pais tp on tp.id  = tr.id_pais  where  nivel_propietario = 1 and p.id=" . $ficha_tecnica_propiedad;
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
$propiedad = $objeto->direccion . " #" . $objeto->numero . ", "
. $objeto->comuna . ", " . $objeto->region . ", " . $objeto->pais;

/************************CONSUNLTA INFO DEL ARRENDATARIO************************************* */

$queryArrendatario = " select va.nombre_1 , va.nombre_2 , va.nombre_3, fa.id, num_cuotas_garantia  from propiedades.propiedad p
 inner join propiedades.ficha_arriendo fa  on p.id = fa.id_propiedad
 left join propiedades.ficha_arriendo_arrendadores faa on faa.id_ficha_arriendo = fa.id
 left join propiedades.vis_arrendatarios va on va.id = faa.id_arrendatario
 where p.id =$ficha_tecnica_propiedad and fa.id_estado_contrato =1 ";

$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryArrendatario, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objArrendatario = json_decode($resultado);
$objetoArrendatario = $objArrendatario[0];

$id_ficha_arriendo = $objetoArrendatario->id; //Ficha Arriedo
$num_cuotas_garantia = @$objetoArrendatario->num_cuotas_garantia;

/***********************SQL PROPIETARIOS****************** */

$queryPropietarios = "select pc.porcentaje_participacion_base, vp.nombre_1 ,vp.nombre_2 ,vp.nombre_3, pc.id_propietario  from propiedades.propiedad p
inner join propiedades.propiedad_copropietarios pc on p.id =pc.id_propiedad  and pc.habilitado = true
inner join propiedades.persona_propietario pp on pp.id_persona = pc.id_propietario
inner join propiedades.vis_propietarios vp on vp.id = pp.id_persona where nivel_propietario= 1 and p.id=" . $ficha_tecnica_propiedad;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryPropietarios, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objPropietarios = json_decode($resultado);

/******************SQL COMISIONES*********************** */

$queryComision = "SELECT precio, id_moneda_precio, arriendo_comision_cobro, adm_comision_cobro, adm_comision_primer_liquidacion,
arriendo_comision_id_moneda, adm_comision_id_moneda, fecha_inicio,
  ttm1.nombre AS nombre_arriendo, arriendo_comision_id_moneda, arriendo_comision_monto,
        ttm2.nombre AS nombre_adm, adm_comision_id_moneda, adm_comision_monto, pago_garantia_propietario
  FROM propiedades.ficha_arriendo fa
  INNER JOIN propiedades.tp_tipo_moneda ttm1 ON ttm1.id = fa.arriendo_comision_id_moneda
  INNER JOIN propiedades.tp_tipo_moneda ttm2 ON ttm2.id = fa.adm_comision_id_moneda
  where  fa.id =" . $id_ficha_arriendo;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryComision, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objComision = json_decode($resultado);
$objeto = $objComision[0]; // Accede al primer elemento del array


$id_tp_arriendo_comision = $objeto->adm_comision_id_moneda; /////Datos comision por arriendo
$monto_arriendo_comision = $objeto->adm_comision_monto; /////Datos comision por arriendo
$arriendo_comision_cobro = $objeto->adm_comision_cobro; /////Datos comision por arriendo

$id_tp_administracion_comision = $objeto->arriendo_comision_id_moneda; /////Datos comision por Administracion
$monto_administracion_comision = $objeto->arriendo_comision_monto; /////Datos comision por Administracion
$adm_comision_cobro = $objeto->arriendo_comision_cobro; /////Datos comision por Administracion

$adm_comision_primer_liquidacion = $objeto->adm_comision_primer_liquidacion; /////Datos comision se cobra comision en primera liquidacion
$moneda_comision_adm = $objeto->adm_comision_id_moneda;
$moneda_comision_arr = $objeto->arriendo_comision_id_moneda;
$moneda_de_arriendo = $objeto->id_moneda_precio;
$precio = $objeto->precio;

$pago_garantia_propietario = $objeto->pago_garantia_propietario;

/// EN CASO DE SER UF, SE CALCULA EL PRECIO A PESOS
if($objeto->id_moneda_precio == 3){
    $precio = round($precio * $valor_UF);
}
///comision adm es pesos
if($moneda_comision_adm == 2){

$comision_administracion_fija = $comision_administracion_fija + $monto_administracion_comision;
}

///comision adm es uf
if($moneda_comision_adm == 3){
$adm_comision_cobro = round($monto_administracion_comision * $valor_UF);
$comision_administracion_fija = $comision_administracion_fija + $adm_comision_cobro;
}


///comision arriendo es pesos
if($moneda_comision_arr == 2){
$comision_arriendo_fija = $comision_arriendo_fija + $monto_arriendo_comision;
}

///comision arriendo es uf
if($moneda_comision_arr == 3){
$adm_comision_primer_liquidacion_uf = $monto_arriendo_comision;
$adm_comision_primer_liquidacion= round($monto_arriendo_comision * $valor_UF);
$comision_arriendo_fija = $comision_arriendo_fija + $adm_comision_primer_liquidacion;
}

$precio_arriendo = $objeto->precio;
$fecha_ingreso = $objeto->fecha_inicio;

/////////////////////////////////////Consulta Rajuste Mensual

$query_reajuste_mensual = "select * from propiedades.ficha_arriendo_reajustes far  where id_ficha_arriendo =$id_ficha_arriendo and id_mes_reajuste= $numeroMes";
$data = array("consulta" => $query_reajuste_mensual, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
if ($resultado != "") {
//////////////////Consultar si no se ha realizado ya el reajuste
    $query_cantidad_reajuste = "SELECT count(id_ficha_arriendo) as cantidad
                                from propiedades.ficha_arriendo_reajuste_hist where
                                    id_ficha_arriendo = '$id_ficha_arriendo' and mes_ajuste= '$numeroMes' and año_ajuste = '$numeroanio'";
    $data = array("consulta" => $query_cantidad_reajuste, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado_cant_reajuste = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    $obj_cant_reajuste = json_decode($resultado_cant_reajuste);
    $objeto_cant_reajuste = $obj_cant_reajuste[0];
    $cantidad_reajuste_realizado = $objeto_cant_reajuste->cantidad;
    //////////////////////////CODIGO PARA PRUEBA LUEGO BORRRAR
    $cantidad_reajuste_realizado = 0;
     
    if ($cantidad_reajuste_realizado == 0) {
 
        $obj_reajuste_mensual = json_decode($resultado);
        $objeto_mes_reajuste = $obj_reajuste_mensual[0];
        $cantidad_reajuste = $objeto_mes_reajuste->cantidad_reajuste;
        $id_tipo_reajuste = $objeto_mes_reajuste->id_tipo_reajuste; 
     
        //////////////////////////////////2 IPC
        if ($id_tipo_reajuste == 2){
         //consulto si hay un mes menor dentro del año 
        $query_meses_reajuste = "select * from propiedades.ficha_arriendo_reajustes 
        where  id_ficha_arriendo = '".$id_ficha_arriendo."' and id_mes_reajuste < ".$numeroMes." 
        ORDER BY id_mes_reajuste DESC";
        $data = array("consulta" => $query_meses_reajuste, "cantRegistros" => 1, "numPagina" => $num_pagina);
        $resultado_meses_reajuste = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
        //si no hay mes menor, toma el mes mayor para consultar al año pasado
       
        if($resultado_meses_reajuste == ""){
        $query_ultimo_mes_reajuste = "SELECT *
        FROM propiedades.ficha_arriendo_reajustes
        WHERE id_ficha_arriendo = $id_ficha_arriendo
        ORDER BY id_mes_reajuste DESC
        "; 
        $data = array("consulta" => $query_ultimo_mes_reajuste, "cantRegistros" => 1, "numPagina" => $num_pagina);
        $resultado_ultimo_mes = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
        $obj_ultimo_mes = json_decode($resultado_ultimo_mes);
        $objeto_ultimo_mes = $obj_ultimo_mes[0];



        $mes_pasado = $objeto_ultimo_mes->id_mes_reajuste ;
        $mes_actualizado = $numeroMes -1;
        $anio_pasado = $numeroanio - 1;
        $anio_actual = $numeroanio;

        $fecha_inicio_IPC = "01-".$mes_pasado."-".$anio_pasado;
        $fecha_termino_IPC = "01-".$mes_actualizado."-".$anio_actual;
        $query_suma_ipc = "SELECT SUM(valor) valor_ipc
        FROM propiedades.indicadores
        WHERE Fecha >= '$fecha_inicio_IPC' AND Fecha <= '$fecha_termino_IPC'
        AND indicador = 'ipc'";
        $data = array("consulta" => $query_suma_ipc, "cantRegistros" => 1, "numPagina" => $num_pagina);
        $resultado_ipc   = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
        $obj_valor_ipc = json_decode($resultado_ipc);
        $objeto_valor_ipc = $obj_valor_ipc[0];
        $ipc_acoumulado = $objeto_valor_ipc->valor_ipc;
        $precio_reajustado_ipc = round((($precio * $ipc_acoumulado)/100) + $precio);
        $precio = $precio_reajustado_ipc;

    }
        /////// En caso de que tiene un mes menor en el mismo año
        else{
         
        $obj_meses_reajuste = json_decode($resultado_meses_reajuste);
        $objeto_meses_reajuste = $obj_meses_reajuste[0];
        $mes_pasado =  $objeto_meses_reajuste->id_mes_reajuste;
        $mes_actualizado = $numeroMes -1;
        $anio_pasado = $numeroanio ;
        $anio_actual = $numeroanio;    
        $fecha_inicio_IPC = "01-".$mes_pasado."-".$anio_pasado;
        $fecha_termino_IPC = "01-".$mes_actualizado."-".$anio_actual;
        $query_suma_ipc = "SELECT SUM(valor) valor_ipc
        FROM propiedades.indicadores
        WHERE Fecha >= '$fecha_inicio_IPC' AND Fecha <= '$fecha_termino_IPC'
        AND indicador = 'ipc'";
        $data = array("consulta" => $query_suma_ipc, "cantRegistros" => 1, "numPagina" => $num_pagina);
        $resultado_ipc   = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
        $obj_valor_ipc = json_decode($resultado_ipc);
        $objeto_valor_ipc = $obj_valor_ipc[0];
        $ipc_acoumulado = $objeto_valor_ipc->valor_ipc;
        $precio_reajustado_ipc = round((($precio * $ipc_acoumulado)/100) + $precio);
        $precio = $precio_reajustado_ipc;
        }
       
            /*///////////////////////////////// A LA MALA EN EL API DE INE

            $mesInicio = $mes_inicio_api;
            $anioInicio = $anio_inicio_api;
            $mesTermino = $numeroMes - 1;
            $anioTermino = $numeroanio;
            $valorAjustar = $precio;

            // URL de la API
            $url = "https://api-calculadora.ine.cl/ServiciosCalculadoraVariacion?mesInicio=$mesInicio&AnioInicio=$anioInicio&mesTermino=$mesTermino&AnioTermino=$anioTermino&valor_a_ajustar=$valorAjustar";
            // Realizar la solicitud y obtener la respuesta
            $response = file_get_contents($url);

            // Decodificar la respuesta JSON
            $response_data = json_decode($response, true);
            $valor_ajustado =  $response_data[0]['valorajustado'];
            $precio = str_replace('.', '', $valor_ajustado);
            
            */
        }
        /////////////////////////////////3 FIjo POrcentual
        if ($id_tipo_reajuste == 3 ){
        $monto_que_reajusta = $precio * ($cantidad_reajuste / 100);
        $precio = round($precio + $monto_que_reajusta);
        }

        ////////////////////////////////4 Fijo Pesos
        if ($id_tipo_reajuste == 4 ){
        $monto_que_reajusta = $cantidad_reajuste;
        $precio = round($precio + $monto_que_reajusta);
        }

        ///////////////////////////////5 UF
         if ($id_tipo_reajuste == 5 ){
        $monto_que_reajusta = round($cantidad_reajuste * $valor_UF);
        $precio = round($precio + $monto_que_reajusta);
        }
       
//////////////////////////////iNSERT DATOS DEL REAJUSTE
        /////////////// SI ES UF el valor del arriendo
        if($moneda_de_arriendo == 3){
        $precio_en_UF  =  round($precio / $valor_UF , 2);
        $query_insert_reajuste_hist = "insert into propiedades.ficha_arriendo_reajuste_hist (id_ficha_arriendo, precio_arriendo, precio_arriendo_ajustado, cantidad_reajuste,
                                        id_tipo_reajuste, mes_ajuste, año_ajuste)
                                        values('$id_ficha_arriendo','$precio_arriendo','$precio_en_UF','$cantidad_reajuste','$id_tipo_reajuste','$numeroMes','$numeroanio')";
        }
        ///Si es Pesos el valor del arriendo
       else{
         $query_insert_reajuste_hist = "insert into propiedades.ficha_arriendo_reajuste_hist (id_ficha_arriendo, precio_arriendo, precio_arriendo_ajustado, cantidad_reajuste,
                                        id_tipo_reajuste, mes_ajuste, año_ajuste)
                                        values('$id_ficha_arriendo','$precio_arriendo','$precio','$cantidad_reajuste','$id_tipo_reajuste','$numeroMes','$numeroanio')";
        
       }
       $dataCab = array("consulta" => $query_insert_reajuste_hist);
        $resultado_insert_pago_arriendo = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
////////////////////ACA ACTUALIZO EL VALOR DEL ARRIENDO EN LA FICHA

         /////////////// SI ES UF el valor del arriendo
        if($moneda_de_arriendo == 3){
        $precio_en_UF  =  round($precio / $valor_UF , 2);
        $update_precio_arriendo = "UPDATE propiedades.ficha_arriendo SET precio = '$precio_en_UF' WHERE id = $id_ficha_arriendo";

        }
        ///Si es Pesos el valor del arriendo
       else{
        $update_precio_arriendo = "UPDATE propiedades.ficha_arriendo SET precio = '$precio' WHERE id = $id_ficha_arriendo";

       }
        $dataCab = array("consulta" => $update_precio_arriendo);
        $resultado_insert_pago_arriendo = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
    }
}

/////////////////////////////////////////////////////////////////////Consultar si el es tiene reajute (otro Valor)
$query_mes_reajuste = "SELECT monto, id_moneda from propiedades.ficha_arriendo_reajustes_fijacion_mes farfm
                        where id_arriendo =$id_ficha_arriendo and id_mes = $numeroMes and agno_curso = $numeroanio ";
$data = array("consulta" => $query_mes_reajuste, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
if ($resultado != "") {
    $obj_mes_reajuste = json_decode($resultado);
    $objeto_mes_reajuste = $obj_mes_reajuste[0];
    $tp_moneda =  $objeto_mes_reajuste->id_moneda;
    if($tp_moneda ==2 ){
    $precio = $objeto_mes_reajuste->monto;
    }
    else if ($tp_moneda == 3){
        $precio = $objeto_mes_reajuste->monto;
        $precio = round($precio * $valor_UF);
    }
}
///////////////////////////Consulta para saber si ya se tiene comision de arriendo
$sql_existe_cobro_comision_arriendo = "select count(pl.id) as cantidad_comision from propiedades.propiedad_liquidaciones pl
                                        where pl.id_ficha_propiedad = $ficha_tecnica_propiedad and pl.id_ficha_arriendo = $id_ficha_arriendo";
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $sql_existe_cobro_comision_arriendo, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objPropiedad = json_decode($resultado);
$objeto = $objPropiedad[0]; // Accede al primer elemento del array
$cantidad_comision = $objeto->cantidad_comision;
$flag_primer_mes = $cantidad_comision;

if ($flag_primer_mes == 0) {
//Calculo para solicitar en caso de que sea la primera liquidacion y se calcule el proporcional
    $ultimo_dia_mes = date("Y-m-t", strtotime($fecha_ingreso)); // Obtiene el último día del mes
    $diferencia_dias = (strtotime($ultimo_dia_mes) - strtotime($fecha_ingreso)) / (60 * 60 * 24); // Calcula la diferencia en días
// Dividir la fecha en partes
    $partes_fecha = explode("-", $fecha_ingreso);
    $mes = $partes_fecha[1]; // Obtener el mes
    $anio = $partes_fecha[0]; // Obtener el año
// Obtener los días en el mes
    $dias_en_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
    $precio = round(($diferencia_dias * $precio) / $dias_en_mes);
}

if ($cantidad_comision >= 1) {

    $arriendo_comision_cobro = 0;
    $adm_comision_primer_liquidacion = 1;
}
   
if ($arriendo_comision_cobro == 1) { //////// Validador si se cobra comision al arrendar (primer arriendo)
    if ($id_tp_arriendo_comision == 1) {
        $valor_comision_arriendo = round(($monto_arriendo_comision / 100) * $precio_arriendo);
    }else if ($id_tp_arriendo_comision == 2) {
        $valor_comision_arriendo = $comision_arriendo_fija;
    }else if ($id_tp_arriendo_comision == 3) {
        $valor_comision_arriendo = $comision_arriendo_fija;
    }
    else {
        $arriendo_comision_cobro = 0;
    }
    if ($adm_comision_primer_liquidacion == 0 && $cantidad_comision >= 1) {
        $adm_comision_cobro = 0;
    }

}

/*****************Ingreso de arriendo*****************

// Asigna el nombre del mes usando un switch
switch ($numeroMes) {
    case '01':
        $nombreMes = "enero";
        break;
    case '02':
        $nombreMes = "febrero";
        break;
    case '03':
        $nombreMes = "marzo";
        break;
    case '04':
        $nombreMes = "abril";
        break;
    case '05':
        $nombreMes = "mayo";
        break;
    case '06':
        $nombreMes = "junio";
        break;
    case '07':
        $nombreMes = "julio";
        break;
    case '08':
        $nombreMes = "agosto";
        break;
    case '09':
        $nombreMes = "septiembre";
        break;
    case '10':
        $nombreMes = "octubre";
        break;
    case '11':
        $nombreMes = "noviembre";
        break;
    case '12':
        $nombreMes = "diciembre";
        break;
    default:
        $nombreMes = "Mes desconocido";
        break;
}

$razon_mov = "Arriendo Mes " . $nombreMes . " del " . $anio_mov_arriendo;
/**********consulta si ya se ralizo inserto este mes ***********
$query_existe_mov_arriendo = "SELECT count(id) as cantidad_cobro_mes from propiedades.ficha_arriendo_cta_cte_movimientos
                                where id_ficha_arriendo = $id_ficha_arriendo and id_propiedad = $ficha_tecnica_propiedad and razon ='$razon_mov'";
$data = array("consulta" => $query_existe_mov_arriendo, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$obj_mov_arriendo = json_decode($resultado);
$objeto_arriendo = $obj_mov_arriendo[0]; // Accede al primer elemento del array
$cantidad_cobro_mes = $objeto_arriendo->cantidad_cobro_mes;
if ($cantidad_cobro_mes != 0) {
    $flag_cobro_mes = 0;
} else {
    $flag_cobro_mes = 1;
}
if ($flag_cobro_mes != 0) {
    $query_insert_pago_arriendo = "INSERT INTO propiedades.ficha_arriendo_cta_cte_movimientos
                                    (id_ficha_arriendo, fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, saldo, razon, cobro_comision, nro_cuotas, id_propiedad, pago_arriendo)
                                    VALUES ('$id_ficha_arriendo', '$fecha_mov_arriendo', '$hora_mov_arriendo', '1', '$precio', '0', '$razon_mov', '1', '0', '$ficha_tecnica_propiedad', '1')";
    $dataCab = array("consulta" => $query_insert_pago_arriendo);
    $resultado_insert_pago_arriendo = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
}
**** */
/************************CONSUNLTA INFO DE LOS MOVIMIENTOS************************************* */

$queryMovimientos = "SELECT *
FROM (
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
        ccm.id_propiedad,
        SUM(CASE WHEN ttm.id = 1 THEN ccm.monto ELSE 0 END) AS haber,
        SUM(CASE WHEN ttm.id = 2 THEN ccm.monto ELSE 0 END) AS debe,
        SUM(CASE WHEN ttm.id = 1 THEN ccm.monto ELSE -ccm.monto END) OVER (PARTITION BY ccm.id_ficha_arriendo ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) AS saldo,
        CASE WHEN SUM(CASE WHEN ttm.id = 1 THEN ccm.monto ELSE -ccm.monto END) OVER (PARTITION BY ccm.id_ficha_arriendo ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) >= 0 THEN '+' ELSE '-' END AS signo_saldo
    FROM
        propiedades.ficha_arriendo_cta_cte_movimientos ccm
    INNER JOIN
        propiedades.tp_tipo_movimiento_cta_cte ttmcc ON ccm.id_tipo_movimiento_cta_cte = ttmcc.id
    INNER JOIN
        propiedades.tp_tipo_movimiento ttm ON ttmcc.id_tipo_movimiento = ttm.id
    WHERE
        ccm.id_propiedad = $ficha_tecnica_propiedad
        AND ccm.id_tipo_movimiento_cta_cte != 2
        AND id_liquidacion is null 
    GROUP BY
        ccm.id,
        ccm.id_ficha_arriendo,
        ccm.fecha_movimiento,
        ccm.hora_movimiento,
        ccm.id_tipo_movimiento_cta_cte,
        ccm.monto,
        ccm.razon,
        ccm.cobro_comision,
        ttm.id,
        ccm.id_propiedad
) subquery
WHERE id_propiedad = $ficha_tecnica_propiedad
ORDER BY id DESC";
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryMovimientos, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objMovimientos = json_decode($resultado);



/******************Meses Garantia******************************** */

$queryMesesGarantia = "SELECT *
FROM propiedades.ficha_arriendo_cuotas_garantia
WHERE id_ficha_arriendo = $id_ficha_arriendo
  AND estado_garantia IS NULL
  AND (
        (garantia_ano < EXTRACT(YEAR FROM CURRENT_DATE))
     OR (garantia_ano = EXTRACT(YEAR FROM CURRENT_DATE) AND mes_garantia <= EXTRACT(MONTH FROM CURRENT_DATE))
      )";
$data = array("consulta" => $queryMesesGarantia, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultadoMesGarantia = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);

if ($resultadoMesGarantia != ""){
    $objmesGarantia = json_decode($resultadoMesGarantia);

}else{
    
	// $queryCabecera= "UPDATE propiedades.ficha_arriendo_cuotas_garantia
	// SET estado_garantia='PAGADO'
	// WHERE id_ficha_arriendo = $id_ficha_arriendo";

    // $dataCab = array("consulta" => $queryCabecera);
    // $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);	

}


/////////////////////// LIQUIDACION POR PROPIETARIO
$contenedor_rutas = [];
$contadorURL = 0;
foreach($objPropietarios as $liq_por_pro){

/*************************Insert Liquidacion******************************** */
////Insert en liquidacion
$fecha_liquidacion = $fechaActual->format('Y-m-d H:i:s.v');
///////////////creacion Url ARchivo
$mesNumero = $fechaActual->format('n');
$anioNumero = $fechaActual->format('Y');
$numeroUnico = strtotime($fecha_liquidacion);
$nombre_archivo = md5($numeroUnico);
$ruta_guardado = '../../../upload/liquidaciones/' . $mesNumero . '-' . $anioNumero . '-' . $ficha_tecnica_propiedad . '-' . $nombre_archivo .$contadorURL.'.pdf';
$ruta_info = 'upload/liquidaciones/' . $mesNumero . '-' . $anioNumero . '-' . $ficha_tecnica_propiedad . '-' . $nombre_archivo .$contadorURL. '.pdf';
$contenedor_rutas[] = $ruta_info;
$sql_insert_liquidacion = "INSERT INTO propiedades.propiedad_liquidaciones
(id_ficha_propiedad, fecha_liquidacion, id_ficha_arriendo, url_liquidacion, id_propietario)
values( '$ficha_tecnica_propiedad', '$fecha_liquidacion','$id_ficha_arriendo', '$ruta_info' , '$liq_por_pro->id_propietario')";
$contadorURL++;
$dataCab = array("consulta" => $sql_insert_liquidacion);
$resultadoLiquidacion = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
//print_r($resultadoLiquidacion);
////Obtiene ID de liquidacion
$queryLiq = "select * from propiedades.propiedad_liquidaciones where fecha_liquidacion = '$fecha_liquidacion'
and id_ficha_arriendo=" . $id_ficha_arriendo. " and id_propietario = ".$liq_por_pro->id_propietario;


$data = array("consulta" => $queryLiq, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objLiq = json_decode($resultado);
$objeto = $objLiq[0]; // Accede al primer elemento del array
$idLiq = $objeto->id;

/***********Variables **************** */
$folio = $idLiq;

$fecha = $fechaActual->format('d/m/Y');
$fecha_mov_garantia =$fechaActual->format('d-m-Y');
$codigo = $id;
$IVA_total = 0;
$comision_total = 0;
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 10, mb_convert_encoding("LIQUIDACIÓN DE ARRIENDO", "ISO-8859-1", "UTF-8"), 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 10, mb_convert_encoding("DEPARTAMENTO DE ADMINISTRACIONES", "ISO-8859-1", "UTF-8"), 0, 0, 'C');
$pdf->Ln(15);
$pdf->Cell(0, 10, mb_convert_encoding("FOLIO " . $folio, "ISO-8859-1", "UTF-8"), 0, 0, 'L');
$pdf->Ln(7);
$pdf->Cell(0, 10, mb_convert_encoding("Fecha: " . $fecha, "ISO-8859-1", "UTF-8"), 0, 0, 'C');
$pdf->Ln(9);

$pdf->SetFont('Arial', 'B', 8.5);

$pdf->SetWidths(array(28, 82, 82));

$pdf->SetAligns(array('C', 'C', 'C'));

$pdf->SetFillColor(217, 237, 247);
$pdf->Row(array("Codigo", "Propiedad", "Arrendatario"), 7, 'C', true);

$pdf->SetFont('Arial', '', 8);

$i = 0; //Flag para ver si se repite y no repetir codigo ni nada
foreach ($objArrendatario as $item) {
    if ($i == 0) {
        $propiedad = mb_convert_encoding($propiedad, "ISO-8859-1", "UTF-8");
    } else {
        $codigo = ""; // Celda para el código
        $propiedad = ""; // Celda para la propiedad
    }
    $i++;
    $nombreCompleto = $item->nombre_1 . " " . $item->nombre_2 . " " . $item->nombre_3 . " ";
    $nombreCompletoMayusculas = strtoupper($nombreCompleto);
    $arrenatario = mb_convert_encoding($nombreCompletoMayusculas, "ISO-8859-1", "UTF-8");
    $pdf->SetWidths(array(28, 82, 82));
    $pdf->SetAligns(array('C', 'L', 'L'));
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Row(array($codigo, $propiedad, $arrenatario), 6.5, 'C', true);

}

/***************Info Propietarios****************** */
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, mb_convert_encoding("Propietarios", "ISO-8859-1", "UTF-8"), 0, 0, 'L');
$pdf->Ln(8);
$pdf->SetFillColor(217, 237, 247);
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->Cell(154, 6, mb_convert_encoding("Propietario", "ISO-8859-1", "UTF-8"), 1, 0, 'C', true);
$pdf->Cell(38, 6, mb_convert_encoding("Porcentaje", "ISO-8859-1", "UTF-8"), 1, 1, 'C', true);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', '', 8);

    $nombreCompleto = $liq_por_pro->nombre_1 . " " . $liq_por_pro->nombre_2 . " " . $liq_por_pro->nombre_3 . " ";
    $nombreCompletoMayusculas = strtoupper($nombreCompleto);
    $nombreCompletoCodificado = mb_convert_encoding($nombreCompletoMayusculas, "ISO-8859-1", "UTF-8");
    $pdf->Cell(154, 6, $nombreCompletoCodificado, 1, 0, 'L', true);
    $pdf->Cell(38, 6, mb_convert_encoding("" . $liq_por_pro->porcentaje_participacion_base . "%", "ISO-8859-1", "UTF-8"), 1, 1, 'R', true);




$pdf->Ln(9);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, mb_convert_encoding("DETALLE DE MOVIMIENTOS", "ISO-8859-1", "UTF-8"), 0, 0, 'L');

$pdf->Ln(9);

$pdf->SetFillColor(217, 237, 247);
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->SetWidths(array(38, 58, 58, 38));
$pdf->SetAligns(array('C', 'C', 'C', 'C'));
$data = array("Fecha Movimiento", "Detalle del Pago", "Cargos", "Abonos");
$pdf->Row($data, 7, 'C', true);

/*****************Info desde Ficha arriendo Cuenta Corriente********************* */
$sum_cargos = 0;
$sum_abon = 0;
$total_comision_adm = 0;
$arrayDeIds = [];
/*****FOREACH PARA RECORRER TODOS LOS MOVIMIENTOS*** */
foreach ($objMovimientos as $movCompletos) {
 if ($movCompletos->haber == 0) {
        $cargos = "";
    } else {
        $cargos = number_format($movCompletos->haber, 0, '', '.');
        $comision = round($movCompletos->haber * ($monto_administracion_comision / 100));
        
          
       

    }
    if ($movCompletos->debe == 0) {
        $abonos = "";
    } else {
        $abonos = number_format($movCompletos->debe, 0, '', '.');
        $comision = round($movCompletos->debe * ($monto_administracion_comision / 100));
       
           
      
    }
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetWidths(array(38, 58, 58, 38));
    $pdf->SetAligns(array('C', 'L', 'R', 'R'));
    $pdf->SetFillColor(255, 255, 255);
    if( $abonos == ""){
    $pdf->Row(array(date("d-m-Y", strtotime($movCompletos->fecha_movimiento)), $movCompletos->razon, "$".$cargos, $abonos), 6.5, 'C', true);

    }
    else{
    $pdf->Row(array(date("d-m-Y", strtotime($movCompletos->fecha_movimiento)), $movCompletos->razon, $cargos, "$".$abonos), 6.5, 'C', true);

    }
}
foreach($objmesGarantia as $objGarantia){
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetWidths(array(38, 58, 58, 38));
    $pdf->SetAligns(array('C', 'L', 'R', 'R'));
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Row(array( $fecha_mov_garantia,  mb_convert_encoding("GARANTÍA CUOTA $objGarantia->num_cuotas de $num_cuotas_garantia", "ISO-8859-1", "UTF-8"), '', "$".$objGarantia->monto_garantia), 6.5, 'C', true);
}

$pdf->SetFillColor(217, 237, 247);
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->SetWidths(array(38, 58, 58, 38));
$pdf->SetAligns(array('C', 'C', 'C', 'C'));
$data = array(" ", " ", " ", " ");
$pdf->Row($data, 4, 'C', true);


foreach ($objMovimientos as $mov) {
    $arrayDeIds[] = $mov->id;
    $comision = 0;
    $movimiento_haber = 0;
    $movimiento_debe = 0;
    if ($mov->haber == 0) {
        $cargos = "";
    } else {
        $movimiento_haber = ($mov->haber * $liq_por_pro->porcentaje_participacion_base) / 100;
        $cargos = number_format($movimiento_haber, 0, '', '.');
        $comision = round($movimiento_haber * ($monto_administracion_comision / 100));
        
            if ($adm_comision_cobro != 0 && $adm_comision_primer_liquidacion != 0) {
                $total_comision_adm = $total_comision_adm + $comision;
            }
       

    }
    if ($mov->debe == 0) {
        $abonos = "";
    } else {
        $movimiento_debe = ($mov->debe * $liq_por_pro->porcentaje_participacion_base) / 100;
        $abonos = number_format($movimiento_debe, 0, '', '.');
        $comision = round($movimiento_debe * ($monto_administracion_comision / 100));
       
            if ($adm_comision_cobro != 0 && $adm_comision_primer_liquidacion != 0) {
                $total_comision_adm = $total_comision_adm + $comision;
            }
      
    }
    /*****************Garantia en caso de tener***************************** */
if($pago_garantia_propietario == 1) {


if ($resultadoMesGarantia != ""){

   foreach($objmesGarantia as $meses_garantia){
    $mesGarantia = $meses_garantia->mes_garantia;
    $anioGarantia = $meses_garantia->garantia_ano;
    $montoGarantia = $meses_garantia->monto_garantia;
    $numeroCuota = $meses_garantia->num_cuotas;
    $idCuota = $meses_garantia->id;
    switch ($mesGarantia) {
    case '01':
        $nombreMes = "enero";
        break;
    case '02':
        $nombreMes = "febrero";
        break;
    case '03':
        $nombreMes = "marzo";
        break;
    case '04':
        $nombreMes = "abril";
        break;
    case '05':
        $nombreMes = "mayo";
        break;
    case '06':
        $nombreMes = "junio";
        break;
    case '07':
        $nombreMes = "julio";
        break;
    case '08':
        $nombreMes = "agosto";
        break;
    case '09':
        $nombreMes = "septiembre";
        break;
    case '10':
        $nombreMes = "octubre";
        break;
    case '11':
        $nombreMes = "noviembre";
        break;
    case '12':
        $nombreMes = "diciembre";
        break;
    default:
        $nombreMes = "Mes desconocido";
        break;
}


$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(38, 6, mb_convert_encoding($fecha_mov_garantia, "ISO-8859-1", "UTF-8"), 1,  0, 'C', true );
$pdf->Cell(58, 6, mb_convert_encoding("Garantía Cuota $numeroCuota de $num_cuotas_garantia $liq_por_pro->porcentaje_participacion_base %", "ISO-8859-1", "UTF-8"), 1, 0, 'L', true);
$pdf->Cell(58, 6, "" , 1, 0, 'L', true);
$montoGarantiaIndividual = ($montoGarantia * $liq_por_pro->porcentaje_participacion_base) / 100; 
$pdf->Cell(38, 6, mb_convert_encoding("$".number_format($montoGarantiaIndividual, 0, '', '.')."", "ISO-8859-1", "UTF-8"), 1, 1, 'R', true);
$pdf->SetFillColor(255, 255, 255);
$sql_update_cuota = "update  propiedades.ficha_arriendo_cuotas_garantia  set estado_garantia = 'PAGADO' where id  = '$idCuota'";

$dataCab = array("consulta" => $sql_update_cuota);
$resultadoCuota = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
}

}
}
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetWidths(array(38, 58, 58, 38));
    $pdf->SetAligns(array('C', 'L', 'R', 'R'));
    $pdf->SetFillColor(255, 255, 255);
     if( $abonos == ""){
    $pdf->Row(array(date("d-m-Y", strtotime($mov->fecha_movimiento)), $mov->razon.' '.$liq_por_pro->porcentaje_participacion_base.'%', "$".$cargos, $abonos), 6.5, 'C', true);
    }
    else{
    $pdf->Row(array(date("d-m-Y", strtotime($mov->fecha_movimiento)), $mov->razon.' '.$liq_por_pro->porcentaje_participacion_base.'%', $cargos, "$".$abonos), 6.5, 'C', true);
  
    }
    $movimiento_debe = ($mov->debe * $liq_por_pro->porcentaje_participacion_base) / 100;
    $sum_cargos = $sum_cargos + $movimiento_haber;
    $sum_abon = $sum_abon + $movimiento_debe;
    if ($adm_comision_cobro == 1 && $adm_comision_primer_liquidacion != 0) {
        $sum_cargos = $sum_cargos + $comision;
    }
    
}


if ($total_comision_adm != 0 && $moneda_comision_adm == 1 ) {
    
    $pdf->Cell(38, 6, "", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "Comision de Administracion " . $monto_administracion_comision . "%", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "$" . number_format($total_comision_adm, 0, '', '.'), 1, 0, 'R', true);
    $pdf->Cell(38, 6, "", 1, 1, 'R', true);

    $pdf->Cell(38, 6, "", 1, 0, 'L', true);
    $cobro_iva_admin = round($total_comision_adm * ($iva / 100));
    $pdf->Cell(58, 6, "IVA Comision de Administracion", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "$" . number_format($cobro_iva_admin, 0, '', '.'), 1, 0, 'R', true);
    $pdf->Cell(38, 6, "", 1, 1, 'R', true);
    $IVA_total = $IVA_total + $cobro_iva_admin;
    $comision_total = $comision_total + $total_comision_adm;

}

else if ($moneda_comision_adm == 2){
    $pdf->Cell(38, 6, "", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "Comision de Administracion $" . $monto_administracion_comision . " CLP", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "$" . number_format($comision_administracion_fija, 0, '', '.'), 1, 0, 'R', true);
    $pdf->Cell(38, 6, "", 1, 1, 'R', true);

    $pdf->Cell(38, 6, "", 1, 0, 'L', true);
    $cobro_iva_admin = round($comision_administracion_fija * ($iva / 100));
    $pdf->Cell(58, 6, "IVA Comision de Administracion", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "$" . number_format($cobro_iva_admin, 0, '', '.'), 1, 0, 'R', true);
    $pdf->Cell(38, 6, "", 1, 1, 'R', true);
    $IVA_total = $IVA_total + $cobro_iva_admin;
    $comision_total =  $comision_administracion_fija;
}
else if ($moneda_comision_adm == 3){
    $pdf->Cell(38, 6, "", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "Comision de Administracion " . $monto_administracion_comision . " UF", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "$" . number_format($comision_administracion_fija, 0, '', '.'), 1, 0, 'R', true);
    $pdf->Cell(38, 6, "", 1, 1, 'R', true);

    $pdf->Cell(38, 6, "", 1, 0, 'L', true);
    $cobro_iva_admin = round($comision_administracion_fija * ($iva / 100));
    $pdf->Cell(58, 6, "IVA Comision de Administracion", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "$" . number_format($cobro_iva_admin, 0, '', '.'), 1, 0, 'R', true);
    $pdf->Cell(38, 6, "", 1, 1, 'R', true);
    $IVA_total = $IVA_total + $cobro_iva_admin;
    $comision_total =  $comision_administracion_fija;
}
/***********Info de Comisiones por Arriendo*********************** */
/************CALCULA COMICION CORRETAJE SEGUN % DEL PROPIETARIO******* */
$valor_comision_arriendo = ($valor_comision_arriendo * $liq_por_pro->porcentaje_participacion_base) / 100;

if ($arriendo_comision_cobro == 1) {

    ///comision arriendo es uf
if($moneda_comision_arr == 3 ){

    
    $pdf->Cell(38, 6, "", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "Comision de Corretaje" . $adm_comision_primer_liquidacion_uf ." UF  ", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "" . number_format($valor_comision_arriendo, 0, '', '.'), 1, 0, 'R', true);
    $pdf->Cell(38, 6, "", 1, 1, 'R', true);
}

///comision arriendo es pesos
if($moneda_comision_arr == 2)	{

    $pdf->Cell(38, 6, "", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "Comision de Corretaje $" . $monto_arriendo_comision . " CLP ", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "" . number_format($valor_comision_arriendo, 0, '', '.'), 1, 0, 'R', true);
    $pdf->Cell(38, 6, "", 1, 1, 'R', true);
}

if($moneda_comision_arr == 1)	{

    $pdf->Cell(38, 6, "", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "Comision de Corretaje ". $monto_arriendo_comision . "% ", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "" . number_format($valor_comision_arriendo, 0, '', '.'), 1, 0, 'R', true);
    $pdf->Cell(38, 6, "", 1, 1, 'R', true);
    }
    $sum_cargos = $sum_cargos + $valor_comision_arriendo;
    ///////////Valor Iva de comison por Arriendo
    $pdf->Cell(38, 6, "", 1, 0, 'L', true);
    $pdf->Cell(58, 6, "Iva Comision de Corretaje", 1, 0, 'L', true);
    $cobro_iva_arr = round($valor_comision_arriendo * ($iva / 100));
    $pdf->Cell(58, 6, "" . number_format($cobro_iva_arr, 0, '', '.'), 1, 0, 'R', true);
    $pdf->Cell(38, 6, "", 1, 1, 'R', true);
    //$sum_cargos = $sum_cargos + $cobro_iva_arr;
    $IVA_total = $IVA_total + $cobro_iva_arr;
    $comision_total = $comision_total + $valor_comision_arriendo;
}

/****************************************** */

$pdf->Cell(38, 6, "", 1, 0, 'L', true);
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->Cell(58, 6, "Total:", 1, 0, 'R', true);
$pdf->SetFont('Arial', '', 8);
$sum_cargos = $sum_cargos + $IVA_total;
if($sum_cargos == 0){
$pdf->Cell(58, 6, "" . number_format($sum_cargos, 0, '', '.'), 1, 0, 'R', true);
}
else{
$pdf->Cell(58, 6, "$" . number_format($sum_cargos, 0, '', '.'), 1, 0, 'R', true);
}
$sum_abon = $sum_abon + $montoGarantiaIndividual;
if($sum_abon == 0){
$pdf->Cell(38, 6, "" . number_format($sum_abon, 0, '', '.'), 1, 1, 'R', true);
}
else{
$pdf->Cell(38, 6, "$" . number_format($sum_abon, 0, '', '.'), 1, 1, 'R', true);
}

$monto_final = $sum_abon - $sum_cargos;
$pdf->Cell(38, 6, "", 1, 0, 'L', true);
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->Cell(58, 6, mb_convert_encoding("Saldo:", "ISO-8859-1", "UTF-8"), 1, 0, 'R', true);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(96, 6, "$" . number_format($monto_final, 0, '', '.'), 1, 0, 'R', true);

$pdf->Ln(8);




/**********************NOTAS**************************** */
$pdf->Ln(9);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, mb_convert_encoding("Notas", "ISO-8859-1", "UTF-8"), 0, 0, 'L');
$pdf->Ln(9);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, mb_convert_encoding("$comentarioLiq", "ISO-8859-1", "UTF-8"), 0, 0, 'L');

$pdf->Output('F', $ruta_guardado);

//////////////////FIN LIQUIDACION POR PROPIETARIO

//////////////////FIN LIQUIDACION POR PROPIETARIO

/*************************UPDATE Liquidacion******************************** */

$sql_insert_liquidacion = "update  propiedades.propiedad_liquidaciones  set monto = '$monto_final', comision= '$comision_total', iva='$IVA_total',
abonos = '$sum_abon', descuentos = '$sum_cargos', total = '$monto_final' where id  = '$idLiq'";

$dataCab = array("consulta" => $sql_insert_liquidacion);
$resultadoLiquidacion = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);


/*******************INSERT DE LAS COMISIONES************************************ */
    $sql_comision_liq = "INSERT INTO propiedades.propiedad_comision_liquidacion(id_propiedad_liquidacion, tipo_comision ,monto, iva, habilitado)
values($idLiq, 'Administracion' ,'$total_comision_adm', '$cobro_iva_admin', true)";
    $dataCab = array("consulta" => $sql_comision_liq);
    $resultadoLiquidacion = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

if ($arriendo_comision_cobro == 1) {
    $sql_comision_liq = "INSERT INTO propiedades.propiedad_comision_liquidacion(id_propiedad_liquidacion, tipo_comision ,monto, iva, habilitado)
values($idLiq, 'Arriendo' ,'$valor_comision_arriendo', '$cobro_iva_arr', true)";
    $dataCab = array("consulta" => $sql_comision_liq);
    $resultadoLiquidacion = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
}
}
/*******************Actualizacion de movimientos con ID de liquidacion*****************/
foreach ($arrayDeIds as $idMov) {
    $sql_update_mov = "UPDATE propiedades.ficha_arriendo_cta_cte_movimientos set id_liquidacion = $idLiq where id = $idMov";
    $dataCab = array("consulta" => $sql_update_mov);
    $resul_update_mov = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
}



/*

/// Abrir el archivo PDF después de guardarlo
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=archivo.pdf");
readfile($ruta_guardado);
//$pdf->Output();
*/

$a = 1;
foreach ($contenedor_rutas as $rutas) {
    echo "<a href ='../../../". $rutas."' target='_blank'> Liquidacion ".$a." </a><br>";
    $a++;
}
?>