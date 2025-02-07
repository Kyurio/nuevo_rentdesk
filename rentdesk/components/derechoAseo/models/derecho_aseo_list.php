<?php
@include("../../includes/sql_inyection.php");

echo "";
//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=propiedad&view=propiedad_list");
if (isset($nav)) {
	$nav = "index.php?" . decodifica_navegacion($nav);
} else {
	$nav = "index.php?component=propiedad&view=propiedad_list";
}
//************************************************************************************************************


$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$token	= @$_GET["token"];

//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=propiedad&view=propiedad&token=$token&nav=$nav");

if (isset($nav)) {
	$nav = "index.php?" . decodifica_navegacion($nav);
} else {
	$nav = "index.php?component=propiedad&view=propiedad_list";
}


//************************************************************************************************************


/*para mejorar el rendimiento el servicio hace una ejecucion directa a la BBDD
es por esto que este objeto en particular debe utilizar los atributos tal como
estan creados en la base de datos y no como objetos
*/
// $result = null;
// $data = array("token" => $token, "idEmpresa" => $id_company);
// $resultado = $services->sendPostNoToken($url_services . '/propiedad/token', $data);
// if ($resultado) {
// 	$result_json = json_decode($resultado);
// 	foreach ($result_json as $result_r) {
// 		$result = $result_r;
// 	} //foreach($result_json as $result)
// }
//************************************************************************************************************

//************************************************************************************************************

$opcion_tipo_propiedad = "<option value=''>Seleccione</option>";
// $data_tipo_propiedad = array("idEmpresa" => $id_company);
// $resp_tipo_propiedad = $services->sendPostNoToken($url_services . '/tipoPropiedad/listaByEmpresa', $data_tipo_propiedad);
// $tipo_propiedads = json_decode($resp_tipo_propiedad);

// foreach ($tipo_propiedads as $tipo_propiedad_r) {

// 	$select_tipo_propiedad = "";
// 	if (@$result->id_tipo_propiedad == @$tipo_propiedad_r->idTipoPropiedad)
// 		$select_tipo_propiedad = " selected ";


// 	$opcion_tipo_propiedad = $opcion_tipo_propiedad . "<option value='$tipo_propiedad_r->idTipoPropiedad' $select_tipo_propiedad >$tipo_propiedad_r->descripcion</option>";
// } //foreach($roles as $rol)

$opcion_tipo_propiedad = "<select id='tipo_propiedad' name='tipo_propiedad' class='form-control' required >
$opcion_tipo_propiedad
</select>";

$dataTableDerechoAseo = array(
	array("AGUA SANTA 2893 Departamento 21 ***SILVER+SEGURO***, Maipú, Región Metropolitana", "Maipú", "-", "-", "15/02/2024"),
	array("ARCABUZ 4849 Casa **PTE.MANDATO** ***GOLD***, Peñalolén, Región Metropolitana", "Peñalolén", "-", "-", "15/02/2024"),
	array("ARCANGEL 1218 Departamento 1304 (gold) *****RETIRA PROPIETARIO****, San Miguel, Región Metropolitana", "San Miguel", "-", "-", "15/02/2024"),
	array("ARCANGEL 1218 Departamento 1305 ***GOLD +SEGURO*** *******RETIRADA POR DUEÑO******, San Miguel, Región Metropolitana", "San Miguel","-", "-", "15/02/2024"),
	array("ARCANGEL 1218 Departamento 802***GOLD*** *****************RETIRADA POR EL DUEÑO*****************, San Miguel, Región Metropolitana", "San Miguel","-", "-", "15/02/2024"),

);
