<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$arrendatarios = "";


$dniRepre = @$_POST['dniRepre'];
$tipoDni = @$_POST['tipoDni'];
$EstadoCivil = @$_POST['EstadoCivil'];
$nombreRepresentante = @$_POST['nombreRepresentante'];
$apellidoPateRepresentante = @$_POST['apellidoPateRepresentante'];
$apellidoMateRepresentante = @$_POST['apellidoMateRepresentante'];
$telefonoFijoRepresentante = @$_POST['telefonoFijoRepresentante'];
$telefonoMovilRepresentante = @$_POST['telefonoMovilRepresentante'];
$correoElectronicoRepresentante = @$_POST['correoElectronicoRepresentante'];
$paisRepresentante = @$_POST['paisRepresentante'];
$regionRepresentante = @$_POST['regionRepresentante'];
$comunaRepresentante = @$_POST['comunaRepresentante'];
$direccionRepresentante = @$_POST['direccionRepresentante'];
$numeroRepresentante = @$_POST['numeroRepresentante'];
$tipo_documento_repre = @$_POST['tipo_documento_repre'];
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_subsidiaria = $current_subsidiaria->id;

// Obtener el objeto de sesión y convertirlo en un objeto PHP
$sesion_rd_login = unserialize($_SESSION['sesion_rd_login']);
// Acceder a la dirección de correo electrónico
$correo = $sesion_rd_login->correo;




/************** Insert Persona****************** */

$queryInsertPersona = "
INSERT INTO propiedades.persona (id_tipo_persona, id_subsidiaria, dni, id_tipo_dni, telefono_fijo, telefono_movil, correo_electronico) 
VALUES (1, '$id_subsidiaria', '$dniRepre', '$tipo_documento_repre', '$telefonoFijoRepresentante', '$telefonoMovilRepresentante', '$correoElectronicoRepresentante')";


$dataCab = array("consulta" => $queryInsertPersona);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

/************** Insert ID Insertado****************** */
$num_reg = 1;
$inicio = 0;
$queryPersona = "
select id from  
propiedades.persona 
where  
dni = '$dniRepre'";


$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryPersona, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCheques = json_decode($resultado);
$objeto = $objCheques[0]; // Accede al primer elemento del array
$id = $objeto->id; // Accede al valor de la propiedad 'id' dentro del objeto stdClass


/************** Insert persona Natural****************** */

$queryInsertPersonaNatural = "
INSERT INTO propiedades.persona_natural (id_persona, nombres, apellido_paterno, apellido_materno, id_estado_civil, fecha_nacimiento)
values ('$id', '$nombreRepresentante', '$apellidoPateRepresentante', '$apellidoMateRepresentante', '1', '2000-01-01' )";


$dataCab = array("consulta" => $queryInsertPersonaNatural);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);


/************** Insert persona Direccion****************** */


$queryInsertPersonaDireccion = " 
INSERT INTO propiedades.persona_direcciones (id_persona, direccion, numero, id_comuna, principal) 
VALUES('$id', '$direccionRepresentante', '$numeroRepresentante','$comunaRepresentante','TRUE');";



$dataCab = array("consulta" => $queryInsertPersonaDireccion);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);



/*****************INSERT PERSONA SUBSIDIARIA***************************** */
$queryInsertPersonaSubsidiaria = " 
INSERT INTO propiedades.persona_subsidiaria (id_persona , id_subsidiaria , usuario_creacion ,habilitado ) 
VALUES('$id', '$id_subsidiaria', '$correo','TRUE');";



$dataCab = array("consulta" => $queryInsertPersonaSubsidiaria);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);



echo $id;


//return true;
