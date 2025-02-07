<?php
session_start();
include("../../../includes/sql_inyection_salto_textarea.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$codigo				= $_POST['codigo'];
$tipo				= $_POST['tipo'];

$current_sucursal = unserialize($_SESSION["rd_current_sucursal"]);
//var_dump($current_sucursal->sucursalToken);

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

//Aqui hacer la consulta a la base de datos:  *****************************************
$respuesta = 0;
$direccion 	= "";
$contrato 	= "";
$html = "";
//$busqueda = formato_busqueda($codigo);

$num_reg = 10;
$inicio = 0;

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_subsidiaria = $current_subsidiaria->id;

$queryProp = " SELECT ps.dni as dni, ps.token as token, ttp.nombre as tipo_persona,
  pnt.nombres as nombres, pnt.apellido_paterno as apellido_paterno, 
  pnt.apellido_materno as apellido_materno ,pj.razon_social as razon_social, 
  pj.nombre_fantasia  as nombre_fantasia, pd.direccion, pd.numero,
  ps.telefono_fijo as telefono_fijo , ps.telefono_movil as telefono_movil,
  pp.token as tokenpropietario, pa.token as tokenarrendatario, pc.token as tokencodeudor
  FROM propiedades.persona ps 
  left join propiedades.persona_natural pnt on ps.id  = pnt.id_persona
  left  join propiedades.persona_juridica pj  on ps.id = pj.id_persona
  inner join propiedades.tp_tipo_persona ttp on ttp.id =ps.id_tipo_persona 
  inner join propiedades.persona_direcciones pd on ps.id = pd.id_persona 
  left join propiedades.persona_propietario pp on pp.id_persona  = ps.id 
  left join propiedades.persona_arrendatario pa on pa.id_persona  = ps.id 
  left join propiedades.persona_codeudor pc on pc.id_persona  = ps.id 
  where ps.id_subsidiaria = $id_subsidiaria and (
  LOWER(CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno)) LIKE LOWER('%$codigo%')
  or LOWER(dni) LIKE LOWER('%$codigo%')
  or LOWER(razon_social) LIKE LOWER('%$codigo%') 
  or LOWER(nombre_fantasia) LIKE LOWER('%$codigo%'))
  order by dni 
				   ";

				   
$dataRegPagos = array("consulta" => $queryProp );	
$resultadoRegPagos = $services->sendPostDirecto($url_services.'/util/objeto',$dataRegPagos);
//var_dump($queryProp);
$result_jsonRegPagos = json_decode($resultadoRegPagos);
if($result_jsonRegPagos){
	foreach($result_jsonRegPagos as $result) {
		$respuesta = 1; 
		$id_select = $result->dni;
    if($result->tipo_persona == "NATURAL"){
      		$html = $html.'<div><a style="color:#2c699e;" class="suggest-element" id="'.$id_select.'" >'.$id_select.' || '.$result->nombres.' '.$result->apellido_paterno.' '.$result->apellido_materno.'</a></div>';

    }
    if($result->tipo_persona == "JURIDICA"){
      		$html = $html.'<div><a style="color:#2c699e;" class="suggest-element" id="'.$id_select.'" >'.$id_select.' || '.$result->nombre_fantasia.'</a></div>';

    }
		//$html = $html.'<div><a class="suggest-element" id="'.$id_select.'" onClick="ingresaBusqueda(this,\''.$tipo.'\',\''.$direccion.'\');" >'.$result->texto.'</a></div>';
		//$html = $html.'<div><a class="suggest-element" id="'.$result->codigo_propiedad.'" onClick="ingresaBusqueda(this);" >'.$result->direccion.'</a></div>';
		//$html = $html.'<div><a class="suggest-element" id="'.$result->codigo_propiedad.'" onClick="ingresaBusqueda(this);" >'.$result->direccion.'</a></div>';
		//$html = $html. 'value <?php echo "'.$id_select.'";;
		
	}//while
	//$html = $html. 'value <?php echo "'.$id_select.'"';
}



//*************************************************************************************

 
 echo $html;


?>