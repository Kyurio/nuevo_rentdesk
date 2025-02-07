<?php

$usuario_token  = @$_SESSION["rd_usuario_token"];
$usuario_rol    = @$_SESSION["usuario_rol"];
$company_token    = @$_SESSION["company_token"];
$config        = new Config;

$url = $config->url_web;
$url_redirect = $url . "index.php?component=dashboard&view=dashboard";
$url_actual = $_SERVER['REQUEST_URI'];

$existe = 0;
//var_dump("url_redirect ",$url_redirect ); 
$usuarioAccesos = unserialize($_SESSION["rd_current_accesos"]);
//var_dump($usuarioAccesos);
// Parsear la URL para obtener la cadena de consulta
$query_string = parse_url($url_actual, PHP_URL_QUERY);

// Parsear la cadena de consulta en un array
parse_str($query_string, $query_params);

// Buscar el parámetro deseado (en este caso, 'view')
if (isset($query_params['view'])) {
    $view = $query_params['view'];
    //  var_dump("El valor del parámetro 'view' es: $view");
}


if ($view != "dashboard") {
    foreach ($usuarioAccesos as $item) {
        //var_dump("item: ",$item->vista);
        if (@$item->vista == $view || @$item->ficha == $view || @$item->edicion == $view) {
            $existe = 1;
        } else {
            //var_dump("No existe");
        }
    }
}

if ($existe == 0 && $view != "dashboard") {
    echo "<script>window.location.href = '$url_redirect';</script>";
}



if ($component == "login") {
    include("login/login.php");
} //if( @$_GET["component"]=="login" )

if ($component == "dashboard") {
    include("components/dashboard/dashboard.php");
} //if( @$_GET["component"]=="dashboard" )

if ($component == "changepassword") {
    include("login/changepassword.php");
} //if( @$_GET["component"]=="company" )



if ($component == "cheques") {
    include("components/cheques/cheques.php");
}

if ($existe == 1) {






    // if ($component == "banco") {
    //     include("components/banco/banco.php");
    // } //if( @$_GET["component"]=="profile" )

    // if ($component == "estadoContrato") {
    //     include("components/estadoContrato/estadoContrato.php");
    // } //if( @$_GET["component"]=="profile" )

    // if ($component == "estadoLiquidacion") {
    //     include("components/estadoLiquidacion/estadoLiquidacion.php");
    // } //if( @$_GET["component"]=="profile" )

    // if ($component == "estadoPersona") {
    //     include("components/estadoPersona/estadoPersona.php");
    // } //if( @$_GET["component"]=="profile" )

    // if ($component == "estadoPropiedad") {
    //     include("components/estadoPropiedad/estadoPropiedad.php");
    // } //if( @$_GET["component"]=="profile" )

    if ($component == "tipoDocumento") {
        include("components/tipoDocumento/tipoDocumento.php");
    } //if( @$_GET["component"]=="profile" )

    // if ($component == "tipoMedioPago") {
    //     include("components/tipoMedioPago/tipoMedioPago.php");
    // } //if( @$_GET["component"]=="profile" )

    // if ($component == "tipoMenu") {
    //     include("components/tipoMenu/tipoMenu.php");
    // } //if( @$_GET["component"]=="profile" )

    // if ($component == "tipoMoneda") {
    //     include("components/tipoMoneda/tipoMoneda.php");
    // } //if( @$_GET["component"]=="profile" )

    // if ($component == "tipoMonto") {
    //     include("components/tipoMonto/tipoMonto.php");
    // } //if( @$_GET["component"]=="profile" )

    // if ($component == "tipoPersona") {
    //     include("components/tipoPersona/tipoPersona.php");
    // } //if( @$_GET["component"]=="profile" )	

    // if ($component == "tipoProducto") {
    //     include("components/tipoProducto/tipoProducto.php");
    // } //if( @$_GET["component"]=="profile" )	

    // if ($component == "tipoPropiedad") {
    //     include("components/tipoPropiedad/tipoPropiedad.php");
    // } //if( @$_GET["component"]=="profile" )	

    // if ($component == "tipoResposable") {
    //     include("components/tipoResposable/tipoResposable.php");
    // } //if( @$_GET["component"]=="profile" )

    // if ($component == "rol") {
    //     include("components/rol/rol.php");
    // } //if( @$_GET["component"]=="profile" )

    // if ($component == "user") {
    //     include("components/user/user.php");
    // } //if( @$_GET["component"]=="profile" )	

    // if ($component == "producto") {
    //     include("components/producto/producto.php");
    // } //if( @$_GET["component"]=="profile" )	

    if ($component == "propietario") {
        include("components/propietario/propietario.php");
    } //if( @$_GET["component"]=="profile" )	

    if ($component == "arrendatario") {
        include("components/arrendatario/arrendatario.php");
    } //if( @$_GET["component"]=="profile" )	

    if ($component == "arriendo") {
        include("components/arriendo/arriendo.php");
    } //if( @$_GET["component"]=="profile" )	

    if ($component == "propiedad") {
        include("components/propiedad/propiedad.php");
    } //if( @$_GET["component"]=="profile" )	

    // if ($component == "eecc") {
    //     include("components/eecc/eecc.php");
    // } //if( @$_GET["component"]=="profile" )	

    if ($component == "liquidacion") {
        include("components/liquidacion/liquidacion.php");
    } //if( @$_GET["component"]=="profile" )		

    if ($component == "contrato") {
        include("components/contrato/contrato.php");
    } //if( @$_GET["component"]=="profile" )


    if ($component == "perfil") {
        include("components/perfil/perfil.php");
    } //if( @$_GET["component"]=="perfil" )	

    if ($component == "visita") {
        include("components/visita/visita.php");
    } //if( @$_GET["component"]=="perfil" )	

    if ($component == "pago") {
        include("components/pago/pago.php");
    } //if( @$_GET["component"]=="perfil" )	

    if ($component == "estadoVisita") {
        include("components/estadoVisita/estadoVisita.php");
    } //if( @$_GET["component"]=="profile" )

    if ($component == "reporte") {
        include("components/reporte/reporte.php");
    } //if( @$_GET["component"]=="profile" )

    if ($component == "cargaMasiva") {
        include("components/cargaMasiva/cargaMasiva.php");
    } //if( @$_GET["component"]=="profile" )

    // if ($component == "pack") {
    //     include("components/pack/pack.php");
    // } //if( @$_GET["component"]=="profile" )

    if ($component == "reajuste") {
        include("components/reajuste/reajuste.php");
    } //if( @$_GET["component"]=="profile" )

    if ($component == "garantia") {
        include("components/garantia/garantia.php");
    } //if( @$_GET["component"]=="profile" )

    if ($component == "rol") {
        include("components/rol/rol.php");
    } //if( @$_GET["component"]=="profile" )

    if ($component == "derecho_aseo") {
        include("components/derechoAseo/derecho_aseo.php");
    } //if( @$_GET["component"]=="profile" )

    if ($component == "movimiento") {
        include("components/movimiento/movimiento.php");
    } //if( @$_GET["component"]=="profile" )

    if ($component == "cobro") {
        include("components/cobro/cobro.php");
    } //if( @$_GET["component"]=="profile" )

    if ($component == "facturacion") {
        include("components/facturacion/facturacion.php");
    } //if( @$_GET["component"]=="profile" )

    if ($component == "codeudor") {
        include("components/codeudor/codeudor.php");
    } //if( @$_GET["component"]=="profile" )

    if ($component == "persona") {
        include("components/persona/persona.php");
    } //if( @$_GET["component"]=="profile" )

    if ($component == "mantenedor") {
        include("components/mantenedor/mantenedor.php");
    }

    if ($component == "officesbanking") {
        require_once("components/officesbanking/officesbanking.php");
    }

    // controlador dte jhernandez
    if ($component == "dte") {
        include("components/dte/dte.php");
    }


    if ($component == "contribucion") {
        include("components/contribucion/contribuciones.php");
    }


    if ($component == "ctacontables") {
        include("components/ctacontables/ctacontables.php");
    }

    if ($component == "servipag") {
        
        include("components/servipag/servipag.php");
    }



} // Fin $existe == 1
