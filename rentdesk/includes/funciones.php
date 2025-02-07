<?php

function fecha_mysql_a_normal($fecha)
{
	$fecha_retornada = "";
	$fecha_retornada = $fecha_retornada . $fecha[8];
	$fecha_retornada = $fecha_retornada . $fecha[9];
	$fecha_retornada = $fecha_retornada . $fecha[7];
	$fecha_retornada = $fecha_retornada . $fecha[5];
	$fecha_retornada = $fecha_retornada . $fecha[6];
	$fecha_retornada = $fecha_retornada . $fecha[4];
	$fecha_retornada = $fecha_retornada . $fecha[0];
	$fecha_retornada = $fecha_retornada . $fecha[1];
	$fecha_retornada = $fecha_retornada . $fecha[2];
	$fecha_retornada = $fecha_retornada . $fecha[3];
	return ($fecha_retornada);
}

function fecha_normal_mysql($fecha)
{
	$fecha_retornada = "";
	$fecha_retornada = $fecha_retornada . $fecha[6];
	$fecha_retornada = $fecha_retornada . $fecha[7];
	$fecha_retornada = $fecha_retornada . $fecha[8];
	$fecha_retornada = $fecha_retornada . $fecha[9];
	$fecha_retornada = $fecha_retornada . $fecha[5];
	$fecha_retornada = $fecha_retornada . $fecha[3];
	$fecha_retornada = $fecha_retornada . $fecha[4];
	$fecha_retornada = $fecha_retornada . $fecha[2];
	$fecha_retornada = $fecha_retornada . $fecha[0];
	$fecha_retornada = $fecha_retornada . $fecha[1];
	return ($fecha_retornada);
}

function fecha_postgre_a_normal($fecha)
{

	$fecha_retornada = "";
	if (isset($fecha)) {
		$fecha_retornada = date("d-m-Y", strtotime($fecha));
	}
	return ($fecha_retornada);
}

function fecha_normal_a_postgre($fecha)
{

	$fecha_retornada = "";
	if (isset($fecha)) {
		$fecha_retornada = date("Y-m-d", strtotime($fecha));
	}
	return ($fecha_retornada);
}

function dia_ingles($dia)
{
	if ($dia == "1")
		return ("monday");
	if ($dia == "2")
		return ("tuesday");
	if ($dia == "3")
		return ("wednesday");
	if ($dia == "4")
		return ("thursday");
	if ($dia == "5")
		return ("friday");
	if ($dia == "6")
		return ("saturday");
	if ($dia == "7")
		return ("sunday");
}

function compararFechas($primera, $segunda)
{
	$valoresPrimera = explode("-", $primera);
	$valoresSegunda = explode("-", $segunda);
	$diaPrimera    = $valoresPrimera[0];
	$mesPrimera  = $valoresPrimera[1];
	$anyoPrimera   = $valoresPrimera[2];
	$diaSegunda   = $valoresSegunda[0];
	$mesSegunda = $valoresSegunda[1];
	$anyoSegunda  = $valoresSegunda[2];
	$diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);
	$diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);
	if (!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)) {
		// "La fecha ".$primera." no es v�lida";   
		return 0;
	} elseif (!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)) {
		// "La fecha ".$segunda." no es v�lida";   
		return 0;
	} else {
		return  $diasSegundaJuliano - $diasPrimeraJuliano;
	}
}



function getRealIP()
{

	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if (getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if (getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if (getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if (getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if (getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;

	//return $ip;
}

function formatea_rut($rut)
{

	$rut 	= str_replace("k", "K", $rut);
	$rut 	= str_replace(".", "", $rut);
	$rut 	= str_replace("-", "", $rut);
	$dv 	= substr($rut, -1);
	$rut 	= substr($rut, 0, -1);
	$rut 	=  number_format($rut, 0, "", ".");
	$rut 	= $rut . "-" . $dv;

	return $rut;
}


function delete_file($pFilename)
{
	if (file_exists($pFilename)) {
		if (@unlink($pFilename) !== true)
			throw new Exception('Could not delete file: ' . $pFilename . ' Please close all applications that are using it.');
	}
	return true;
}


function codifica_navegacion($nav)
{
	$navegacion = base64_encode($nav);
	return  $navegacion;
}

function decodifica_navegacion($nav)
{
	$navegacion = base64_decode($nav);
	return  $navegacion;
}

function formatea_number($numero, $decimales, $sep_mil)
{
	$sep_decimal = "";
	if ($sep_mil == ".") {
		$sep_decimal = ",";
	} else {
		$sep_decimal = ".";
	}

	$format = number_format((float)$numero, $decimales, $sep_decimal, $sep_mil);
	$decimal = substr($format, strpos($format, $sep_decimal));
	$decimal = str_replace($sep_decimal, "", $decimal);

	if ((int)$decimal == 0) {
		$format = number_format((float)$numero, 0, $sep_decimal, $sep_mil);
	}

	return $format;
}

function desformatea_number($numero, $sep_mil)
{
	$format = "";
	if ($sep_mil == ".") {
		$format = str_replace($sep_mil, "", $numero);
		$format = str_replace(",", ".", $format);
	} else {
		$format = str_replace($sep_mil, "", $numero);
	}

	return  $format;
}

function eliminar_acentos($cadena)
{
	$cadena = utf8_decode($cadena);
	//Reemplazamos la A y a
	$cadena = str_replace(
		array('�', '�', '�', '�', '�', '�', '�', '�', '�'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
	);

	//Reemplazamos la E y e
	$cadena = str_replace(
		array('�', '�', '�', '�', '�', '�', '�', '�'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena
	);

	//Reemplazamos la I y i
	$cadena = str_replace(
		array('�', '�', '�', '�', '�', '�', '�', '�'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena
	);

	//Reemplazamos la O y o
	$cadena = str_replace(
		array('�', '�', '�', '�', '�', '�', '�', '�'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena
	);

	//Reemplazamos la U y u
	$cadena = str_replace(
		array('�', '�', '�', '�', '�', '�', '�', '�'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena
	);

	//Reemplazamos la C y c
	$cadena = str_replace(
		array('�', '�'),
		array('C', 'c'),
		$cadena
	);

	//Reemplazamos la C y c
	$cadena = str_replace(
		array('�'),
		array('�'),
		$cadena
	);

	return $cadena;
}

function formato_busqueda($texto)
{
	$format = eliminar_acentos($texto);
	$format = mb_strtoupper(utf8_encode($format));
	return  $format;
}


function generarCodigoAutorizacionUsuario($options = [])
{
	$length = isset($options['length']) ? $options['length'] : 6;
	$useUpperCase = isset($options['useUpperCase']) ? $options['useUpperCase'] : false;
	$useLowerCase = isset($options['useLowerCase']) ? $options['useLowerCase'] : false;
	$useNumbers = isset($options['useNumbers']) ? $options['useNumbers'] : true;
	$useSymbols = isset($options['useSymbols']) ? $options['useSymbols'] : false;

	$DEFAULT_SYMBOLS = '!@#$%+?';
	$KEYS = [
		'upperCase' => 'ABCDEFGHJKMNPQRSTUVWXYZ',
		'lowerCase' => 'abcdefghjkmnpqrstuvwxyz',
		'number' => '0123456789',
		'symbol' => $DEFAULT_SYMBOLS,
	];

	$availableCharacters = '';

	if ($useUpperCase) {
		$availableCharacters .= $KEYS['upperCase'];
	}
	if ($useLowerCase) {
		$availableCharacters .= $KEYS['lowerCase'];
	}
	if ($useNumbers) {
		$availableCharacters .= $KEYS['number'];
	}
	if ($useSymbols) {
		$availableCharacters .= $KEYS['symbol'];
	}

	if (strlen($availableCharacters) === 0) {
		throw new Exception("At least one character type should be selected to generate a password.");
	}

	$password = '';
	for ($i = 0; $i < $length; $i++) {
		$randomIndex = rand(0, strlen($availableCharacters) - 1);
		$password .= $availableCharacters[$randomIndex];
	}

	return $password;
}

//jhernandez
// function generarCodigoAutorizacionUsuario()
// {
// 	$longitud = 10;

// 	$cadenaUnica = strtoupper(uniqid());
// 	while (strlen($cadenaUnica) < $longitud) {
// 		$cadenaUnica .= strtoupper(bin2hex(random_bytes(1))); // Agregar un byte aleatorio en mayúsculas
// 	}
// 	return substr($cadenaUnica, 0, $longitud);
// }
