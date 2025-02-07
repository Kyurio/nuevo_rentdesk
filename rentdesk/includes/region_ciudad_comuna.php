<?php
session_start();
// include("sql_inyection.php");
include("services_util.php");
include("../configuration.php");

$accion			= $_POST['accion'];
$valor			= $_POST['valor'];


$valor = explode("|", $valor);
$valor = $valor[0];

$valorpais		= @$_POST['valorpais'];
$valorregion	= @$_POST['valorregion'];
$valorcomuna	= @$_POST['valorcomuna'];



//accion: 0=cargar paises, 1=cargar regiones, 2=cargar comunas
//valor=id de pais o region

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


// //var_dump("ACCION PHP: ", $accion);
// //var_dump("VALOR PHP: ", $valor);

//*************************************************************************************************
if ($accion == "0") {

	// $data = array("token" => "");
	$paises = $services->sendGet($url_services . '/rentdesk/direcciones/paises', null, [], []);
	$json_paises = json_decode($paises);




	$opcion_pais = "";
	foreach ($json_paises as $pais) {
		$selected = " ";
		if ($valorpais == "$pais->token")
			$selected = " selected ";
		$opcion_pais = $opcion_pais . "
	<option value='$pais->token' $selected >$pais->nombre</option>";
	}


	$opcion_pais = "<select id='pais' name='pais'  required data-validation-required  onChange='seteaRegionComuna(\"1\",this.value);' class='form-control' >
<option value=''>Selecciona el Pais</option>
" . $opcion_pais . "
</select>";

	//*************************************************************************************************
	$opcion_region = "<select id='region' name='region'  style='color:#6d6c6c' required data-validation-required disabled  class='form-control'  onChange='seteaRegionComuna(\"2\",this.value);'><option value=''>Antes selecciona El Pais</option></select>";



	if ($valorpais != "" && $valorregion != "") {



		// $data = array("idPais" => $valorpais);
		$regiones = $services->sendGet($url_services . "/rentdesk/direcciones/paises/{$valor}/regiones", [], [], []);
		$json_regiones = json_decode($regiones);


		$opcion_region = "";
		foreach ((array)$json_regiones as $region) {
			$selected = " ";
			if ($valorregion == $region->token)
				$selected = " selected ";
			$opcion_region = $opcion_region . "
		<option value='$region->token' $selected >$region->nombre</option>";
		}
		$opcion_region = "<select id='regioncom' name='regioncom'   onChange='seteaRegionComuna(\"2\",this.value);' class='form-control'  >
	<option value=''>Selecciona la Región</option>
	" . $opcion_region . "
	</select>";
	}
	//*************************************************************************************************
	$opcion_comuna = "<select id='comuna' name='comuna' style='color:#6d6c6c' required data-validation-required disabled  class='form-control'  ><option value=''>Antes selecciona la Región</option></select> ";

	if ($valorpais != "" && $valorregion != "") {

		$comunas = $services->sendGet($url_services . "/rentdesk/direcciones/paises/regiones/{$valorregion}/comunas", [], [], []);
		$json_comunes = json_decode($comunas);
		$opcion_comuna = "";
		foreach ($json_comunes as $comuna) {
			//var_dump("comuna:", $comuna);

			$selected = " ";
			if ($valorcomuna == "$comuna->id")
				$selected = " selected ";
			$opcion_comuna = $opcion_comuna . "
		<option value='$comuna->id' $selected >$comuna->nombre</option>";
		}
		$opcion_comuna = "<select id='comunacom' name='comunacom'   class='form-control' >
	<option value=''>Selecciona la Comuna</option>
	" . $opcion_comuna . "
	</select>";
	}
	//**************************************************************************************************


	echo "xxx,
$opcion_pais
xxx,
$opcion_region
xxx,
$opcion_comuna
xxx,";
} //if($accion=="0")

//*************************************************************************************************

if ($accion == "1") {

	// $data = array("idPais" => $valor);
	$regiones = $services->sendGet($url_services . "/rentdesk/direcciones/paises/{$valor}/regiones", [], [], []);
	$json_regiones = json_decode($regiones);

	$opcion_region = "";
	foreach ((array)$json_regiones as $region) {
		$selected = " ";
		if ($valor == "$region->token")
			$selected = " selected ";
		$opcion_region = $opcion_region . "
		<option value='$region->token' $selected >$region->nombre</option>";
	}
	$opcion_region = "<select id='region' name='region'  required data-validation-required onChange='seteaRegionComuna(\"2\",this.value);' class='form-control'  >
	<option value=''>Selecciona la Región</option>
	" . $opcion_region . "
	</select>";
	echo $opcion_region;
} //if($accion=="1")

//*************************************************************************************************

if ($accion == "2") {

	// $data = array("idRegion" => $valor);
	$comunas = $services->sendGet($url_services . "/rentdesk/direcciones/paises/regiones/{$valor}/comunas", [], [], []);
	$json_comunes = json_decode($comunas);

	$opcion_comuna = "";
	foreach ((array)$json_comunes as $comuna) {
		$selected = " ";
		if ($valor == "$comuna->token")
			$selected = " selected ";
		$opcion_comuna = $opcion_comuna . "
		<option value='$comuna->id' $selected >$comuna->nombre</option>";
	}
	$opcion_comuna = "<select id='comuna' name='comuna'  required data-validation-required class='form-control'  >
	<option value=''>Selecciona la Comuna</option>
	" . $opcion_comuna . "
	</select>";

	echo $opcion_comuna;
} //if($accion=="2")

//*****************************************************************************************

if ($accion == "3") {

	// $data = array("idPais" => $valor);
	$regiones = $services->sendGet($url_services . "/rentdesk/direcciones/paises/{$valor}/regiones", [], [], []);
	$json_regiones = json_decode($regiones);

	$opcion_region = "";
	foreach ((array)$json_regiones as $region) {
		$selected = " ";
		if ($valor == "$region->token")
			$selected = " selected ";
		$opcion_region = $opcion_region . "
		<option value='$region->token' $selected >$region->nombre</option>";
	}
	$opcion_region = "<select id='region' name='region'  onChange='seteaRegionComuna(\"4\",this.value);' class='form-control'  >
	<option value=''>Selecciona la Región</option>
	" . $opcion_region . "
	</select>";
	echo $opcion_region;
} //if($accion=="1")

//*************************************************************************************************
if ($accion == "4") {

	// $data = array("idRegion" => $valor);
	$comunas = $services->sendGet($url_services . "/rentdesk/direcciones/paises/regiones/{$valor}/comunas", [], [], []);
	$json_comunes = json_decode($comunas);

	$opcion_comuna = "";
	foreach ((array)$json_comunes as $comuna) {
		$selected = " ";
		if ($valor == "$comuna->token")
			$selected = " selected ";
		$opcion_comuna = $opcion_comuna . "
		<option value='$comuna->id' $selected >$comuna->nombre</option>";
	}
	$opcion_comuna = "<select id='comuna' name='comuna' data-validation-required  class='form-control'  >
	<option value=''>Selecciona la Comuna</option>
	" . $opcion_comuna . "
	</select>";

	echo $opcion_comuna;
} //if($accion=="2")

if ($accion == "load") {
} //if($accion=="load")
