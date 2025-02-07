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


$num_reg = 50;
$inicio = 0;

if(isset($_POST["idFicha"])) {
    $idFicha = $_POST["idFicha"];
    $queryCopropietarios = "SELECT pc.nivel_propietario, pc.token, pcb.id as id_cta_banc, vp.token_propietario, pc.id_propietario, vp.nombre_1 ||' ' || vp.nombre_2||' ' || vp.nombre_3 as nombre, vp.dni, pcb.nombre_titular, pcb.rut_titular, pcb.numero || '/' || tb.nombre as cuenta_banco , pc.porcentaje_participacion, pc.porcentaje_participacion_base, pc.id_beneficiario,ttp.nombre as tipo_persona 
		from propiedades.propiedad_copropietarios pc, 
		propiedades.vis_propietarios vp ,propiedades.propietario_ctas_bancarias pcb , propiedades.tp_banco tb , propiedades.tp_tipo_persona ttp 
			where pc.id_propietario = vp.id
			and pcb.id_propietario  = pc.id_propietario  
			and pc.id_propiedad = $idFicha
			and pcb.id = pc.id_cta_bancaria
			and tb.id = pcb.id_banco
			and vp.id_tipo_persona = ttp.id 
			and pc.habilitado  = true
		union
		SELECT pc.nivel_propietario, pc.token, pb.cta_id_banco as id_cta_banc, vp.token_propietario, pc.id_propietario, pb.nombre  as nombre, pb.RUT, pb.cta_nombre_titular , pb.cta_rut, pb.numero_cuenta|| '/' || tb.nombre as cuenta_banco , pc.porcentaje_participacion, pc.porcentaje_participacion_base, pc.id_beneficiario,  '-'
		from propiedades.propiedad_copropietarios pc, 
		propiedades.persona_beneficiario pb ,propiedades.vis_propietarios vp ,  propiedades.tp_banco tb
		where pc.id_propietario = vp.id
		and pc.id_propiedad = $idFicha
		and pc.id_beneficiario = pb.id 
		and tb.id = pb.cta_id_banco
		and pc.habilitado  = true
		order by id_propietario , nivel_propietario   asc ";

    // var_dump("QUERY COPROP: ", $queryCopropietarios);
} 


$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCopropietarios, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCopropietarios = json_decode($resultado);

echo json_encode($objCopropietarios);


?>