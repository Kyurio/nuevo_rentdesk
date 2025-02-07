<?php
session_start();
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

class Cheques
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

    public function getValoresCheques($fechaDesde = null, $fechaHasta = null, $estado = FALSE)
    {
        try {
            $query = "SELECT 
                fach.id_ficha_arriendo AS ficha_arriendo,
                far.id_propiedad AS id_propiedad,
                TO_CHAR(fach.fecha_cobro::DATE, 'DD/MM/YYYY') AS fecha_cobro,
                TO_CHAR(fach.fecha_cobro::DATE, 'YYYYMMDD') AS marcatiempo,
                UPPER(pro.direccion || ' ' || pro.numero) AS direccion,
                UPPER(tip.nombre) AS tipo_propiedad,
                UPPER(bco.nombre) AS nombre_banco,
                UPPER(fach.razon) AS girador,
                UPPER(fach.numero_documento) AS numero_cheque,
                UPPER(fach.girador) AS girador,
                fach.monto AS monto,
                       fach.desposito 
                        AS depositado,
                UPPER(
                    CASE
                        WHEN fach.cobrar = true THEN 'COBRADO'
                        ELSE 'POR COBRAR'
                    END
                ) AS cobrado,
                UPPER(COALESCE(fach.comentario, '')) AS comentario,
                pro.token,
                fach.token as tokencheque,
				far.token As tokenFichaArriendo
            FROM 
                propiedades.ficha_arriendo_cheques fach
            INNER JOIN 
                propiedades.ficha_arriendo far ON far.id = fach.id_ficha_arriendo
            INNER JOIN 
                propiedades.propiedad pro ON pro.id = far.id_propiedad
            INNER JOIN 
                propiedades.tp_tipo_propiedad tip ON tip.id = pro.id_tipo_propiedad AND tip.habilitado = TRUE
            INNER JOIN 
                propiedades.tp_banco bco ON bco.id = fach.banco AND bco.habilitado = TRUE
            WHERE 
                fach.habilitado = TRUE and fach.desposito = $estado ";

          

            // Agregar condición de rango de fechas si están presentes
            if ($fechaDesde && $fechaHasta) {
                // Escapando las fechas para evitar inyecciones SQL
                $fechaDesde = htmlspecialchars($fechaDesde, ENT_QUOTES, 'UTF-8');
                $fechaHasta = htmlspecialchars($fechaHasta, ENT_QUOTES, 'UTF-8');
                $query .= " AND fach.fecha_cobro::DATE BETWEEN '" . $fechaDesde . "' AND '" . $fechaHasta . "'";
            }

            //$query .= " ORDER BY fach.fecha_cobro, fach.id_ficha_arriendo asc";

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

// Recibir parámetros de fecha desde el front-end
$fechaDesde = isset($_GET['fechaDesde']) ? $_GET['fechaDesde'] : null;
$fechaHasta = isset($_GET['fechaHasta']) ? $_GET['fechaHasta'] : null;
$estado =  $_GET['estado'];



error_log("Fecha Desde: " . $fechaDesde);
error_log("Fecha Hasta: " . $fechaHasta);

$cheques = new Cheques();
$data = $cheques->getValoresCheques($fechaDesde, $fechaHasta, $estado);
echo json_encode($data);
