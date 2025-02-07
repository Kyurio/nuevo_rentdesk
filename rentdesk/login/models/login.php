<?php
session_start();
include("../../configuration.php");
include("../../includes/funciones.php");
include("../../includes/services_util.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_base = $config->urlbase;
$url_services = $config->url_services;

$accion      = @$_POST["accion"];
$correo	     = @$_POST["correo"];
//$password	 = @$_POST["password"];
$password	 = md5(@$_POST["password"]);



//Formateo del rut, Ej: 11.111.111-1,  15.223.443-K
$inicio = 1;
$cant_rows = 9999;
//$rut_empresa = formatea_rut($rut_empresa);

$queryUsuario = "SELECT activo,id
from  propiedades.cuenta_usuario 
where habilitado = true  and upper(correo) = upper('$correo') ";

$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryUsuario, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objUsuario = json_decode($resultado)[0];

if($objUsuario->activo != "true"){
	echo "|ERROR|";
	return;
}

if ($accion == "login") { //ingresar

	//var_dump("ENTRAMOS");

	/*LLAMADO A ENDPOINT LOGIN */
	$headers = array("correo" => $correo, "password" => $password);
	//var_dump($headers);
	$resultado = $services->sendGetNoToken($url_services . '/rentdesk/cuentas/login', null, $headers);
	//var_dump($url_services . '/rentdesk/cuentas/login');
	//var_dump("RESULTADO: ", $resultado);

	$json = json_decode($resultado);

	//var_dump("Resultado JSON");
	//var_dump($json);
	//var_dump("FIN  JSON");

	if (isset($json->accesoCorrecto) && $json->accesoCorrecto === true) {


		//var_dump("accesoCorrecto: ", $json->accesoCorrecto);




		$queryUsuario = "  select ci.vista , ci.ficha , ci.edicion from propiedades.cuenta_subsidiarias_usuarios csu, propiedades.cuenta_usuario cu  , 
		propiedades.cuenta_rol_componentes cr , propiedades.menu_componentes_items ci
		where upper(cu.correo) = upper('$correo') and cu.id = csu.id_usuario 
		and csu.id_rol  = cr.id_rol and  cr.id_componente_item = ci.id and ci.vista is not null";
		
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $queryUsuario, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		$objUsuarioAcceso = json_decode($resultado);
		//var_dump($queryUsuario);
		
		/*GUARDA DATOS DE ACCESOS */
		$_SESSION["sesion_rd_accesoS"] = serialize($objUsuarioAcceso);
		if (isset($objUsuarioAcceso)) {
			$_SESSION["rd_current_accesos"] = serialize($objUsuarioAcceso);
		}

		/*NUEVA LÓGICA SEMANA 01-03-2024 */
		//var_dump("---REGISTRO DE VARIABLES DE SESIÓN---");

		/*GUARDA DATOS DE LOGIN */
		$_SESSION["sesion_rd_login"] = serialize((object) [
			'correo' => $correo,
			'contrasena' => $password,
		]);

		/*GUARDA DATOS DE USUARIO */
		$_SESSION["sesion_rd_usuario"] = serialize((object) [
			'nombres' => @$json->nombres,
			'apellidoPaterno' => @$json->apellidoPaterno,
			'apellidoMaterno' => @$json->apellidoMaterno,
			'token' => @$json->token
		]);
		/*GUARDA DATOS DE EMPRESA */
		$_SESSION["sesion_rd_empresa"] = serialize((object) [
			'id' => @$json->empresa->id,
			'nombre' => @$json->empresa->nombre,
			'token' => @$json->empresa->token
		]);

		/*GUARDA DATOS DE SUBSIDIARIAS */
		$_SESSION["sesion_rd_subsidiarias"] = serialize(@$json->empresa->subsidiarias);
		if (isset($json->empresa->subsidiarias)) {
			$_SESSION["rd_current_subsidiaria"] = serialize(@$json->empresa->subsidiarias[0]);
		}
		//var_dump("------------------------------------------------------------");

		//var_dump("---OBTENCIÓN DE VARIABLES DE SESIÓN---");
		//var_dump("SESION USUARIO: ", unserialize($_SESSION["sesion_rd_usuario"]));
		//var_dump("SESION EMPRESA: ", unserialize($_SESSION["sesion_rd_empresa"]));
		//var_dump("SESION SUBSIDIARIAS: ", unserialize($_SESSION["sesion_rd_subsidiarias"]));
		//var_dump("------------------------------------------------------------");


		/*LLAMADO A ENDPOINT SUCURSALES */

		$current_subsidiaria = unserialize($_SESSION["rd_current_subsidiaria"]);

		$queryParamsSuc = array(
			'token_subsidiaria' => $current_subsidiaria->token
		);
		
		$num_reg = 10000;
		$inicio = 0;
		
		$query = "select cs.principal as \"subsidiariaPrincipal\" ,cs.token as \"subsidiariaToken\" ,cs2.casa_matriz as \"sucursalCasaMatriz\" ,
				cs2.habilitada  as \"sucursalHabilitada\" , cs2.nombre as \"sucursalNombre\" , cs2.token  as \"sucursalToken\"
                , cs2.id
				from propiedades.cuenta_subsidiaria cs,
				propiedades.cuenta_sucursal cs2  , propiedades.cuenta_usuario_sucursales cus 
				where cs.token = '$current_subsidiaria->token' and cs2.id_subsidiaria = cs.id 
				and cus.id_usuario  = $objUsuario->id and cus.id_sucursal  = cs2.id 
				and cs2.habilitada = true and cs2.activo = true 
                order by cs2.id asc";
		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		$jsonSuc = json_decode($resultado);

		//$resultadoSuc = $services->sendGet($url_services . '/rentdesk/cuentas/sucursales', null, [], $queryParamsSuc);
		//$jsonSuc = json_decode($resultadoSuc);

		//var_dump("duccc: ",$resultadoSuc );

		/*GUARDA DATOS DE SUCURSALES */
		$_SESSION["sesion_rd_sucursales"] = serialize($jsonSuc);
		if (isset($jsonSuc)) {
			$_SESSION["rd_current_sucursal"] = serialize($jsonSuc[0]);
		}

		/*--------------------------------------------*/
		$_SESSION["rd_usuario_valido_arpis"] = "true";
		$_SESSION["rd_usuario_id"]		= @$json->token;
		$_SESSION["rd_usuario_token"] 	= @$json->token;
		$_SESSION["rd_company_id"] 	= @$json->empresa->id;

		echo "|ok|1|";

		return;
		// $data_emp = array("idUsuario" => @$json->idUsuario);
		// //var_dump("idUsuario");

		// //var_dump($data_emp);
		// echo $data_emp;

		// $resultado_emp = $services->sendPostNoToken($url_services . '/empresa/countEmpresaByUser', $data_emp);
		// $json_emp = json_decode($resultado_emp);

		// //var_dump($json_emp);
		// echo $json_emp;

		// if ($json_emp) {
		// 	foreach ($json_emp as $resultado_emp_r) {
		// 		$result_emp = $resultado_emp_r;
		// 	}

		// 	$_SESSION["cantidad_empresas"] 	= $result_emp->cantidad;

		// 	if ($result_emp->status == "OK") {
		// 		if ($result_emp->cantidad == 0) {
		// 			echo ",xxx,3,xxx,0,xxx,";  //usuario sin empresas asignadas	
		// 		} else {
		// 			if ($result_emp->cantidad == 1) {
		// 				$_SESSION["rd_usuario_valido_arpis"] = "true";
		// 				$_SESSION["rd_usuario_token"] 	= @$json->token;
		// 				$_SESSION["usuario_nombre"]	= @$json->nombreUsuario;
		// 				$_SESSION["rd_usuario_id"]		= @$json->idUsuario;
		// 				$_SESSION["usuario_email"] 	= @$json->correo;
		// 				$_SESSION["usuario_rol"]   	= @$json->rol->idRol;
		// 				$_SESSION["company_token"] 	= @$json->empresa->token;
		// 				$_SESSION["rd_company_id"] 	= @$json->empresa->idEmpresa;
		// 				$_SESSION["company_nombre"] = @$json->empresa->nombreFantasia;
		// 				$_SESSION["company_email"]  = @$json->empresa->correo;
		// 				$_SESSION["company_zona"]   = "";
		// 				$_SESSION["company_logo"]   = @$json->empresa->logo;
		// 				$_SESSION["cant_decimales"] = @$json->empresa->cantDecimales;
		// 				$_SESSION["separador_mil"]  = @$json->empresa->separadorMil;
		// 				echo ",xxx,ok,xxx,1,xxx,";
		// 			} else {
		// 				$_SESSION["rd_usuario_id"]		= @$json->idUsuario;
		// 				$_SESSION["rd_company_id"] 	= -1;

		// 				$opcion_empresa = "<option value=''>Seleccione</option>";
		// 				$data_empresa = array("idUsuario" => @$json->idUsuario);
		// 				$resp_empresa = $services->sendPostNoToken($url_services . '/empresa/empresaByUser', $data_empresa);
		// 				$empresas = json_decode($resp_empresa);

		// 				foreach ($empresas as $empresa_r) {
		// 					$opcion_empresa = $opcion_empresa . "<option value='$empresa_r->token' >$empresa_r->nombre_fantasia</option>";
		// 				}
		// 				$opcion_empresa = "<select id='empresa' name='empresa' class='form-control'  >
		// 				$opcion_empresa
		// 				</select>";

		// 				$_SESSION["combo_empresas"]  = $opcion_empresa;

		// 				echo ",xxx,ok,xxx,$result_emp->cantidad,xxx,$opcion_empresa,xxx,";
		// 			}
		// 		}
		// 	} else {
		// 		echo "Error al obtener empresas";  //error al obtener empresas
		// 	}
		// } else {
		// 	echo "No se ha podido conectar con servicio"; //error al comunicarse con el servicio
		// }
	} else {
		echo "|ERROR|";
	}
} else {
	echo "|ERROR|";  //formulario invalido
}