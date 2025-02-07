<?php


$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$token	= @$_GET["token"];
$basico = "basico";
$seguro = "seguro";
$fecha_inicio = "";
$fecha_termino_real = "";

/************Fechas**********************/
$fecha_actual = date("Y-m-d");

$fecha_final = date("Y-m-d", strtotime("+1 year", strtotime($fecha_actual)));



//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=propiedad&view=propiedad&token=$token&nav=$nav");

if (isset($nav)) {
	$nav = "index.php?" . decodifica_navegacion($nav);
} else {
	$nav = "index.php?component=propiedad&view=propiedad_list";
}

//************************************************************************************************************

/*SELECTOR - Ficha de arriendo - MANTENER PARA RENTDESK */

$num_reg = 10;
$inicio = 0;
$num_cuotas_garantia = 0;

$query = " SELECT * FROM propiedades.ficha_arriendo fa 
           where fa.token='$token'  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_ficha_arriendo = json_decode($resultado);

foreach ($json_ficha_arriendo as $item_ficha_arriendo) {

	$id_arriendo = $item_ficha_arriendo->id;

	if ($item_ficha_arriendo->fecha_inicio) {
		$fecha_inicio = $item_ficha_arriendo->fecha_inicio;
	} else {
		$fecha_inicio = $fecha_actual;
	}


	if ($item_ficha_arriendo->fecha_termino_real) {
		$fecha_termino_real = @$item_ficha_arriendo->fecha_termino_real;
	} else {
		$fecha_termino_real = $fecha_final;
	}


	if ($item_ficha_arriendo->id_moneda_precio == 3) {
		$precio = @$item_ficha_arriendo->precio;

	} else {
		$precio = number_format(@$item_ficha_arriendo->precio, 0, ',', '.');

	
	
	}


	$duracion_contrato_meses = @$item_ficha_arriendo->duracion_contrato_meses;
	$valor_monto_garantia = @$item_ficha_arriendo->monto_garantia;
	$monto_garantia = number_format(@$item_ficha_arriendo->monto_garantia, 0, ',', '.');
	$pago_garantia_propietario = @$item_ficha_arriendo->pago_garantia_propietario;
	$mesesGarantia = @$item_ficha_arriendo->meses_garantia;
	$id_contrato = @$item_ficha_arriendo->id_estado_contrato;
	$monedaContrato = @$item_ficha_arriendo->id_moneda_precio;

	$tipoMulta = @$item_ficha_arriendo->id_tipo_multa;
	$monedaMulta = @$item_ficha_arriendo->id_moneda_multa;
	$num_cuotas_garantia = @$item_ficha_arriendo->num_cuotas_garantia;
	//Comisiones
	$arriendo_comision_cobro = @$item_ficha_arriendo->arriendo_comision_cobro;
	$arriendo_comision_monto = @$item_ficha_arriendo->arriendo_comision_monto;
	$arriendo_comision_id_moneda = @$item_ficha_arriendo->arriendo_comision_id_moneda;
	$arriendo_comision_id_tipo_documento = @$item_ficha_arriendo->arriendo_comision_id_tipo_documento;

	$adm_comision_cobro = @$item_ficha_arriendo->adm_comision_cobro;
	$adm_comision_monto = @$item_ficha_arriendo->adm_comision_monto;
	$adm_comision_id_moneda = @$item_ficha_arriendo->adm_comision_id_moneda;
	$adm_comision_id_tipo_documento = @$item_ficha_arriendo->adm_comision_id_tipo_documento;
	$adm_comision_primer_liquidacion = @$item_ficha_arriendo->adm_comision_primer_liquidacion;

	//monto multa
	if ($monedaMulta  == 1) {

		$montoMultaAtraso = @$item_ficha_arriendo->monto_multa_atraso;
	} else if ($monedaMulta == 2) {
		$montoMultaAtraso = number_format(@$item_ficha_arriendo->monto_multa_atraso, 0, ',', '.');
	} else {
		$montoMultaAtraso = @$item_ficha_arriendo->monto_multa_atraso;
	}

	//Ajustes
	$tipoReajuste = @$item_ficha_arriendo->id_tipo_reajuste;

	//dias multa jhernandez
	$diascobro = @$item_ficha_arriendo->cobro_dias_multa;
}



//************************************************************************************************************


$query = " SELECT distinct cantidad_reajuste FROM propiedades.ficha_arriendo_reajustes 
           where token_ficha_arriendo ='$token'  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_cantidad_reajuste = json_decode($resultado)[0];

$cantidadReajuste = @$json_cantidad_reajuste->cantidad_reajuste;
$cantidadReajuste = @str_replace('.', ',', $cantidadReajuste);



// Array de todos los meses
$meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];


// Ejecutar la consulta para obtener los ID de los meses seleccionados
$query = "SELECT id_mes_reajuste FROM propiedades.ficha_arriendo_reajustes WHERE token_ficha_arriendo = '$token'";
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$mesesSeleccionados = json_decode($resultado, true);



$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
@$json_id_mes = json_decode($resultado);


$query = " SELECT id_mes_reajuste FROM propiedades.tp_periodicidad 
           where extension_1 = true  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
@$json_periocidad = json_decode($resultado);

$opcion_mes = "";
$selected = "";
$selected2 = "";
$selected3 = "";
$selected4 = "";
$selected5 = "";
$selected6 = "";
$selected7 = "";
$selected8 = "";
$selected9 = "";
$selected10 = "";
$selected11 = "";
$selected12 = "";




// tipo de moneda



/*Consulta Cantidad de registros*/
$query_count = "SELECT * FROM propiedades.tp_tipo_moneda WHERE habilitado = true AND id IN (3, 2)";

$data = array("consulta" => $query_count);
$resultado = json_decode($services->sendPostNoToken($url_services . '/util/objeto', $data));

// Iniciar la variable como una cadena vacía para acumular las opciones
$OpcionMonedas = "";
$OpcionMonedas1 = "";
$OpcionMonedas2 = "";
$OpcionMonedas3 = "";
$OpcionMonedas4 = "";
$OpcionMonedas5 = "";
$OpcionMonedas6 = "";
$OpcionMonedas7 = "";
$OpcionMonedas8 = "";
$OpcionMonedas9 = "";
$OpcionMonedas10 = "";
$OpcionMonedas11 = "";
$OpcionMonedas12 = "";

foreach ($resultado as $tipoMoneda) {
	$nombreMoneda = $tipoMoneda->nombre;
	$idMoneda = $tipoMoneda->id;

	// Acumular las opciones en la variable
	$OpcionMonedas .= "<option value='$idMoneda'>$nombreMoneda</option>";
}



//************************************ MESES ESPECIALES************************************************************************


$opcion_mes = $opcion_mes . "<option value='1' id='Enero' $selected >Enero</option>";
$opcion_mes = $opcion_mes . "<option value='2' id='Febrero' $selected2>Febrero</option>";
$opcion_mes = $opcion_mes . "<option value='3' id='Marzo' $selected3>Marzo</option>";
$opcion_mes = $opcion_mes . "<option value='4' id='Abril' $selected4>Abril</option>";
$opcion_mes = $opcion_mes . "<option value='5' id='Mayo' $selected5>Mayo</option>";
$opcion_mes = $opcion_mes . "<option value='6' id='Junio' $selected6>Junio</option>";
$opcion_mes = $opcion_mes . "<option value='7' id='Julio' $selected7>Julio</option>";
$opcion_mes = $opcion_mes . "<option value='8' id='Agosto' $selected8>Agosto</option>";
$opcion_mes = $opcion_mes . "<option value='9' id='Septiembre' $selected9>Septiembre</option>";
$opcion_mes = $opcion_mes . "<option value='10' id='Octubre' $selected10>Octubre</option>";
$opcion_mes = $opcion_mes . "<option value='11' id='Noviembre' $selected11>Noviembre</option>";
$opcion_mes = $opcion_mes . "<option value='12' id='Diciembre' $selected12>Diciembre</option>";

$opcion_mes = "<select class='form-control js-example-responsive' name='meses[]' id='meses' multiple='multiple'>
$opcion_mes
</select>";

/**
 * 
 * 
 * 
 *  nueva version
 * 
 * 
 * 
 * 
 */

// Consulta las monedas habilitadas
$query_count = "SELECT * FROM propiedades.tp_tipo_moneda WHERE habilitado = true and id in(2, 3)";
$data = array("consulta" => $query_count);
$resultadoMonedas = json_decode($services->sendPostNoToken($url_services . '/util/objeto', $data));

// Almacenar las monedas disponibles en un array
$monedas = [];
foreach ($resultadoMonedas as $tipoMoneda) {
	$monedas[$tipoMoneda->id] = $tipoMoneda->nombre; // id_moneda => nombre_moneda
}

// Consulta los ajustes de los meses
$query = "SELECT * FROM propiedades.ficha_arriendo_reajustes_fijacion_mes WHERE id_arriendo = $id_arriendo";
$data = array("consulta" => $query);
$json_mes_ajustes = json_decode($services->sendPostNoToken($url_services . '/util/objeto', $data));


// Inicializar los meses
$meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
$selectsMeses = [];

// Recorremos los meses y generamos los campos de forma dinámica
for ($idMes = 1; $idMes <= 12; $idMes++) {
	$mesNombre = $meses[$idMes - 1]; // Obtener el nombre del mes
	$monedaSeleccionada = null;
	$costo = ""; // Valor predeterminado del costo
	$periocidad = null; // Valor predeterminado de periodicidad

	// Verificar si ya hay datos para este mes en ficha_arriendo_reajustes_fijacion_mes
	if (!empty($json_mes_ajustes)) {
		foreach ($json_mes_ajustes as $item) {
			// Cambiar sintaxis de array a objeto
			if ($item->id_mes == $idMes) {
				$monedaSeleccionada = $item->id_moneda;

				if ($monedaSeleccionada == 2) { // 2 es peso
					$costo = number_format($item->monto, 0, '', '.');
				} else if ($monedaSeleccionada == 3) {

					$costo = $item->monto;
				} else {
					$costo = $item->monto;
				}

				$periocidad = $item->id_periodicidad; // Asignar la periodicidad
				break; // Salimos del bucle si encontramos el dato
			}
		}
	}

	// Generar las opciones del select de monedas
	$OpcionMonedas = "";
	foreach ($monedas as $idMoneda => $nombreMoneda) {
		$selected = ($monedaSeleccionada == $idMoneda) ? "selected" : "";
		$OpcionMonedas .= "<option value='$idMoneda' $selected>$nombreMoneda</option>";
	}

	// Asegurarse de que siempre se muestren las opciones de periodicidad, incluso si no hay datos
	$selected_periocidad_una_vez = ($periocidad == 1) ? "selected" : "";
	$selected_periocidad_siempre = ($periocidad == 2) ? "selected" : "";

	$opcionesPeriocidad = "
        <select class='form-control' name='OpcionAplicar{$mesNombre}'>
            <option value='1' $selected_periocidad_una_vez>Una vez</option>
            <option value='2' $selected_periocidad_siempre>Siempre</option>
        </select>";

	// Generar el bloque de HTML para cada mes (monto, moneda, aplicar)
	$selectsMeses[$idMes] = "
    <div class='col-md'>
        <div class='form-group'>
            <label for='monto{$mesNombre}'>$mesNombre</label>
            <input type='text' class='form-control' name='monto{$mesNombre}' id='monto{$mesNombre}' value='{$costo}' placeholder='Monto'>
            <label>Tipo moneda</label>
            <select class='form-control' name='diasPagoTipoMoneda{$mesNombre}' id='diasPagoTipoMoneda{$mesNombre}'>
                $OpcionMonedas
            </select>
            <label for='OpcionAplicar{$mesNombre}'>Aplicar</label>
            $opcionesPeriocidad
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Función para aplicar el formato adecuado basado en la moneda seleccionada
            function aplicarFormatoMoneda() {
                var tipo_moneda = $('#diasPagoTipoMoneda{$mesNombre}').val();
                
                if (tipo_moneda == '2') { // Moneda 'Pesos' (id == 2)
                    $('#monto{$mesNombre}').on('input', function() {
                        var value = $(this).val().replace(/[^\d]/g, ''); // Solo números
                        var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Formato de miles
                        $(this).val(formattedValue);
                    });
                } else {
                    $('#monto{$mesNombre}').on('input', function() {
                        var value = $(this).val().replace(/[^0-9.,]/g, ''); // Solo permitir números, comas y puntos
                        var parts = value.split(/[.,]/); // Separar parte entera y decimal

                        if (parts.length > 2) {
                            value = parts[0] + '.' + parts[1]; // Mantener solo la parte antes y después del primer separador decimal
                        }
                        $(this).val(value);
                    });
                }
            }

            // Ejecutar la función cuando se cargue la página
            aplicarFormatoMoneda();

            // Cambiar el formato automáticamente cuando cambie el tipo de moneda
            $('#diasPagoTipoMoneda{$mesNombre}').on('change', function() {
                // Desactivar cualquier event listener previo
                $('#monto{$mesNombre}').off('input');

                // Reaplicar el formato al cambiar la moneda
                aplicarFormatoMoneda();
            });

            // Evitar que las teclas de navegación alteren el formato
            $('#monto{$mesNombre}').on('keydown', function(event) {
                if (event.which >= 37 && event.which <= 40) {
                    event.preventDefault();
                }
            });
        });
    </script>";
}



/**
 * 
 * 
 * 
 * 
 * 		 nueva version
 * 
 * 
 * 
 * 
 * 
 * 
 */




// Meses especiales periocidad
// esto vamos a cambiar !! 

$opcion_periocidad1  = $opcion_periocidad1  . "<option value='1' id='1' $selected_periocidad1_una_vez >Una vez</option>";
$opcion_periocidad1  = $opcion_periocidad1  . "<option value='9' id='2' $selected_periocidad1_siempre >Siempre</option>";

$opcion_periocidad2  = $opcion_periocidad2  . "<option value='1' id='1' $selected_periocidad2_una_vez >Una vez</option>";
$opcion_periocidad2  = $opcion_periocidad2  . "<option value='9' id='2' $selected_periocidad2_siempre >Siempre</option>";

$opcion_periocidad3  = $opcion_periocidad3  . "<option value='1' id='1' $selected_periocidad3_una_vez >Una vez</option>";
$opcion_periocidad3  = $opcion_periocidad3  . "<option value='9' id='2' $selected_periocidad3_siempre >Siempre</option>";

$opcion_periocidad4  = $opcion_periocidad4  . "<option value='1' id='1' $selected_periocidad4_una_vez >Una vez</option>";
$opcion_periocidad4  = $opcion_periocidad4  . "<option value='9' id='2' $selected_periocidad4_siempre >Siempre</option>";

$opcion_periocidad5  = $opcion_periocidad5  . "<option value='1' id='1' $selected_periocidad5_una_vez >Una vez</option>";
$opcion_periocidad5  = $opcion_periocidad5  . "<option value='9' id='2' $selected_periocidad5_siempre >Siempre</option>";

$opcion_periocidad6  = $opcion_periocidad6  . "<option value='1' id='1' $selected_periocidad6_una_vez >Una vez</option>";
$opcion_periocidad6  = $opcion_periocidad6  . "<option value='9' id='2' $selected_periocidad6_siempre >Siempre</option>";

$opcion_periocidad7  = $opcion_periocidad7  . "<option value='1' id='1' $selected_periocidad7_una_vez >Una vez</option>";
$opcion_periocidad7  = $opcion_periocidad7  . "<option value='9' id='2' $selected_periocidad7_siempre >Siempre</option>";

$opcion_periocidad8  = $opcion_periocidad8  . "<option value='1' id='1' $selected_periocidad8_una_vez >Una vez</option>";
$opcion_periocidad8  = $opcion_periocidad8  . "<option value='9' id='2' $selected_periocidad8_siempre >Siempre</option>";

$opcion_periocidad9  = $opcion_periocidad9  . "<option value='1' id='1' $selected_periocidad9_una_vez >Una vez</option>";
$opcion_periocidad9  = $opcion_periocidad9  . "<option value='9' id='2' $selected_periocidad9_siempre >Siempre</option>";

$opcion_periocidad10  = $opcion_periocidad10  . "<option value='1' id='1' $selected_periocidad10_una_vez >Una vez</option>";
$opcion_periocidad10  = $opcion_periocidad10  . "<option value='9' id='2' $selected_periocidad10_siempre >Siempre</option>";

$opcion_periocidad11  = $opcion_periocidad11  . "<option value='1' id='1' $selected_periocidad11_una_vez >Una vez</option>";
$opcion_periocidad11  = $opcion_periocidad11  . "<option value='9' id='2' $selected_periocidad11_siempre >Siempre</option>";

$opcion_periocidad12  = $opcion_periocidad12  . "<option value='1' id='1' $selected_periocidad12_una_vez >Una vez</option>";
$opcion_periocidad12  = $opcion_periocidad12  . "<option value='9' id='2' $selected_periocidad12_siempre >Siempre</option>";

$opcion_periocidad1 = "<select name='OpcionAplicarEnero' id='OpcionAplicarEnero' class='form-control'>
$opcion_periocidad1
</select>";

$opcion_periocidad2 = "<select name='OpcionAplicarFebrero' id='OpcionAplicarFebrero' class='form-control'>
$opcion_periocidad2
</select>";

$opcion_periocidad3 = "<select name='OpcionAplicarMarzo' id='OpcionAplicarMarzo' class='form-control'>
$opcion_periocidad3
</select>";

$opcion_periocidad4 = "<select name='OpcionAplicarAbril' id='OpcionAplicarAbril' class='form-control'>
$opcion_periocidad4
</select>";

$opcion_periocidad5 = "<select name='OpcionAplicarMayo' id='OpcionAplicarMayo' class='form-control'>
$opcion_periocidad5
</select>";

$opcion_periocidad6 = "<select name='OpcionAplicarJunio' id='OpcionAplicarJunio' class='form-control'>
$opcion_periocidad6
</select>";

$opcion_periocidad7 = "<select name='OpcionAplicarJulio' id='OpcionAplicarJulio' class='form-control'>
$opcion_periocidad7
</select>";

$opcion_periocidad8 = "<select name='OpcionAplicarAgosto' id='OpcionAplicarAgosto' class='form-control'>
$opcion_periocidad8
</select>";

$opcion_periocidad9 = "<select name='OpcionAplicarSeptiembre' id='OpcionAplicarSeptiembre' class='form-control'>
$opcion_periocidad9
</select>";

$opcion_periocidad10 = "<select name='OpcionAplicarOctubre' id='OpcionAplicarOctubre' class='form-control'>
$opcion_periocidad10
</select>";

$opcion_periocidad11 = "<select name='OpcionAplicarNoviembre' id='OpcionAplicarNoviembre' class='form-control'>
$opcion_periocidad11
</select>";

$opcion_periocidad12 = "<select name='OpcionAplicarDiciembre' id='OpcionAplicarDiciembre' class='form-control'>
$opcion_periocidad12
</select>";





//************************************************************************************************************

/*SELECTOR - ARRENDATARIO - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = "SELECT va.nombre_1 ,va.token_arrendatario, va.nombre_1||' '||va.nombre_2 ||' '|| va.nombre_3 as nombre, va.correo_electronico, va.telefono_movil, id_tipo_persona 
 FROM propiedades.ficha_arriendo fa ,propiedades.ficha_arriendo_arrendadores faa  , propiedades.vis_arrendatarios va 
           where fa.token='$token' and fa.id = faa.id_ficha_arriendo  and va.id = faa.id_arrendatario  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$jsonArrendatarios = json_decode($resultado);
$json_arrendatario = json_decode($resultado)[0];


$cantidadArrendatario = count($jsonArrendatarios);


$nombre_arrendatario = $json_arrendatario->nombre;
$tipo_persona = $json_arrendatario->id_tipo_persona;
$correo_electronico_arrendatario = @$json_arrendatario->correo_electronico ?? "-";
$telefono_arrendatario = @$json_arrendatario->telefono_movil ?? "-";
$token_arrendatario = @$json_arrendatario->token_arrendatario ?? "-";

$opcion_arrendatario = null;



//var_dump("Arrendatario", $resultado);

//$opcion_arrendatario = "<option value=''>Seleccione</option>";


foreach ($json as $item) {
	$select_arrendatario = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);

	if ($item->token_arrendatario != null) {
		$select_arrendatario = " Seleccione ";
	}
	$opcion_arrendatario = $opcion_arrendatario . "<option selected=selected' value='$item->token_arrendatario' data-select2-id='$item->token_arrendatario' $select_arrendatario >$item->nombre_1 </option>";
}

//$opcion_arrendatario = "<select id='arrendatario' name='arrendatario[]' class='form-control js-example-responsive' data-select2-id='arrendatarios'  multiple='multiple' required > Descomentar cuando la api reciva un arreglo
$opcion_arrendatario = "<select id='arrendatario' name='arrendatario' class='form-control js-example-responsive' data-select2-id='arrendatarios'  multiple='multiple' required disabled>
$opcion_arrendatario
</select>";


//************************************************************************************************************

/*SELECTOR - CODEUDOR - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = " SELECT vc.nombre_1 ,vc.token_codeudor, vc.nombre_1||' '||vc.nombre_2 as nombre, vc.correo_electronico, vc.telefono_movil  FROM propiedades.ficha_arriendo fa ,propiedades.ficha_arriendo_codeudores fac , propiedades.vis_codeudores vc 
           where fa.token='$token' and fa.id = fac.id_ficha_arriendo and vc.id = fac.id_codeudor  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
$json_codeudor = json_decode($resultado)[0];

//var_dump("Arrendatario", $resultado);

$opcion_codeudor = "";
$nombre_codeudor = @$json_codeudor->nombre ?? "-";
$correo_electronico_codeudor = @$json_codeudor->correo_electronico ?? "-";
$telefono_codeudor = @$json_codeudor->telefono_movil ?? "-";
$token_codeudor = @$json_codeudor->token_codeudor ?? "-";

if ($json_codeudor) {
	foreach ($json as $item) {
		$select_codeudor = "";

		// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);

		if ($item->token_codeudor != null) {
			$select_codeudor = " Seleccione ";
		}
		$opcion_codeudor = $opcion_codeudor . "<option value='$item->token_codeudor' data-select2-id='$item->token_codeudor' $select_codeudor >$item->nombre_1</option>";
	}

	$opcion_codeudor = "<select id='codeudor' name='codeudor' class='form-control js-example-responsive' data-select2-id='codeudor' required disabled>
$opcion_codeudor
</select>";
}
//************************************************************************************************************


/*SELECTOR - propiedad - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = " SELECT p.id, p.direccion ,p.token,p.numero,p.id_comuna FROM propiedades.ficha_arriendo fa , propiedades.propiedad p  where fa.token = '$token' and fa.id_propiedad  = p.id   ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
$json_propiedad = json_decode($resultado)[0];
//var_dump("Arrendatario", $resultado);

$direccion_propiedad = $json_propiedad->direccion;
$numero_propiedad = $json_propiedad->numero;
$id_comuna_propiedad = $json_propiedad->id_comuna;
$token_propiedad = $json_propiedad->token;
$id_propiedad = $json_propiedad->id;
$opcion_propiedad = "";

foreach ($json as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);

	if ($item->token != null) {
		$select_propiedad = " Seleccione ";
	}
	$opcion_propiedad = $opcion_propiedad . "<option selected='selected value='$item->token' data-select2-id='$item->token' $select_propiedad >$item->direccion</option>";
}

$opcion_propiedad = "<select id='propiedad' name='propiedad' class='form-control js-example-responsive' data-select2-id='propiedad' required disabled>
$opcion_propiedad
</select>";

/*SELECTOR - propiedad - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = " select tr.nombre as nombre_region,tc.nombre as nombre_comuna from propiedades.tp_comuna tc , propiedades.tp_region tr  where tc.id =  $id_comuna_propiedad  and tr.id = tc.id_region   ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_region_comuna = json_decode($resultado)[0];
//var_dump("Arrendatario", $resultado);

$region_propiedad = $json_region_comuna->nombre_region;
$comuna_propiedad = $json_region_comuna->nombre_comuna;

//**********************************************************************************************************************

/*SELECTOR - Estado contrato - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;


// selec que valida el estado de contrato segun en que estado se encuentre el contrato


$query = "SELECT tca.nombre, tca.id FROM  propiedades.tp_contrato_arriendo tca where habilitado = true and id in (1,2,5)";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
//var_dump("Arrendatario", $resultado);

$estado_contrato = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_contrato = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);

	if ($item->id == $id_contrato) {
		$estado_contrato = $estado_contrato . "<option value='$item->id'  Selected >$item->nombre</option>";
	} else {
		$estado_contrato = $estado_contrato . "<option  value='$item->id'  >$item->nombre</option>";
	}
}

$estado_contrato = "<select id='estadoContrato' name='estadoContrato' class='form-control' data-select2-id='estadoContrato'  onchange='ConfiramarFinalizarArriendo(this.value)' required>
$estado_contrato
</select>";

//********************************************************************************************************


/*SELECTOR - Tipo documento - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

/// Consulta optimizada: Ordenar por nombre para que la Boleta (tipo 3) salga primero si es persona natural
$query = "SELECT nombre, id FROM propiedades.tp_tipo_documento 
WHERE habilitado = true AND id IN (1, 3) 
ORDER BY CASE WHEN $tipo_persona = 1 AND id = 3 THEN 0 ELSE 1 END, nombre";

$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array(
"consulta" => $query,
"cantRegistros" => $cant_rows,
"numPagina" => $num_pagina
);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_documentos = json_decode($resultado);

// Inicializar opciones de los selects
$opcion_documento_adm = generarOpcionesSelect($json_documentos, $adm_comision_id_tipo_documento, $tipo_persona);
$opcion_documento_arriendo = generarOpcionesSelect($json_documentos, $arriendo_comision_id_tipo_documento, $tipo_persona);

// Renderizar los selects
$opcion_documento_adm = "
<select id='tipoFacturaComisionAdministracion' 
name='tipoFacturaComisionAdministracion' 
class='form-control' 
data-select2-id='tipoFacturaComisionAdministracion' 
style='display: none' 
required>
$opcion_documento_adm
</select>";

$opcion_documento_arriendo = "
<select id='tipoFacturaComisionArriendo' 
name='tipoFacturaComisionArriendo' 
class='form-control' 
data-select2-id='tipoFacturaComisionArriendo' 
required>
$opcion_documento_arriendo
</select>";

// Función para generar opciones del select
function generarOpcionesSelect($documentos, $id_tipo_seleccionado, $tipo_persona) {
$opciones = "";

foreach ($documentos as $doc) {
// Determinar si el documento debe estar seleccionado
$selected = "";
if ($doc->id == $id_tipo_seleccionado) {
  $selected = "selected";
} elseif ($tipo_persona == 1 && $doc->id == 3) {
  // Persona natural: Seleccionar Boleta (id = 3)
  $selected = "selected";
} elseif ($tipo_persona == 2 && $doc->id == 1) {
  // Persona jurídica: Seleccionar Factura (id = 1)
  $selected = "selected";
}

// Generar opción
$opciones .= "<option value='$doc->id' $selected>$doc->nombre</option>";
}

return $opciones;
}






//***************************** Llenado Servicio y Seguro ************************************************

/*SELECTOR - seguro - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = " SELECT nombre,id FROM propiedades.tp_tipo_servicio  where tipo_servicio = 'seguro'  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
//var_dump("Arrendatario", $resultado);

$opcion_seguro = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_propiedad = "";

	$opcion_seguro = $opcion_seguro . "<option  id='$item->id' value='$item->id'  >$item->nombre</option>";
}

$opcion_seguro = "<select id='TipoServicioSeguro' name='TipoServicioSeguro' onClick='buscaProveedor(\"$seguro\")' class='form-control ' data-select2-id='TipoServicioSeguro' >
$opcion_seguro
</select>";

/*******************************************************************************************************************************************************

/*SELECTOR - Tipo moneda Precio - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = " SELECT * FROM propiedades.tp_tipo_moneda  where id_pais = 1 and habilitado = true and extension_1 = true and id in (2, 3) ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
$json_comision_arriendo = json_decode($resultado);
$json_comision_adm = json_decode($resultado);

//var_dump("Arrendatario", $resultado);



$opcion_tipo_moneda_precio = "<option value=''>Seleccione</option>";
$opcion_tipo_moneda_comision_arriendo = "<option value=''>Seleccione</option>";
$opcion_tipo_moneda_comision_adm = "<option value=''>Seleccione</option>";


foreach ($json as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
	if ($item->id == $monedaContrato) {
		$opcion_tipo_moneda_precio = $opcion_tipo_moneda_precio . "<option selected='selected value='$item->id' data-select2-id='$item->id' Selected >$item->nombre</option>";
	} else {
		// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
		$opcion_tipo_moneda_precio = $opcion_tipo_moneda_precio . "<option  id='$item->id' value='$item->id'  >$item->nombre</option>";
	}
}




$num_reg = 10;
$inicio = 0;

$query = " SELECT nombre,id FROM propiedades.tp_tipo_moneda  where id_pais = 1 and habilitado = true  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_comision_arriendo = json_decode($resultado);
$json_comision_adm = json_decode($resultado);




foreach ($json_comision_arriendo as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
	//var_dump("arriendo_comision_id_moneda", $arriendo_comision_id_moneda);
	if ($item->id == $arriendo_comision_id_moneda) {


		$opcion_tipo_moneda_comision_arriendo = $opcion_tipo_moneda_comision_arriendo . "<option value='$item->id' Selected >$item->nombre</option>";
	} else {
		// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
		$opcion_tipo_moneda_comision_arriendo = $opcion_tipo_moneda_comision_arriendo . "<option  id='$item->id' value='$item->id'  >$item->nombre</option>";
	}
}

foreach ($json_comision_adm as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
	if ($item->id == $adm_comision_id_moneda) {
		$opcion_tipo_moneda_comision_adm = $opcion_tipo_moneda_comision_adm . "<option id='$item->id' value='$item->id' Selected >$item->nombre</option>";
	} else {
		// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
		$opcion_tipo_moneda_comision_adm = $opcion_tipo_moneda_comision_adm . "<option  id='$item->id' value='$item->id'  >$item->nombre</option>";
	}
}



$opcion_tipo_moneda_precio = "<select name='monedaContrato' id='monedaContrato' class='form-control' onchange='LimpiarValorTipoMoneda()' required>
$opcion_tipo_moneda_precio
</select>";

$opcion_tipo_moneda_comision_arriendo = "<select name='monedaComisionArriendo' id='monedaComisionArriendo' class='form-control'  onchange='VolverAceroArriendo()' required>
$opcion_tipo_moneda_comision_arriendo
</select>";

$opcion_tipo_moneda_comision_adm = "<select name='monedaComisionAdministracion'  id='monedaComisionAdministracion'  onchange='VolverAceroCorretaje()'  class='form-control' required >
$opcion_tipo_moneda_comision_adm
</select>";


/*******************************************************************************************************************************************************

/*SELECTOR - Tipo moneda Multa - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = " SELECT nombre,id FROM propiedades.tp_tipo_moneda  where id_pais = 1 and habilitado = true and extension_1 = true  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
//var_dump("json", $json);

$opcion_tipo_moneda_multa = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
	if ($item->id == $monedaMulta) {
		$opcion_tipo_moneda_multa = $opcion_tipo_moneda_multa . "<option  value='$item->id' Selected >$item->nombre</option>";
	} else {
		// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
		$opcion_tipo_moneda_multa = $opcion_tipo_moneda_multa . "<option  id='$item->id' value='$item->id'  >$item->nombre</option>";
	}
}

$opcion_tipo_moneda_multa = "<select name='monedaMulta' id='monedaMulta' class='form-control'>
$opcion_tipo_moneda_multa
</select>";


/*************************************************************************************************************************************************
/*******************************************************************************************************************************************************

/*SELECTOR - adm comision primer liquidacion jhernandez*/

$num_reg = 10;
$inicio = 0;

$query = "SELECT adm_comision_primer_liquidacion, case  when adm_comision_primer_liquidacion = true then '1' else '2' end AS id
	FROM propiedades.ficha_arriendo
	where token = '$token'  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
var_dump("json", $json);

$facturarComisionAdministracion = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);

	if ($item->id == '1') {
		$facturarComisionAdministracion = $facturarComisionAdministracion . "<option  value='SI' id='1' Selected>SI</option>";
		$facturarComisionAdministracion = $facturarComisionAdministracion . "<option  value='NO' id='2' >NO</option>";
	} else if(($item->id == '2')) {
		// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
		$facturarComisionAdministracion = $facturarComisionAdministracion . "<option  value='NO' id='2' Selected>NO</option>";
		$facturarComisionAdministracion = $facturarComisionAdministracion . "<option  value='SI' id='1' >SI</option>";
	} else{
		$facturarComisionAdministracion = $facturarComisionAdministracion . "<option  value='NO' id='2'>NO</option>";
		$facturarComisionAdministracion = $facturarComisionAdministracion . "<option  value='SI' id='1'>SI</option>";
	}
}	

$facturarComisionAdministracion = "<select name='facturarComisionAdministracion' id='facturarComisionAdministracion' class='form-control'>
$facturarComisionAdministracion
</select>";


/*************************************************************************************************************************************************


/*SELECTOR - Tipo Ajuste - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = " SELECT nombre,id FROM propiedades.tp_tipo_reajuste  where habilitado = true  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
//var_dump("Arrendatario", $resultado);

$opcion_tipo_ajuste = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
	if ($item->id == $tipoReajuste) {
		$opcion_tipo_ajuste = $opcion_tipo_ajuste . "<option selected='selected value='$item->nombre' data-select2-id='$item->id' Selected >$item->nombre</option>";
	} else {
		// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
		$opcion_tipo_ajuste = $opcion_tipo_ajuste . "<option  id='$item->id' value='$item->nombre'  >$item->nombre</option>";
	}
}

$opcion_tipo_ajuste = "<select name='tipoReajuste' id='tipoReajuste' class='form-control' onChange='limpiarReajustes()'>
$opcion_tipo_ajuste
</select>";


/*************************************************************************************************************************************************
/*******************************************************************************************************************************************************

/*SELECTOR - Tipo  Multa - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = " SELECT nombre,id FROM propiedades.tp_tipo_multa  where  habilitado = true  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
//var_dump("Arrendatario", $resultado);

$opcion_tipo_multa = "<option value=''>Seleccione</option>";
//$opcion_tipo_multa = "";

foreach ($json as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
	if ($item->id == $tipoMulta) {
		$opcion_tipo_multa = $opcion_tipo_multa . "<option value='$item->id' Selected >$item->nombre</option>";
	} else {
		// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
		$opcion_tipo_multa = $opcion_tipo_multa . "<option  id='$item->id' value='$item->id'  >$item->nombre</option>";
	}
}

$opcion_tipo_multa = "<select name='tipoMulta' id='tipoMulta' class='form-control'  onchange='ValidarTipoMulta()'>
$opcion_tipo_multa
</select>";


/*************************************************************************************************************************************************

/*SELECTOR - servicios basicos - MANTENER PARA RENTDESK */
$num_reg = 40;
$inicio = 0;

$query = " SELECT nombre,id FROM propiedades.tp_tipo_servicio  where tipo_servicio = 'basico'  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
//var_dump("Arrendatario", $resultado);

$opcion_servicio_basico = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);

	$opcion_servicio_basico = $opcion_servicio_basico . "<option  id='$item->id' value='$item->id' $select_propiedad  >$item->nombre</option>";
}

$opcion_servicio_basico = "<select id='TipoServicio' name='TipoServicio' class='form-control ' onClick='buscaProveedor(\"$basico\")' data-select2-id='TipoServicio' onChange='habilitarFormSegunTipoDoc()' >
$opcion_servicio_basico
</select>";







//*******************************************  DATOS FORMULARIO    *****************************************************************

// FECHA_INICIO

//$fecha_inicio


//************************************************************************************************************

$opcion_tipo_propiedad = "<option value=''>Seleccione</option>";

$opcion_tipo_propiedad = "<select id='tipo_propiedad' name='tipo_propiedad' class='form-control' required >
$opcion_tipo_propiedad
</select>";

//************************************************************************************************************

$opcion_terreno = "<option selected value='N'>No</option>";

if (@$result->terreno == "S") {
	$opcion_terreno = $opcion_terreno . "<option selected value='S'>Si</option>";
} else {
	$opcion_terreno = $opcion_terreno . "<option value='S'>Si</option>";
}

//************************************************************************************************************

$opcion_edificado = "<option selected value='N'>No</option>";

if (@$result->edificado == "S") {
	$opcion_edificado = $opcion_edificado . "<option selected value='S'>Si</option>";
} else {
	$opcion_edificado = $opcion_edificado . "<option value='S'>Si</option>";
}


//************************************************************************************************************

$opcion_tipo_moneda = "<option value=''>Seleccione</option>";
// $data_tipo_moneda = array("idEmpresa" => $id_company);
// $resp_tipo_moneda = $services->sendPostNoToken($url_services . '/tipoMoneda/listaByEmpresa', $data_tipo_moneda);
// $tipo_monedas = json_decode($resp_tipo_moneda);

// foreach ($tipo_monedas as $tipo_moneda_r) {

// 	$select_tipo_moneda = "";
// 	if (@$result->id_moneda == @$tipo_moneda_r->idTipoMoneda)
// 		$select_tipo_moneda = " selected ";


// 	$opcion_tipo_moneda = $opcion_tipo_moneda . "<option value='$tipo_moneda_r->idTipoMoneda' $select_tipo_moneda >$tipo_moneda_r->descripcion</option>";
// } //foreach($roles as $rol)

$opcion_tipo_moneda = "<select id='tipo_moneda' name='tipo_moneda' class='form-control' required >
$opcion_tipo_moneda
</select>";


//************************************************************************************************************
$disabled_estado = "";
if (!$token == "") {
	/*Verifica si tiene el permiso para editar el estado*/
	$query_count = "select 1 
					from arpis.usuario u,
						 arpis.menu_rol mr,
						 arpis.menu m
					where u.id_usuario = $id_usuario
					and mr.id_rol = u.id_rol
					and m.id_menu = mr.id_menu
					and m.ref_externa = 'PROPIEDAD' ";

	$data = array("consulta" => $query_count);
	$resultado = $services->sendPostNoToken($url_services . '/util/count', $data);
	$cantidad_registros = $resultado;

	if (!$cantidad_registros) {
		$disabled_estado = "disabled";
	} else {
		if ($cantidad_registros > 0) {
			$disabled_estado = "";
		} else {
			$disabled_estado = "disabled";
		}
	}
}


$opcion_estado_propiedad = "<option value=''>Seleccione</option>";
$opcion_estado_propiedad = "<select id='estado_propiedad' name='estado_propiedad' $disabled_estado class='form-control' required >
$opcion_estado_propiedad
</select>";

//************************************************************************************************************

$opcion_piscina = "<option selected value='N'>No</option>";

if (@$result->piscina == "S") {
	$opcion_piscina = $opcion_piscina . "<option selected value='S'>Si</option>";
} else {
	$opcion_piscina = $opcion_piscina . "<option value='S'>Si</option>";
}




//************************************************************************************************************

$opcion_amoblado = "<option selected value='N'>No</option>";

if (@$result->amoblado == "S") {
	$opcion_amoblado = $opcion_amoblado . "<option selected value='S'>Si</option>";
} else {
	$opcion_amoblado = $opcion_amoblado . "<option value='S'>Si</option>";
}

//************************************************************************************************************

$opcion_dfl2 = "<option selected value='N'>No</option>";

if (@$result->dfl2 == "S") {
	$opcion_dfl2 = $opcion_dfl2 . "<option selected value='S'>Si</option>";
} else {
	$opcion_dfl2 = $opcion_dfl2 . "<option value='S'>Si</option>";
}

//************************************************************************************************************

$opcion_amoblado = "<option selected value='N'>No</option>";

if (@$result->amoblado == "S") {
	$opcion_amoblado = $opcion_amoblado . "<option selected value='S'>Si</option>";
} else {
	$opcion_amoblado = $opcion_amoblado . "<option value='S'>Si</option>";
}


//************************************************************************************************************

$opcion_banco = "<option value=''>Seleccione</option>";
// $data_banco = array("idEmpresa" => $id_company);
// $resp_banco = $services->sendPostNoToken($url_services . '/banco/listaByEmpresa', $data_banco);
// $bancos = json_decode($resp_banco);

// foreach ($bancos as $banco_r) {

// 	$select_banco = "";
// 	if (@$result->id_banco == @$banco_r->idBanco)
// 		$select_banco = " selected ";


// 	$opcion_banco = $opcion_banco . "<option value='$banco_r->idBanco' $select_banco >$banco_r->descripcion</option>";
// } //foreach($roles as $rol)

$opcion_banco = "<select id='banco' name='banco' class='form-control' required >
$opcion_banco
</select>";

//************************************************************************************************************
$participacion_total = 0;
$lista_propietarios = "";



$lista_propietarios = "
 <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" class='tabla-propiedad'>
  <tbody>
	<tr class='tp-titulo' >
	  <td height='28'><strong>Tipo. Documento</strong></td>	
	  <td height='28'><strong>Nro. Documento</strong></td>	
	  <td height='28'><strong>Nombre</strong></td>
	  <td height='28'><strong>Ap. Paterno</strong></td>
	  <td height='28'><strong>Ap. Materno</strong></td>
	  <td height='28'><strong>% Participación</strong></td>
	  <td height='28'><strong>Ver</strong></td>
	  <td height='28'><strong>Eliminar</strong></td>
	</tr>
	$lista_propietarios
  </tbody>
</table>
<br>
";


//************************************************************************************************************
$tiene_check_in = "N";
$lista_check_in = "";



$lista_check_in = "
 <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" class='tabla-propiedad'>
  <tbody>
	<tr class='tp-titulo' >
	  <td height='28'><strong>Fecha</strong></td>	
	  <td height='28'><strong>Arrendatario recibe</strong></td>	
	  <td height='28'><strong>Rut</strong></td>
	  <td height='28'><strong>Email Contacto</strong></td>
	  <td height='28'><strong>Ver</strong></td>
	  <td height='28'><strong>Eliminar</strong></td>
	</tr>
	$lista_check_in
  </tbody>
</table>
<br>
";


//************************************************************************************************************

$opcion_sucursal = "<option value=''>Seleccione</option>";

$opcion_sucursal = "<select id='sucursal' name='sucursal' class='form-control' >
$opcion_sucursal
</select>";

$comuna = @$result->id_comuna;
$region = @$result->id_region;
$pais = @$result->id_pais;
$existe_archivo = "N";

if (@$result->mandato != "") {
	@$archivo = "<a href='javascript: borrarArchivo(\"$result->token\");'><i class='far fa-trash-alt'></i></a> <a href='upload/mandato/$result->mandato' target='_blank'> Ver Archivo <i class='fas fa-file'></i></a>";
	$existe_archivo = "S";
}

//************************************************************************************************************

$opcion_destino = "<option value=''>Seleccione</option>";
$select_destino_arriendo = "";
if (@$result->destino_arriendo == "HAB") {
	$select_destino_arriendo = " selected ";
}
$opcion_destino = $opcion_destino . "<option value='HAB' $select_destino_arriendo >Habitacional</option>";
$select_destino_arriendo = "";
if (@$result->destino_arriendo == "COM") {
	$select_destino_arriendo = " selected ";
}
$opcion_destino = $opcion_destino . "<option value='COM' $select_destino_arriendo >Comercial</option>";

$opcion_destino = "<select id='destino_arriendo' name='destino_arriendo' class='form-control' required data-validation-required >
$opcion_destino
</select>";


////////////////////////Consulta para saber si hay garantias pagadas

$queryIDFicha = "select id from propiedades.ficha_arriendo fa  where token = '$token'";

$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryIDFicha, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultadoFichaID = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objIDFicha = json_decode($resultadoFichaID);
$objetoIDFicha = $objIDFicha[0];
$idFichaArriendo = $objetoIDFicha->id;


$queryEstadosArriendo = "select count(id_ficha_arriendo) as cantidad_estados 
		from propiedades.ficha_arriendo_cuotas_garantia facg 
		where id_ficha_arriendo =$idFichaArriendo and estado_garantia is not null ";
$data = array("consulta" => $queryEstadosArriendo, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultadoEstadosArriendo = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objEstadosArriendo = json_decode($resultadoEstadosArriendo);
$objetoEstadosArriendo = $objEstadosArriendo[0];
$cantidad_estados = $objetoEstadosArriendo->cantidad_estados;
$flag_disabled_garantia = 0;

if ($cantidad_estados != 0) {

	$flag_disabled_garantia = 1;
}


////////////////////////////SELCT CUOTAS

$select_Cuotas = '<select name="num_cuotas_garantia" id="num_cuotas_garantia" class="form-control form-select" ';

if ($flag_disabled_garantia == 1) {
	$select_Cuotas = $select_Cuotas . " readonly ";
}
$select_Cuotas = $select_Cuotas . ">";
for ($i = 1; $i <= 12; $i++) {
	$select_Cuotas = $select_Cuotas . " ";
	if ($i == $num_cuotas_garantia) {
		$select_Cuotas = $select_Cuotas . "<option value='$i' selected>$i</option>";
	} else {
		$select_Cuotas = $select_Cuotas . "<option value='$i'>$i</option>";
	}
}
$select_Cuotas = $select_Cuotas . " </select>";


if ($num_cuotas_garantia == 0) {
	$monto_por_cuota = 0;
} else {
	$monto_por_cuota = round($valor_monto_garantia / $num_cuotas_garantia);
}
$monto_por_cuota = number_format($monto_por_cuota, 0, ',', '.');