<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;



$token	= @$_GET["token"];
$token_propiedad	= @$_GET["token_propiedad"];
$id_company = $_SESSION["rd_company_id"]; 


$i=0;

$id_visita ="";
$data = array("token" => $token,
			  "idEmpresa" => $id_company);		
$resultado = $services->sendPostNoToken($url_services.'/visita/token',$data);
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result = $result_r;
	$id_visita	= @$result->id_visita;
}//foreach($result_json as $result)	
}



//Select de los item generales****************************************************************************************************
$option_item = "";
$query = "SELECT a.* FROM arpis.visita_item a WHERE a.id_padre='0' ORDER BY titulo ";
$data = array("query" => $query);	
$resultado = $services->sendPostNoToken($url_services.'/visita/object',$data);
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result2= $result_r;
	$option_item = $option_item."<option value='$result2->token'>$result2->titulo</option>";
}	
}


$select_item = "
<select id='item' name='item' class='form-control' required data-validation-required >
<option value=''>Seleccione item a agregar</option>
$option_item
</select>
";

$tipo_ingreso = $result->tipo;
$tipo_ingreso_ori = $result->tipo;
$id_visita_checkin = "0";
if ($result->tipo == 'Checkout'){
	/*Si el tipo es checkout se debe buscar el ultimo checkin que tenga la propiedad*/
	$tipo_ingreso = $result->tipo."','Checkin";
	$query = "SELECT MAX(v.id_visita) id_visita_checkin
			   FROM arpis.propiedad p,
					 arpis.visita v
				WHERE p.token = '$token_propiedad'
				AND v.id_propiedad = p.id_propiedad
				AND v.tipo = 'Checkin'";			
	$data = array("consulta" => $query);	
	$resultado = $services->sendPostNoToken($url_services.'/util/objeto',$data);
	if($resultado){
		$result_json3 = json_decode($resultado); 
		foreach($result_json3 as $result_r3) {
			$result3= $result_r3;
			$id_visita_checkin = $result3->id_visita_checkin;
		}	
	}
	
}	

//Seleccion de los item generales agregados para esta visita**************************************************************************************
$item_general = "";
$token_item_check = "";
$resultado_checkout = "";

$query = "SELECT a.* FROM arpis.visita_item_check a WHERE a.id_visita='$id_visita' or a.id_visita='$id_visita_checkin' ORDER BY a.nombre ASC ";
$data = array("query" => $query);	
$resultado = $services->sendPostNoToken($url_services.'/visita/object',$data);
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$token_item_check = "";
	$sub_item = "";	
	$i++;
	$result= $result_r;
	
	
	$token_item_check = $result->token;
	$resultado_checkout = $result->resultado_checkout;
	$observacion_checkout = $result->observacion_checkout;
	
	//Seleccion de las preguntas para ese item***********************************************************************************
	$query = "SELECT a.* FROM arpis.visita_item a WHERE a.id_padre='$result->id_visita_item' ORDER BY a.titulo ASC ";
	$data = array("query" => $query);	
	$resultado = $services->sendPostNoToken($url_services.'/visita/object',$data);
	if($resultado){
		$result_json = json_decode($resultado); 
		foreach($result_json as $result_r1) {
			$result7 = $result_r1;
			$sub_item = $sub_item."<option value='$result->id_visita_item_check|$result7->titulo'>$result7->titulo</option>";
		}
	}		
	
	$sub_item = "
	<select id='subitem$i' name='subitem$i' class='form-control' required data-validation-required >
	<option value=''>Seleccione item a agregar</option>
	$sub_item
	</select>
	";
	
	//********************************************************************************************
	//Seleción de las respuestas guardadas******************************************************************
	$id_padre = "";
	
	$query = "SELECT a.* FROM arpis.visita_item a WHERE a.id_visita_item='$result->id_visita_item' ";
	$data = array("query" => $query);	
	$resultado = $services->sendPostNoToken($url_services.'/visita/object',$data);
	if($resultado){
		$result_json = json_decode($resultado); 
		foreach($result_json as $result_r2) {
			$result9 = $result_r2;
			$id_padre = $result9->id_padre;
		}
	}		
	
	$respuestas = "";
	$query = "SELECT a.* FROM arpis.visita_respuesta a WHERE a.id_visita_item_check='$result->id_visita_item_check' AND a.tipo_ingreso in ('$tipo_ingreso') ORDER BY titulo_item ASC ";
	$data = array("query" => $query);	
	$resultado = $services->sendPostNoToken($url_services.'/visita/object',$data);
	if($resultado){
		$result_json = json_decode($resultado); 
		
		foreach($result_json as $result_r3) {
			$v_tipo_diferente = "";
			$result8 = $result_r3;
			if($result8->tipo_ingreso != $tipo_ingreso_ori){
				$v_tipo_diferente = "<spam style='color:#00CD0E; font-weight:bold;'>* </spam>";
			}	
			$respuestas = $respuestas. "<tr><td>$v_tipo_diferente $result8->titulo_item </td><td>: $result8->respuesta </td> <td><a href='javascript: borrarSubItem(\"$result8->token\")'><i class='far fa-trash-alt'></i></a></td></tr>";
		}
	}		
					
	$respuestas = "<table class='tabla-visita'>$respuestas</table>";
	
	//********************************************************************************************************
	//SELECCION DE FOTOS**********************************************************************************************
	$fotos="";
	
	$query = "SELECT a.* FROM arpis.archivo a WHERE a.id_referencia='$result->id_visita_item_check' ORDER BY a.id_archivo ASC ";
	$data = array("query" => $query);	
	$resultado = $services->sendPostNoToken($url_services.'/visita/object',$data);
	if($resultado){
		$result_json = json_decode($resultado); 
		foreach($result_json as $result_r4) {
			$result44 = $result_r4;
			$fotos= $fotos."<a href='javascript: borrarFoto(\"$result44->token\")'><i class='fas fa-trash-alt' style='font-size:20px; color: #6c99e2;'></i></a><a href=\"upload/fotos/$result44->archivo\" data-fancybox=\"gallery\" data-caption=\"$result->nombre\"><img src=\"upload/fotos/$result44->archivo\" alt=\"\" style='width:auto; height:90px;' /></a>&nbsp; &nbsp;";
		}
	}	
		
	if($fotos!="")
	$fotos = "<div style='clear:both; width: 100%;'></div>$fotos";
	
	//****************************************************************************************************************
	
	$checkout_color = "gris";
	$color_boton = "dark";  //gris
	
	if($resultado_checkout == 1){
		$checkout_color = "verde";
		$color_boton = "verde"; //verde
	}else{
		if($resultado_checkout == 2){
			$checkout_color = "rojo";
			$color_boton = "danger"; //rojo
		}
	}		
	
	$text_val_checkout=""; 
	if($tipo_ingreso_ori == 'Checkout'){
	$text_val_checkout="	<div class='checkout-line-$checkout_color'>

							<form name='checkoutform$i' id='checkoutform$i' method='post' action='javascript: enviarCheckout(\"checkoutform$i\", \"$token_item_check\");' >

							<strong><i class='fas fa-circle checkout-circulo-$checkout_color' ></i> Checkout $result->nombre:</strong>
							<br>Checkout conforme: <a href='javascript: enviarCheckout(\"checkoutform$i\", \"1\", \"\" , \"$token_item_check\");'>
							<button type='button' class='btn btn-$color_boton  btn-checkout' style='margin:0px !important;'> Conforme </button></a>
							<br>Checkout con observaciones: 
							<textarea name='observacionescheck$i' class='form-control' id='observacionescheck$i' placeholder='' style='width:300px;' onblur='elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);' onkeydown='limita(this,255);'>$observacion_checkout</textarea>
							<a href='javascript: enviarCheckout(\"checkoutform$i\", \"2\",document.getElementById(\"observacionescheck$i\").value, \"$token_item_check\");'>
							<button type='button' class='btn btn-$color_boton  btn-checkout' style='margin:0px !important;'> Observar </button></a>

							</form>

							</div> ";
	}	
		

	$item_general = $item_general."
	<form name='formulario$i' id='formulario$i' method='post' action='javascript: enviarSubItem(\"formulario$i\",\"subitem$i\",\"respuesta$i\",\"$token\");' style='width:100%;'>
	<div class='row'>
		<div class='col-sm-2 form-group'>
				<div style='margin-top: 4px;'><strong>$result->nombre :</strong></div>
		</div> 
		<div class='col-sm-3 form-group'>
				$sub_item
		</div>
		<div class='col-sm-4 form-group'>
		<input type='text' class='form-control' name='respuesta$i' id='respuesta$i' placeholder='Respuesta' value='' required data-validation-required  />
		</div>
		<div class='col-sm-2 form-group'>
				<button type='submit' class='btn btn-primary' style='margin:0px !important;'> Agregar </button>
		</div>
		
		</div>
		</form>
		<div class='row'>
		<div class='col-sm-12'>
		<strong>Detalle $result->nombre :</strong><br>
		</div>
		
		</div>
		$respuestas 
		
		
		
		<div class='row'>
		<div class='col-sm-12'>
		<br> 
		<strong>Imágenes de $result->nombre :</strong><br>
		
		

<form name=\"form$i\" id=\"form$i\" method=\"post\" enctype=\"multipart/form-data\" action=\"javascript: enviarFotoMultiple('form$i','archivo$i','$result->token');\">	
 <input id=\"archivo$i"." "."\" name=\"archivo$i"."[]"."\" type=\"file\"  class=\"btn btn-success btn-xs\" multiple=\"\" />
 
 <button type=\"submit\" class=\"btn btn-primary\" > subir foto </button>
 
 $fotos
 
</form>

		
		
		
$text_val_checkout





		
		
		
		
		
		</div>
		
		</div>
		
		
		

		
		
		
		
		
		<br> <br> 
		<div class='linea-horizontal'></div>
		";
}
}	

?>