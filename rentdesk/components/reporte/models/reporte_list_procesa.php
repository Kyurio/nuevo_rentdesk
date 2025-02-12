<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;



$id_company 	= $_SESSION["rd_company_id"];



if($inicio=="")
$inicio = 0;


/*Consulta Cantidad de registros
$query_count = "SELECT * 
				FROM arpis.contrato_cab cc,
					 arpis.persona ar,
					 arpis.propiedad pro,
					 arpis.estado_contrato ec,
					 arpis.usuario u,
					 arpis.comuna c
				WHERE ar.id_persona = cc.id_cliente
				AND pro.id_propiedad = cc.id_propiedad
				AND ec.id_estado_contrato = cc.id_estado_contrato
				AND u.id_usuario = cc.id_usuario
				AND c.id_comuna = pro.id_comuna
				AND (pro.token = '$token_propiedad' OR '' = '$token_propiedad')
				$busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
//Obtiene Json con objetos
$query= "SELECT cc.id_contrato,
				CASE WHEN ar.digito_verificador IS NULL THEN ar.num_documento ELSE CONCAT(ar.num_documento,'-',ar.digito_verificador) END num_documento,
				ar.nombre,ar.apellido_pat,ar.apellido_mat,	
				pro.rol,pro.direccion,pro.numero,pro.numero_depto,
				ec.descripcion estado,
				u.nombre_usuario,
				cc.token,
				c.descripcion comuna,
				ar.token token_arrendatario,
				pro.token token_propiedad
		 FROM arpis.contrato_cab cc,
			 arpis.persona ar,
			 arpis.propiedad pro,
			 arpis.estado_contrato ec,
			 arpis.usuario u,
			 arpis.comuna c
		WHERE ar.id_persona = cc.id_cliente
		AND pro.id_propiedad = cc.id_propiedad
		AND ec.id_estado_contrato = cc.id_estado_contrato
		AND u.id_usuario = cc.id_usuario 
		AND c.id_comuna = pro.id_comuna
		AND (pro.token = '$token_propiedad' OR '' = '$token_propiedad')
		$busqueda $orderby ";
$cant_rows = $num_reg;
$num_pagina = round($inicio/$cant_rows)+1;	
$data = array("consulta" => $query,"cantRegistros" => $cant_rows,"numPagina" => $num_pagina);	
$resultado = $services->sendPostNoToken($url_services.'/util/paginacion',$data);	
	
$json = json_decode($resultado);
}


//Proceso para iterar sobre el resultado
$coma = 0;
$signo_coma = "";
$datos		= ""; 
foreach($json as $result){
if($coma==1)
$signo_coma = ",";

$coma = 1;
$nav_return = codifica_navegacion("component=contrato&view=contrato_list&token_propiedad=$token_propiedad&nav=$nav");

$ver = "";
$ver = "<a href='index.php?component=contrato&view=contrato&token=$result->token&nav=$nav_return'><i class='fas fa-search'></i></a>";

$eliminar = "";
$eliminar = "<a href='javascript: deleteContrato(\\\"$result->token\\\");'><i class='far fa-trash-alt'></i></a>";

$link_arrendatario = "<a href='index.php?component=arrendatario&view=arrendatario&token=$result->token_arrendatario&nav=$nav_return'>";

$link_propiedad = "<a href='index.php?component=propiedad&view=propiedad&token=$result->token_propiedad&nav=$nav_return'>";

$datos = $datos ."
     $signo_coma
	 [
      \"$result->id_contrato\",
      \"$link_arrendatario $result->num_documento</a>\",
	  \"$link_arrendatario $result->nombre $result->apellido_pat $result->apellido_mat</a>\",
	  \"$link_propiedad $result->comuna, $result->direccion $result->numero $result->numero_depto </a>\",
	  \"$result->estado\",
	  \"$result->nombre_usuario\",
	  \"$ver\",
      \"$eliminar\"
    ]";
	
}//foreach($json as $result)

*/


echo "
<div></div>
<div></div>
<div></div>
<div></div>

";


?>