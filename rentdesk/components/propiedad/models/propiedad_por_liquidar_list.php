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


$dataTablePropiedadesPorLiquidar = array(
	array("macarenaibaceta@fuenzalida.com", "ANIBAL PINTO # 1393 Departamento 109-A (SEGURO), Independencia, Región Metropolitana", "CESAR ALBERTO PINOCHET ROBLES", "$47.686"),
	array("macarenaibaceta@fuenzalida.com", "AV. TORREONES # 1731 Departamento 806, EDIF. B (SEGURO-SIN COBRANZA-CONCEPCION), Concepción, Región Biobío", "ANDRES GUSTAVO SANHUEZA FIGUEROA", "$41.392"),
	array("macarenaibaceta@fuenzalida.com", "CANDELARIA GOYENECHEA # 3820 Local Comercial 66 (SILVER - DERIVADA), Vitacura, Región Metropolitana", "MARIA ANDREA PODESTA LOPEZ", "$540.000"),
	array("macarenaibaceta@fuenzalida.com", "CARMEN # 557 Departamento 1211 (SEGURO), Santiago, Región Metropolitana", "SALOMON BERNABE ACUÑA CIFUENTES", "$231.828"),
	array("macarenaibaceta@fuenzalida.com", "FRANZ LISZT # 4192 (BAJA - PAGA DIRECTO DUEÑO), Maipú, Región Metropolitana", "ALVARO ARAYA AMADO", "$940.050"),
);

//************************************************************************************************************
