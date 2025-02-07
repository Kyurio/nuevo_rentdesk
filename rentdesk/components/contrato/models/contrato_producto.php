<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company 	= $_SESSION["rd_company_id"];
$token			= @$_GET["token"];
$token_contrato	= @$_GET["token_contrato"];


//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=contrato&view=contrato_producto&token_contrato=$token_contrato&nav=$nav");

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=contrato&view=contrato&token=$token_contrato";
}	


//************************************************************************************************************


$readonly = "readonly";
$puede_guardar = "N";
if($token==""){
	$readonly = "";
	$puede_guardar = "S";
}	

$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/contratoDet/token',$data);	

if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result = $result_r;
}//foreach($result_json as $result)
}

//************************************************************************************************************

$opcion_producto = "<option value=''>Seleccione</option>";
$data_producto = array("idEmpresa" => $id_company);							
$resp_producto = $services->sendPostNoToken($url_services.'/producto/select',$data_producto);	
$productos = json_decode($resp_producto);

foreach($productos as $producto_r) {

$select_producto = "";
if(@$result->id_producto == @$producto_r->id_producto)
$select_producto = " selected ";

$editable = @$producto_r->editable;
$valor = @$producto_r->valor;
$min_valor = @$producto_r->min_valor;

$opcion_producto = $opcion_producto . "<option value='$producto_r->token' $select_producto data-editable='$editable' data-valor='$valor' data-min_valor='$min_valor' >$producto_r->descripcion_prod</option>";
}//foreach($roles as $rol)

$opcion_producto = "<select id='producto' name='producto' class='form-control' required  $readonly onChange='cambiaProducto();'>
$opcion_producto
</select>";

//************************************************************************************************************

$valor_arriendo = 0;
$data = array("tokenContrato" => $token_contrato,"idEmpresa" => $id_company);							
$resultado2 = $services->sendPostNoToken($url_services.'/contratoDet/valorArriendo',$data);	

if($resultado2){
$result_json2 = json_decode($resultado2); 
foreach($result_json2 as $result_r2) {
	$result2 = $result_r2;
	$valor_arriendo = $result2->valorarriendo;
}//foreach($result_json as $result)
}
//************************************************************************************************************
?>