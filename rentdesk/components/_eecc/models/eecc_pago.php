<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$token	= @$_GET["token"];
$token_contrato	= @$_GET["token_contrato"];

//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=eecc&view=eecc_pago&token=$token&token_contrato=$token_contrato&nav=$nav");

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=eecc&view=eecc_list";
}	


//************************************************************************************************************


$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/eecc/cabecera',$data);	
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result = $result_r;
}//foreach($result_json as $result)
}

$monto_deuda = 0;
$data = array("token" => $token_contrato,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/eecc/consultaDeuda',$data);

if($resultado> 0 ){
	$monto_deuda = number_format($resultado,0, '.', '') ;
}


$lista_items_pago = "";
if ($monto_deuda > 0 ){
$data = array("token" => $token_contrato,"idEmpresa" => $id_company);
$resultado = $services->sendPostNoToken($url_services.'/eecc/detalleDeuda',$data);	
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$valor_formateado = formatea_number($result_r->valor_cuota,$_SESSION["cant_decimales"],$_SESSION["separador_mil"]);
	$lista_items_pago = $lista_items_pago."    <tr>
	  <td height='28'>$result_r->texto_linea</td>
	  <td height='28' align='right'>$valor_formateado</td>
	  <td height='28'>$result_r->moneda</td>
	  <td height='28'>$result_r->cuotas</td>
	</tr>";
}//foreach($result_json as $result)
}

}

$lista_items_pago = "
 <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" class='tabla-propiedad'>
  <tbody>
	<tr >
	  <td height='28'><strong>Descripción</strong></td>
	  <td height='28'><strong>Monto</strong></td>
	  <td height='28'><strong>Moneda</strong></td>
	  <td height='28'><strong>Cuota</strong></td>
	</tr>
	$lista_items_pago
  </tbody>
</table>
<br>
";

//************************************************************************************************************

$opcion_medio_pago = "<option value='' selected>Seleccione</option>";
$data_medio_pago = array("idEmpresa" => $id_company);							
$resp_medio_pago = $services->sendPostNoToken($url_services.'/tipoMedioPago/listaByEmpresa',$data_medio_pago);	
$medio_pagos = json_decode($resp_medio_pago);

foreach($medio_pagos as $medio_pago_r) {

$opcion_medio_pago = $opcion_medio_pago . "<option value='$medio_pago_r->idTipoMedioPago' >$medio_pago_r->descripcion</option>";
}//foreach($roles as $rol)

$cant_decimales = $_SESSION["cant_decimales"];
$separador_mil = $_SESSION["separador_mil"]; 
$opcion_medio_pago = "<select id='medio_pago' name='medio_pago' class='form-control' required  onChange='esCheque(this.value,\"$cant_decimales\",\"$separador_mil\");' >
$opcion_medio_pago
</select>";

//************************************************************************************************************

$opcion_banco = "<option value=''>Seleccione</option>";
$data_banco = array("idEmpresa" => $id_company);							
$resp_banco = $services->sendPostNoToken($url_services.'/banco/listaByEmpresa',$data_banco);	
$bancos = json_decode($resp_banco);

$options_bancos = "";

foreach($bancos as $banco_r) {

$select_banco = "";
if(@$result->banco->idBanco == @$banco_r->idBanco)
$select_banco = " selected ";
$opcion_banco = $opcion_banco . "<option value='$banco_r->idBanco' $select_banco >$banco_r->descripcion</option>";
}//foreach($roles as $rol)

$options_bancos = $opcion_banco;

$opcion_banco = "<select id='banco1' name='banco1' class='form-control' >
$opcion_banco
</select>";


//************************************************************************************************************
$link_propiedad = "";
if(@$result->token_propiedad!=""){
	$link_propiedad = "<a data-fancybox data-type='iframe' data-src='index.php?component=propiedad&view=propiedad_iframe&token=$result->token_propiedad&nav=Y29tcG9uZW50PWFycmVuZGF0YXJpbyZ2aWV3PWFycmVuZGF0YXJpb19saXN0' href='javascript:;'><i class='far fa-eye'></i></a>";
}


?>