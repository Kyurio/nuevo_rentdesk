<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$url_reportes_eje = $config->url_reportes_eje;

$token			= @$_GET["t"];
$id_company 	= $_SESSION["rd_company_id"]; 
$rol_usuario 	= $_SESSION["usuario_rol"];
$id_usuario    	= $_SESSION["rd_usuario_id"];


//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=reporte&view=reporte&token=$token&nav=$nav");

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=reporte&view=reporte_list";
}	


$filtros_reporte= "";
$nombres_campos	= "";

$mostrar_empresa 	= 0;
$mostrar_sucursal 	= 0;

//BASE PARA FILTROS HTML*************************************************************************************
$filtro_input 	= "<input type=\"text\" class=\"form-control xxxclassxxx \"  xxxpropiedadxxx maxlength=\"500\" name=\"xxxnamexxx\" id=\"xxxidxxx\" placeholder=\"xxxplaceholderxxx\" value=\"\" onblur=\"elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);\">";
$filtro_select	= "<select id=\"xxxidxxx\" name=\"xxxnamexxx\" class=\"form-control xxxclassxxx \" xxxpropiedadxxx >
					xxxoptionxxx
					</select>";
$filtro_date	= "<input type=\"text\" class=\"form-control xxxclassxxx \" xxxpropiedadxxx maxlength=\"500\" name=\"xxxnamexxx\" id=\"xxxidxxx\" placeholder=\"xxxplaceholderxxx\" value=\"\" onblur=\"elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);\">";
$filtro_number	= "<input type=\"number\" class=\"form-control xxxclassxxx \" xxxpropiedadxxx maxlength=\"2\" name=\"xxxnamexxx\" id=\"xxxidxxx\" placeholder=\"xxxplaceholderxxx\"  value=\"\" onblur=\"elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);\">";

// ID Y NOMBRE DEL REPORTE ***********************************************************************************
$id_reporte = "";
$nombre_repporte = "";
 
 
$query = "	SELECT r.*
			FROM arpis.rep_reporte r
			WHERE r.token='$token'
			";			
$data = array("consulta" => $query);	
$resultado = $services->sendPostNoToken($url_services.'/util/objeto',$data);
//echo   $resultado;

if($resultado){
	$result_json3 = json_decode($resultado); 
	foreach($result_json3 as $result_r3) {
		$result3			= $result_r3;
		$id_reporte			= $result_r3->id_reporte;
		$nombre_repporte 	= $result_r3->descripcion;
	}	
}

 
//TIPO DE EXPORTACION***********************************************************************************************************
$select_tipo_reporte = "";

	$query = "	select rte.*
				from arpis.rep_reporte_export rre,
				arpis.rep_tipo_export rte
				where rre.id_reporte = $id_reporte
				and rte.id_tipo_export = rre.id_tipo_export
				and rre.activo = 'S'
				";			
	$data = array("consulta" => $query);	
	$resultado = $services->sendPostNoToken($url_services.'/util/objeto',$data);
	//echo   $resultado;
	
	if($resultado){
		$result_json3 = json_decode($resultado); 
		foreach($result_json3 as $result_r3) {
			$result3			= $result_r3;
		//	$id_reporte			= $result_r3->id_reporte;
		$select_tipo_reporte = $select_tipo_reporte . "<option value='$result_r3->id_tipo_export|$result_r3->cod_externo' >$result_r3->descripcion</option>";
		 
		}	
	}



$select_tipo_reporte = "
						<div class=\"col-md-4\">
								<div class=\"form-group campo-empresa\">
									<label ><span class=\"obligatorio\">*</span>Tipo de Exportación:</label>
									<select id='tiporeporte' name='tiporeporte' class='form-control'  required  data-validation-required=\"\"  >
									<option value='' >Seleccionar</option>
									$select_tipo_reporte
									</select>
								</div>
						</div>
";



//SELECT EMPRESA****************************************************************************************************************************	
$opcion_empresa = "<option value='0|Todas'>Todas</option>";
$data_empresa = array("idUsuario" => $id_usuario);							
$resp_empresa = $services->sendPostNoToken($url_services.'/empresa/empresaByUser',$data_empresa);	
$empresas = json_decode($resp_empresa);

$script_empresa = "";

foreach($empresas as $empresa_r) {

$select_empresa = "";
$opcion_empresa = $opcion_empresa . "<option value='$empresa_r->id_empresa|$empresa_r->nombre_fantasia' $select_empresa >$empresa_r->nombre_fantasia</option>";

}
$opcion_empresa = "<select id='empresa' name='empresa' class='form-control' onchange='seteaSucursal(this.value);' >
$opcion_empresa
</select>";

//SELECT SUCURSALES***************************************************************************************************************

$opcion_sucursal = "<option value='0|Todas'>Todas</option>";

$query = "	select s.*
			from arpis.usuario_empresa ue,
				 arpis.sucursal	s	
			where ue.id_usuario = $id_usuario
			and s.id_empresa = ue.id_empresa
			";			
$data_sucursal = array("consulta" => $query);	
$resp_sucursal = $services->sendPostNoToken($url_services.'/util/objeto',$data_sucursal);
$sucursales = json_decode($resp_sucursal);


foreach($sucursales as $sucursal_r) {

$select_sucursal = "";
$opcion_sucursal = $opcion_sucursal . "<option value='$sucursal_r->id_sucursal|$sucursal_r->nombre_fantasia' $select_sucursal >$sucursal_r->nombre_fantasia</option>";
}//foreach($roles as $rol)

$opcion_sucursal = "<select id='sucursal' name='sucursal' class='form-control' >
$opcion_sucursal
</select>";

//FILTROS PARA EL REPORTE****************************************************************************************************************
//ID DE LOS FILTROS PARA ESE REPORTE********************

	$query = "	SELECT rrf.id_filtro,
					   rf.descripcion, rf.id_tipo_filtro, rf.id_filtro_padre, rf.placeholder, rf.mascara,rf.query, rf.name_html,
					   rtf.tipo_html
				FROM arpis.rep_reporte_filtro rrf,
					 arpis.rep_filtro rf,
					arpis.rep_tipo_filtro rtf				 
				WHERE rrf.id_reporte = $id_reporte 
				AND rrf.activo='S' 
				AND rf.id_filtro = rrf.id_filtro
				AND rtf.id_tipo_filtro = rf.id_tipo_filtro
				ORDER BY rrf.orden ASC 
				";			
	$data = array("consulta" => $query);	
	$resultado = $services->sendPostNoToken($url_services.'/util/objeto',$data);
	//echo   $resultado;
	
	if($resultado){
		$result_json3 = json_decode($resultado); 
		$contador = 10;
		
		foreach($result_json3 as $result_r3) {
			$contador++;
			$id_filtro			= $result_r3->id_filtro;
			$id_tipo_filtro		= $result_r3->id_tipo_filtro;
			$placeholder		= $result_r3->placeholder;
			$descripcion		= $result_r3->descripcion;
			$mascara			= $result_r3->mascara;
			$option_query		= $result_r3->query;
			$name_html			= $result_r3->name_html;
			$tipo_html			= $result_r3->tipo_html;
			
			switch ($tipo_html) {
			case "empresa":	
				$mostrar_empresa = 1;
				break;
			case "sucursal":	
				$mostrar_sucursal = 1;
				break;
			case "input":
				$mi_input = $filtro_input;
				$mi_input = str_replace("xxxnamexxx","$name_html",$mi_input );
				$mi_input = str_replace("xxxidxxx","$name_html",$mi_input );
				$mi_input = str_replace("xxxplaceholderxxx","$placeholder",$mi_input );
				$mi_input = str_replace("xxxclassxxx","campo$contador",$mi_input );
					
				if( strlen($mascara) >0  )
				$mi_input = str_replace("xxxpropiedadxxx"," data-mask=\"$mascara\" ",$mi_input );
				
				$mi_input = "<div class=\"col-md-4\"><div class=\"form-group\">
				<label >$descripcion:</label>
				$mi_input </div></div>";
													
				$filtros_reporte = $filtros_reporte . "$mi_input";
				break;
			case "date":
				$mi_date = $filtro_date;
				$mi_date = str_replace("xxxnamexxx","$name_html",$mi_date );
				$mi_date = str_replace("xxxidxxx","$name_html",$mi_date );
				$mi_date = str_replace("xxxplaceholderxxx","$placeholder",$mi_date );
				
				if( strlen($mascara) >0  )
				$mi_date = str_replace("xxxpropiedadxxx"," data-mask=\"$mascara\" ",$mi_date );

				$mi_date = "<div class=\"col-md-4\"><div class=\"form-group\">
				<label >$descripcion:</label>
				$mi_date </div></div>";
													
				$filtros_reporte = $filtros_reporte . "$mi_date";
				break;
			case "number":	
				$mi_number = $filtro_number;
				$mi_number = str_replace("xxxnamexxx","$name_html",$mi_number );
				$mi_number = str_replace("xxxidxxx","$name_html",$mi_number );
				$mi_number = str_replace("xxxplaceholderxxx","$placeholder",$mi_number );
				
				if( strlen($mascara) >0  )
				$mi_number = str_replace("xxxpropiedadxxx"," data-mask=\"$mascara\" ",$mi_number );
				
				$mi_number = "<div class=\"col-md-4\"><div class=\"form-group\">
				<label >$descripcion:</label>
				$mi_number </div></div>";
													
				$filtros_reporte = $filtros_reporte . "$mi_number";
				break;
			case "select":			
				$mi_select = $filtro_select;
				$mi_select = str_replace("xxxnamexxx","$name_html",$mi_select );
				$mi_select = str_replace("xxxidxxx","$name_html",$mi_select );
				$mi_select = str_replace("xxxplaceholderxxx","$placeholder",$mi_select );
				
				if( strlen($mascara) >0  )
				$mi_select = str_replace("xxxpropiedadxxx"," data-mask=\"$mascara\" ",$mi_select );
							//********************************************************
							if($option_query!=""){
								$query7 = "	$option_query ";			
								$data7 = array("consulta" => $query7);	
								$resultado7 = $services->sendPostNoToken($url_services.'/util/objeto',$data7);
								//echo   $resultado7;
								$select_options = "<option value='0|Todos'>Todos</option>";
								if($resultado7){
									$result_json7 = json_decode($resultado7); 
									foreach($result_json7 as $result_r7) {
										$result7			= $result_r7;
										$id_option			= $result_r7->id;
										$nombre_option		= $result_r7->nombre;
										$select_options = $select_options."<option value='$id_option|$nombre_option'>$nombre_option</option>";
									}	
								}
								$mi_select = str_replace("xxxoptionxxx","$select_options",$mi_select );
							}//if($option_query!="")
							//********************************************************
				$mi_select = "<div class=\"col-md-4\"><div class=\"form-group\">
				<label >$descripcion:</label>
				$mi_select </div></div>";
													
				$filtros_reporte = $filtros_reporte . "$mi_select";
				break;
			}	

		}	
	}

//EMPRESA Y SUCURSAL****************************************************************************************
$empresa = "";
$sucursal= "";
if($mostrar_empresa==1){
$empresa = "<div class=\"col-md-4\">
								<div class=\"form-group campo-empresa\">
									<label >Empresa:</label>
									$opcion_empresa
								</div>
			</div>";	
}//if($mostrar_empresa==1)
	

if($mostrar_sucursal==1){
$sucursal = "<div class=\"col-md-4\">
								<div class=\"form-group campo-empresa\">
									<label >Sucursal:</label>
									<div id=\"divsucursal\">$opcion_sucursal</div>
								</div>
						</div>";
}//if($mostrar_sucursal==1)


if( $mostrar_sucursal==0 )
$opcion_empresa = str_replace("onchange='seteaSucursal(this.value);'"," ",$opcion_empresa );

?>