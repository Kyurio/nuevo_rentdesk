<?php

$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

// $usuario_token  = $_SESSION["rd_usuario_token"];
// $usuario_rol    = $_SESSION["usuario_rol"];
// $company_token	= $_SESSION["company_token"];

$current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

// Simulating user's role
// $userRole = $current_subsidiaria->rol;

// $data = array("idRol" => $usuario_rol, "idTipoMenu" => 2);
// $resultado = $services->sendPostNoToken($url_services . '/menuRol/accesos', $data);

$num_reg = 50;
$inicio = 0;

$queryMenuConfigs = "SELECT json_build_object(
    'icon', mcg.icon, 
    'id', mcg.nombre, 
    'index', mcg.index, 
    'items', json_agg(
      json_build_object(
        'index', mci.index, 
        'label', mci.label, 
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
  ) as \"menuConfigs\"
  FROM propiedades.menu_componentes_grupos mcg
  JOIN propiedades.tp_tipo_menu ttm ON ttm.id = mcg.id_tipo_menu
  JOIN propiedades.menu_componentes_items mci ON mci.id_grupo = mcg.id
  JOIN propiedades.cuenta_rol_componentes crc ON crc.id_componente_item  = mci.id 
  JOIN propiedades.cuenta_roles cr ON cr.id  = crc.id_rol 
  WHERE ttm.habilitado = true
    AND mcg.habilitado = true
    AND ttm.nombre = 'menuConfigs'
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

// var_dump("resultado: ", $resultado);
// var_dump("json: ", $json);


$menuItems = "";
$menuName = "menuConfigs";

// Define a function to compare objects by the 'index' property
$compareByIndex = function ($a, $b) {
    return $a['index'] - $b['index'];
};


//Add menuConfigs if applicable
if (isset($json[0][$menuName]) && canDisplayMenuConfigs($json[0][$menuName])) {
    $menu = $json[0][$menuName];
    $menuItems .= '<li class="nav-item dropdown btn-rotate  d-flex dropstart">';
    $menuItems .= '<a class="nav-link no-decoration" href="" id="menuConfigs" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="10,20" style="display: flex; align-items: center; gap: 0.3rem; padding:.5rem"><i class="fa-solid fa-gear font-icon-sup"></i><i class="fas fa-chevron-down"></i><p><span class="d-lg-none d-md-block">' . $menu['label'] . '</span></p></a>';
    $menuItems .= '<div class="dropdown-menu" aria-labelledby="menuConfigs">';

    usort($menu['items'], $compareByIndex);

    foreach ($menu['items'] as $item) {
        if (canDisplayMenuItemConfigs($item)) {

            $menuItems .= '<a class="dropdown-item no-decoration" href="' . $item['url'] . '">' . $item['label'] . '</a>';
        }
    }
    $menuItems .= '</div>';
    $menuItems .= '</li>';
}



// foreach ($json as $valor) {
// 	$url = $valor->url;
// 	$icono = $valor->icono;
// 	$nombre = $valor->nombre;

// 	$lista_menu_conf = $lista_menu_conf . "
// 	<a class='dropdown-item' href='$url'>$nombre</a>";
// }


function canDisplayMenuItemConfigs($menuItem)
{
    global $userRole;
    if (isset($menuItem['role']) && is_array($menuItem['role'])) {
        return in_array($userRole, $menuItem['role']);
    }
    return true;
}

function canDisplayMenuConfigs($menu)
{
    global $urlActual;


    if (isset($menu['items']) && is_array($menu['items'])) {


        return count($menu['items']) !== 0;
    }
    return true;
}
