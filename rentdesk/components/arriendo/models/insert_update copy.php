<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$codigoPropiedad = @$_POST['codigoPropiedad'];
$rol	 		 = @$_POST['rol'];
$direccion		 = @$_POST['direccion'];
$numero		 	 = @$_POST['numero'];
$numeroDepto 	 = @$_POST['numeroDepto'];
$piso 			 = @$_POST['piso'];
$precio 		 = @$_POST['precio'];
$fechaIngreso 	 = @$_POST['fechaIngreso'];
$dormitorios     = @$_POST['dormitorios'];
$banos   	 	 = @$_POST['banos'];
$dormitoriosServicio = @$_POST['dormitoriosServicio'];
$banosVisita 	 = @$_POST['banosVisita'];
$estacionamientos = @$_POST['estacionamientos'];
$bodegas     	 = @$_POST['bodegas'];
$logia     		 = @$_POST['logia'];
$coordenadas     = @$_POST['coordenadas'];
$tipo_propiedad	 = @$_POST['tipo_propiedad'];
$terreno		 = @$_POST['terreno'];
$edificado		 = @$_POST['edificado'];
$tipo_moneda	= @$_POST['tipo_moneda'];
$estado_propiedad = @$_POST['estado_propiedad'];
$piscina		= @$_POST['piscina'];
$sucursal		= @$_POST['sucursal'];
$token	  		= @$_POST['token'];
$comuna      = @$_POST['comuna'];
$amoblado   = @$_POST['amoblado'];
$dfl2   = @$_POST['dfl2'];
$avaluo_fiscal   = @$_POST['avaluo_fiscal'];
$destino_arriendo   = @$_POST['destino_arriendo'];

$id_company 	= $_SESSION["rd_company_id"];
$mandato = "";

$avaluo_fiscal	 = desformatea_number($avaluo_fiscal, $_SESSION["separador_mil"]);

$id_comuna = explode("|", $comuna);
$id_comuna = $id_comuna[0];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token_nuevo = md5(rand(99999, 99999999) . $direccion . $rol . date("Y m d H s"));

if ($token == "" && $direccion != "") {

	$data = array(
		"banos" => $banos,
		"banosVisita" => $banosVisita,
		"bodegas" => $bodegas,
		"codigoPropiedad" => $codigoPropiedad,
		"coordenadas" => $coordenadas,
		"direccion" => $direccion,
		"dormitorios" => $dormitorios,
		"dormitoriosServicio" => $dormitoriosServicio,
		"edificado" => $edificado,
		"estacionamientos" => $estacionamientos,
		"fechaIngreso" => $fechaIngreso,
		"logia" => $logia,
		"numero" => $numero,
		"numeroDepto" => $numeroDepto,
		"piscina" => $piscina,
		"piso" => $piso,
		"precio" => $precio,
		"rol" => $rol,
		"terreno" => $terreno,
		"idEstadoPropiedad" => $estado_propiedad,
		"idTipoMoneda" => $tipo_moneda,
		"idTipoPropiedad" => $tipo_propiedad,
		"terreno" => $terreno,
		"token" => $token_nuevo,
		"idSucursal" => $sucursal,
		"idEmpresa" => $id_company,
		"idComuna" => $id_comuna,
		"mandato" => $mandato,
		"amoblado" => $amoblado,
		"dfl2" => $dfl2,
		"avaluoFiscal" => $avaluo_fiscal,
		"destinoArriendo" => $destino_arriendo
	);
	$resultado = $services->sendPostNoToken($url_services . '/propiedad', $data);
	$result = json_decode($resultado);

	echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token_nuevo,xxx,";
} //if($token=="" && $nombre!="")

//***********************************************************************************************************
//******************************************************************************************

if ($token == "") {
	$token = $token_nuevo;
} //if($token=="")

$id_archivo 		= "";
$mandato_anterior 	= "";

$data = array("token" => $token, "idEmpresa" => $id_company);
$resultado = $services->sendPostNoToken($url_services . '/propiedad/token', $data);

if ($resultado) {
	$result_json = json_decode($resultado);
	foreach ($result_json as $result_r) {
		$result = $result_r;
		$id_propiedad 		= @$result->id_propiedad;
		$mandato_anterior 	= @$result->mandato;
	} //foreach($result_json as $result)
}


$patronIMG 	= "%\.(jpg|PNG|png|JPG|JPEG|jpeg|doc|DOC|docx|DOCX|pdf|PDF|xls|XLS|xlsx|XLSX)$%i";

$fis_arch = $_FILES["archivo"]["name"];
$aleatorio = rand(9999, 99999999);
$doc_ima_fisico = $mandato_anterior;
if ($fis_arch != "") {
	preg_match($patronIMG, $fis_arch) == 1 ? $archivoValido = "S" : $archivoValido = "N";
	if ($archivoValido == "S") {
		$doc_ima = $fis_arch;
		$doc_ima_fisico =  date('Ymd_his') . "_mandato$aleatorio$id_propiedad." . pathinfo($fis_arch, PATHINFO_EXTENSION);


		move_uploaded_file($_FILES["archivo"]["tmp_name"], "../../../upload/mandato/" . $doc_ima_fisico);
		try {
			if (delete_file("../../../upload/mandato/" . $mandato_anterior) === true) {
				$msg = "OK";
			}
		} catch (Exception $e) {
			$msg = $e->getMessage();
		}
	}
}



if ($token != "" && $direccion != "") {
	$mandato = @$doc_ima_fisico;
	$data = array(
		"banos" => $banos,
		"banosVisita" => $banosVisita,
		"bodegas" => $bodegas,
		"codigoPropiedad" => $codigoPropiedad,
		"coordenadas" => $coordenadas,
		"direccion" => $direccion,
		"dormitorios" => $dormitorios,
		"dormitoriosServicio" => $dormitoriosServicio,
		"edificado" => $edificado,
		"estacionamientos" => $estacionamientos,
		"fechaIngreso" => $fechaIngreso,
		"logia" => $logia,
		"numero" => $numero,
		"numeroDepto" => $numeroDepto,
		"piscina" => $piscina,
		"piso" => $piso,
		"precio" => $precio,
		"rol" => $rol,
		"terreno" => $terreno,
		"idEstadoPropiedad" => $estado_propiedad,
		"idTipoMoneda" => $tipo_moneda,
		"idTipoPropiedad" => $tipo_propiedad,
		"terreno" => $terreno,
		"token" => $token,
		"idSucursal" => $sucursal,
		"idEmpresa" => $id_company,
		"idComuna" => $id_comuna,
		"mandato" => $mandato,
		"amoblado" => $amoblado,
		"dfl2" => $dfl2,
		"avaluoFiscal" => $avaluo_fiscal,
		"destinoArriendo" => $destino_arriendo
	);

	$resultado = $services->sendPut($url_services . '/propiedad', $data);

	$result = json_decode($resultado);

	echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
} //if($token!="" && $nombre!=""){
