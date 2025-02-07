<?php
session_start();
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

class Items
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
        error_log("Resultado del servicio RESTful: " . $result);
        
        return json_decode($result, true);
    }

    public function getValoresItems()
    {
        try {
            $query = "SELECT 
                id_contribucion,
                id_propiedad,
                rol,
                TO_CHAR(fecha_contribucion::DATE, 'DD/MM/YYYY') AS fecha_contribucion,
                num_cuota,
                valor_cuota,
                TO_CHAR(fecha_pago::DATE, 'DD/MM/YYYY') AS fecha_pago,
                monto_contrib,
                mes_contrib,
                ano_contrib,
                UPPER(estado) AS estado
            FROM 
                propiedades.propiedad_contribuciones_temp
            ORDER BY 
                fecha_contribucion DESC"; 

            error_log("Consulta SQL: " . $query);
            $results = $this->executeQuery($query);

            // Verifica el contenido de $results
            error_log("Resultados: " . print_r($results, true));

            return $results;
        } catch (Exception $e) {
            error_log("Excepción: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}

header('Content-Type: application/json');

// Crear instancia de la clase Items
$items = new Items();
$data = $items->getValoresItems();
echo json_encode($data);
?>
