<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token	= @$_GET["token"];
$id_company 	= $_SESSION["rd_company_id"];

//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=pack&view=pack&token=$token");

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=pack&view=pack_list";
}	

//************************************************************************************************************


$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/packCab/token',$data);		
$result = json_decode($resultado); 



$data_productos = array("idPackCab" => @$result->idPackCab,"idEmpresa" => $id_company);							
$resp_productos = $services->sendPostNoToken($url_services.'/packCab/productForPack',$data_productos);	
$lista_productos = "";
if($resp_productos){
$result_json = json_decode($resp_productos); 
foreach($result_json as $result_r) {
	$eliminar ="";

	if(@$result_r->autorizado == 'S'){
		$activar = "<a id='link_$result_r->token' href='javascript: desactivarProducto(\"$result_r->token\",\"$token\");'><i id='icono_$result_r->token' class='far fa-check-circle' style='font-size: 17px;'></i></a>";	
	}else{
		$activar = "<a id='link_$result_r->token' href='javascript: activarProducto(\"$result_r->token\",\"$token\");'><i id='icono_$result_r->token' class='far fa-circle' style='font-size: 17px;'></i></a>";	
	}	

	$lista_productos = $lista_productos."    <tr>
	  <td height='28' align='center'>$activar</td>
	  <td height='28'>$result_r->tipo_responsable</td>
	  <td height='28'>$result_r->tipo_producto</td>
	  <td height='28'><a href='index.php?component=producto&view=producto&token=$result_r->token&nav=$pag_origen'>$result_r->descripcion_prod</a></td>
	  <td height='28'>$result_r->tipo_monto</td>
	  <td height='28' align='right'>$result_r->valor</td>
	  <td height='28'>$result_r->tipo_moneda</td>
	  <td height='28' align='center'>$result_r->renovable</td>
	  <td height='28' align='center'>$result_r->editable</td>
	  <td height='28' align='center'>$result_r->reajustable</td>
	  <td height='28' align='center'>$result_r->paga_iva</td>
	</tr>";
}//foreach($result_json as $result)
}

$lista_productos = "
 <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" class='tabla-propiedad'>
  <tbody>
	<tr >
	  <td height='28'><strong>Incluido</strong></td>	
	  <td height='28'><strong>Responsable</strong></td>	
	  <td height='28'><strong>Tipo Producto</strong></td>	
	  <td height='28'><strong>Producto</strong></td>
	  <td height='28'><strong>Tipo Monto</strong></td>
	  <td height='28'><strong>Valor</strong></td>
	  <td height='28'><strong>Tipo Moneda</strong></td>
	  <td height='28'><strong>Renovable</strong></td>
	  <td height='28'><strong>Editable</strong></td>
	  <td height='28'><strong>Reajustable</strong></td>
	  <td height='28'><strong>Paga IVA</strong></td>
	</tr>
	$lista_productos
  </tbody>
</table>
<br>
";

$opcion_activo="<option selected value='S'>Si</option>";

if(@$result->activo=="N"){
	$opcion_activo=$opcion_activo."<option selected value='N'>No</option>";
}else{
	$opcion_activo=$opcion_activo."<option value='N'>No</option>";
}

?>