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
        
        // Verificar el resultado de la solicitud
        error_log("Resultado del servicio RESTful: " . $result);
        
        return json_decode($result, true);
    }

    public function getCuentasContables()
    {
        try {
            $query = "SELECT 		
				UPPER(CONCAT(cu.nombres,' ', cu.apellido_paterno, ' ', cu.apellido_materno)) AS ejectivo,
                fccm.cta_contable AS ctacontable,
                UPPER(tcc.nombre) AS nombre,
                SUM(CASE WHEN tcc.tipo_movimiento = 'DEBE' THEN fccm.monto ELSE 0 END) AS cargo,
                SUM(CASE WHEN tcc.tipo_movimiento = 'HABER' THEN fccm.monto ELSE 0 END) AS abono,
                SUM(CASE WHEN tcc.tipo_movimiento = 'DEBE' THEN fccm.monto ELSE 0 END) - 
                SUM(CASE WHEN tcc.tipo_movimiento = 'HABER' THEN fccm.monto ELSE 0 END) AS saldo

            FROM 
                propiedades.ficha_arriendo_cta_cte_movimientos fccm
            INNER JOIN 
                propiedades.tp_cta_contable tcc ON tcc.nro_cuenta = fccm.cta_contable
			INNER JOIN 
				propiedades.ficha_arriendo fa ON fccm.id_ficha_arriendo = fa.id
			INNER JOIN 
				propiedades.propiedad p ON fa.id_propiedad = p.id
			INNER JOIN 
				propiedades.cuenta_usuario cu ON p.id_ejecutiva_encargada = cu.id
				
            WHERE fccm.estado = 'P'
            
	    	GROUP BY 
                fccm.cta_contable, UPPER(tcc.nombre), nombres, apellido_paterno, apellido_materno
            ORDER BY 
                fccm.cta_contable, UPPER(tcc.nombre)";

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

// Crear instancia de la clase CuentasContables
$cuentasContables = new CuentasContables();
$data = $cuentasContables->getCuentasContables();
echo json_encode($data);
?>
