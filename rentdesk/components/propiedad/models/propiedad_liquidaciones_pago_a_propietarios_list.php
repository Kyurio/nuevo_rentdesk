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

$dataTablePropiedadLiqPagoPropietariosRealizarPago = array(
	array("ALDO FANTINATI RUGGERONI", "EL RADAL 80 Departamento D-55, BX-357, BD-111, Lo Barnechea, Región Metropolitana", "$575.235", "SI", "1"),
	array("INMOBILIARIA ESTORIL SPA", "ESTORIL # 820 Departamento 20, Las Condes, Región Metropolitana	", "$1.891.224", "SI", "1"),
	array("JORGE FERRER PARES", "VITACURA 6195 Local Comercial LOCAL J, Vitacura, Región Metropolitana", "$596.242", "SI", "1"),
	array("JOSE OSVALDO MALDONADO HERNANDEZ", "AV. ALBERTO HURTADO 590 Departamento DPTO 202 BX013 BD41, Maipú, Región Metropolitana", "$512.777", "SI", "1"),
	array("OSCAR ALFREDO BERNARDINO ITURRIA LOPEZ", "ROJAS MAGALLANES # 61 Local Comercial E, La Florida, Región Metropolitana", "$396.969", "SI", "1"),


);


$dataTablePropiedadLiqPagoPropietariosPagosRealizados = array(
	array("21/02/2024 17:17:33", "transferencias-1985671-20240221171733540175480-0300.xls"),
	array("21/02/2024 17:17:33", "manager-file-23334-2024022117181708546692.txt"),
	array("21/02/2024 12:25:44", "transferencias-1982411-20240221122545267519595-0300.xls"),
	array("21/02/2024 12:25:44", "manager-file-23330-2024022112261708529195.txt"),
	array("20/02/2024 17:52:45", "transferencias-6501781-20240220175246605943063-0300.xls"),


);
