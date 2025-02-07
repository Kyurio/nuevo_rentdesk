<?php

@include("../../includes/sql_inyection.php");


$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

// $usuario_token  = $_SESSION["rd_usuario_token"];
// $usuario_rol    = $_SESSION["usuario_rol"];
// $company_token	= $_SESSION["company_token"];
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);



$queryParams = array(
	'token_subsidiaria' => $current_subsidiaria->token
);

$resultado = $services->sendGet($url_services . '/rentdesk/cuentas/menus', null, [], $queryParams);

$json = json_decode($resultado);

// Decode the JSON data
$menuData = json_decode($resultado, true);

$menuPrincipal = $menuData['menuPrincipal'];

$urlActual = preg_replace('#^/rentdesk/#', '', $_SERVER['REQUEST_URI']);
//var_dump($_SERVER['REQUEST_URI']);
// Simulating user's role
$userRole = $current_subsidiaria->rol;


// Function to check if a menu should be displayed based on user's role
function isActiveLateralTab($buttonId)
{
	$url = $_SERVER['QUERY_STRING'];

	// Parse the query string into variables
	parse_str($url, $query);

	// Access the value of the 'detail' parameter
	$detail = isset($query['detail']) ? $query['detail'] : null;

	return $detail == $buttonId;
	// // Parse the URL to extract the fragment
	// $urlParts = parse_url($_SERVER['REQUEST_URI']);

	// // Check if fragment exists and matches button ID
	// return (isset($urlParts['fragment']) && $urlParts['fragment'] === $buttonId);
}



function stringContainsItem($string, $items)
{
	foreach ($items as $item) {
		if (strpos($string, $item) !== false) {
			return true;
		}
	}
	return false;
}



function canDisplayMenuLateral($menu)
{
	global $urlActual;

	if (isset($menu['url'])) {
		return $urlActual === $menu['url'];
	}

	if (isset($menu['items']) && is_array($menu['items'])) {

		$extractUrls = function ($object) {
			return $object['url'];
		};

		$urls = array_map($extractUrls, $menu['items']);

		return count($menu['items']) !== 0  && stringContainsItem($urlActual, $urls);
	}

	return true;
}


function canDisplayMenuLateralItem($menuItem)
{


	global $userRole;
	if (isset($menuItem['role']) && is_array($menuItem['role'])) {
		return in_array($userRole, $menuItem['role']);
	}
	return true;
}


function generarMenuLateral()
{


    global $urlActual;
    global $menuPrincipal;



    // Define a function to compare objects by the 'index' property
    $compareByIndex = function ($a, $b) {
        return $a['index'] - $b['index'];
    };


    $lateralMenuHTML = '<li><a href="index.php?component=dashboard&view=dashboard" class="no-decoration' . ($urlActual == "index.php?component=dashboard&view=dashboard" ? ' active' : '') . '" ><i  class="fa fa-fw fas fa-home" style="font-size: 1.1rem"></i> Inicio</a></li>';

    if (is_array($menuPrincipal))
        usort($menuPrincipal, $compareByIndex);
//var_dump("menuPrincipal: ",$menuPrincipal);


    foreach ($menuPrincipal as $menuItem) {
        // Check if the menu should be displayed based on user's role
        if (canDisplayMenuLateral($menuItem)) {

            if ($menuItem['label'] === "Arriendos" && stringContainsItem($urlActual, ["component=arriendo&view=arriendo_pago_cheques_list"])) {
                $lateralMenuHTML .= ' ';
            } else if ($menuItem['label'] === "Propiedades" && stringContainsItem($urlActual, ["component=propiedad&view=propiedad_revision_cuentas_servicio_list"])) {
                $lateralMenuHTML .= ' ';
            } else if ($menuItem['label'] === "Propiedades" && stringContainsItem($urlActual, ["component=propiedad&view=propiedad_liquidaciones_generacion_masiva_list"])) {
                $lateralMenuHTML .= ' ';
            } else if ($menuItem['label'] === "Propiedades" && stringContainsItem($urlActual, ["component=propiedad&view=propiedad_pago_arriendo_eliminar_moras_list"])) {
                $lateralMenuHTML .= ' ';
            } else if ($menuItem['label'] === "Propiedades" && stringContainsItem($urlActual, ["component=propiedad&view=propiedad_liquidaciones_pago_a_propietarios_list"])) {
                $lateralMenuHTML .= ' ';
			} else if ($menuItem['label'] === "Cliente" && stringContainsItem($urlActual, ["component=propietario&view=facturas_generacion_masiva_list"])) {
                $lateralMenuHTML .= ' ';
            } else {

                $lateralMenuHTML .= '<li>';

                $lateralMenuHTML .= '<a class="no-decoration active"><i style="color:#ffffff !important;" class="' . $menuItem["icon"] . '" style="font-size: 1.1rem"></i>' . $menuItem['label'] . '</a>';



                // Check if the menu item has sub-options
                if (isset($menuItem['items']) && !empty($menuItem['items'])) {
                    usort($menuItem['items'], $compareByIndex);

                    $lateralMenuHTML .= '<ul class="sub-menu mm-collapse mm-show" style="padding-left:3rem" aria-expanded="false" >';
                    foreach ($menuItem['items'] as $subItem) {
                        if (canDisplayMenuLateralItem($subItem)) {
                            $lateralMenuHTML .= '<li><a class="no-decoration' . ($urlActual == $subItem['url'] ? ' active-item' : '') . '" style="margin-left: 0" href="' . $subItem['url'] . '">' . $subItem['label'] . '</a></li>';
                        }
                    }
                    $lateralMenuHTML .= '</ul>';
                }

                $lateralMenuHTML .= '</li>';
            }
        }
    }
    $lateralMenuHTML .= ' ';

    return $lateralMenuHTML;
}




function generarMenuTabs()
{
	global $urlActual;

	$lateralMenuHTML = '';
	
	
		if (stringContainsItem($urlActual, ["dashboard"])) {
		$lateralMenuHTML = '';

		$lateralMenuHTML .= '
						<li >
						<a class="no-decoration">Inicio</a>
							<ul class="sub-menu mm-collapse mm-show" aria-expanded="false"  style="padding-left:3rem">
							<li ><a id="dashboard-ft-informacion" class="no-decoration  active-item" href="#" >Administración</a></li>
							<li><a id="dashboard-ft-co-propietarios" class="no-decoration"  href="index.php?component=propiedad&view=propiedad_list" >Propiedades</a></li>
							<li><a id="dashboard-ft-retencionesP" class="no-decoration"  href="index.php?component=persona&view=persona_list" >Cliente</a></li>
							<li><a id="dashboard-ft-cuentaCorriente" class="no-decoration" href="index.php?component=arriendo&view=arriendo_list" >Arriendos</a></li>
							<li><a id="dashboard-ft-cuentaServicio" class="no-decoration" href="index.php?component=movimiento&view=movimiento_varios_acreedores_list" >Acciones</a></li>
							<li><a id="dashboard-ft-liquidacionesCoPropietarios" class="no-decoration" href="index.php?component=facturacion&view=facturacion_list" >Facturación</a></li>
							<li><a id="dashboard-ft-notasDeCredito" class="no-decoration" href="#" >Reportes</a></li>
						</ul>

							</li>';
	}
	
	
		if (stringContainsItem($urlActual, ["arriendo_editar"])) {
		$lateralMenuHTML = '';

		$lateralMenuHTML .= '
						<li><a class="no-decoration active"><i style="color:#ffffff !important;" class="fas fa-door-open"></i>Arriendos</a>
							<ul class="sub-menu mm-collapse mm-show" style="padding-left:3rem" aria-expanded="false">
								<li><a class="no-decoration" style="margin-left: 0"
										href="index.php?component=arriendo&amp;view=arriendo_list">Búsqueda de Arriendos</a></li>
								<li><a class="no-decoration" style="margin-left: 0"
										href="index.php?component=arriendo&amp;view=arriendo">Creación de Arriendo</a></li>
					

							 </ul>
						</li>';
	}

	if (stringContainsItem($urlActual, ["propiedad_ficha_tecnica"])) {
		$lateralMenuHTML = '';

		$lateralMenuHTML .= '
						<li >
						<a class="no-decoration">Ficha Técnica</a>
							<ul class="sub-menu mm-collapse mm-show" aria-expanded="false"  style="padding-left:3rem">
							<li ><a id="propiedad-ft-informacion" class="no-decoration  active-item" href="#"    onClick="clickTab(\'propiedad-ft-informacion\');">Información</a></li>
							<li><a id="propiedad-ft-co-propietarios" class="no-decoration"  href="#"  onClick="clickTab(\'propiedad-ft-co-propietarios\');" >Propietarios</a></li>
							<li><a id="propiedad-ft-retencionesP" class="no-decoration"  href="#"  onClick="clickTab(\'propiedad-ft-retencionesP\');" >Retenciones</a></li>
							<li><a id="propiedad-ft-cuentaCorriente" class="no-decoration" href="#"  onClick="clickTab(\'propiedad-ft-cuentaCorriente\');">Cuenta Corriente</a></li>
							<li><a id="propiedad-ft-cuentaServicio" class="no-decoration" href="#" onClick="clickTab(\'propiedad-ft-cuentaServicio\');">Cuentas de Servicios</a></li>
							<li><a id="propiedad-ft-liquidacionesCoPropietarios" class="no-decoration" href="#" onClick="clickTab(\'propiedad-ft-liquidacionesCoPropietarios\');">Liquidaciones Propietarios</a></li>
							<li><a id="propiedad-ft-notasDeCredito" class="no-decoration" href="#" onClick="clickTab(\'propiedad-ft-notasDeCredito\');">Notas de Crédito</a></li>
							<li><a id="propiedad-ft-roles" class="no-decoration" href="#" onClick="clickTab(\'propiedad-ft-roles\');">Roles</a></li>
							<li><a id="propiedad-ft-recordatorios" class="no-decoration" href="#" onClick="clickTab(\'propiedad-ft-recordatorios\');">Recordatorios</a></li>
							<li><a id="propiedad-ft-historial" class="no-decoration" href="#" onClick="clickTab(\'propiedad-ft-historial\');">Historial</a></li>
						</ul>

							</li>';
	}


	if (stringContainsItem($urlActual, ["arriendo_ficha_tecnica"])) {
		$lateralMenuHTML = '';

		$lateralMenuHTML .= '
						<li >
							<a class="no-decoration">Ficha Técnica</a>
							<ul class="sub-menu mm-collapse mm-show" aria-expanded="false"  style="padding-left:3rem">
								<li ><a id="arriendo-ft-informacion" class="no-decoration active-item" href="#"  onClick="clickTab(\'arriendo-ft-informacion\');" >Información</a></li>
								<li><a id="arriendo-ft-cuentaCorriente" class="no-decoration" href="#" onClick="clickTab(\'arriendo-ft-cuentaCorriente\');"  >Cuenta Corriente</a></li>
								<li><a id="arriendo-ft-cheques" class="no-decoration" href="#" onClick="clickTab(\'arriendo-ft-cheques\');" href="#">Cheques</a></li>
								<!--<li><a id="arriendo-ft-cobros" class="no-decoration" href="#" onClick="clickTab(\'arriendo-ft-cobros\');" href="#">Cobros </a></li>-->
								<li><a  id="arriendo-ft-garantia" class="no-decoration" href="#" onClick="clickTab(\'arriendo-ft-garantia\');" href="#">Garantía</a></li>
								<!--<li><a id="arriendo-ft-reajuste" class="no-decoration" href="#" onClick="clickTab(\'arriendo-ft-reajuste\');" href="#">Reajuste</a></li>-->
								<li><a id="arriendo-ft-recordatorios" class="no-decoration" href="#" onClick="clickTab(\'arriendo-ft-recordatorios\');" href="#">Recordatorios</a></li>
								<li><a id="arriendo-ft-historial" class="no-decoration" href="#" onClick="clickTab(\'arriendo-ft-historial\');" href="#">Historial</a></li>
							</ul>

						</li>';
	}


	if (stringContainsItem($urlActual, ["arrendatario_ficha_tecnica"])) {
		$lateralMenuHTML = '';

		$lateralMenuHTML .= '
						<li >
							<a class="no-decoration">Ficha Técnica</a>
							<ul class="sub-menu mm-collapse mm-show" aria-expanded="false"  style="padding-left:3rem">
								<li ><a id="arrendatario-ft-informacion" class="no-decoration active-item"  href="#" onClick="clickTab(\'arrendatario-ft-informacion\');">Información</a></li>
								<li><a id="arrendatario-ft-cuentaCorriente" class="no-decoration" href="#" onClick="clickTab(\'arrendatario-ft-cuentaCorriente\');">Cuenta Corriente</a></li>
								<li><a id="arrendatario-ft-arriendos" class="no-decoration" href="#" onClick="clickTab(\'arrendatario-ft-arriendos\');">Arriendos</a></li>
								<li><a id="arrendatario-ft-historial" class="no-decoration" href="#" onClick="clickTab(\'arrendatario-ft-historial\');">Historial</a></li>
							</ul>

						</li>';
	}


	if (stringContainsItem($urlActual, ["codeudor_ficha_tecnica"])) {
		$lateralMenuHTML = '';

		$lateralMenuHTML .= '
						<li >
							<a class="no-decoration">Ficha Técnica</a>
							<ul class="sub-menu mm-collapse mm-show" aria-expanded="false"  style="padding-left:3rem">
								<li ><a id="codeudor-ft-informacion" class="no-decoration active-item" href="#" onClick="clickTab(\'codeudor-ft-informacion\');">Información</a></li>
								<li><a id="codeudor-ft-arriendos" class="no-decoration" href="#" onClick="clickTab(\'codeudor-ft-arriendos\');">Arriendos</a></li>
								<li><a id="codeudor-ft-historial" class="no-decoration" href="#" onClick="clickTab(\'codeudor-ft-historial\');">Historial</a></li>
							</ul>

						</li>';
	}
	

	
	if (stringContainsItem($urlActual, ["component=propietario&view=propietario"])) {
		$lateralMenuHTML = '';

		$lateralMenuHTML .= '
						<ul class="nav">
   				 <li><a href="index.php?component=dashboard&amp;view=dashboard" class="no-decoration"><i class="fa fa-fw fas fa-home"
                style="font-size: 1.1rem"></i> Inicio</a></li>
    			<li><a class="no-decoration active"><i style="color:#ffffff !important;" class="fas fa-door-open"></i>Cliente</a>
        <ul class="sub-menu mm-collapse mm-show" style="padding-left:3rem" aria-expanded="false">
            <li><a class="no-decoration" style="margin-left: 0"
                    href="index.php?component=persona&amp;view=persona_list">Búsqueda de Cliente</a></li>
            <li><a class="no-decoration" style="margin-left: 0"
                    href="index.php?component=persona&amp;view=persona">Creación de Cliente</a></li>
            <li><a class="no-decoration active-item" style="margin-left: 0"
                    href="index.php?component=propietario&amp;view=propietario_list">Propietarios</a></li>
            <li><a class="no-decoration" style="margin-left: 0"
                    href="index.php?component=arrendatario&amp;view=arrendatario_list">Arrendatarios</a></li>
            <li><a class="no-decoration" style="margin-left: 0"
                    href="index.php?component=codeudor&amp;view=codeudor_list">Codeudores</a></li>
        </ul>
    </li>
</ul>
						';
	}
	/*
	if (stringContainsItem($urlActual, ["component=propietario&view=propietario_facturas_generacion_masiva_list"])) {
		$lateralMenuHTML = '';
	}*/
	if (stringContainsItem($urlActual, ["component=arrendatario&view=arrendatario"])) {
		$lateralMenuHTML = '';

		$lateralMenuHTML .= '
						<ul class="nav">
   				 <li><a href="index.php?component=dashboard&amp;view=dashboard" class="no-decoration"><i class="fa fa-fw fas fa-home"
                style="font-size: 1.1rem"></i> Inicio</a></li>
    			<li><a class="no-decoration active"><i style="color:#ffffff !important;" class="fas fa-door-open"></i>Cliente</a>
        <ul class="sub-menu mm-collapse mm-show" style="padding-left:3rem" aria-expanded="false">
            <li><a class="no-decoration" style="margin-left: 0"
                    href="index.php?component=persona&amp;view=persona_list">Búsqueda de Cliente</a></li>
            <li><a class="no-decoration" style="margin-left: 0"
                    href="index.php?component=persona&amp;view=persona">Creación de Cliente</a></li>
            <li><a class="no-decoration" style="margin-left: 0"
                    href="index.php?component=propietario&amp;view=propietario_list">Propietarios</a></li>
            <li><a class="no-decoration active-item" style="margin-left: 0"
                    href="index.php?component=arrendatario&amp;view=arrendatario_list">Arrendatarios</a></li>
            <li><a class="no-decoration" style="margin-left: 0"
                    href="index.php?component=codeudor&amp;view=codeudor_list">Codeudores</a></li>
        </ul>
    </li>
</ul>';
	}
	if (stringContainsItem($urlActual, ["component=codeudor&view=codeudor"])) {
		$lateralMenuHTML = '';

		$lateralMenuHTML .= '
						<ul class="nav">
   				 <li><a href="index.php?component=dashboard&amp;view=dashboard" class="no-decoration"><i class="fa fa-fw fas fa-home"
                style="font-size: 1.1rem"></i> Inicio</a></li>
    			<li><a class="no-decoration active"><i style="color:#ffffff !important;" class="fas fa-door-open"></i>Cliente</a>
        <ul class="sub-menu mm-collapse mm-show" style="padding-left:3rem" aria-expanded="false">
            <li><a class="no-decoration" style="margin-left: 0"
                    href="index.php?component=persona&amp;view=persona_list">Búsqueda de Cliente</a></li>
            <li><a class="no-decoration" style="margin-left: 0"
                    href="index.php?component=persona&amp;view=persona">Creación de Cliente</a></li>
            <li><a class="no-decoration" style="margin-left: 0"
                    href="index.php?component=propietario&amp;view=propietario_list">Propietarios</a></li>
            <li><a class="no-decoration" style="margin-left: 0"
                    href="index.php?component=arrendatario&amp;view=arrendatario_list">Arrendatarios</a></li>
            <li><a class="no-decoration active-item" style="margin-left: 0"
                    href="index.php?component=codeudor&amp;view=codeudor_list">Codeudores</a></li>
        </ul>
    </li>
</ul>';
	}
		if (stringContainsItem($urlActual, ["view=propietario_ficha_tecnica&token="])) {
		$lateralMenuHTML = '';

		$lateralMenuHTML .= '
						<li >
							<a class="no-decoration">Ficha Técnica</a>
							<ul class="sub-menu mm-collapse mm-show" aria-expanded="false"  style="padding-left:3rem">
								<li ><a id="propietario-ft-informacion" class="no-decoration active-item"  href="#" onClick="clickTab(\'propietario-ft-informacion\');">Información</a></li>
								<li><a id="propietario-ft-cuentaCorriente" class="no-decoration" href="#" onClick="clickTab(\'propietario-ft-cuentaCorriente\');">Cuenta Corriente</a></li>
								<li><a id="propietario-ft-propiedades" class="no-decoration" href="#" onClick="clickTab(\'propietario-ft-propiedades\');">Propiedades</a></li>
								<li><a id="propietario-ft-liquidaciones" class="no-decoration" href="#" onClick="clickTab(\'propietario-ft-liquidaciones\');">Liquidaciones</a></li>
								<li><a id="propietario-ft-historial" class="no-decoration"  href="#" onClick="clickTab(\'propietario-ft-historial\');">Historial</a></li>
							</ul>

						</li>';
	}
	return $lateralMenuHTML;


	
}
