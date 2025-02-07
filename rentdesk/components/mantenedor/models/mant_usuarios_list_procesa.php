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
$idNuevo = "";
$idGrupo = "";
$RolAccesos="";
$sucursales="";

$orden         = "";
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



$queryRoles = " SELECT cr.id, dni,nombres||' '||apellido_paterno||' '|| apellido_materno as nombre,correo,password ,cr.habilitado,cr.activo, nombres,apellido_paterno, apellido_materno, c.nombre as nombre_rol ,c.descripcion ,c.id as id_rol
from  propiedades.cuenta_usuario cr ,  propiedades.cuenta_subsidiarias_usuarios us , propiedades.cuenta_roles c
where cr.id = us.id_usuario and us.id_rol = c.id  and cr.habilitado = true and cr.id_empresa = 1  
order by cr.id  desc ";

 //var_dump("QUERY HISTORIAL: ", $queryRoles);



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
    $correo = $result->correo;
	$rut = $result->dni;
	$idRol = $result->id_rol;
	$password = $result->password;
	$nombre_rol = $result->nombre_rol;
	$descripcion_rol = $result->descripcion;
    $habilitado = $result->habilitado ?? false;
    $activo = $result->activo ?? false;
    // $botones = "";
    $botones = "<div class='d-flex' style='gap: .5rem;'><a data-bs-toggle='modal' data-bs-target='#modalMantenedorEditarUsuario' type='button' onclick='cargarUsuarioEditar($id,\\\"$result->nombres\\\",\\\"$result->apellido_paterno\\\",\\\"$result->apellido_materno\\\",\\\"$correo\\\",\\\"$password\\\",\\\"$rut\\\",$activo,$idRol)' class='btn btn-info m-0 d-flex' style='padding: .5rem;' aria-label='Editar' title='Editar'><i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i></a>";
    $botones = $botones . "<div class='d-flex' style='gap: .5rem;'><a data-bs-toggle='modal' data-bs-target='#modalMantenedorEditarPass' type='button' onclick='cargarUsuarioEditarPass($id)' class='btn btn-info m-0 d-flex' style='padding: .5rem;' aria-label='Editar Contraseña' title='Editar Contraseña'><i class='fa-solid fa-user-lock' style='font-size: .75rem;'></i></a>";
	$botones = $botones . "<button onclick='eliminarUsuario($id)' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i></button></div>";


$queryRolesDescripcion = "SELECT  crc.* , mc.label as nombre , mc.id_grupo  from propiedades.cuenta_roles cr , propiedades.cuenta_rol_componentes crc , propiedades.menu_componentes_items mc
where cr.habilitado = true and cr.id = $idRol  and cr.id_tipo_rol = 2  and crc.id_rol  = cr.id and mc.id = crc.id_componente_item and mc.id_grupo not in  (13,10)
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
		if($idNuevo != $idRol){
			$idGrupo = "";
			$idNuevo = $idRol;
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
			
		$RolAccesos.= " <br> <strong>$objModulo->label :</strong></br> ";
			$RolAccesos.= "$result2->nombre";
			//$RolAccesos = $RolAccesos." ".$objModulo->label." : ".$result2->nombre;
			
		}else{
			$RolAccesos = $RolAccesos." | $result2->nombre";
			
			
			$contador++;
		//$RolAccesos = $RolAccesos." - ".$result2->nombre;
		}
		
		//var_dump($RolAccesos);
	}
}

$rol = "<div class='d-flex align-items-center'><button onclick='descripcionRol(\\\"$nombre_rol\\\",\\\"$RolAccesos\\\")' type='button'  style='border: none; background-color: transparent; padding: 0; margin-right: .5rem' title='informacion'><i class='fas fa-info-circle icono-info' ></i></button> $result->nombre_rol </div>";




$queryRolesDescripcion = "select cs.nombre  from propiedades.cuenta_usuario_sucursales cus , propiedades.cuenta_sucursal cs 
where id_usuario = $id and cus.id_sucursal = cs.id ";
//var_dump($queryRolesDescripcion);
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryRolesDescripcion, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objSucursal = json_decode($resultado);

if($objSucursal != null && $objSucursal != ""){
	$sucursales = "";
	foreach ($objSucursal as $result2) {
		
		
		$sucursales = $sucursales."<div>".$result2->nombre."</div>";
	}
}


    $datos = $datos . "
     $signo_coma
     [
     
      \"$id\",
      \"$nombre\",
	  \"$rut\",
      \"$correo\",
	  \"$sucursales\",
	  \"$rol\",
      \"$activo\",
      \"$botones\"

    ]";
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
