<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$token	= @$_GET["token"];

//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=pago&view=pago&token=$token&nav=$nav'");
if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=pago&view=pago_list";
}	
//************************************************************************************************************


$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/pago/cabecera',$data);		
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result = $result_r;
}//foreach($result_json as $result)
}

$lista_items_pago = "";
$resultado = $services->sendPostNoToken($url_services.'/pago/detalle',$data);		
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$valor_formateado = formatea_number($result_r->monto_pagado,$_SESSION["cant_decimales"],$_SESSION["separador_mil"]);
	
	$lista_items_pago = $lista_items_pago."    <tr>
	  <td height='28'>$result_r->texto_linea</td>
	  <td height='28' align='right'>$valor_formateado</td>
	  <td height='28'>$result_r->cuotas</td>
	</tr>";
}//foreach($result_json as $result)
}


$lista_items_pago = "
 <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" class='tabla-propiedad'>
  <tbody>
	<tr >
	  <td height='28'><strong>Descripción</strong></td>
	  <td height='28'><strong>Monto</strong></td>
	  <td height='28'><strong>Cuota</strong></td>
	</tr>
	$lista_items_pago
  </tbody>
</table>
<br>
";

?>