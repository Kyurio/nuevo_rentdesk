<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
require_once("../../../includes/envia_email.php");

$config = new Config;
$services = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];

$selectedRows = isset($_POST['selectedRows']) ? $_POST['selectedRows'] : [];



// Set timezone and get the current date and time
date_default_timezone_set("America/Santiago");
$dateTime = new DateTime();
$dateTimeString = $dateTime->format('Y-m-d H:i:s'); // Full date and time string

// Obtener el objeto de sesión y convertirlo en un objeto PHP
$sesion_rd_login = unserialize($_SESSION['sesion_rd_login']);
$correo = $sesion_rd_login->correo;

// 1. Obtener Autorizadores
$queryAutorizadores = "SELECT *
    FROM propiedades.cuenta_usuario
    WHERE id_empresa = $id_company 
    AND autorizador = true 
    AND habilitado = true";

$num_pagina =  round(1 / 9999) + 1;
$dataAutorizadores = array("consulta" => $queryAutorizadores, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultadoAutorizadores = $services->sendPostNoToken($url_services . '/util/paginacion', $dataAutorizadores, []);
$usuariosAutorizadores = json_decode($resultadoAutorizadores, true);

if (empty($usuariosAutorizadores)) {
    echo "Autorizadores no encontrados";
    exit;
}

// 2. Obtener usuario por correo (usuario sesión actual)
$queryUsuario = "SELECT *
    FROM propiedades.cuenta_usuario
    WHERE id_empresa = $id_company  AND UPPER(correo) = UPPER('$correo')";

$data = array("consulta" => $queryUsuario, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);

$objUsuario = json_decode($resultado, true)[0];



if (!$objUsuario) {
    echo "Usuario no encontrado";
    exit;
}

// 3. Obtener autorizadores ya asociados al usuario
$queryHistorial = "SELECT *
    FROM propiedades.historial_autorizadores
    WHERE id_usuario = {$objUsuario['id']}";

$dataHistorial = array("consulta" => $queryHistorial, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultadoHistorial = $services->sendPostNoToken($url_services . '/util/paginacion', $dataHistorial, []);
$historialAutorizadores = json_decode($resultadoHistorial, true);

// 4. Mapeo de autorizadores existentes
$autorizadoresMap = [];
foreach ($historialAutorizadores as $hist) {
    $autorizadoresMap[$hist['id_usuario_autorizador']] = $hist;
}

foreach ($usuariosAutorizadores as $usuarioAut) {

    $idUsuario = $objUsuario['id'];
    $idUsuarioAutorizador = $usuarioAut['id'];
    $codigoAutorizacionUsuario = generarCodigoAutorizacionUsuario();

    if (isset($autorizadoresMap[$idUsuarioAutorizador])) {
        $hist = $autorizadoresMap[$idUsuarioAutorizador];
        $fechaCreacionCodigo = new DateTime($hist['fecha_creacion_codigo']);
        $interval = $fechaCreacionCodigo->diff($dateTime);

        //if ($interval->h >= 4 || $interval->days > 0) {
        // Update codigo_autorizacion and fecha_codigo_creacion if older than 4 hours
        $queryUpdateHistorialAutorizador = "UPDATE propiedades.historial_autorizadores
                SET codigo_autorizacion = '$codigoAutorizacionUsuario', fecha_creacion_codigo = '$dateTimeString'
                WHERE id_usuario = $idUsuario AND id_usuario_autorizador = $idUsuarioAutorizador";

        $dataUpdate = array("consulta" => $queryUpdateHistorialAutorizador);
        $resultadoUpdate = $services->sendPostDirecto($url_services . '/util/dml', $dataUpdate);


        if (!$resultadoUpdate) {
            echo "Error al actualizar historial autorizador para usuario $idUsuario";
            exit;
        }
        //}
    } else {
        // Insert new autorizadores into historial_autorizadores
        $queryInsertHistorialAutorizador = "INSERT INTO propiedades.historial_autorizadores
            (id_usuario, id_usuario_autorizador, codigo_autorizacion, fecha_creacion_codigo)
            VALUES($idUsuario, $idUsuarioAutorizador, '$codigoAutorizacionUsuario', '$dateTimeString')";

        $dataInsert = array("consulta" => $queryInsertHistorialAutorizador);
        $resultadoInsert = $services->sendPostDirecto($url_services . '/util/dml', $dataInsert);

        if (!$resultadoInsert) {
            echo "Error al insertar historial autorizador para usuario $idUsuario";
            exit;
        }
    }
}

// Obtener Autorizadores de usuario actualizados
$queryAutorizadores = "SELECT au.*, cu.nombres, cu.correo, cu.apellido_paterno, cu.apellido_materno
    FROM propiedades.historial_autorizadores au, propiedades.cuenta_usuario cu 
    WHERE au.id_usuario = {$objUsuario['id']}
    and cu.id = au.id_usuario_autorizador
    and cu.habilitado = true
    and cu.autorizador = true";

$num_pagina =  round(1 / 9999) + 1;
$dataAutorizadores = array("consulta" => $queryAutorizadores, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultadoAutorizadores = $services->sendPostNoToken($url_services . '/util/paginacion', $dataAutorizadores, []);
$usuariosAutorizadores = json_decode($resultadoAutorizadores, true);

// captura el array de arriendos seleccionados
// Inicializa el total
$total = 0;
$filasTabla  = '';
// Valida que haya datos
if (empty($selectedRows)) {
    echo json_encode(['status' => 'error', 'message' => 'No se recibieron datos de las moras.']);
    exit;
}

foreach ($selectedRows as $row) {
    $arriendo = htmlspecialchars($row['arriendo']);
    $direccion = htmlspecialchars($row['direccion']);
    $arrendatario = htmlspecialchars($row['arrendatario']);
    $saldo = htmlspecialchars($row['saldo']);

    // Limpia el saldo, convierte a valor absoluto y formatea como moneda chilena
    $saldoLimpio = str_replace(['$', '-', '.'], '', $saldo); // Elimina $ y comas
    $saldoAbsoluto = floatval($saldoLimpio); // Valor absoluto
    $saldoChileno = '$' . number_format($saldoAbsoluto, 0, '', '.'); // Formatea como moneda chilena

    $total += $saldoAbsoluto;
 
    $filasTabla .= "<tr>
                    <td>{$direccion}</td>
                    <td>{$arrendatario}</td>
                    <td style='color: red;'>{$saldoChileno}</td>
                </tr>";
}

// Formatear el total como moneda chilena
$totalFormatted = number_format($total, 0, '', '.');

$nombre = $_SESSION['sesion_rd_usuario'];
$serializado = $nombre;
if (!empty($serializado)) {
    try {
        // Intenta deserializar el contenido
        $usuario = @unserialize($serializado);

        // Verifica si el resultado es válido
        if ($usuario !== false && $usuario instanceof stdClass) {
            // Accede a los valores del objeto
            $nombres = $usuario->nombres ?? '';
            $apellidoPaterno = $usuario->apellidoPaterno ?? '';
            $apellidoMaterno = $usuario->apellidoMaterno ?? '';

           
        } else {
            //echo "El contenido no es un objeto serializado válido.";
        }
    } catch (Exception $e) {
        //echo "Error al deserializar: " . $e->getMessage();
    }
} else {
    //echo "No hay datos disponibles para deserializar.";
}

$serializadoCorreo = $_SESSION['sesion_rd_login'];
if (!empty($serializadoCorreo)) {
    try {
        // Deserializa el contenido
        $datosCorreo = @unserialize($serializadoCorreo);

        // Verifica si es un array y contiene la clave 'correo'
        if ($datosCorreo !== false && is_array($datosCorreo)) {
            $correo = $datosCorreo['correo'] ?? '';
        
            echo $correo;
        } else {
//            echo "El contenido no es válido o no contiene datos de correo.";
        }
    } catch (Exception $e) {
  //      echo "Error al deserializar: " . $e->getMessage();
    }
} else {
   // echo "No hay datos disponibles para deserializar.";
}



foreach ($usuariosAutorizadores as $usuarioAut) {

    $asunto = 'Código de autorización';
    $mensajeCorreo = 'Hola, este es el código de autorización: ' . $usuarioAut['codigo_autorizacion'];
    $mailtoLink = 'mailto:' . $correo . '?subject=' . rawurlencode($asunto) . '&body=' . rawurlencode($mensajeCorreo);


    // HTML en una variable
    $mensaje = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
            .email-container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; }
            .email-header { background-color: #ff1c37; color: #ffffff; padding: 20px; text-align: center; font-size: 24px; font-weight: bold; }
            .email-body { padding: 20px; font-size: 16px; }
            .email-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            .email-table th, .email-table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            .email-table th { background-color: #ff1c37; color: #ffffff; font-weight: bold; }
            .email-table td { font-size: 10px; }
            .btn { 
                display: inline-block; 
                background-color: #ff1c37; 
                color: #ffffff !important; 
                padding: 10px 20px; 
                text-decoration: none; 
                border-radius: 4px; 
                font-weight: bold; 
                text-align: center; 
            }
            .btn:hover {
                background-color: #e6002a; 
            }       
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="email-header">Confirmación de Autorización</div>
            <div class="email-body">
           

                <p>Hola {$usuarioAut['nombres']} {$usuarioAut['apellido_paterno']} {$usuarioAut['apellido_materno']},</p>
                <p>Comparto la información necesaria para proceder con la eliminación de las moras, conforme a la solicitud realizada por: {$nombres} {$apellidoPaterno} {$apellidoMaterno}, ({$correo}), por un monto total de $<span class="numero">{$totalFormatted}</span></p> Quedamos atento a tu confirmación para continuar con el proceso.
                <p style="text-align: center; font-weight: bold; font-size: 20px; color: #ff1c37;">Código de Autorización: {$usuarioAut['codigo_autorizacion']}</p>
                <div style="text-align: center; margin-top: 20px;">
                    <a class="btn" href="$mailtoLink">Enviar Autorización</a>
                </div>
                <table class="email-table">
                    <thead>
                        <tr>
                            <th>Arriendo</th>
                            <th>Arrendatario</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        $filasTabla
                    </tbody>
                </table>
              
                <p>Saludos, equipo Rentdesk</p>



            </div>
        </div>
    </body>
    </html>
    HTML;
    // Parametros a enviar a función de correo   
    envia_mail('Sistema Rentdesk', $usuarioAut['nombres'], $usuarioAut['correo'], 'Petición de Autorización Eliminación Mora', $mensaje, 3, 'https://rentalpartner.cl/templates/fuenzalida/images/logo-rp.png');
}


echo true;
