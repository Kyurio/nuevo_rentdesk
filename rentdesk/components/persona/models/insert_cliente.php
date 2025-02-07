<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$config   = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


$tipo_documento = @$_POST["tipo_documento"];
$dni = @$_POST["dni"];
$tipo_persona_legal = @$_POST["tipo_persona_legal"];
$telefonoFijo = @$_POST["telefonoFijo"];
$telefonoMovil = @$_POST["telefonoMovil"];
$correoElectronico = @$_POST["correoElectronico"];
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
$comuna = @$_POST["comuna"];
$direccion = @$_POST["direccion"];
$nroComplemento = @$_POST["nroComplemento"];

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_subsidiaria = $current_subsidiaria->id;


// Obtener el objeto de sesión y convertirlo en un objeto PHP
$sesion_rd_login = unserialize($_SESSION['sesion_rd_login']);
// Acceder a la dirección de correo electrónico
$correo = $sesion_rd_login->correo;


///////////////////////////////////////////Guardar si trae datos Cuenta

$nombreTitular = $_POST["nombreTitular"];
$rutTitular = $_POST["rutTitular"];
$emailTitular = $_POST["emailTitular"];
$banco = $_POST["banco"];
$ctabanco = $_POST["cta-banco"];
$numCuenta = $_POST["numCuenta"];





//////////CONSULTAR SI EXISTE LA PERSONA CON EL MISMO DNI Y TIPO DE DOCUMENTO


$num_reg = 1;
$inicio = 0;
$queryPersona = "
select id, token from  
propiedades.persona 
where  
dni = '$dni' 
and
id_tipo_dni ='$tipo_documento'";


$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryPersona, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultadoPersona = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);


// Decodificar el JSON en un array asociativo

//$data = json_decode($resultado, true);

// Contar la cantidad de elementos en el array
//$numElements = count($data);

if ($resultado == "") {
  $queryInsertPersona = "
        INSERT INTO propiedades.persona (id_tipo_persona, id_subsidiaria, dni, id_tipo_dni, telefono_fijo, telefono_movil, correo_electronico) 
        VALUES ('$tipo_persona_legal', '$id_subsidiaria', '$dni', '$tipo_documento', '$telefonoFijo', '$telefonoMovil', '$correoElectronico')";


  $dataCab = array("consulta" => $queryInsertPersona);
  $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
  /************** Insert ID Insertado****************** */
  $num_reg = 1;
  $inicio = 0;
  $queryPersona = "
        select id, token from  
        propiedades.persona 
        where  
        dni = '$dni'";


  $cant_rows = $num_reg;
  $num_pagina = round($inicio / $cant_rows) + 1;
  $data = array("consulta" => $queryPersona, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
  $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
  $objCheques = json_decode($resultado);
  $objeto = $objCheques[0]; // Accede al primer elemento del array
  $id = $objeto->id; // Accede al valor de la propiedad 'id' dentro del objeto stdClass
  $token = $objeto->token;

  if ($tipo_persona_legal == 1) {
    /************** Insert persona Natural****************** */

    $queryInsertPersonaNatural = "
        INSERT INTO propiedades.persona_natural (id_persona, nombres, apellido_paterno, apellido_materno, id_estado_civil, fecha_nacimiento)
        values ('$id', '$nombre', '$apellidoPat', '$apellidoMat', '$estado_civil', '$fechaNacimiento' )";

    $dataCab = array("consulta" => $queryInsertPersonaNatural);
    $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
  } else if ($tipo_persona_legal == 2) {
    /************** Insert persona Juridica****************** */

    $queryInsertPersonJuridica = "INSERT INTO propiedades.persona_juridica (id_persona, razon_social, giro, nombre_fantasia, id_representante_legal) 
          VALUES ('$id', '$razonSocial', '$giro', '$nombreFantasia', '$idRepresentante');";
    $dataCab = array("consulta" => $queryInsertPersonJuridica);
    $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
  }
  /************** Insert persona Direccion****************** */


  $queryInsertPersonaDireccion = " 
        INSERT INTO propiedades.persona_direcciones (id_persona, direccion, numero, id_comuna, principal) 
        VALUES('$id', '$direccion', '$nroComplemento','$comuna','TRUE');";



  $dataCab = array("consulta" => $queryInsertPersonaDireccion);
  $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);


  $queryInsertPersonaSubsidiaria = " 
        INSERT INTO propiedades.persona_subsidiaria (id_persona , id_subsidiaria , usuario_creacion ,habilitado ) 
        VALUES('$id', '$id_subsidiaria', '$correo','TRUE');";

  $dataCab = array("consulta" => $queryInsertPersonaSubsidiaria);
  $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);


// jhernandez revision insert propietarios al crear cliente nuevo

  /************** Insert Datos de cuenta en caso de tenerlos ****************** */

 
  // if ($nombreTitular) {


//     $queryInsertPropietario = "INSERT INTO propiedades.persona_propietario (id_persona) 
//           VALUES('$id');";
//     $dataCab = array("consulta" => $queryInsertPropietario);
//     $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

//     $queryInsertCuenta = "INSERT INTO propiedades.propietario_ctas_bancarias 
//           (id_propietario, id_banco, id_tipo_cta_bancaria, numero, correo_electronico, principal, habilitado, rut_titular, nombre_titular)
//           values ($id,'$banco',$ctabanco,'$numCuenta','$emailTitular','TRUE','TRUE','$rutTitular','$nombreTitular')";

    

//     $dataCab = array("consulta" => $queryInsertCuenta);
//     $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

  // }else {

  //   echo "aqui no hay nada ... ";
  
  // }



  echo "OK";

} else {


  $objCliente = json_decode($resultadoPersona);
  $objeto = $objCliente[0]; // Accede al primer elemento del array
  $token = $objeto->token;
  echo $token;

}
