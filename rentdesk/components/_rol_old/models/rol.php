<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token	= @$_GET["token"];
$id_company 	= $_SESSION["rd_company_id"];


$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/rol/token',$data);		
$result = json_decode($resultado); 



$data_menu = array("idRol" => @$result->idRol,
				   "idEmpresa" => @$id_company );							
$resp_menus = $services->sendPostNoToken($url_services.'/menu/menuForRol',$data_menu);	
$menus = json_decode($resp_menus);

$lista_menu = array();
$permisos = "";
foreach($menus as $menu) {
	
	$autorizado = "";
	
	if($menu->autorizado == "S" ){
	$autorizado = " checked ";
	}else{
	$autorizado = " ";
	}
	
	$token_permiso = $menu->token;
	array_push($lista_menu, "$menu->tipo_menu<input type=\"checkbox\" name=\"permiso_$token_permiso\" value=\"1\" $autorizado > $menu->nombre<br>");
	
}//while($result2 = $mysql->f_obj($sql2))

//sort($lista_menu);

$k=0;
$t=0;

foreach ($lista_menu as &$valor) {
	
	$t = $valor[0];

	if($t!=$k){
		
		if($t==1)
		$permisos = $permisos."<br><strong>Men&uacute; Lateral</strong><br>";

		if($t==2)
		$permisos = $permisos."<br><strong>Men&uacute; de Configuraciones</strong><br>";

		if($t==3)
		$permisos = $permisos."<br><strong>Men&uacute; Home</strong><br>";	
	
		if($t==4)
		$permisos = $permisos."<br><strong>Cambios de Estado</strong><br>";	
	
		if($t==5)
		$permisos = $permisos."<br><strong>Reportes</strong><br>";	
	
		if($t==6)
		$permisos = $permisos."<br><strong>Carga Masiva</strong><br>";	
	
		if($t==7)
		$permisos = $permisos."<br><strong>Permisos Especiales</strong><br>";	
		
		$k=$t;
	}//if($k!=$t)

$valor = substr($valor,1);
   $permisos = $permisos.$valor;
}


?>