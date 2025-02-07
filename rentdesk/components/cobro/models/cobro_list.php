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

$dataTable = array(
	array("AGUA SANTA 2893 Departamento 21 ***SILVER+SEGURO***, Maipú, Región Metropolitana", "ZULEMA CRUMILLA ANDRADE", "$250.000", "IPC", "Marzo, Septiembre"),
	array("ALCALDE J.MONCKEBERG 35 Departamento 502 (SEGURO), Ñuñoa, Región Metropolitana", "SOLANGE LORENA ITURBE  CRESPO", "$560.450", "IPC", "Marzo, Septiembre"),
	array("ALCALDE JORGE MONCKEBERG # 35 Departamento 405, BD-16, Ñuñoa, Región Metropolitana", "TAMARA GABRIELA PAKOZDI  MELLADO", "$377.030", "IPC", "Marzo, Septiembre"),
	array("ALCALDE JOSE LUIS INFANTE LARRAIN 1680 (SEG) Gold + seguro, Maipú, Región Metropolitana", "ENEDINA DORALISA DIAZ VEGA", "$746.825", "IPC", "Marzo, Septiembre"),
	array("ALCALDE PEDRO ALARCON 921 Departamento 812 (T.C.30-09-23) ***CORRETAJE/SAN MIGUEL***PYP***, San Miguel, Región Metropolitana", "INVERSIONES RIACHUELO S.A", "$398.309", "IPC", "Marzo, Septiembre")
);
