<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


$token	= @$_GET["token"];
$token_propiedad	= @$_GET["token_propiedad"];
$id_company = $_SESSION["rd_company_id"]; 
$id_usuario = $_SESSION["rd_usuario_id"];

//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=visita&view=visita&token=$token&token_propiedad=$token_propiedad&nav=$nav'");

$menu_detalle = "";
if($token!=""){
	$menu_detalle = " | <a href=\"index.php?component=visita&view=visita_detalle&token=$token&token_propiedad=$token_propiedad&nav=$nav\"   >Detalle de la visita</a>";
}else{
	$token = "0";
}	

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=visita&view=visita";
}	
//************************************************************************************************************
 
 

$data = array("token" => $token,
			  "idEmpresa" => $id_company);				  
$resultado = $services->sendPostNoToken($url_services.'/visita/token',$data);
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$result = $result_r;
	}//foreach($result_json as $result)
}

$id_visita 			= @$result->id_visita;
$tipo				= @$result->tipo;
$fecha				= @$result->fecha;
$hora				= @$result->hora;
$direccion 			= @$result->direccion;
$administradora		= @$result->administradora;
$correo_solicitante	= @$result->correo_solicitante;
$correo_arrendatario= @$result->correo_arrendatario;
$arrendatario_recibe= @$result->arrendatario_recibe;
$rut				= @$result->rut;
$estado_visita      = @$result->id_estado_visita;
$inspector          = @$result->inspector;

$readonly = "";
if($token_propiedad != ""){
$readonly = "readonly";	

$query = "select concat(p.direccion,' ',p.numero,' ',p.numero_depto) direccion, 
				u.nombre_usuario administradora,
				   u.email correo_solicitante,
				   u.email correo_arrendatario,
				   concat(a.nombre,' ',a.apellido_pat,' ',a.apellido_mat) arrendatario,
				   concat(a.num_documento,'-',a.digito_verificador) rut
			from arpis.propiedad p,
				 arpis.contrato_cab cc,
				 arpis.usuario u,
				 arpis.persona a
			where p.token = '$token_propiedad'
			and cc.id_propiedad = p.id_propiedad
			and cc.id_estado_contrato = 1
			and u.id_usuario = cc.id_usuario
			and a.id_persona = id_cliente ";
$data = array("query" => $query);	
$resultado3 = $services->sendPostNoToken($url_services.'/visita/object',$data);
if($resultado3){
	$result_json3 = json_decode($resultado3); 
	foreach($result_json3 as $result_r3) {
		$result3 = $result_r3;
		
		$direccion = $result3->direccion;
		$administradora =  $result3->administradora;
		$correo_solicitante = $result3->correo_solicitante;
		$correo_arrendatario = $result3->correo_arrendatario;
		$arrendatario_recibe =  $result3->arrendatario;
		$rut =  $result3->rut;
	}//foreach($result_json as $result)
}
}

if($fecha=="")
$fecha  = date("Y-m-d");	

$fecha = fecha_postgre_a_normal(@$fecha);

if($id_visita==""){
$id_visita = "0";
}
	
$imagen_rut = "";
$query = "SELECT a.* FROM arpis.archivo a  WHERE id_referencia = '$id_visita' AND componente ='visita' AND titulo='rut' ";
$data = array("query" => $query);	
$resultado = $services->sendPostNoToken($url_services.'/archivo/archivo',$data);
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$result2 = $result_r;
		$imagen_rut = @$result2->archivo;
	}//foreach($result_json as $result)
}

if($imagen_rut!="")
$imagen_rut = "<img src='upload/rut/$imagen_rut' alt='Rut' style='width:200px; height:auto;'>";


$query = "SELECT a.* FROM arpis.visita_respuesta a WHERE id_visita = '$id_visita' ";
$data = array("query" => $query);	
$resultado = $services->sendPostNoToken($url_services.'/visita/object',$data);
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$result2 = $result_r;
	}//foreach($result_json as $result)
}	

/*Proceso para determinar que tipo de visita se pueden realizar */
$select_tipo="";
if($token=="0"){
	$token = "";
	if($token_propiedad==""){
		$select_tipo="<select id='tipo' name='tipo' class='form-control' requiered disabled >
			<option value='Checkin'>Checkin</option>
			</select>";
	}else{
		$select_tipo="<select id='tipo' name='tipo' class='form-control' required data-validation-required >
			<option value=''>Seleccione</option>
			<option value='Checkout'>Checkout</option>
			<option value='Rutina'>Rutina</option>
			</select>";
	}
}else{
	if($tipo!=""){
		$select_tipo="<select id='tipo' name='tipo' class='form-control' required data-validation-required disabled>";
		if($tipo == 'Checkin'){		
			$select_tipo= $select_tipo. " <option value='Checkin' selected>Checkin</option> ";
		}else{
			$select_tipo= $select_tipo. " <option value='Checkin'>Checkin</option> ";
		}

		if($tipo == 'Checkout'){		
			$select_tipo= $select_tipo. " <option value='Checkout' selected>Checkout</option> ";
		}else{
			$select_tipo= $select_tipo. " <option value='Checkout'>Checkout</option> ";
		}

		if($tipo == 'Rutina'){		
			$select_tipo= $select_tipo. " <option value='Rutina' selected>Rutina</option> ";
		}else{
			$select_tipo= $select_tipo. " <option value='Rutina'>Rutina</option> ";
		}	

		$select_tipo= $select_tipo. " </select> ";	
	}
}


//************************************************************************************************************
$disabled_estado = "";
if(!$token==""){
	/*Verifica si tiene el permiso para editar el estado*/
	$query_count = "select 1 
					from arpis.usuario u,
						 arpis.menu_rol mr,
						 arpis.menu m
					where u.id_usuario = $id_usuario
					and mr.id_rol = u.id_rol
					and m.id_menu = mr.id_menu
					and m.ref_externa = 'VISITA' ";

	$data = array("consulta" => $query_count);							
	$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
	$cantidad_registros =$resultado;

	if(!$cantidad_registros){
		$disabled_estado = "disabled";
	}else{
		if($cantidad_registros > 0){
			$disabled_estado = "";
		}else{
			$disabled_estado = "disabled";
		}		
	}	
}

$opcion_estado_visita = "<option value=''>Seleccione</option>";
$data_estado_visita = array("idEmpresa" => $id_company);							
$resp_estado_visita = $services->sendPostNoToken($url_services.'/estadoVisita/listaByEmpresa',$data_estado_visita);	
$json_estado_visitas = json_decode($resp_estado_visita);

foreach($json_estado_visitas as $estado_visita_r) {

$select_estado_visita = "";
if(@$estado_visita == @$estado_visita_r->idEstadoVisita)
$select_estado_visita = " selected ";


$opcion_estado_visita = $opcion_estado_visita . "<option value='$estado_visita_r->idEstadoVisita' $select_estado_visita >$estado_visita_r->descripcion</option>";
}//foreach($roles as $rol)

$opcion_estado_visita = "<select id='estado_visita' name='estado_visita' $disabled_estado class='form-control' required >
$opcion_estado_visita
</select>";


?>