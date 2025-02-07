<?php
@include("../../includes/sql_inyection.php");


$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$current_acceso = unserialize($_SESSION["rd_current_accesos"]);

//var_dump ($current_acceso);
//var_dump ($current_subsidiaria);


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


/*MENU DASHBOARD */
$num_reg = 50;
$inicio = 0;

$queryMenuConfigs = "SELECT json_build_object(
    'icon', mcg.icon, 
    'id', mcg.nombre, 
    'index', mcg.index, 
    'items', json_agg(
      json_build_object(
        'id', mci.id, 
        'icon', mci.icon, 
        'index', mci.index, 
        'label', mci.label,
        'descripcion', mci.descripcion,  
        'role', (
          SELECT json_agg(cr.nombre)
          FROM propiedades.cuenta_rol_componentes crc
          JOIN propiedades.cuenta_roles cr ON cr.id = crc.id_rol
          WHERE crc.id_componente_item = mci.id
        ),
        'url', mci.url 
      )
    ),
    'label', mcg.label 
  ) as \"menuDashboard\"
  FROM propiedades.menu_componentes_grupos mcg
  JOIN propiedades.tp_tipo_menu ttm ON ttm.id = mcg.id_tipo_menu
  JOIN propiedades.menu_componentes_items mci ON mci.id_grupo = mcg.id
  JOIN propiedades.cuenta_rol_componentes crc ON crc.id_componente_item  = mci.id 
  JOIN propiedades.cuenta_roles cr ON cr.id  = crc.id_rol 
  WHERE ttm.habilitado = true
    AND mcg.habilitado = true
    AND ttm.nombre = 'menuDashboard'
    and cr.id = (
            select csu.id_rol
          from propiedades.cuenta_subsidiarias_usuarios csu
              inner join propiedades.cuenta_usuario cu on cu.id = csu.id_usuario
              inner join propiedades.cuenta_subsidiaria cs on cs.id_empresa = cu.id_empresa
              and cs.id = csu.id_subsidiaria
          where cu.token = '$current_usuario->token'
              and cs.token = '$current_subsidiaria->token'
              )
  GROUP BY mcg.id";

// var_dump("queryMenuConfigs: ", $queryMenuConfigs);

$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryMenuConfigs, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado, true);


$json_menu_sup = "";
$menuName = "menuDashboard";
if (isset($json[0][$menuName])) {


    // Sort the associative array based on the "index" field
    uasort($json[0][$menuName]['items'], 'sortByIndex');

    $json_menu_sup = $json[0][$menuName]['items'];
}

// Custom sorting function based on the "index" field
function sortByIndex($a, $b)
{
    return $a['index'] - $b['index'];
}
