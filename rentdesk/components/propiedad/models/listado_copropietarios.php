<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);


// var_dump($current_subsidiaria->token);
$num_reg = 1000;
$inicio = 0;

if (isset($_POST["idFicha"])) {
	$idFicha = $_POST["idFicha"];
	// $queryCopropietarios = "SELECT pc.id_propiedad, pc.id,pc.id_relacion, pc.nivel_propietario, pc.token, pcb.id as id_cta_banc, vp.token_propietario, pc.id_propietario, vp.nombre_1 ||' ' || vp.nombre_2||' ' || vp.nombre_3 as nombre, vp.dni, pcb.nombre_titular, pcb.rut_titular, pcb.numero || '/' || tb.nombre as cuenta_banco , pc.porcentaje_participacion, pc.porcentaje_participacion_base, pc.id_beneficiario,ttp.nombre as tipo_persona 
	// 	from propiedades.propiedad_copropietarios pc, 
	// 	propiedades.vis_propietarios vp ,propiedades.propietario_ctas_bancarias pcb , propiedades.tp_banco tb , propiedades.tp_tipo_persona ttp 
	// 		where pc.id_propietario = vp.id
	// 		and pcb.id_propietario  = pc.id_propietario  
	// 		and pc.id_propiedad = $idFicha
	// 		and pcb.id = pc.id_cta_bancaria
	// 		and tb.id = pcb.id_banco
	// 		and vp.id_tipo_persona = ttp.id 
	// 		and pc.habilitado  = true
	// 	union ALL 
	// 	SELECT pc.id_propiedad, pc.id,pc.id_relacion, pc.nivel_propietario, pc.token, pb.cta_id_banco as id_cta_banc, vp.token_propietario, pc.id_propietario, pb.nombre as nombre, pb.rut, pb.cta_nombre_titular , pb.cta_rut, pb.numero_cuenta|| '/' || tb.nombre as cuenta_banco , pc.porcentaje_participacion, pc.porcentaje_participacion_base, pc.id_beneficiario,  '-'
	// 	from propiedades.propiedad_copropietarios pc, 
	// 	propiedades.persona_beneficiario pb ,propiedades.vis_propietarios vp ,  propiedades.tp_banco tb
	// 	where pc.id_propietario = vp.id
	// 	and pc.id_propiedad =  $idFicha
	// 	--and pc.id_beneficiario = pb.id 
	// 	and tb.id = pb.cta_id_banco
	//     and pc.habilitado  = true
	//     order by id_propietario , nivel_propietario asc
	// 	";

	$queryCopropietarios = "SELECT 
		pc.id_propiedad, 
		pc.id,
		pc.id_relacion, 
		pc.nivel_propietario, 
		pc.token, 
		pcb.id AS id_cta_banc, 
		vp.token_propietario, 
		pc.id_propietario, 
		vp.nombre_1 || ' ' || vp.nombre_2 || ' ' || vp.nombre_3 AS nombre, 
		vp.dni, 
		pcb.nombre_titular, 
		pcb.rut_titular, 
		pcb.numero || '/' || tb.nombre AS cuenta_banco, 
		pc.porcentaje_participacion, 
		pc.porcentaje_participacion_base, 
		pc.id_beneficiario, 
		ttp.nombre AS tipo_persona
	FROM 
		propiedades.propiedad_copropietarios pc
	JOIN propiedades.vis_propietarios vp ON pc.id_propietario = vp.id
	JOIN propiedades.propietario_ctas_bancarias pcb ON pcb.id_propietario = pc.id_propietario
	JOIN propiedades.tp_banco tb ON tb.id = pcb.id_banco
	JOIN propiedades.tp_tipo_persona ttp ON vp.id_tipo_persona = ttp.id
	WHERE 
		pc.id_propiedad = $idFicha
		--AND pcb.id = pc.id_cta_bancaria 
		AND pc.habilitado = true 

	UNION ALL 

	SELECT 
		pc.id_propiedad, 
		pc.id,
		pc.id_relacion, 
		pc.nivel_propietario, 
		pc.token, 
		pb.cta_id_banco AS id_cta_banc, 
		vp.token_propietario, 
		pc.id_propietario, 
		pb.nombre AS nombre, 
		pb.rut, 
		pb.cta_nombre_titular, 
		pb.cta_rut, 
		pb.numero_cuenta || '/' || tb.nombre AS cuenta_banco, 
		pc.porcentaje_participacion, 
		pc.porcentaje_participacion_base, 
		pc.id_beneficiario, 
		'-' AS tipo_persona
	FROM 
		propiedades.propiedad_copropietarios pc
	JOIN propiedades.vis_propietarios vp ON pc.id_propietario = vp.id
	JOIN propiedades.persona_beneficiario pb ON pc.id_beneficiario = pb.id
	JOIN propiedades.tp_banco tb ON tb.id = pb.cta_id_banco
	WHERE 
		pc.id_propiedad = $idFicha
		AND pc.habilitado = true 
	ORDER BY 
		nombre DESC";

// 	$queryCopropietarios = "SELECT 
//     a.id_propiedad, 
//     a.id,
//     a.id_relacion, 
//     a.nivel_propietario, 
//     a.token, 
//     b.id AS id_cta_banc, 
//     vp.token_propietario, 
//     a.id_propietario, 
//     vp.nombre_1 || ' ' || vp.nombre_2 || ' ' || vp.nombre_3 AS nombre, 
//     vp.dni, 
//     --b.nombre_titular, 
//     --b.rut_titular, 
//     --b.numero || '/' || tb.nombre AS cuenta_banco, 
//     a.porcentaje_participacion, 
//     a.porcentaje_participacion_base, 
//     a.id_beneficiario, 
//     ttp.nombre AS tipo_persona
// FROM 
//     propiedades.propiedad_copropietarios a
// LEFT JOIN propiedades.persona_beneficiario b 
//     ON a.id_propietario = b.id_propietario --AND b.id_tipo_cuenta = 2
// LEFT JOIN propiedades.vis_propietarios vp 
//     ON a.id_propietario = vp.id
// LEFT JOIN propiedades.tp_tipo_persona ttp 
//     ON vp.id_tipo_persona = ttp.id
// LEFT JOIN propiedades.tp_banco tb 
//     ON tb.id = b.cta_id_banco
// WHERE 
//     a.id_propiedad = $idFicha";

}

$data = array("consulta" => $queryCopropietarios);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);


//echo $resultado;

//echo '[{"id_propiedad":507663,"id":300267,"id_relacion":null,"nivel_propietario":1,"token":"fbff511b913055732c0340f8444c9039","id_cta_banc":22,"token_propietario":"ffea888c767de1d17641c30056b02318","id_propietario":948863,"nombre":"CLAUDIA PODESTA LOPEZ","dni":"7818679-8","nombre_titular":"CLAUDIA PODESTA LOPEZ","rut_titular":"7818679-8","cuenta_banco":"125773001\/Banco Security","porcentaje_participacion":100,"porcentaje_participacion_base":100,"id_beneficiario":null,"tipo_persona":"-"},{"id_propiedad":507663,"id":300267,"id_relacion":null,"nivel_propietario":2,"token":"fbff511b913055732c0340f8444c9039","id_cta_banc":44726,"token_propietario":"ffea888c767de1d17641c30056b02318","id_propietario":948863,"nombre":"ANA MESSENGER MU\u00d1OZ","dni":"6958613-9","nombre_titular":"ANA MESSENGER MU\u00d1OZ","rut_titular":"6958613-9","cuenta_banco":"6700624\/Banco Bice","porcentaje_participacion":100,"porcentaje_participacion_base":100,"id_beneficiario":null,"tipo_persona":"NATURAL"},{"id_propiedad":507663,"id":300267,"id_relacion":null,"nivel_propietario":2,"token":"fbff511b913055732c0340f8444c9039","id_cta_banc":4,"token_propietario":"ffea888c767de1d17641c30056b02318","id_propietario":948863,"nombre":"francisco javier fuenzalida baldeig","dni":"10602253-4","nombre_titular":"francisco javier fuenzalida baldeig","rut_titular":"10602253-4","cuenta_banco":"45895295\/Banco BCI","porcentaje_participacion":100,"porcentaje_participacion_base":100,"id_beneficiario":null,"tipo_persona":"-"},{"id_propiedad":507663,"id":300267,"id_relacion":null,"nivel_propietario":2,"token":"fbff511b913055732c0340f8444c9039","id_cta_banc":4,"token_propietario":"ffea888c767de1d17641c30056b02318","id_propietario":948863,"nombre":"FRANCISCO FUENZALIDA BALDEIG","dni":"10602253-4","nombre_titular":"FRANCISCO FUENZALIDA BALDEIG","rut_titular":"10602253-4","cuenta_banco":"45895295\/Banco BCI","porcentaje_participacion":100,"porcentaje_participacion_base":100,"id_beneficiario":null,"tipo_persona":"-"},{"id_propiedad":507663,"id":300267,"id_relacion":null,"nivel_propietario":1,"token":"fbff511b913055732c0340f8444c9039","id_cta_banc":7,"token_propietario":"ffea888c767de1d17641c30056b02318","id_propietario":948863,"nombre":"juan alberto","dni":"19271262-9","nombre_titular":"juan alberto","rut_titular":"19271262-9","cuenta_banco":"3900\/Banco de China Construction","porcentaje_participacion":100,"porcentaje_participacion_base":100,"id_beneficiario":null,"tipo_persona":"-"}]';





