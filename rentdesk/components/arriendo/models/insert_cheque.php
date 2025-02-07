<?php

//bruno

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$config     = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$arrendatarios = "";



/*=================================================================*/
/*PROCESAMIENTO DE FORMULARIO
/*=================================================================*/

$Cheque_id_ficha = @$_POST['id_ficha'];
$Cheque_Monto = @$_POST['Cheque_Monto'];
$Cheque_Monto = str_replace(",", "", $Cheque_Monto);
$Cheque_Monto = str_replace(".", "", $Cheque_Monto);
$Cheque_Razon = @$_POST['Cheque_Razon'];
$Cheque_Banco = @$_POST['tipo_banco'];
$Cheque_Fecha = @$_POST['Cheque_Fecha'];
$Cheque_Girador = @$_POST['Cheque_Girador'];
$Cheque_Numero_Doc = @$_POST['Cheque_Numero_Doc'];
$Cantidad_Cheque = @$_POST['Cantidad_Cheque'];
$token_Cheque = @$_POST['token'];
$Comentario_Cheque = @$_POST['Comentario_Cheque'];
//echo $token_Cheque."-".$Cheque_Monto;

if ($Cantidad_Cheque === '') {
    $Cantidad_Cheque = 1;
};

if ($Comentario_Cheque === '') {
    $Comentario_Cheque = 'No hay cometarios';
}

$fecha_inicial = new DateTime($Cheque_Fecha);
$dia_inicial = $fecha_inicial->format('d'); // Guardar el día inicial

$queryInsertCheque = '';

for ($i = 0; $i < $Cantidad_Cheque; $i++) {
    // Insertar Cheque
    $Cheque_Fecha = $fecha_inicial->format('Y-m-d');
    $queryInsertCheque .= "INSERT INTO propiedades.ficha_arriendo_cheques (id_ficha_arriendo, monto, razon, banco,
    fecha_cobro, girador, numero_documento, cantidad, comentario, desposito, cobrar, habilitado)
    VALUES ('$Cheque_id_ficha', '$Cheque_Monto', '$Cheque_Razon', '$Cheque_Banco', '$Cheque_Fecha', 
    '$Cheque_Girador', '$Cheque_Numero_Doc', 1, '$Comentario_Cheque', false, true, true);";

    // Actualizar la fecha sumando un mes
    $fecha_inicial->modify('first day of next month'); // Ir al primer día del siguiente mes
    $ultimo_dia_mes = $fecha_inicial->format('t'); // Obtener el último día del mes

    // Si el día inicial es mayor al último día del mes, ajustar al último día del mes
    if ($dia_inicial > $ultimo_dia_mes) {
        $fecha_inicial->setDate($fecha_inicial->format('Y'), $fecha_inicial->format('m'), $ultimo_dia_mes);
    } else {
        $fecha_inicial->setDate($fecha_inicial->format('Y'), $fecha_inicial->format('m'), $dia_inicial);
    }

    // Actualización del número de documento
    $Cheque_Numero_Doc += 1;
}




//var_dump($queryInsertCheque);
$dataCab = array("consulta" => $queryInsertCheque);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

////Obtener ID insertado
$num_reg = 1;
$inicio = 0;
$queryCheques = "select id from propiedades.ficha_arriendo_cheques 
where id_ficha_arriendo ='$Cheque_id_ficha' 
and monto = '$Cheque_Monto'
and razon = '$Cheque_Razon'
and banco= '$Cheque_Banco'
and fecha_cobro = '$Cheque_Fecha'
and girador = '$Cheque_Girador'
and numero_documento = '$Cheque_Numero_Doc'
and cantidad = '$Cantidad_Cheque'
and habilitado = true
order by id desc";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCheques, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCheques = json_decode($resultado);
$objeto = $objCheques[0]; // Accede al primer elemento del array
$id = $objeto->id; // Accede al valor de la propiedad 'id' dentro del objeto stdClass
echo $id;
 
//return true;