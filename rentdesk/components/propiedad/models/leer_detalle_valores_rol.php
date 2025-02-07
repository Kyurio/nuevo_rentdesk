<?php
session_start();
include("../../../configuration.php"); // Incluye la configuración
include("../../../includes/funciones.php"); // Incluye funciones adicionales
include("../../../includes/services_util.php"); // Incluye servicios útiles

class Roles
{
    protected $services;
    protected $url_services;

    public function __construct()
    {
        $config = new Config;
        $this->services = new ServicesRestful;
        $this->url_services = $config->url_services;
    }

    public function executeQuery($query)
    {
        $data = [
            "consulta" => $query,
            "cantRegistros" => 9999,
            "numPagina" => 1
        ];
        $result = $this->services->sendPostNoToken($this->url_services . '/util/paginacion', $data, []);
        return json_decode($result);
    }

    public function getValoresRoles($token)
    {
        try {
            $query = "SELECT a.id, 
            año, 
            valor, 
            CASE 
                WHEN cuota = 1 THEN 'Abril'
                WHEN cuota = 2 THEN 'Junio'
                WHEN cuota = 3 THEN 'Septiembre'
                WHEN cuota = 4 THEN 'Noviembre'
                ELSE 'Desconocido'
            END AS mes,
            cobrado, 
            pagado, 
            id_propiedad
        FROM propiedades.valores_roles a 
		INNER JOIN propiedades.propiedad b 
		on a.id_propiedad = b.id
        WHERE b.token = '$token'
        ";
            $results = $this->executeQuery($query);
            return $results;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}

// Instancia la clase y llama al método getValoresRoles

$token = $_GET['token'];

$roles = new Roles();
$resultado = $roles->getValoresRoles($token);
echo json_encode($resultado);
