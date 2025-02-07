<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company 	= $_SESSION["rd_company_id"];
$token_contrato	= @$_GET["token_contrato"];


//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=contrato&view=contrato_pack&token_contrato=$token_contrato&nav=$nav");

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=contrato&view=contrato&token=$token_contrato";
}	

//************************************************************************************************************
$query = "	SELECT pc.id_pack_cab,pc.token, 
			pc.descripcion,(SELECT array_to_json(array_agg(vis.*))
							FROM
							(SELECT p.token, p.descripcion_prod, p.editable, p.valor, p.min_valor,
									tr.descripcion tipo_responsable,tmonto.descripcion tipo_monto,tm.descripcion tipo_moneda,
									p.id_tipo_producto
							FROM arpis.pack_det pd,
								 arpis.producto p,
								 arpis.tipo_responsable tr,
								 arpis.tipo_monto tmonto,
								 arpis.tipo_moneda tm
							WHERE pd.id_pack_cab = pc.id_pack_cab
							AND p.id_producto = pd.id_producto
							AND pd.activo = 'S'
							AND p.activo = 'S'
							AND tm.id_tipo_moneda = p.id_tipo_moneda
							AND tr.id_tipo_responsable = p.id_tipo_responsable
							AND tmonto.id_tipo_monto = p.id_tipo_monto
							) vis) datos 
			FROM arpis.pack_cab pc	
			WHERE pc.activo = 'S'
			AND pc.id_empresa = $id_company
			ORDER BY pc.descripcion
			";					
$data = array("consulta" => $query);	
$resultado = $services->sendPostNoToken($url_services.'/util/objeto',$data);

$opcion_pack = "<option value=''>Seleccione</option>";
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$token_pack		= $result_r->token;
		$nombre_pack 	= $result_r->descripcion;
		$datos 			= json_encode($result_r->datos);
		
		$opcion_pack = $opcion_pack . "<option value='$token_pack' data-datos='$datos' >$nombre_pack</option>";
	}	
}

$fecha = date('d-m-Y');
$separador_mil = $_SESSION["separador_mil"];
$opcion_pack = "<select id='pack' name='pack' class='form-control' required onChange='cambiaPack(\"$fecha\",\"$separador_mil\");'>
$opcion_pack
</select>";

?>