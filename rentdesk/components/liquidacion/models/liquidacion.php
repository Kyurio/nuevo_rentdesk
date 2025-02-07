<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$token	= @$_GET["token"];

//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=liquidacion&view=liquidacion&token=$token&nav=$nav'");
if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=liquidacion&view=liquidacion_list";
}	
//************************************************************************************************************

$propietario = "";
$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/liquidacion/cabecera',$data);	
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result = $result_r;
	$propietario = $result->nombre." ".$result->apellido_pat." ".$result->apellido_mat;
}//foreach($result_json as $result)
}

$total = 0;
$lista_items_liquidacion = "";
$resultado = $services->sendPostNoToken($url_services.'/liquidacion/detalle',$data);		
if($resultado ){
$pos = strpos($resultado , 'ERROR');
if ($pos === false) {	
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$total = $total + $result_r->valor;
	
	$valor_formateado = formatea_number($result_r->valor,$_SESSION["cant_decimales"],$_SESSION["separador_mil"]);
	
	$lista_items_liquidacion = $lista_items_liquidacion."    <tr>
	  <td height='28'>$result_r->descripcion</td>
	  <td height='28' align='right'>$valor_formateado</td>
	  <td height='28'>$result_r->moneda</td>
	  <td height='28'>$result_r->cuotas</td>
	</tr>";
}//foreach($result_json as $result)
}
}


$lista_items_liquidacion = "
 <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" class='tabla-propiedad'>
  <tbody>
	<tr >
	  <td height='28'><strong>Descripción</strong></td>
	  <td height='28'><strong>Monto</strong></td>
	  <td height='28'><strong>Moneda</strong></td>
	  <td height='28'><strong>Cuota</strong></td>
	</tr>
	$lista_items_liquidacion
  </tbody>
</table>
<br>
";


$link_propiedad = "";
if(@$result->token_propiedad!=""){
	$link_propiedad = "<a data-fancybox data-type='iframe' data-src='index.php?component=propiedad&view=propiedad_iframe&token=$result->token_propiedad&nav=Y29tcG9uZW50PWFycmVuZGF0YXJpbyZ2aWV3PWFycmVuZGF0YXJpb19saXN0' href='javascript:;'><i class='far fa-eye'></i></a>";
}

?>