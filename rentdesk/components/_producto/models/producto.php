<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$token	= @$_GET["token"];


$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/producto/token',$data);	
$result = json_decode($resultado); 

//***********************************************************************************************************
$opcion_activo="<option selected value='S'>Si</option>";

if(@$result->activo=="N"){
	$opcion_activo=$opcion_activo."<option selected value='N'>No</option>";
}else{
	$opcion_activo=$opcion_activo."<option value='N'>No</option>";
}

//***********************************************************************************************************
$opcion_editable="<option selected value='S' >Si</option>";

if(@$result->editable=="N"){
	$opcion_editable=$opcion_editable."<option selected value='N'>No</option>";
}else{
	$opcion_editable=$opcion_editable."<option value='N'>No</option>";
}

//***********************************************************************************************************
$opcion_monto_mayor="<option selected value='N' >No</option>";

if(@$result->montoMayor=="S"){
	$opcion_monto_mayor=$opcion_monto_mayor."<option selected value='S'>Si</option>";
}else{
	$opcion_monto_mayor=$opcion_monto_mayor."<option value='S'>Si</option>";
}

//***********************************************************************************************************
$opcion_seleccionable="<option selected value='S' >Si</option>";

if(@$result->seleccionable=="N"){
	$opcion_seleccionable=$opcion_seleccionable."<option selected value='N'>No</option>";
}else{
	$opcion_seleccionable=$opcion_seleccionable."<option value='N'>No</option>";
}

//***********************************************************************************************************
$opcion_reajustable="<option selected value='N'>No</option>";

if(@$result->reajustable=="S"){
	$opcion_reajustable=$opcion_reajustable."<option selected value='S'>Si</option>";
}else{
	$opcion_reajustable=$opcion_reajustable."<option value='S'>Si</option>";
}
//***********************************************************************************************************
$opcion_paga_iva="<option selected value='N'>No</option>";

if(@$result->pagaIva=="S"){
	$opcion_paga_iva=$opcion_paga_iva."<option selected value='S'>Si</option>";
}else{
	$opcion_paga_iva=$opcion_paga_iva."<option value='S'>Si</option>";
}
//***********************************************************************************************************
$opcion_proporcional_mes="<option selected value='N'>No</option>";

if(@$result->proporcionalMes=="S"){
	$opcion_proporcional_mes=$opcion_proporcional_mes."<option selected value='S'>Si</option>";
}else{
	$opcion_proporcional_mes=$opcion_proporcional_mes."<option value='S'>Si</option>";
}
//************************************************************************************************************

$opcion_tipo_producto = "<option value=''>Seleccione</option>";
$data_tipo_producto = array("idEmpresa" => $id_company);							
$resp_tipo_producto = $services->sendPostNoToken($url_services.'/tipoProducto/listaByEmpresa',$data_tipo_producto);	
$tipo_productos = json_decode($resp_tipo_producto);

foreach($tipo_productos as $tipo_producto_r) {
	

$select_tipo_producto = "";
if(@$result->tipoProducto->idTipoProducto == @$tipo_producto_r->idTipoProducto)
$select_tipo_producto = " selected ";

$tipo_resp_pre = @$tipo_producto_r->idTipoResponsablePredefinido;

$opcion_tipo_producto = $opcion_tipo_producto . "<option value='$tipo_producto_r->idTipoProducto' $select_tipo_producto data-id_tipo_responsable_pre='$tipo_resp_pre' >$tipo_producto_r->descripcion</option>";
}//foreach($roles as $rol)

$opcion_tipo_producto = "<select id='tipo_producto' name='tipo_producto' class='form-control' required autofocus onChange='cambiaProducto(this);' >
$opcion_tipo_producto
</select>";

//************************************************************************************************************

$opcion_tipo_moneda = "<option value=''>Seleccione</option>";
$data_tipo_moneda = array("idEmpresa" => $id_company);							
$resp_tipo_moneda = $services->sendPostNoToken($url_services.'/tipoMoneda/listaByEmpresa',$data_tipo_moneda);	
$tipo_monedas = json_decode($resp_tipo_moneda);

foreach($tipo_monedas as $tipo_moneda_r) {

$select_tipo_moneda = "";
if(@$result->tipoMoneda->idTipoMoneda == @$tipo_moneda_r->idTipoMoneda)
$select_tipo_moneda = " selected ";


$opcion_tipo_moneda = $opcion_tipo_moneda . "<option value='$tipo_moneda_r->idTipoMoneda' $select_tipo_moneda >$tipo_moneda_r->descripcion</option>";
}//foreach($roles as $rol)

$opcion_tipo_moneda = "<select id='tipo_moneda' name='tipo_moneda' class='form-control' required >
$opcion_tipo_moneda
</select>";


//************************************************************************************************************

$opcion_tipo_monto = "<option value=''>Seleccione</option>";
$data_tipo_monto = array("idEmpresa" => $id_company);							
$resp_tipo_monto = $services->sendPostNoToken($url_services.'/tipoMonto/listaByEmpresa',$data_tipo_monto);	
$tipo_montos = json_decode($resp_tipo_monto);

foreach($tipo_montos as $tipo_monto_r) {

$select_tipo_monto = "";
if(@$result->tipoMonto->idTipoMonto == @$tipo_monto_r->idTipoMonto)
$select_tipo_monto = " selected ";


$opcion_tipo_monto = $opcion_tipo_monto . "<option value='$tipo_monto_r->idTipoMonto' $select_tipo_monto >$tipo_monto_r->descripcion</option>";
}//foreach($roles as $rol)

$opcion_tipo_monto = "<select id='tipo_monto' name='tipo_monto' class='form-control' required onChange='cambiaTipoMonto(this);'>
$opcion_tipo_monto
</select>";


//************************************************************************************************************

$opcion_tipo_responsable = "<option value=''>Seleccione</option>";
$data_tipo_responsable = array("idEmpresa" => $id_company);							
$resp_tipo_responsable = $services->sendPostNoToken($url_services.'/tipoResposable/listaByEmpresa',$data_tipo_responsable);	
$tipo_responsables = json_decode($resp_tipo_responsable);

foreach($tipo_responsables as $tipo_responsable_r) {

$select_tipo_responsable = "";
if(@$result->tipoResponsable->idTipoResponsable == @$tipo_responsable_r->idTipoResponsable)
$select_tipo_responsable = " selected ";

$opcion_tipo_responsable = $opcion_tipo_responsable . "<option value='$tipo_responsable_r->idTipoResponsable' $select_tipo_responsable >$tipo_responsable_r->descripcion</option>";
}//foreach($roles as $rol)

$opcion_tipo_responsable = "<select id='tipo_responsable' name='tipo_responsable' class='form-control' required >
$opcion_tipo_responsable
</select>";

//***********************************************************************************************************
$opcion_renovable="<option selected value='N'>No</option>";

if(@$result->renovable=="S"){
	$opcion_renovable=$opcion_renovable."<option selected value='S'>Si</option>";
}else{
	$opcion_renovable=$opcion_renovable."<option value='S'>Si</option>";
}

//************************************************************************************************************

$opcion_prod_monto_mayor = "<option value=''>Seleccione</option>";
$data_prod_monto_mayor = array("idEmpresa" => $id_company);							
$resp_data_prod_monto_mayor = $services->sendPostNoToken($url_services.'/producto/listaMMByEmpresa',$data_prod_monto_mayor);	
$tipo_resp_data_prod_monto_mayor = json_decode($resp_data_prod_monto_mayor);

foreach($tipo_resp_data_prod_monto_mayor as $tipo_resp_data_prod_monto_mayor_r) {

$select_tipo_resp_data_prod_monto_mayor = "";
if(@$result->idMontoMayor == @$tipo_resp_data_prod_monto_mayor_r->idProducto)
$select_tipo_resp_data_prod_monto_mayor = " selected ";


$opcion_prod_monto_mayor = $opcion_prod_monto_mayor . "<option value='$tipo_resp_data_prod_monto_mayor_r->idProducto' $select_tipo_resp_data_prod_monto_mayor >$tipo_resp_data_prod_monto_mayor_r->descripcionProd</option>";
}//foreach($roles as $rol)

$opcion_prod_monto_mayor = "<select id='prod_monto_mayor' name='prod_monto_mayor' class='form-control' required onChange='validaMontoMayor(this);'>
$opcion_prod_monto_mayor
</select>";

$id_prod_query = @$result->idProducto;
/*Verifica si el producto se encuentra incorporado en algun contrato, de ser asi no puede ser modificado.*/
$query_count = "SELECT * FROM arpis.contrato_det cd where cd.id_producto = $id_prod_query and cd.activo = 'S' limit 1 ";

$data_count = array("consulta" => $query_count);							
$resultado_count = $services->sendPostNoToken($url_services.'/util/count',$data_count);		
$cantidad_registros_count =$resultado_count;
if(!$cantidad_registros_count){
	$cantidad_registros_count = 0;
}	

$muestra_Guardar = "S";
if($cantidad_registros_count > 0){
	$muestra_Guardar = "N";
}


?>