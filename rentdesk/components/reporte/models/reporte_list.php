<?php
@include("../../includes/sql_inyection.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;



$token	= @$_GET["token"];
$id_company = $_SESSION["rd_company_id"]; 

$rol_usuario = $_SESSION["usuario_rol"];

$listado = "";

 
 
	$query = "	SELECT r.*
				FROM arpis.menu_rol mr,
				arpis.menu m,
				arpis.rep_reporte r
				WHERE mr.id_rol = $rol_usuario
				AND m.id_menu = mr.id_menu
				AND concat('REP_',r.id_reporte) = m.ref_externa
				AND m.tipo_menu = 5 ";			
	$data = array("consulta" => $query);	
	$resultado = $services->sendPostNoToken($url_services.'/util/objeto',$data);
	
	if($resultado){
		$result_json3 = json_decode($resultado); 
		foreach($result_json3 as $result_r3) {
			$result3= $result_r3;
			$listado = $listado . "<tr role=\"row\" class=\"odd\">
										<td class=\"sorting_1\">$result_r3->descripcion</td>
										<td class=\"sorting_1\"><a href='index.php?component=reporte&view=reporte&t=$result_r3->token'><i class=\"fas fa-database\"></i></a></td>
									</tr>";
		}	
	}
	
 


?>