<?php

@include("../../includes/sql_inyection.php");


$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

/*----------------------------------- */
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

function canDisplayMenuItem($menuItem)
{
	global $userRole;
	if (isset($menuItem['role']) && is_array($menuItem['role'])) {
		return in_array($userRole, $menuItem['role']);
	}
	return true;
}

function canDisplayMenuSuperior($menu)
{
	global $urlActual;


	if (isset($menu['items']) && is_array($menu['items'])) {


		return count($menu['items']) !== 0;
	}
	return true;
}

// Function to generate menu items
function generarMenuSuperior($menuName)
{

	global $urlActual;
	global $menuData;

	$menuItems = '';

	// Define a function to compare objects by the 'index' property
	$compareByIndex = function ($a, $b) {
		return $a['index'] - $b['index'];
	};

	if ($menuName === "menuPrincipal" && isset($menuData[$menuName]) && count($menuData[$menuName]) != 0  && canDisplayMenuSuperior($menuData[$menuName])) {
		usort($menuData[$menuName], $compareByIndex);

		foreach ($menuData[$menuName] as $menu) {

			if (canDisplayMenuSuperior($menu)) {
				$menuItems .= '<li class="nav-item dropdown btn-rotate  d-flex">';
				if (isset($menu['items'])) {
					usort($menu['items'], $compareByIndex);

					$menuItems .= '<a class="nav-link no-decoration" href="" id="' . $menu['id'] . '" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="10,20" style="display: flex; align-items: center; gap: 0.3rem; padding:.5rem"><span>' . $menu['label'] . ' <i class="fas fa-chevron-down"></i></span></a>';
					$menuItems .= '<div class="dropdown-menu" aria-labelledby="' . $menu['id'] . '">';



					foreach ($menu['items'] as $item) {
						if (canDisplayMenuItem($item)) {
							$menuItems .= '<a class="no-decoration' . ($urlActual == $item['url'] ? ' active-item' : '') . '" href="' . $item['url'] . '">' . $item['label'] . '</a>';
						}
					}

					$menuItems .= '</div>';
				} else {
					$menuItems .= '<a class="no-decoration" class="nav-link" href="' . $menu['url'] . '" id="' . $menu['id'] . '" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="10,20" style="display: flex; align-items: center; gap: 0.3rem; padding:.5rem"><span>' . $menu['label'] . '</span></a>';
				}
				$menuItems .= '</li>';
			}
		}
	}

	// Add menuAcciones if applicable
	if ($menuName === "menuAcciones" && isset($menuData[$menuName]) && count($menuData[$menuName]) != 0 && canDisplayMenuSuperior($menuData[$menuName])) {
		$menu = $menuData[$menuName][0];
		$menuItems .= '<li class="nav-item dropdown btn-rotate  d-flex">';
		$menuItems .= '<a class="nav-link no-decoration" href="" id="menuAcciones" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="10,20" style="display: flex; align-items: center; gap: 0.3rem; padding:.5rem"><i class="far fa-bell font-icon-sup"></i><i class="fas fa-chevron-down"></i><p><span class="d-lg-none d-md-block">' . $menu['label'] . '</span></p></a>';
		$menuItems .= '<div class="dropdown-menu" aria-labelledby="menuAcciones">';

		usort($menu['items'], $compareByIndex);

		foreach ($menu['items'] as $item) {
			if (canDisplayMenuItem($item)) {

				$menuItems .= '<a class="dropdown-item no-decoration" href="' . $item['url'] . '">' . $item['label'] . '</a>';
			}
		}
		$menuItems .= '</div>';
		$menuItems .= '</li>';
	}

	
	// Add menuAcciones if applicable
	if ($menuName === "menu archivos" && isset($menuData[$menuName]) && count($menuData[$menuName]) != 0 && canDisplayMenuSuperior($menuData[$menuName])) {
		$menu = $menuData[$menuName][0];
		$menuItems .= '<li class="nav-item dropdown btn-rotate  d-flex">';
		$menuItems .= '<a class="nav-link no-decoration" href="" id="menuAcciones" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="10,20" style="display: flex; align-items: center; gap: 0.3rem; padding:.5rem"><i class="far fa-bell font-icon-sup"></i><i class="fas fa-chevron-down"></i><p><span class="d-lg-none d-md-block">' . $menu['label'] . '</span></p></a>';
		$menuItems .= '<div class="dropdown-menu" aria-labelledby="menuAcciones">';

		usort($menu['items'], $compareByIndex);

		foreach ($menu['items'] as $item) {
			if (canDisplayMenuItem($item)) {

				$menuItems .= '<a class="dropdown-item no-decoration" href="' . $item['url'] . '">' . $item['label'] . '</a>';
			}
		}
		$menuItems .= '</div>';
		$menuItems .= '</li>';
	}

	// echo "<pre>";	
	// var_dump($menuData[$menuName]);
	// echo "</pre>";

	return $menuItems;
}

