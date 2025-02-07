<?php

session_start();
@include("../../../includes/sql_inyection.php");
@include("../../../configuration.php");
@include("../../../includes/funciones.php");
@include("../../../includes/services_util.php");
echo "";
//************************************************************************************************************

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
@$inicio		= $_POST["start"];
@$num_reg		= $_POST["length"];
@$num_reg_principal		= $_POST["length"];



$draw			= @$_POST["draw"];
$inicio			= @$_POST["start"];
$fin			= @$_POST["length"];
$busqueda 		= @$_POST["search"]["value"];

$cantidad_filtrados = 0;
$cantidad_registros = 0;


$orden 		= "";
if (!empty($_POST["order"][0]["column"]))
$orden 		= @$_POST["order"][0]["column"];

$direccion = "";
if (!empty($_POST["order"][0]["dir"]))
$direccion = @$_POST["order"][0]["dir"];
 


$current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$current_sucursales = unserialize($_SESSION['sesion_rd_sucursales']);
$current_sucursal = unserialize($_SESSION["rd_current_sucursal"]);

//var_dump("SUCURSAL ACTUAL: ", $current_sucursal);
$_SESSION["sesion_rd_current_propiedad_token"] = null;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$token	= @$_GET["token"];
$fecha_desde	= @$_GET["fecha_desde"];
$fecha_hasta	= @$_GET["fecha_hasta"];
$propietarios = "";
$coma = 0;
$datos		= "";
$signo_coma = "";
//$ficha_propiedad = ""




if ($inicio == ""){
	$inicio = 0;
}
if ($num_reg == ""){
	$num_reg = 99999;
}
//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=propiedad&view=propiedad&token=$token&nav=$nav");

if (isset($nav)) {
	$nav = "index.php?" . decodifica_navegacion($nav);
} else {
	$nav = "index.php?component=propiedad&view=propiedad_list";
}


//************************************************************************************************************


/*para mejorar el rendimiento el servicio hace una ejecucion directa a la BBDD
es por esto que este objeto en particular debe utilizar los atributos tal como
estan creados en la base de datos y no como objetos
*/
// $result = null;
// $data = array("token" => $token, "idEmpresa" => $id_company);
// $resultado = $services->sendPostNoToken($url_services . '/propiedad/token', $data);
// if ($resultado) {
// 	$result_json = json_decode($resultado);
// 	foreach ($result_json as $result_r) {
// 		$result = $result_r;
// 	} //foreach($result_json as $result)
// }
//************************************************************************************************************

/*PRUEBA OBTENCIÓN LISTADO PROPIEDADES */
/*Consulta Cantidad de registros*/
// $query_count = "SELECT * FROM propiedades.propiedad where id_subsidiaria = $current_subsidiaria->id";

// $data = array("consulta" => $query_count);
// $resultado = $services->sendPostNoToken($url_services . '/util/count', $data);
// $cantidad_registros = $resultado;

// //var_dump("CANTIDAD PROPIEDADES: ", json_decode($cantidad_registros));


/*BUSQUEDA CON FILTRO LISTA PROPIEDADES */

/* SE comenta por que no se tiene relacion aun con las propiedades
$query = " SELECT a.* , p.* from propiedades.accion_varios_acreedores a ,  propiedades.propiedad p  where a.habilitado = true and p.id  = a.id_propiedad 
 order by a.id desc ";

*/
$query = " SELECT * from propiedades.accion_varios_acreedores a  where id_propiedad is null and 
to_char(fecha_cierre,'yyyy-mm-dd') <= '$fecha_hasta' and to_char(fecha_cierre,'yyyy-mm-dd') >= '$fecha_desde' 
 order by a.id_vou_cierre desc ";
 
//var_dump($query);
$data = array("consulta" => $query);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $num_reg, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);



$json = json_decode($resultado);



if ($json !== null) {
	// Iterate over each object in the array
	foreach ($json as $obj) {
		
		if($coma==1)
$signo_coma = ",";

$cantidad_filtrados++;

$coma = 1;
		// Transform each field of the object
		$propietarios_con_saltos = "";
		$propietarios = "";
		if ($obj->id_propiedad != null && $obj->id_propiedad != "" ){
		    $query = "   SELECT vp.nombre_1 ||' '|| vp.nombre_2 ||' | ' || vp.dni as info_propietario ,pc.nivel_propietario,  pc.id_propietario
							from propiedades.propiedad_copropietarios pc, 
							propiedades.vis_propietarios vp ,propiedades.propietario_ctas_bancarias pcb , propiedades.tp_banco tb , propiedades.tp_tipo_persona ttp 
								where pc.id_propietario = vp.id
								and pcb.id_propietario  = pc.id_propietario  
								and pc.id_propiedad = $obj->id_propiedad 
								and pcb.id = pc.id_cta_bancaria
								and tb.id = pcb.id_banco
								and vp.id_tipo_persona = ttp.id 
								and pc.habilitado  = true
							union
							select pb.nombre ||' | ' || pb.rut as info_propietario ,pc.nivel_propietario,  pc.id_propietario from propiedades.propiedad_copropietarios pc, 
							propiedades.persona_beneficiario pb ,propiedades.vis_propietarios vp ,  propiedades.tp_banco tb
							where pc.id_propietario = vp.id
							and pc.id_propiedad = $obj->id_propiedad 
							and pc.id_beneficiario = pb.id 
							and tb.id = pb.cta_id_banco
							and pc.habilitado  = true 
							order by id_propietario , nivel_propietario   asc ";
            $cant_rows = $num_reg;
            $num_pagina = round($inicio / $cant_rows) + 1;
            $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
            $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	        //var_dump($query);
            @$json_propietarios = json_decode($resultado);
			//var_dump($json_propietarios);
			foreach ($json_propietarios as $obj_propietarios) {
				if ($obj_propietarios->nivel_propietario == 2 ){
					$propietarios = $propietarios."zzz".$obj_propietarios->info_propietario;
				}else{
					$propietarios = $propietarios."xxx".$obj_propietarios->info_propietario;
				}
				
			}
			
			$propietarios_con_saltos = str_replace("xxx", "<br><i class='fa-solid fa-house-user' style='color:#515151;font-size:12px;' title='Propietario' ></i> ", $propietarios);	
			$propietarios_con_saltos = str_replace("zzz", "<br>&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa-solid fa-user-large' style='font-size:10px; color:#707070;' title='Beneficiario'></i> ", $propietarios_con_saltos);	
		}else{
			$propietarios_con_saltos = "";
		}

		//var_dump("token",$obj->token);
		//$botones =  "<a href='index.php?component=propiedad&view=propiedad&token=$obj->token_propiedad' type='button' class='btn btn-info m-0' style='padding: .5rem;' aria-label='Editar' title='Editar'><i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i> </a>";
		
		///$botones = "<button type='button' onclick='eliminarVariosAcreedores($obj->id)' class='btn btn-danger m-0' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i> </button>";
		
		// Descomentar cuando se tenga realcion con propiedad $ficha_propiedad = "<a href='index.php?component=propiedad&view=propiedad_ficha_tecnica&token=$obj->token' class='link-info' > $obj->direccion, $obj->comuna , $obj->region  </a>";
		$ficha_propiedad = "<a href='index.php?component=propiedad&view=propiedad_ficha_tecnica&token=' class='link-info' > $obj->direccion, $obj->comuna , $obj->region  </a>";

$fecha_objeto = new DateTime($obj->fecha_cierre);
$fecha_formateada = $fecha_objeto->format('d-m-Y');

if ($obj->tipo_voucher == "ANT_RENTA_PROPIETARIO"){
	$tipo_voucher = "Abono VA";
}else{
	$tipo_voucher = "Descuento VA";
}
	 

		$datos = $datos ."
     $signo_coma
	 [
	   \"$obj->id_vou_cierre\",
	   \"$fecha_formateada\",
	  \"$ficha_propiedad\",
	  \"$propietarios_con_saltos\",
	  \"$tipo_voucher\",
	  \"$obj->razon\",
	  \"$obj->valor\"
    ]";
	
	
}
}

function validateNull($item)
{
	return is_null($item) || $item === "" ? "-" : $item;
}

function redirectToPropiedadUrl($token)
{

	$addTokenQuery = "&token=" . urlencode($token);



	$redirectURL = "index.php?component=propiedad&view=propiedad" . $addTokenQuery;


	return $redirectURL;
}

function redirectToCreatePropiedad()
{

	$redirectURL = "index.php?component=propiedad&view=propiedad";

	return $redirectURL;
}
//************************************************************************************************************


echo "
{
  \"draw\": $draw,
  \"recordsTotal\": $cantidad_registros,
  \"recordsFiltered\": $cantidad_registros,
  \"data\": [
    $datos
  ]
}


";


