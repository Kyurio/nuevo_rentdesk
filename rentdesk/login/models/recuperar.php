<?php
session_start();
include("../../configuration.php");
include("../../includes/funciones.php");
include("../../includes/services_util.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_base = $config->urlbase;
$url_services = $config->url_services;

$email	     = @$_POST["email"];


//Formateo del rut, Ej: 11.111.111-1,  15.223.443-K
//$rut_empresa = formatea_rut($rut_empresa);



	$data = array("email" => $email);							
	$resultado = $services->sendPostNoToken($url_services.'/usuario/olvido',$data);							
	$json = json_decode ($resultado);
							
							
	
	if ($json->status == 'OK'){
		
		
		$hoy		= date("d-m-y");
		$asunto 	= "Recuperación de contraseña";
		$mensaje 	= "<strong>Estimado(a) <br>$json->nombreUsuario,</strong><br><br>Se ha solicitado la recuperación de su contraseña. Para obtener una contraseña haga click en el siguiente enlace y siga las instrucciones:<br><a href='$url_base/index.php?toc=$json->token'>haga click aquí para ir a recuperar contraseña</a> <br> <br><strong>$config->sitename</strong><br>*Este email se ha generado de manera automática. No responder.";
		$nivel		= "2";
		$logo 		= "logo_email.png";

		$nombre		= $json->nombreUsuario;
		$from 		= $config->sitename;
				
		//envia_mail($from, $nombre, $email, $asunto, $mensaje, $nivel, $logo );

		echo "xxx,1xxx,";  
	}else{				     
	echo "xxx,0xxx,";  //email no existe
	} 

?>