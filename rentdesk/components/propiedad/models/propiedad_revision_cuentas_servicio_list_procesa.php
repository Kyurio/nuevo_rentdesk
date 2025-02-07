<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
@$inicio        = $_POST["start"];
@$num_reg        = $_POST["length"];
@$num_reg_principal        = $_POST["length"];

$draw            = @$_POST["draw"];
$inicio            = @$_POST["start"];
@$fin            = @$_POST["length"];
$busqueda         = @$_POST["search"]["value"];
$primero = 0;

$cantidad_filtrados = 0;
$cantidad_registros = 0;

$Servicios = "";


$orden         = "";
if (!empty($_POST["order"][0]["column"]))
  $orden         = @$_POST["order"][0]["column"];

$direccion = "";
if (!empty($_POST["order"][0]["dir"]))
  $direccion = @$_POST["order"][0]["dir"];


$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$lista_servicios = "";


$coma = 0;
$signo_coma = "";
$datos        = "";

if ($inicio == "") {
  $inicio = 0;
}
if ($num_reg == "") {
  $num_reg = 99999;
}

$cant_rows = $num_reg;



// if (isset($_GET["idFicha"])) {
//     $idFicha = $_GET["idFicha"];
$queryCheques = "SELECT p.token as token_propiedad, pcs.id_ficha_propiedad as ficha_tecnica,  vp.direccion ||', '|| vp.numero||', '||vp.comuna ||', '||vp.region as propiedad, sum(pcs.monto_adeudado) as monto_adeudado from propiedades.propiedad_cta_servicios pcs, 
propiedades.propiedad p, propiedades.tp_tipo_servicio tts , propiedades.vis_propiedades vp 
where p.id = pcs.id_ficha_propiedad 
and vp.id_propiedad = p.id 
and tts.id = pcs.id_tipo_servicio 
and pcs.habilitado = true
group by pcs.id_ficha_propiedad ,p.token ,vp.direccion ,vp.numero,vp.comuna ,vp.region ";
// }

// var_dump("QUERY HISTORIAL: ", $queryCheques);



$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCheques, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCheques = json_decode($resultado);


// echo json_encode($objCheques);


$dataCount = array("consulta" => $queryCheques);
$resultadoCount = $services->sendPostNoToken($url_services . '/util/count', $dataCount);
$cantidad_registros = $resultadoCount;

if ($cantidad_registros  != 0) {

  foreach ($objCheques as $result) {
    if ($coma == 1)
      $signo_coma = ",";

    $coma = 1;


    $id_propiedad = $result->ficha_tecnica;
    $token_propiedad = $result->token_propiedad;
    $ficha_tecnica = $result->ficha_tecnica;
    $propiedad = $result->propiedad;
   // $cuenta = $result->cuenta;
    $monto_adeudado = $result->monto_adeudado;
	
	
	
	$queryServicio = "	SELECT p.token as token_propiedad, pcs.id_ficha_propiedad as ficha_tecnica,  vp.direccion ||', '|| vp.numero||', '||vp.comuna ||', '||vp.region as propiedad,tts.nombre as cuenta, pcs.monto_adeudado from propiedades.propiedad_cta_servicios pcs, 
						propiedades.propiedad p, propiedades.tp_tipo_servicio tts , propiedades.vis_propiedades vp 
						where p.id = pcs.id_ficha_propiedad 
						and vp.id_propiedad = p.id 
						and tts.id = pcs.id_tipo_servicio 
						and pcs.habilitado = true 
						and pcs.id_ficha_propiedad = $ficha_tecnica ";
// }

 //var_dump("queryServicio: ", $queryServicio);

	$num_pagina = round(0 / 999999) + 1;
	$data = array("consulta" => $queryServicio, "cantRegistros" => 999999, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$objServicios = json_decode($resultado);
	
	
	
	foreach ($objServicios as $result2) {
		
		if ($primero == 0){
		$Servicios .= $result2->cuenta;
		$primero = 1;
		$num_formateado = number_format($result2->monto_adeudado, 0, ',', '.');
		$lista_servicios = " <tr> <td>$result2->cuenta</td><td>$".$num_formateado."</td> </tr>   ";
		
		}else{
		$Servicios .=" , ".$result2->cuenta;
		$num_formateado = number_format($result2->monto_adeudado, 0, ',', '.');
		$lista_servicios = $lista_servicios." <tr> <td >$result2->cuenta</td><td>$".$num_formateado."</td> </tr>  ";
		}
	}
	
	//$lista_servicios = $lista_servicios." </tr> ";
	
	//var_dump($lista_servicios) ;
  //$lista_servicios = addslashes($lista_servicios);


    $ficha_tecnica = "<a href='index.php?component=propiedad&view=propiedad_ficha_tecnica&token=$token_propiedad' class='link-info' > #$ficha_tecnica</a>";
	$botones = "<div class='d-flex' style='gap: .5rem;'><a data-bs-toggle='modal' data-bs-target='#modalDetalleServicio' type='button' onclick='cargarDetalleServicio(\\\"$lista_servicios\\\")' class='btn btn-info m-0 d-flex' style='padding: .5rem;' aria-label='Detalle cuenta' title='Detalle cuenta'><i class='fa-regular fa-plus' style='font-size: .75rem;'></i>  </a> $Servicios";


    $datos = $datos . "
     $signo_coma
     [
      \"$id_propiedad\",
      \"$ficha_tecnica\",
      \"$propiedad\",
      \"$botones\",
      \"$monto_adeudado\"
    ]";
	
	$Servicios = "";
	$primero = 0;

	
	
  }

  echo "
{
  \"draw\": 1,
  \"recordsTotal\": $cantidad_registros,
  \"recordsFiltered\": $cantidad_registros,
  \"data\": [
    $datos
  ]
}";
} else {
  echo "
{
  \"draw\": 0,
  \"recordsTotal\": 0,
  \"recordsFiltered\": 0,
  \"data\": [
    $datos
  ]
}";
}
