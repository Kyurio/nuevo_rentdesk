<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 

// Obtener el método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

// Validar si el método no es DELETE
if ($metodo !== 'GET') {
    // Manejar el error
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido. Solo se permite DELETE.']);
    exit; // Terminar la ejecución del script
}


try {


    // Definir la URL del servicio al que deseas enviar el XML
    $url = 'https://dteqa.arpis.cl/WSFactLocal/DteLocal.asmx?WSDL';
    $tipo_doc = 39;
    $rut = '77367969K';


    $client = new SoapClient(trim($url));
    $resultdte = $client->Solicitar_Folio(array('RutEmpresa' => $rut, 'TipoDocto' => $tipo_doc));
    $status_dte = $resultdte->Solicitar_FolioResult->Estatus;

    echo "<pre>";
    echo "resultado:";
    echo $resultdte->Solicitar_FolioResult->Folio;;
    echo "</pre>";


} catch (\Throwable $th) {
    echo $th->getMessage();
}

