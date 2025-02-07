<?php
include("sql_inyection.php");
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



//*************************************************************************************************
if ($accion == "0") {

	$data = array("token" => "");
	$paises = $services->sendGetNoToken($url_services . '/pais/lista', $data);
	$json_paises = json_decode($paises);

	$opcion_pais = "";
	foreach ($json_paises as $pais) {
		$selected = " ";
		if ($valorpais == "$pais->idPais")
			$selected = " selected ";
		$opcion_pais = $opcion_pais . "
	<option value='$pais->idPais|$pais->descripcion' $selected >$pais->descripcion</option>";
	}


	$opcion_pais = "<select id='paiscom' name='paiscom' onChange='seteaRegionComunaCom(\"1\",this.value);' class='form-control' >
<option value=''>Selecciona el Pais</option>
" . $opcion_pais . "
</select>";

	//*************************************************************************************************
	$opcion_region = "<select id='regioncom' name='regioncom'  style='color:#6d6c6c'  disabled  class='form-control'  ><option value=''>Antes selecciona El Pais</option></select>";

	if ($valorpais != "" && $valorregion != "") {

		$data = array("idPais" => $valorpais);
		$regiones = $services->sendPostNoToken($url_services . '/region/byPais', $data);
		$json_regiones = json_decode($regiones);

		$opcion_region = "";
		foreach ($json_regiones as $region) {
			$selected = " ";
			if ($valorregion == "$region->idRegion")
				$selected = " selected ";
			$opcion_region = $opcion_region . "
		<option value='$region->idRegion|$region->descripcion' $selected >$region->descripcion</option>";
		}
		$opcion_region = "<select id='regioncom' name='regioncom'   onChange='seteaRegionComunaCom(\"2\",this.value);' class='form-control'  >
	<option value=''>Selecciona la Región</option>
	" . $opcion_region . "
	</select>";
	}
	//*************************************************************************************************
	$opcion_comuna = "<select id='comunacom' name='comunacom' style='color:#6d6c6c'  disabled  class='form-control'  ><option value=''>Antes selecciona la Región</option></select> ";

	if ($valorpais != "" && $valorregion != "") {

		$data = array("idRegion" => $valorregion);
		$comunas = $services->sendPostNoToken($url_services . '/comuna/byRegion', $data);
		$json_comunes = json_decode($comunas);

		$opcion_comuna = "";
		foreach ($json_comunes as $comuna) {
			$selected = " ";
			if ($valorcomuna == "$comuna->idComuna")
				$selected = " selected ";
			$opcion_comuna = $opcion_comuna . "
		<option value='$comuna->idComuna' $selected >$comuna->descripcion</option>";
		}
		$opcion_comuna = "<select id='comunacom' name='comunacom'   class='form-control'  >
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

	$data = array("idPais" => $valor);
	$regiones = $services->sendPostNoToken($url_services . '/region/byPais', $data);
	$json_regiones = json_decode($regiones);

	$opcion_region = "";
	foreach ($json_regiones as $region) {
		$selected = " ";
		if ($valorregion == "$region->idRegion")
			$selected = " selected ";
		$opcion_region = $opcion_region . "
		<option value='$region->idRegion|$region->descripcion' $selected >$region->descripcion</option>";
	}
	$opcion_region = "<select id='regioncom' name='regioncom'   onChange='seteaRegionComunaCom(\"2\",this.value);' class='form-control'  >
	<option value=''>Selecciona la Región</option>
	" . $opcion_region . "
	</select>";
	echo $opcion_region;
} //if($accion=="1")

//*************************************************************************************************

if ($accion == "2") {

	$data = array("idRegion" => $valor);
	$comunas = $services->sendPostNoToken($url_services . '/comuna/byRegion', $data);
	$json_comunes = json_decode($comunas);

	$opcion_comuna = "";
	foreach ($json_comunes as $comuna) {
		$selected = " ";
		if ($valorcomuna == "$comuna->idComuna")
			$selected = " selected ";
		$opcion_comuna = $opcion_comuna . "
		<option value='$comuna->idComuna|$comuna->descripcion' $selected >$comuna->descripcion</option>";
	}
	$opcion_comuna = "<select id='comunacom' name='comunacom'   class='form-control'  >
	<option value=''>Selecciona la Comuna</option>
	" . $opcion_comuna . "
	</select>";

	echo $opcion_comuna;
} //if($accion=="2")

//*****************************************************************************************

if ($accion == "load") {
} //if($accion=="load")
