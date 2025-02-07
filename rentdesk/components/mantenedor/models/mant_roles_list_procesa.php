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

$cantidad_filtrados = 0;
$cantidad_registros = 0;

$RolAccesos="";
$idGrupo="";
$orden         = "";

$idNuevo = "";
if (!empty($_POST["order"][0]["column"]))
  $orden         = @$_POST["order"][0]["column"];

$direccion = "";
if (!empty($_POST["order"][0]["dir"]))
  $direccion = @$_POST["order"][0]["dir"];


$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];


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
$contador = 0;


$queryRoles = "SELECT  * from propiedades.cuenta_roles cr 
where habilitado = true and cr.id_tipo_rol = 2  
order by id  desc ";


// var_dump("QUERY HISTORIAL: ", $queryRoles);



$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryRoles, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objRol = json_decode($resultado);


// echo json_encode($objRol);


$dataCount = array("consulta" => $queryRoles);
$resultadoCount = $services->sendPostNoToken($url_services . '/util/count', $dataCount);
$cantidad_registros = $resultadoCount;

if ($cantidad_registros  != 0) {

  foreach ($objRol as $result) {
    if ($coma == 1)
      $signo_coma = ",";

    $coma = 1;


    $id = $result->id;
    $nombre = $result->nombre;
    $descripcion = $result->descripcion;
    $habilitado = $result->habilitado ?? false;
    $activo = $result->activo ?? false;
    // $botones = "";
    $botones = "<div class='d-flex' style='gap: .5rem;'><a data-bs-toggle='modal' data-bs-target='#modalMantenedorEditarRol' type='button' onclick='cargarRolEditar($id,\\\"$nombre\\\",\\\"$descripcion\\\",$activo)' class='btn btn-info m-0 d-flex' style='padding: .5rem;' aria-label='Editar' title='Editar'><i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i></a>";
    $botones = $botones . "<button onclick='eliminarRol($id)' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i></button></div>";



$queryRolesDescripcion = "SELECT  crc.* , mc.label as nombre , mc.id_grupo  from propiedades.cuenta_roles cr , propiedades.cuenta_rol_componentes crc , propiedades.menu_componentes_items mc
where cr.habilitado = true and cr.id = $id  and cr.id_tipo_rol = 2  and crc.id_rol  = cr.id and mc.id = crc.id_componente_item and mc.id_grupo not in  (13,10)
order by mc.id_grupo desc";
//var_dump($queryRolesDescripcion);
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryRolesDescripcion, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objRolDescripcion = json_decode($resultado);
//var_dump("ID",$id);
if($objRolDescripcion != null && $objRolDescripcion != ""){
	
	foreach ($objRolDescripcion as $result2) {
		//var_dump("ID",$id);
		if($idNuevo != $id){
			$idGrupo = "";
			$idNuevo = $id;
			$contador = 0;
		}
		
		if($idGrupo != $result2->id_grupo){
			$idGrupo = $result2->id_grupo;
			//var_dump($idGrupo);
			$queryModulo= "SELECT label FROM propiedades.menu_componentes_grupos where id = $idGrupo ";
			//var_dump($queryModulo);
			$num_pagina = round($inicio / $cant_rows) + 1;
			$data = array("consulta" => $queryModulo, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
			$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
			$objModulo = json_decode($resultado)[0];
			
		$RolAccesos.= "<br> <strong>$objModulo->label :</strong></br>";
			$RolAccesos.= " &nbsp;&nbsp;&nbsp;&nbsp; - $result2->nombre";
			//$RolAccesos = $RolAccesos." ".$objModulo->label." : ".$result2->nombre;
			
		}else{
			$RolAccesos = $RolAccesos." &nbsp;&nbsp;&nbsp;&nbsp; - $result2->nombre";
			
			
			$contador++;
		//$RolAccesos = $RolAccesos." - ".$result2->nombre;
		}
		
		//var_dump($RolAccesos);
	}
}

//$RolAccesos.= "</ul>

    $datos = $datos . "
     $signo_coma
     [
     
      \"$id\",
      \"$nombre\",
      \"$descripcion\",
	   \"$RolAccesos\",
      \"$habilitado\",
      \"$activo\",
      \"$botones\"

    ]";
	$RolAccesos = "";
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
