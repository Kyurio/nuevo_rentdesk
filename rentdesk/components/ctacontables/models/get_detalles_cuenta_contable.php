<?php
session_start();
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

class CuentasContables
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
        error_log("Resultado del servicio RESTful: " . $result);
        
        return json_decode($result, true);
    }

    public function getCuentasContables($ctacontable)
    {
        try {
            // Validar y escapar el valor de ctacontable
            $ctacontable = intval($ctacontable); // Asegúrate de que sea un número entero

            // Construir la consulta
            $query = "SELECT 
                fccm.cta_contable AS ctacontable,
                UPPER(tcc.nombre) AS nombre,
                fccm.razon AS razon,
                CASE WHEN tcc.tipo_movimiento = 'DEBE' THEN fccm.monto ELSE 0 END AS cargo,
                CASE WHEN tcc.tipo_movimiento = 'HABER' THEN fccm.monto ELSE 0 END AS abono,
                CASE WHEN tcc.tipo_movimiento = 'DEBE' THEN fccm.monto ELSE 0 END - 
                CASE WHEN tcc.tipo_movimiento = 'HABER' THEN fccm.monto ELSE 0 END AS saldo
            FROM 
                propiedades.ficha_arriendo_cta_cte_movimientos fccm
            INNER JOIN 
                propiedades.tp_cta_contable tcc ON tcc.nro_cuenta = fccm.cta_contable
            WHERE 
                fccm.estado = 'P' AND fccm.cta_contable = $ctacontable
            ORDER BY 
                fccm.cta_contable, UPPER(tcc.nombre)"; 
            
            $results = $this->executeQuery($query); // Ejecuta la consulta sin pasar parámetros
            error_log("Resultados: " . print_r($results, true)); // Log de resultados

            return $results;
        } catch (Exception $e) {
            error_log("Excepción: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}

// Procesar la solicitud solo si se pasa el parámetro ctacontable
header('Content-Type: application/json');

if (isset($_GET['ctacontable'])) {
    $ctacontable = $_GET['ctacontable'];
    $cuentasContables = new CuentasContables();
    $data = $cuentasContables->getCuentasContables($ctacontable);
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'ctacontable no especificado']);
}
?>
