<?php
@include("../../includes/sql_inyection.php");


$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

$queryParams = array(
    'token_subsidiaria' => $current_subsidiaria->token
);

$resultado = $services->sendGet($url_services . '/rentdesk/cuentas/menus', null, [], $queryParams);

$json = json_decode($resultado);

// Decode the JSON data
$menuData = json_decode($resultado, true);

$urlActual = preg_replace('#^/rentdesk/#', '', $_SERVER['REQUEST_URI']);

// Simulating user's role
$userRole = $current_subsidiaria->rol;

$json_parsed = json_decode('[
    {"id_menu":11,"id_menu_padre":null,"tiene_hijos":0,"nombre":"Arrendatario","descripcion":"Arrendatario","url":"index.php?component=arrendatario&view=arrendatario_list","icono":"far fa-id-card","orden":10,"activo":"S","token":"c8e467b6a93f80c8db777a4235807d72","tipo_menu":3,"id_empresa":2,"color":"#02b5e9","ref_externa":null,"query_contador":null,"clase_contador":null},
    {"id_menu":12,"id_menu_padre":null,"tiene_hijos":0,"nombre":"Propietario","descripcion":"Propietario","url":"index.php?component=propietario&view=propietario_list","icono":"fas fa-user-tie","orden":20,"activo":"S","token":"612f66977daaf15dfe8b4c4394e15ee2","tipo_menu":3,"id_empresa":2,"color":"#252422","ref_externa":null,"query_contador":null,"clase_contador":null},
    {"id_menu":14,"id_menu_padre":null,"tiene_hijos":0,"nombre":"Propiedades","descripcion":"Propiedades","url":"index.php?component=propiedad&view=propiedad_list","icono":"fas fa-door-open","orden":30,"activo":"S","token":"97b9992c3214a05bbce5d981e3392b6e","tipo_menu":3,"id_empresa":2,"color":"#ffa302","ref_externa":null,"query_contador":null,"clase_contador":null},
    {"id_menu":13,"id_menu_padre":null,"tiene_hijos":0,"nombre":"Contratos","descripcion":"Contratos","url":"index.php?component=contrato&view=contrato_list","icono":"far fa-file-alt","orden":40,"activo":"S","token":"171fc5edb06a88bef620ea6d0e57c9bf","tipo_menu":3,"id_empresa":2,"color":"#18c80a","ref_externa":null,"query_contador":null,"clase_contador":null},
    {"id_menu":15,"id_menu_padre":null,"tiene_hijos":0,"nombre":"Visitas","descripcion":"Visitas","url":"index.php?component=visita&view=visita_list","icono":"fas fa-eye","orden":50,"activo":"S","token":"90a157f0ed994f66fe2efaf68b1b04b8","tipo_menu":3,"id_empresa":2,"color":"#ffd900","ref_externa":null,"query_contador":null,"clase_contador":null},
    {"id_menu":18,"id_menu_padre":null,"tiene_hijos":0,"nombre":"Arriendos","descripcion":"Arriendos","url":"index.php?component=arriendo&view=arriendo_list","icono":"fas fa-house-user","orden":50,"activo":"S","token":"90a157f0ed994f66fe2efaf68b1b04b8","tipo_menu":3,"id_empresa":2,"color":"#ffd900","ref_externa":null,"query_contador":null,"clase_contador":null},
    {"id_menu":19,"id_menu_padre":null,"tiene_hijos":0,"nombre":"Arriendos Morosos","descripcion":"Arriendos Morosos","url":"index.php?component=arriendo&view=arriendo_morosos_list","orden":50,"activo":"S","token":"90a157f0ed994f66fe2efaf68b1b04b8","tipo_menu":3,"id_empresa":2,"color":"#ffd900","ref_externa":null,"query_contador":null,"clase_contador":null},
    {"id_menu":20,"id_menu_padre":null,"tiene_hijos":0,"nombre":"Propiedades por Liquidar","descripcion":"Propiedades por Liquidar","url":"index.php?component=propiedad&view=propiedad_por_liquidar_list","orden":50,"activo":"S","token":"90a157f0ed994f66fe2efaf68b1b04b8","tipo_menu":3,"id_empresa":2,"color":"#ffd900","ref_externa":null,"query_contador":null,"clase_contador":null},
    {"id_menu":21,"id_menu_padre":null,"tiene_hijos":0,"nombre":"Propiedades por Pagar","descripcion":"Propiedades por Pagar","url":"index.php?component=propiedad&view=propiedad_por_pagar_list","orden":50,"activo":"S","token":"90a157f0ed994f66fe2efaf68b1b04b8","tipo_menu":3,"id_empresa":2,"color":"#ffd900","ref_externa":null,"query_contador":null,"clase_contador":null}

    ]');


$json_filtered = array_filter($json_parsed, function ($data) {
    return $data->nombre != 'Visitas';
});

// Define the desired order
$specificOrder = [14, 12, 18, 11, 13, 19, 20, 21];


// Create an associative array to map IDs to their desired order
$orderMap = array_flip($specificOrder);

usort($json_filtered, function ($a, $b) use ($orderMap) {
    $orderA = isset($orderMap[$a->id_menu]) ? $orderMap[$a->id_menu] : PHP_INT_MAX;
    $orderB = isset($orderMap[$b->id_menu]) ? $orderMap[$b->id_menu] : PHP_INT_MAX;

    // Compare the custom order
    return $orderA - $orderB;
});

$json_menu_sup = $json_filtered;
