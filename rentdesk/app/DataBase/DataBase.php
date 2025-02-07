<?php

namespace app\database;

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv as Dotenv;
use PDO;
use PDOException;

class Database {
    
    private static $instance = null;
    private $conn;
    
    private function __construct() {

        try {
            // Cargar variables del archivo .env
            $dotenv = Dotenv::createImmutable(__DIR__ . '../../config');
            $dotenv->load();
        } catch (\Throwable $th) {
            die("Error al cargar el archivo .env: " . $th->getMessage());
        }

        try {
            // Conectar a PostgreSQL usando PDO
            $this->conn = new PDO("pgsql:host=".$_ENV['DB_HOST'].";port=".$_ENV['DB_PORT'].";dbname=".$_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Establecer el conjunto de caracteres a UTF-8
            $this->conn->exec("SET NAMES 'utf8'");

        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Database();
        }
        
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}