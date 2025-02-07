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

$fecha_desde = "2024-01-01";
$fecha_hasta = "2024-12-31";


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

$dataTableMovimientoVariosAcreedores = array(
	array("21/02/2024 15:13:00", "LAS CAMELIAS 2096, Maipú, Región Metropolitana", "GILDA DEL CARMEN MUÑOZ MENDEZ", "Descuento VA", "Descuento varios acreedores", "$61.275"),
	array("21/02/2024 15:13:00", "LOPEZ DE AYALA # 1552 //REPARACIONES DUEÑO// ***CORRETAJE TALAGANTE***, Maipú, Región Metropolitana", "EVELYN MACARENA ORDENES SALAS", "Descuento VA", "Descuento varios acreedores", "$465.806"),
	array("21/02/2024 15:13:00", "HUERFANOS 1400 Departamento DEPTO. 806-A, Santiago, Región Metropolitana", "CALIXTO GABRIEL MERY MARIN", "Descuento VA", "Descuento varios acreedores", "$475.213"),
	array("20/02/2024 15:19:00", "MANUEL DE FALLA 0155 //REPARACIONES DUEÑO//, Puente Alto, Región Metropolitana", "NORA JOAQUINA RUIZ TAPIA", "Descuento VA", "Descuento varios acreedores", "$380.000"),
	array("20/02/2024 15:19:00", "TERESA VIAL 1170 Departamento 201 ***RETIRADA***, San Miguel, Región Metropolitana", "JOSE FABIAN HERNANDEZ TORRES", "Descuento VA", "Descuento varios acreedores", "$549.499")

);
