<?php

use app\database\QueryBuilder;

require '../../../app/model/QuerysBuilder.php';
$QueryBuilder = new QueryBuilder();

$dni = $_POST["dni"];


$table = "propiedades.persona a"; // Tabla base
$columns = "
	a.id AS id_persna,
    a.id_tipo_persona,
    a.dni,
    a.token,
    COALESCE(a.telefono_fijo, '') || ' ' || COALESCE(a.telefono_movil, '') AS telefono,
    a.correo_electronico,
    a.fecha_ingreso,
    a.fecha_modificacion,

    -- Datos de persona natural
    b.nombres, 
    b.apellido_paterno, 
    b.apellido_materno,

    -- Datos de persona jurídica
    c.razon_social, 
    c.giro, 
    c.nombre_fantasia,

    -- Propiedades del copropietario
    d.id_propiedad,
    d.id_propietario,
    d.porcentaje_participacion,
    d.monto_participacion,

    -- Información bancaria
    id_cta_bancaria,
   
    
    -- Nombre completo según tipo de persona
    CASE 
        WHEN a.id_tipo_persona = 1 
            THEN CONCAT(COALESCE(b.nombres, ''), ' ', COALESCE(b.apellido_paterno, ''), '|', COALESCE(b.apellido_materno, '')) 
        WHEN a.id_tipo_persona = 2 
            THEN COALESCE(c.nombre_fantasia, c.razon_social, '') 
        ELSE 'Tipo desconocido'
    END AS nombre_completo
    ";

$joins = [
    [
        'type' => 'LEFT',
        'table' => 'propiedades.persona_natural b',
        'on' => 'a.id = b.id_persona'
    ],
    [
        'type' => 'LEFT',
        'table' => 'propiedades.persona_juridica c',
        'on' => 'a.id = c.id_persona'
    ],
    [
        'type' => 'INNER',
        'table' => 'propiedades.propiedad_copropietarios d',
        'on' => 'a.id = d.id_propietario'
    ]
];

$conditions = [
    'a.dni' => ['=', $dni] // Condición para filtrar por DNI
];

$result = $QueryBuilder->selectAdvanced($table, $columns, $joins, $conditions, '', '', null, false, false); // Debug activado

// Verificar el resultado
if (!empty($result)) {
    echo json_encode($result);
} else {
    echo "No se encontró el propietario.";
}
