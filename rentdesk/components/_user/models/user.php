<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token	= @$_GET["token"];
$id_company 	= $_SESSION["rd_company_id"];
$usuario_rol    = $_SESSION["usuario_rol"];
$id_usuario    = $_SESSION["rd_usuario_id"];


$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/usuario/token',$data);		
$result = json_decode($resultado); 

//************************************************************************************************************

$rol = "";
$data_rol = array("idRol" => @$result->idRol,
				  "idEmpresa" => @$id_company );							
$resp_rol = $services->sendPostNoToken($url_services.'/rol/rolForUser',$data_rol);	
$roles = json_decode($resp_rol);

foreach($roles as $rol_r) {

$select_rol = "";
if(@$result->idRol == @$rol_r->id_rol )
$select_rol = " selected ";


$rol = $rol . "<option value='$rol_r->token' $select_rol >$rol_r->nombre</option>";
}//foreach($roles as $rol)

$rol = "<select id='rol' name='rol' class='form-control' required >
$rol
</select>";

//***************************************************************************************************************

$pass_required = " required ";
$pass_placeholder = "Ingrese la contraseña para acceso al sistema (obligatorio)";
$ingrese_nuevo = " (debe tener al menos 6 caracteres)";

if($token!=""){
	$pass_required = "";
	$pass_placeholder = "*************";
	$ingrese_nuevo = " (ingréselo solo si quiere cambiar la contraseña actual)";
}//if($token!="")
	

//*****************************************************************************************************************
$lista_accesos_perfil = "";
if(@$result->nombreUsuario!=""){
	
//*********************************************************************************************************************************	
$opcion_empresa = "<option value=''>Seleccione</option>";
$data_empresa = array("idUsuario" => $id_usuario);							
$resp_empresa = $services->sendPostNoToken($url_services.'/empresa/empresaByUser',$data_empresa);	
$empresas = json_decode($resp_empresa);

$script_empresa = "";

foreach($empresas as $empresa_r) {

$select_empresa = "";
if(@$result->empresa->idEmpresa == @$empresa_r->id_empresa){
$select_empresa = " selected ";
$opcion_seleccionar = "";
}

$opcion_empresa = $opcion_empresa . "<option value='$empresa_r->id_empresa' $select_empresa >$empresa_r->nombre_fantasia</option>";

/**  Codigo para traerse las sucursales a las que accede el usuario  **/
$data_sucursal_perf = array("idUsuario" => $result->idUsuario,
							"idEmpresa" => $empresa_r->id_empresa);							
$resp_empresa_suc = $services->sendPostNoToken($url_services.'/sucursal/sucursalByEmpUser',$data_sucursal_perf);	
$empresa_suc = json_decode($resp_empresa_suc);
if($empresa_suc){
	foreach($empresa_suc as $sucursal_r) {
		if($sucursal_r->status == "OK"){
			$lista_accesos_perfil = $lista_accesos_perfil."    <tr>
			  <td height='28'>$empresa_r->nombre_fantasia</td>
			  <td height='28'>$sucursal_r->nombre_fantasia</td>
			  <td height='28'><a href='javascript:deletePermisosUser(\"$empresa_r->token\",\"$sucursal_r->token\",\"$token\");'><i class='far fa-trash-alt'></i></a></td>
			</tr>";
		}else{
			$lista_accesos_perfil = $lista_accesos_perfil."    <tr>
			  <td height='28'>$empresa_r->nombre_fantasia</td>
			  <td height='28'>&nbsp;</td>
			  <td height='28'><a href='javascript:deletePermisosUser(\"$empresa_r->token\",\"-\",\"$token\");'><i class='far fa-trash-alt'></i></a></td>
			</tr>";
		}
	}	
}else{
	/**  Codigo para traerse las empresas a las que accede el usuario  **/
	$data_empresa_perf = array("idUsuario" => $result->idUsuario);								
	$resp_empresa_perf = $services->sendPostNoToken($url_services.'/empresa/empresaByUser',$data_empresa_perf);	
	$empresa_perf = json_decode($resp_empresa_perf);
	if($empresa_perf){
		foreach($empresa_perf as $empresa_perf_r) {
			if($empresa_perf_r->status == "OK" && $empresa_perf_r->id_empresa == $empresa_r->id_empresa ){
				$lista_accesos_perfil = $lista_accesos_perfil."    <tr>
				  <td height='28'>$empresa_perf_r->nombre_fantasia</td>
				  <td height='28'>&nbsp;</td>
				  <td height='28'><a href='javascript:deletePermisosUser(\"$empresa_perf_r->token\",\"-\",\"$token\");'><i class='far fa-trash-alt'></i></a></td>
				</tr>";
			}
		}	
	}	
}	


}//foreach($roles as $rol)

$opcion_empresa = "<select id='empresa' name='empresa' class='form-control' onchange='seteaSucursal(this.value);' >
$opcion_empresa
</select>";


$lista_accesos_perfil = "
 <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" class='tabla-propiedad'>
  <tbody>
	<tr >
	  <td height='28'><strong>Empresa</strong></td>	
	  <td height='28'><strong>Sucursal</strong></td>	
	  <td height='28'><strong>Eliminar</strong></td>
	</tr>
	$lista_accesos_perfil
  </tbody>
</table>
<br>
";

//************************************************************************************************************************************

$opcion_sucursal = "<option value=''>Seleccione</option>";
$data_sucursal = array("idSucursal" => $id_company);							
$resp_sucursal = $services->sendGetNoToken($url_services.'/sucursal/lista',$data_sucursal);	
$sucursales = json_decode($resp_sucursal);


foreach($sucursales as $sucursal_r) {

$select_sucursal = "";
if(@$result->sucursal->idSucursal == @$sucursal_r->idSucursal){
$select_sucursal = " selected ";
}

$opcion_sucursal = $opcion_sucursal . "<option value='$sucursal_r->idSucursal' $select_sucursal >$sucursal_r->nombreFantasia</option>";
}//foreach($roles as $rol)

$opcion_sucursal = "<select id='sucursal' name='sucursal' class='form-control' >
$opcion_sucursal
</select>";
//*************************************************************************************************************************************


}else{
	$script_empresa = "<script>
	$( document ).ready(function() {
	$('.campo-empresa').html('');
	$('.boton-empresa').html('');
	});
	</script>";
}//if(@$result->nombreUsuario!="")



?>