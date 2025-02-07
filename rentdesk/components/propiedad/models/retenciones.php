<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

header('Content-Type: application/json');

include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

class Retenciones
{
    protected $services;
    protected $url_services;

    public function __construct()
    {
        $config = new Config;
        $this->services = new ServicesRestful;
        $this->url_services = $config->url_services;
    }

    public function executeQuery($query, $params = [])
    {
        $data = [
            "consulta" => $query,
            "cantRegistros" => 9999,
            "numPagina" => 1,
            "parametros" => $params
        ];
        
        $result = $this->services->sendPostNoToken($this->url_services . '/util/paginacion', $data);
        
        // Verificar el resultado de la solicitud
       // error_log("Resultado del servicio RESTful: " . $result);
        
        return json_decode($result, true);
    }

    public function getRetenciones($id)
    {
        try {
            // Consulta para obtener los datos de las retenciones y la id_arriendo
            $queryRetenciones = "
                SELECT * FROM propiedades.fn_consulta_retencion($id)";
    
            $queryIdRetencion = "
                SELECT id AS id_retencion, estado_retencion 
                FROM propiedades.propiedad_retenciones 
                WHERE id_propiedad = $id ORDER BY id desc";
    
            // error_log("Consulta SQL para retenciones: " . $queryRetenciones);
            // error_log("Consulta SQL para id_retencion y estado_retencion: " . $queryIdRetencion);
    
            $resultsRetenciones = $this->executeQuery($queryRetenciones);
            $resultsIdRetencion = $this->executeQuery($queryIdRetencion);
    
            // Verifica si ambas consultas devolvieron resultados
            if (is_array($resultsRetenciones) && is_array($resultsIdRetencion)) {
                // Asociar id_retencion y estado_retencion a cada resultado de retención
                $idRetenciones = array_column($resultsIdRetencion, 'id_retencion'); // Extraer todos los id_retencion
                $estadoRetenciones = array_column($resultsIdRetencion, 'estado_retencion'); // Extraer todos los estado_retencion
                
                foreach ($resultsRetenciones as $key => &$retencion) {
                    // Asigna el id_retencion y estado_retencion correspondiente a cada retención
                    if (isset($idRetenciones[$key])) {
                        $retencion['id_retencion'] = $idRetenciones[$key];
                        $retencion['estado_retencion'] = $estadoRetenciones[$key];
                    }
                }
            }
    
            // Verifica el contenido de $results
           //error_log("Resultados de retenciones con estado: " . print_r($resultsRetenciones, true));
    
            return $resultsRetenciones;
        } catch (Exception $e) {
           // error_log("Excepción: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}

// Obtener el ID de propiedad de la solicitud GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$retenciones = new Retenciones();
$data = $retenciones->getRetenciones($id);
echo json_encode($data);
