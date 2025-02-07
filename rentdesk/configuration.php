<?php


// if (!isset($id_usuario)) {
// 	header('Location: https://rentdesk.fuenzalida.com/');
// }

class Config
{

	// variables globlaes dte
	public $url_DTE = 'https://dteqa.arpis.cl/WSFactLocal/DteLocal.asmx?WSDL';
	//public $url_DTE = 'https://bes-dte.bestechnology.cl/wsconsulta/avanzado.asmx?wsdl';
	
	//public $wsdl = "https://dteqa.arpis.cl/wsconsulta/avanzado.asmx?WSDL";
	public $wsdl = "https://bes-dte.bestechnology.cl/wsconsulta/avanzado.asmx?wsdl";
	
	// variables globlaes dte
	public $rut = '77367969-K';
	public $rut_empresa = '77367969K';
	public $rut_certificado = '6285461-8';
	public $rut_receptor = '60803000-K';
	public $rut_sii = '60803000-K';
	public $razon_social_emisor = 'MEDITERRANEO RENTAS INMOBILIARIAS SPA';
	public $giro_emisor = 'Negocios Inmobiliarios';
	public $dir_origen = 'Av. Andres Bello 2777 Oficina 1902';
	public $comuna_origen = 'Las Condes';
	public $ciudad_origen = 'Santiago';
	public $cdg_item_tipo = '';

	
	public $version_app = '1.5.02';
	//public $url_services = 'http://localhost:8081/restful/arpis'; // LOCAL
	public $url_services = 'https://rentdesk-qa.fuenzalida.com/restful/arpis';  //  PRODUCCIÓN
	public $url_reportes_export = 'https://rentdesk-qa.fuenzalida.com/centralReportes/Ejecutar';
	public $url_reportes_eje = 'https://rentdesk-qa.fuenzalida.com/centralReportes/EjecutaToken';
	public $url_reportes_reg = 'https://rentdesk-qa.fuenzalida.com/centralReportes/Registrar';

	// public $url_services = 'https://rentdesk.fuenzalida.com/restful/arpis';  //  PRODUCCIÓN
	// public $url_reportes_export = 'https://rentdesk.fuenzalida.com/centralReportes/Ejecutar';
	// public $url_reportes_eje = 'https://rentdesk.fuenzalida.com/centralReportes/EjecutaToken';
	// public $url_reportes_reg = 'https://rentdesk.fuenzalida.com/centralReportes/Registrar';
	
	//public $url_reportes_export = 'http://localhost:8081/centralReportes/Ejecutar';
	//public $url_reportes_eje = 'http://localhost:8081/centralReportes/EjecutaToken';
	//public $url_reportes_reg = 'http://localhost:8081/centralReportes/Registrar';
	//public $url_web 	= 'https://rentdesk.fuenzalida.com/'; // PRODUCCIÓN
	public $url_web 	= 'http://localhost:8080/rentdesk/';  // LOCAL
	public $zona_horaria = "America/Santiago";
	public $offline = '0';
	public $maxSizeMB = '2'; //Tamaño maximo en MB de los archivos
	public $sitename = 'ARPIS';
	public $urlbase = '.';
	public $list_limit = '20';
	public $access = '1';
	public $debug = '0';
	public $debug_lang = '0';
	public $dbtype = 'postgresql';
	public $apiKey = '532d17d72a0a45f1219fcd915076f5bda689d25b';
	public $apiDir = 'http://api.sbif.cl/api-sbifv3/recursos_api/';

	public $error_reporting = 'default';

	public $smtpauth = '0';
	public $smtpsecure = 'none';
	public $smtpport = '25';

	public $log_path = '';
	public $lifetime = '180';
	public $offline_message = 'Sistema cerrado por tareas de mantenimiento.<br />Por favor, inténtelo nuevamente más tarde.';

	//config phpmailer
	public $email_host = 'smtp.gmail.com';
	public $email_user = 'norespondergracias@fuenzalida.com';
	public $email_pass = 'Qf6PcXk5';
	public $email_from = 'norespondergracias@fuenzalida.com';
	public $email_name = "Fuenzalida Propiedades";
	public $email_reply = 'norespondergracias@fuenzalida.com';
	public $email_smtpport = '465';




	
} //class Config
