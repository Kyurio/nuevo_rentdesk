var dniParaPropiedad = sessionStorage.getItem('dniParaPropiedad');
sessionStorage.removeItem('dniParaPropiedad');
sessionStorage.removeItem('codigo_propiedad');
sessionStorage.removeItem('ejecutivo');
sessionStorage.removeItem('filtro_direccion');
sessionStorage.removeItem('propietario');
sessionStorage.removeItem('filtro_sucursal');
sessionStorage.removeItem('tipoPropiedad');
sessionStorage.removeItem('estadoPropiedad');
sessionStorage.removeItem('region');
sessionStorage.removeItem('comuna');
sessionStorage.clear();

$(document).ready(function () {
	//jhernandez
	CargarInfoCopopieratioBeneficiario();

	// bruno

	$('#nombreEjecutivo').select2({
		placeholder: 'Seleccione uno o más ejecutivos', // Placeholder
		allowClear: true, // Botón para limpiar selección
		tags: true, // Permitir agregar valores no listados
		tokenSeparators: [','], // Separar por coma,
		width: '100%', // Asegura que se ajuste al ancho del contenedor
	});

	// Guardar nuevo recordatorio
	$('#btnGuardarRecordatorio').on('click', function () {
		// Validaciones
		let camposVacios = [
			// $('#nombreEjecutivo').val().trim(),
			$('#fechaRecordatorio').val(),
			$('#tipoRecordatorio').val(),
		].some((campo) => campo === '');

		if (camposVacios) {
			Swal.fire('Complete todos los campos.', '', 'info');
			return;
		}

		var url = window.location.href;
		var parametros = new URL(url).searchParams;
		var token_propiedad = parametros.get('token');

		var formElement = $('#formRecordatorio').get(0);
		var formData = new FormData(formElement);

		let idEjecutivos = $('#nombreEjecutivo').val();
		formData.append('idEjecutivos', idEjecutivos);
		formData.append('token', token_propiedad);
		console.log('formData:', formData);

		$.ajax({
			url: 'components/propiedad/models/insert_recordatorio.php',
			method: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function (response) {
				try {
					var result = JSON.parse(response);
					console.log();

					if (result.success) {
						Swal.fire(result.message, '', 'success').then((result) => {
							if (result.isConfirmed) {
								$('#modalRecordatoriosNuevo').modal('hide');
								$('#formRecordatorio')[0].reset();
								// Limpiar el campo de ejecutivos
								$('#nombreEjecutivo').val(null).trigger('change');
								$('#ListadoRecordatiorios')
									.DataTable()
									.ajax.reload(null, false);
							}
						});
					} else {
						Swal.fire(result.message, '', 'error');
					}
				} catch (error) {
					Swal.fire(
						'Error al procesar la respuesta del servidor.',
						'',
						'error'
					);
				}
			},
		});
	});

	// Modal para nuevo recordatorio
	$('#modalRecordatoriosNuevo').on('show.bs.modal', function () {
		var url = window.location.href;
		var parametros = new URL(url).searchParams;
		var token = parametros.get('token');
		var hoy = new Date().toISOString().split('T')[0];

		// Establecer la fecha mínima
		$('#fechaNotificacion').attr('min', hoy);

		// Obtener nombre del ejecutivo
		$.ajax({
			url: 'components/propiedad/models/buscar_nombre_ejecutivo.php',
			method: 'GET',
			data: { token: token },
			success: function (response) {
				let data = response; // Ya está parseado en JSON

				// $('#nombreEjecutivo')
				// 	.empty()
				// 	.append('<option value="">Seleccione uno o más ejecutivos</option>');

				data.forEach(function (ejecutivo) {
					$('#nombreEjecutivo').append(
						`<option value="${ejecutivo.id}">${ejecutivo.nombre_completo}</option>`
					);
				});

				// Recargar el select2 para reflejar los nuevos datos
				$('#nombreEjecutivo').select2();
			},
			error: function (error) {
				console.error('Error al obtener los ejecutivos:', error);
			},
		});

		// Obtener tipos de recordatorio
		$.ajax({
			url: 'components/propiedad/models/buscar_tipo_recordatorio.php',
			method: 'GET',
			success: function (response) {
				try {
					var tipos = JSON.parse(response);
					$('#tipoRecordatorio')
						.empty()
						.append('<option value="">Seleccione un tipo</option>'); // Opción por defecto

					tipos.forEach(function (tipo) {
						$('#tipoRecordatorio').append(
							'<option value="' + tipo.nombre + '">' + tipo.nombre + '</option>'
						);
					});
				} catch (error) {
					console.error('Error al procesar los datos JSON de tipos:', error);
				}
			},
			error: function (xhr, status, error) {
				console.error('Error al obtener los tipos de recordatorio:', error);
			},
		});
	});

	ListadoNotificaciones();

	//jhernandez carga la funcion de listar tipo movimientos cuentas corrientes
	CargarSelectTipoMovimientosCC();
	CargarSelectTipoMovimientosCCAbono();

	//jhernandez bloquea los inputs del formulario cuando el estado es bloqueado
	var estadoPropiedadBloqueo = $('#estadoPropiedad').val();
	if (estadoPropiedadBloqueo == 6) {
		Swal.fire({
			title: 'Propiedad Retirada',
			text: 'La propiedad se encuentra en estado retirado.',
			icon: 'info',
		});

		$('#formulario-propiedad input').prop('disabled', true);
		$('#formulario-propiedad select').prop('disabled', true);
	}

	CargarListadoEjecutivos();

	//esto lo agrego jhernandez
	//carga el rut del cliente  la lgoica es la siguiente. si  esta variable del localstorage esta cargada quiere decir que
	// viene del proceso de asignar a  una propiedad a un cliente no existente, se creo el cliente y luego volvio aqui asi que
	// por eso se activara un tab, luego de ahi
	var rutcapturado = localStorage.getItem('Rutaregistrar');
	if (rutcapturado) {
		$(document).ready(function () {
			$('#propiedad-ft-co-propietarios').trigger('click');
		});
	}
	storage.removeItem('Rutaregistrar');
	console.log(localStorage.getItem('miVariable')); // Debería mostrar null si se eliminó correctamente

	localStorage.removeItem('alertaMostrada');
	if (dniParaPropiedad) {
		$('#DNI').val(dniParaPropiedad);
		$('#button-addon2').hide();
		$('#DNI').css('border', '1px solid #dddddd');
		busquedaDNI();
	}
	var url = window.location.href;
	var parametros = new URL(url).searchParams;
	var token = parametros.get('token');
	if (token) {
		$('#button-addon2').hide();
		$('#DNI').css('border', '1px solid #dddddd');
		busquedaDNI();
	}

	document.getElementById('botonEliminaSeccion').style.display = 'none';
});

$(document).ready(function () {
	getDireccion();
	cargarDocumentos();
	cargarDocumentosSoloLectura();
});

function getDireccion() {
	// Obtener token de la URL
	var url = window.location.href;
	var parametros = new URL(url).searchParams;
	var token_propiedad = parametros.get('token');

	// Llamada AJAX
	$.ajax({
		url: 'components/propiedad/models/get_direccion.php',
		type: 'post',
		data: {
			token: token_propiedad,
		},
		success: function (response) {
			// Aquí puedes procesar la respuesta, p.ej. parsear JSON
			const data = JSON.parse(response);
			if (data && data.length > 0) {
				// Asumiendo que solo tomas la primera fila, por ejemplo
				// Parsea la respuesta JSON
				const data = JSON.parse(response);
				if (data && data.length > 0) {
					// Asigna el texto al span
					$('#direccionFichaPropiedad').text(data[0].direccion);
				} else {
					console.log('No se encontró dirección');
				}
			} else {
				console.log('No se encontró dirección');
			}
		},
		error: function (xhr, status, error) {
			console.error('Error:', error);
		},
	});
}

$(document).ready(function () {
	cargarInfoCoPropietariosPropiedad();
	cargarInfoCuentaCorriente();
	checkUseBenefData();
	cargarRevCuentasServicioList();
	cargarArriendoEliminarMorasList();
	cargarLiquidacionesHistorico();
	cargarLiquidacionesPagoPropietariosList();
	cargarInfoComentario();

	//BRUNO TORRES

	$('#descargarExcelPropiedad').on('click', function (e) {
		e.preventDefault();

		// Capturamos los valores de los 2 inputs
		var codigoProp = $('#codigo_propiedad').val();
		var propietario = $('#propietario').val();

		$.ajax({
			url: 'components/propiedad/models/get_propiedad_excel.php', // Ajusta la ruta a tu PHP
			type: 'GET',
			// Enviamos los parámetros
			data: {
				codigo_propiedad: codigoProp,
				propietario: propietario,
			},
			dataType: 'json',
			success: function (response) {
				// 1) Validar si está vacío
				if (!response || response.length === 0) {
					Swal.fire({
						icon: 'info',
						title: 'No se encontró ningún arriendo',
						showConfirmButton: true,
					});
					return; // Terminamos la ejecución de la función
				}
				// 1) Transformar la respuesta a columnas amigables
				//    (Sucusal, Ejecutivo, Propietario, Tipo de Propiedad, Dirección, etc.)
				var formattedData = response.map(function (row) {
					return {
						'Codigo Propiedad': row.codigo_propiedad,
						Sucursal: row.sucursal,
						Ejecutivo: row.ejecutivo,
						Propietario: row.propietarios,
						'Tipo de Propiedad': row.tipo_propiedad,
						Dirección: row.direccion,
						Comuna: row.comuna,
						Región: row.region,
						Estado: row.estado,
						Asegurado: row.asegurado,
						Precio: row.precio,
					};
				});

				// 2) Crear la hoja con SheetJS
				var worksheet = XLSX.utils.json_to_sheet(formattedData);

				// 3) Ajustar anchos de columna (opcional)
				worksheet['!cols'] = [
					{ wpx: 140 }, // Sucursal
					{ wpx: 140 }, // Ejecutivo
					{ wpx: 180 }, // Propietario
					{ wpx: 130 }, // Tipo de Propiedad
					{ wpx: 220 }, // Dirección
					{ wpx: 120 }, // Comuna
					{ wpx: 120 }, // Región
					{ wpx: 100 }, // Estado
					{ wpx: 100 }, // Asegurado
					{ wpx: 120 }, // Precio
				];

				// 4) Crear workbook y añadir la hoja
				var workbook = XLSX.utils.book_new();
				XLSX.utils.book_append_sheet(workbook, worksheet, 'Propiedades');

				// 5) Generar archivo XLSX (array binario)
				var wbout = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

				// 6) Convertir a Blob
				var blob = new Blob([wbout], { type: 'application/octet-stream' });

				// 7) Crear URL de descarga
				var url = URL.createObjectURL(blob);

				// 8) Crear enlace temporal y forzar la descarga
				var a = document.createElement('a');
				a.href = url;
				a.download = 'propiedades.xlsx'; // Nombre del archivo
				document.body.appendChild(a);
				a.click();
				document.body.removeChild(a);

				// 9) Liberar URL
				URL.revokeObjectURL(url);
			},
			error: function (xhr, status, error) {
				console.error('Error en la petición AJAX:', error);
			},
		});
	});
});

function generarExcel(urlbase) {
	var propietario = sessionStorage.getItem('propietario');
	var ejecutivo = sessionStorage.getItem('ejecutivo');
	var filtro_codigo_propiedad = sessionStorage.getItem('codigo_propiedad');
	var filtro_direccion = sessionStorage.getItem('filtro_direccion');
	var filtro_sucursal = sessionStorage.getItem('filtro_sucursal');
	var tipoPropiedad = sessionStorage.getItem('tipoPropiedad');
	var estadoPropiedad = sessionStorage.getItem('estadoPropiedad');
	var region = sessionStorage.getItem('region');
	var comuna = sessionStorage.getItem('comuna');

	var ajaxUrl =
		'components/propiedad/models/propiedad_list_procesa_excel.php?' +
		'propietario=' +
		encodeURIComponent(propietario) +
		'&ejecutivo=' +
		encodeURIComponent(ejecutivo) +
		'&filtro_direccion=' +
		encodeURIComponent(filtro_direccion) +
		'&filtro_sucursal=' +
		encodeURIComponent(filtro_sucursal) +
		'&tipoPropiedad=' +
		encodeURIComponent(tipoPropiedad) +
		'&region=' +
		encodeURIComponent(region) +
		'&comuna=' +
		encodeURIComponent(comuna) +
		'&codigo_propiedad=' +
		encodeURIComponent(filtro_codigo_propiedad);
	$.ajax({
		type: 'GET',
		url: ajaxUrl,
		success: function (res) {
			window.open('/upload/propiedad/excel/' + res, '_blank');
		},
	});
}

function cargarInfoCuentaCorriente() {
	cargarCCMovimientosList();
	cargarCCMovimientoSaldoActual();
}

function guardarCcAbono() {
	var formData = new FormData(document.getElementById('cc_pago_no_liquidable'));

	var jsonInformacionNueva = obtenerValoresFormulario('cc_pago_no_liquidable');

	const ccTipoMovimientoAbonoinput = document.getElementById(
		'ccTipoMovimientoAbono'
	);

	var ccTipoMovimientoAbono = ccTipoMovimientoAbonoinput.value;

	const cc_pago_razon_input = document.getElementById('ccIngresoPagoNLRazon');
	var ccIngresoPagoNLRazon = cc_pago_razon_input.value;

	const cc_pago_monto_input = document.getElementById('ccIngresoPagoNLMonto');
	var ccIngresoPagoNLMonto = cc_pago_monto_input.value;

	const cc_pago_moneda_input = document.getElementById('ccIngresoPagoNLMoneda');
	var ccIngresoPagoNLMoneda = cc_pago_moneda_input.value;

	const cc_pago_fecha_input = document.getElementById('ccIngresoPagoNLFecha');
	var ccIngresoPagoNLFecha = cc_pago_fecha_input.value;

	var ccidResponsableAbono =
		document.getElementById('idResponsableAbono').value;
	var ccctacorrienteAbono = document.getElementById('ctacorrienteAbono').value;

	if (ccIngresoPagoNLRazon == null || ccIngresoPagoNLRazon == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar una razón',
			icon: 'warning',
		});
		return;
	}

	if (ccIngresoPagoNLMonto == null || ccIngresoPagoNLMonto == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar un monto',
			icon: 'warning',
		});
		return;
	}

	if (ccIngresoPagoNLMoneda == null || ccIngresoPagoNLMoneda == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar una Moneda',
			icon: 'warning',
		});
		return;
	}

	if (ccIngresoPagoNLFecha == null || ccIngresoPagoNLFecha == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar fecha de pago',
			icon: 'warning',
		});
		return;
	}

	if (ccTipoMovimientoAbono == null || ccTipoMovimientoAbono == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar seleccionar el tipo de abono',
			icon: 'warning',
		});
		return;
	}

	formData.append('ccIngresoPagoNLRazon', ccIngresoPagoNLRazon);
	formData.append('ccIngresoPagoNLMonto', ccIngresoPagoNLMonto);
	formData.append('ccIngresoPagoNLMoneda', ccIngresoPagoNLMoneda);
	formData.append('ccIngresoPagoNLFecha', ccIngresoPagoNLFecha);
	formData.append('ccTipoMovimientoAbono', ccTipoMovimientoAbono);
	formData.append('ccidResponsableAbono', ccidResponsableAbono);
	formData.append('ccctacorrienteAbono', ccctacorrienteAbono);

	var id_ficha = $('#id_ficha').val();
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	//console.log(parametros.get("token"));
	formData.append('token', parametros.get('token'));

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/propiedad/models/insert_cc_abono.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			$('#modalCuentaCorrienteIngresoPagoNoLiquidable').modal('hide');
			$('#cc_pago_no_liquidable')[0].reset();

			Swal.fire({
				title: 'Abono registrado',
				text: 'El Abono se registro correctamente',
				icon: 'success',
			});
			var id_comentario = res;
			var jsonInformacioantigua = capturarInformacionAntigua();

			cargarCCMovimientoSaldoActual();
			cargarCCMovimientosList();
			registroHistorial(
				'Crear',
				'',
				jsonInformacionNueva,
				'Cuenta Corriente - Abono',
				id_ficha,
				id_comentario
			);

			//habilita y deshabilita los botoenes segun movimeintos de cuenta corriente jhernandez
			$('#btnLiquidar').css('display', 'block');
			$('#infoPropiedad').css('display', 'none');
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			$('#modalCuentaCorrienteIngresoPagoNoLiquidable').modal('hide');

			Swal.fire({
				title: 'Atención',
				text: 'El Abono no se registró',
				icon: 'warning',
			});
		});
	$('#cc_pago_no_liquidable')[0].reset();
	$('#modalCuentaCorrienteIngresoPagoNoLiquidable').modal('hide');
	cargarCCMovimientoSaldoActual();
	cargarCCMovimientosList();
}

// funcion cargar ejecutivos jhernandez
function CargarListadoEjecutivos() {
	// Obtén la URL actual
	// const url = new URL(window.location.href);
	// const params = new URLSearchParams(url.search);
	// const token = params.get('token');
	// $.ajax({
	//   url: "components/propiedad/models/listado_ejecutivos.php?token=" + token,
	//   type: "post",
	// }).done(function (res) {
	//   var select = $("#selectEjecutivos");
	//   var data = JSON.parse(res);
	//   // Limpiar el select antes de agregar nuevos elementos
	//   select.empty();
	//   // Agregar la opción "Seleccione" al principio del select
	//   select.append($('<option>', {
	//     value: "",
	//     text: "Seleccione"
	//   }));
	//   // Iterar sobre los datos y agregar opciones al select
	//   $.each(data, function (index, ejecutivo) {
	//     select.append($('<option>', {
	//       value: ejecutivo.id,
	//       text: ejecutivo.nombres
	//     }));
	//   });
	// }).fail(function (jqXHR, textStatus, errorThrown) {
	//   $("#modalCuentaCorrienteIngresoPagoNoLiquidable").modal("hide");
	//   Swal.fire({
	//     title: "Atención",
	//     text: "Error al obtener los ejecutivos",
	//     icon: "warning",
	//   });
	// });
}

function cargaDocumentoCuentaCorrientePago() {
	var formData = new FormData(document.getElementById('cc_pago_doc'));

	var url = window.location.href;
	var parametros = new URL(url).searchParams;

	//  const token_propiedad_defecto_input = document.getElementById("token_propiedad_defecto");
	//  var token_propiedad_defecto = token_propiedad_defecto_input.value;

	if (parametros.get('token')) {
		formData.append('token_ficha', parametros.get('token'));
	}

	const titulo_input = document.getElementById('documentoTitulo');
	var titulo = titulo_input.value;

	if (titulo == null || titulo == '') {
		Swal.fire({
			title: 'Atención',
			text: 'Debe agregar un titulo',
			icon: 'warning',
		});
		return;
	}

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	$.ajax({
		url: 'components/propiedad/models/insert_archivo_cc.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		console.log('res');
		console.log(res);
		var retorno = res.split(',xxx,');
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var token = retorno[3];

		if (resultado == 'OK') {
			Swal.fire({
				title: 'Se guardo correctamente el documento',
				icon: 'success',
			});
			$('#modalDocumentoIngresoCC').modal('hide');
			resetFormGuardar();
			cargarDocumentos();
		} else {
			Swal.fire({
				title: 'Atención',
				text: mensaje,
				icon: 'warning',
			});
			// alert("No se logro crear arriendo");
			return false;
		}
	});
	// resetForm();
}

function resetFormCC() {
	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Al volver sin guardar se perderan los cambios',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Si',
		denyButtonText: 'No',
	}).then((result) => {
		if (result.isConfirmed) {
			// Si se confirma la acción
			document.getElementById('documentoTitulo').value = '';
			document.getElementById('archivo_cuenta_corriente_0').value = '';
			document.getElementById('documentoFecha_0').value = '';

			var seccionesAdicionales = document.querySelectorAll(
				'[id^="seccionDocumento_archivo_cuenta_corriente_"]'
			); // Se mantiene formulario original
			var idSeccionAConservar = 'seccionDocumento_archivo_cuenta_corriente_0';
			seccionesAdicionales.forEach(function (seccion) {
				if (seccion.id !== idSeccionAConservar) {
					seccion.remove();
				}
			});
			document.getElementById('botonEliminaSeccion').style.display = 'none';
			$('#modalDocumentoIngresoCC').modal('hide');
		}
	});

	//document.getElementById("modalDocumentoIngreso").reset();
}

function cargaDocumentoCuentaCorrientePagoNL() {
	var formData = new FormData(document.getElementById('cc_pago_no_liquidable'));

	var url = window.location.href;
	console.log(url);
	var parametros = new URL(url).searchParams;

	//  const token_propiedad_defecto_input = document.getElementById("token_propiedad_defecto");
	//  var token_propiedad_defecto = token_propiedad_defecto_input.value;

	console.log('TOKEN CREACION: ', parametros.get('token'));
	if (parametros.get('token')) {
		formData.append('token_ficha', parametros.get('token'));
	}

	const titulo_input = document.getElementById('documentoTituloPagoNL');
	var titulo = titulo_input.value;

	if (titulo == null || titulo == '') {
		Swal.fire({
			title: 'Atención',
			text: 'Debe agregar un titulo',
			icon: 'warning',
		});
		console.log('titulo vacio');
		return;
	}

	console.log('Enviando Documentos');
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	$.ajax({
		url: 'components/propiedad/models/insert_archivo_cc_pago_nl.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		console.log('res');
		console.log(res);
		var retorno = res.split(',xxx,');
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var token = retorno[3];

		if (resultado == 'OK') {
			Swal.fire({
				title: 'Se guardo correctamente el documento',
				icon: 'success',
			});
			$('#modalDocumentoIngresoCCPagoNL').modal('hide');
			resetFormGuardar();
			cargarDocumentos();
		} else {
			Swal.fire({
				title: 'Atención',
				text: mensaje,
				icon: 'warning',
			});
			// alert("No se logro crear arriendo");
			return false;
		}
	});
	// resetForm();
}

function resetFormCCPagoNL() {
	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Al volver sin guardar se perderan los cambios',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Si',
		denyButtonText: 'No',
	}).then((result) => {
		if (result.isConfirmed) {
			// Si se confirma la acción
			document.getElementById('documentoTituloPagoNL').value = '';
			document.getElementById('archivo_cuenta_corriente_pago_nl_0').value = '';
			document.getElementById('documentoFecha_0').value = '';

			var seccionesAdicionales = document.querySelectorAll(
				'[id^="seccionDocumento_archivo_cuenta_corriente_pago_nl_"]'
			); // Se mantiene formulario original
			var idSeccionAConservar =
				'seccionDocumento_archivo_cuenta_corriente_pago_nl_0';
			seccionesAdicionales.forEach(function (seccion) {
				if (seccion.id !== idSeccionAConservar) {
					seccion.remove();
				}
			});
			document.getElementById('botonEliminaSeccion').style.display = 'none';
			$('#modalDocumentoIngresoCCPagoNL').modal('hide');
		}
	});

	//document.getElementById("modalDocumentoIngreso").reset();
}

function resetFormGuardarCC() {
	document.getElementById('documentoTitulo').value = '';
	document.getElementById('archivo_cuenta_corriente_0').value = '';
	document.getElementById('documentoFecha_0').value = '';

	var seccionesAdicionales = document.querySelectorAll(
		'[id^="seccionDocumento_archivo_cuenta_corriente_"]'
	); // Se mantiene formulario original
	var idSeccionAConservar = 'seccionDocumento_archivo_cuenta_corriente_0';
	seccionesAdicionales.forEach(function (seccion) {
		if (seccion.id !== idSeccionAConservar) {
			seccion.remove();
		}
	});
	document.getElementById('botonEliminaSeccion').style.display = 'none';
	$('#modalDocumentoIngresoCC').modal('hide');
	//document.getElementById("modalDocumentoIngresoCC").reset();
}

function cargarDocumentosSoloLectura() {
	// Realizar la solicitud AJAX para obtener los datos
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	var token_propiedad = parametros.get('token');
	// console.log(parametros.get('token'));

	var formData = new FormData(document.getElementById('formulario-propiedad'));

	const token_propiedad_defecto_input = document.getElementById(
		'token_propiedad_defecto'
	);
	var token_propiedad_defecto = token_propiedad_defecto_input.value;

	if (token_propiedad) {
		var token = token_propiedad;
	} else {
		var token = token_propiedad_defecto;
	}

	console.log('Ingresando a ajax');
	$.ajax({
		url: 'components/propiedad/models/listado_documentos.php',
		type: 'POST',
		dataType: 'json',
		//data:  "token="+ parametros.get('token') ,
		data: { token: token },
		cache: false,
		success: function (data) {
			console.log('entrando  a la funcion');
			console.log(data);
			if (data != null) {
				console.log('la data no es nula');
				var previousId = null;
				var tbody = $('#lectura tbody');
				// Limpiar el cuerpo de la tabla por si hay datos anteriores
				tbody.empty();
				// Iterar sobre los datos y agregar filas a la tabla
				$.each(data, function (index, item) {
					// Crear una nueva fila de la tabla
					var newRow = $('<tr>');
					// Agregar celdas a la fila con los datos
					if (item.token_agrupador != previousId) {
						newRow.append(
							"<td><div class='d-flex align-items-center' style='gap: .5rem;'><label style='font-size: 1em; text-align: center; color: black;'>" +
								item.titulo +
								'</label></div></td>'
						);
						previousId = item.token_agrupador;
					} else {
						newRow.append('<td></td>'); // Agrega una celda vacía si es el mismo ID que el anterior
					}
					if (item.nombre_archivo != null && item.nombre_archivo != '') {
						newRow.append(
							"<td><i class='fa-solid fa-chevron-right'></i> " +
								item.nombre_archivo +
								'</td>'
						);
					} else {
						newRow.append('<td>-</td>');
					}
					newRow.append(
						'<td>' + moment(item.fecha_subida).format('DD-MM-YYYY') + '</td>'
					);
					//console.log("Fecha ",moment(item.fecha_vencimiento).format("DD-MM-YYYY"));
					if (
						moment(item.fecha_vencimiento).format('DD-MM-YYYY') != '01-01-1900'
					) {
						newRow.append(
							'<td>' +
								moment(item.fecha_vencimiento).format('DD-MM-YYYY') +
								'</td>'
						);
					} else {
						newRow.append('<td>-</td>');
					}

					//console.log(item.link);
					newRow.append(
						"<td><div class='d-flex' style='gap: .5rem;'><a href='" +
							item.link +
							"' download  type='button' class='btn btn-info m-0 d-flex' style='padding: .5rem;' aria-label='documento' title='documento'><i class='fa-solid fa-file' style='font-size: .75rem;'></i></div></td>"
					);
					if (
						item.fecha_ultima_actualizacion != null &&
						item.fecha_ultima_actualizacion != ''
					) {
						newRow.append(
							'<td>' +
								(item.fecha_ultima_actualizacion
									? moment(item.fecha_ultima_actualizacion).format('DD-MM-YYYY')
									: '-') +
								"  <i class='fa-solid fa-circle-info' data-bs-toggle='tooltip' data-bs-placement='top' title='Modificado por : " +
								item.nombre_usuario +
								"'></i></td>"
						);
					} else {
						newRow.append('<td>-</td>');
					}

					// Agregar la fila al cuerpo de la tabla
					tbody.append(newRow);
				});
				console.log(data);
			} else {
				var tbody = $('#lectura tbody');
				tbody.empty();
				var newRow = $('<tr>');
				console.log('error ....');
				newRow.append(
					"<td colspan='9' style='text-align:center'> No hay Documentos</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			console.log('error ');
			console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
		},
	});
}

// function guardarInfoComentario() {
// 	var formData = new FormData(document.getElementById('comentario_formulario'));
// 	console.log('Entrando comentario');

// 	var jsonInformacionNueva = obtenerValoresFormulario('comentario_formulario');

// 	const comentarioEditar_input = document.getElementById('ComentarioIngreso');
// 	var ComentarioIngreso = comentarioEditar_input.value;

// 	if (ComentarioIngreso == null || ComentarioIngreso == '') {
// 		Swal.fire({
// 			title: 'Atención ',
// 			text: 'Debe agregar un comentario',
// 			icon: 'warning',
// 		});
// 		return;
// 	}

// 	formData.append('ComentarioIngreso', ComentarioIngreso);

// 	var id_ficha = $('#id_ficha').val();
// 	var url = window.location.href;
// 	//console.log(url);
// 	var parametros = new URL(url).searchParams;
// 	//console.log(parametros.get("token"));
// 	formData.append('token', parametros.get('token'));

// 	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

// 	$.ajax({
// 		url: 'components/propiedad/models/insert_comentario.php',
// 		type: 'post',
// 		dataType: 'text',
// 		data: formData,
// 		cache: false,
// 		contentType: false,
// 		processData: false,
// 	})
// 		.done(function (res) {
// 			console.log('RESULTADO INSERCIÓN COMENTARIO: ', res);
// 			$('#modalInfoComentarioIngreso').modal('hide');
// 			$('#comentario_formulario')[0].reset();

// 			Swal.fire({
// 				title: 'Comentario registrado',
// 				text: 'El comentario se registro correctamente',
// 				icon: 'success',
// 			});
// 			var id_comentario = res;
// 			var jsonInformacioantigua = capturarInformacionAntigua();

// 			cargarInfoComentario();
// 			registroHistorial(
// 				'Crear',
// 				'',
// 				jsonInformacionNueva,
// 				'Comentario',
// 				id_ficha,
// 				id_comentario
// 			);
// 		})
// 		.fail(function (jqXHR, textStatus, errorThrown) {
// 			$('#modalInfoComentarioIngreso').modal('hide');

// 			Swal.fire({
// 				title: 'Atención',
// 				text: 'El comentario no se registro',
// 				icon: 'warning',
// 			});
// 		});
// 	$('#comentario_formulario')[0].reset();
// 	$('#modalInfoComentarioIngreso').modal('hide');
// 	cargarInfoComentario();
// }

function cargarInfoComentario() {
	// Realizar la solicitud AJAX para obtener los datos
	var idFicha = $('#id_ficha').val();
	$.ajax({
		url: 'components/propiedad/models/listado_info_comentarios.php',
		type: 'POST',
		dataType: 'json',
		data: { idFicha: idFicha },
		cache: false,
		success: function (data) {
			console.log('DATA: ', data);
			if (data != null) {
				var tbody = $('#info-comentarios tbody');
				// Limpiar el cuerpo de la tabla por si hay datos anteriores
				tbody.empty();
				// Iterar sobre los datos y agregar filas a la tabla
				$.each(data, function (index, item) {
					// Crear una nueva fila de la tabla
					var newRow = $('<tr>');

					// Agregar celdas a la fila con los datos
					newRow.append('<td>' + formateoNulos(item.comentario) + '</td>');
					newRow.append(
						'<td>' + formateoNulos(item.fecha_comentario) + '</td>'
					);
					if (!item?.nombre_usuario) {
						newRow.append('<td>-</td>');
					} else if (
						item.fecha_modificacion != null &&
						item.fecha_modificacion != ''
					) {
						newRow.append(
							'<td>' +
								formateoNulos(item.fecha_comentario) +
								"  <i class='fa-solid fa-circle-info' data-bs-toggle='tooltip' data-bs-placement='top' title='Modificado por : " +
								item.nombre_usuario +
								"'></i></td>"
						);
					} else {
						newRow.append('<td>-</td>');
					}
					newRow.append('<td>' + formateoNulos(item.nombre_usuario) + '</td>');

					newRow.append(
						`<td>
      <div class='d-flex' style='gap: .5rem;'>
      
       <a 
        data-bs-toggle='modal' 
        data-bs-target='#modalEditarInfoComentario' 
        type='button' 
			  onclick='cargarInfoComentarioEditar(${item.id}, "${item.comentario}")' 
			  class='btn btn-info m-0 d-flex' 
        style='padding: .5rem;' 
        aria-label='Editar' 
        title='Editar'
      >
        <i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i>
      </a>
      
      <button onclick='eliminarInfoComentario(${item.id})' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'>
        <i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
      </button>
    </div>
  </td>`
					);
					// Agregar la fila al cuerpo de la tabla
					tbody.append(newRow);
				});
			} else {
				var tbody = $('#info-comentarios tbody');
				tbody.empty();
				var newRow = $('<tr>');
				console.log('error');
				newRow.append(
					"<td colspan='9' style='text-align:center'> No hay Comentarios</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			console.log('error', jqXHR, textStatus, errorThrown);
		},
	});
}

function cargarInfoComentarioEditar(idInfoComentario, comentario) {
	console.log('PARAMETROS ENTRADA: ', { idInfoComentario, comentario });
	console.log('ESTOY EN cargarInfoComentarioEditar');
	$('#ComentarioEditar').val(comentario);
	$('#ID_Info_Comentario_Editar').val(idInfoComentario);
}

function editarInfoComentario() {
	var formData = new FormData(
		document.getElementById('comentario_formulario_editar')
	);
	var jsonInformacionNueva = obtenerValoresFormulario(
		'comentario_formulario_editar'
	);
	// Decodificar el texto JSON a un array asociativo
	var objeto_json = JSON.parse(jsonInformacionNueva);

	console.log('objeto_json editar info comentario: ', objeto_json);

	var id_comentario = objeto_json.ID_Info_Comentario_Editar;
	var url = window.location.href;
	//console.log(url);
	var id_ficha = $('#id_ficha').val();
	var parametros = new URL(url).searchParams;

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/propiedad/models/editar_info_comentario.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			Swal.fire({
				title: 'Comentario actualizado',
				text: 'El comentario se actualizó correctamente',
				icon: 'success',
			});
			var jsonInformacioantigua = capturarInformacionAntigua();
			cargarInfoComentario();
			registroHistorial(
				'Modificar',
				jsonInformacioantigua,
				jsonInformacionNueva,
				'Comentario',
				id_ficha,
				id_comentario
			);
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			Swal.fire({
				title: 'Atención',
				text: 'El comentario no se actualizó',
				icon: 'warning',
			});
		});

	$('#comentario_formulario_editar')[0].reset();
	$('#modalEditarInfoComentario').modal('hide');
	cargarInfoComentario();
}

function eliminarInfoComentario(idInfoComentario) {
	console.log('idInfoComentario: ', idInfoComentario);

	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Una vez eliminado, no podrás recuperar este comentario',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Eliminar',
		denyButtonText: 'Cancelar',
	}).then((result) => {
		if (result.isConfirmed) {
			// Si el usuario hace clic en "Eliminar"
			$.ajax({
				url: 'components/propiedad/models/delete_info_comentario.php',
				type: 'POST',
				dataType: 'text',
				data: { idInfoComentario: idInfoComentario },
				success: function (response) {
					Swal.fire({
						title: 'Comentario eliminado',
						text: 'El comentario se eliminó correctamente',
						icon: 'success',
					});
					cargarInfoComentario();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.error('Error en la solicitud:', textStatus, errorThrown);
					// Aquí puedes mostrar un mensaje de error al usuario o realizar otras acciones
				},
			});
		} else if (result.isDenied) {
			// Si el usuario hace clic en "Cancelar"
			// Aquí puedes cerrar el modal de SweetAlert si lo deseas
			Swal.close();
		}
	});
}

function guardarCcDescuento() {
	var formData = new FormData(
		document.getElementById('cc_descuento_autorizado')
	);

	var ccTipoMovimiento = document.getElementById('ccTipoMovimientoCargo');

	var jsonInformacionNueva = obtenerValoresFormulario(
		'cc_descuento_autorizado'
	);

	const cc_pago_razon_input = document.getElementById(
		'ccIngresoDescAutorizadoRazon'
	);
	var ccIngresoDescAutorizadoRazon = cc_pago_razon_input.value;

	const cc_pago_monto_input = document.getElementById(
		'ccIngresoDescAutorizadoMonto'
	);
	var ccIngresoDescAutorizadoMonto = cc_pago_monto_input.value;

	const cc_pago_moneda_input = document.getElementById(
		'ccIngresoDescAutorizadoMoneda'
	);
	var ccIngresoDescAutorizadoMoneda = cc_pago_moneda_input.value;

	const cc_pago_cobra_comision_input = document.getElementById(
		'ccIngresoDescAutorizadoCobraComision'
	);
	var ccIngresoDescAutorizadoCobraComision = cc_pago_cobra_comision_input.value;

	const cc_pago_fecha_input = document.getElementById(
		'ccIngresoDescAutorizadoFecha'
	);
	var ccIngresoDescAutorizadoFecha = cc_pago_fecha_input.value;

	var ccidResponsable = document.getElementById('idResponsableCargo').value;
	var cccta_contableCargo = document.getElementById(
		'cccta_contableCargo'
	).value;

	if (ccTipoMovimiento == null || ccTipoMovimiento == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe seleccionar un tipo de movimiento.',
			icon: 'warning',
		});
		return;
	}

	if (
		ccIngresoDescAutorizadoRazon == null ||
		ccIngresoDescAutorizadoRazon == ''
	) {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar una razón',
			icon: 'warning',
		});
		return;
	}

	if (
		ccIngresoDescAutorizadoMonto == null ||
		ccIngresoDescAutorizadoMonto == ''
	) {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar un monto',
			icon: 'warning',
		});
		return;
	}

	if (
		ccIngresoDescAutorizadoMoneda == null ||
		ccIngresoDescAutorizadoMoneda == ''
	) {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar una Moneda',
			icon: 'warning',
		});
		return;
	}

	if (
		ccIngresoDescAutorizadoCobraComision == null ||
		ccIngresoDescAutorizadoCobraComision == ''
	) {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe seleccionar si cobra comisión',
			icon: 'warning',
		});
		return;
	}

	if (
		ccIngresoDescAutorizadoFecha == null ||
		ccIngresoDescAutorizadoFecha == ''
	) {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar fecha de pago',
			icon: 'warning',
		});
		return;
	}

	formData.append('ccTipoMovimiento', ccTipoMovimiento.value);
	formData.append('ccIngresoDescAutorizadoRazon', ccIngresoDescAutorizadoRazon);
	formData.append('ccIngresoDescAutorizadoMonto', ccIngresoDescAutorizadoMonto);
	formData.append(
		'ccIngresoDescAutorizadoMoneda',
		ccIngresoDescAutorizadoMoneda
	);
	formData.append(
		'ccIngresoDescAutorizadoCobraComision',
		ccIngresoDescAutorizadoCobraComision
	);
	formData.append('ccIngresoDescAutorizadoFecha', ccIngresoDescAutorizadoFecha);
	formData.append('ccidResponsable', ccidResponsable);
	formData.append('cccta_contableCargo', cccta_contableCargo);

	var id_ficha = $('#id_ficha').val();
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	//console.log(parametros.get("token"));
	formData.append('token', parametros.get('token'));

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/propiedad/models/insert_cc_descuento.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			$('#modalCuentaCorrienteIngresoDescuentoAutorizado').modal('hide');
			$('#cc_descuento_autorizado')[0].reset();

			Swal.fire({
				title: 'Cargo registrado',
				text: 'El cargo se registro correctamente',
				icon: 'success',
			});
			var id_comentario = res;
			var jsonInformacioantigua = capturarInformacionAntigua();

			cargarCCMovimientoSaldoActual();
			cargarCCMovimientosList();
			registroHistorial(
				'Crear',
				'',
				jsonInformacionNueva,
				'Cuenta Corriente - cargo',
				id_ficha,
				id_comentario
			);
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			$('#modalCuentaCorrienteIngresoDescuentoAutorizado').modal('hide');

			Swal.fire({
				title: 'Atención',
				text: 'El cargo no se registró',
				icon: 'warning',
			});
		});
	$('#cc_descuento_autorizado')[0].reset();
	$('#modalCuentaCorrienteIngresoDescuentoAutorizado').modal('hide');
	cargarCCMovimientoSaldoActual();
	cargarCCMovimientosList();
}

// function cargarCCMovimientoSaldoActual() {
// 	// Realizar la solicitud AJAX para obtener los datos
// 	// var idFicha = $('#id_ficha').val();
// 	// $.ajax({
// 	// 	url: 'components/propiedad/models/listado_cc_movimientos.php',
// 	// 	type: 'POST',
// 	// 	dataType: 'json',
// 	// 	data: { idFicha: idFicha },
// 	// 	cache: false,
// 	// 	success: function (data) {
// 	// 		console.log('DATA: ', data);
// 	// 		let ccmovSaldoDia = document.getElementById('ccMovsaldoAlDia');

// 	// 		if (data != null) {
// 	// 			// Select the span element by its id

// 	// 			console.log('MYSPAN: ', ccmovSaldoDia);
// 	// 			let saldoAlDia = '';

// 	// 			// Set the text content of the span element
// 	// 			if (data[0]?.saldo >= 0) {
// 	// 				saldoAlDia =
// 	// 					"<strong class='text-primary'>" +
// 	// 					formateoNulos(formateoDivisa(data[0]?.saldo)) +
// 	// 					'</strong>';
// 	// 			} else {
// 	// 				saldoAlDia =
// 	// 					"<strong class='text-danger'>" +
// 	// 					formateoNulos(formateoDivisa(data[0]?.saldo)) +
// 	// 					'</strong>';
// 	// 			}

// 	// 			ccmovSaldoDia.innerHTML = saldoAlDia;
// 	// 		} else {
// 	// 			// Set the text content of the span element
// 	// 			ccmovSaldoDia.textContent = '-';
// 	// 		}
// 	// 	},
// 	// 	error: function (jqXHR, textStatus, errorThrown) {
// 	// 		// Manejar errores si es necesario
// 	// 		console.log('error', jqXHR, textStatus, errorThrown);
// 	// 	},
// 	// });

// 	var idFicha = $('#id_ficha').val();
// 	$.ajax({
// 		url: 'components/propiedad/models/prueba.php?idFicha=' + idFicha,
// 		method: 'GET',
// 		success: function (data) {

// 			console.log("data de prueba: "+ data);
// 			// Limpiar la tabla antes de llenarla
// 			// var tableBody = $('#cc-movimientos-propiedad tbody');
// 			// tableBody.empty(); // Limpiar el contenido anterior

// 			// $.each(data, function (index, item) {

// 			// 	// Agregar una fila a la tabla
// 			// 	tableBody.append(
// 			// 		'<tr>' +
// 			// 		'<td>' + item.id_ficha_arriendo + '</td>' +
// 			// 		'<td>' + item.fecha_movimiento + '</td>' +
// 			// 		'<td>' + item.monto2 + '</td>' +
// 			// 		'</tr>'
// 			// 	);
// 			// });

// 			// Inicializar DataTables
// 			$('#cc-movimientos-propiedad').DataTable();
// 		},
// 		error: function (xhr, status, error) {
// 			console.error('Error en la solicitud:', error);
// 		}
// 	});
// }

// ahora se llena de una nueva manera esta tabla por que se creo una funcion sql para ello
// jhernandez

// function cargarCCMovimientosList() {
// 	console.log('CARGA LISTA MOVIMIENTOS');
// 	var idFicha = $('#id_ficha').val();

// 	$('#cc-movimientos-propiedad').DataTable({

// 		dom: 'B<"clear">lfrtip',
// 		destroy: true,
// 		targets: 'no-sort',
// 		bSort: false,
// 		order: [[0, 'desc']],
// 		pagingType: 'full_numbers', // Tipo de paginación
// 		pageLength: 10, // Número de filas por página
// 		lengthMenu: [
// 			[10, 25, 50, 100, 5000],
// 			[10, 25, 50, 100, 'Todos'],
// 		],

// 		// "columnDefs": [ { orderable: false, targets: [9] } ],
// 		columnDefs: [
// 			{
// 				render: (data, type, row) => {
// 					return formateoNulos(moment(data).format('DD-MM-YYYY HH:mm'));
// 				},
// 				targets: 0,
// 			},
// 			{
// 				render: (data, type, row) => {
// 					return formateoNulos(data);
// 				},
// 				targets: 1,
// 			},
// 			{
// 				render: (data, type, row) => {
// 					return (
// 						"<span class='d-flex justify-content-end'>" +
// 						formateoNulos(formateoDivisa(data)) +
// 						'</span>'
// 					);
// 				},
// 				targets: 2,
// 			},
// 			{
// 				render: (data, type, row) => {
// 					return (
// 						"<span class='text-danger  d-flex justify-content-end'>" +
// 						formateoNulos(formateoDivisa(data)) +
// 						'</span>'
// 					);
// 				},
// 				targets: 3,
// 			},
// 			{
// 				render: (data, type, row) => {
// 					return data >= 0
// 						? "<strong class='d-flex justify-content-end'>" +
// 						formateoNulos(formateoDivisa(data)) +
// 						'</strong>'
// 						: "<strong class='text-danger  d-flex justify-content-end'>" +
// 						formateoNulos(formateoDivisa(data)) +
// 						'</strong>';
// 				},
// 				targets: 4,
// 			},
// 		],

// 		ajax: {
// 			url:
// 				'components/propiedad/models/listado_cc_movimientos_procesa.php?idFicha=' +
// 				idFicha,
// 			type: 'POST',
// 		},

// 		language: {
// 			lengthMenu: 'Mostrar _MENU_ registros por página',
// 			zeroRecords: 'No se encontraron registros',
// 			info: 'Mostrando página _PAGE_ de _PAGES_',
// 			infoEmpty: 'No existen registros para mostrar',
// 			infoFiltered: '(filtrado desde _MAX_ total de registros)',
// 			loadingRecords: 'Cargando...',
// 			processing: 'Procesando...',
// 			search: 'Buscar',
// 			paginate: {
// 				first: 'Primero',
// 				last: 'Último',
// 				next: 'Siguiente',
// 				previous: 'Anterior',
// 			},
// 			buttons: {
// 				copy: 'Copiar',
// 			},
// 		},

// 	});

// 	$('#cc-movimientos-propiedad').on('init.dt', function () {
// 		console.log(
// 			'DataTables se ha inicializado correctamente en #cc-movimientos-propiedad'
// 		);
// 	});
// }

// function cargarCCMovimientosList() {
//     console.log('CARGA LISTA MOVIMIENTOS');
//     var idFicha = $('#id_ficha').val();

//     $('#cc-movimientos-propiedad').DataTable({
//         dom: 'B<"clear">lfrtip',
//         destroy: true,
//         order: [[0, 'desc']],
//         pagingType: 'full_numbers',
//         pageLength: 10,
//         lengthMenu: [
//             [10, 25, 50, 100, 5000],
//             [10, 25, 50, 100, 'Todos'],
//         ],
//         columnDefs: [
//             {
//                 render: (data, type, row) => {
//                     return formateoNulos(moment(data[2]).format('DD-MM-YYYY HH:mm'));
//                 },
//                 targets: 0,
//             },
//             {
//                 render: (data, type, row) => {
//                     return formateoNulos(data[6]); // Razon
//                 },
//                 targets: 1,
//             },
//             {
//                 render: (data, type, row) => {
//                     return (
//                         "<span class='d-flex justify-content-end'>" +
//                         formateoNulos(formateoDivisa(data[5])) + // Monto
//                         '</span>'
//                     );
//                 },
//                 targets: 2,
//             },
//             {
//                 render: (data, type, row) => {
//                     return (
//                         "<span class='text-danger d-flex justify-content-end'>" +
//                         formateoNulos(formateoDivisa(data[5])) + // Monto
//                         '</span>'
//                     );
//                 },
//                 targets: 3,
//             },
//             {
//                 render: (data, type, row) => {
//                     const monto = parseFloat(data[5]);
//                     return (
//                         "<strong class='d-flex justify-content-end'>" +
//                         formateoNulos(formateoDivisa(monto)) +
//                         '</strong>'
//                     );
//                 },
//                 targets: 4,
//             },
//         ],
//         ajax: {
//             url: 'components/propiedad/models/listado_cc_movimientos_procesa.php?idFicha=' + idFicha,
//             type: 'POST',
//             dataSrc: function (json) {
//                 // Aquí puedes procesar el JSON si es necesario
//                 console.log(json);
// 				//return json; // Asegúrate de que esto devuelva el array correcto
//             },
//         },
//         language: {
//             lengthMenu: 'Mostrar _MENU_ registros por página',
//             zeroRecords: 'No se encontraron registros',
//             info: 'Mostrando página _PAGE_ de _PAGES_',
//             infoEmpty: 'No existen registros para mostrar',
//             infoFiltered: '(filtrado desde _MAX_ total de registros)',
//             loadingRecords: 'Cargando...',
//             processing: 'Procesando...',
//             search: 'Buscar',
//             paginate: {
//                 first: 'Primero',
//                 last: 'Último',
//                 next: 'Siguiente',
//                 previous: 'Anterior',
//             },
//             buttons: {
//                 copy: 'Copiar',
//             },
//         },
//     });

//     $('#cc-movimientos-propiedad').on('init.dt', function () {
//         console.log('DataTables se ha inicializado correctamente en #cc-movimientos-propiedad');
//     });
// }

function cargarCCMovimientoSaldoActual() {
	// var idFicha = $('#id_ficha').val();
	// $.ajax({
	// 	url: 'components/propiedad/models/prueba.php', // Cambia esto a la ruta de tu script PHP
	// 	method: 'GET',
	// 	dataType: 'json',
	// 	success: function(response) {
	// 		if (response.status === 'success') {
	// 			// Parsear el string JSON
	// 			var data = JSON.parse(response.data[0].fn_saldos_cuenta_corriente);
	// 			// Destruir la instancia anterior de DataTable si existe
	// 			if ($.fn.DataTable.isDataTable('#cc-movimientos-propiedad')) {
	// 				$('#cc-movimientos-propiedad').DataTable().clear().destroy();
	// 			}
	// 			// Inicializar DataTables
	// 			$('#cc-movimientos-propiedad').DataTable({
	// 				data: data,
	// 				columns: [
	// 					// { title: "ID CC", data: "idcc" },
	// 					// { title: "ID Ficha Arriendo", data: "id_ficha_arriendo" },
	// 					{ title: "Fecha Movimiento", data: "fecha_movimiento" },
	// 					{ title: "Hora Movimiento", data: "hora_movimiento" },
	// 					//{ title: "ID Tipo Movimiento Cta Cte", data: "id_tipo_movimiento_cta_cte" },
	// 					{ title: "Razón", data: "razon" },
	// 					{ title: "Monto 1", data: "monto1" },
	// 					//{ title: "ID Tipo Movimiento", data: "id_tipo_movimiento" },
	// 					{ title: "Monto 2", data: "monto2" },
	// 					{ title: "Saldo", data: "saldo" }
	// 				]
	// 			});
	// 		} else {
	// 			console.error(response.message);
	// 			// Manejar el error
	// 		}
	// 	},
	// 	error: function(xhr, status, error) {
	// 		console.error('Error en la solicitud:', error);
	// 	}
	// });
}

function cargarCCMovimientosList() {
	var idFicha = $('#id_ficha').val();

	$.ajax({
		url: 'components/propiedad/models/cuentas_corrientes.php?id=' + idFicha,
		method: 'GET',
		dataType: 'json',
		success: function (response) {
			console.log('Respuesta de la función: ', response);

			// Destruir la instancia anterior de DataTable si existe
			if ($.fn.DataTable.isDataTable('#cc-movimientos-propiedad')) {
				$('#cc-movimientos-propiedad').DataTable().clear().destroy();
			}

			if (response && response.length > 0) {
				// Inicializar DataTables directamente con el array de datos
				$('#cc-movimientos-propiedad').DataTable({
					data: response,
					columns: [
						{ title: 'Fecha Movimiento', data: 'fecha_movimiento' },
						{ title: 'Hora Movimiento', data: 'hora_movimiento' },
						{ title: 'Razón', data: 'razon' },
						{
							title: 'Abono',
							data: 'monto1',
							render: function (data) {
								return formatCurrency(data);
							},
						},
						{
							title: 'Cargo',
							data: 'monto2',
							render: function (data) {
								return formatCurrency(data);
							},
						},
						{
							title: 'Saldo',
							data: 'saldo',
							render: function (data) {
								return formatCurrency(data);
							},
						},
						{
							title: 'Acciones',
							data: null,
							orderable: false,
							searchable: false,
							render: function (data, type, row) {
								// Determinamos si se deshabilita el botón
								const isDisabled = row.elimina === 0 ? 'disabled' : '';

								return `
                                    <button 
                                        type="button" 
                                        class="btn btn-danger m-0 mx-3" 
                                        style="padding: .5rem;" 
                                        title="Eliminar" 
                                        onclick="eliminarMovimiento(${row.idcc}, ${row.elimina})" 
                                        ${isDisabled}
                                    >
                                        <i class="fa-regular fa-trash-can px-1" style="font-size: .75rem;"></i>
                                    </button>
                                `;
							},
						},
					],
					ordering: false,
					language: {
						sProcessing: 'Procesando...',
						sLengthMenu: 'Mostrar _MENU_ registros',
						sZeroRecords: 'No se encontraron resultados',
						sEmptyTable: 'No hay datos disponibles en la tabla',
						sInfo:
							'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
						sInfoEmpty:
							'Mostrando registros del 0 al 0 de un total de 0 registros',
						sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
						sSearch: 'Buscar:',
						oPaginate: {
							sFirst: 'Primero',
							sLast: 'Último',
							sNext: 'Siguiente',
							sPrevious: 'Anterior',
						},
					},
					dom: 'Bfrtip',
					buttons: [
						{
							extend: 'excelHtml5',
							text: '<i class="fas fa-file-excel"></i> Descargar Excel',
							title: 'Movimientos de Cuenta Corriente',
							className: 'btn btn-success',
							exportOptions: {
								format: {
									body: function (data) {
										return data; // Exportar los datos tal como están
									},
								},
							},
						},
					],
				});
			} else {
				// Si no hay datos, mostrar un mensaje en la tabla
				$('#cc-movimientos-propiedad').DataTable({
					data: [],
					columns: [
						{ title: 'Fecha Movimiento', data: '' },
						{ title: 'Hora Movimiento', data: '' },
						{ title: 'Razón', data: '' },
						{ title: 'Abono', data: '' },
						{ title: 'Cargo', data: '' },
						{ title: 'Saldo', data: '' },
						{ title: 'Acciones', data: '' },
					],
					ordering: false,
					language: {
						sProcessing: 'Procesando...',
						sLengthMenu: 'Mostrar _MENU_ registros',
						sZeroRecords: 'No se encontraron resultados',
						sEmptyTable: 'No hay datos disponibles en la tabla',
						sInfo:
							'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
						sInfoEmpty:
							'Mostrando registros del 0 al 0 de un total de 0 registros',
						sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
						sSearch: 'Buscar:',
						oPaginate: {
							sFirst: 'Primero',
							sLast: 'Último',
							sNext: 'Siguiente',
							sPrevious: 'Anterior',
						},
					},
					createdRow: function (row) {
						$('td', row)
							.eq(0)
							.attr('colspan', 7)
							.html('No hay resultados disponibles')
							.css({
								'text-align': 'center',
							});
					},
				});
			}
		},
		error: function (xhr, status, error) {
			console.error('Error en la solicitud: ', error);
		},
	});
}

// Función para manejar la eliminación de un registro
function eliminarMovimiento(idcc, elimina) {
	//Validacion elimina (0 = no se puede eliminar, 1 = se puede eliminar)

	if (elimina == 0) {
		Swal.fire({
			title: 'Acción no permitida',
			text: 'El movimiento no puede ser eliminado.',
			icon: 'info',
		});
		return;
	}

	// Aquí puedes realizar una solicitud AJAX al servidor para cambiar el estado del movimiento
	$.ajax({
		url: 'components/propiedad/models/eliminar_movimiento.php',
		method: 'POST',
		data: { idcc: idcc },
		success: function (response) {
			Swal.fire({
				title: 'Movimiento eliminado',
				icon: 'success',
			});
			cargarCCMovimientosList();
		},
		error: function (xhr, status, error) {
			Swal.fire({
				title: 'Error al eliminar el movimiento',
				text: 'El movimiento no pudo ser eliminado',
				icon: 'error',
			});
		},
	});
}

function formatCurrency(value) {
	if (!value) return '$0.00';
	return `$${parseFloat(value).toFixed(2).toLocaleString()}`;
}

function guardarInfoComentario() {
	var formData = new FormData(document.getElementById('comentario_formulario'));
	console.log('Entrando comentario');

	var jsonInformacionNueva = obtenerValoresFormulario('comentario_formulario');

	const comentarioEditar_input = document.getElementById('ComentarioIngreso');
	var ComentarioIngreso = comentarioEditar_input.value;

	if (ComentarioIngreso == null || ComentarioIngreso == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar un comentario',
			icon: 'warning',
		});
		return;
	}

	formData.append('ComentarioIngreso', ComentarioIngreso);

	var id_ficha = $('#id_ficha').val();
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	//console.log(parametros.get("token"));
	formData.append('token', parametros.get('token'));

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/propiedad/models/insert_comentario.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			console.log('RESULTADO INSERCIÓN COMENTARIO: ', res);
			$('#modalInfoComentarioIngreso').modal('hide');
			$('#comentario_formulario')[0].reset();

			Swal.fire({
				title: 'Comentario registrado',
				text: 'El comentario se registro correctamente',
				icon: 'success',
			});
			var id_comentario = res;
			var jsonInformacioantigua = capturarInformacionAntigua();

			cargarInfoComentario();
			registroHistorial(
				'Crear',
				'',
				jsonInformacionNueva,
				'Comentario',
				id_ficha,
				id_comentario
			);
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			$('#modalInfoComentarioIngreso').modal('hide');

			Swal.fire({
				title: 'Atención',
				text: 'El comentario no se registro',
				icon: 'warning',
			});
		});
	$('#comentario_formulario')[0].reset();
	$('#modalInfoComentarioIngreso').modal('hide');
	cargarInfoComentario();
}

function cargarInfoComentario() {
	// Realizar la solicitud AJAX para obtener los datos
	var idFicha = $('#id_ficha').val();
	$.ajax({
		url: 'components/propiedad/models/listado_info_comentarios.php',
		type: 'POST',
		dataType: 'json',
		data: { idFicha: idFicha },
		cache: false,
		success: function (data) {
			console.log('DATA: ', data);
			if (data != null) {
				var tbody = $('#info-comentarios tbody');
				// Limpiar el cuerpo de la tabla por si hay datos anteriores
				tbody.empty();
				// Iterar sobre los datos y agregar filas a la tabla
				$.each(data, function (index, item) {
					// Crear una nueva fila de la tabla
					var newRow = $('<tr>');

					// Agregar celdas a la fila con los datos
					newRow.append('<td>' + formateoNulos(item.comentario) + '</td>');
					newRow.append(
						'<td>' + formateoNulos(item.fecha_comentario) + '</td>'
					);
					if (!item?.nombre_usuario) {
						newRow.append('<td>-</td>');
					} else if (
						item.fecha_modificacion != null &&
						item.fecha_modificacion != ''
					) {
						newRow.append(
							'<td>' +
								formateoNulos(item.fecha_comentario) +
								"  <i class='fa-solid fa-circle-info' data-bs-toggle='tooltip' data-bs-placement='top' title='Modificado por : " +
								item.nombre_usuario +
								"'></i></td>"
						);
					} else {
						newRow.append('<td>-</td>');
					}
					newRow.append('<td>' + formateoNulos(item.nombre_usuario) + '</td>');

					newRow.append(
						`<td>
							<div class='d-flex' style='gap: .5rem;'>
							
							<a 
								data-bs-toggle='modal' 
								data-bs-target='#modalEditarInfoComentario' 
								type='button' 
									onclick='cargarInfoComentarioEditar(${item.id}, "${item.comentario}")' 
									class='btn btn-info m-0 d-flex' 
								style='padding: .5rem;' 
								aria-label='Editar' 
								title='Editar'
							>
								<i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i>
							</a>
							
							<button onclick='eliminarInfoComentario(${item.id})' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'>
								<i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
							</button>
							</div>
						</td>`
					);
					// Agregar la fila al cuerpo de la tabla
					tbody.append(newRow);
				});
			} else {
				var tbody = $('#info-comentarios tbody');
				tbody.empty();
				var newRow = $('<tr>');
				console.log('error');
				newRow.append(
					"<td colspan='9' style='text-align:center'> No hay Comentarios</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			console.log('error', jqXHR, textStatus, errorThrown);
		},
	});
}

function eliminarInfoComentario(idInfoComentario) {
	console.log('idInfoComentario: ', idInfoComentario);

	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Una vez eliminado, no podrás recuperar este comentario',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Eliminar',
		denyButtonText: 'Cancelar',
	}).then((result) => {
		if (result.isConfirmed) {
			// Si el usuario hace clic en "Eliminar"
			$.ajax({
				url: 'components/propiedad/models/delete_info_comentario.php',
				type: 'POST',
				dataType: 'text',
				data: { idInfoComentario: idInfoComentario },
				success: function (response) {
					Swal.fire({
						title: 'Comentario eliminado',
						text: 'El comentario se eliminó correctamente',
						icon: 'success',
					});
					cargarInfoComentario();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.error('Error en la solicitud:', textStatus, errorThrown);
					// Aquí puedes mostrar un mensaje de error al usuario o realizar otras acciones
				},
			});
		} else if (result.isDenied) {
			// Si el usuario hace clic en "Cancelar"
			// Aquí puedes cerrar el modal de SweetAlert si lo deseas
			Swal.close();
		}
	});
}

function editarInfoComentario() {
	var formData = new FormData(
		document.getElementById('comentario_formulario_editar')
	);
	var jsonInformacionNueva = obtenerValoresFormulario(
		'comentario_formulario_editar'
	);
	// Decodificar el texto JSON a un array asociativo
	var objeto_json = JSON.parse(jsonInformacionNueva);

	console.log('objeto_json editar info comentario: ', objeto_json);

	var id_comentario = objeto_json.ID_Info_Comentario_Editar;
	var url = window.location.href;
	//console.log(url);
	var id_ficha = $('#id_ficha').val();
	var parametros = new URL(url).searchParams;

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/propiedad/models/editar_info_comentario.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			Swal.fire({
				title: 'Comentario actualizado',
				text: 'El comentario se actualizó correctamente',
				icon: 'success',
			});
			var jsonInformacioantigua = capturarInformacionAntigua();
			cargarInfoComentario();
			registroHistorial(
				'Modificar',
				jsonInformacioantigua,
				jsonInformacionNueva,
				'Comentario',
				id_ficha,
				id_comentario
			);
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			Swal.fire({
				title: 'Atención',
				text: 'El comentario no se actualizó',
				icon: 'warning',
			});
		});

	$('#comentario_formulario_editar')[0].reset();
	$('#modalEditarInfoComentario').modal('hide');
	cargarInfoComentario();
}

function cargarInfoComentarioEditar(idInfoComentario, comentario) {
	console.log('PARAMETROS ENTRADA: ', { idInfoComentario, comentario });
	console.log('ESTOY EN cargarInfoComentarioEditar');
	$('#ComentarioEditar').val(comentario);
	$('#ID_Info_Comentario_Editar').val(idInfoComentario);
}

function validaDFL2(valor) {
	console.log('valor', valor.value);

	if (valor.value == 'true') {
		Swal.fire({
			title: 'Aviso',
			text: 'Por favor verificar que el valor del M2 concuerde con DFL2',
			icon: 'warning',
		});
	}
}

function avisoPropiedad() {
	Swal.fire({
		title: 'Aviso',
		text: 'La propiedad se encuentra con un arriendo activo en el sistema por lo que no se puede eliminar',
		icon: 'warning',
	});
}

// actualizacion de generar excel propiedad
// <!-- actualizacion de generar excel propiedad -->
function loadPropiedad_List() {
	$(document).ready(function () {
		var propietario = $('#propietario').val() || '';
		var ejecutivo = $('#ejecutivo').val() || '';
		var filtro_codigo_propiedad = $('#codigo_propiedad').val() || '';
		var filtro_direccion = $('#filtro_direccion').val() || '';
		var filtro_sucursal = $('#filtro_sucursal').val() || '';
		var tipoPropiedad = $('#tipoPropiedad').val() || '';
		var estadoPropiedad = $('#estadoPropiedad').val() || '';
		var region = $('#region').val() || '';
		var comuna = $('#comuna').val() || '';
		var direccion = $('#direccion').val() || '';

		var ajaxUrl =
			'components/propiedad/models/propiedad_list_procesa.php?' +
			'propietario=' +
			encodeURIComponent(propietario) +
			'&ejecutivo=' +
			encodeURIComponent(ejecutivo) +
			'&filtro_direccion=' +
			encodeURIComponent(filtro_direccion) +
			'&filtro_sucursal=' +
			encodeURIComponent(filtro_sucursal) +
			'&tipoPropiedad=' +
			encodeURIComponent(tipoPropiedad) +
			'&estadoPropiedad=' +
			encodeURIComponent(estadoPropiedad) +
			'&region=' +
			encodeURIComponent(region) +
			'&comuna=' +
			encodeURIComponent(comuna) +
			'&codigo_propiedad=' +
			encodeURIComponent(filtro_codigo_propiedad) +
			'&direccion=' +
			encodeURIComponent(direccion);

		if ($.fn.DataTable.isDataTable('#propiedades')) {
			$('#propiedades').DataTable().ajax.url(ajaxUrl).load();
		} else {
			$('#propiedades').DataTable({
				order: [[0, 'desc']],
				processing: true,
				serverSide: true,
				searching: false, // Desactiva el buscador
				pageLength: 25,
				ajax: {
					url: ajaxUrl,
					type: 'POST',
					dataSrc: function (json) {
						if (!json || !json.data || json.data.length === 0) {
							return []; // Regresa un arreglo vacío si no hay datos
						}
						return json.data; // Si hay datos, pásalos a la tabla
					},
				},
				language: {
					emptyTable: 'No hay datos disponibles para la sucursal seleccionada.',
					processing:
						'<div class=" text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>',
				},
				drawCallback: function () {
					console.log('Tabla actualizada correctamente');
					$('#propiedades_processing').hide();
				},
				dom: 'Bfrtip', // Permite agregar los botones de exportación
				buttons: [
					{
						extend: 'excelHtml5',
						title: 'Listado de Propiedades',
						text: 'Descargar Excel',
						exportOptions: {
							columns: ':visible', // Exporta solo las columnas visibles
						},
					},
				],
			});
		}
	});
}

var tablaPropiedades; // Variable global para almacenar la instancia de DataTables
/*
function filtroPropiedadList(){
  	
   $(document).ready(function() {
	var propietario = document.getElementById("propietario")?.value ?? '';
		var ejecutivo = document.getElementById("ejecutivo")?.value ?? ''; 
	var filtro_codigo_propiedad = document.getElementById("codigo_propiedad")?.value ?? ''; 
	var filtro_direccion = document.getElementById("filtro_direccion")?.value ?? '';
	var filtro_sucursal = document.getElementById("filtro_sucursal")?.value ?? '';
	var tipoPropiedad = document.getElementById("tipoPropiedad")?.value ?? '';
	var estadoPropiedad = document.getElementById("estadoPropiedad")?.value ?? '';
	var region = document.getElementById("region")?.value ?? '';
	var comuna = document.getElementById("comuna")?.value ?? '';

	console.log("propietario_enviarFiltros: ",propietario);
		  $('#propiedades').DataTable({
	  "dom": 'B<"clear">lfrtip',  
	  "destroy": true, 
			"pagingType": "full_numbers", // Tipo de paginación
			"pageLength": 10, // Número de filas por página
	  "lengthMenu": [[10, 25, 50, 100, 5000], [10, 25, 50, 100, "Todos"]],
	  "columnDefs": [ { orderable: false, targets: [9] } ],
	  "ajax": {
		"url":"components/propiedad/models/propiedad_list_procesa.php?propietario="+propietario+"&ejecutivo="+ejecutivo+"&filtro_direccion="+filtro_direccion+"&filtro_sucursal="+filtro_sucursal+"&tipoPropiedad="+tipoPropiedad+"&region="+region+"&comuna="+comuna+"&codigo_propiedad="+filtro_codigo_propiedad,
		"type": "POST"},
	  "language": {
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No encontrado",
		"info": "Mostrando página _PAGE_ de _PAGES_",
		"infoEmpty": "No existen registros para mostrar",
		"infoFiltered": "(filtrado desde _MAX_ total de registros)",
		"loadingRecords": "Cargando...",
		"processing":     "Procesando...",
		"search":     "Buscar",
		"paginate": {
		  "first":      "Primero",
		  "last":       "Último",
		  "next":       "Siguiente",
		  "previous":   "Anterior"
		},
		"buttons": {
		  "copy":      "Copiar"
		},
	  }
		});
  	
  	
	});

$('#busqueda-form').on('submit', function(e) {
	e.preventDefault(); // Evitar el envío del formulario

	// Realizar la búsqueda utilizando los valores del formulario
	// Aquí podrías obtener los valores de los campos de búsqueda y enviarlos al servidor para filtrar los resultados

	// Una vez que tengas los resultados de la búsqueda, llamar a ajax.reload() para actualizar la tabla
	tablaPropiedades.ajax.reload();
});
	
}
/*
function filtroPropiedadList(propietario){
  /*
	  if ($.fn.DataTable.isDataTable('#propiedades')) {
		// Si ya existe, destruir la instancia existente antes de crear una nueva
		tablaPropiedades.destroy();
	}
   $(document).ready(function() {
		 $('#propiedades').DataTable({
	  "destroy": true, 
			"pagingType": "full_numbers", // Tipo de paginación
			"pageLength": 10, // Número de filas por página
	  "lengthMenu": [[10, 25, 50, 100, 5000], [10, 25, 50, 100, "Todos"]],
	  "columnDefs": [ { orderable: false, targets: [9] } ],
	  "ajax": {
		"url":"components/propiedad/models/propiedad_list_procesa.php?propietario="+propietario+"&ejecutivo=",
		"type": "POST"},
	  "language": {
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No encontrado",
		"info": "Mostrando página _PAGE_ de _PAGES_",
		"infoEmpty": "No existen registros para mostrar",
		"infoFiltered": "(filtrado desde _MAX_ total de registros)",
		"loadingRecords": "Cargando...",
		"processing":     "Procesando...",
		"search":     "Buscar",
		"paginate": {
		  "first":      "Primero",
		  "last":       "Último",
		  "next":       "Siguiente",
		  "previous":   "Anterior"
		},
		"buttons": {
		  "copy":      "Copiar"
		},
	  }
		});
	});
	
}
*/
function enviarRentdesk() {
	var ejecutivo = $('#selectEjecutivos').val();

	if (ejecutivo) {
		var formData = new FormData(
			document.getElementById('formulario-propiedad')
		);

		const token_propiedad_defecto_input = document.getElementById(
			'token_propiedad_defecto'
		);
		var token_propiedad_defecto = token_propiedad_defecto_input.value;
		formData.append('token_propiedad_defecto', token_propiedad_defecto);

		$.ajax({
			url: 'components/propiedad/models/insert_update.php',
			type: 'post',
			dataType: 'html',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
		}).done(function (res) {
			var retorno = res.split(',xxx,');
			var resultado = retorno[1];
			var mensaje = retorno[2];
			var token = retorno[3];
			console.log('res', res);

			if (resultado == 'OK') {
				Swal.fire({
					title: ' Propiedad Guardada',
					text: '',
					icon: 'success',
					showConfirmButton: true,
					allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
					willClose: () => {
						// Redireccionar a otra página cuando la alerta se cierre

						document.location.reload();
						//window.location.href = "index.php?component=propiedad&view=propiedad_list";
					},
				}).then((result) => {
					// Verificar si el usuario confirmó la alerta
					if (result.isConfirmed) {
						// Redireccionar a otra página si se confirma la alerta
						document.location.reload();
						//window.location.href = "index.php?component=propiedad&view=propiedad_list";
					}
				});
			} else {
				//$.showAlert({ title: "Error", body: mensaje });
				Swal.fire({
					title: '¡La propiedad no fue registrada!',
					text: mensaje,
					icon: 'warning',
					showConfirmButton: true,
					allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
				}).then((result) => {
					// Verificar si el usuario confirmó la alerta
				});
				return false;
			}
		});
	} else {
		Swal.fire({
			title: 'Debes seleccionar un ejecutivo',
			text: 'Selecciona  un ejecutivo de la lista.',
			icon: 'warning',
		});
	}
} //function enviarRentdesk

function enviar() {
	var formData = new FormData(document.getElementById('formulario-propiedad'));

	console.log('formData a enviar propiedad: ', formData);
	return;
	var archivo = $('#archivo').val();
	var archivo_bd = $('#archivo_bd').val();

	if (archivo == '' && archivo_bd == 'N') {
		$.showAlert({ title: 'Atención', body: 'Debe Adjuntar el mandato' });
		return;
	}

	if (archivo) {
		console.log('EXISTE ARCHIVO');
		$.showAlert({ title: 'Atención', body: 'Debe Adjuntar el mandato' });
		return;
	}

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	var formData = new FormData(document.getElementById('formulario-propiedad'));

	$.ajax({
		url: 'components/propiedad/models/insert_update.php',
		type: 'post',
		dataType: 'html',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		var retorno = res.split(',xxx,');
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var token = retorno[3];

		if (resultado == 'OK') {
			$.showAlert({ title: 'Atención', body: mensaje });
			document.location.href =
				'index.php?component=propiedad&view=propiedad&token=' + token;
			return false;
		} else {
			$.showAlert({ title: 'Error', body: mensaje });
			return false;
		}
	});
} //function enviar

//Desde acá código para Datatable listado
//*****************************************************************************************
function loadPropiedad() {
	$(document).ready(function () {
		$('#propiedades').DataTable({
			order: [[0, 'asc']],
			processing: true,
			serverSide: true,
			pageLength: 25,
			columnDefs: [{ orderable: false, targets: [6, 7, 8, 9, 10] }],
			ajax: {
				url: 'components/propiedad/models/propiedad_list_procesa.php',
				type: 'POST',
			},
			language: {
				lengthMenu: 'Mostrar _MENU_ registros por página',
				zeroRecords: 'No encontrado',
				info: 'Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)',
				infoEmpty: 'Sin resultados',
				infoFiltered:
					' <strong>Total de registros filtrados: _TOTAL_ </strong>',
				loadingRecords: 'Cargando...',
				search: 'buscar: ',
				processing: 'Procesando...',
				paginate: {
					first: 'Primero',
					last: 'Último',
					next: 'siguiente',
					previous: 'anterior',
				},
			},
		});

		$('div.dataTables_filter input').unbind(); // se desactiva la busqueda al presionar una tecla

		$(
			"<div id='divbotonbuscar' ><i id='buscar' class='fas fa-search'></i></div>"
		).insertBefore('.dataTables_filter input');

		//Para realizar la búsqueda al hacer click en el botón
		$('#buscar').click(function (e) {
			var table = $('#propiedades').DataTable();
			table.search($('div.dataTables_filter input').val()).draw();
			//mostrar u ocultar botón para resetear las búsquedas y orden
		}); //$('#buscar').click(function(e){
	}); //$(document).ready(function()
} //function loadUsers()

//************************************************************************
function deletePropiedad(token) {
	$.showConfirm({
		title: 'Por Favor Confirme.',
		body: 'Realmente desea Eliminar El registro? No se puede deshacer.',
		textTrue: 'Si',
		textFalse: 'No',
		onSubmit: function (result) {
			if (result) {
				$.ajax({
					type: 'POST',
					url: 'components/propiedad/models/delete.php',
					data: 'token=' + token,
					success: function (res) {
						var retorno = res.split(',xxx,');
						var resultado = retorno[1];
						var mensaje = retorno[2];
						var token = retorno[3];

						if (resultado == 'OK') {
							$.showAlert({ title: 'Atención', body: mensaje });
							document.location.reload();
							return false;
						} else {
							$.showAlert({ title: 'Error', body: mensaje });
							return false;
						}
					},
				});
			} else {
				//nada
			}
		},
		onDispose: function () {
			//nada
		},
	});
}

//**************************************************************************************************************

//*****************************************************************************************
function loadPropietarios(token, participacion) {
	$(document).ready(function () {
		$('#propiedades').DataTable({
			order: [[1, 'asc']],
			processing: true,
			serverSide: true,
			pageLength: 10,
			columnDefs: [{ orderable: false, targets: [6] }],
			ajax: {
				url:
					'../models/listado_propietarios.php?token_propiedad=' +
					token +
					'&participacion=' +
					participacion,
				type: 'POST',
			},
			language: {
				lengthMenu: 'Mostrar _MENU_ registros por página',
				zeroRecords: 'No encontrado',
				info: 'Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)',
				infoEmpty: 'Sin resultados',
				infoFiltered:
					' <strong>Total de registros filtrados: _TOTAL_ </strong>',
				loadingRecords: 'Cargando...',
				search: 'buscar: ',
				processing: 'Procesando...',
				paginate: {
					first: 'Primero',
					last: 'Último',
					next: 'siguiente',
					previous: 'anterior',
				},
			},
		});

		$('div.dataTables_filter input').unbind(); // se desactiva la busqueda al presionar una tecla

		$(
			"<div id='divbotonbuscar' ><i id='buscar' class='fas fa-search'></i></div>"
		).insertBefore('.dataTables_filter input');

		//Para realizar la búsqueda al hacer click en el botón
		$('#buscar').click(function (e) {
			var table = $('#propiedades').DataTable();
			table.search($('div.dataTables_filter input').val()).draw();
			//mostrar u ocultar botón para resetear las búsquedas y orden
		}); //$('#buscar').click(function(e){
	}); //$(document).ready(function()
} //function loadUsers()

function agregarPropietario(token, token_propiedad, participacion_ant) {
	console.log(' participacion_ant --> ' + participacion_ant);
	var part_max = 100 - participacion_ant;
	$.showModal({
		title: 'Ingrese % Participación',
		body:
			'<form><div class="form-group row">' +
			'<div class="col-9"><label for="text" class="col-form-label">% Participacion (Máximo ' +
			part_max +
			'%)</label></div>' +
			'<div class="col-3"><input type="number" maxlength="3" min="1" max="100" required="required" class="form-control" id="participacion"/></div>' +
			'</div></form>',
		footer:
			'<button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Ingresar</button>',
		onCreate: function (modal) {
			// create event handler for form submit and handle values
			$(modal.element).on('click', "button[type='submit']", function (event) {
				event.preventDefault();
				var $form = $(modal.element).find('form');
				var participacion = $form.find('#participacion').val();

				if (!/^([0-9])*$/.test(participacion)) {
					$.showAlert({
						title: 'Atención',
						body: 'El valor ' + participacion + ' no es un número valido',
					});
				} else {
					if (participacion < 1 || participacion > 100) {
						$.showAlert({
							title: 'Atención',
							body: 'El porcentaje debe ser un valor entre 1 y 100 ',
						});
					} else {
						if (participacion > part_max) {
							$.showAlert({
								title: 'Atención',
								body:
									'La suma de las participaciones de cada propietario no puede ser superior al 100%. El maximo a ingresar debe ser = ' +
									part_max,
							});
						} else {
							$.ajax({
								type: 'POST',
								url: '../models/insert_delete_propietario.php',
								data: {
									token: token,
									token_propiedad: token_propiedad,
									participacion: participacion,
									accion: 'I',
								},
								success: function (res) {
									var retorno = res.split(',xxx,');
									var resultado = retorno[1];
									var mensaje = retorno[2];
									if (resultado == 'OK') {
										$.showAlert({ title: 'Atención', body: mensaje });
										modal.hide();
										parent.jQuery.fancybox.close();
										parent.document.location.reload();
										return false;
									} else {
										$.showAlert({ title: 'Error', body: mensaje });
										return false;
									}
								},
							});
						}
					}
				}
			});
		},
	});
}

//************************************************************************
function deletePropietario(token, token_propiedad) {
	$.showConfirm({
		title: 'Por Favor Confirme.',
		body: 'Realmente desea Eliminar El registro? No se puede deshacer.',
		textTrue: 'Si',
		textFalse: 'No',
		onSubmit: function (result) {
			if (result) {
				$.ajax({
					type: 'POST',
					url: 'components/propiedad/models/insert_delete_propietario.php',
					data: {
						token: token,
						token_propiedad: token_propiedad,
						participacion: 0,
						accion: 'D',
					},
					success: function (res) {
						var retorno = res.split(',xxx,');
						var resultado = retorno[1];
						var mensaje = retorno[2];
						var token = retorno[3];

						if (resultado == 'OK') {
							$.showAlert({ title: 'Atención', body: mensaje });
							document.location.reload();
							return false;
						} else {
							$.showAlert({ title: 'Error', body: mensaje });
							return false;
						}
					},
				});
			} else {
				//nada
			}
		},
		onDispose: function () {
			//nada
		},
	});
}

//*****************************************************************************************
function loadCheckIn(token) {
	$(document).ready(function () {
		$('#propiedades').DataTable({
			order: [[0, 'desc']],
			processing: true,
			serverSide: true,
			pageLength: 10,
			columnDefs: [{ orderable: false, targets: [6] }],
			ajax: {
				url: '../models/listado_check_in.php?token_propiedad=' + token,
				type: 'POST',
			},
			language: {
				lengthMenu: 'Mostrar _MENU_ registros por página',
				zeroRecords: 'No encontrado',
				info: 'Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)',
				infoEmpty: 'Sin resultados',
				infoFiltered:
					' <strong>Total de registros filtrados: _TOTAL_ </strong>',
				loadingRecords: 'Cargando...',
				search: 'buscar: ',
				processing: 'Procesando...',
				paginate: {
					first: 'Primero',
					last: 'Último',
					next: 'siguiente',
					previous: 'anterior',
				},
			},
		});

		$('div.dataTables_filter input').unbind(); // se desactiva la busqueda al presionar una tecla

		$(
			"<div id='divbotonbuscar' ><i id='buscar' class='fas fa-search'></i></div>"
		).insertBefore('.dataTables_filter input');

		//Para realizar la búsqueda al hacer click en el botón
		$('#buscar').click(function (e) {
			var table = $('#propiedades').DataTable();
			table.search($('div.dataTables_filter input').val()).draw();
			//mostrar u ocultar botón para resetear las búsquedas y orden
		}); //$('#buscar').click(function(e){
	}); //$(document).ready(function()
} //function loadUsers()

//************************************************************************
function agregarCheckIn(token, token_propiedad) {
	$.showConfirm({
		title: 'Por Favor Confirme.',
		body: 'Realmente desea Asignar el registro?',
		textTrue: 'Si',
		textFalse: 'No',
		onSubmit: function (result) {
			if (result) {
				$.ajax({
					type: 'POST',
					url: '../models/insert_check_in.php',
					data: 'token=' + token + '&token_propiedad=' + token_propiedad,
					success: function (res) {
						var retorno = res.split(',xxx,');
						var resultado = retorno[1];
						var mensaje = retorno[2];
						var token = retorno[3];

						if (resultado == 'OK') {
							$.showAlert({ title: 'Atención', body: mensaje });
							parent.jQuery.fancybox.close();
							parent.document.location.reload();
							return false;
						} else {
							$.showAlert({ title: 'Error', body: mensaje });
							return false;
						}
					},
				});
			} else {
				//nada
			}
		},
		onDispose: function () {
			//nada
		},
	});
}

//************************************************************************
function deleteCheckIn(token, token_propiedad) {
	$.showConfirm({
		title: 'Por Favor Confirme.',
		body: 'Realmente desea Eliminar el registro?',
		textTrue: 'Si',
		textFalse: 'No',
		onSubmit: function (result) {
			if (result) {
				$.ajax({
					type: 'POST',
					url: 'components/propiedad/models/delete_check_in.php',
					data: 'token=' + token + '&token_propiedad=' + token_propiedad,
					success: function (res) {
						var retorno = res.split(',xxx,');
						var resultado = retorno[1];
						var mensaje = retorno[2];
						var token = retorno[3];

						if (resultado == 'OK') {
							$.showAlert({ title: 'Atención', body: mensaje });
							document.location.reload();
							return false;
						} else {
							$.showAlert({ title: 'Error', body: mensaje });
							return false;
						}
					},
				});
			} else {
				//nada
			}
		},
		onDispose: function () {
			//nada
		},
	});
}

//**************************************************************************************************************

//*************************************************************************************************

function validaArchivo(e) {
	console.log('ARCHIVO SUBIDO: ', e);

	$.showAlert({
		title: 'Atención',
		body: 'El Archivo debe ser una imagen, word, excel o pdf.',
	});
	var fileExtension = [
		'jpeg',
		'jpg',
		'png',
		'doc',
		'docx',
		'pdf',
		'xls',
		'xlsx',
	];
	if (
		$.inArray($(e).val().split('.').pop().toLowerCase(), fileExtension) == -1
	) {
		$.showAlert({
			title: 'Atención',
			body: 'El Archivo debe ser una imagen, word, excel o pdf.',
		});
		$(e).val('');
		return false;
	} else {
		return true;
	}
}

//************************************************************************************************

function borrarArchivo(token) {
	$.showConfirm({
		title: 'Por Favor Confirme.',
		body: 'Confirma la eliminación del Archivo. No se puede deshacer.',
		textTrue: 'Si',
		textFalse: 'No',
		onSubmit: function (result) {
			if (result) {
				$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
				$.ajax({
					type: 'POST',
					url: 'components/propiedad/models/borrar_mandato.php',
					data: 'token=' + token,
					success: function (res) {
						var retorno = res.split(',xxx,');
						var resultado = retorno[1];
						var mensaje = retorno[2];
						var token = retorno[3];

						if (resultado == 'OK') {
							$.showAlert({ title: 'Atención', body: mensaje });
							document.location.reload();
							return false;
						} else {
							$.showAlert({ title: 'Error', body: mensaje });
							return false;
						}
					},
				});
			} else {
				//nada
			}
		},
		onDispose: function () {
			//nada
		},
	});
} //function borrarArchivo(token)

$(document).ready(function () {
	// Add change event listener to the select element
	// onChangePersona();
	//Parametro
	//$('#propiedad-ft-co-propietarios-tab').trigger('click');
	// Obtener la URL actual
	console.log('estoy aqui');

	$('#propiedades').on('init.dt', function () {
		console.log('La tabla se ha cargado correctamente.');
		// Ahora puedes acceder a las propiedades de la tabla con seguridad
		var miSelect = document.querySelector('[name="propiedades_length"]');
		console.log('miSelect', miSelect);
		miSelect.id = 'propiedades_length';
		console.log('miSelect despues', miSelect);
	});

	var miSelect = document.querySelector('[name="propiedades_length"]');
	miSelect.id = 'propiedades_length';

	$('select[name="propiedades_length"]').on('change', function () {
		var valorPropiedadesLength = $(this).val();
		console.log('El valor de propiedades_length es:', valorPropiedadesLength);
	});

	const urlParams = new URLSearchParams(window.location.search);

	// Obtener el valor del parámetro 'nav'
	const navValue = urlParams.get('nav');

	// Mostrar el valor del parámetro en la consola
	console.log('navValue', navValue);
	if (navValue == 'propietario') {
		$('#propiedad-ft-co-propietarios-tab').trigger('click');
	}

	// También puedes usar el valor del parámetro en tu lógica
});

function redirectPropietarios() {
	$('#propiedad-ft-co-propietarios-tab').trigger('click');
}

function onChangePersona(token = null) {
	var selectedValue = $('#DNI').val();
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	var token = parametros.get('token');
	$('#section-0').hide();
	$('#section-1').hide();
	$('#section-2').hide();
	$('#section-3').hide();
	$('#section-4').hide();
	$('#section-propietarios').hide();

	if (selectedValue) {
		$('#section-0').show();
		$('#section-1').show();
		$('#section-2').show();
		$('#section-3').show();
		$('#section-4').show();
		$('#section-propietarios').show();
		//$("#section-info-cliente-natural").hide();
		//$("#section-info-cliente-juridico").hide();
		$('#infoPropiedad').hide();
		$('#DNI').attr('disabled', true);
		$('#btDNI').hide();

		if (token) {
			$('#info-cliente').hide();
		}
	}
}

function busquedaDNI() {
	//var formData = dni;

	const input = document.getElementById('DNI');
	let inputPersonaToken = document.getElementById('persona');

	var dni = input.value;
	var dniBuscar = $('#DNI').val();

	if (dniBuscar !== '') {
		$.ajax({
			url: 'components/propiedad/models/busca_dni.php',
			type: 'POST',
			data: 'dni=' + dni,
			success: function (resp) {
				$.unblockUI();
				var retorno = resp.split('||');
				var resultado = retorno[0];
				var mensaje = retorno[2];

				if (resultado == 'ERROR') {
					///$.showAlert({ title: "Atención", body: mensaje });

					if (mensaje === 'persona') {
						// Guardar un valor en sessionStorage
						sessionStorage.setItem('personaDNI', dni);

						// Mostrar SweetAlert2 con un botón de confirmación
						Swal.fire({
							title: 'El cliente no esta registrado',
							text: 'Serás redirigido para crear el cliente en el sistema. Con esto podrás realizar la creación de la propiedad y dejar asociado al cliente como propietario',
							icon: 'info',
							showConfirmButton: true,
							allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
							willClose: () => {
								// Redireccionar a otra página cuando la alerta se cierre
								window.location.href =
									'index.php?component=persona&view=persona';
							},
						}).then((result) => {
							// Verificar si el usuario confirmó la alerta
							if (result.isConfirmed) {
								// Redireccionar a otra página si se confirma la alerta
								window.location.href =
									'index.php?component=persona&view=persona';
							}
						});

						return;
					}

					if (mensaje === 'propietario') {
						sessionStorage.setItem('propietarioDNI', dni);

						Swal.fire({
							title: 'Información',
							text: 'Serás redirigido para asociar al cliente como propietario. Con esto podrás realizar la creación de la propiedad.',
							icon: 'info',
							showConfirmButton: true,
							allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
							willClose: () => {
								// Redireccionar a otra página cuando la alerta se cierre

								window.location.href =
									'index.php?component=propietario&view=propietario';
							},
						}).then((result) => {
							// Verificar si el usuario confirmó la alerta
							if (result.isConfirmed) {
								// Redireccionar a otra página si se confirma la alerta
								window.location.href =
									'index.php?component=propietario&view=propietario';
							}
						});

						return;
					}
				} else {
					var datos = JSON.parse(retorno[3]); // Convierte el string JSON a un objeto
					var id = datos[0].id; // Accede al id del primer objeto en el array

					// document.location.href =
					//   "index.php?component=arrendatario&view=arrendatario&persona=" + mensaje;

					ValidarClienteCuentasBancarias(id)
						.then((flag) => {
							if (flag == true) {
								inputPersonaToken.value = mensaje;
								var personaJson = retorno[3];
								var personaJson = JSON.parse(personaJson);
								inputPersonaToken.value = mensaje;
								console.log(personaJson);
								cargarInfoPersonal(personaJson);
								onChangePersona();
								$('#section-0').show();
								$('#section-1').show();
								$('#section-2').show();
								$('#section-3').show();
								$('#section-4').show();
								$('#bt_aceptar_propiedad').show();
							} else {
								Swal.fire({
									icon: 'info',
									title: 'Información de Cuenta Bancaria Requerida',
									text: 'Por favor, complete los datos de la cuenta bancaria del propietario para poder continuar.',
								});
							}
						})
						.catch((error) => {
							console.error('Error en la validación: ', error);
						});
				}
			},
		});
	} else {
		Swal.fire({
			icon: 'info',
			title: 'Por favor rellene la información',
			text: 'Es necesario rellenar la información para continuar.',
		});
		//$.showAlert({ title: "Atención", body: "Debe escribir un DNI/RUT" });
	}
} //function enviar

document.addEventListener('DOMContentLoaded', function () {
	// busquedaDNI();

	conteoInput('nroComplemento', 'cuentaNumero');
	conteoInput('numeroDepto', 'cuentanumeroDepto');
	conteoInput('coordenadas', 'cuentaCoordenadas');
	conteoInput('rol', 'cuentaRol');
	conteoInput('avaluoFiscal', 'cuentaAvaluo');
	conteoInput('direccion', 'cuentaDireccion');
});

/*Funciones de busqueda*/

function buscarPropiedadAutocompleteB(valor, tipo) {
	var codigo = document.getElementById(tipo).value;

	var caracteres = codigo.length;
	//Si por lo menos ha ingresado 3 caracteres comenzamos a autocompletar
	if (caracteres >= 3) {
		$.ajax({
			type: 'POST',
			url: 'components/propiedad/models/buscar_propiedades_autocomplete.php',
			data: 'codigo=' + codigo + '&tipo=' + tipo,
			success: function (data) {
				$('#suggestions_' + tipo)
					.fadeIn(500)
					.html(data);
				$('.suggest-element').on('click', function () {
					return false;
				});
			},
		});
	} else {
		ocultarAutocomplete(tipo);
	}
}

function ingresaBusqueda(elemento) {
	ocultarAutocomplete('codigo_propiedad');
	var codigo = $(elemento).attr('id');
	console.log(codigo);
	document.getElementById('codigo_propiedad').value = codigo;
	//document.getElementById("codigo").value = $(elemento).text();
}

function ingresaBusquedaPropiedad(elemento) {
	ocultarAutocomplete('codigo_propiedad');
	var codigo = $(elemento).attr('id');
	console.log(codigo);
	document.getElementById('codigo_propiedad').value = codigo;
	//document.getElementById("codigo").value = $(elemento).text();
}

function ocultarAutocomplete(tipo) {
	$('#suggestions_' + tipo).fadeOut(500);
}

//******************************************************************************

function masMenosFiltros() {
	var textoBuscado = 'Menos Filtros';

	var div = $('#btnMasFiltros');

	var contenidoDiv = div.text();

	if (contenidoDiv.indexOf(textoBuscado) !== -1) {
		$('#btnMasFiltros').html("Más Filtros <i class='fas fa-chevron-down'>");
	} else {
		$('#btnMasFiltros').html("Menos Filtros <i class='fas fa-chevron-up'>");
	}
}

function cargarInfoPersonal(infoJSON) {
	if (infoJSON[0].tipo_persona === 'NATURAL') {
		$('#nombrePersona').text(
			infoJSON[0].nombres +
				' ' +
				infoJSON[0].apellido_paterno +
				' ' +
				infoJSON[0].apellido_materno
		);

		$('#telefonoMovilPersona').text(
			infoJSON[0].telefono_fijo + '  ' + infoJSON[0].telefono_movil
		);
		$('#emailPersona').text(infoJSON[0].correo_electronico);
		$('#tipoPersona').text(infoJSON[0].tipo_persona);
		$('#direccionPersona').text(
			infoJSON[0].direccion +
				' #' +
				infoJSON[0].numero +
				', ' +
				infoJSON[0].comuna +
				', ' +
				infoJSON[0].region +
				', ' +
				infoJSON[0].pais
		);
		var urlMaps =
			'https://www.google.com/maps/place/' +
			infoJSON[0].direccion +
			'+%23' +
			infoJSON[0].numero +
			',+' +
			infoJSON[0].comuna +
			',+' +
			infoJSON[0].region +
			',+' +
			infoJSON[0].pais;

		$('#linkMaps').attr('href', urlMaps);
		$('#section-info-cliente-juridico').css('display', 'none');
		$('#section-info-cliente-natural').css('display', 'block');
	} else {
		$('#nombrePersonaJuridica').text(infoJSON[0].nombre_fantasia);
		$('#razonPersonaJuridica').text(infoJSON[0].razon_social);
		$('#telefonoMovilPersonaJuridica').text(infoJSON[0].telefono_fijo);
		$('#emailPersonaJuridica').text(infoJSON[0].correo_electronico);
		$('#tipoPersonaJuridica').text(infoJSON[0].tipo_persona);
		$('#direccionPersonaJuridica').text(
			infoJSON[0].direccion +
				' #' +
				infoJSON[0].numero +
				', ' +
				infoJSON[0].comuna +
				', ' +
				infoJSON[0].region +
				', ' +
				infoJSON[0].pais
		);
		var urlMaps =
			'https://www.google.com/maps/place/' +
			infoJSON[0].direccion +
			'+%23' +
			infoJSON[0].numero +
			',+' +
			infoJSON[0].comuna +
			',+' +
			infoJSON[0].region +
			',+' +
			infoJSON[0].pais;

		$('#linkMapsJuridica').attr('href', urlMaps);
		$('#section-info-cliente-natural').css('display', 'none');
		$('#section-info-cliente-juridico').css('display', 'block');
	}
}

function eliminarPropiedad(idPropiedad) {
	console.log('idPropiedad: ', idPropiedad);

	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Una vez eliminado, no podrás recuperar esta Propiedad',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Eliminar',
		denyButtonText: 'Cancelar',
	}).then((result) => {
		if (result.isConfirmed) {
			// Si el usuario hace clic en "Eliminar"
			$.ajax({
				url: 'components/propiedad/models/delete_propiedad.php',
				type: 'POST',
				dataType: 'text',
				data: { idPropiedad: idPropiedad },
				success: function (response) {
					Swal.fire({
						title: 'Propiedad eliminada',
						text: 'La propiedad se eliminó correctamente',
						icon: 'success',
					});
					document.location.reload();
					// cargarInfoComentario();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.error('Error en la solicitud:', textStatus, errorThrown);
					// Aquí puedes mostrar un mensaje de error al usuario o realizar otras acciones
				},
			});
		} else if (result.isDenied) {
			// Si el usuario hace clic en "Cancelar"
			// Aquí puedes cerrar el modal de SweetAlert si lo deseas
			Swal.close();
		}
	});
}

//*************************************  DOCUMENTOS  *************************************

function cargaDocumento() {
	var formData = new FormData(document.getElementById('formulario-propiedad'));

	var url = window.location.href;
	console.log(url);
	var parametros = new URL(url).searchParams;

	const token_propiedad_defecto_input = document.getElementById(
		'token_propiedad_defecto'
	);
	var token_propiedad_defecto = token_propiedad_defecto_input.value;

	console.log('TOKEN CREACION: ', parametros.get('token'));
	if (parametros.get('token')) {
		formData.append('token_arrendatario', parametros.get('token'));
	} else {
		formData.append('token_propiedad_defecto', token_propiedad_defecto);
	}

	const titulo_input = document.getElementById('documentoTitulo');
	var titulo = titulo_input.value;

	if (titulo == null || titulo == '') {
		Swal.fire({
			title: 'Atención',
			text: 'Debe agregar un titulo',
			icon: 'warning',
		});
		console.log('titulo vacio');
		return;
	}

	console.log('Enviando Documentos');
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	$.ajax({
		url: 'components/propiedad/models/insert_archivo.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		console.log('res');
		console.log(res);
		var retorno = res.split(',xxx,');
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var token = retorno[3];

		if (resultado == 'OK') {
			Swal.fire({
				title: 'Se guardo correctamente el documento',
				icon: 'success',
			});
			$('#modalDocumentoIngreso').modal('hide');
			resetFormGuardar();
			cargarDocumentos();
		} else {
			Swal.fire({
				title: 'Atención',
				text: mensaje,
				icon: 'warning',
			});
			// alert("No se logro crear arriendo");
			return false;
		}
	});
	// resetForm();
} //function enviarRentdesk

function validaArchivo(e, peso_archivo) {
	// $.showAlert({ title: "Atención", body: "El Archivo debe ser una imagen, word, excel o pdf." });
	var fileExtension = [
		'jpeg',
		'jpg',
		'png',
		'doc',
		'docx',
		'pdf',
		'xls',
		'xlsx',
	];
	var file = e.files[0];
	var maxSizeBytes = peso_archivo * 1024 * 1024;

	if (
		$.inArray($(e).val().split('.').pop().toLowerCase(), fileExtension) == -1
	) {
		Swal.fire({
			title: 'tipo archivo no permitido',
			text: 'El Archivo debe ser una imagen, word, excel o pdf.',
			icon: 'warning',
		});
		$(e).val('');
		return false;
	}

	if (file.size > maxSizeBytes) {
		Swal.fire({
			title: 'Archivo demasiado grande',
			text: 'El tamaño máximo permitido es de ' + peso_archivo + 'MB.',
			icon: 'warning',
		});
		$(e).val(''); // Limpiar el campo de entrada de archivos
		return false;
	}
	var elementos = document.querySelectorAll("[id^='archivo_']");
	var elementosFecha = document.querySelectorAll("[id^='documentoFecha_']");
	var cantidad = elementos.length;
	var cantidadFecha = elementosFecha.length;
	console.log('elementosFecha', elementosFecha);
	console.log('cantidad', cantidad);
	var nombreArchivo = 'archivo_' + cantidad;
	var nombreFecha = 'documentoFecha_' + cantidadFecha;
	var nuevaSeccion = document.createElement('div');
	nuevaSeccion.classList.add('form-group');
	nuevaSeccion.innerHTML = `
	<div class="row" id= "seccionDocumento_${nombreArchivo}">
		  <div class="col-lg-6">
            <div class="form-group">	
				<input id="${nombreArchivo}" name="${nombreArchivo}" type="file" onchange="validaArchivo(this, ${peso_archivo});" class="btn btn-file btn-xs opacity-100 position-relative h-auto btn-upload" />
			</div>
		</div>
		<div class="col-lg-4">
            <div class="form-group">
                <input name="${nombreFecha}" id="${nombreFecha}" onchange="recalcularMes()" class="form-control" type="date" value="" />
                <span id="startDateSelected"></span>
             </div>
		</div>
		<div class="col-lg-2 align-self-end" id="botonEliminaSeccion">
			<div class="form-group">
				<button onclick='eliminarSeccion("seccionDocumento_${nombreArchivo}")' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'>
					<i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
				</button>
			</div>
		</div>	
	</div>
    `;
	console.log('nuevaSeccion', nuevaSeccion);

	var modalBody = document.querySelector('#modalDocumentoIngreso .modal-body');
	modalBody.appendChild(nuevaSeccion);
	document.getElementById('botonEliminaSeccion').style.display = 'inline-block';
}

function eliminarSeccion(id) {
	var elementoAEliminar = document.getElementById(id);
	elementoAEliminar.parentNode.removeChild(elementoAEliminar);
	//elementoAEliminar.remove();
	var elementos = document.querySelectorAll("[id^='archivo_']");
	var cantidad = elementos.length;
	console.log('despues de eliminar cantidad', cantidad);
	if (cantidad == 1) {
		//se contempla 2 por que se toma el id de archivoEditar
		document.getElementById('botonEliminaSeccion').style.display = 'none';
	}
}

function cargarDocumentos() {
	// Realizar la solicitud AJAX para obtener los datos
	var url = window.location.href;
	var parametros = new URL(url).searchParams;
	var token_propiedad = parametros.get('token');
	// console.log(parametros.get('token'));

	var formData = new FormData(document.getElementById('formulario-propiedad'));

	const token_propiedad_defecto_input = document.getElementById(
		'token_propiedad_defecto'
	);
	var token_propiedad_defecto = token_propiedad_defecto_input.value;

	if (token_propiedad) {
		var token = token_propiedad;
	} else {
		var token = token_propiedad_defecto;
	}

	console.log('Ingresando a ajax');
	$.ajax({
		url: 'components/propiedad/models/listado_documentos.php',
		type: 'POST',
		dataType: 'json',
		//data:  "token="+ parametros.get('token') ,
		data: { token: token },
		cache: false,
		success: function (data) {
			console.log('entrando  a la funcion');
			console.log(data);
			if (data != null) {
				console.log('la data no es nula');
				var previousId = null;
				var tbody = $('#documentos tbody');
				// Limpiar el cuerpo de la tabla por si hay datos anteriores
				tbody.empty();
				// Iterar sobre los datos y agregar filas a la tabla
				$.each(data, function (index, item) {
					// Crear una nueva fila de la tabla
					var newRow = $('<tr>');
					// Agregar celdas a la fila con los datos
					if (item.token_agrupador != previousId) {
						newRow.append(
							"<td><div class='d-flex align-items-center' style='gap: .5rem;'> <a data-bs-toggle='modal' data-bs-target='#modalTituloEditar' type='button' onclick='cargarTituloDocumentosEditar(\"" +
								item.titulo +
								'","' +
								item.token_agrupador +
								"\")' class='btn btn-info m-0 d-flex' style='padding: .5rem;' aria-label='Editar' title='Editar'> <i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i></a><label style='font-size: 1em; text-align: center; color: black;'>" +
								item.titulo +
								'</label></div></td>'
						);
						previousId = item.token_agrupador;
					} else {
						newRow.append('<td></td>'); // Agrega una celda vacía si es el mismo ID que el anterior
					}
					if (item.nombre_archivo != null && item.nombre_archivo != '') {
						newRow.append(
							"<td><i class='fa-solid fa-chevron-right'></i> " +
								item.nombre_archivo +
								'</td>'
						);
					} else {
						newRow.append('<td>-</td>');
					}
					newRow.append(
						'<td>' + moment(item.fecha_subida).format('DD-MM-YYYY') + '</td>'
					);
					//console.log("Fecha ",moment(item.fecha_vencimiento).format("DD-MM-YYYY"));
					if (
						moment(item.fecha_vencimiento).format('DD-MM-YYYY') != '01-01-1900'
					) {
						newRow.append(
							'<td>' +
								moment(item.fecha_vencimiento).format('DD-MM-YYYY') +
								'</td>'
						);
					} else {
						newRow.append('<td>-</td>');
					}

					//console.log(item.link);
					newRow.append(
						"<td><div class='d-flex' style='gap: .5rem;'><a href='" +
							item.link +
							"' download  type='button' class='btn btn-info m-0 d-flex' style='padding: .5rem;' aria-label='documento' title='documento'><i class='fa-solid fa-file' style='font-size: .75rem;'></i></div></td>"
					);
					if (
						item.fecha_ultima_actualizacion != null &&
						item.fecha_ultima_actualizacion != ''
					) {
						newRow.append(
							'<td>' +
								(item.fecha_ultima_actualizacion
									? moment(item.fecha_ultima_actualizacion).format('DD-MM-YYYY')
									: '-') +
								"  <i class='fa-solid fa-circle-info' data-bs-toggle='tooltip' data-bs-placement='top' title='Modificado por : " +
								item.nombre_usuario +
								"'></i></td>"
						);
					} else {
						newRow.append('<td>-</td>');
					}

					newRow.append(
						`<td><div class='d-flex' style='gap: .5rem;'><a data-bs-toggle='modal' data-bs-target='#modalDocumentoEditar' type='button' 
            onclick='cargarDocumentosEditar("${item.titulo}", "${item.fecha_vencimiento}","${item.archivo}","${item.extension}","${item.token}")'	   
			class='btn btn-info m-0 d-flex' style='padding: .5rem;' aria-label='Editar' title='Editar'><i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i></a>
		   <button type='button' onclick='eliminarDocumento("${item.token}")' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i></button></div></td>`
					);
					// Agregar la fila al cuerpo de la tabla
					tbody.append(newRow);
				});
				console.log(data);
			} else {
				var tbody = $('#documentos tbody');
				tbody.empty();
				var newRow = $('<tr>');
				console.log('error');
				newRow.append(
					"<td colspan='9' style='text-align:center'> No hay Documentos</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			console.log('error ');
			console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
		},
	});
}

function cargarDocumentosEditar(
	titulo,
	fecha_vencimiento,
	archivo,
	extension,
	token
) {
	var url = 'upload\\propiedad\\' + archivo + '.' + extension;

	if (moment(fecha_vencimiento).format('YYYY-MM-DD') != '1900-01-01') {
		var fechaVencimientoFormateada =
			moment(fecha_vencimiento).format('YYYY-MM-DD');
		var fecha_vencimiento = $('#documentoFechaEditar').val(
			fechaVencimientoFormateada
		);
	}
	var titulo = $('#documentoTituloEditar').val(titulo);
	var token = $('#documentoTokenEditar').val(token);

	$('#linkDocumento').attr('href', url).text(url);
	$('#linkDocumento2').attr('href', url).text(url);
	$('#linkDocumento')
		.empty()
		.append('<i class="fas fa-file" style="font-size: .75rem;"></i>');
	$('#linkDocumento2').empty().append('Ver Documento :');
}

function cargarTituloDocumentosEditar(titulo, token) {
	var titulo = $('#TituloEditar').val(titulo);
	var token = $('#TokenEditar').val(token);
}

function eliminarDocumento(idDocumento) {
	// Obtener el formulario y crear un objeto FormData
	var formData = new FormData(document.getElementById('formulario-propiedad'));
	// Agregar el id del documento a eliminar como un nuevo parámetro
	formData.append('tokenEliminar', idDocumento);

	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Una vez eliminado, no podrás recuperar este documento',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Eliminar',
		denyButtonText: 'Cancelar',
	}).then((result) => {
		if (result.isConfirmed) {
			// Si el usuario hace clic en "Eliminar"
			$.ajax({
				url: 'components/propiedad/models/delete_documento.php',
				type: 'POST',
				dataType: 'text',
				data: formData,
				processData: false, // Necesario para enviar formData correctamente
				contentType: false, // Necesario para enviar formData correctamente
				success: function (response) {
					Swal.fire({
						title: 'Documento eliminado',
						text: 'El documento se eliminó correctamente',
						icon: 'success',
					});
					cargarDocumentos();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.error('Error en la solicitud:', textStatus, errorThrown);
					// Aquí puedes mostrar un mensaje de error al usuario o realizar otras acciones
				},
			});
		} else if (result.isDenied) {
			// Si el usuario hace clic en "Cancelar"
			// Aquí puedes cerrar el modal de SweetAlert si lo deseas
			Swal.close();
		}
	});
}

function editarDocumento() {
	var formData = new FormData(document.getElementById('formulario-propiedad'));
	var url = window.location.href;
	console.log(url);
	var parametros = new URL(url).searchParams;
	var token_arriendo = parametros.get('token');
	formData.append('token_arrendatario', parametros.get('token'));

	const token_propiedad_defecto_input = document.getElementById(
		'token_propiedad_defecto'
	);
	var token_propiedad_defecto = token_propiedad_defecto_input.value;

	const documentoFechaEditar_input = document.getElementById(
		'documentoFechaEditar'
	);
	var documentoFechaEditar = documentoFechaEditar_input.value;

	const documentoTituloEditar_input = document.getElementById(
		'documentoTituloEditar'
	);
	var documentoTituloEditar = documentoTituloEditar_input.value;

	const documentoTokenEditar_input = document.getElementById(
		'documentoTokenEditar'
	);
	var documentoTokenEditar = documentoTokenEditar_input.value;

	formData.append('documentoTituloEditar', documentoTituloEditar);
	formData.append('documentoFechaEditar', documentoFechaEditar);
	formData.append('documentoTokenEditar', documentoTokenEditar);

	console.log('Editando documento');
	$.ajax({
		url: 'components/propiedad/models/editar_documento.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		console.log('res');
		console.log(res);
		var retorno = res.split(',xxx,');
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var token = retorno[3];

		if (resultado == 'OK') {
			Swal.fire({
				title: 'Edicion correcta',
				text: '',
				icon: 'success',
			});
			//$("#modalServicioIngreso")[0].reset();
			$('#modalDocumentoEditar').modal('hide');
			cargarDocumentos();
		} else {
			Swal.fire({
				title: 'Atención',
				text: mensaje,
				icon: 'warning',
			});
			// alert("No se logro crear arriendo");
			return false;
		}
	});
} //function enviarRentdesk

function editarTituloDocumento() {
	var formData = new FormData(document.getElementById('formulario-propiedad'));
	var url = window.location.href;
	console.log(url);
	var parametros = new URL(url).searchParams;
	var token_arriendo = parametros.get('token');
	formData.append('token_arrendatario', parametros.get('token'));

	const documentoTituloEditar_input = document.getElementById('TituloEditar');
	var documentoTituloEditar = documentoTituloEditar_input.value;

	const documentoTokenEditar_input = document.getElementById('TokenEditar');
	var documentoTokenEditar = documentoTokenEditar_input.value;

	formData.append('documentoFechaEditar', documentoFechaEditar);
	formData.append('documentoTokenEditar', documentoTokenEditar);

	console.log('Editando Titulo documento');
	$.ajax({
		url: 'components/propiedad/models/editar_titulo_documento.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		console.log('res');
		console.log(res);
		var retorno = res.split(',xxx,');
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var token = retorno[3];

		if (resultado == 'OK') {
			Swal.fire({
				title: 'Edicion correcta',
				text: '',
				icon: 'success',
			});
			//$("#modalServicioIngreso")[0].reset();
			$('#modalTituloEditar').modal('hide');
			cargarDocumentos();
		} else {
			Swal.fire({
				title: 'Atención',
				text: mensaje,
				icon: 'warning',
			});
			// alert("No se logro crear arriendo");
			return false;
		}
	});
} //function enviarRentdesk

function resetearFieldsetDocumento() {
	// Obtener referencia al fieldset por su ID
	var fieldset = document.getElementById('section-Documentos');

	// Obtener todos los elementos dentro del fieldset
	var elementos = fieldset.getElementsByTagName('*');

	// Iterar sobre los elementos y restablecer sus valores
	for (var i = 0; i < elementos.length; i++) {
		// Comprobar si el elemento es un campo de formulario (input, select, textarea)
		if (
			elementos[i].tagName === 'INPUT' ||
			elementos[i].tagName === 'TEXTAREA'
		) {
			// Restablecer el valor del campo a su valor inicial
			elementos[i].value = elementos[i].defaultValue;
		}
	}
}

function resetForm() {
	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Al volver sin guardar se perderan los cambios',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Si',
		denyButtonText: 'No',
	}).then((result) => {
		if (result.isConfirmed) {
			// Si se confirma la acción
			document.getElementById('documentoTitulo').value = '';
			document.getElementById('archivo_0').value = '';
			document.getElementById('documentoFecha_0').value = '';

			var seccionesAdicionales = document.querySelectorAll(
				'[id^="seccionDocumento_archivo_"]'
			); // Se mantiene formulario original
			var idSeccionAConservar = 'seccionDocumento_archivo_0';
			seccionesAdicionales.forEach(function (seccion) {
				if (seccion.id !== idSeccionAConservar) {
					seccion.remove();
				}
			});
			document.getElementById('botonEliminaSeccion').style.display = 'none';
			$('#modalDocumentoIngreso').modal('hide');
		}
	});

	//document.getElementById("modalDocumentoIngreso").reset();
}

function resetFormGuardar() {
	document.getElementById('documentoTitulo').value = '';
	document.getElementById('archivo_0').value = '';
	document.getElementById('documentoFecha_0').value = '';

	var seccionesAdicionales = document.querySelectorAll(
		'[id^="seccionDocumento_archivo_"]'
	); // Se mantiene formulario original
	var idSeccionAConservar = 'seccionDocumento_archivo_0';
	seccionesAdicionales.forEach(function (seccion) {
		if (seccion.id !== idSeccionAConservar) {
			seccion.remove();
		}
	});
	document.getElementById('botonEliminaSeccion').style.display = 'none';
	$('#modalDocumentoIngreso').modal('hide');
	//document.getElementById("modalDocumentoIngreso").reset();
}

//Fin documentos

function formateoNulos(text) {
	return !text || text === '' ? '-' : text;
}

// function cargarInfoCoPropietarios() {
// 	// Realizar la solicitud AJAX para obtener los datos
// 	var idFicha = $('#ficha_tecnica').val();
// 	console.log('id ficha: ', idFicha);
// 	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
// 	$('#procentajePropietarioValidacion').hide();
// 	var porcentaje = 0;

// 	$.ajax({
// 		url: 'components/propiedad/models/listado_copropietarios.php',
// 		type: 'POST',
// 		dataType: 'json',
// 		data: { idFicha: idFicha },
// 		cache: false,
// 		success: function (data) {
// 			console.log('DATA: ', data.length);
// 			if (data != null) {
// 				var tbody = $('#info-copropietarios tbody');
// 				// Limpiar el cuerpo de la tabla por si hay datos anteriores
// 				tbody.empty();

// 				const parents = data.filter((item) => item.nivel_propietario === 1);

// 				console.log('PARENTS: ', parents);

// 				/*AGRUPA POR ID PROPIETARIO Y ID RELACION */
// 				const groupedData = data.reduce((acc, item) => {
// 					const key = item.id_propietario + '|' + item.id_relacion;
// 					if (!acc[key]) {
// 						acc[key] = [];
// 					}
// 					acc[key].push(item);
// 					return acc;
// 				}, {});

// 				// Iterate over grouped data to append rows to the table
// 				Object.keys(groupedData).forEach((keys) => {

// 					const keysArray = keys.split('|');
// 					const id_propietario = keysArray[0];
// 					const id_relacion = keysArray[1];

// 					const items = groupedData[keys];
// 					const parentRow = items.find((item) => item.nivel_propietario === 1);
// 					const childRows = items.filter(
// 						(item) => item.nivel_propietario === 2
// 					);

// 					// Append parent row
// 					if (parentRow) {
// 						porcentaje += parseFloat(parentRow.porcentaje_participacion_base);

// 						if (parseInt(porcentaje) < 100) {
// 							$('#procentajePropietarioValidacion').show();
// 							$('#btnLiquidar').hide();
// 						} else {
// 							$('#procentajePropietarioValidacion').hide();
// 						}

// 						let parentRowHtml = `
//               <tr class="parent-row">
//                   <td>
//                       <div class='d-flex' style='gap: .5rem;'>
//                           <button type='button' class='btn btn-info m-0 d-flex' style='padding: .5rem;' title='Ingreso Beneficiario' data-bs-toggle="modal" data-bs-target="#modalBeneficiarioIngreso" onclick="llenarIdPropietarioSeleccionado(${parentRow.id_propietario
// 							}, ${parentRow.id})">
//                               <i class='fa-regular fa-plus' style='font-size: .75rem;'></i>
//                           </button>
//                       </div>
//                   </td>
//                   <td>${formateoNulos(parentRow.nombre)}</td>
//                   <td>${formateoNulos(formatRutChile(parentRow.dni))}</td>
//                   <td>${formateoNulos(parentRow.nombre_titular)}</td>
//                   <td>${formateoNulos(
// 								formatRutChile(parentRow.rut_titular)
// 							)}</td>
//                   <td>${formateoNulos(parentRow.cuenta_banco)}</td>

//                   <td><input type="number" class="porcentaje_participacion_base parent-input numeric-vacio" id="porcentaje_participacion_base_${id_propietario}" name="${parentRow.id_propietario
// 							}|${parentRow.id_cta_banc}|porc_part_base||${parentRow?.id
// 							}" min="0" max="100" step="0.01" value="${parentRow.porcentaje_participacion_base
// 							}"></td>
//             <td><input disabled type="number" class="porcentaje_participacion numeric-vacio" id="porcentaje_participacion_${id_propietario}" name="${parentRow.id_propietario
// 							}|${parentRow.id_cta_banc}|porc_part||${parentRow?.id
// 							}" min="0" max="100" step="0.01" value="${parentRow.porcentaje_participacion
// 							}"></td>
//             <td>
//             <div id="eliminarParent">
//             <div class='d-flex' style='gap: .5rem;' >
//                 <button onclick='eliminarInfoCoPropietario({
//                   idRegistro: ${parentRow.id},
//                   idPropiedad: ${parentRow.id_propiedad},
//                   idPropietario: ${parentRow.id_propietario}
//                 })' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'>
//                     <i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
//                 </button>
//             </div>
//             </div>
//         </td>
//               </tr>
//           `;

// 						tbody.append(parentRowHtml);
// 					}

// 					// Append child rows
// 					childRows.forEach((child, index) => {

// 						console.log("respuesta dentro del foreach" +  child.nombre);

// 						let childRowHtml = `
//               <tr class="child-row table-info">
//                   <td></td>
//                   <td>${formateoNulos(child.nombre)}</td>
//                   <td>${formateoNulos(formatRutChile(child.dni))}</td>
//                   <td>${formateoNulos(child.nombre_titular)}</td>
//                   <td>${formateoNulos(formatRutChile(child.rut_titular))}</td>
//                   <td>${formateoNulos(child.cuenta_banco)}</td>

//                 <!--  <td><input type="number" class="porcentaje_participacion_base numeric-vacio" id="porcentaje_participacion_base_${id_propietario}_${index}" name="${child.id_propietario
// 							}||porc_part_base|${child.id_beneficiario}|${child?.id_relacion
// 							}" min="0" max="100" step="0.01" value="${child.porcentaje_participacion_base
// 							}"></td>

//             -->
//             <td>-</td>
//             <td><input type="number" class="porcentaje_participacion child-input numeric-vacio" id="porcentaje_participacion_${id_propietario}_${index}" name="${child.id_propietario
// 							}||porc_part|${child.id_beneficiario}|${child?.id_relacion
// 							}" min="0" max="100" step="0.01" value="${child.porcentaje_participacion
// 							}"></td>
//                   <td>
//                       <div class='d-flex' style='gap: .5rem;'>
//                           <button onclick='eliminarInfoCoPropietario({
//                             idPropiedad: ${child.id_propiedad},
//                             idPropietario: ${child.id_propietario},
//                             tokenBeneficiario: "${child.token}"
//                           })' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'>
//                               <i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
//                           </button>
//                       </div>
//                   </td>
//               </tr>
//           `;
// 						tbody.append(childRowHtml);
// 					});

// 					if (calculateSumPorcBase() !== 100) {
// 						$('#current-sum').text(calculateSumPorcBase().toFixed(2));
// 						$('#alertAvisoPorcentajeTotal').show();
// 					} else {
// 						$('#alertAvisoPorcentajeTotal').hide();
// 					}

// 					// Update parent input based on child inputs
// 					updateParentInput(id_propietario);

// 					// Event listener for child input fields
// 					$(`.child-input`).on('input', function () {
// 						enforceMaxSum(id_propietario);
// 						updateParentInput(id_propietario);
// 					});
// 				});

// 				/*SI EXISTE SÓLO UN REGISTRO DE PROPIETARIO, NO PUEDE ELIMINARSE */
// 				if (parents.length === 1) {
// 					$('#eliminarParent').attr('hidden', true);
// 				} else {
// 					$('#eliminarParent').attr('hidden', false);
// 					$('#eliminarParent').css('display', 'flex');
// 				}

// 				// Event listener for parent base input fields
// 				$(`.parent-input`).on('input', function () {
// 					enforceMaxSum();
// 					updateParentBaseInput();
// 				});

// 				llenarInputNumericoVacio();
// 			}
// 		},
// 		error: function (jqXHR, textStatus, errorThrown) {
// 			// Manejar errores si es necesario
// 			console.log('error', jqXHR, textStatus, errorThrown);
// 		},
// 	});
// }

// Elimina (habilitado = false) condicionalmente
// Opción 1: Elimina al propietario con sus beneficiarios asociados
// Opción 2: Sólo elimina al beneficiario (sólo si existe tokenBeneficiario)

function cargarInfoCoPropietarios() {
	// Obtiene el ID de la ficha técnica desde el input en la página
	var idFicha = $('#ficha_tecnica').val();
	console.log('id ficha: ', idFicha);

	// Bloquea la UI mientras se ejecuta la solicitud AJAX
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	// Oculta la validación del porcentaje de propietarios
	$('#procentajePropietarioValidacion').hide();
	var porcentaje = 0;

	// Realiza la solicitud AJAX para obtener los datos de los copropietarios
	$.ajax({
		url: 'components/propiedad/models/listado_copropietarios.php',
		type: 'POST',
		dataType: 'json',
		data: { idFicha: idFicha },
		cache: false,
		success: function (data) {
			console.log('DATA: ', data.length);
			if (data != null) {
				// var tbody = $('#info-copropietarios tbody');
				// Limpia el cuerpo de la tabla antes de agregar nuevos datos
				tbody.empty();

				// Filtra los propietarios principales (nivel 1)
				const parents = data.filter((item) => item.nivel_propietario === 1);
				console.log('PARENTS: ', parents);

				// Agrupa los datos por id_propietario y id_relacion
				const groupedData = data.reduce((acc, item) => {
					const key = item.id_propietario + '|' + (item.id_relacion ?? '0'); // Se evita concatenar 'null'
					if (!acc[key]) {
						acc[key] = [];
					}
					acc[key].push(item);
					return acc;
				}, {});

				// Itera sobre los datos agrupados y genera las filas de la tabla
				Object.keys(groupedData).forEach((keys) => {
					const keysArray = keys.split('|');
					const id_propietario = keysArray[0];
					const id_relacion = keysArray[1];

					const items = groupedData[keys];
					const parentRow = items.find((item) => item.nivel_propietario === 1);
					const childRows = items.filter(
						(item) => item.nivel_propietario === 2
					);

					// Agrega la fila del propietario principal si existe
					if (parentRow) {
						porcentaje += parseFloat(parentRow.porcentaje_participacion_base);

						// Muestra u oculta el mensaje de validación del porcentaje
						if (parseInt(porcentaje) < 100) {
							$('#procentajePropietarioValidacion').show();
							$('#btnLiquidar').hide();
						} else {
							$('#procentajePropietarioValidacion').hide();
						}

						// Genera la fila del propietario principal
						let parentRowHtml = `
                          <tr class="parent-row">
                              <td>
                                  <div class='d-flex' style='gap: .5rem;'>
                                      <button type='button' class='btn btn-info m-0 d-flex' style='padding: .5rem;' 
                                          title='Ingreso Beneficiario' data-bs-toggle="modal" 
                                          data-bs-target="#modalBeneficiarioIngreso" 
                                          onclick="llenarIdPropietarioSeleccionado(${
																						parentRow.id_propietario
																					}, ${parentRow.id})">
                                          <i class='fa-regular fa-plus' style='font-size: .75rem;'></i>
                                      </button>
                                  </div>
                              </td>
                              <td>${formateoNulos(parentRow.nombre)}</td>
                              <td>${formateoNulos(
																formatRutChile(parentRow.dni)
															)}</td>
                              <td>${formateoNulos(
																parentRow.nombre_titular
															)}</td>
                              <td>${formateoNulos(
																formatRutChile(parentRow.rut_titular)
															)}</td>
                              <td>${formateoNulos(parentRow.cuenta_banco)}</td>
                              <td><input type="number" class="porcentaje_participacion_base parent-input numeric-vacio" 
                                  id="porcentaje_participacion_base_${id_propietario}" 
                                  name="${parentRow.id_propietario}|${
							parentRow.id_cta_banc
						}|porc_part_base||${parentRow?.id}" 
                                  min="0" max="100" step="0.01" 
                                  value="${
																		parentRow.porcentaje_participacion_base
																	}"></td>
                              <td><input disabled type="number" class="porcentaje_participacion numeric-vacio" 
                                  id="porcentaje_participacion_${id_propietario}" 
                                  name="${parentRow.id_propietario}|${
							parentRow.id_cta_banc
						}|porc_part||${parentRow?.id}" 
                                  min="0" max="100" step="0.01" 
                                  value="${
																		parentRow.porcentaje_participacion
																	}"></td>
                              <td>
                                  <div id="eliminarParent">
                                      <div class='d-flex' style='gap: .5rem;'>
                                          <button onclick='eliminarInfoCoPropietario({
                                              idRegistro: ${parentRow.id},
                                              idPropiedad: ${
																								parentRow.id_propiedad
																							},
                                              idPropietario: ${
																								parentRow.id_propietario
																							}
                                          })' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'>
                                              <i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
                                          </button>
                                      </div>
                                  </div>
                              </td>
                          </tr>
                      `;

						tbody.append(parentRowHtml);
					}

					// Agrega las filas de los beneficiarios secundarios
					childRows.forEach((child, index) => {
						console.log('respuesta dentro del foreach' + child.nombre);

						let childRowHtml = `
                          <tr class="child-row table-info">
                              <td></td>
                              <td>${formateoNulos(child.nombre)}</td>
                              <td>${formateoNulos(
																formatRutChile(child.dni)
															)}</td>
                              <td>${formateoNulos(child.nombre_titular)}</td>
                              <td>${formateoNulos(
																formatRutChile(child.rut_titular)
															)}</td>
                              <td>${formateoNulos(child.cuenta_banco)}</td>
                              <td>-</td>
                              <td><input type="number" class="porcentaje_participacion child-input numeric-vacio" 
                                  id="porcentaje_participacion_${id_propietario}_${index}" 
                                  name="${child.id_propietario}||porc_part|${
							child.id_beneficiario
						}|${child?.id_relacion}" 
                                  min="0" max="100" step="0.01" 
                                  value="${
																		child.porcentaje_participacion
																	}"></td>
                              <td>
                                  <div class='d-flex' style='gap: .5rem;'>
                                      <button onclick='eliminarInfoCoPropietario({
                                          idPropiedad: ${child.id_propiedad},
                                          idPropietario: ${
																						child.id_propietario
																					},
                                          tokenBeneficiario: "${child.token}"
                                      })' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'>
                                          <i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
                                      </button>
                                  </div>
                              </td>
                          </tr>
                      `;
						tbody.append(childRowHtml);
					});

					if (calculateSumPorcBase() !== 100) {
						$('#current-sum').text(calculateSumPorcBase().toFixed(2));
						$('#alertAvisoPorcentajeTotal').show();
					} else {
						$('#alertAvisoPorcentajeTotal').hide();
					}

					// Actualiza el valor del propietario principal basado en los beneficiarios
					updateParentInput(id_propietario);

					// Agrega eventos para controlar los inputs de beneficiarios
					$(`.child-input`).on('input', function () {
						enforceMaxSum(id_propietario);
						updateParentInput(id_propietario);
					});
				});

				// Si solo hay un propietario, oculta la opción de eliminarlo
				$('#eliminarParent').attr('hidden', parents.length === 1);

				// Eventos para controlar los inputs de propietarios principales
				$(`.parent-input`).on('input', function () {
					enforceMaxSum();
					updateParentBaseInput();
				});

				llenarInputNumericoVacio();
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log('error', jqXHR, textStatus, errorThrown);
		},
	});
}

// jose trabajando aqui
async function CargarInfoCopopieratioBeneficiario() {
	var idFicha = $('#ficha_tecnica').val();

	try {
		const response = await fetch(
			'components/propiedad/models/leer_infor_copropietario_beneficiario.php',
			{
				method: 'POST',
				body: new URLSearchParams({ idFicha: idFicha }),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			}
		);

		const responseData = await response.text();
		console.log('🔹 responseData:', responseData);

		let data;
		try {
			data = JSON.parse(responseData);
		} catch (error) {
			Swal.fire('Error', 'La respuesta del servidor no es válida.', 'error');
			return;
		}

		if (!data || !data.copropietarios || !data.beneficiarios) {
			Swal.fire(
				'Error',
				'No se encontraron datos para la ficha seleccionada.',
				'warning'
			);
			return;
		}

		console.log('✅ Datos recibidos para la tabla:', data);

		let formattedData = combinarDatos(data);
		console.log('✅ Datos combinados para DataTable:', formattedData);

		// Verificar si la tabla ya existe y limpiarla antes de recargar
		if ($.fn.DataTable.isDataTable('#info-copropietarios')) {
			$('#info-copropietarios').DataTable().clear().destroy();
		}

		// 🔹 Inicializamos DataTable con los datos formateados
		$('#info-copropietarios').DataTable({
			destroy: true,
			data: formattedData,
			columns: [
				{ title: '', data: 'tipo' },
				{ title: 'Propietario', data: 'propietario' },
				{ title: 'RUT Propietario', data: 'rut_propietario' },
				{ title: 'Nombre Titular', data: 'titular' },
				{ title: 'RUT Titular', data: 'rut_titular' },
				{ title: 'Cuenta Banco', data: 'cuenta_banco' },
				{ title: '% Porcentaje Propietario', data: 'porcentaje_propietario' },
				{ title: '% Porcentaje Beneficiario', data: 'porcentaje_beneficiario' },
				{ title: 'Acciones', data: 'acciones', orderable: false },
			],
			paging: false,
			searching: false,
			ordering: false,
		});

		Swal.close(); // Cerrar SweetAlert cuando todo esté listo

		// 🔹 Llamar validaciones después de cargar los datos
		validarTotalPorcentajes();
		asignarEventosValidacion();
	} catch (error) {
		console.error('❌ Error al cargar los datos:', error);
	}
}

// 🔹 Función para combinar datos de copropietarios y beneficiarios
function combinarDatos(data) {
	let filas = [];

	// 🔹 Recorremos cada copropietario
	data.copropietarios.forEach((coprop) => {
		let botonIngresoBeneficiario = `
            <div class='d-flex' style='gap: .5rem;'>
                <button type='button' class='btn btn-info m-0 d-flex' style='padding: .5rem;' 
                    title='Ingreso Beneficiario' data-bs-toggle="modal" 
                    data-bs-target="#modalBeneficiarioIngreso" 
                    onclick="llenarIdPropietarioSeleccionado(${coprop.id_propietario}, ${coprop.id})">
                    <i class='fa-regular fa-plus' style='font-size: .75rem;'></i>
                </button>
            </div>
        `;

		// 🔹 Fila del copropietario
		filas.push({
			tipo: botonIngresoBeneficiario,
			propietario: coprop.nombre ? coprop.nombre.trim() : '-',
			rut_propietario: coprop.rut_propietario || '-',
			titular: '-',
			rut_titular: '-',
			cuenta_banco: '-',
			porcentaje_propietario: `<input type="number" class="form-control porcentaje-propietario" 
                                      value="${coprop.porcentaje_participacion}" 
                                      data-id="${coprop.id_propietario}" 
                                      min="0" max="100" step="0.01">`,
			porcentaje_beneficiario: '-',
			acciones: `
                <div class='d-flex' style='gap: .5rem;'>
                    <button onclick='eliminarInfoCoPropietario({
                        idRegistro: ${coprop.id},
                        idPropiedad: ${coprop.id_propiedad},
                        idPropietario: ${coprop.id_propietario}
                    })' type='button' class='btn btn-danger eliminar-copropietario' 
                       title='Eliminar'>
                        <i class='fa-regular fa-trash-can'></i>
                    </button>
                </div>
            `,
		});

		// 🔹 Beneficiarios del copropietario
		data.beneficiarios.forEach((bene) => {
			if (coprop.id_propietario === bene.id_propietario) {
				filas.push({
					tipo: '➥ Beneficiario',
					propietario: '',
					rut_propietario: '',
					titular: bene.nombre || '-',
					rut_titular: bene.rut || '-',
					cuenta_banco: bene.numero_cuenta || '-',
					porcentaje_propietario: '-',
					porcentaje_beneficiario: `<input type="number" class="form-control porcentaje-beneficiario"
					value="${bene.porcentaje}" 
					data-id="${bene.id_propietario}" 
					data-beneficiario-id="${bene.id}"  
					min="0" max="100" step="0.01">`,
					acciones: '',
				});
			}
		});
	});

	return filas;
}

// 🔹 Validación de porcentajes en tiempo real
function asignarEventosValidacion() {
	$('.porcentaje-propietario').on('input', validarTotalPorcentajes);
	$('.porcentaje-beneficiario').on('input', validarBeneficiarios);
}

// 🔹 Validar que los propietarios sumen exactamente 100%
function validarTotalPorcentajes() {
	let total = 0;

	$('.porcentaje-propietario').each(function () {
		total += parseFloat($(this).val()) || 0;
	});

	$('#current-sum').text(total.toFixed(2));

	if (total !== 100) {
		$('#alertAvisoPorcentajeTotal').show();
	} else {
		$('#alertAvisoPorcentajeTotal').hide();
	}
}

// 🔹 Validar que los beneficiarios de cada propietario no superen 100%
function validarBeneficiarios() {
	let propietarios = {};

	$('.porcentaje-beneficiario').each(function () {
		let idPropietario = $(this).data('id');
		let valor = parseFloat($(this).val()) || 0;

		if (!propietarios[idPropietario]) {
			propietarios[idPropietario] = 0;
		}

		propietarios[idPropietario] += valor;
	});

	for (let id in propietarios) {
		if (propietarios[id] > 100) {
			Swal.fire({
				title: 'Atención',
				text: `Los beneficiarios del propietario ID ${id} superan el 100%. Ajuste los porcentajes.`,
				icon: 'warning',
			});

			$(event.target).val(0);
			return;
		}
	}
}

function eliminarInfoCoPropietario({
	idRegistro = null,
	idPropiedad,
	idPropietario,
	tokenBeneficiario = null,
}) {
	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Una vez eliminado, no podrás recuperar este propietario',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Eliminar',
		denyButtonText: 'Cancelar',
	}).then((result) => {
		if (result.isConfirmed) {
			$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

			// Si el usuario hace clic en "Eliminar"
			$.ajax({
				url: 'components/propiedad/models/delete_info_copropietario.php',
				type: 'POST',
				dataType: 'text',
				data: {
					idRegistro: idRegistro,
					idPropiedad: idPropiedad,
					idPropietario: idPropietario,
					tokenBeneficiario: tokenBeneficiario,
				},
				success: function (response) {
					cargarInfoCoPropietarios();

					Swal.fire({
						title: 'Propietario eliminado',
						text: 'El propietario se eliminó correctamente',
						icon: 'success',
					}).then(() => {
						/*CHEQUEA SI QUEDÓ SÓLO UN REGISTRO DE PROPIETARIO */
						const inputs = $('.porcentaje_participacion_base.parent-input');
						const inputCount = inputs.length;

						console.log('inputCount: ', inputCount);

						if (inputCount === 1) {
							$('.porcentaje_participacion_base.parent-input').val(100);
							guardarCoPropietarioPorcentaje();
						}
					});
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.error('Error en la solicitud:', textStatus, errorThrown);
					// Aquí puedes mostrar un mensaje de error al usuario o realizar otras acciones
				},
			});
		} else if (result.isDenied) {
			// Si el usuario hace clic en "Cancelar"
			// Aquí puedes cerrar el modal de SweetAlert si lo deseas
			Swal.close();
		}
	});
}

function llenarInputNumericoVacio() {
	$(document).ready(function () {
		$('.numeric-vacio').blur(function () {
			if ($(this).val() === '') {
				$(this).val(0);
			}
		});

		$('.numeric-vacio').change(function () {
			if ($(this).val() === '') {
				$(this).val(0);
			}
		});
	});
}

// Guarda los ids correspondientes del propietario y registro
function llenarIdPropietarioSeleccionado(idPropietario, idRegistro) {
	console.log(
		'idPropietario, idRegistro desde onclick: ',
		idPropietario,
		idRegistro
	);
	$('#idPropietario').val(idPropietario);
	$('#idRegistro').val(idRegistro);
}

// Valida la sumatoria total sea 100 para propietarios
function enforceMaxSum() {
	const parentInputs = $('.porcentaje_participacion_base.parent-input');
	let sum = 0;

	parentInputs.each(function () {
		sum += parseFloat($(this).val()) || 0;
	});

	if (sum > 100) {
		let excess = sum - 100;
		parentInputs.each(function () {
			let currentValue = parseFloat($(this).val()) || 0;
			if (currentValue > excess) {
				$(this).val((currentValue - excess).toFixed(2));
				return false; // Break the loop
			} else {
				$(this).val(0);
				excess -= currentValue;
			}
		});
		// alert("La suma de los valores de los propietarios no puede superar 100.");
		updateParentBaseInput();
	}
}

// Valida la sumatoria total sea 100 para beneficiarios
function enforceMaxSum(id_propietario) {
	const childInputs = $(`[id^="porcentaje_participacion_${id_propietario}_"]`);
	let sum = 0;

	childInputs.each(function () {
		sum += parseFloat($(this).val()) || 0;
	});

	if (sum > 100) {
		let excess = sum - 100;
		childInputs.each(function () {
			let currentValue = parseFloat($(this).val()) || 0;
			if (currentValue > excess) {
				$(this).val((currentValue - excess).toFixed(2));
				return false; // Break the loop
			} else {
				$(this).val(0);
				excess -= currentValue;
			}
		});

		// Swal.fire({
		//   title: "Atención ",
		//   text: "La suma de los % de Participación de Beneficiarios no puede superar el 100%.",
		//   icon: "warning",
		// }).then(() => {
		// });

		updateParentInput(id_propietario);
	}
}

// Reutiliza la información del beneficiario en formulario para la cuenta bancaria asociada
function checkUseBenefData() {
	$(document).ready(function () {
		$('#nombreBeneficiario').on('input', function () {
			$('#nombreTitular').val($(this).val());
		});

		$('#rutBeneficiario').on('input', function () {
			$('#rutTitular').val($(this).val());
		});

		$('#correoElectronicoBeneficiario').on('input', function () {
			$('#emailTitular').val($(this).val());
		});
	});
}

function updateParentInput(id_propietario) {
	let childInputs = $(`[id^="porcentaje_participacion_${id_propietario}_"]`);
	let sum = 0;

	// Calculate the sum of values
	childInputs.each(function () {
		sum += parseFloat($(this).val()) || 0;
	});

	let parentInput = $(`#porcentaje_participacion_${id_propietario}`);
	parentInput.val((100 - sum).toFixed(2));
}

// Actualiza el input % del propietario asociado
function updateParentBaseInput() {
	let parentInputs = $('.porcentaje_participacion_base.parent-input');
	let sum = 0;

	// Calculate the sum of values
	parentInputs.each(function () {
		sum += parseFloat($(this).val()) || 0;
	});

	if (sum !== 100) {
		$('#current-sum').text(sum.toFixed(2));
		$('#alertAvisoPorcentajeTotal').show();
	} else {
		$('#alertAvisoPorcentajeTotal').hide();
	}
}

function calculateSumPorc(id_propietario = null) {
	const inputs = document.querySelectorAll('.porcentaje_participacion');

	console.log('inputs: ', inputs);
	let sum = 0;

	// inputs.forEach((input) => {
	//   sum += parseFloat(input.value) || 0;
	// });

	$('input[id^="' + id_propietario + '_"]').each(function () {
		// Change the selector pattern here
		sum += parseFloat($(this).val()) || 0;
	});

	console.log('SUMA DE PORCENTAJES BENEFICIARIOS: ', sum);
	// document.getElementById('sumResult').innerHTML = 'Total: ' + sum.toFixed(1);

	return sum;
}

/*METODOS PARA CALCULO DE SUMATORIA A 100 */
function calculateSumPorcBase() {
	const inputs = document.querySelectorAll('.porcentaje_participacion_base');

	console.log('inputs: ', inputs);
	let sum = 0;

	inputs.forEach((input) => {
		sum += parseFloat(input.value) || 0;
	});

	console.log('SUMA DE PORCENTAJES: ', sum);

	return sum;
}

// Guarda los porcentajes actualizados tanto de los propietarios como beneficiarios
// function guardarCoPropietarioPorcentaje() {
//     let formData = new FormData(document.getElementById('copropietario_porcentaje'));

//     var jsonInformacionNueva = obtenerValoresFormulario('copropietario_porcentaje');

//     // Get all input fields de propietarios
//     var inputs = document.querySelectorAll('.porcentaje-propietario');

//     // Agregar datos de los inputs al formData
//     inputs.forEach(function (input) {
//         formData.append(input.name, input.value);
//     });

//     // 🔹 1️⃣ Validar que la suma de propietarios sea EXACTAMENTE 100%
//     if (calculateSumPorcBase() !== 100) {
//         Swal.fire({
//             title: 'Atención',
//             text: 'Debe modificar los porcentajes de los propietarios. El total debe ser 100%.',
//             icon: 'warning',
//         });
//         return;
//     }

//     // 🔹 2️⃣ Validar que CADA propietario tenga beneficiarios que sumen máximo 100%
//     let propietarios = {}; // Guardaremos la suma de beneficiarios por cada propietario

//     $(".porcentaje-beneficiario").each(function () {
//         let idPropietario = $(this).data("id"); // Obtiene el ID del propietario asociado
//         let valor = parseFloat($(this).val()) || 0;

//         if (!propietarios[idPropietario]) {
//             propietarios[idPropietario] = 0;
//         }

//         propietarios[idPropietario] += valor;
//     });

//     for (let id in propietarios) {
//         if (propietarios[id] > 100) {
//             Swal.fire({
//                 title: 'Atención',
//                 text: `Los beneficiarios del propietario con ID ${id} superan el 100%. Ajuste los porcentajes.`,
//                 icon: 'warning',
//             });
//             return;
//         }
//     }

//     // 🔹 3️⃣ Enviar los datos al servidor si todo está correcto
//     var id_ficha = $('#ficha_tecnica').val();
//     var url = window.location.href;
//     var parametros = new URL(url).searchParams;
//     formData.append('token', parametros.get('token'));

//     $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//     $.ajax({
//         url: 'components/propiedad/models/update_copropietario_porcentajes.php',
//         type: 'post',
//         dataType: 'text',
//         data: formData,
//         cache: false,
//         contentType: false,
//         processData: false,
//     })
//         .done(function (res) {
//             Swal.fire({
//                 title: 'Porcentajes de Participación Actualizados',
//                 text: 'Los porcentajes de participación se actualizaron correctamente',
//                 icon: 'success',
//             });
//             var id_comentario = res;
//             cargarInfoCoPropietarios();
//             registroHistorial(
//                 'Editar',
//                 '',
//                 jsonInformacionNueva,
//                 'CoPropietarios - Porcentaje',
//                 id_ficha,
//                 id_comentario
//             );
//         })
//         .fail(function (jqXHR, textStatus, errorThrown) {
//             Swal.fire({
//                 title: 'Atención',
//                 text: 'Los porcentajes de participación no se registraron',
//                 icon: 'warning',
//             });
//         });

//     cargarInfoCoPropietarios();
// }

// Guarda los porcentajes actualizados tanto de los propietarios como beneficiarios
// function guardarCoPropietarioPorcentaje() {
//     let formData = new URLSearchParams(); // Cambiamos a URLSearchParams para que PHP lo reciba correctamente

//     var jsonInformacionNueva = obtenerValoresFormulario('copropietario_porcentaje');

//     // 🔹 Capturar inputs de propietarios y mantener el formato esperado por el PHP
//     $(".porcentaje-propietario").each(function () {
//         let id = $(this).data("id");
//         let valor = parseFloat($(this).val()) || 0;
//         formData.append(`${id}||porc_part_base||`, valor);
//     });

//     // 🔹 Capturar inputs de beneficiarios con el formato correcto
//     $(".porcentaje-beneficiario").each(function () {
//         let idPropietario = $(this).data("id");
//         let idBeneficiario = $(this).attr("name"); // Nombre debe tener el ID del beneficiario
//         let valor = parseFloat($(this).val()) || 0;
//         formData.append(`${idPropietario}||porc_part||${idBeneficiario}|`, valor);
//     });

//     // 🔹 Validar que la suma de propietarios sea 100%
//     let totalPropietarios = 0;
//     $(".porcentaje-propietario").each(function () {
//         totalPropietarios += parseFloat($(this).val()) || 0;
//     });

//     if (totalPropietarios !== 100) {
//         Swal.fire({
//             title: 'Atención',
//             text: `Debe modificar los porcentajes de los propietarios. El total debe ser 100%. (Actual: ${totalPropietarios}%)`,
//             icon: 'warning',
//         });
//         return;
//     }

//     // 🔹 Validar que CADA propietario tenga beneficiarios que sumen máximo 100%
//     let propietarios = {};

//     $(".porcentaje-beneficiario").each(function () {
//         let idPropietario = $(this).data("id");
//         let valor = parseFloat($(this).val()) || 0;

//         if (!propietarios[idPropietario]) {
//             propietarios[idPropietario] = 0;
//         }

//         propietarios[idPropietario] += valor;
//     });

//     for (let id in propietarios) {
//         if (propietarios[id] > 100) {
//             Swal.fire({
//                 title: 'Atención',
//                 text: `Los beneficiarios del propietario ID ${id} superan el 100%. Ajuste los porcentajes.`,
//                 icon: 'warning',
//             });
//             return;
//         }
//     }

//     // 🔹 Enviar los datos al servidor
//     var id_ficha = $('#ficha_tecnica').val();
//     var url = window.location.href;
//     var parametros = new URL(url).searchParams;
//     formData.append('token', parametros.get('token'));

//     $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//     $.ajax({
//         url: 'components/propiedad/models/update_copropietario_porcentajes.php',
//         type: 'post',
//         dataType: 'text',
//         data: formData.toString(), // Convertimos el FormData a string para PHP
//         cache: false,
//         contentType: "application/x-www-form-urlencoded", // Para que PHP lo reciba correctamente
//         processData: false,
//     })
//         .done(function (res) {
//             Swal.fire({
//                 title: 'Porcentajes de Participación Actualizados',
//                 text: 'Los porcentajes de participación se actualizaron correctamente',
//                 icon: 'success',
//             });
//             var id_comentario = res;
//             cargarInfoCoPropietarios();
//             registroHistorial(
//                 'Editar',
//                 '',
//                 jsonInformacionNueva,
//                 'CoPropietarios - Porcentaje',
//                 id_ficha,
//                 id_comentario
//             );
//         })
//         .fail(function (jqXHR, textStatus, errorThrown) {
//             Swal.fire({
//                 title: 'Atención',
//                 text: 'Los porcentajes de participación no se registraron',
//                 icon: 'warning',
//             });
//         });

//     cargarInfoCoPropietarios();
// }

function guardarCoPropietarioPorcentaje() {
	let formData = new URLSearchParams();

	var jsonInformacionNueva = obtenerValoresFormulario(
		'copropietario_porcentaje'
	);

	console.log('🚀 Iniciando captura de datos...');

	// 🔹 Capturar inputs de propietarios y mostrar alertas
	$('.porcentaje-propietario').each(function () {
		let id = $(this).data('id') || 'SIN_ID';
		let valor = parseFloat($(this).val()) || 0;

		alert(`Propietario Capturado: ID=${id}, Valor=${valor}`);
		console.log(`✅ Propietario - ID: ${id}, Valor: ${valor}`);

		formData.append(`${id}||porc_part_base||`, valor);
	});

	// 🔹 Capturar inputs de beneficiarios y mostrar alertas
	$('.porcentaje-beneficiario').each(function () {
		let idPropietario = $(this).data('id') || 'SIN_ID';
		let idBeneficiario = $(this).attr('name') || 'SIN_NAME';
		let valor = parseFloat($(this).val()) || 0;

		alert(
			`Beneficiario Capturado: Propietario ID=${idPropietario}, Beneficiario ID=${idBeneficiario}, Valor=${valor}`
		);
		console.log(
			`✅ Beneficiario - Propietario ID: ${idPropietario}, Beneficiario ID: ${idBeneficiario}, Valor: ${valor}`
		);

		formData.append(`${idPropietario}||porc_part||${idBeneficiario}|`, valor);
	});

	// 🔹 Validar que la suma de propietarios sea 100%
	let totalPropietarios = 0;
	$('.porcentaje-propietario').each(function () {
		totalPropietarios += parseFloat($(this).val()) || 0;
	});

	if (totalPropietarios !== 100) {
		Swal.fire({
			title: 'Atención',
			text: `Debe modificar los porcentajes de los propietarios. El total debe ser 100%. (Actual: ${totalPropietarios}%)`,
			icon: 'warning',
		});
		return;
	}

	// 🔹 Validar que CADA propietario tenga beneficiarios que sumen máximo 100%
	let propietarios = {};

	$('.porcentaje-beneficiario').each(function () {
		let idPropietario = $(this).data('id');
		let valor = parseFloat($(this).val()) || 0;

		if (!propietarios[idPropietario]) {
			propietarios[idPropietario] = 0;
		}

		propietarios[idPropietario] += valor;
	});

	for (let id in propietarios) {
		if (propietarios[id] > 100) {
			Swal.fire({
				title: 'Atención',
				text: `Los beneficiarios del propietario ID ${id} superan el 100%. Ajuste los porcentajes.`,
				icon: 'warning',
			});
			return;
		}
	}

	// 🔹 Enviar los datos al servidor y mostrar alerta con los datos enviados
	alert(`Enviando datos al servidor: ${formData.toString()}`);
	console.log('🚀 Datos enviados al PHP:', formData.toString());

	var id_ficha = $('#ficha_tecnica').val();
	var url = window.location.href;
	var parametros = new URL(url).searchParams;
	formData.append('token', parametros.get('token'));

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/propiedad/models/update_copropietario_porcentajes.php',
		type: 'post',
		dataType: 'text',
		data: formData.toString(),
		cache: false,
		contentType: 'application/x-www-form-urlencoded',
		processData: false,
	})
		.done(function (res) {
			alert(`Respuesta del servidor: ${res}`);
			console.log('✅ Respuesta del servidor:', res);
			Swal.fire({
				title: 'Porcentajes de Participación Actualizados',
				text: 'Los porcentajes de participación se actualizaron correctamente',
				icon: 'success',
			});
			cargarInfoCoPropietarios();
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			alert(`Error en la petición: ${textStatus} - ${errorThrown}`);
			console.error('❌ Error en la solicitud:', textStatus, errorThrown);
			Swal.fire({
				title: 'Atención',
				text: 'Los porcentajes de participación no se registraron',
				icon: 'warning',
			});
		});

	cargarInfoCoPropietarios();
}

function calculateSum() {
	const inputs = document.querySelectorAll('.porcentaje_participacion');

	console.log('inputs: ', inputs);
	let sum = 0;

	inputs.forEach((input) => {
		sum += parseFloat(input.value) || 0;
	});

	console.log('SUMA DE PORCENTAJES: ', sum);
	// document.getElementById('sumResult').innerHTML = 'Total: ' + sum.toFixed(1);

	return sum;
}

// function busquedaDNIProp() {
// 	// Realizar la solicitud AJAX para obtener los datos
// 	var idFicha = $('#ficha_tecnica').val();
// 	//var formData = dni;

// 	const input = document.getElementById('DNIProp');
// 	let inputPersonaToken = document.getElementById('persona');
// 	var dni = input.value;
// 	var idCtaBanc = $('#suggested_cta_banc').val();

// 	//
// 	// guarda el rut en localstorage para capturar en la otra pantalla
// 	//
// 	token_propiedad = $('#tokenPropiedad').val();
// 	localStorage.setItem('Rutaregistrar', dni);
// 	localStorage.setItem('TokenPropiedad', token_propiedad);

// 	if (dni === '' || dni === null) {
// 		Swal.fire({
// 			icon: 'info',
// 			title: 'Por favor rellene la información',
// 			text: 'Es necesario rellenar la información para continuar.',
// 		});

// 		return;
// 	}

// 	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

// 	$.ajax({
// 		url: 'components/propiedad/models/busca_dni_prop.php',
// 		type: 'POST',
// 		data: 'dni=' + dni + '&cta_banc=' + idCtaBanc,
// 		success: function (resp) {
// 			/*
// 		}).done(function (res) {
// 		console.log(res);
// 		  var retorno = res.split("||");
// 		  var resultado = retorno[1];
// 		  var mensaje = retorno[2];
// 		*/
// 			var retorno = resp.split('||');
// 			var resultado = retorno[0];
// 			console.log('resultado');
// 			console.log(resultado);
// 			var mensaje = retorno[2];
// 			console.log(mensaje);

// 			if (resultado == 'ERROR') {
// 				if (mensaje === 'propietario') {
// 					sessionStorage.setItem('propietarioDNI', dni);

// 					Swal.fire({
// 						title: 'El cliente no esta registrado',
// 						text: 'Serás redirigido para agregarlo como cliente',
// 						icon: 'info',
// 						showConfirmButton: true,
// 						allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
// 						willClose: () => {
// 							// Redireccionar a otra página cuando la alerta se cierre
// 							window.location.href = 'index.php?component=persona&view=persona';
// 						},
// 					}).then((result) => {
// 						// Verificar si el usuario confirmó la alerta
// 						if (result.isConfirmed) {
// 							// Redireccionar a otra página si se confirma la alerta
// 							window.location.href = 'index.php?component=persona&view=persona';
// 						}
// 					});

// 					//alert(
// 					// "No existe DNI como propietario por lo que se debe crear en la plataforma"
// 					// );
// 					//document.location.href =
// 					//"index.php?component=propietario&view=propietario";
// 					return;
// 				}
// 			} else {
// 				var json = JSON.parse(retorno[3]);
// 				console.log(json);

// 				$.ajax({
// 					url: 'components/propiedad/models/listado_copropietarios.php',

// 					type: 'POST',
// 					dataType: 'json',
// 					data: { idFicha: idFicha },
// 					cache: false,
// 					success: function (data) {
// 						console.log('DATA btn busqueda dni pro: ', data);

// 						/*VALIDA SI TOKEN PROPIETARIO EXISTE EN LISTA COPROPIETARIOS*/
// 						if (data != null) {
// 							// var existsPropietario = data.find((item) => {
// 							//   return (
// 							//     item?.token_propietario == json[0].token_prop && item?.id_cta_banc == json[0].id
// 							//   );
// 							// });

// 							var existsPropietario = data.find((item) => {
// 								return item?.token_propietario == json[0].token_prop;
// 							});

// 							if (existsPropietario) {
// 								Swal.fire({
// 									title: 'Atención',
// 									text:
// 										'Ya se encuentra agregado en la lista el Propietario ' +
// 										json[0].dni,
// 									icon: 'warning',
// 								});
// 								return;
// 							}

// 							inputPersonaToken.value = mensaje;
// 							var personaJson = retorno[3];
// 							var personaJson = JSON.parse(personaJson);
// 							inputPersonaToken.value = mensaje;

// 							cargarInfoPersonalProp(personaJson);
// 							let botonAgregarCoprop =
// 								document.getElementById('agregar_coprop');

// 							botonAgregarCoprop.style.display = 'block';
// 						}
// 					},
// 					error: function (jqXHR, textStatus, errorThrown) {
// 						// Manejar errores si es necesario
// 						console.log('error', jqXHR, textStatus, errorThrown);
// 					},
// 				});
// 			}
// 		},
// 	});
// }

function busquedaDNIProp() {
	var dni = $('#DNIProp').val();

	if (!dni) {
		Swal.fire({
			icon: 'info',
			title: 'Por favor ingrese un RUT',
			text: 'Debes ingresar un RUT válido para buscar al propietario.',
		});
		return;
	}

	$.ajax({
		url: 'components/propiedad/models/leer_copropietarios.php',
		type: 'POST',
		data: { dni: dni },
		dataType: 'json',
		success: function (data) {
			console.log('Resultados búsqueda RUT:', data);

			if (!data || data.length === 0) {
				Swal.fire({
					title: 'No encontrado',
					text: 'Este RUT no corresponde a ningún propietario registrado.',
					icon: 'warning',
				});
				return;
			}

			// ✅ Tomamos el primer propietario del array recibido
			let propietario = data[0];

			// Llenamos el formulario con los datos obtenidos
			cargarInfoPersonalPropietarios(propietario);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error(
				'Error en la búsqueda del propietario:',
				jqXHR,
				textStatus,
				errorThrown
			);
			Swal.fire('Error', 'Hubo un problema al buscar el RUT.', 'error');
		},
	});
}

function cargarInfoPersonalPropietarios(propietario) {
	console.log('Llenando formulario con:', propietario);

	// 🔹 Ocultar secciones inicialmente
	$('#section-info-cliente-natural').hide();
	$('#section-info-cliente-juridico').hide();
	$('#section-info-cta-bancaria').hide();

	// 🔹 Llenar datos generales
	$('#nombrePersona').text(propietario.nombre_completo || '-');
	$('#telefonoMovilPersona').text(propietario.telefono || '-');
	$('#emailPersona').text(propietario.correo_electronico || '-');
	$('#direccionPersona').text(propietario.direccion || '-');
	$('#linkMaps').attr('href', propietario.ubicacion || '#');

	$('#suggested_cta_banc').val(propietario.id_cta_bancaria);
	$('#idPropietarioNuevo').val(propietario.id_persna);

	// 🔹 Determinar el tipo de persona y mostrar la sección correspondiente
	if (propietario.id_tipo_persona == 1) {
		// NATURAL
		$('#section-info-cliente-natural').show();
		$('#tipoPersona').text('Natural');
	} else if (propietario.id_tipo_persona == 2) {
		// JURÍDICA
		$('#section-info-cliente-juridico').show();
		$('#razonPersonaJuridica').text(propietario.razon_social || '-');
		$('#tipoPersonaJuridica').text('Jurídica');
	}

	// 🔹 Llenar información bancaria si está disponible
	if (propietario.cuenta_numero) {
		$('#section-info-cta-bancaria').show();
		$('#ctaBancNombreTitular').text(propietario.cuenta_nombre_titular || '-');
		$('#ctaBancRutTitular').text(propietario.cuenta_rut_titular || '-');
		$('#ctaBancNumero').text(propietario.cuenta_numero || '-');
	}

	// 🔹 Mostrar formulario y botón "Añadir"
	$('#formulario-propietario').show();
	$('#agregar_coprop').show();
}

/*MÉTODOS INFORMACIÓN PROPIETARIO BUSCADO */
function cargarInfoPersonalProp(infoJSON) {
	console.log('infoJSON: ', infoJSON);
	$('#idPropietario').val(infoJSON[0].id_propietario);

	if (infoJSON[0].tipo_persona === 'NATURAL') {
		$('#nombrePersona').text(
			infoJSON[0].nombres +
				' ' +
				infoJSON[0].apellido_paterno +
				' ' +
				infoJSON[0].apellido_materno
		);
		$('#telefonoMovilPersona').text(infoJSON[0].telefono_fijo);
		$('#emailPersona').text(infoJSON[0].correo_electronico);
		$('#tipoPersona').text(infoJSON[0].tipo_persona);
		$('#direccionPersona').text(
			infoJSON[0].direccion +
				' #' +
				infoJSON[0].numero +
				', ' +
				infoJSON[0].comuna +
				', ' +
				infoJSON[0].region +
				', ' +
				infoJSON[0].pais
		);
		var urlMaps =
			'https://www.google.com/maps/place/' +
			infoJSON[0].direccion +
			'+%23' +
			infoJSON[0].numero +
			',+' +
			infoJSON[0].comuna +
			',+' +
			infoJSON[0].region +
			',+' +
			infoJSON[0].pais;

		$('#linkMaps').attr('href', urlMaps);
		$('#section-info-cliente-juridico').css('display', 'none');
		$('#section-info-cliente-natural').css('display', 'block');
	} else {
		$('#nombrePersonaJuridica').text(infoJSON[0].nombre_fantasia);
		$('#razonPersonaJuridica').text(infoJSON[0].razon_social);
		$('#telefonoMovilPersonaJuridica').text(infoJSON[0].telefono_fijo);
		$('#emailPersonaJuridica').text(infoJSON[0].correo_electronico);
		$('#tipoPersonaJuridica').text(infoJSON[0].tipo_persona);
		$('#direccionPersonaJuridica').text(
			infoJSON[0].direccion +
				' #' +
				infoJSON[0].numero +
				', ' +
				infoJSON[0].comuna +
				', ' +
				infoJSON[0].region +
				', ' +
				infoJSON[0].pais
		);
		var urlMaps =
			'https://www.google.com/maps/place/' +
			infoJSON[0].direccion +
			'+%23' +
			infoJSON[0].numero +
			',+' +
			infoJSON[0].comuna +
			',+' +
			infoJSON[0].region +
			',+' +
			infoJSON[0].pais;

		$('#linkMapsJuridica').attr('href', urlMaps);
		$('#section-info-cliente-natural').css('display', 'none');
		$('#section-info-cliente-juridico').css('display', 'block');
	}

	$('#ctaBancNombreTitularDeCuenta').text(
		infoJSON[0].nombre_titular +
			' ' +
			infoJSON[0].apellido_paterno +
			' ' +
			infoJSON[0].apellido_materno
	);
	$('#ctaBancRutTitular').text(infoJSON[0].rut_titular);
	$('#ctaBancNumero').text(infoJSON[0].numero_cta_banc);
	$('#section-info-cta-bancaria').css('display', 'block');
}

// Agrega al propietario buscado
function guardarInfoCoPropietario() {
	var formData = new FormData();

	const idPropietario_input = document.getElementById('idPropietarioNuevo');
	var idPropietario = idPropietario_input.value;

	alert(idPropietario);

	formData.append('idPropietarioNuevo', idPropietario);

	var id_ficha = $('#ficha_tecnica').val();
	formData.append('idFicha', id_ficha);

	var idCtaBanc = $('#suggested_cta_banc').val();
	formData.append('idCtaBancaria', idCtaBanc);

	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	//console.log(parametros.get("token"));
	formData.append('token', parametros.get('token'));

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/propiedad/models/insert_info_copropietario.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			Swal.fire({
				title: 'Propietario registrado',
				text: 'El propietario se registró correctamente',
				icon: 'success',
			});
			var id_comentario = res;

			limpiarInfoPersonalProp();
			cargarInfoCoPropietarios();
			// registroHistorial("Crear", "", "", "Propietario", id_ficha, id_comentario);
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			Swal.fire({
				title: 'Atención',
				text: 'El propietario no se registró',
				icon: 'warning',
			});
		});
}

function limpiarInfoPersonalProp() {
	$('#DNIProp').val('');

	$('#nombrePersona').val('');
	$('#telefonoMovilPersona').val('');
	$('#emailPersona').val('');
	$('#tipoPersona').val('');
	$('#direccionPersona').val('');
	$('#linkMaps').attr('href', '#');

	$('#nombrePersonaJuridica').val('');
	$('#razonPersonaJuridica').val('');
	$('#telefonoMovilPersonaJuridica').val('');
	$('#emailPersonaJuridica').val('');
	$('#tipoPersonaJuridica').val('');
	$('#direccionPersonaJuridica').val('');
	$('#linkMapsJuridica').attr('href', '#');

	$('#ctaBancNombreTitular').val('');
	$('#ctaBancRutTitular').val('');
	$('#ctaBancNumero').val('');

	$('#section-info-cta-bancaria').css('display', 'none');
	$('#section-info-cliente-juridico').css('display', 'none');
	$('#section-info-cliente-natural').css('display', 'none');
}

// Agrega al beneficiario
function guardarInfoBeneficiario() {
	var formData = new FormData(document.getElementById('ingreso_beneficiario'));

	var jsonInformacionNueva = obtenerValoresFormulario('ingreso_beneficiario');

	const idPropietario_input = document.getElementById('idPropietario');
	var idPropietario = idPropietario_input.value;

	formData.append('idPropietario', idPropietario);

	const idRegistro_input = document.getElementById('idRegistro');
	var idRegistro = idRegistro_input.value;

	formData.append('idRegistro', idRegistro);

	var id_ficha = $('#ficha_tecnica').val();
	formData.append('idFicha', id_ficha);

	const beneficiario_nombre_input =
		document.getElementById('nombreBeneficiario');
	var nombreBeneficiario = beneficiario_nombre_input.value;

	const beneficiario_rut_input = document.getElementById('rutBeneficiario');
	var rutBeneficiario = beneficiario_rut_input.value;

	const beneficiario_correo_input = document.getElementById(
		'correoElectronicoBeneficiario'
	);
	var correoElectronicoBeneficiario = beneficiario_correo_input.value;

	const beneficiario_telefono_fijo_input = document.getElementById(
		'beneficiarioTelefonoFijo'
	);
	var beneficiarioTelefonoFijo = beneficiario_telefono_fijo_input.value;

	const beneficiario_telefono_movil_input = document.getElementById(
		'beneficiarioTelefonoMovil'
	);
	var beneficiarioTelefonoMovil = beneficiario_telefono_movil_input.value;

	/*----- */
	const nombre_titular_input = document.getElementById('nombreTitular');
	var nombreTitular = nombre_titular_input.value;

	const rut_titular_input = document.getElementById('rutTitular');
	var rutTitular = rut_titular_input.value;

	const email_titular_input = document.getElementById('emailTitular');
	var emailTitular = email_titular_input.value;

	const banco_input = document.getElementById('banco');
	var banco = banco_input.value;

	const cta_banco_input = document.getElementById('cta-banco');
	var cuentaBanco = cta_banco_input.value;

	const numCuenta_input = document.getElementById('numCuenta');
	var numCuenta = numCuenta_input.value;

	// if (nombreBeneficiario == null || nombreBeneficiario == "") {
	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe agregar un nombre",
	//     icon: "warning",
	//   });
	//   return;
	// }

	// if (rutBeneficiario == null || rutBeneficiario == "") {
	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe agregar un rut",
	//     icon: "warning",
	//   });
	//   return;
	// }

	// if (correoElectronicoBeneficiario == null || correoElectronicoBeneficiario == "") {
	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe agregar un correo electrónico",
	//     icon: "warning",
	//   });
	//   return;
	// }

	// if (beneficiarioTelefonoFijo == null || beneficiarioTelefonoFijo == "") {
	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe ingresar telefono fijo",
	//     icon: "warning",
	//   });
	//   return;
	// }

	// if (beneficiarioTelefonoMovil == null || beneficiarioTelefonoMovil == "") {
	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe ingresar telefono movil",
	//     icon: "warning",
	//   });
	//   return;
	// }

	// /*-------- */
	// if (nombreTitular == null || nombreTitular == "") {
	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe ingresar nombre titular",
	//     icon: "warning",
	//   });
	//   return;
	// }

	// if (rutTitular == null || rutTitular == "") {
	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe ingresar rut titular",
	//     icon: "warning",
	//   });
	//   return;
	// }

	// if (emailTitular == null || emailTitular == "") {
	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe ingresar email titular",
	//     icon: "warning",
	//   });
	//   return;
	// }

	// if (banco == null || banco == "") {
	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe seleccionar un banco",
	//     icon: "warning",
	//   });
	//   return;
	// }

	// if (cuentaBanco == null || cuentaBanco == "") {
	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe seleccionar una cuenta de banco",
	//     icon: "warning",
	//   });
	//   return;
	// }

	// if (numCuenta == null || numCuenta == "") {
	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe ingresar un numero de cuenta",
	//     icon: "warning",
	//   });
	//   return;
	// }

	/*VALIDACIONES FORMATOS */
	/*VALIDA QUE NOMBRE BENEFICIARIO TENGA FORMATO CORRECTO */
	if (nombreBeneficiario == null || nombreBeneficiario == '') {
		$('#nombreBeneficiario')[0].setCustomValidity(
			'Debe ingresar Nombre Beneficiario'
		);
		$('#nombreBeneficiario')[0].reportValidity();

		return;
	}

	/*VALIDA QUE TELEFONO FIJO TENGA FORMATO CORRECTO */
	if (beneficiarioTelefonoFijo == null || beneficiarioTelefonoFijo == '') {
		$('#beneficiarioTelefonoFijo')[0].setCustomValidity(
			'Debe ingresar Teléfono Fijo'
		);
		$('#beneficiarioTelefonoFijo')[0].reportValidity();

		return;
	}

	/*VALIDA QUE NOMBRE BENEFICIARIO TENGA FORMATO CORRECTO */
	if (beneficiarioTelefonoMovil == null || beneficiarioTelefonoMovil == '') {
		$('#beneficiarioTelefonoMovil')[0].setCustomValidity(
			'Debe ingresar Teléfono Móvil'
		);
		$('#beneficiarioTelefonoMovil')[0].reportValidity();

		return;
	}

	/*VALIDA QUE RUT TENGA FORMATO CORRECTO */
	console.log('rutBeneficiario: ', rutBeneficiario);
	if (!validarRutChile(rutBeneficiario)) {
		$('#rutBeneficiario')[0].setCustomValidity('Rut inválido');
		$('#rutBeneficiario')[0].reportValidity();

		return;
	}

	if (!validarEmail(correoElectronicoBeneficiario)) {
		$('#correoElectronicoBeneficiario')[0].setCustomValidity('Email inválido');
		$('#correoElectronicoBeneficiario')[0].reportValidity();
		return;
	}

	if (banco == null || banco == '') {
		$('#banco')[0].setCustomValidity('Debe seleccionar un Banco');
		$('#banco')[0].reportValidity();
		return;
	}

	if (cuentaBanco == null || cuentaBanco == '') {
		$('#cta-banco')[0].setCustomValidity(
			'Debe seleccionar una Cuenta de Banco'
		);
		$('#cta-banco')[0].reportValidity();
		return;
	}

	if (numCuenta == null || numCuenta == '') {
		$('#numCuenta')[0].setCustomValidity('Debe ingresar número de Cuenta');
		$('#numCuenta')[0].reportValidity();
		return;
	}

	formData.append('nombreBeneficiario', nombreBeneficiario);
	formData.append('rutBeneficiario', rutBeneficiario);
	formData.append(
		'correoElectronicoBeneficiario',
		correoElectronicoBeneficiario
	);
	formData.append('beneficiarioTelefonoFijo', beneficiarioTelefonoFijo);
	formData.append('beneficiarioTelefonoMovil', beneficiarioTelefonoMovil);

	formData.append('nombreTitular', nombreTitular);
	formData.append('rutTitular', rutTitular);
	formData.append('emailTitular', emailTitular);
	formData.append('banco', banco);
	formData.append('cuentaBanco', cuentaBanco);
	formData.append('numCuenta', numCuenta);

	var id_ficha = $('#id_ficha').val();
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	//console.log(parametros.get("token"));
	formData.append('token', parametros.get('token'));

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/propiedad/models/insert_info_beneficiario.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			var retorno = res.split(',xxx,');
			var resultado = retorno[1];
			var mensaje = retorno[2];
			var token = retorno[3];
			console.log('res', res);

			if (resultado == 'OK') {
				$('#modalBeneficiarioIngreso').modal('hide');
				$('#ingreso_beneficiario')[0].reset();

				Swal.fire({
					title: 'Beneficiario registrado',
					text: 'El beneficiario se registró correctamente',
					icon: 'success',
				});
				var id_comentario = res;
				var jsonInformacioantigua = capturarInformacionAntigua();

				cargarInfoCoPropietarios();
				registroHistorial(
					'Crear',
					'',
					jsonInformacionNueva,
					'Beneficiario',
					id_ficha,
					id_comentario
				);
				return;
			} else {
				$('#modalBeneficiarioIngreso').modal('hide');

				Swal.fire({
					title: 'Atención',
					text: 'El beneficiario no se registró',
					icon: 'warning',
				});

				return;
			}
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			$('#modalBeneficiarioIngreso').modal('hide');

			Swal.fire({
				title: 'Atención',
				text: 'El beneficiario no se registró',
				icon: 'warning',
			});
		});
	$('#ingreso_beneficiario')[0].reset();
	$('#modalBeneficiarioIngreso').modal('hide');
	cargarInfoCoPropietarios();
}

function buscarClienteAutocompleteGenerica(valor, tipo) {
	var codigo = document.getElementById(tipo).value;

	codigo = codigo.replace(/\./g, '');

	var caracteres = codigo.length;
	//Si por lo menos ha ingresado 3 caracteres comenzamos a autocompletar
	if (caracteres >= 5) {
		$.ajax({
			type: 'POST',
			url: 'components/propiedad/models/buscar_propietario_autocomplete_generica.php',
			data: 'codigo=' + codigo + '&tipo=' + tipo,
			success: function (data) {
				console.log('DATA SUGERIDO:', data);
				console.log;
				$('#suggestions_' + tipo)
					.fadeIn(500)
					.html(data);

				$('.suggest-element').on('click', function () {
					// var idCtaBanc = $("#suggested_cta_banc").val();
					var valorSugerido = $(this).text(); // Obtener el texto de la sugerencia
					var idCtaBanc = $(this).attr('name');
					console.log('idCtaBanc: ', idCtaBanc);

					$('#suggested_cta_banc').val(idCtaBanc);

					console.log('valorSugerido: ', valorSugerido);
					var primerValor = valorSugerido.split('|')[0].trim(); // Obtener el primer valor antes del '/'
					$('#' + tipo).val(primerValor); // Llenar el campo con el valor sugerido
					$('#suggestions_' + tipo).fadeOut(500); // Ocultar las sugerencias
					busquedaDNIProp();
					return false;
				});
			},
		});
	} else {
		ocultarAutocomplete(tipo);
	}
}

/*
function buscarClienteAutocompleteGenerica(valor, tipo) {
  var codigo = document.getElementById(tipo).value;
  $("#suggested_cta_banc").val("");

  var caracteres = codigo.length;
  //Si por lo menos ha ingresado 3 caracteres comenzamos a autocompletar
  if (caracteres >= 3) {
	$.ajax({
	  type: "POST",
	  url: "components/propiedad/models/buscar_propietario_autocomplete_generica.php",
	  data: "codigo=" + codigo + "&tipo=" + tipo,
	  success: function (data) {
		console.log("DATA SUGERIDO:", data);
		console.log;
		$("#suggestions_" + tipo)
		  .fadeIn(500)
		  .html(data);
	    
		$(".suggest-element").on("click", function () {
		  var idCtaBanc = $("#suggested_cta_banc").val();
		  var valorSugerido = $(this).text(); // Obtener el texto de la sugerencia
		  console.log("idCtaBanc: ", idCtaBanc);

		  console.log("valorSugerido: ", valorSugerido);
		  var primerValor = valorSugerido.split("|")[0].trim(); // Obtener el primer valor antes del '/'
		  $("#" + tipo).val(primerValor); // Llenar el campo con el valor sugerido
		  $("#suggestions_" + tipo).fadeOut(500); // Ocultar las sugerencias
		  busquedaDNIProp();
		  return false;
		});
	  },
	});
  } else {
	ocultarAutocomplete(tipo);
  }
}
*/
/*
function cargarInfoPersonalProp(infoJSON) {
  console.log("infoJSON: ", infoJSON);
  $("#idPropietario").val(infoJSON[0].id_propietario);

  if (infoJSON[0].tipo_persona === "NATURAL") {
	$("#nombrePersona").text(
	  infoJSON[0].nombres + " " + infoJSON[0].apellido_paterno + " " + infoJSON[0].apellido_materno
	);
	$("#telefonoMovilPersona").text(infoJSON[0].telefono_fijo);
	$("#emailPersona").text(infoJSON[0].correo_electronico);
	$("#tipoPersona").text(infoJSON[0].tipo_persona);
	$("#direccionPersona").text(
	  infoJSON[0].direccion +
		" #" +
		infoJSON[0].numero +
		", " +
		infoJSON[0].comuna +
		", " +
		infoJSON[0].region +
		", " +
		infoJSON[0].pais
	);
	var urlMaps =
	  "https://www.google.com/maps/place/" +
	  infoJSON[0].direccion +
	  "+%23" +
	  infoJSON[0].numero +
	  ",+" +
	  infoJSON[0].comuna +
	  ",+" +
	  infoJSON[0].region +
	  ",+" +
	  infoJSON[0].pais;

	$("#linkMaps").attr("href", urlMaps);
	$("#section-info-cliente-juridico").css("display", "none");
	$("#section-info-cliente-natural").css("display", "block");
  } else {
	$("#nombrePersonaJuridica").text(infoJSON[0].nombre_fantasia);
	$("#razonPersonaJuridica").text(infoJSON[0].razon_social);
	$("#telefonoMovilPersonaJuridica").text(infoJSON[0].telefono_fijo);
	$("#emailPersonaJuridica").text(infoJSON[0].correo_electronico);
	$("#tipoPersonaJuridica").text(infoJSON[0].tipo_persona);
	$("#direccionPersonaJuridica").text(
	  infoJSON[0].direccion +
		" #" +
		infoJSON[0].numero +
		", " +
		infoJSON[0].comuna +
		", " +
		infoJSON[0].region +
		", " +
		infoJSON[0].pais
	);
	var urlMaps =
	  "https://www.google.com/maps/place/" +
	  infoJSON[0].direccion +
	  "+%23" +
	  infoJSON[0].numero +
	  ",+" +
	  infoJSON[0].comuna +
	  ",+" +
	  infoJSON[0].region +
	  ",+" +
	  infoJSON[0].pais;

	$("#linkMapsJuridica").attr("href", urlMaps);
	$("#section-info-cliente-natural").css("display", "none");
	$("#section-info-cliente-juridico").css("display", "block");
  }

  $("#ctaBancNombreTitular").text(infoJSON[0].nombre_titular);
  $("#ctaBancRutTitular").text(infoJSON[0].rut_titular);
  $("#ctaBancNumero").text(infoJSON[0].numero_cta_banc);
  $("#section-info-cta-bancaria").css("display", "block");
  
}
*/

function resetFormById(idForm) {
	$('#' + idForm)[0].reset();
}

function formateoFecha(fecha) {
	let f = new Date(fecha);
	return f.toLocaleString('es-ES', { month: 'long', year: 'numeric' });
}

function cargarInfoCtaServicios() {
	// Realizar la solicitud AJAX para obtener los datos
	var idFicha = $('#ficha_tecnica').val();
	console.log('id ficha: ', idFicha);
	$.ajax({
		url: 'components/propiedad/models/listado_cta_servicios.php',
		type: 'POST',
		dataType: 'json',
		data: { idFicha: idFicha },
		cache: false,
		success: function (data) {
			console.log('DATA: ', data);
			if (data != null) {
				var tbody = $('#info-ctas-servicio tbody');
				// Limpiar el cuerpo de la tabla por si hay datos anteriores
				tbody.empty();
				// Iterar sobre los datos y agregar filas a la tabla
				$.each(data, function (index, item) {
					// Crear una nueva fila de la tabla
					var newRow = $('<tr>');

					// Agregar celdas a la fila con los datos
					newRow.append(
						'<td>' + formateoNulos(formateoFecha(item.fecha)) + '</td>'
					);
					newRow.append('<td>' + formateoNulos(item.nombre_servicio) + '</td>');
					newRow.append(
						'<td>' +
							formateoNulos(formateoDivisa(item.monto_adeudado)) +
							'</td>'
					);
					newRow.append(
						`<td>
      <div class='d-flex' style='gap: .5rem;'>      
      <button id="btn-eliminar" onclick='eliminarInfoCtaServicio(${item.id})' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'>
        <i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
      </button>
    </div>
  </td>`
					);
					// Agregar la fila al cuerpo de la tabla
					tbody.append(newRow);
				});
			} else {
				var tbody = $('#info-ctas-servicio tbody');
				tbody.empty();
				var newRow = $('<tr>');
				console.log('error');
				newRow.append(
					"<td colspan='9' style='text-align:center'> No hay Cuentas de Servicios</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			console.log('error', jqXHR, textStatus, errorThrown);
		},
	});
}

function eliminarInfoCtaServicio(idInfoCtaServicio) {
	console.log('idInfoCtaServicio: ', idInfoCtaServicio);

	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Una vez eliminado, no podrás recuperar esta cuenta de servicio',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Eliminar',
		denyButtonText: 'Cancelar',
	}).then((result) => {
		if (result.isConfirmed) {
			// Si el usuario hace clic en "Eliminar"
			$.ajax({
				url: 'components/propiedad/models/delete_info_cta_servicio.php',
				type: 'POST',
				dataType: 'text',
				data: { idInfoCtaServicio: idInfoCtaServicio },
				success: function (response) {
					Swal.fire({
						title: 'Cuenta de Servicio eliminada',
						text: 'La cuenta de servicio se eliminó correctamente',
						icon: 'success',
					});
					cargarInfoCtaServicios();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.error('Error en la solicitud:', textStatus, errorThrown);
					// Aquí puedes mostrar un mensaje de error al usuario o realizar otras acciones
				},
			});
		} else if (result.isDenied) {
			// Si el usuario hace clic en "Cancelar"
			// Aquí puedes cerrar el modal de SweetAlert si lo deseas
			Swal.close();
		}
	});
}

function guardarInfoCtaServicio() {
	var formData = new FormData(document.getElementById('cta_servicio'));

	var jsonInformacionNueva = obtenerValoresFormulario('cta_servicio');

	const cta_servicio_cuenta_input = document.getElementById(
		'modalCtaServicioCuenta'
	);
	var ctaServicioCuenta = cta_servicio_cuenta_input.value;

	const cta_servicio_fecha_input = document.getElementById(
		'modalCtaServicioFecha'
	);
	var ctaServicioFecha = cta_servicio_fecha_input.value;

	const cta_servicio_monto_adeudado_input = document.getElementById(
		'modalCtaServicioMontoAdeudado'
	);
	var ctaServicioMontoAdeudado = cta_servicio_monto_adeudado_input.value;

	if (ctaServicioCuenta == null || ctaServicioCuenta == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe seleccionar una cuenta',
			icon: 'warning',
		});
		return;
	}

	if (ctaServicioFecha == null || ctaServicioFecha == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar una fecha',
			icon: 'warning',
		});
		return;
	}

	if (ctaServicioMontoAdeudado == null || ctaServicioMontoAdeudado == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar un monto adeudado',
			icon: 'warning',
		});
		return;
	}

	formData.append('ctaServicioCuenta', ctaServicioCuenta);
	formData.append('ctaServicioFecha', ctaServicioFecha);
	formData.append('ctaServicioMontoAdeudado', ctaServicioMontoAdeudado);

	var id_ficha = $('#ficha_tecnica').val();
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	//console.log(parametros.get("token"));
	formData.append('token', parametros.get('token'));

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/propiedad/models/insert_info_cta_servicio.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			$('#modalCuentaServiciosFijarEstado').modal('hide');
			$('#cta_servicio')[0].reset();

			Swal.fire({
				title: 'Cuenta de Servicio registrada',
				text: 'La cuenta de servicio se registró correctamente',
				icon: 'success',
			});
			var id_comentario = res;
			var jsonInformacioantigua = capturarInformacionAntigua();

			cargarInfoCtaServicios();
			registroHistorial(
				'Crear',
				'',
				jsonInformacionNueva,
				'Cuenta de Servicio',
				id_ficha,
				id_comentario
			);
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			$('#modalCuentaServiciosFijarEstado').modal('hide');

			Swal.fire({
				title: 'Atención',
				text: 'La cuenta de servicio no se registró',
				icon: 'warning',
			});
		});
	$('#cta_servicio')[0].reset();
	$('#modalCuentaServiciosFijarEstado').modal('hide');
	cargarInfoCtaServicios();
}

//********************************  AGREGAR PROPIETARIO **********************************
function buscarPropiedadAutocomplete(valor, tipo) {
	var codigo = document.getElementById(tipo).value;
	$('#suggested_cta_banc').val('');

	var caracteres = codigo.length;
	//Si por lo menos ha ingresado 3 caracteres comenzamos a autocompletar
	if (caracteres >= 5) {
		$.ajax({
			type: 'POST',
			url: 'components/propiedad/models/buscar_propietario_autocomplete_generica.php',
			data: 'codigo=' + codigo + '&tipo=' + tipo,
			success: function (data) {
				console.log('DATA SUGERIDO:', data);
				console.log;

				$('#suggestions_' + tipo)
					.fadeIn(500)
					.html(data);

				$('.suggest-element').on('click', function () {
					var idCtaBanc = $('#suggested_cta_banc').val();
					var valorSugerido = $(this).text(); // Obtener el texto de la sugerencia
					console.log('idCtaBanc: ', idCtaBanc);

					console.log('valorSugerido: ', valorSugerido);
					var primerValor = valorSugerido.split('|')[0].trim(); // Obtener el primer valor antes del '/'
					$('#' + tipo).val(primerValor); // Llenar el campo con el valor sugerido
					$('#suggestions_' + tipo).fadeOut(500); // Ocultar las sugerencias
					//busquedaDNIProp();
					return false;
				});
			},
		});
	} else {
		ocultarAutocomplete(tipo);
	}
}

function busquedaDNIPropietario() {
	//var formData = dni;

	const input = document.getElementById('DNIPropietario');
	let inputPersonaToken = document.getElementById('persona_formulario');

	var dni = input.value;
	console.log('dni enviado');
	console.log(dni);

	var dniBuscar = $('#DNI').val();

	if (dniBuscar !== '') {
		$.ajax({
			url: 'components/propiedad/models/busca_dni_prop.php',
			type: 'POST',
			data: 'dni=' + dni,
			success: function (resp) {
				var retorno = resp.split('||');
				var resultado = retorno[0];
				console.log('resultado');
				console.log(resultado);
				var mensaje = retorno[2];
				console.log(mensaje);

				if (resultado == 'ERROR') {
					///$.showAlert({ title: "Atención", body: mensaje });

					if (mensaje === 'persona') {
						// Guardar un valor en sessionStorage
						sessionStorage.setItem('personaDNI', dni);

						// Mostrar SweetAlert2 con un botón de confirmación
						Swal.fire({
							/*
			  title: alertaJSON.clientesErrorBusqueda.titulo,
			  text: alertaJSON.clientesErrorBusqueda.mensaje,
			  icon: alertaJSON.clientesErrorBusqueda.icono,
		*/
							title: 'Registro de Propietario',
							text: 'Serás redirigido para crear el cliente en el sistema. Con esto podrás realizar la creación de la propiedad y dejar asociado al cliente como propietario',
							icon: 'info',
							showConfirmButton: true,
							allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
							willClose: () => {
								// Redireccionar a otra página cuando la alerta se cierre
								window.location.href =
									'index.php?component=persona&view=persona';
							},
						}).then((result) => {
							// Verificar si el usuario confirmó la alerta
							if (result.isConfirmed) {
								// Redireccionar a otra página si se confirma la alerta
								window.location.href =
									'index.php?component=persona&view=persona';
							}
						});

						return;
					}
					if (mensaje === 'propietario') {
						sessionStorage.setItem('propietarioDNI', dni);

						Swal.fire({
							title: 'Registro de Propietario',
							text: 'Serás redirigido para asociar al cliente como propietario. Con esto podrás realizar la creación de la propiedad.',
							icon: 'info',
							showConfirmButton: true,
							allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
							willClose: () => {
								// Redireccionar a otra página cuando la alerta se cierre
								window.location.href =
									'index.php?component=propietario&view=propietario';
							},
						}).then((result) => {
							// Verificar si el usuario confirmó la alerta
							if (result.isConfirmed) {
								// Redireccionar a otra página si se confirma la alerta
								window.location.href =
									'index.php?component=propietario&view=propietario';
							}
						});

						//alert(
						// "No existe DNI como propietario por lo que se debe crear en la plataforma"
						// );
						//document.location.href =
						//"index.php?component=propietario&view=propietario";
						return;
					}
				} else {
					// document.location.href =
					//   "index.php?component=arrendatario&view=arrendatario&persona=" + mensaje;

					inputPersonaToken.value = mensaje;
					var personaJson = retorno[3];
					var personaJson = JSON.parse(personaJson);
					inputPersonaToken.value = mensaje;
					console.log(personaJson);
					cargarInfoPersonal(personaJson);
					onChangePersona();
					$('#section-1').show();
					$('#section-2').show();
					$('#section-3').show();
					$('#section-4').show();
					$('#bt_aceptar_propiedad').show();
				}
			},
		});
	} else {
		Swal.fire({
			icon: 'info',
			title: 'Por favor rellene la información',
			text: 'Es necesario rellenar la información para continuar.',
		});
		//$.showAlert({ title: "Atención", body: "Debe escribir un DNI/RUT" });
	}
} //function enviar

function cargarInfoCoPropietariosPropiedad() {
	// Realizar la solicitud AJAX para obtener los datos
	var idFicha = $('#ficha_tecnica').val();
	console.log('id ficha: ', idFicha);
	$.ajax({
		url: 'components/propiedad/models/listado_copropietarios_propiedad.php',
		type: 'POST',
		dataType: 'json',
		data: { idFicha: idFicha },
		cache: false,
		success: function (data) {
			console.log('DATA: ', data);
			if (data != null) {
				var tbody = $('#info-propietarios-propiedad tbody');
				// Limpiar el cuerpo de la tabla por si hay datos anteriores
				tbody.empty();

				// Iterar sobre los datos y agregar filas a la tabla
				$.each(data, function (index, item) {
					// Crear una nueva fila de la tabla
					if (item.nivel_propietario == 1) {
						var newRow = $('<tr>');

						// Agregar celdas a la fila con los datos
						newRow.append('<td>' + formateoNulos(item.nombre) + '</td>');
						newRow.append(
							'<td>' + formateoNulos(formatRutChile(item.dni)) + '</td>'
						);
						newRow.append('<td>' + formateoNulos(item.tipo_persona) + '</td>');
						newRow.append(
							'<td>' + formateoNulos(item.nombre_titular) + '</td>'
						);
						newRow.append(
							'<td>' + formateoNulos(formatRutChile(item.rut_titular)) + '</td>'
						);
						newRow.append('<td>' + formateoNulos(item.cuenta_banco) + '</td>');
						newRow.append(
							'<td>' +
								formateoNulos(item.porcentaje_participacion_base) +
								'</td>'
						);
						newRow.append(
							'<td>' + formateoNulos(item.porcentaje_participacion) + '</td>'
						);
					} else {
						var newRow = $("<tr class='table-info'>");

						// Agregar celdas a la fila con los datos
						newRow.append('<td>' + formateoNulos(item.nombre) + '</td>');
						newRow.append(
							'<td>' + formateoNulos(formatRutChile(item.dni)) + '</td>'
						);
						newRow.append('<td>' + formateoNulos(item.tipo_persona) + '</td>');
						newRow.append(
							'<td>' + formateoNulos(item.nombre_titular) + '</td>'
						);
						newRow.append(
							'<td>' + formateoNulos(formatRutChile(item.rut_titular)) + '</td>'
						);
						newRow.append('<td>' + formateoNulos(item.cuenta_banco) + '</td>');
						newRow.append(
							'<td>' +
								formateoNulos(item.porcentaje_participacion_base) +
								'</td>'
						);
						newRow.append(
							'<td>' + formateoNulos(item.porcentaje_participacion) + '</td>'
						);
					}

					// Agregar la fila al cuerpo de la tabla
					tbody.append(newRow);
				});
			} else {
				var tbody = $('#info-propietarios-propiedad tbody');
				tbody.empty();
				var newRow = $('<tr>');
				console.log('error');
				newRow.append(
					"<td colspan='9' style='text-align:center'> No hay Copropietarios</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			console.log('error', jqXHR, textStatus, errorThrown);
		},
	});
}

function cargarLiquidaciones() {
	cargarCCMovimientosList();

	var fichaLiq = $('#ficha_tecnica').val();
	$('#modalLiqCoPropsLiquidar').modal('hide');

	$.ajax({
		url: 'components/propiedad/models/listado_liquidaciones.php',
		type: 'POST',
		dataType: 'json',
		data: { fichaLiq: fichaLiq },
		cache: false,
		success: function (data) {
			if (data != null) {
				var tbody = $('#liProp1 tbody');
				// Limpiar el cuerpo de la tabla por si hay datos anteriores
				tbody.empty();
				// Función para reemplazar valores nulos por 0
				function replaceNull(value) {
					return value === null ? 0 : value;
				}
				// Iterar sobre los datos y agregar filas a la tabla
				$.each(data, function (index, item) {
					// Crear una nueva fila de la tabla
					var newRow = $('<tr>');

					// Agregar celdas a la fila con los datos
					var fecha_liquidacion = moment(item.fecha_liquidacion).format(
						'DD-MM-YYYY'
					);
					newRow.append('<td>' + fecha_liquidacion + '</td>');
					newRow.append('<td>' + replaceNull(item.id_ficha_arriendo) + '</td>');
					newRow.append(
						'<td>$' +
							replaceNull(item.comision).toLocaleString('es-ES') +
							'</td>'
					);
					newRow.append(
						'<td>$' + replaceNull(item.iva).toLocaleString('es-ES') + '</td>'
					);
					newRow.append(
						'<td>$' + replaceNull(item.abonos).toLocaleString('es-ES') + '</td>'
					);
					newRow.append(
						'<td>$' +
							replaceNull(item.descuentos).toLocaleString('es-ES') +
							'</td>'
					);
					newRow.append(
						'<td>$' + replaceNull(item.total).toLocaleString('es-ES') + '</td>'
					);
					if (item.url_liquidacion != null) {
						var enlaceArchivo =
							'<a href="' +
							item.url_liquidacion +
							'" download="" type="button" class="btn btn-info m-0 d-flex" style="padding: .5rem; width: 27px;" aria-label="documento" title="documento"><i class="fa-solid fa-file" style="font-size: .75rem;"></i></a>';
						newRow.append('<td> ' + enlaceArchivo + ' </td>');
					} else {
						newRow.append('<td> -</td>');
					}
					newRow.append('</tr>');

					// Agregar la fila al cuerpo de la tabla
					tbody.append(newRow);
				});
			} else {
				var tbody = $('#info-copropietarios tbody');
				tbody.empty();
				var newRow = $('<tr>');
				console.log('error');
				newRow.append(
					"<td colspan='4' style='text-align:center'> No hay Copropietarios</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			console.log('error', jqXHR, textStatus, errorThrown);
		},
	});
}

function cargarHistorialArriendoList() {
	var idFicha = $('#id_ficha').val();

	// Validar idFicha
	if (!idFicha) {
		console.error('El ID de la ficha no está definido.');
		return;
	}

	// Destruir instancia previa si existe
	if ($.fn.DataTable.isDataTable('#historial-table')) {
		$('#historial-table').DataTable().destroy();
	}

	// Limpiar eventos previos
	$('#historial-table').off('init.dt');

	// Inicializar DataTable
	$('#historial-table').DataTable({
		dom: 'B<"clear">lfrtip',
		destroy: true,
		targets: 'no-sort',
		bSort: false,
		order: [[0, 'desc']],
		pagingType: 'full_numbers',
		pageLength: 10,
		lengthMenu: [
			[10, 25, 50, 100, 5000],
			[10, 25, 50, 100, 'Todos'],
		],
		columnDefs: [
			{
				render: (data) =>
					formateoNulos(moment(data).format('DD-MM-YYYY HH:mm')),
				targets: 0,
			},
			{ render: (data) => formateoNulos(data), targets: [1, 2, 3, 4, 5] },
		],
		ajax: {
			url: `components/propiedad/models/listado_historial_procesa.php?idFicha=${idFicha}`,
			type: 'POST',
			dataType: 'json',
			error: function (xhr, error, thrown) {
				console.error('Error al cargar los datos:', error, thrown);
			},
		},
		language: {
			lengthMenu: 'Mostrar _MENU_ registros por página',
			zeroRecords: 'No se encontraron registros',
			info: 'Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)',
			infoEmpty: 'No existen registros para mostrar',
			infoFiltered: '(filtrado desde _MAX_ total de registros)',
			loadingRecords: 'Cargando...',
			processing: 'Procesando...',
			search: 'Buscar',
			paginate: {
				first: 'Primero',
				last: 'Último',
				next: 'Siguiente',
				previous: 'Anterior',
			},
			buttons: {
				copy: 'Copiar',
			},
		},
	});

	// Agregar evento de inicialización
	$('#historial-table').on('init.dt', function () {
		console.log(
			'DataTables se ha inicializado correctamente en #historial-table'
		);
	});
}

function cargarRevCuentasServicioList() {
	var idFicha = $('#id_ficha').val();

	var table = $('#propiedad-rev-cuentas-servicio-table').DataTable({
		dom: 'B<"clear">lfrtip',
		bFilter: true,
		destroy: true,
		targets: 'no-sort',
		bSort: false,
		order: [[0, 'desc']],
		pagingType: 'full_numbers', // Tipo de paginación
		pageLength: 25, // Número de filas por página
		lengthMenu: [
			[25, 50, 100, 5000],
			[25, 50, 100, 'Todos'],
		],
		// "columnDefs": [ { orderable: false, targets: [9] } ],
		columnDefs: [
			{
				render: (data, type, row) => {
					console.log('ROW FROM COLUMN: ', row);
					return formateoNulos(data);
				},
				targets: 0,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 1,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 2,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 3,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(formateoDivisa(data));
				},
				targets: 4,
			},
			{ visible: false, targets: [0] },
			{
				searchable: false,
				targets: [3, 4],
			},
		],
		ajax: {
			url: 'components/propiedad/models/propiedad_revision_cuentas_servicio_list_procesa.php',
			type: 'POST',
		},
		language: {
			lengthMenu: 'Mostrar _MENU_ registros por página',
			zeroRecords: 'No se encontraron registros',
			info: 'Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)',
			infoEmpty: 'No existen registros para mostrar',
			infoFiltered: '(filtrado desde _MAX_ total de registros)',
			loadingRecords: 'Cargando...',
			processing: 'Procesando...',
			search: 'Buscar',
			searchPlaceholder: 'Nro. Ficha o Dirección',
			paginate: {
				first: 'Primero',
				last: 'Último',
				next: 'Siguiente',
				previous: 'Anterior',
			},
			buttons: {
				copy: 'Copiar',
			},
		},
	});

	table.on('draw', function () {
		var rows = table.rows({ page: 'current' }).nodes();
		var lastIdPropietario = null;

		console.log('rows: ', rows);

		rows.each(function (index, element) {
			var data = table.row(element).data();
			console.log('data from table on: ', data, 'index: ', index);

			var id_propietario = data[0];
			console.log(
				`lastIdPropietario ${lastIdPropietario} for id prop: ${id_propietario}`
			);

			console.log(
				'lastIdPropietario != id_propietario: ',
				lastIdPropietario != id_propietario
			);
			if (lastIdPropietario != id_propietario) {
				lastIdPropietario = id_propietario;

				$(index).removeClass('table-info');
				$(index).addClass('parent-row');
			} else {
				$(index).removeClass('parent-row');
				$(index).addClass('table-info');

				// Replace data with a hyphen in each cell of the row
				$(index)
					.find('td:nth-child(1), td:nth-child(2)')
					.each(function () {
						$(this)
							.css({
								color: '#cff4fc',
							})
							.find('a')
							.removeClass('link-info')
							.css({
								// Add your styles here
								color: '#cff4fc',
								cursor: 'auto',
								'text-decoration': 'none',
								// Add more styles as needed
							});
					});
			}
		});
	});

	$('#propiedad-rev-cuentas-servicio-table').on('init.dt', function () {
		console.log(
			'DataTables se ha inicializado correctamente en #propiedad-rev-cuentas-servicio-table'
		);
	});
}

function cargarArriendoEliminarMorasList() {
	var idFicha = $('#id_ficha').val();

	$('#prop-pago-arriendo-eliminar-moras-table').DataTable({
		destroy: true,
		targets: 'no-sort',
		bSort: true,
		order: [[0, 'desc']],
		pagingType: 'full_numbers', // Tipo de paginación
		autoWidth: false, // Desactiva ajuste automático
		responsive: false, // Desactiva comportamiento responsive

		columnDefs: [
			{
				render: (data, type, row) => {
					return `<span class="arriendo">${formateoNulos(data)}</span>`; // Agrega clase 'direccion'
				},
				targets: 0,
			},
			{
				render: (data, type, row) => {
					return `<span class="direccion">${formateoNulos(data)}</span>`; // Agrega clase 'arrendatario'
				},
				targets: 1,
			},
			{
				render: (data, type, row) => {
					return `<span class="arrendatario">${formateoNulos(data)}</span>`; // Agrega clase 'saldo'
				},
				targets: 2,
			},
			{
				render: (data, type, row) => {
					return `<span class="saldo">${formateoDivisa(data)}</span>`; // Aplica clase 'saldo'
				},
				targets: 3,
				createdCell: (td, cellData, rowData, row, col) => {
					$(td).css('color', 'red'); // Aplica color rojo
				},
			},

			{
				render: (data, type, row) => {
					const isChecked = data == 1 ? 'checked' : '';
					const checkboxId = `checkbox_${row[0]}`; // Assuming row[0] is unique for each row
					return `
              <div class="d-flex justify-content-end">
                <label class="switch">
                  <input class="row-checkbox" type="checkbox" id="${checkboxId}" name="${checkboxId}" ${isChecked} data-row-id="${row[0]}">
                  <span class="slider round"></span>
                </label>
              </div>
            `;
				},
				targets: 4,
			},
		],

		ajax: {
			url: 'components/propiedad/models/propiedad_pago_arriendo_eliminar_moras_list_procesa.php',
			type: 'POST',
		},
		language: {
			lengthMenu: 'Mostrar _MENU_ registros por página',
			zeroRecords: 'No se encontraron registros',
			info: 'Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)',
			infoEmpty: 'No existen registros para mostrar',
			infoFiltered: '(filtrado desde _MAX_ total de registros)',
			loadingRecords: 'Cargando...',
			processing: 'Procesando...',
			search: 'Buscar',
			paginate: {
				first: 'Primero',
				last: 'Último',
				next: 'Siguiente',
				previous: 'Anterior',
			},
			buttons: {
				copy: 'Copiar',
			},
		},
		drawCallback: function () {
			// Event delegation for checkbox change
			$('#prop-pago-arriendo-eliminar-moras-table').on(
				'change',
				'.row-checkbox',
				function () {
					var isChecked = $(this).prop('checked');
					var rowId = $(this).data('row-id');

					console.log(
						'Checkbox clicked for row id:',
						rowId,
						'isChecked:',
						isChecked
					);

					// Perform any additional actions as needed
				}
			);
		},
	});
}

function selectAllCheckboxes() {
	$('#prop-pago-arriendo-eliminar-moras-table')
		.find('.row-checkbox')
		.each(function () {
			$(this).prop('checked', true);
		});

	checkCheckboxes();
}

function deselectAllCheckboxes() {
	$('#prop-pago-arriendo-eliminar-moras-table')
		.find('.row-checkbox')
		.each(function () {
			$(this).prop('checked', false);
		});

	checkCheckboxes();
}

function checkCheckboxes() {
	const anyChecked = $('#prop-pago-arriendo-eliminar-moras-table')
		.find('.row-checkbox')
		.is(':checked');
	$('#eliminar-moras').prop('disabled', !anyChecked);
}

$(document).ready(function () {
	// Add event listener to checkboxes
	$('#prop-pago-arriendo-eliminar-moras-table').on(
		'change',
		'.row-checkbox',
		function () {
			checkCheckboxes();
		}
	);

	// Initial check on page load
	checkCheckboxes();
});

function removerCheckboxes() {
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	let checkboxValues = [];

	// 1. Obtiene los datos de los checkboxes con check
	$('#prop-pago-arriendo-eliminar-moras-table')
		.find('.row-checkbox')
		.each(function () {
			var checkboxValue = $(this).attr('data-row-id');

			if (checkboxValue !== undefined && $(this).prop('checked')) {
				checkboxValues.push(checkboxValue);
			}
		});

	if (checkboxValues.length === 0) return;

	// //2. Continua si hay registros a eliminar
	// console.log("checkboxValues: ", checkboxValues);

	//2.0 BUSCA AL USUARIO ACTUAL Y SE VALIDA SI ES O NO AUTORIZADOR
	$.ajax({
		url: 'components/propiedad/models/busca_cuenta_usuario.php',
		type: 'POST',
		success: function (res) {
			if (!res) return;

			let objUsuario = JSON.parse(res);

			// console.log("USUARIO BUSCADO: ", objUsuario);
			let selectedRows = [];

			// Recorre las filas de la tabla para capturar la información relevante
			$('#prop-pago-arriendo-eliminar-moras-table')
				.find('.row-checkbox:checked') // Solo checkboxes seleccionados
				.each(function () {
					let row = $(this).closest('tr'); // Encuentra la fila correspondiente al checkbox
					let rowData = {
						arriendo: row.find('.arriendo').text().trim(),
						direccion: row.find('.direccion').text().trim(), // Captura datos visibles en la fila
						arrendatario: row.find('.arrendatario').text().trim(),
						saldo: row.find('.saldo').text().trim(),
						id: $(this).attr('data-row-id'), // Captura el ID desde el checkbox
					};

					selectedRows.push(rowData); // Agrega los datos al array
				});

			// Verifica si hay datos seleccionados
			if (selectedRows.length === 0) {
				alert('No has seleccionado ninguna mora.');
				return;
			}

			if (objUsuario.autorizador) {
				//console.log("ES AUTORIZADOR, PASA DIRECTO A ELIMINACIÓN");
				//PROCESO DE ELIMINACIÓN
				eliminarMoras(checkboxValues, objUsuario.id);
			} else {
				//2.1 INSERTA LOS REGISTROS DE AUTORIZADORES (EN CASO DE HABER) CON CODIGOS EN HISTORIAL PARA ESTE USUARIO EN ESPECIFICO
				$.ajax({
					url: 'components/propiedad/models/insert_autorizadores_usuario.php',
					data: { selectedRows: selectedRows },
					type: 'POST',
					success: function (res) {
						if (!res) return;

						//2.2 SE OBTIENEN LOS AUTORIZADORES PARA EL USUARIO ESPECIFICADO (CON SUS RESPECTIVOS CÓDIGOS UNICOS)
						$.ajax({
							url: 'components/propiedad/models/buscar_historial_autorizadores.php',
							type: 'POST',
							success: function (res) {
								if (!res) return;

								//console.log("RES: ", res);
								let listaUsuariosAutorizadores = JSON.parse(res);

								const generateDlList = (items) => {
									return `<dl>${items
										.map(
											(item) =>
												`<dt>${item.nombres} ${item.apellido_paterno}</dt>`
										)
										.join('')}</dl>`;
								};

								Swal.fire({
									title: 'Ingresa tu código de validación',
									text: 'Si cierras la ventana tendrás que solicitar un nuevo código',
									input: 'text',
									inputLabel: 'Código de Validación',
									html: `
										<p>Recuerde que puede consultar su código a los siguientes autorizadores:</p>
										${generateDlList(listaUsuariosAutorizadores)}
                						`,
									showCancelButton: true,
									confirmButtonText: 'Confirmar',
									cancelButtonText: 'Cancelar',
									allowOutsideClick: false,
									inputValidator: (value) => {
										if (!value) {
											return 'Debes ingresar tu código de autorización!';
										}
									},
								}).then((resp) => {
									if (!resp.isConfirmed) return;

									const findAutorizadorMatch = listaUsuariosAutorizadores.find(
										(aut) => aut?.codigo_autorizacion === resp.value
									);

									//2.3. SI CÓDIGO DE AUTORIZACIÓN INGRESADO HACE MATCH CON EL DEL AUTORIZADOR, SE APRUEBA OPERACIÓN
									if (!findAutorizadorMatch) {
										Swal.fire({
											title: 'Código Incorrecto',
											text: 'Has ingresado un código de autorización incorrecto, intenta nuevamente',
											icon: 'warning',
											showDenyButton: false,
											denyButtonText: 'Cancelar',
										});

										return;
									}

									Swal.fire({
										title: 'Código Autorizado',
										text: `La operación ha sido aprobada por el Usuario Autorizador ${findAutorizadorMatch.nombres} ${findAutorizadorMatch.apellido_paterno}`,
										icon: 'warning',
										showDenyButton: false,
										denyButtonText: 'Cancelar',
									});

									//4. PROCESO DE ELIMINACIÓN
									eliminarMoras(checkboxValues, findAutorizadorMatch.id);
								});
							},
						});
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.error('Error en la solicitud:', textStatus, errorThrown);
						// Show an error message to the user or perform other actions
					},
				});
			}
		},
	});
}

function eliminarMoras(idsFichasTecnicas, idAutorizador) {
	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Una vez eliminado la mora(s) desaparecerá(n) del listado',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Eliminar',
		denyButtonText: 'Cancelar',
	}).then((result) => {
		if (result.isConfirmed) {
			// Si el usuario hace clic en "Eliminar"
			$.ajax({
				url: 'components/propiedad/models/delete_arriendo_eliminar_mora.php',
				type: 'POST',

				dataType: 'text',
				data: { idsFichasTecnicas, idAutorizador },
				success: function (response) {
					Swal.fire({
						title: 'Mora(s) eliminada(s)',
						text: 'La(s) mora(s) se eliminó(aron) correctamente',
						icon: 'success',
					});
					cargarArriendoEliminarMorasList();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.error('Error en la solicitud:', textStatus, errorThrown);
					// Aquí puedes mostrar un mensaje de error al usuario o realizar otras acciones
				},
			});
		} else if (result.isDenied) {
			// Si el usuario hace clic en "Cancelar"
			// Aquí puedes cerrar el modal de SweetAlert si lo deseas
			Swal.close();
		}
	});
}

function isAnyCheckboxChecked() {
	// Select all checkboxes within the DataTable
	var checkboxes = $('#prop-pago-arriendo-eliminar-moras-table .row-checkbox');

	// Check if at least one checkbox is checked
	var isChecked = false;
	checkboxes.each(function () {
		if ($(this).prop('checked')) {
			isChecked = true;
			return false; // Exit the loop early since we found a checked checkbox
		}
	});

	return isChecked;
}

function toggleButtonState() {
	// Select all checkboxes within the DataTable
	var checkboxes = document.querySelectorAll(
		'#prop-pago-arriendo-eliminar-moras-table .row-checkbox'
	);

	// Check if at least one checkbox is checked
	var isChecked = Array.from(checkboxes).some((checkbox) => checkbox.checked);

	// Enable or disable the button based on the checkbox state
	document.getElementById('eliminar-moras').disabled = !isChecked;
}

// jhernandez
function cargarLiquidacionesGenMasivaList() {
	// Mostrar el mensaje de carga con SweetAlert
	Swal.fire({
		title: 'Cargando...',
		text: 'Por favor, espera mientras se cargan los datos.',
		allowOutsideClick: false,
		didOpen: () => {
			Swal.showLoading();
		},
	});

	$.ajax({
		url: 'components/propiedad/models/PropiedadesPorLiquidar.php',
		method: 'GET',
		dataType: 'json',
		success: function (data) {
			// Ordenar los datos por idcontrato en orden descendente
			data.sort(function (a, b) {
				return b.idcontrato - a.idcontrato;
			});

			// Si la tabla ya está inicializada como DataTable, destruirla
			if ($.fn.DataTable.isDataTable('#liq-generacion-masiva-table')) {
				$('#liq-generacion-masiva-table').DataTable().destroy();
			}

			// Vaciar el cuerpo de la tabla
			var tableBody = $('#liq-generacion-masiva-table tbody');
			tableBody.empty();

			// Recorrer los datos y agregar cada fila a la tabla
			$.each(data, function (index, item) {
				var cierre =
					item.detalle && item.detalle.conciliacion !== undefined
						? item.detalle.conciliacion
						: 0;

				var direccion = item.direccion || 'Sin dato';
				var idPropiedad = item.idpropiedad || 'Sin dato';
				var idContrato = item.idcontrato || 'Sin dato';

				var precioNumerico = parseFloat(item.saldo);
				var montoFormateado = isNaN(precioNumerico)
					? 'No definido'
					: new Intl.NumberFormat('es-CL', {
							style: 'currency',
							currency: 'CLP',
					  }).format(precioNumerico);

				if (idPropiedad !== 'Sin dato' && idContrato !== 'Sin dato') {
					tableBody.append(`
                        <tr>
                            <td>${direccion}</td>
                            <td>${idPropiedad}</td>
                            <td>${idContrato}</td>
                            <td data-order="${precioNumerico}">${montoFormateado}</td>
                            <td>${cierre}</td>
                            <td>-</td>
                            <td>
                                <div class="d-flex">
                                    <label class="switch">
                                        <input type="checkbox" id="rolActivoEditar_${idPropiedad}" 
                                            name="ficha_tecnica" 
                                            value="${idPropiedad}"
                                            class="checkbox-contrato">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    `);
				}
			});

			// Re-inicializar DataTable con opciones
			$('#liq-generacion-masiva-table').DataTable({
				lengthMenu: [
					[25, 50, 100, 200, 300],
					[25, 50, 100, 200, 300],
				],
			});

			// Cerrar SweetAlert después de cargar los datos
			Swal.close();
		},
		error: function (xhr, status, error) {
			Swal.fire({
				icon: 'error',
				title: 'Error al cargar los datos',
				text: 'Hubo un problema al obtener la información. Intenta nuevamente.',
			});
			console.error('Error en la solicitud:', error);
		},
	});
}

function habilitarTodos() {
	$('#liq-generacion-masiva-table tbody input[type="checkbox"]').prop(
		'checked',
		true
	);
}

function deshabilitarTodos() {
	$('#liq-generacion-masiva-table tbody input[type="checkbox"]').prop(
		'checked',
		false
	);
}

// jhernandez
function GenerarLiquidaciones() {
	let contratosSeleccionados = [];

	// Recolectar los IDs seleccionados desde los checkboxes marcados
	$('#liq-generacion-masiva-table tbody input[type="checkbox"]:checked').each(
		function () {
			contratosSeleccionados.push($(this).val());
		}
	);

	if (contratosSeleccionados.length === 0) {
		Swal.fire('Por favor selecciona al menos un contrato.');
		return;
	}

	// Crear un formulario dinámico para enviar los datos por POST
	let form = document.createElement('form');
	form.method = 'POST';
	form.action = 'components/officesbanking/models/Api.php'; // Ruta al backend
	form.target = '_blank'; // Se abrirá en una nueva pestaña

	// Crear inputs ocultos con los IDs seleccionados
	contratosSeleccionados.forEach((id_arriendo) => {
		let input = document.createElement('input');
		input.type = 'hidden';
		input.name = 'ficha_tecnica[]'; // Usamos como array
		input.value = id_arriendo;
		form.appendChild(input);

		// Registrar en historial cada ID procesado
		registroHistorial(
			'Crear',
			'',
			JSON.stringify({ id_arriendo }),
			'Liquidación',
			id_arriendo,
			id_arriendo
		);
	});

	// Agregar el formulario al body y enviarlo en una nueva pestaña
	document.body.appendChild(form);
	form.submit();
	document.body.removeChild(form); // Eliminar el formulario después de enviarlo

	// Refrescar la página después de un breve tiempo
	setTimeout(() => {
		location.reload();
	}, 1000);
}

// jhernandez
function cargarLiquidacionesPagoPropietariosList() {
	var idFicha = $('#id_ficha').val();

	$('#prop-liq-pago-propietarios-table').DataTable({
		dom: 'B<"clear">lfrtip',
		destroy: true,
		targets: 'no-sort',
		bSort: false,
		order: [[0, 'desc']],
		pagingType: 'full_numbers', // Tipo de paginación
		pageLength: 25, // Número de filas por página
		lengthMenu: [
			[25, 50, 100, 5000],
			[25, 50, 100, 'Todos'],
		],
		// "columnDefs": [ { orderable: false, targets: [9] } ],
		columnDefs: [
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 0,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 1,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(formateoDivisa(data));
				},
				targets: 2,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 3,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 4,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 5,
			},
		],
		ajax: {
			url: 'components/propiedad/models/propiedad_liquidaciones_pago_a_propietarios_list_procesa.php',
			type: 'POST',
		},
		language: {
			lengthMenu: 'Mostrar _MENU_ registros por página',
			zeroRecords: 'No se encontraron registros',
			info: 'Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)',
			infoEmpty: 'No existen registros para mostrar',
			infoFiltered: '(filtrado desde _MAX_ total de registros)',
			loadingRecords: 'Cargando...',
			processing: 'Procesando...',
			search: 'Buscar',
			paginate: {
				first: 'Primero',
				last: 'Último',
				next: 'Siguiente',
				previous: 'Anterior',
			},
			buttons: {
				copy: 'Copiar',
			},
		},
	});

	$('#prop-liq-pago-propietarios-table').on('init.dt', function () {});
}

function deselectAll() {
	$('input[type="checkbox"]').prop('checked', false);
	$('#idPropiedades').val('[]');
	if ($('#idPropiedades').val() == '[]') {
		$('#generarLiq').prop('disabled', true);
	} else {
		$('#generarLiq').prop('disabled', false);
	}
}

function selectAll() {
	$('input[type="checkbox"]').prop('checked', true);
	document.location.reload();
}

function modLiquidacion(id_propiedad, checkbox) {
	let idPropiedades = $('#idPropiedades').val();
	let arregloIds = JSON.parse(idPropiedades);

	if (checkbox.checked) {
		arregloIds.push(id_propiedad);
	} else {
		arregloIds = arregloIds.filter(function (valor) {
			return valor !== id_propiedad;
		});
	}

	// Convertir el array de nuevo a una cadena y actualizar el campo de entrada
	$('#idPropiedades').val(JSON.stringify(arregloIds));

	if ($('#idPropiedades').val() == '[]') {
		$('#generarLiq').prop('disabled', true);
	} else {
		$('#generarLiq').prop('disabled', false);
	}
}

function getValores() {
	let inputVAlor = $('#idPropiedades').val();
	const propiedades = JSON.parse(inputVAlor);
	// Usar map para obtener todos los id_ficha_arriendo
	const idFichaArriendo = propiedades.map(
		(propiedad) => propiedad.id_ficha_arriendo
	);
}

function cargarLiquidacionesHistorico() {
	var idFicha = $('#id_ficha').val();
	$('#liq-histo').DataTable({
		dom: 'B<"clear">lfrtip',
		destroy: true,
		targets: 'no-sort',
		bSort: false,
		order: [[0, 'desc']],
		pagingType: 'full_numbers', // Tipo de paginación
		pageLength: 25, // Número de filas por página
		lengthMenu: [
			[25, 50, 100, 5000],
			[25, 50, 100, 'Todos'],
		],
		// "columnDefs": [ { orderable: false, targets: [9] } ],
		columnDefs: [
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 0,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 1,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 2,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 3,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 4,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(moment(data).format('DD-MM-YYYY'));
				},
				targets: 5,
			},
			{
				render: (data, type, row) => {
					if (!data) {
						return `<a type="button" class="btn btn-warning m-0 d-flex" style="padding: .5rem; width: 27px;" title="URL no encontrada"><i class="fa-solid fa-triangle-exclamation"></i></a>`;
					} else {
						return `<a href="${data}" download="" type="button" class="btn btn-info m-0 d-flex" style="padding: .5rem; width: 27px;" aria-label="documento" title="documento"><i class="fa-solid fa-file" style="font-size: .75rem;"></i></a>`;
					}
				},
				targets: 6,
			},
		],
		ajax: {
			url: 'components/propiedad/models/liquidacion_historico_procesa.php',
			type: 'POST',
			data: {
				fecha_inicio: $('#fechaInicio').val(),
				fecha_fin: $('#fechaTermino').val(),
			},
		},
		language: {
			lengthMenu: 'Mostrar _MENU_ registros por página',
			zeroRecords: 'No se encontraron registros',
			info: 'Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)',
			infoEmpty: 'No existen registros para mostrar',
			infoFiltered: '(filtrado desde _MAX_ total de registros)',
			loadingRecords: 'Cargando...',
			processing: 'Procesando...',
			search: 'Buscar',
			paginate: {
				first: 'Primero',
				last: 'Último',
				next: 'Siguiente',
				previous: 'Anterior',
			},
			buttons: {
				copy: 'Copiar',
			},
		},
	});
	$('#liq-generacion-masiva-table').on('init.dt', function () {});
	$('[data-toggle="tooltip"]').tooltip();
}

function cargarDetalleServicio(body) {
	var tbody = document.querySelector('#detalleCuenta tbody');
	tbody.innerHTML = '';
	tbody.innerHTML = body;
}

// orden

// $(document).ready(function () {

//   var $select = $('#paises');
//   var $options = $select.find('option');

//   $options.sort(function (a, b) {
//     if (a.text > b.text) return 1;
//     if (a.text < b.text) return -1;
//     return 0;
//   });
//   $select.empty().append($options);
// });

// Asociar el evento click al botón #region usando delegación de eventos

$(document).on('click', '#region', function (id) {
	var $select = $('#region');
	var $options = $select.find('option');
	var selectedValue = $select.val();

	$options.sort(function (a, b) {
		if (a.text > b.text) return 1;
		if (a.text < b.text) return -1;
		return 0;
	});

	$select.empty().append($options);
	$select.val(selectedValue);
});

$(document).on('click', '#comuna', function () {
	var $select = $('#comuna');
	var $options = $select.find('option');
	var selectedValue = $select.val();

	$options.sort(function (a, b) {
		if (a.text > b.text) return 1;
		if (a.text < b.text) return -1;
		return 0;
	});

	$select.empty().append($options);
	$select.val(selectedValue);
});

// copia los datos al formulario de cuentas bancarias
function CopiarDatos() {
	var nombre = $('#nombrePersona').text();
	var rut = $('#DNI').val();
	var email = $('#emailPersona').text();

	$('#nombreTitular').val(nombre);
	$('#rutTitular').val(rut);
	$('#emailTitular').val(email);
}

$(document).ready(function () {
	// picker year
	$('#valorRolAño').datepicker({
		format: 'yyyy',
		viewMode: 'years',
		minViewMode: 'years',
	});

	function extraerdetalleid(id_propiedades_roles) {
		$.ajax({
			url: 'components/propiedad/models/LeerDetalleValoresRol.php',
			type: 'GET',
			data: { id_propiedades_roles: id_propiedades_roles },
			dataType: 'json',
			success: function (data) {
				// Limpiar la tabla antes de llenarla
				$('#tablaValoresRolDetalle tbody').empty();

				// Verificar si hay errores
				if (data.error) {
					console.error('Error:', data.error);
					return;
				}

				// Iterar sobre los datos recibidos y construir las filas de la tabla
				$.each(data.result, function (index, item) {
					// Formatear el valor como moneda
					var valorFormateado = formateoDivisa(item.valor);

					var row = `
				<tr>
				  <td>${item.año}</td>
				  <td>${valorFormateado}</td>
				  <td>${item.mes}</td>
				  <td>
					<div class="d-flex">
					  <label class="switch"> 
						<input value="1" type="checkbox" id="rolActivoCobrado_${
							item.id
						}" name="cobrado_${item.id}" ${
						item.cobrado ? 'checked' : ''
					} onclick="confirmarCambioEstado(${
						item.id
					}, 'cobrado', this.checked)">
						<span class="slider round"></span>
					  </label>
					</div> 
				  </td>
				  <td>
					<div class="d-flex">
					  <label class="switch">
						<input value="1" type="checkbox" id="rolActivoPagado_${item.id}" name="pagado_${
						item.id
					}" ${item.pagado ? 'checked' : ''} onclick="confirmarCambioEstado(${
						item.id
					}, 'pagado', this.checked)">
						<span class="slider round"></span>
					  </label>
					</div>
				  </td>
				  <td>
					<button class="btn btn-info editar-btn me-2" data-bs-toggle="modal" data-bs-target="#ModalEditarValor" data-id="${
						item.id
					}" data-año="${item.año}" data-valor="${item.valor}" data-cuota="${
						item.cuota
					}" data-mes="${item.mes}">
					  <i class="fa-solid fa-pen-to-square"></i>
					</button>
					<button class="btn btn-danger eliminar-btn-valores me-2" data-id="${item.id}">
					  <i class="fa-solid fa-trash"></i>
					</button>
				  </td>
				</tr>`;
					$('#tablaValoresRolDetalle tbody').append(row);
				});
				// Asignar eventos a los checkboxes
				$('input[type="checkbox"]').on('click', function () {
					if (this.id.startsWith('rolActivoCobrado_')) {
						var id = this.id.split('_')[1];
						confirmarCambioEstado(id, 'cobrado', this.checked);
					} else if (this.id.startsWith('rolActivoPagado_')) {
						var id = this.id.split('_')[1];
						confirmarCambioEstado(id, 'pagado', this.checked);
					}
				});

				// Asignar eventos a los botones de eliminar
				$(document).on('click', '.eliminar-btn-valores', function () {
					var idEliminar = $(this).data('id');
					confirmarEliminacionvalor(idEliminar);
				});

				function confirmarEliminacionvalor(id) {
					Swal.fire({
						title: '¿Estás seguro?',
						text: '¡No podrás revertir esto!',
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Sí, elimínalo!',
					}).then((result) => {
						if (result.isConfirmed) {
							// Lógica para eliminar el registro
							$.ajax({
								url: 'components/propiedad/models/EliminarValoresRol.php',
								type: 'POST',
								data: { id: id },
								success: function (response) {
									if (response.success) {
										Swal.fire({
											title: '¡Eliminado!',
											text: response.message,
											icon: 'success',
											timer: 1500,
											showConfirmButton: false,
											position: 'center',
										}).then(() => {
											// Cierra el modal de eliminación y el modal de detalle
											$('#ModalEliminar').modal('hide');
											$('#ModalDetalle').modal('hide');

											registroHistorial(
												'Eliminar', // Acción
												`Rol eliminado con ID: ${id}`,
												'', // Información posterior (vacío ya que el rol fue eliminado)
												'Rol', // Componente afectado
												id, // ID del recurso eliminado
												id // ID del item eliminado
											)
												.then((historialResponse) => {
													console.log(
														'Historial registrado:',
														historialResponse
													);
												})
												.catch((error) => {
													console.error(
														'Error al registrar en el historial:',
														error
													);
												});

											// Llama a la función para cargar los datos, usando el id_propiedades_roles
											var idPropiedadesRoles = $('#idPropiedadesRoles').val(); // Asegúrate de que el valor del ID esté disponible
											extraerdetalleid(idPropiedadesRoles);
										});
									} else {
										Swal.fire({
											title: 'Error',
											text: response.message,
											icon: 'error',
											confirmButtonText: 'Aceptar',
											position: 'center',
										});
									}
								},
								error: function () {
									Swal.fire({
										title: 'Error',
										text: 'Error al procesar la solicitud.',
										icon: 'error',
										confirmButtonText: 'Aceptar',
										position: 'center',
									});
								},
							});
						}
					});
				}

				// Asignar eventos a los botones de editar
				$(document).on('click', '.editar-btn', function () {
					var idEditar = $(this).data('id');
					var añoEditar = $(this).data('año');
					var valorEditar = $(this).data('valor');
					var cuotaEditar = $(this).data('cuota');
					var medeEditar = $(this).data('mes');

					// Llenar el formulario del modal con los datos
					$('#idEdit').val(idEditar);
					$('#valorRolAñoEdit').val(añoEditar);
					$('#ValorRolEdit').val(valorEditar);

					// Limpiar el select antes de agregar nuevas opciones
					$('#mesEdit').empty().append('<option>Selecciona una cuota</option>');

					// Crear y agregar el nuevo elemento
					var nuevoElemento = $('<option>', {
						value: cuotaEditar, // Asegúrate de que el valor coincida con el que deseas seleccionar
						text: medeEditar,
						selected: true, // Marca este elemento como seleccionado
					});

					// Agregar el nuevo elemento al select
					$('#mesEdit').append(nuevoElemento);

					// Asegúrate de que el valor del mes se seleccione
					$('#mesEdit').val(cuotaEditar);

					// Mostrar atributos del nuevo elemento
					console.log('Clase:', nuevoElemento.attr('class'));
					console.log('Texto:', nuevoElemento.text());
				});
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.error('Error al cargar datos:', textStatus);
			},
		});
		// Función para formatear el valor como moneda chilena
		function formateoDivisa(valor) {
			return `$${Number(valor).toLocaleString('es-CL', {
				minimumFractionDigits: 0,
			})}`;
		}
	}

	//********************* bruno ****************************/

	//********* se añadió la funcionalidad para los botones editar  **********/

	// Muestra los datos
	function cargarDatos() {
		var url = window.location.href;
		var parametros = new URL(url).searchParams;
		var token = parametros.get('token');

		$.ajax({
			url: 'components/propiedad/models/LeerValoresRol.php',
			type: 'GET',
			dataType: 'json',
			data: { token: token },
			success: function (data) {
				// Limpiar la tabla antes de llenarla
				$('#tablaValoresRol').DataTable().clear().destroy();

				// Iterar sobre los datos recibidos y construir las filas de la tabla
				$.each(data, function (index, item) {
					let botonBorrar = `<button class="btn btn-danger eliminar-rol-btn" data-token-rol="${item.token_rol}"><i class="fa-solid fa-trash"></i></button>`;
					var row = `
                <tr>
                  <td>${item.numero}</td>
                  <td>${item.principal}</td>
                  <td>${
										item.descripcion
									}</td> <!-- Nueva celda para la descripción -->
                  <td>
                    <div class="d-flex gap-2">
                      <button class="btn btn-success pasar-id-btn" data-token="${
												item.token_rol
											}" data-id-rol="${
						item.id_propiedades_roles
					}" data-bs-toggle="modal" data-bs-target="#ModalDetalle"><i class="fa-regular fa-eye"></i></button>
                      <button class="btn btn-info editar-btn" data-bs-toggle="modal" data-bs-target="#modalRolEditar" data-propiedad="${
												item.id_propiedad
											}" data-id="${item.id}" data-numero="${
						item.numero
					}" data-principal="${item.principal}" data-token-rol="${
						item.token_rol
					}"> <i class="fa-solid fa-pen-to-square"></i></button>
                      ${item.principal === 'No' ? botonBorrar : ''}
                    </div>
                  </td>
                </tr>`;
					$('#tablaValoresRol tbody').append(row);
				});

				// Inicializar DataTables
				$('#tablaValoresRol').DataTable({
					paging: true,
					searching: true,
					ordering: false,
					info: true,
					language: {
						lengthMenu: 'Mostrar _MENU_ registros por página',
						zeroRecords: 'No se encontraron registros',
						info: 'Mostrando página _PAGE_ de _PAGES_',
						infoEmpty: 'No hay registros disponibles',
						infoFiltered: '(filtrado de _MAX_ registros totales)',
						search: 'Buscar:',
						paginate: {
							first: 'Primero',
							last: 'Último',
							next: 'Siguiente',
							previous: 'Anterior',
						},
					},
				});

				// Asignar eventos a los botones de la tabla
				asignarEventos();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.error('Error al cargar datos:', textStatus);
			},
		});
	}

	//*****************************BOTON PARA MOSTRAR LA TABLA DE VALORES********* */
	// Asignar eventos a los botones de la tabla
	function asignarEventos() {
		// Botón para ver detalles
		$('#tablaValoresRol tbody').on('click', '.pasar-id-btn', function () {
			var id_propiedades_roles = $(this).data('id-rol');
			$('#id_propiedades_roles').val(id_propiedades_roles);
			extraerdetalleid(id_propiedades_roles);
		});
		//******************************BOTON PARA EDITAR VALORES******************************* */
		// Botón para editar
		$('#tablaValoresRol tbody').on('click', '.editar-btn', function () {
			var numero = $(this).data('numero');
			var principal = $(this).data('principal');

			$('#modalRolEditarNumero').val(numero);
			$('#modalRolDescripcion').val(descripcion);
			$('#modalEditarRolPrincipal').prop('checked', principal === 'Sí');
		});

		//***************************BOTON PARA ELIMINAR Y CONFIRMACION VALORES*************** */
		//cristobal saez
		// Botón para eliminar
		$('#tablaValoresRol tbody').on('click', '.eliminar-rol-btn', function () {
			// Captura el token_rol usando el atributo correcto
			var token_rol = $(this).data('token-rol'); // Asegúrate de que el botón tenga este atributo
			confirmarEliminacion(token_rol); // Llama a la función de confirmación con el token
		});
	}

	// Función para confirmar la eliminación
	function confirmarEliminacion(id, infoEliminado) {
		Swal.fire({
			title: '¿Estás seguro?',
			text: '¡No podrás revertir esto!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sí, eliminarlo!',
		}).then((result) => {
			if (result.isConfirmed) {
				// Llamar a la función eliminarRegistro con el id e infoEliminado
				eliminarRegistro(id, infoEliminado);
			}
		});
	}

	// Función para eliminar el registro
	function eliminarRegistro(id, infoEliminado) {
		$.ajax({
			url: 'components/rol/models/eliminar_valores_rol.php',
			type: 'POST',
			data: { id: id },
			success: function () {
				Swal.fire(
					'¡Éxito!',
					'El registro ha sido eliminado correctamente.',
					'success'
				);
				// Recargar los datos después de la eliminación
				cargarDatos();
			},
			error: function () {
				Swal.fire(
					'Precaución',
					'Hubo un problema al eliminar el registro.',
					'info'
				);
			},
		});
	}

	// funcion para confirar el cambio de estado
	function confirmarCambioEstado(id, tipo, isChecked) {
		Swal.fire({
			title: '¿Estás seguro?',
			text: '¿Quieres cambiar el estado?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sí, cambiarlo',
			cancelButtonText: 'No, cancelar',
		}).then((result) => {
			if (result.isConfirmed) {
				if (tipo === 'cobrado') {
					CambiarCobrado(id, isChecked);
				} else if (tipo === 'pagado') {
					CambiarEstadoPago(id, isChecked);
				}
			} else {
				// Revertir el cambio en el switch si se cancela la acción
				if (tipo === 'cobrado') {
					document.getElementById(`rolActivoCobrado_${id}`).checked =
						!isChecked;
				} else if (tipo === 'pagado') {
					document.getElementById(`rolActivoPagado_${id}`).checked = !isChecked;
				}
			}
		});
	}

	// funcion para cambiar el estado true o ffalse de la columna pago
	function CambiarEstadoPago(id, isChecked) {
		$.ajax({
			url: 'components/rol/models/actualiza_valores_rol_pagado.php',
			type: 'POST',
			data: {
				id: id,
				pagado: isChecked,
			},
			success: function (response) {
				Swal.fire(
					'¡Éxito!',
					'El estado de cobrado se ha actualizado correctamente.',
					'success'
				);
				cargarDatos();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				Swal.fire(
					'Error',
					'Hubo un problema al actualizar el estado de cobrado.',
					'error'
				);
			},
		});
	}

	// funcion para cambiar el estado true o ffalse de la columna cobrado
	function CambiarCobrado(id, isChecked) {
		$.ajax({
			url: 'components/rol/models/actualizar_valores_rol_cobrado.php',
			type: 'POST',
			data: {
				id: id,
				cobrado: isChecked,
			},
			success: function (response) {
				Swal.fire(
					'¡Éxito!',
					'El estado de pagado se ha actualizado correctamente.',
					'success'
				);
				cargarDatos();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				Swal.fire(
					'Error',
					'Hubo un problema al actualizar el estado de pagado.',
					'error'
				);
			},
		});
	}

	// Llamar a la función para cargar los datos al cargar la página
	cargarDatos();
	// *******************************GRABAR VALORES DE ROLES**************************
	$('#btnGrabar').on('click', function () {
		var url = window.location.href;
		var parametros = new URL(url).searchParams;
		var token_propiedad = parametros.get('token');

		var valorRolAño = $('#valorRolAño').val();
		var ValorRol = $('#ValorRol').val();
		var mes = $('#mes').val(); // Suponiendo que este valor es un número
		var id_propiedades_roles = $('#id_propiedades_roles').val();

		if (!valorRolAño || !ValorRol || mes === 'Selecciona una cuota') {
			Swal.fire(
				'Campos incompletos',
				'Por favor, completa todos los campos requeridos.',
				'info'
			);
			return;
		}

		// Mapeo de los meses
		var meses = {
			1: 'Abril',
			2: 'Junio',
			3: 'Septiembre',
			4: 'Noviembre',
		};

		// Obtiene el nombre del mes correspondiente
		var nombreMes = meses[mes] || 'Desconocido';

		grabarIdPropiedad(token_propiedad)
			.then((idPropiedad) => {
				if (idPropiedad && idPropiedad.length > 0) {
					let id_propiedad = idPropiedad[0].id;
					$.ajax({
						url: 'components/propiedad/models/insertarValoresRol.php',
						type: 'POST',
						data: {
							id_propiedad: id_propiedad,
							id_propiedades_roles: id_propiedades_roles,
							valorRolAño: valorRolAño,
							ValorRol: ValorRol,
							mes: mes,
						},
						success: function (response) {
							if (response.trim() === 'true') {
								Swal.fire(
									'¡Éxito!',
									'Datos enviados correctamente.',
									'success'
								);
								// Registrar en historial con el nombre del mes
								registroHistorial(
									'Crear',
									'',
									JSON.stringify({
										'valor Rol Año': valorRolAño,
										'Valor Rol': ValorRol,
										mes: nombreMes, // Usa el nombre del mes en lugar del número
									}),
									'Rol',
									id_propiedad,
									id_propiedades_roles
								);
								$('#ModalAgregarValor').modal('hide');
								cargarDatos();
								limpiarCampos();
							} else {
								Swal.fire('Error', 'No se pudieron enviar los datos.', 'error');
							}
						},
						error: function (jqXHR, textStatus) {
							Swal.fire(
								'Precaución',
								'Error al enviar los datos: ' + textStatus,
								'info'
							);
						},
					});
				}
			})
			.catch((error) => {
				console.error('Error al obtener idPropiedad:', error);
			});
	});

	// Función para limpiar los campos de entrada después de enviar los datos
	function limpiarCampos() {
		$('#valorRolAño').val('');
		$('#ValorRol').val('');
		$('#mes').val('Selecciona una cuota');
		$('#id_propiedades_roles').val('');
	}

	// Manejar clic en el botón "Guardar Cambios" dentro del modal de editar
	$('#btnGuardarCambios').on('click', function () {
		var idEdit = $('#idEdit').val(); // Este es el ID actual del elemento
		var valorRolAño = $('#valorRolAñoEdit').val();
		var ValorRol = $('#ValorRolEdit').val();
		var mes = $('#mesEdit').val();

		if (!valorRolAño || !ValorRol) {
			Swal.fire(
				'Precaución',
				'Por favor, complete todos los campos requeridos.',
				'info'
			);
			return;
		}

		if (!mes || mes === 'Selecciona una cuota' || isNaN(mes)) {
			Swal.fire('Precaución', 'Debe seleccionar un mes válido.', 'info');
			$('#mesEdit').focus();
			return;
		}

		var formData = new FormData($('#editar_valor_rol')[0]);

		// Obtener el token de propiedad desde la URL o la interfaz
		var url = window.location.href;
		var parametros = new URL(url).searchParams;
		var token_propiedad = parametros.get('token');

		// Obtener id_propiedad antes de hacer la solicitud AJAX
		grabarIdPropiedad(token_propiedad)
			.then((idPropiedad) => {
				if (idPropiedad && idPropiedad.length > 0) {
					let id_propiedad = idPropiedad[0].id; // Obtener el id_propiedad

					$.ajax({
						url: 'components/rol/models/actualizar_valores_rol.php',
						type: 'POST',
						data: formData,
						processData: false,
						contentType: false,
						success: function () {
							Swal.fire(
								'¡Éxito!',
								'Datos actualizados correctamente',
								'success'
							);

							// Registrar en historial
							var jsonInformacionNueva = JSON.stringify({
								valorRolAño: valorRolAño,
								ValorRol: ValorRol,
								mes: mes,
							});
							var jsonInformacioAntigua = capturarInformacionAntigua(); // Obtener información anterior

							// Asegurarse de que idEdit no esté vacío y que se pase correctamente como id_item
							if (!idEdit) {
								console.error('idEdit está vacío');
								return;
							}

							registroHistorial(
								'Modificar', // Acción
								jsonInformacioAntigua, // Información anterior
								jsonInformacionNueva, // Información nueva
								'Valores Rol',
								id_propiedad,
								idEdit // id_recurso (id de la propiedad)
							);

							// Ocultar el modal y recargar los datos
							$('#ModalEditarValor').modal('hide');
							cargarDatos();
						},
						error: function (jqXHR, textStatus) {
							Swal.fire(
								'Precaución',
								'Error al actualizar los datos: ' + textStatus,
								'info'
							);
						},
					});
				}
			})
			.catch((error) => {
				console.error('Error al obtener idPropiedad:', error);
			});
	});

	// Limpiar campos al cerrar el modal de editar
	$('#ModalEditarValor').on('hidden.bs.modal', function () {
		$('#editar_valor_rol')[0].reset();
	});

	//************************  cristobal ***********************//

	//************eliminar rol  *************/
	function eliminarRegistro(token_rol) {
		var url = window.location.href;
		var parametros = new URL(url).searchParams;
		var token_propiedad = parametros.get('token');

		// Obtener el id_propiedad antes de proceder con la eliminación
		grabarIdPropiedad(token_propiedad)
			.then((idPropiedad) => {
				if (idPropiedad && idPropiedad.length > 0) {
					let id_propiedad = idPropiedad[0].id; // Obtener el id_propiedad

					$.ajax({
						url: 'components/propiedad/models/eliminar_rol_propiedad.php',
						type: 'POST',
						data: {
							token_rol: token_rol, // Enviar el token para la eliminación
						},
						success: function (response) {
							Swal.fire(
								'¡Eliminado!',
								'El registro ha sido eliminado.',
								'success'
							);

							// Llamada a registroHistorial
							registroHistorial(
								'Eliminar',
								'', // Acción
								`Registro eliminado ${token_rol}`,
								'Rol', // Item (el rol) - Asegúrate que este sea 'Rol'
								id_propiedad, // id_recurso
								id_propiedad // id_item, puedes ajustar este valor si es necesario
							);

							cargarDatos(); // Volver a cargar los datos después de eliminar
						},
						error: function (jqXHR, textStatus, errorThrown) {
							Swal.fire(
								'Precaución',
								'Hubo un problema al eliminar el registro.',
								'info'
							);
						},
					});
				}
			})
			.catch((error) => {
				console.error('Error al obtener idPropiedad:', error);
			});
	}

	// Función para confirmar la eliminación usando SweetAlert2
	function confirmarEliminacion(token_rol) {
		Swal.fire({
			title: '¿Estás seguro?',
			text: '¡No podrás revertir esto!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sí, eliminarlo!',
		}).then((result) => {
			if (result.isConfirmed) {
				eliminarRegistro(token_rol);
			}
		});
	}

	// Delegación de eventos para manejar los clics en los botones de eliminación
	$(document).ready(function () {
		$('#tablaValoresRol tbody').on('click', '.eliminar-rol-btn', function () {
			var token_rol = $(this).data('token-rol'); // Asegúrate de que el atributo sea correcto
			confirmarEliminacion(token_rol);
		});
	});

	// Delegación de eventos para manejar los clics en los botones de eliminación
	$(document).ready(function () {
		$('#tablaValoresRol tbody').on('click', '.eliminar-rol-btn', function () {
			var id_propiedades_roles = $(this).data('id_propiedades_roles');
			var id_ficha = $(this).data('id_ficha'); // Asegúrate de que este dato esté disponible
			var idCheque = $(this).data('id_cheque'); // Asegúrate de que este dato esté disponible
			confirmarEliminacion(id_propiedades_roles, id_ficha, idCheque);
		});
	});

	// ************************* bruno *************************** //

	// ************************* roles *************************** //
	$('#btnGuardarRol').on('click', function () {
		let rolInput = $('#modalRolNumero');
		let numeroRol = rolInput.val().trim();
		let descripcionInput = $('#modalRolDescripcion'); // Obtener el input de descripción
		let descripcion = descripcionInput.val().trim(); // Obtener valor de descripción

		var url = window.location.href;
		var parametros = new URL(url).searchParams;
		var token = parametros.get('token');

		// VALIDAR NÚMERO
		if (numeroRol === '' || descripcion === '') {
			// Añadir validación para descripción
			Swal.fire({
				icon: 'info',
				title: 'Información',
				text: 'Faltaron Algunos Datos',
			});
			return;
		}

		if (numeroRol.length > 11) {
			Swal.fire({
				icon: 'info',
				title: 'Información',
				text: 'El rol excede el máximo de caracteres (10 y el "-")',
			});
			return;
		} else if (!numeroRol.includes('-')) {
			Swal.fire({
				icon: 'info',
				title: 'Información',
				text: 'El rol debe incluir un guión (-)',
			});
			return;
		}

		// Separar en dos partes
		let partes = numeroRol.split('-');
		let izquierda = partes[0];
		let derecha = partes[1];

		if (izquierda.length > 5 || derecha.length > 5) {
			Swal.fire({
				icon: 'info',
				title: 'Información',
				text: 'Cada parte del rol no debe exceder los 5 caracteres',
			});
			return;
		} else if (izquierda.length <= 0 || derecha.length <= 0) {
			Swal.fire({
				icon: 'info',
				title: 'Información',
				text: 'Cada parte del rol debe tener por lo menos un carácter',
			});
			return;
		}

		// Rellenar con ceros si es necesario
		izquierda = izquierda.padStart(5, '0');
		derecha = derecha.padStart(5, '0');

		const rolFormateado = `${izquierda}-${derecha}`;
		let principal = $('#modalRolPrincipal').is(':checked');

		grabarIdPropiedad(token)
			.then((idPropiedad) => {
				if (idPropiedad && idPropiedad.length > 0) {
					let id_propiedad = idPropiedad[0].id;
					console.log('ID de propiedad:', id_propiedad);

					if (principal) {
						cambiarEstadoRol(id_propiedad);
						ingresarRol(id_propiedad, rolFormateado, principal, 1, descripcion); // Incluir descripción
						cargarDatos();
					} else {
						ingresarRol(id_propiedad, rolFormateado, principal, 1, descripcion); // Incluir descripción
						cargarDatos();
					}

					// Cerrar modal solo si la operación fue exitosa
					$('#modalRolIngreso').modal('hide');
					$('#modalRolNumero').val('');
					descripcionInput.val(''); // Limpiar el campo de descripción
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'No se encontró la propiedad',
					});
					console.log('No se encontró la idPropiedad');
				}
			})
			.catch((error) => {
				console.error('Error al obtener idPropiedad:', error);
			});
	});

	// PASAR LOS ATRIBUTOS DE BTN EDITAR -> MODAL EDITAR
	$(document).on('click', '.editar-btn', function () {
		// Captura los valores del botón de edición
		let numero = $(this).attr('data-numero');
		let principal = $(this).attr('data-principal');
		let token = $(this).attr('data-token-rol');
		let descripcion = $(this).attr('data-descripcion'); // Añadir captura de descripción

		// Guarda los valores en atributos ocultos del modal
		$('#modalRolEditar').data('numero', numero);
		$('#modalRolEditar').data('principal', principal);
		$('#modalRolEditar').data('token', token);
		$('#modalRolEditarDescripcion').val(descripcion); // Establecer la descripción en el modal
	});

	// Botón para editar
	$('#btnEditarRol').on('click', function () {
		// Recupera los valores almacenados en el modal
		var numero = validarNumero($('#modalRolEditarNumero').val().trim());
		var principal = $('#modalEditarRolPrincipal').is(':checked');
		let token = $('#modalRolEditar').data('token');
		const principal_inicial = $('#modalRolEditar').data('principal'); // 'Sí' o 'No'

		// Recupera la descripción
		var descripcion = $('#modalRolDescripcion').val().trim();

		// Obtener token de la ficha
		var url = window.location.href;
		var parametros = new URL(url).searchParams;
		var token_propiedad = parametros.get('token');

		// Obtener idPropiedad de manera asíncrona
		grabarIdPropiedad(token_propiedad)
			.then((idPropiedad) => {
				if (idPropiedad && idPropiedad.length > 0) {
					let id_propiedad = idPropiedad[0].id;

					// Si el número es válido
					if (numero) {
						if (principal_inicial === 'Sí') {
							if (principal === false) {
								Swal.fire({
									icon: 'info',
									title: 'Información',
									text: 'No se puede desmarcar este rol como principal. Debe haber un rol principal.',
								});
							} else {
								editarRoles(numero, true, token, descripcion); // Pasamos descripción
							}
						} else if (principal === true) {
							cambiarEstadoRol(id_propiedad);
							editarRoles(numero, principal, token, descripcion); // Pasamos descripción
						} else {
							editarRoles(numero, principal, token, descripcion); // Pasamos descripción
						}

						// Registrar en historial
						var jsonInformacionNueva = JSON.stringify({
							numero: numero,
							principal: principal,
							descripcion: descripcion,
						});
						var jsonInformacionAntigua = JSON.stringify({
							numero: principal_inicial,
						}); // Captura la información anterior
						registroHistorial(
							'Modificar',
							jsonInformacionAntigua,
							jsonInformacionNueva,
							'Rol',
							id_propiedad,
							id_propiedades_roles // Si no hay ID específico
						);

						cargarDatos();
					}
				} else {
					console.log('No se encontró la idPropiedad');
				}
			})
			.catch((error) => {
				console.error('Error al obtener idPropiedad:', error);
			});
	});

	function grabarIdPropiedad(token) {
		return new Promise((resolve, reject) => {
			$.ajax({
				url: 'components/propiedad/models/grabar_id_propiedad.php',
				type: 'get',
				data: {
					token: token,
				},
				success: function (response) {
					// Convertir la respuesta a un objeto JSON
					try {
						let data = JSON.parse(response);
						resolve(data); // Resuelve la promesa con los datos
					} catch (error) {
						reject('Error al parsear la respuesta JSON: ' + error);
					}
				},
				error: function (xhr, status, error) {
					reject('Error en la solicitud AJAX: ' + error);
				},
			});
		});
	}

	function validarNumero(numero) {
		let rolFormateado;
		// validación de campos vacíos
		if (numero === '') {
			Swal.fire({
				icon: 'info',
				title: 'Información',
				text: 'Faltaron Algunos Datos',
			});
			return;
		}
		// verificar si el rol excede los 11 dígitos
		if (numero.length > 11) {
			Swal.fire({
				icon: 'info',
				title: 'Información',
				text: 'El rol excede el máximo de carácteres (10 y el "-")',
			});
			return;
		} else if (!numero.includes('-')) {
			Swal.fire({
				icon: 'info',
				title: 'Información',
				text: 'El rol debe incluir un guión (-)',
			});
			return;
		} else {
			// separar en dos partes, izquierda y derecha
			let partes = numero.split('-');
			let izquierda = partes[0];
			let derecha = partes[1];

			// verificar que cada parte no exceda 5 dígitos
			if (izquierda.length > 5 || derecha.length > 5) {
				Swal.fire({
					icon: 'info',
					title: 'Información',
					text: 'Cada parte del rol no debe exceder los 5 carácteres',
				});
				return;
			} else if (izquierda.length <= 0 || derecha.length <= 0) {
				Swal.fire({
					icon: 'info',
					title: 'Información',
					text: 'Cada parte del rol debe tener por lo menos un caracter',
				});
				return;
			} else {
				// Rellenar con ceros si es necesario
				izquierda = izquierda.padStart(5, '0');
				derecha = derecha.padStart(5, '0');

				// Concatenar y mostrar el rol formateado
				rolFormateado = `${izquierda}-${derecha}`;
				return rolFormateado;
			}
		}
	}
	function ingresarRol(
		id_propiedad,
		numero,
		principal,
		id_comuna,
		descripcion
	) {
		// Validación: Solo permite números y el signo "-" en el campo de número
		var regex = /^[0-9\-]+$/;

		// Verificar si hay letras en el campo 'numero'
		var containsLetters = /[a-zA-Z]/.test(numero);

		if (containsLetters) {
			Swal.fire({
				icon: 'warning',
				title: 'Número de rol inválido',
				text: "El número de rol no puede contener letras. Solo se permiten números y el signo '-'",
				confirmButtonText: 'Ok',
			});
			return; // Detener la ejecución si se encuentran letras
		}

		if (!regex.test(numero)) {
			Swal.fire({
				icon: 'warning',
				title: 'Número de rol inválido',
				text: "El número de rol solo puede contener dígitos y el signo '-'",
				confirmButtonText: 'Ok',
			});
			return; // Detener la ejecución si no pasa la validación
		}

		// Enviar los datos a insert_rol.php mediante AJAX
		$.ajax({
			url: 'components/propiedad/models/insert_rol.php', // Asegúrate de que la ruta es correcta
			type: 'post',
			data: {
				id_propiedad: id_propiedad,
				numero: numero,
				principal: principal,
				id_comuna: id_comuna,
				descripcion: descripcion, // Añadir la descripción a los datos enviados
			},
			success: function (response) {
				console.log('Respuesta de insert_rol.php:', response); // Registra la respuesta

				try {
					let respuesta = JSON.parse(response);
					if (respuesta.status === 'success') {
						// Confirmación de que se ha guardado el rol
						Swal.fire({
							icon: 'success',
							title: 'Los datos se han guardado con éxito.',
							confirmButtonText: 'Ok',
						});

						// Registrar en el historial
						registroHistorial(
							'crear',
							`Rol creado: ${numero} - Descripción: ${descripcion}`, // Incluir la descripción en el mensaje del historial
							'',
							'Rol',
							id_propiedad,
							id_propiedad
						).then(() => {
							cargarDatos(); // Actualizar los datos después de crear el rol
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'No se pudo guardar el rol.',
						});
					}
				} catch (error) {
					console.error('Error al analizar la respuesta JSON:', error);
				}
			},
			error: function (xhr, status, error) {
				console.error('Error en la solicitud AJAX:', error);
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Hubo un problema al guardar el rol.',
				});
			},
		});
	}

	function editarRoles(numero, principal, token) {
		// Obtener token de la ficha
		var url = window.location.href;
		var parametros = new URL(url).searchParams;
		var token_propiedad = parametros.get('token');

		var descripcion = $('#modalRolDescripcion').val().trim();
		console.log('Descripción capturada: ', descripcion); // Añade esto para verificar el valor

		// Obtener idPropiedad de manera asíncrona
		grabarIdPropiedad(token_propiedad)
			.then((idPropiedad) => {
				if (idPropiedad && idPropiedad.length > 0) {
					let id_propiedad = idPropiedad[0].id;

					// Llamada AJAX para editar el rol
					$.ajax({
						url: 'components/propiedad/models/editar_rol.php',
						type: 'post',
						data: {
							numero: numero,
							principal: principal,
							token: token, // Aquí pasamos el token de la propiedad
							descripcion: descripcion, // Aquí pasamos la descripción
						},
						success: function (response) {
							console.log('Respuesta del servidor:', response);
							if (response === 'OK') {
								// Registro en historial
								registroHistorial(
									'Modificar', // Acción
									JSON.stringify({ numero: numero, principal: principal }), // Información anterior
									JSON.stringify({
										numero: numero,
										principal: principal,
										descripcion: descripcion,
									}), // Nueva información incluyendo descripción
									'Rol', // Item
									id_propiedad, // id_recurso, el ID de la propiedad
									id_propiedad // id_item, si aplica
								);

								// Confirmación de que se ha guardado el rol
								Swal.fire({
									icon: 'success',
									title: 'Los datos se han guardado con éxito.',
									confirmButtonText: 'Ok',
								}).then((result) => {
									if (result.isConfirmed) {
										$('#modalRolEditar').modal('hide');
									}
								});
							} else {
								// Mensaje si no se guardó correctamente
								Swal.fire({
									position: 'center',
									icon: 'info',
									title: 'No ha sido guardado',
									showConfirmButton: false,
									timer: 1500,
								});
							}
						},
						error: function (xhr, status, error) {
							// Manejo de errores
							console.error('Error:', error);
						},
					});
				} else {
					console.log('No se encontró la idPropiedad');
				}
			})
			.catch((error) => {
				console.error('Error al obtener idPropiedad:', error);
			});
	}

	function cambiarEstadoRol(id_propiedad) {
		$.ajax({
			url: 'components/propiedad/models/actualizar_estados.php',
			type: 'post',
			data: {
				id_propiedad: id_propiedad,
			},
			success: function (response) {
				// Registro en historial para cambiar estado
				registroHistorial(
					'Modificar', // Acción
					JSON.stringify({ estadoAnterior: 'estado viejo' }), // Información anterior, debes capturar el estado anterior si es posible
					JSON.stringify({ id_propiedad: id_propiedad }), // Nueva información
					'Estado Rol', // Item
					id_propiedad, // id_recurso, puedes usar id_propiedad si aplica
					id_propiedad // id_item, si aplica
				);

				console.log('Roles actualizados exitosamente:', response);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.error('Error al actualizar el rol:', textStatus, errorThrown);
			},
		});
	}
});

function ValidarClienteCuentasBancarias(id) {
	return new Promise((resolve, reject) => {
		$.ajax({
			url: 'components/propiedad/models/ValidarClienteCuentasBancarias.php',
			type: 'post',
			data: {
				id: id,
			},
			success: function (response) {
				var res = JSON.parse(response);
				var estado = res[0].estado_cuenta;

				if (estado == 'true') {
					resolve(true);
				} else {
					resolve(false);
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.error('Error al actualizar el rol:', textStatus, errorThrown);
				reject(false); // O puedes resolver con false si hay un error
			},
		});
	});
}

// jhernandez - funcion para bajar excel con js
function GrabarValorRol() {
	// defino mis variables a guardar
	let id_propiedad = $('#id_propiedad').val();
	var valorRolAño = $('#valorRolAño').val();
	var ValorRol = $('#ValorRol').val();
	var mes = $('#mes').val();
	var id_propiedades_roles = $('#id_propiedades_roles').val();

	$.ajax({
		url: 'aqui va la url donde se inserta',
		type: 'post',
		data: {
			id_propiedad: id_propiedad,
			valorRolAño: valorRolAño,
			ValorRol: ValorRol,
			mes: mes,
			id_propiedades_roles: id_propiedades_roles,
		},
		success: function (response) {
			var res = JSON.parse(response);
			var estado = res[0].estado_cuenta;

			if (estado == 'true') {
				alert('datos guardo con exito.');
			} else {
				alert('error al guardar datos.');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error('Error al actualizar el rol:', textStatus, errorThrown);
			reject(false); // O puedes resolver con false si hay un error
		},
	});
}

// // jhernandez funcion para listar los tipos de movimientos de cuentas corrientes cargos.
// function CargarSelectTipoMovimientosCC() {
// 	// Realizar la solicitud AJAX
// 	$.ajax({
// 		url: 'components/propiedad/models/TipoMovimientos.php',
// 		method: 'GET', // Método de la solicitud (puede ser GET o POST según sea necesario)
// 		dataType: 'json', // Esperamos una respuesta en formato JSON
// 		success: function (data) {
// 			// Limpiar el contenido previo del <select> y agregar opción inicial
// 			const select = $('#ccTipoMovimiento');
// 			select.empty();
// 			select.append(
// 				$('<option>', {
// 					value: '', // Valor vacío
// 					text: 'Selecciona una opción', // Texto visible
// 					disabled: true, // Deshabilitado para evitar selección
// 					selected: true, // Marcado como predeterminado
// 				})
// 			);

// 			// Ordenar los datos por la descripción
// 			data.sort((a, b) => a.descripcion.localeCompare(b.descripcion));

// 			// Si la solicitud es exitosa, llenamos el select con los datos
// 			$.each(data, function (index, item) {
// 				select.append(
// 					$('<option>', {
// 						value: item.id,
// 						text: item.descripcion,
// 						'data-idresponsable': item.id_responsable,
// 						'data-cuentacorrientecargo': item.cuentaco,
// 					})
// 				);
// 			});

// 			// Actualiza el input oculto con el idResponsable al cambiar la selección
// 			select.off('change').on('change', function () {
// 				const idResponsable = $(this).find(':selected').data('idresponsable');
// 				$('#idResponsable').val(idResponsable || '');
// 			});
// 		},
// 		error: function (xhr, status, error) {
// 			// Manejo de errores
// 			console.error('Error al obtener los datos: ', error);
// 		},
// 	});
// }

// function CargarSelectTipoMovimientosCCAbono() {
// 	$.ajax({
// 		url: 'components/propiedad/models/TipoMovimientosAbono.php',
// 		method: 'GET',
// 		dataType: 'json',
// 		success: function (data) {
// 			// Limpiar el contenido previo del <select> y agregar opción inicial
// 			const select = $('#ccTipoMovimientoAbono');
// 			select.empty();
// 			select.append(
// 				$('<option>', {
// 					value: '', // Valor vacío
// 					text: 'Selecciona una opción', // Texto visible
// 					disabled: true, // Deshabilitado para evitar selección
// 					selected: true, // Marcado como predeterminado
// 				})
// 			);

// 			// Ordenar los datos por descripción
// 			data.sort((a, b) => a.descripcion.localeCompare(b.descripcion));

// 			// Poblar el select con los datos recibidos
// 			$.each(data, function (index, item) {
// 				alert(item.cuentacto); // Verificar valor de item.cuentacto
// 				select.append(
// 					$('<option>', {
// 						value: item.id,
// 						text: item.descripcion,
// 						'data-idresponsableabono': item.id_responsable,
// 						'data-ctacorrienteabono': item.cuentacto, // Usar el nombre exacto
// 					})
// 				);
// 			});

// 			// Asignar manejador al evento change
// 			select.off('change').on('change', function () {
// 				const id_responsable_cargo = $(this).find(':selected').data('idresponsableabono');
// 				$('#idResponsableAbono').val(id_responsable_cargo || '');

// 				const cta_corriente = $(this).find(':selected').data('ctacorrienteabono'); // Acceder usando el nombre correcto
// 				alert(cta_corriente); // Verificar valor obtenido
// 				$('#ctacorrienteAbono').val(cta_corriente || '');
// 			});
// 		},
// 		error: function (xhr, status, error) {
// 			console.error('Error en AJAX:', error);
// 		},
// 	});
// }

// jhernandez funcion para listar los tipos de movimientos de cuentas corrientes cargos.
function CargarSelectTipoMovimientosCC() {
	// Realizar la solicitud AJAX
	$.ajax({
		url: 'components/propiedad/models/TipoMovimientos.php',
		method: 'GET', // Método de la solicitud (puede ser GET o POST según sea necesario)
		dataType: 'json', // Esperamos una respuesta en formato JSON
		success: function (data) {
			// Ordenar los datos por la descripción
			data.sort((a, b) => a.descripcion.localeCompare(b.descripcion));

			// Limpiar el select y agregar opción inicial
			const select = $('#ccTipoMovimientoCargo');
			select.empty(); // Limpiamos el select antes de añadir opciones
			select.append(
				$('<option>', {
					value: '', // Valor vacío
					text: 'Selecciona una opción', // Texto visible
					disabled: true, // Deshabilitado para evitar selección
					selected: true, // Marcado como predeterminado
				})
			);

			// Llenar el select con los datos del servidor
			$.each(data, function (index, movimiento) {
				select.append(
					$('<option>', {
						value: movimiento.id,
						text: movimiento.descripcion,
						'data-idresponsablecargo': movimiento.id_responsable, // Agregar atributo data
						'data-ctacorriente': movimiento.cuentaco,
					})
				);
			});

			// Asignar manejador al evento change
			select.off('change').on('change', function () {
				const id_responsable_cargo = $(this)
					.find(':selected')
					.data('idresponsablecargo');
				$('#idResponsableCargo').val(id_responsable_cargo);

				const cta_corriente = $(this).find(':selected').data('ctacorriente');
				$('#cccta_contableCargo').val(cta_corriente || '');
			});
		},
		error: function (xhr, status, error) {
			// Manejo de errores
			console.error('Error al obtener los datos: ', error);
		},
	});
}

// jhernandez funcion para listar los tipos de movimientos de cuentas corrientes abonos.
function CargarSelectTipoMovimientosCCAbono() {
	$.ajax({
		url: 'components/propiedad/models/TipoMovimientosAbono.php',
		method: 'GET',
		dataType: 'json',
		success: function (data) {
			// Ordenar los datos por descripción
			data.sort((a, b) => a.descripcion.localeCompare(b.descripcion));

			// Limpiar el select y agregar opción inicial
			const select = $('#ccTipoMovimientoAbono');
			select.empty(); // Limpiar el select
			select.append(
				$('<option>', {
					value: '', // Valor vacío
					text: 'Selecciona una opción', // Texto visible
					disabled: true, // Deshabilitado para evitar selección
					selected: true, // Marcado como predeterminado
				})
			);

			// Crear opciones en el select con los datos recibidos
			$.each(data, function (index, movimiento) {
				select.append(
					$('<option>', {
						value: movimiento.id,
						text: movimiento.descripcion,
						'data-idresponsableabono': movimiento.id_responsable,
						'data-cta_corriente': movimiento.cuentaco,
					})
				);
			});

			// Manejar el evento de cambio del select
			select.off('change').on('change', function () {
				// Obtener valores del option seleccionado
				const selectedOption = $(this).find(':selected');

				const id_responsable_abono = selectedOption.data('idresponsableabono');
				$('#idResponsableAbono').val(id_responsable_abono || '');

				const cta_corriente = selectedOption.data('cta_corriente');
				$('#ctacorrienteAbono').val(cta_corriente || '');
			});
		},
		error: function (xhr, status, error) {
			console.error('Error al obtener los datos:', error);
		},
	});
}

// end jose

//bruno
function ListadoNotificaciones() {
	const ficha_tecnica = document.getElementById('ficha_tecnica_id').value;
	const tablaId = '#ListadoRecordatiorios';
	const urlBase = 'components/propiedad/models/ListadoNotificaciones.php';

	const configuracionTabla = {
		ajax: {
			url: `${urlBase}?ficha_tecnica=${ficha_tecnica}`,
			dataSrc: (json) => {
				console.log('Datos recibidos:', json);
				return json.data || [];
			},
			error: (xhr, status, error) => {
				console.error('Error en la solicitud AJAX:', error);
			},
		},
		columns: [
			{
				data: 'fecha_notificacion',
				render: function (data, type, row) {
					if (!data) return '';
					const dateParts = data.split('-');
					if (dateParts.length === 3) {
						const [year, month, day] = dateParts;
						return `${day}-${month}-${year}`;
					}
					return data;
				},
			},
			{ data: 'tipo_recordatorio' },
			{ data: 'descripcion' },
			{ data: 'ejecutivo' },
			{
				data: null,
				orderable: false,
				render: function (data, type, row) {
					const id = row.id;
					return `
						<button 
							class="btn btn-danger btn-sm"
							onclick="eliminarRecordatorio(${id})"
						>
							<i class="fa-solid fa-trash py-1"></i>
						</button>
					`;
				},
			},
		],
		language: {
			emptyTable: 'No hay notificaciones disponibles.',
		},
	};

	if ($.fn.DataTable.isDataTable(tablaId)) {
		$(tablaId)
			.DataTable()
			.ajax.url(`${urlBase}?ficha_tecnica=${ficha_tecnica}`)
			.load();
	} else {
		$(tablaId).DataTable(configuracionTabla);
	}
}

function eliminarRecordatorio(id) {
	// Mostramos la ventana de confirmación de SweetAlert en lugar de confirm()
	Swal.fire({
		title: 'Estás seguro de que deseas eliminar este recordatorio?',
		text: 'Esta acción no se puede revertir.',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Eliminar',
		cancelButtonText: 'Cancelar',
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
	}).then((result) => {
		// Si el usuario hace clic en "Sí, eliminar"
		if (result.isConfirmed) {
			// Llamada AJAX para procesar la eliminación en el servidor
			$.ajax({
				url: 'components/propiedad/models/delete_recordatorio.php',
				type: 'POST',
				data: { id_recordatorio: id },
				success: function (response) {
					try {
						const data = JSON.parse(response);

						if (data.success) {
							// Mostramos una alerta de éxito con SweetAlert
							Swal.fire({
								title: 'Eliminado',
								text: 'El recordatorio se eliminó correctamente.',
								icon: 'success',
								confirmButtonColor: '#3085d6',
							}).then(() => {
								// Recargamos la tabla
								$('#ListadoRecordatiorios')
									.DataTable()
									.ajax.reload(null, false);
							});
						} else {
							// Si falla, mostramos alerta de error
							Swal.fire({
								title: 'Error',
								text: data.message || 'No se pudo eliminar el registro.',
								icon: 'error',
								confirmButtonColor: '#3085d6',
							});
						}
					} catch (e) {
						console.error(e);
						Swal.fire({
							title: 'Error',
							text: 'No se pudo procesar la respuesta del servidor.',
							icon: 'error',
							confirmButtonColor: '#3085d6',
						});
					}
				},
				error: function (xhr, status, error) {
					console.error(error);
					Swal.fire({
						title: 'Error',
						text: 'Hubo un error al intentar eliminar el registro.',
						icon: 'error',
						confirmButtonColor: '#3085d6',
					});
				},
			});
		}
	});
}

$(document).ready(function () {
	cargarRetencionesList(); // Llama a la función al cargar la página
});

// Función para cargar retenciones
function cargarRetencionesList() {
	var idFicha = $('#id_ficha').val();

	// Manejo de ID desde localStorage
	if (!idFicha) {
		console.warn(
			'ID de ficha no encontrado en el DOM. Verificando localStorage...'
		);
		idFicha = localStorage.getItem('idFicha');
		if (!idFicha) {
			console.error(
				'ID de ficha no disponible en localStorage. No se puede cargar la información.'
			);
			return;
		}
	} else {
		localStorage.setItem('idFicha', idFicha); // Guardar en localStorage si existe
	}

	// Llamada AJAX para obtener retenciones
	$.ajax({
		url: `components/propiedad/models/retenciones.php?id=${idFicha}`,
		method: 'GET',
		dataType: 'json',
		success: function (response) {
			// Reiniciar DataTable si existe
			if ($.fn.DataTable.isDataTable('#retenciones')) {
				$('#retenciones').DataTable().clear().destroy();
			}

			// Validar la respuesta y manejar datos vacíos
			const data =
				response && Array.isArray(response) && response.length > 0
					? response
					: [];

			// Configuración de columnas
			const columns = [
				{
					data: 'fecha',
					render: function (data) {
						if (data) {
							const formattedDate = data.replace(/\\\//g, '/');
							return moment(formattedDate, 'DD/MM/YYYY HH:mm:ss').format(
								'DD-MM-YYYY'
							);
						}
						return 'N/A';
					},
				},
				{ data: 'motivo' },
				{
					data: 'montoretencion',
					render: function (data) {
						return formatCurrency(parseNumber(data));
					},
				},
				{
					data: 'montoretenido',
					render: function (data) {
						return formatCurrency(parseNumber(data));
					},
				},
				{
					data: 'saldo',
					render: function (data) {
						return formatCurrency(parseNumber(data));
					},
				},
				{ data: 'estado' },
				{
					data: null,
					render: function (data, type, row) {
						if (
							row.estado_retencion === false ||
							row.estado_retencion === 'POR RETENER' ||
							row.estado_retencion === 'RETENIDO'
						) {
							return `
                                <button class="btn btn-danger eliminar-retencion-btn" data-retencion="${row.id_retencion},${row.estado_retencion}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            `;
						}
						return row.estado_retencion
							? ''
							: `<span>${row.estado_retencion}</span>`;
					},
					orderable: false,
					searchable: false,
				},
			];

			// Inicializar DataTable
			$('#retenciones').DataTable({
				data: data,
				columns: columns,
				ordering: false,
				language: {
					lengthMenu: 'Mostrar _MENU_ registros',
					zeroRecords: 'No se encontraron resultados',
					info: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
					infoEmpty: 'No hay registros disponibles',
					infoFiltered: '(filtrado de un total de _MAX_ registros)',
					search: 'Buscar:',
					paginate: {
						first: 'Primero',
						last: 'Último',
						next: 'Siguiente',
						previous: 'Anterior',
					},
				},
			});
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error('Error en la llamada AJAX:', textStatus, errorThrown);
			Swal.fire({
				title: 'Error',
				text: 'Hubo un problema al cargar los datos. Por favor, inténtelo de nuevo más tarde.',
				icon: 'error',
			});
		},
	});
}

// Función para limpiar y formatear números
function parseNumber(data) {
	if (!data) return 0;
	return Number(data.replace(/\$/g, '').replace(/\./g, '').trim()) || 0;
}

// Función para formatear números como moneda
function formatCurrency(value) {
	return new Intl.NumberFormat('es-CL', {
		style: 'currency',
		currency: 'CLP',
	}).format(value);
}

$(document).on('click', '.eliminar-retencion-btn', function () {
	var data = $(this).data('retencion').split(','); // Obtener el id_retencion y estado_retencion desde el data-atributo
	var id_retencion = data[0];
	var estado_retencion = data[1];

	if (!id_retencion) {
		console.error('No se encontró el id_retencion.');
		return;
	}

	// Verificar si el estado_retencion es 'false'
	if (estado_retencion !== 'false') {
		Swal.fire({
			title: 'No se puede eliminar',
			text: "Solo se puede eliminar si el estado es 'false'.",
			icon: 'warning',
		});
		return;
	}

	// Obtener id_propiedad desde el DOM o variable global idFicha
	var id_propiedad = $('#id_ficha').val(); // Suponiendo que 'id_ficha' es el id_propiedad

	if (!id_propiedad) {
		console.error('No se encontró el id_propiedad.');
		return;
	}

	// Confirmación con SweetAlert2
	Swal.fire({
		title: '¿Estás seguro?',
		text: '¡No podrás revertir esto!',
		icon: 'info',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sí, ¡eliminar!',
		cancelButtonText: 'Cancelar',
	}).then((result) => {
		if (result.isConfirmed) {
			// Llamada AJAX para eliminar la retención
			$.ajax({
				url: 'components/propiedad/models/eliminar_retencion.php',
				method: 'POST',
				data: { id_retencion: id_retencion },
				success: function (response) {
					try {
						// Verificar si la respuesta es JSON
						var parsedResponse = JSON.parse(response);

						if (parsedResponse.success) {
							Swal.fire({
								title: '¡Eliminada!',
								text:
									parsedResponse.message ||
									'La retención ha sido eliminada correctamente.',
								icon: 'success',
							});

							// Aquí pasamos el mensaje de eliminación
							const mensajeEliminacion = `La retención con ID: ${id_retencion} fue eliminada.`;

							// Llamada a registroHistorial para registrar la eliminación
							registroHistorial(
								'Eliminar', // Tipo de registro
								'', // Información antigua vacía
								mensajeEliminacion, // El mensaje de eliminación
								'Retención', // El ítem es "Retención"
								id_propiedad, // ID del recurso (id_propiedad)
								id_retencion // ID del comentario relacionado (id_retencion)
							);

							// Cerrar el modal
							$('#agregarRetencionModal').modal('hide');

							// Recargar la lista de retenciones
							cargarRetencionesList();
						} else {
							Swal.fire({
								title: 'Error',
								text:
									parsedResponse.message || 'No se pudo eliminar la retención.',
								icon: 'error',
							});
						}
					} catch (e) {
						console.error('Error al analizar la respuesta del servidor:', e);
						Swal.fire({
							title: 'Error',
							text: 'Respuesta inesperada del servidor.',
							icon: 'error',
						});
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.error(
						'Error en la eliminación de la retención:',
						textStatus,
						errorThrown
					);
					Swal.fire({
						title: 'Error',
						text: 'Hubo un problema al procesar la solicitud.',
						icon: 'error',
					});
				},
			});
		}
	});
});

// Función para formatear los montos como moneda chilena
function formatCurrency(value) {
	return (
		'$' +
		parseFloat(value).toLocaleString('es-CL', { minimumFractionDigits: 0 })
	);
}
function abrirModalAgregarRetencion(id_arriendo, id_propiedad) {
	localStorage.setItem('id_arriendo', id_arriendo);
	localStorage.setItem('id_propiedad', id_propiedad);

	document.getElementById('id_arriendo').value = id_arriendo;
	document.getElementById('id_propiedad').value = id_propiedad;

	var modal = new bootstrap.Modal(
		document.getElementById('agregarRetencionModal')
	);
	modal.show();
}

// Formatear monto como dinero
function formatearDinero(monto) {
	return new Intl.NumberFormat('es-CL', {
		style: 'currency',
		currency: 'CLP',
	}).format(monto);
}

//******************* insertar retenciones cristobal saez */
document.addEventListener('DOMContentLoaded', function () {
	const tipoRetencion = document.getElementById('tipo_retencion');
	const fechasContainer = document.getElementById('fechasContainer');
	const razonContainer = document.getElementById('razonContainer');
	const montoContainer = document.getElementById('montoContainer');
	const montoInput = document.getElementById('monto_total');

	tipoRetencion.addEventListener('change', function () {
		// Ocultar todos los elementos primero
		fechasContainer.style.display = 'none';
		razonContainer.style.display = 'none';
		montoContainer.style.display = 'none';

		// Mostrar los elementos basados en el valor seleccionado
		if (tipoRetencion.value === '2') {
			fechasContainer.style.display = 'flex';
			montoContainer.style.display = 'flex';
		} else if (tipoRetencion.value === '3') {
			fechasContainer.style.display = 'flex';
			razonContainer.style.display = 'flex';
			montoInput.value = '0'; // Asignar automáticamente el valor 0
		} else {
			montoContainer.style.display = 'flex';
		}
	});

	document
		.getElementById('formAgregarRetencion')
		.addEventListener('submit', function (e) {
			e.preventDefault();

			const tipo_retencion = document.getElementById('tipo_retencion').value;
			let monto_retencion = document.getElementById('monto_total').value;
			const razon_retencion = document
				.getElementById('razonRetencion')
				.value.trim(); // Eliminar espacios

			// Procesar y validar el monto
			if (tipo_retencion !== '3') {
				// Solo procesar el monto si no es retención tipo 3
				if (typeof monto_retencion === 'string') {
					monto_retencion =
						parseFloat(monto_retencion.replace(/\./g, '').replace(/\$/g, '')) ||
						0;
				}
				if (isNaN(monto_retencion) || monto_retencion <= 0) {
					Swal.fire({
						icon: 'info',
						title: 'Monto no válido',
						text: 'Por favor, ingrese un monto válido mayor que cero.',
					});
					return;
				}
			} else {
				// Para tipo_retencion === '3', el monto siempre será 0
				monto_retencion = 0;

				// Validar que la razón no esté vacía
				if (!razon_retencion) {
					Swal.fire({
						icon: 'info',
						title: 'Razón requerida',
						text: 'Por favor, ingrese una razón para la retención.',
					});
					return;
				}
			}

			// Validar fechas
			const fechaActual = new Date().toISOString().split('T')[0];
			const fecha_desde =
				document.getElementById('fecha_desde').value || fechaActual;
			const fecha_hasta =
				document.getElementById('fecha_hasta').value || fechaActual;

			if (fecha_desde < fechaActual || fecha_hasta < fechaActual) {
				Swal.fire({
					icon: 'info',
					title: 'Fechas no válidas',
					text: 'Las fechas no pueden ser anteriores a la fecha actual.',
				});
				return;
			}

			if (
				(tipo_retencion === '2' || tipo_retencion === '3') &&
				fecha_desde &&
				fecha_hasta
			) {
				const fechaDesdeObj = new Date(fecha_desde);
				const fechaHastaObj = new Date(fecha_hasta);
				if (
					fechaDesdeObj.getMonth() === fechaHastaObj.getMonth() &&
					fechaDesdeObj.getFullYear() === fechaHastaObj.getFullYear()
				) {
					Swal.fire({
						icon: 'info',
						title: 'Ingreso inválido',
						text: 'No se permitirá el ingreso de cuotas dentro del mismo mes, ya que se considerará inválido.',
					});
					return;
				}
			}

			// Preparar datos para el envío
			const id_arriendo = localStorage.getItem('id_arriendo');
			const id_propiedad = localStorage.getItem('id_propiedad');
			const data = {
				id_arriendo,
				id_propiedad,
				tipo_retencion,
				monto_retencion,
				fecha_desde:
					tipo_retencion === '2' || tipo_retencion === '3'
						? fecha_desde
						: fechaActual,
				fecha_hasta:
					tipo_retencion === '2' || tipo_retencion === '3'
						? fecha_hasta
						: fechaActual,
				razon_retencion,
			};

			// Enviar los datos usando fetch
			fetch('components/propiedad/models/insert_retenciones.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify(data),
			})
				.then((response) => response.json())
				.then((result) => {
					if (result.status === 'success') {
						Swal.fire({
							icon: 'success',
							title: 'La retención ha sido agregada exitosamente.',
							showConfirmButton: false,
							timer: 1500,
						});
						limpiarCampos();
						$('#agregarRetencionModal').modal('hide');
						cargarRetencionesList(); // Recargar la lista de retenciones
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Error al agregar la retención',
							text: result.message || 'Error desconocido.',
						});
					}
				})
				.catch((error) => {
					console.error('Error:', error);
				});
		});

	function limpiarCampos() {
		montoInput.value = '';
		document.getElementById('fecha_desde').value = '';
		document.getElementById('fecha_hasta').value = '';
		tipoRetencion.value = '1';
		fechasContainer.style.display = 'none';
		montoContainer.style.display = 'flex';
		razonContainer.style.display = 'none';
	}

	document
		.querySelector('.btn-secondary[data-bs-dismiss="modal"]')
		.addEventListener('click', function () {
			limpiarCampos();
		});

	montoInput.addEventListener('input', function () {
		let valor = montoInput.value.replace(/\./g, '').replace(/\$/g, '');
		if (valor) {
			valor = parseFloat(valor);
			if (!isNaN(valor)) {
				montoInput.value = formatearComoDinero(valor);
			}
		}
	});

	montoInput.addEventListener('focus', function () {
		montoInput.value = montoInput.value.replace(/\./g, '').replace(/\$/g, '');
	});

	montoInput.addEventListener('blur', function () {
		let valor = montoInput.value.replace(/\./g, '').replace(/\$/g, '');
		if (valor) {
			valor = parseFloat(valor);
			if (!isNaN(valor)) {
				montoInput.value = formatearComoDinero(valor);
			}
		}
	});

	function formatearComoDinero(valor) {
		const formatter = new Intl.NumberFormat('es-CL', {
			style: 'currency',
			currency: 'CLP',
			minimumFractionDigits: 0,
			maximumFractionDigits: 0,
		});
		return formatter.format(valor);
	}
});

function cargarInfoPropiedad() {
	cargarInfoComentario();
}
