<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$config       = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


$tipo_documento = @$_POST["tipo_documento"];
$dni = @$_POST["dniEditar"];
$tipo_persona_legal = @$_POST["tipo_persona_legal"];
$telefonoFijo = @$_POST["telefonoFijo"];
$telefonoMovil = @$_POST["telefonoMovil"];
$correoElectronico = @$_POST["correoElectronico"];
$token = @$_POST["token"];
//// DATOS PERSONA JURIDICA
$giro = @$_POST["giro"];
$nombreFantasia = @$_POST["nombreFantasia"];
$razonSocial = @$_POST["razonSocial"];
$idRepresentante = @$_POST["hiddenRepresentante"];
//DATOS PERSONA NATURAL
$nombre = @$_POST["nombre"];
$apellidoPat = @$_POST["apellidoPat"];
$apellidoMat = @$_POST["apellidoMat"];
$fechaNacimiento = @$_POST["fechaNacimiento"];
if ($fechaNacimiento == "") {
      $fechaNacimiento = "1990-01-01";
}
$estado_civil = @$_POST["estado_civil"];
//// DATOS PERSONA DIRECCION
$comuna = @$_POST["comunacom"];
$direccion = @$_POST["direccion"];
$nroComplemento = @$_POST["nroComplemento"];

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_subsidiaria = $current_subsidiaria->id;


// Obtener el objeto de sesión y convertirlo en un objeto PHP
$sesion_rd_login = unserialize($_SESSION['sesion_rd_login']);
// Acceder a la dirección de correo electrónico
$correo = $sesion_rd_login->correo;




/************** ID Cliente****************** */

$num_reg = 1;
$inicio = 0;
$queryPersona = "
select id, token from  
propiedades.persona 
where  
token = '$token' ";


$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryPersona, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCliente = json_decode($resultado);
$objeto = $objCliente[0]; // Accede al primer elemento del array
$id = $objeto->id; // Accede al valor de la propiedad 'id' dentro del objeto stdClass

/************** ID Update persona (cliente)****************** */
$queryUpdatePersona = "
        UPDATE propiedades.persona
	      SET dni = '$dni', 
        id_tipo_dni  = '$tipo_documento', 
        telefono_fijo  = '$telefonoFijo', 
        telefono_movil  = '$telefonoMovil', 
        correo_electronico  = '$correoElectronico'
        where id='$id'";
$dataCab = array("consulta" => $queryUpdatePersona);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);


if ($tipo_persona_legal == 1) {
      /************** UPDATE Persona Natural (cliente)****************** */
      $queryUpdatePersonaNatural = "
        UPDATE propiedades.persona_natural
	      SET nombres = '$nombre', 
        apellido_paterno  = '$apellidoPat', 
        apellido_materno  = '$apellidoMat', 
        id_estado_civil  = '$estado_civil', 
        fecha_nacimiento  = '$fechaNacimiento'
        where id_persona='$id'";
      $dataCab = array("consulta" => $queryUpdatePersonaNatural);
      $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
} else if ($tipo_persona_legal == 2) {

      /************** UPDATE Persona Juridica (cliente)****************** */
      $queryUpdatePersonaJuridica = "
        UPDATE propiedades.persona_juridica
	      SET razon_social = '$razonSocial', 
        giro  = '$giro', 
        nombre_fantasia  = '$nombreFantasia', 
        id_representante_legal  = '$idRepresentante'
        where id_persona='$id'";
      $dataCab = array("consulta" => $queryUpdatePersonaJuridica);
      $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

}
/************** UPDATE Persona Juridica (cliente)****************** */
$queryUpdatePersonaDireccion = "
        UPDATE propiedades.persona_direcciones
	      SET direccion = '$direccion', 
        numero  = '$nroComplemento', 
        id_comuna  = '$comuna'
        where id_persona='$id'";
$dataCab = array("consulta" => $queryUpdatePersonaDireccion);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);



echo "OK||" . $id;
