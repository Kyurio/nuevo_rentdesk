<?php

namespace app\database;

require '../../../app/DataBase/DataBase.php';

use PDO;
use PDOException;
use app\database\Database;

class QueryBuilder
{

    private $db;

    public function __construct()
    {
        // Obtener la instancia de la base de datos
        $this->db = Database::getInstance()->getConnection();
    }
    /**
     * Inicia una transacción.
     */
    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    /**
     * Confirma una transacción.
     */
    public function commit()
    {
        $this->db->commit();
    }

    /**
     * Revierte una transacción en caso de error.
     */
    public function rollback()
    {
        $this->db->rollBack();
    }


    /**
     * Realiza un SELECT en una tabla con filtros opcionales.
     */
    public function select($table, $conditions = [])
    {
        $sql = "SELECT * FROM {$table}";
        $params = [];

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", array_map(function ($key) {
                return "{$key} = :{$key}";
            }, array_keys($conditions)));

            $params = $conditions;
        }

        return $this->execute($sql, $params);
    }


    /**
     * Realiza un SELECT con opciones avanzadas (INNER JOIN, WHERE, ORDER BY, GROUP BY, etc.).
     */

    public function selectAdvanced($table, $columns = '*', $joins = [], $conditions = [], $groupBy = '', $orderBy = '', $limit = null, $isCount = false, $debug = false)
    {
        $sql = "SELECT {$columns} FROM {$table}";
        $params = [];

        // Agregar los JOINs si existen
        if (!empty($joins)) {
            foreach ($joins as $join) {
                // Soporte para alias en JOINs
                $sql .= " {$join['type']} JOIN {$join['table']} ON {$join['on']}";
            }
        }

        // Agregar las condiciones WHERE si existen
        if (!empty($conditions)) {
            $clauses = [];

            foreach ($conditions as $key => $condition) {
                $operator = '=';
                $value = $condition;

                // Detectar si se proporcionó un operador personalizado o expresión compleja
                if (is_array($condition) && isset($condition[0])) {
                    [$operator, $value] = $condition;
                }

                // Quitar los puntos del nombre del parámetro
                $paramKey = str_replace('.', '_', $key);

                // Manejar condiciones especiales como IN, NOT IN, LIKE, IS NULL, IS NOT NULL
                if (strtoupper($operator) === 'IN' && is_array($value)) {
                    // Creamos placeholders individuales
                    $placeholderArray = [];
                    foreach ($value as $i => $val) {
                        // Cada elemento debe tener su propio nombre de placeholder
                        $paramKeyItem = "{$paramKey}_{$i}";
                        $placeholderArray[] = ":{$paramKeyItem}";
                        $params[$paramKeyItem] = $val;
                    }

                    $clauses[] = "{$key} IN (" . implode(', ', $placeholderArray) . ")";
                } elseif (strtoupper($operator) === 'NOT IN' && is_array($value)) {
                    $placeholderArray = [];
                    foreach ($value as $i => $val) {
                        $paramKeyItem = "{$paramKey}_{$i}";
                        $placeholderArray[] = ":{$paramKeyItem}";
                        $params[$paramKeyItem] = $val;
                    }
                    $clauses[] = "{$key} NOT IN (" . implode(', ', $placeholderArray) . ")";
                } elseif (strtoupper($operator) === 'LIKE') {
                    $clauses[] = "{$key} LIKE :{$paramKey}";
                    $params[$paramKey] = $value;
                } elseif (strtoupper($operator) === 'IS NULL') {
                    $clauses[] = "{$key} IS NULL";
                } elseif (strtoupper($operator) === 'IS NOT NULL') {
                    $clauses[] = "{$key} IS NOT NULL";
                } else {
                    // Caso simple:  "fa.token = :fa_token"
                    $clauses[] = "{$key} {$operator} :{$paramKey}";
                    $params[$paramKey] = $value;
                }
            }

            $sql .= " WHERE " . implode(" AND ", $clauses);
        }

        // Agregar GROUP BY, ORDER BY, LIMIT, etc.
        if (!empty($groupBy)) {
            $sql .= " GROUP BY {$groupBy}";
        }
        if (!empty($orderBy)) {
            $sql .= " ORDER BY {$orderBy}";
        }
        if (!empty($limit)) {
            $sql .= " LIMIT {$limit}";
        }

        // Modo de depuración
        if ($debug) {
            echo "SQL Query: $sql\n";
            print_r($params);
        }

        return $this->execute($sql, $params, $isCount);
    }

    /**
     * Ejecuta consultas SQL complejas directamente.
     */
    public function executeComplexQuery($sql, $params = [], $debug = false)
    {
        try {
            if ($debug) {
                echo "SQL Query: $sql\n";
                print_r($params);
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al ejecutar la consulta SQL compleja: " . $e->getMessage());
        }
    }

    /**
     * Realiza un INSERT en una tabla.
     */
    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($key) => ":{$key}", array_keys($data)));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        return $this->execute($sql, $data);
    }

    /**
     * Realiza un UPDATE en una tabla.
     */
    public function update($table, $data, $conditions)
    {
        $setClause = implode(", ", array_map(fn($key) => "{$key} = :{$key}", array_keys($data)));
        $whereClause = implode(" AND ", array_map(fn($key) => "{$key} = :where_{$key}", array_keys($conditions)));

        $sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";

        // Renombrar los parámetros de las condiciones para evitar conflicto
        $params = array_merge($data, array_combine(
            array_map(fn($key) => "where_{$key}", array_keys($conditions)),
            array_values($conditions)
        ));


        return $this->execute($sql, $params);
    }

    /**
     * Realiza un DELETE en una tabla.
     */
    public function delete($table, $conditions)
    {
        $whereClause = implode(" AND ", array_map(fn($key) => "{$key} = :{$key}", array_keys($conditions)));
        $sql = "DELETE FROM {$table} WHERE {$whereClause}";
        return $this->execute($sql, $conditions);
    }

    /**
     * Ejecuta funciones sql.
     */
    public function executeFunction($functionName, $params = [])
    {
        try {
            // Construimos el string de la llamada a la función SQL
            $placeholders = implode(", ", array_fill(0, count($params), "?"));
            $sql = "SELECT {$functionName}({$placeholders}) AS result";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_values($params));

            // Retornamos el resultado de la función
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['result'] : null;
        } catch (PDOException $e) {
            die("Error al ejecutar la función SQL: " . $e->getMessage());
        }
    }


    /**
     * Vacía una tabla (TRUNCATE).
     * 
     * @param string $table Nombre de la tabla a truncar.
     * @return bool True si la operación fue exitosa, false en caso de error.
     */
    public function truncate($table)
    {
        try {
            $sql = "TRUNCATE TABLE {$table}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error al truncar la tabla: " . $e->getMessage());
        }
    }

    /**
     * Ejecuta una consulta SQL con parámetros.
     */
    private function execute($sql, $params = [])
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }
}
