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

$dataTableResponsable = array(
	array("ALAMEDA 1831 Local Comercial LOCAL 1 - 2, Santiago, Región Metropolitana", "Arrendada", "Rol principal", "59-77", "Sin valor", "No existe valor", "No existe valor"),
	array("ALAMEDA 1831 Local Comercial LOCAL 1 - 2, Santiago, Región Metropolitana", "Arrendada", "ALAMEDA 1831", "59-78", "Sin valor", "No existe valor", "No existe valor"),
	array("ALAMEDA 1831 Local Comercial LOCAL 1 - 2, Santiago, Región Metropolitana", "Arrendada", "ALAMEDA 1831", "59-57", "Sin valor", "No existe valor", "No existe valor"),
	array("ALCALDE ALMANZOR URETA # 1220 Departamento 202, Providencia, Región Metropolitana", "Arrendada", "Rol principal", "1914-111", "Sin valor", "No existe valor", "No existe valor"),
	array("Alcalde Pedro Alarcón 997 Departamento 176-B ***GOLD+SEGURO***, San Miguel, Región Metropolitana", "Arrendada", "Alcalde Pedro AlarcónDpto. 997 Departamento 176-B", "2148-594", "Sin valor", "No existe valor", "No existe valor")
);

$dataTableNoResponsable = array(
	array("7MA AVENIDA 1234 Departamento DPTO 93 BD 51 BX19 (R), San Miguel, Región Metropolitana", "Desocupada", "Rol principal", "6253-92", "Sin valor", "No existe valor", "No existe valor"),
	array("A. VESPUCIO 1264 Departamento DEPTO. 602 BX 196 BD 176, Maipú, Región Metropolitana", "Arrendada", "Rol principal", "2200-75", "Sin valor", "No existe valor", "No existe valor"),
	array("A. VESPUCIO 1264 Departamento DEPTO. 902-A BX-902, BD-147 reparaciones dueño ***CORRETAJE MAIPU PAJARITOS***, Maipú, Región Metropolitana", "Desocupada", "Rol principal", "2200-111", "Sin valor", "No existe valor", "No existe valor"),
	array("A.PEDRO ALARCON 963 Departamento , D-903 A BD57 BX114, San Miguel, Región Metropolitana", "Desocupada", "Rol principal", "2146-61", "Sin valor", "No existe valor", "No existe valor"),
	array("ABATE MOLINA 4 Chalet Nº2670, Maipú, Región Metropolitana", "Desocupada", "Rol principal", "8068-101", "Sin valor", "No existe valor", "No existe valor"),

);
