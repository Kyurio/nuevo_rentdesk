<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$token = @$_POST['token'];
$id_usuario = $_SESSION["rd_usuario_id"];



/*=================================================================*/
/*PROCESAMIENTO DE FORMULARIO
/*=================================================================*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	/***************************/
	/*CARGA DE ARCHIVO
	/***************************/
	if (isset($_FILES["archivo"])) {
		//var_dump($_FILES["archivo"]);
	}



	// Accessing form fields
	$persona	= @$_POST['persona']; /*SE RECIBE TOKEN PERSONA (PROPIETARIO) */
	$tipoPropiedad = @$_POST['tipoPropiedad'];
	$estadoPropiedad = @$_POST['estadoPropiedad'];
	$fechaIngreso = @$_POST['fechaIngreso'];
	$direccion = @$_POST['direccion'];
	$complemento = @$_POST['complemento'];
	$nroComplemento = @$_POST['nroComplemento'];
	$numeroDepto = @$_POST['numeroDepto'];
	$piso = @$_POST['piso'];
	$coordenadas = @$_POST['coordenadas'];
	$pais = @$_POST['pais'];
	$region = @$_POST['region'];
	$comuna = @$_POST['comuna'];
	$mCuadrados = @$_POST['mCuadrados'];
	$edificado = @$_POST['edificado'];
	$dormitorios = @$_POST['dormitorios'];
	$dormitoriosServicio = @$_POST['dormitoriosServicio'];
	$banos = @$_POST['banos'];
	$banosVisita = @$_POST['banosVisita'];
	$estacionamientos = @$_POST['estacionamientos'];
	$bodegas = @$_POST['bodegas'];
	$logia = @$_POST['logia'];
	$piscina = @$_POST['piscina'];
	$rol = @$_POST['rol'];
	$avaluoFiscal = @$_POST['avaluoFiscal'];
	$amoblado = @$_POST['amoblado'];
	$dfl2 = @$_POST['dfl2'];
	$destinoArriendo = @$_POST['destinoArriendo'];
	$naturaleza = @$_POST['naturaleza'];
	$dj1835 = @$_POST['dj1835'];
	$pagoContribucion = @$_POST['pagoContribucion'];
	$exentoContribucion = @$_POST['exentoContribucion'];
	$montoRetencion = @$_POST['montoRetencion'];
	$monedaRetencion = @$_POST['monedaRetencion'];
	$motivoRetencion = @$_POST['motivoRetencion'];
	$retenerHasta = @$_POST['retenerHasta'];
	$mostrarCuentasServicio = @$_POST['mostrarCuentasServicio'];
	$sucursal = @$_POST['sucursal'];
	$token_defecto = @$_POST['token_propiedad_defecto'];
	$comunacom = @$_POST['comunacom'];
	$fecha_retenerHasta = @$_POST['retenerHasta'];
	$Complementoestacionamientos =  @$_POST['Complementoestacionamientos'];
	$Complementobodegas =  @$_POST['Complementobodegas'];
	$asegurado = @$_POST['asegurado'];

	// agregado por jernandez agrega el usuario asigando a la propiedad
	$selectEjecutivos = @$_POST["selectEjecutivos"];


	$avaluoFiscal = str_replace(",", "", $avaluoFiscal);
	$avaluoFiscal = str_replace(".", "", $avaluoFiscal);

	@$montoRetencion = str_replace(",", "", $montoRetencion);
	@$montoRetencion = str_replace(".", "", $montoRetencion);

	if ($comuna == "") {
		$comuna = $comunacom;
	}

	if ($fecha_retenerHasta == "") {
		@$concat_fecha = "";
		@$concat_fecha_insert = "";
		@$concat_fecha_insert_valor = "";
	} else {
		@$concat_fecha = ",fecha_retener = '$fecha_retenerHasta'";
		@$concat_fecha_insert = " ,fecha_retener ";
		@$concat_fecha_insert_valor = " , '$fecha_retenerHasta' ";
	}

	if ($dormitorios == "") {
		$dormitorios = 0;
	}
	if ($dormitoriosServicio == "") {
		$dormitoriosServicio = 0;
	}
	if ($banos == "") {
		$banos = 0;
	}
	if ($banosVisita == "") {
		$banosVisita = 0;
	}
	if ($estacionamientos == "") {
		$estacionamientos = 0;
	}
	if ($bodegas == "") {
		$bodegas = 0;
	}
	if ($logia == "") {
		$logia = 0;
	}
	if ($montoRetencion == "") {
		$montoRetencion = 0;
	}
	if ($piso == "") {
		$piso = 0;
	}
	if ($mCuadrados == "") {
		$mCuadrados = 0;
	}
	if ($motivoRetencion == "") {
		$motivoRetencion = 0;
	}
	// File upload handling
	/*
	$archivoNombre = $_FILES['archivo']['name'];
	$archivoTipo = $_FILES['archivo']['type'];
	$archivoTmpNombre = $_FILES['archivo']['tmp_name'];
	$archivoError = $_FILES['archivo']['error'];
	$archivoTamano = $_FILES['archivo']['size'];
*/

	if ($avaluoFiscal == "") {
		$avaluoFiscal = 0;
	}
	$num_reg = 10;
	$inicio = 0;

	$query = "
  SELECT id FROM propiedades.cuenta_sucursal WHERE token = '$sucursal' ";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$json_sucursal = json_decode($resultado)[0];


	$query = "
  SELECT id FROM propiedades.cuenta_usuario WHERE token = '$id_usuario' ";
	//var_dump($query);
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$json_usuario = json_decode($resultado)[0];


	$query = "
  SELECT id FROM propiedades.vis_propietarios vp where token_propietario = '$persona'  ";
	//var_dump($query);
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$json_propietario = json_decode($resultado)[0];

	$query = "
  SELECT id FROM propiedades.propietario_ctas_bancarias where id_propietario = $json_propietario->id  ";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$json_cta_bancaria = json_decode($resultado)[0];


	$query = "
  SELECT id FROM propiedades.propiedad vp where token = '$token'  ";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	@$json_propiedad = json_decode($resultado)[0];


	if (preg_match('/-/', $rol)) {
		var_dump("rol valido");
	} else {
		echo ",xxx,ERROR,xxx,El rol debe tener formato válido (ejemplo: 123-456) ,xxx,-,xxx,";
		return;
	}







	/*---------------------------- */
	/*CREACIÓN/ACTUALIZACIÓN PROPIEDAD */
	if (isset($token) && $token != "") {


		$data = array(
			'tokenSubsidiaria' => $current_subsidiaria->token,
			'tokenSucursal' => $sucursal,
			'roles' => array(
				array(
					"numero" => $rol,
					"principal" => true
				)
			),
			'propietarios' => array(
				array(
					"token" => $persona,
				)
			),
			"comuna" => array(
				"id" => intval($comuna)
			),
			"tipoPropiedad" => array(
				"id" => intval($tipoPropiedad)
			),
			"estadoPropiedad" => array(
				"id" => intval($estadoPropiedad)
			),
			"tipoMoneda" => array(
				// "id" => intval($monedaRetencion)
				"id" => 2
			),
			"direccion" => $direccion,
			"numero" => $nroComplemento,
			"avaluoFiscal" => intval($avaluoFiscal),
			"amoblado" => $amoblado
		);



		//Validacion de rol $rol
		$query = "
			SELECT numero  FROM propiedades.propiedad_roles vp where id_propiedad =  @$json_propiedad->id ";
		// var_dump($query);
		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		@$json_rol_propiedad = json_decode($resultado)[0];

		if ($json_rol_propiedad->numero != "$rol") {

			//Validacion de rol $rol
			$query = "
				SELECT 'SI' as existe FROM propiedades.propiedad_roles vp where vp.id_comuna = '$comuna'  and numero = '$rol'  ";
			// var_dump($query);
			$cant_rows = $num_reg;
			$num_pagina = round($inicio / $cant_rows) + 1;
			$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
			$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
			@$json_rol = json_decode($resultado)[0];

			if ($json_rol->existe == "SI") {
				echo ",xxx,ERROR,xxx,Ya existe rol para Pais - region - comuna ,xxx,-,xxx,";
				return;
			}
		}






		// Merge the base request with the conditional part
		$request = $data;

		//var_dump("DATOS A ENVIAR PATCH: ", $request);


		$queryCabecera = " UPDATE  propiedades.propiedad SET
                     id_sucursal = $json_sucursal->id ,id_comuna = $comuna,id_tipo_propiedad = $tipoPropiedad ,id_estado_propiedad = $estadoPropiedad , 
					 id_moneda = $monedaRetencion ,direccion =  '$direccion',numero = '$nroComplemento' ,avaluo_fiscal = $avaluoFiscal,amoblado = '$amoblado' ,
					 numero_depto = '$numeroDepto',piso = '$piso', coordenadas = '$coordenadas' , edificado = '$edificado' , dormitorios = $dormitorios ,
					 banos = $banos , dormitorios_servicio = $dormitoriosServicio , banos_visita = $banosVisita , estacionamientos = $estacionamientos ,
					 bodegas = $bodegas , logias = $logia , piscina = '$piscina',m2 = $mCuadrados , dfl2 = '$dfl2',id_destino_arriendo =$destinoArriendo,
					 naturaleza = '$naturaleza', exento_contribuciones = '$exentoContribucion' ,paga_contribuciones = '$pagoContribucion' , id_motivo_retencion = $motivoRetencion ,
					 precio = $montoRetencion , mostrar_liquidacion = '$mostrarCuentasServicio' $concat_fecha, complemento_estacionamiento = '$Complementoestacionamientos',
					 complemento_bodega = '$Complementobodegas', id_ejecutiva_encargada = $selectEjecutivos, asegurado = '$asegurado'
                     WHERE token =  '$token' ";

		$dataCab = array("consulta" => $queryCabecera);
		$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

		if ($resultadoCab != "OK") {
			echo ",xxx,ERROR,xxx,No se logro editar propiedad - 0,xxx,-,xxx,";
			return;
		}

		// Insercion de codigo de propiedad
		$num_reg = 10;
		$inicio = 0;

		/*
			$queryCabecera= " INSERT INTO  propiedades.propiedad_copropietarios
							(id_propiedad,id_propietario)
							VALUES ($objIdPropiedad->id,$json_propietario->id ) ";
							
	        var_dump($queryCabecera);
            $dataCab = array("consulta" => $queryCabecera);
            $resultadoCab_propietarios = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
			
			if($resultadoCab_propietarios != "OK"){
				echo ",xxx,ERROR,xxx,No se logro ingresar propiedad - 2,xxx,-,xxx,";
				return ;
			}*/

		$queryCabecera = " UPDATE  propiedades.propiedad_roles
							SET numero = '$rol' where id_propiedad = '$json_propiedad->id' and id_comuna = $comuna ";
		echo $queryCabecera;
		$dataCab = array("consulta" => $queryCabecera);
		$resultadoCab_rol = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

		if ($resultadoCab_rol != "OK") {
			echo ",xxx,ERROR,xxx,No se logro ingresar propiedad - 3,xxx,-,xxx,";
			return;
		}


		//$services->sendPatch($url_services . '/rentdesk/propiedades', $request, null, null); No se ocupara ENDPOINT

		echo ",xxx,OK,xxx,Propiedad Actualizada Correctamente,xxx,-,xxx,";
	} else {
		$data = array(
			'tokenSubsidiaria' => $current_subsidiaria->token,
			'tokenSucursal' => $sucursal,
			'roles' => array(
				array(
					"numero" => $rol,
					"principal" => true
				)
			),
			'propietarios' => array(
				array(
					"token" => $persona,
				)
			),
			"comuna" => array(
				"id" => intval($comuna)
			),
			"tipoPropiedad" => array(
				"id" => intval($tipoPropiedad)
			),
			"estadoPropiedad" => array(
				"id" => intval($estadoPropiedad)
			),
			"tipoMoneda" => array(
				// "id" => intval($monedaRetencion)
				"id" => 2
			),
			"direccion" => $direccion,
			"numero" => $nroComplemento,
			"avaluoFiscal" => intval($avaluoFiscal),
			"amoblado" => $amoblado
		);





		// Merge the base request with the conditional part
		$request = $data;


		//$services->sendPost($url_services . '/rentdesk/propiedades', $request, [], null);  No se ocupara ENDPOINT


		//Validacion de rol $rol
		$query = "
			SELECT 'SI' as existe FROM propiedades.propiedad_roles vp where vp.id_comuna = '$comuna'  and numero = '$rol'  ";
		// var_dump($query);
		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		@$json_rol = json_decode($resultado)[0];

		if ($json_rol->existe == "SI") {
			echo ",xxx,ERROR,xxx,Ya existe rol para Pais - region - comuna ,xxx,-,xxx,";
			return;
		}

		//sucursal 
		$queryCabecera = " INSERT INTO propiedades.propiedad
                    (id_subsidiaria,id_sucursal,id_comuna,id_tipo_propiedad ,id_estado_propiedad , id_moneda,direccion,numero,avaluo_fiscal,amoblado,token,
					 numero_depto,piso,coordenadas ,edificado,dormitorios , banos ,dormitorios_servicio ,banos_visita, estacionamientos, bodegas ,logias , piscina, m2,
					 dfl2,id_destino_arriendo,naturaleza,exento_contribuciones,paga_contribuciones, id_motivo_retencion, precio , mostrar_liquidacion ,id_ejecutivo $concat_fecha_insert, complemento_estacionamiento, complemento_bodega, id_ejecutiva_encargada, asegurado)
                     VALUES ($current_subsidiaria->id,$json_sucursal->id,$comuna,$tipoPropiedad,$estadoPropiedad,2,'$direccion','$nroComplemento',$avaluoFiscal,'$amoblado' ,'$token_defecto',
					 '$numeroDepto','$piso', '$coordenadas' ,'$edificado' , $dormitorios , $banos, $dormitoriosServicio, $banosVisita, $estacionamientos ,
					 $bodegas , $logia , '$piscina', $mCuadrados, '$dfl2' , $destinoArriendo , '$naturaleza', '$exentoContribucion' , '$pagoContribucion', $motivoRetencion , $montoRetencion, 
					 '$mostrarCuentasServicio' , '$json_usuario->id' $concat_fecha_insert_valor, '$Complementoestacionamientos', '$Complementobodegas', $selectEjecutivos, '$asegurado') ";
		$dataCab = array("consulta" => $queryCabecera);
		$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

		if ($resultadoCab != "OK") {
			echo ",xxx,ERROR,xxx,No se logro ingresar propiedad - 0,xxx,-,xxx,";
			return;
		}

		// Insercion de codigo de propiedad
		$num_reg = 10;
		$inicio = 0;

		$query = "select max(id) as id from propiedades.propiedad p ";
		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		//var_dump($resultado);
		$objIdPropiedad = json_decode($resultado)[0];


		$queryCabecera = " UPDATE  propiedades.propiedad
	        				set codigo_propiedad ='$objIdPropiedad->id' where id = $objIdPropiedad->id ";
		$dataCab = array("consulta" => $queryCabecera);
		$resultadoCodigo_propiedad = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

		if ($resultadoCodigo_propiedad != "OK") {
			echo ",xxx,ERROR,xxx,No se logro ingresar propiedad - 1,xxx,-,xxx,";
			return;
		}


		$queryCabecera = " INSERT INTO  propiedades.propiedad_copropietarios
			(id_propiedad,id_propietario,nivel_propietario,id_moneda,id_cta_bancaria,porcentaje_participacion,porcentaje_participacion_base)
			VALUES ($objIdPropiedad->id,$json_propietario->id,1,2,$json_cta_bancaria->id,100,100)";


		$dataCab = array("consulta" => $queryCabecera);
		$resultadoCab_propietarios = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

		if ($resultadoCab_propietarios != "OK") {
			echo ",xxx,ERROR,xxx,No se logro ingresar propiedad - 2,xxx,-,xxx,";
			return;
		}


		$queryCabecera = "INSERT INTO  propiedades.propiedad_roles
							(id_propiedad,numero,principal,id_comuna, descripcion)
							VALUES ($objIdPropiedad->id,'$rol',true,$comuna,(select nombre from propiedades.tp_tipo_propiedad where id in (select id_tipo_propiedad from propiedades.propiedad where id = $objIdPropiedad->id)))";



		$dataCab = array("consulta" => $queryCabecera);
		$resultadoCab_rol = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);


		if ($resultadoCab_rol != "OK") {
			echo ",xxx,ERROR,xxx,No se logro ingresar propiedad - 3,xxx,-,xxx,";
			return;
		}




		echo ",xxx,OK,xxx,Propiedad Ingresada Correctamente,xxx,-,xxx,";
	}
}
