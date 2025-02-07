<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$token	= @$_GET["token"];


//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=contrato&view=contrato&token=$token&nav=$nav");

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=contrato&view=contrato_list";
}	

//************************************************************************************************************


$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/contratoCab/token',$data);		
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result = $result_r;
}//foreach($result_json as $result)
}

$muestra_boton_productos = "N";
$muestra_boton_activar = "N";
$muestra_boton_pago = "N";
$puede_activar = "N";
if($token == ""){
@$result->id_estado_contrato = 0;	
}else{
	if(@$result->id_estado_contrato == "0"){
		$muestra_boton_activar = "S";
	}	
	if(@$result->id_estado_contrato == "1"){
		$muestra_boton_pago = "S";
	}	
	$muestra_boton_productos = "S";
}	

$token_eecc = @$result->estado_cuenta;

$url_pago = "index.php?component=eecc&view=eecc_pago&token=$token_eecc&token_contrato=$token&nav=$pag_origen";

//************************************************************************************************************

$opcion_estado_contrato = "<option value=''>Seleccione</option>";
$data_estado_contrato = array("idEmpresa" => $id_company);							
$resp_estado_contrato = $services->sendPostNoToken($url_services.'/estadoContrato/listaByEmpresa',$data_estado_contrato);	
$estado_contratos = json_decode($resp_estado_contrato);

foreach($estado_contratos as $estado_contrato_r) {

$select_estado_contrato = "";
if(@$result->id_estado_contrato == @$estado_contrato_r->idEstadoContrato)
$select_estado_contrato = " selected ";


$opcion_estado_contrato = $opcion_estado_contrato . "<option value='$estado_contrato_r->idEstadoContrato' $select_estado_contrato >$estado_contrato_r->descripcion</option>";
}//foreach($roles as $rol)

$opcion_estado_contrato = "<select id='estado_contrato' name='estado_contrato' class='form-control' disabled='true' readonly='true' required  >
$opcion_estado_contrato
</select>";

//************************************************************************************************************

$opcion_tipo_moneda = "<option value=''>Seleccione</option>";
$data_tipo_moneda = array("idEmpresa" => $id_company);							
$resp_tipo_moneda = $services->sendPostNoToken($url_services.'/tipoMoneda/listaByEmpresa',$data_tipo_moneda);	
$tipo_monedas = json_decode($resp_tipo_moneda);

foreach($tipo_monedas as $tipo_moneda_r) {

$select_tipo_moneda = "";
if(@$result->id_tipo_moneda == @$tipo_moneda_r->idTipoMoneda)
$select_tipo_moneda = " selected ";


$opcion_tipo_moneda = $opcion_tipo_moneda . "<option value='$tipo_moneda_r->idTipoMoneda' $select_tipo_moneda >$tipo_moneda_r->descripcion</option>";
}//foreach($roles as $rol)

$opcion_tipo_moneda = "<select id='tipo_moneda' name='tipo_moneda' class='form-control' required >
$opcion_tipo_moneda
</select>";


//************************************************************
$opcion_periodo_reajuste = "<option value=''>Seleccione</option>";

$select_periodo_reajuste = "";
if(@$result->mes_reajuste == 0){
	$select_periodo_reajuste = " selected ";
}
$opcion_periodo_reajuste = $opcion_periodo_reajuste . "<option value='0' $select_periodo_reajuste >Sin Reajuste</option>";

$select_periodo_reajuste = "";
if(@$result->mes_reajuste == 6){
	$select_periodo_reajuste = " selected ";
}
$opcion_periodo_reajuste = $opcion_periodo_reajuste . "<option value='6' $select_periodo_reajuste >Semestral</option>";

$select_periodo_reajuste = "";
if(@$result->mes_reajuste == 12){
	$select_periodo_reajuste = " selected ";
}
$opcion_periodo_reajuste = $opcion_periodo_reajuste . "<option value='12' $select_periodo_reajuste >Anual</option>";



$opcion_periodo_reajuste = "<select id='mes_reajuste' name='mes_reajuste' class='form-control' required >
$opcion_periodo_reajuste
</select>";

//************************************************************************************************************
$lista_productos = "";
$data = array("token" => $token,"idEmpresa" => $id_company);
$resultado = $services->sendPostNoToken($url_services.'/contratoDet/productos',$data);		
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$puede_activar = "S";
	$fecha_inicio = fecha_postgre_a_normal($result_r->fecha_inicio);
	$fecha_fin = fecha_postgre_a_normal($result_r->fecha_fin);
	$fecha_prox_vencimiento = fecha_postgre_a_normal($result_r->fecha_prox_vcto);
	$valor_formateado = formatea_number($result_r->valor_cuota,2,$_SESSION["separador_mil"]);
	$eliminar ="";

	if(@$result->id_estado_contrato != '3'){
		$eliminar = "<a href='javascript: deleteProducto(\"$result_r->token\",\"$token\");'><i class='far fa-trash-alt'></i></a>";	
	}

	if($result_r->status == "OK"){
		$lista_productos = $lista_productos."    <tr>
		  <td height='28'>$result_r->descripcion_prod</td>
		  <td height='28'>$result_r->texto_linea</td>
		  <td height='28'>$fecha_inicio</td>
		  <td height='28'>$fecha_fin</td>
		  <td height='28' align='right'>$valor_formateado</td>
		  <td height='28'>$result_r->moneda</td>
		  <td height='28'>$fecha_prox_vencimiento</td>
		  <td height='28'><a href='index.php?component=contrato&view=contrato_producto&token=$result_r->token&token_contrato=$token&nav=$pag_origen'><i class='fas fa-search'></i></a></td>
		  <td height='28'>$eliminar</td>
		</tr>";
	}
}//foreach($result_json as $result)
}

$lista_productos = "
 <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" class='tabla-propiedad'>
  <tbody>
	<tr >
	  <td height='28'><strong>Producto</strong></td>	
	  <td height='28'><strong>Descripción</strong></td>	
	  <td height='28'><strong>Fecha Inicio</strong></td>
	  <td height='28'><strong>Fecha Fin</strong></td>
	  <td height='28'><strong>Valor</strong></td>
	  <td height='28'><strong>Moneda</strong></td>
	  <td height='28'><strong>Prox. Vencimiento</strong></td>
	  <td height='28'><strong>Ver</strong></td>
	  <td height='28'><strong>Eliminar</strong></td>
	</tr>
	$lista_productos
  </tbody>
</table>
<br>
";

$existe_archivo = "N";

if(@$result->archivo_contrato != ""){
	if(@$result->id_estado_contrato != '3'){
		@$archivo = "<a href='javascript: borrarArchivo(\"$result->token\");'><i class='far fa-trash-alt'></i></a> <a href='upload/contrato/$result->archivo_contrato' target='_blank'> Ver Archivo <i class='fas fa-file'></i></a>";
		$existe_archivo = "S";
	}else{
		@$archivo = "<a href='upload/contrato/$result->archivo_contrato' target='_blank'> Ver Archivo <i class='fas fa-file'></i></a>";
		$existe_archivo = "S";
	}
}

//****************************************************************************************************************************
$link_arrendatario = "";
if(@$result->token_arrendatario!=""){
	$link_arrendatario = "<a data-fancybox data-type='iframe' data-src='index.php?component=arrendatario&view=arrendatario_iframe&token=$result->token_arrendatario&nav=Y29tcG9uZW50PWFycmVuZGF0YXJpbyZ2aWV3PWFycmVuZGF0YXJpb19saXN0' href='javascript:;'><i class='far fa-eye'></i>";
}

$link_propiedad = "";
if(@$result->token_propiedad!=""){
	$link_propiedad = "<a data-fancybox data-type='iframe' data-src='index.php?component=propiedad&view=propiedad_iframe&token=$result->token_propiedad&nav=Y29tcG9uZW50PWFycmVuZGF0YXJpbyZ2aWV3PWFycmVuZGF0YXJpb19saXN0' href='javascript:;'><i class='far fa-eye'></i>";
}

//************************************************************************************************************
$forzar_termino_contrato = "";
if(!$token==""){
	/*Verifica si tiene el permiso para forzar el termino de contrato*/
	$query_count = "select 1 
					from arpis.usuario u,
						 arpis.menu_rol mr,
						 arpis.menu m
					where u.id_usuario = $id_usuario
					and mr.id_rol = u.id_rol
					and m.id_menu = mr.id_menu
					and m.ref_externa = 'FORZAR_TERM_CONTRATO' ";

	$data = array("consulta" => $query_count);							
	$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
	$cantidad_registros =$resultado;

	if(!$cantidad_registros){
		$forzar_termino_contrato = "N";
	}else{
		if($cantidad_registros > 0){
			$forzar_termino_contrato = "S";
		}else{
			$forzar_termino_contrato = "N";
		}		
	}	
}

//************************************************************************************************************


?>