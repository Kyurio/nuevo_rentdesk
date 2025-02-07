<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_usuario = $_SESSION["rd_usuario_id"];
$arrendatarios = "";
$fecha = date("Y-m-d");
$carpeta = "upload\arriendo_cuenta_corriente_pago_nl_";
$documentoTitulo = @$_POST['documentoTituloPagoNL'];
$tokenFichaArriendo = @$_POST['token_ficha'];
$token_agrupador = rand(9999,99999999);
$token_agrupador = md5($token_agrupador);
$fecha_defecto = "1900-01-01";

$fechasDocumento = [];


	
	$num_reg = 10;
     $inicio = 0;
	 
	 
	$query = "SELECT id FROM propiedades.cuenta_usuario cu where token = '$id_usuario' ";
    $cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    $objUsuarioId = json_decode($resultado)[0];
	
	$query = "SELECT id FROM propiedades.ficha_arriendo fa  where token = '$tokenFichaArriendo' ";
	//var_dump($query);

    $cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    $objArriendoId = json_decode($resultado)[0];
	//var_dump($objArriendoId);


foreach ($_POST as $clave => $valor) {
    if (strpos($clave, 'documentoFecha_') === 0) {
		if ($valor != null && $valor != ""){
		//var_dump("Valor Fecha: ",$valor);
		$fechasDocumento[] = $valor;
		}
        
    }
}

//Subida de archivos 
$patronIMG 	= "%\.(xls|XLS|xlsx|XLSX)$%i";



//$fis_arch = $_FILES["archivo"]["name"]; Anterior lectura
/*
foreach ($_FILES as $clave => $archivo) {
    if (strpos($clave, 'archivo') !== false) {
        // Aquí puedes acceder a la información del archivo
        $nombre_archivo = $archivo["name"];
        $tipo_archivo = $archivo["type"];
        $tamano_archivo = $archivo["size"];
        // Y realizar cualquier otra operación que necesites
		if($nombre_archivo != null && $nombre_archivo != "") //Solo insertar cuando se tiene algun archivo
		var_dump("Archivo",$nombre_archivo);
    }
}
*/
/* SE comenta por que las fechas seran opcionales - solicitado 3-5-2024
foreach ($_FILES as $clave => $archivo) {
    if (strpos($clave, 'archivo_cuenta_corriente_pago_nl_') !== false) {
        // Aquí puedes acceder a la información del archivo
        $nombre_archivo = $archivo["name"];
        $tipo_archivo = $archivo["type"];
        $tamano_archivo = $archivo["size"];
		var_dump($tipo_archivo);
        // Y realizar cualquier otra operación que necesites
        // Obtenemos el índice del archivo en base al nombre de la clave

        $indice = substr($clave, strlen('archivo_cuenta_corriente_pago_nl_'));
		if($nombre_archivo != null && $nombre_archivo != ""){
			if (isset($fechasDocumento[$indice]) ) {
				$fechaDocumento = $fechasDocumento[$indice];
				// Aquí puedes utilizar $fechaDocumento junto con la información del archivo
				if ($nombre_archivo != null && $nombre_archivo != "") { //Solo insertar cuando se tiene algún archivo
					if($fechaDocumento != null && $fechaDocumento != ""){
						var_dump("FechaDocumento",$fechaDocumento );
					}else{
						echo ",xxx,ERROR,xxx,Falta ingresar fecha vencimiento al archivo :$nombre_archivo ,xxx,-,xxx,";
						return;
					}
				
				}
			}else{
				echo ",xxx,ERROR,xxx,Falta ingresar fecha vencimiento al archivo : $nombre_archivo ,xxx,-,xxx,";
				return;
			}
		}
    }
}*/

//var_dump("_FILES:", $_FILES);
foreach ($_FILES as $clave => $archivo) {
	//var_dump("FILE: ", $archivo);
    if (strpos($clave, 'archivo_cuenta_corriente_pago_nl_') !== false) {
        // Aquí puedes acceder a la información del archivo
        $nombre_archivo = $archivo["name"];
        $tipo_archivo = $archivo["type"];
        $tamano_archivo = $archivo["size"];
		//var_dump($tipo_archivo);
        // Y realizar cualquier otra operación que necesites
        // Obtenemos el índice del archivo en base al nombre de la clave

        $indice = substr($clave, strlen('archivo_cuenta_corriente_pago_nl_'));
		///if (isset($fechasDocumento[$indice])) { //Se comenta por que puede ser que no venga una fecha
            $fechaDocumento = @$fechasDocumento[$indice];
			if ($fechaDocumento == "" ) {
				$fechaDocumento = $fecha_defecto;
			}
            // Aquí puedes utilizar $fechaDocumento junto con la información del archivo
            if ($nombre_archivo != null && $nombre_archivo != "") { //Solo insertar cuando se tiene algún archivo
			    $nombre_sin_extension = pathinfo($nombre_archivo, PATHINFO_FILENAME);
				$extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
				$nombre_sin_extension = str_replace('___-___', '', $nombre_sin_extension); //SE REMPLAZA EN EL CASO QUE SE TENGA LA MISMA NOMENCLATURA AL CREAR EL ARCHIVO
               // var_dump("Archivo", $nombre_sin_extension, "Fecha de Documento", $fechaDocumento);
				$aleatorio = rand(9999,99999999);
				$nombre_archivo_token = md5($aleatorio.date('Ymd_his'));
				$doc_ima_fisico =  $nombre_sin_extension."___-___arriendo_cuenta_corriente_".$nombre_archivo_token;
				move_uploaded_file($archivo["tmp_name"], "../../../upload/arriendo_cuenta_corriente/" . $doc_ima_fisico.".".$extension);
				//move_uploaded_file($_FILES["archivo"]["tmp_name"], "../../../upload/arriendo_cuenta_corriente/" . $doc_ima_fisico.".".$tipo_archivo);
				
					$queryCabecera= " INSERT INTO propiedades.propiedad_archivos
                    (id, id_usuario, fecha_subida, ruta, componente, archivo, titulo, fecha_vencimiento, id_ficha_arriendo, estado,extension,token_agrupador,nombre_archivo)
                     VALUES(nextval('propiedades.propiedad_archivos_id_seq'::regclass),$objUsuarioId->id, '$fecha','$carpeta' ,'arriendo_cuenta_corriente_pago_nl', '$doc_ima_fisico' , '$documentoTitulo', '$fechaDocumento', $objArriendoId->id,true,'$extension','$token_agrupador','$nombre_sin_extension') ";
		        //var_dump("cabecera: ",$queryCabecera);
                      $dataCab = array("consulta" => $queryCabecera);
                      $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

            }
		//}
    }
}

		if(!$resultadoCab || $resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro registrar documento,xxx,-,xxx,";
		return;
	}else{
		echo ",xxx,OK,xxx,Se registro documento,xxx,-,xxx,";
	}

return;


// Codigo antiguo --> Se cambia forma de trabajar
$nombre_sin_extension = pathinfo($fis_arch, PATHINFO_FILENAME);
$nombre_sin_extension = str_replace('___-___', '', $nombre_sin_extension); //SE REMPLAZA EN EL CASO QUE SE TENGA LA MISMA NOMENCLATURA AL CREAR EL ARCHIVO
$extension = pathinfo($fis_arch, PATHINFO_EXTENSION);
var_dump($nombre_sin_extension);
var_dump($extension);
$aleatorio = rand(9999,99999999);

/*
if ($fis_arch!="") {
	preg_match($patronIMG, $fis_arch) == 1 ? $archivoValido ="S": $archivoValido ="N";
			if($archivoValido == "S"){
				$doc_ima = $fis_arch;
				$doc_ima_fisico =  date('Ymd_his') . "_santander_$aleatorio." . pathinfo($fis_arch, PATHINFO_EXTENSION);

				move_uploaded_file($_FILES["archivo"]["tmp_name"], "rentdesk\upload\arriendo_cuenta_corriente" . $doc_ima_fisico);


			}
}*/
$nombre_archivo = md5($aleatorio.date('Ymd_his'));

if ($fis_arch!="") {
				$doc_ima = $fis_arch;
				//$doc_ima_fisico =  "arriendo_cuenta_corriente_".$nombre_archivo;
				//var_dump(pathinfo($fis_arch, PATHINFO_EXTENSION));
				$doc_ima_fisico =  $nombre_sin_extension."___-___arriendo_cuenta_corriente_".$nombre_archivo;
				move_uploaded_file($_FILES["archivo"]["tmp_name"], "../../../upload/arriendo_cuenta_corriente/" . $doc_ima_fisico.".". pathinfo($fis_arch, PATHINFO_EXTENSION));

				//move_uploaded_file($_FILES["archivo"]["tmp_name"], "rentdesk/upload/arriendo_cuenta_corriente/" . $doc_ima_fisico);
}else{
	//var_dump("No se subio archivo");
	echo ",xxx,ERROR,xxx,Falta agregar un archivo,xxx,-,xxx,";
	return;
}
/*	
$nombre_bdd = $doc_ima_fisico.".".$extension;
var_dump($nombre_bdd);
*/
/*=================================================================*/
/*PROCESAMIENTO DE FORMULARIO
/*=================================================================*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Accessing form fields
	
	$documentoTitulo = @$_POST['documentoTitulo'];
	$documentoFecha = @$_POST['documentoFecha'];
	$tokenFichaArriendo = @$_POST['token_ficha'];
	
	$num_reg = 10;
     $inicio = 0;
	 
	 
	$query = "SELECT id FROM propiedades.cuenta_usuario cu where token = '$id_usuario' ";
    $cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    $objUsuarioId = json_decode($resultado)[0];
	
	$query = "SELECT id FROM propiedades.ficha_arriendo fa  where token = '$tokenFichaArriendo' ";
    $cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	//var_dump($query);
    $objArriendoId = json_decode($resultado)[0];
					
				
	$queryCabecera= " INSERT INTO propiedades.propiedad_archivos
                    (id, id_usuario, fecha_subida, ruta, componente, archivo, titulo, fecha_vencimiento, id_ficha_arriendo, estado,extension)
                     VALUES(nextval('propiedades.propiedad_archivos_id_seq'::regclass),$objUsuarioId->id, '$fecha','$carpeta' ,'arriendo_cuenta_corriente_pago_nl', '$doc_ima_fisico' , '$documentoTitulo', '$documentoFecha', $objArriendoId->id,true,'$extension') ";
		//var_dump($queryCabecera);
              $dataCab = array("consulta" => $queryCabecera);
              $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);	
    //var_dump($resultadoCab);
	/*---------------------------- */


	
	
		if(!$resultadoCab || $resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro registrar documento,xxx,-,xxx,";
		return;
	}else{
		echo ",xxx,OK,xxx,Se registro documento,xxx,-,xxx,";
	}
}
