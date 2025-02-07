$(document).ready(function () {


	SeleccionacobrarComisionAdministracion();
	//BRUNO TORRES
	$('#descargarExcelArriendo').on('click', function (e) {
		e.preventDefault();

		// Capturamos los valores de los tres inputs
		let codigoPropiedad = $('#codigo_propiedad').val();
		let propietario = $('#Propietario').val();
		let arrendatario = $('#Arrendatario').val();
		let codigo_arriendo = $('#FichaArriendo').val();

		$.ajax({
			url: 'components/arriendo/models/get_arriendo_excel.php',
			type: 'GET',
			// Enviamos los tres parámetros
			data: {
				codigo_arriendo: codigo_arriendo,
				codigo_propiedad: codigoPropiedad,
				propietario: propietario,
				arrendatario: arrendatario,
				codigo_arriendo: codigo_arriendo,
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

				// 1) Transforma la respuesta para renombrar y ordenar columnas en el Excel
				var formattedData = response.map(function (row) {
					return {
						// 'Ficha Técnica': row.id_arriendo,
						'Código Propiedad': row.codigo_propiedad,
						Dirección: row.direccion,
						'Estado Propiedad': row.estado_propiedad,
						Estado: row.estado,
						Propietario: row.propietario,
						Arrendatario: row.arrendatario,
						Precio: row.precio,
					};
				});

				// 2) Crear la hoja (worksheet) usando los datos formateados
				var worksheet = XLSX.utils.json_to_sheet(formattedData);

				// 3) Ajustar el ancho de columnas (opcional)
				worksheet['!cols'] = [
					{ wpx: 120 }, // Arriendo ID
					{ wpx: 120 }, // Propiedad Codigo
					{ wpx: 200 }, // Dirección
					{ wpx: 150 }, // Estado Propiedad
					{ wpx: 100 }, // Estado
					{ wpx: 180 }, // Propietario
					{ wpx: 180 }, // Arrendatario
					{ wpx: 120 }, // Precio
				];

				// 4) Crear un nuevo libro de trabajo (workbook)
				var workbook = XLSX.utils.book_new();
				XLSX.utils.book_append_sheet(workbook, worksheet, 'Arriendos');

				// 5) Generar el archivo XLSX (binario / array)
				var wbout = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

				// 6) Crear un Blob a partir del workbook
				var blob = new Blob([wbout], { type: 'application/octet-stream' });

				// 7) Crear un objeto URL para la descarga
				var url = URL.createObjectURL(blob);

				// 8) Crear un enlace temporal y forzar la descarga
				var a = document.createElement('a');
				a.href = url;
				a.download = 'arriendos.xlsx';
				document.body.appendChild(a);
				a.click();
				document.body.removeChild(a);

				// 9) Liberar el objeto URL
				URL.revokeObjectURL(url);
			},
			error: function (xhr, status, error) {
				console.error('Error en la petición AJAX:', error);
			},
		});
	});
	
	inicializarTablaArriendos();

	$('.js-example-responsive').select2({
		width: '100%',
		placeholder: 'Seleccione',
	});

	/***
	 *
	 * validacion para impedir que el input fecha de cc cargos se igresen fechas anteriores jhernandez
	 *
	 */

	const today = new Date();
	const dd = String(today.getDate()).padStart(2, '0');
	const mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0
	const yyyy = today.getFullYear();
	const formattedDate = `${yyyy}-${mm}-${dd}`;

	// Establecer el atributo min del input
	document
		.getElementById('ccIngresoPagoFecha')
		.setAttribute('min', formattedDate);
	document
		.getElementById('ccIngresoPagoFechaAbono')
		.setAttribute('min', formattedDate);
});
sessionStorage.clear();
sessionStorage.removeItem('nombre_arrendatario');
$(document).ready(function () {
	// Obtiene el parámetro "activeTab" de la URL
	let urlParams = new URLSearchParams(window.location.search);
	let activeTab = urlParams.get('activeTab');

	if (activeTab) {
		let tabElement = $(`#${activeTab}-tab`);
		if (tabElement.length) {
			new bootstrap.Tab(tabElement[0]).show();
		}
	}

	//jhernandez desaparece los dias de cobro mientras el tipo multa no sea por dia
	ValidarTipoMulta();

	// Add change event listener to the select element
	//console.log("Estoy aqui");

	cargarDocumentos();
	cargarServicios();
	cargarSeguros();
	cargarInfoComentario();
	cargarInfoArrendatario();
	cargarCCMovimientos();
	cargarPagoChequesList();
	ObtenerMesesEspeciales();

	CargarSelectTipoMovimientosCC();
	CargarSelectTipoMovimientosCCAbono();

	// jhernandez bloqueo de formulario cuando el arriendo este finalizado.
	var estado_contrato_seleccionado = $(
		'#estadoContrato option:selected'
	).text();

	if (estado_contrato_seleccionado === 'Finalizado') {
		// Seleccionar todos los inputs del formulario y establecer el atributo readonly
		$('input').prop('readonly', true);
		$('select').prop('disabled', true);
		$('file').prop('disabled', true);
		$('input[type="file"]').prop('disabled', true);
		$('#bt-aceptar').hide();
		$('button').prop('disabled', true);
		$('.btn-close').prop('disabled', false);
		$('#btn-eliminar-seguros').hide();
		$('#btn-eliminar-documento').hide();
		$('#btn-eliminar-servicios').hide();
	} else {
		document.querySelector('button').disabled = false; // Para habilitar el botón
	}

	$('#TipoProveedorServicio').attr('disabled', true);
	$('#TipoProveedorSeguro').attr('disabled', true);
	var elementosFormControl = document.querySelectorAll('select.form-control');
	elementosFormControl.forEach(function (elemento) {
		elemento.classList.add('form-select');
	});
	//Ocultamos boton

	try {
		document.getElementById('botonEliminaSeccion').style.display = 'none';
	} catch (error) { }
	//botonEliminar.style.display = 'none';
	onChangePersona();
});

/*
function enviarRentdesk() {
  var formData = new FormData(document.getElementById("formulario"));

  console.log("ENTRO A enviarRentdesk");
  var url = window.location.href;
  console.log(url);
  var parametros = (new URL(url)).searchParams;
  console.log(parametros.get('token'));
  formData.append('token_arrendatario', parametros.get('token'));

  $.showConfirm({
	title: "Confirmación", 
	body: "Esta seguro que desea guardar los cambios", 
	textTrue: "Si", 
	textFalse: "No",
	onConfirm: function() {
	  $.ajax({
		url: "components/arriendo/models/insert_update.php",
		type: "post",
		dataType: "html",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	  }).done(function (res) {
		var retorno = res.split(",xxx,");
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var token = retorno[3];

		if (resultado == "OK") {
		  $.showAlert({ title: "Atención", body: mensaje });
		  document.location.href = "index.php?component=arriendo&view=arriendo_list";
		} else {
		  $.showAlert({ title: "Error", body: mensaje });
		}
	  });
	}
  });
}*/

function salirArriendo() {
	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Al volver sin guardar se perderan los cambios',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Si',
		denyButtonText: 'No',
	}).then((result) => {
		if (result.isConfirmed) {
			document.location.href =
				'index.php?component=arriendo&view=arriendo_list';
		} else if (result.isDenied) {
			// Si el usuario hace clic en "Cancelar"
			// Aquí puedes cerrar el modal de SweetAlert si lo deseas
		}
	});
} //function enviarRentdesk

function loadArriendo_List() {
	$(document).ready(function () {
		// Recuperar valores de los elementos del DOM de forma segura
		var filtro_codigo_propiedad =
			document.getElementById('codigo_propiedad')?.value.toUpperCase() ?? '';
		var estadoPropiedad =
			document.getElementById('estadoPropiedad')?.value.toUpperCase() ?? '';
		var estadoContrato =
			document.getElementById('estadoContrato')?.value.toUpperCase() ?? '';
		var propietario =
			document.getElementById('Propietario')?.value.toUpperCase() ?? '';
		var Arrendatario =
			document.getElementById('Arrendatario')?.value.toUpperCase() ?? '';
		var tipoPropiedad =
			document.getElementById('tipoPropiedad')?.value.toUpperCase() ?? '';
		var FichaArriendo = document.getElementById('FichaArriendo')?.value ?? '';

		// Guardar filtros en sessionStorage
		sessionStorage.setItem('filtro_codigo_propiedad', filtro_codigo_propiedad);
		sessionStorage.setItem('estadoPropiedad', estadoPropiedad);
		sessionStorage.setItem('estadoContrato', estadoContrato);
		sessionStorage.setItem('propietario', propietario);
		sessionStorage.setItem('Arrendatario', Arrendatario);
		sessionStorage.setItem('tipoPropiedad', tipoPropiedad);

		var ajaxUrl =
			'components/arriendo/models/arriendo_list_procesa.php?' +
			'activos=1' +
			'&Propietario=' +
			encodeURIComponent(propietario) +
			'&tipoPropiedad=' +
			encodeURIComponent(tipoPropiedad) +
			'&Arrendatario=' +
			encodeURIComponent(Arrendatario) +
			'&estadoContrato=' +
			encodeURIComponent(estadoContrato) +
			'&estadoPropiedad=' +
			encodeURIComponent(estadoPropiedad) +
			'&FichaArriendo=' +
			encodeURIComponent(FichaArriendo) +
			'&codigo_propiedad=' +
			encodeURIComponent(filtro_codigo_propiedad);

		// Comprobar si la tabla ya ha sido inicializada
		if ($.fn.DataTable.isDataTable('#arriendos-activos')) {
			var table = $('#arriendos-activos').DataTable();
			table.ajax.url(ajaxUrl).load();
		} else {
			$('#arriendos-activos').DataTable({
				order: [[0, 'desc']],
				processing: true,
				serverSide: true,
				pageLength: 25,
				columnDefs: [
					{ orderable: false, targets: [0, 1, 2, 3, 4, 5, 6, 7] },
					{
						targets: [7], // Cambia este índice según la posición de tu columna de precio
						render: function (data, type, row) {
							if (!data || data === null || data === '0' || data === 0) {
								return '$ 0'; // Manejar caso de valor nulo o vacío
							}
							// Asegurarse de que el valor no tenga comas ni símbolos antes de formatearlo
							// let precioLimpio = parseFloat(data.toString().replace(/[$,.]/g, ''));
							// // Verificar que el número sea válido
							// if (isNaN(precioLimpio)) {
							// 	return "$ 0";
							// }
							return data; // Usar la función formateoDivisa
						},
					},
				],
				ajax: {
					url: ajaxUrl,
					type: 'POST',
					error: function (xhr, error, thrown) {
						console.error('Error al cargar los datos:', error, thrown);
					},
				},
				language: {
					lengthMenu: 'Mostrar _MENU_ registros por página',
					zeroRecords: 'No encontrado',
					info: 'Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)',
					infoEmpty: 'Sin resultados',
					infoFiltered:
						' <strong>Total de registros filtrados: _TOTAL_ </strong>',
					loadingRecords: 'Cargando...',
					search: '',
					processing: 'Procesando...',
					paginate: {
						first: 'Primero',
						last: 'Último',
						next: 'siguiente',
						previous: 'anterior',
					},
				},
				drawCallback: function (settings) {
					// Inicializar tooltips después de que la tabla se haya redibujado
					$('[data-bs-toggle="tooltip"]').tooltip();
				},
				dom: 'Bfrtip',
				buttons: [
					{
						extend: 'excelHtml5',
						text: 'Exportar a Excel',
						title: 'Lista de Arriendos',
						className: 'btn btn-success',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7],
							format: {
								body: function (data, row, column, node) {
									// Asegurarse de convertir cada dato a mayúsculas y limpiar links
									let cleanData = $(node).text().toUpperCase();
									return cleanData;
								},
							},
						},
					},
				],
			});
			// Desactiva la búsqueda al presionar una tecla
			$('div.dataTables_filter input').unbind();
			// Agrega el botón de búsqueda si no existe
			if (!$('#divbotonbuscar').length) {
				$(
					"<div id='divbotonbuscar'><button id='buscar' class='btn btn-light btn-buscar-tablas'>Buscar</button></div>"
				).insertBefore('.dataTables_filter input');
			}
			$('.dataTables_filter').css('display', 'none');
			// Configura el evento de clic para el botón de búsqueda
			$('#buscar')
				.off('click')
				.on('click', function (e) {
					loadArriendo_List();
				});
		}
	});
	// function formateoDivisa(valor) {
	// 	// Convertir el valor a un número flotante para asegurar el formato
	// 	var numero = parseFloat(valor);
	// 	// Verificar si el número es válido
	// 	if (isNaN(numero)) {
	// 		return "$ 0";
	// 	}
	// 	// Formatear el número con separador de miles y sin decimales
	// 	return "$ " + numero.toLocaleString("es-CL", {
	// 		minimumFractionDigits: 0, // Esto elimina los decimales
	// 		maximumFractionDigits: 0, // Esto elimina los decimales
	// 	});
	// }
}

function generarExcel(urlbase) {
	// Recuperar valores de los elementos del DOM de forma segura
	var filtro_codigo_propiedad = sessionStorage.getItem(
		'filtro_codigo_propiedad'
	);
	var estadoPropiedad = sessionStorage.getItem('estadoPropiedad');
	var estadoContrato = sessionStorage.getItem('estadoContrato');
	var propietario = sessionStorage.getItem('propietario');
	var Arrendatario = sessionStorage.getItem('Arrendatario');
	var tipoPropiedad = sessionStorage.getItem('tipoPropiedad');

	var ajaxUrl =
		'components/arriendo/models/arriendo_list_procesa_excel.php?' +
		'activos=1' +
		'&Propietario=' +
		encodeURIComponent(propietario) +
		'&tipoPropiedad=' +
		encodeURIComponent(tipoPropiedad) +
		'&Arrendatario=' +
		encodeURIComponent(Arrendatario) +
		'&estadoContrato=' +
		encodeURIComponent(estadoContrato) +
		'&estadoPropiedad=' +
		encodeURIComponent(estadoPropiedad) +
		'&codigo_propiedad=' +
		encodeURIComponent(filtro_codigo_propiedad);
	$.ajax({
		type: 'GET',
		url: ajaxUrl,
		success: function (res) {
			window.open('/upload/arriendo/excel/' + res, '_blank');
		},
	});
}

function loadArriendo_List_Inactivos() {
	$(document).ready(function () {
		// Recuperar valores de los elementos del DOM de forma segura
		var filtro_codigo_propiedad =
			document.getElementById('codigo_propiedad')?.value ?? '';
		var estadoPropiedad =
			document.getElementById('estadoPropiedad')?.value ?? '';
		//var estadoContrato = document.getElementById("estadoContrato")?.value ?? '';
		var propietario = document.getElementById('propietario')?.value ?? '';
		var Arrendatario = document.getElementById('Arrendatario')?.value ?? '';
		var tipoPropiedad = document.getElementById('tipoPropiedad')?.value ?? '';
		var FichaArriendo = document.getElementById('FichaArriendo')?.value ?? '';

		// Guardar filtros en sessionStorage
		sessionStorage.setItem('filtro_codigo_propiedad', filtro_codigo_propiedad);
		sessionStorage.setItem('estadoPropiedad', estadoPropiedad);
		//sessionStorage.setItem("estadoContrato", estadoContrato);
		sessionStorage.setItem('propietario', propietario);
		sessionStorage.setItem('Arrendatario', Arrendatario);
		sessionStorage.setItem('tipoPropiedad', tipoPropiedad);

		var ajaxUrl =
			'components/arriendo/models/arriendo_list_procesa.php?' +
			'activos=2' +
			'&Propietario=' +
			encodeURIComponent(propietario) +
			'&tipoPropiedad=' +
			encodeURIComponent(tipoPropiedad) +
			'&Arrendatario=' +
			encodeURIComponent(Arrendatario) +
			//"&estadoContrato=" + encodeURIComponent(estadoContrato) +
			'&estadoPropiedad=' +
			encodeURIComponent(estadoPropiedad) +
			'&codigo_propiedad=' +
			encodeURIComponent(filtro_codigo_propiedad) +
			'&FichaArriendo=' +
			encodeURIComponent(FichaArriendo);

		// Comprobar si la tabla ya ha sido inicializada
		if ($.fn.DataTable.isDataTable('#arriendos-inactivos')) {
			var table = $('#arriendos-inactivos').DataTable();
			table.ajax.url(ajaxUrl).load();
		} else {
			$('#arriendos-inactivos').DataTable({
				order: [[0, 'desc']],
				processing: true,
				serverSide: true,
				pageLength: 25,
				columnDefs: [
					{ orderable: false, targets: [0, 1, 2, 3, 4, 5, 6] },
					{ targets: 2, visible: false },
					{
						targets: [6], // Cambia este índice según la posición de tu columna de precio
						render: function (data, type, row) {
							if (!data || data === null || data === '0' || data === 0) {
								return '$ 0'; // Manejar caso de valor nulo o vacío
							}

							// // Asegurarse de que el valor no tenga comas ni símbolos antes de formatearlo
							// let precioLimpio = parseFloat(data.toString().replace(/[$,.]/g, ''));

							// // Verificar que el número sea válido
							// if (isNaN(precioLimpio)) {
							// 	return "$ 0";
							// }

							return data; // Usar la función formateoDivisa
						},
					},
				],

				ajax: {
					url: ajaxUrl,
					type: 'POST',
					error: function (xhr, error, thrown) {
						console.error('Error al cargar los datos:', error, thrown);
					},
				},
				language: {
					lengthMenu: 'Mostrar _MENU_ registros por página',
					zeroRecords: 'No encontrado',
					info: 'Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)',
					infoEmpty: 'Sin resultados',
					infoFiltered:
						' <strong>Total de registros filtrados: _TOTAL_ </strong>',
					loadingRecords: 'Cargando...',
					search: '',
					processing: 'Procesando...',
					paginate: {
						first: 'Primero',
						last: 'Último',
						next: 'siguiente',
						previous: 'anterior',
					},
				},
				drawCallback: function (settings) {
					// Inicializar tooltips después de que la tabla se haya redibujado
					$('[data-bs-toggle="tooltip"]').tooltip();
				},
				dom: 'Bfrtip',
				buttons: [
					{
						extend: 'excelHtml5',
						text: 'Exportar a Excel',
						title: 'Lista de Arriendos Inactivos',
						className: 'btn btn-success',
						exportOptions: {
							// Especifica aquí las columnas que quieres exportar (excluyendo la de acciones)
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],

							format: {
								body: function (data, row, column, node) {
									// Asegurarse de convertir cada dato a mayúsculas y limpiar links
									let cleanData = $(node).text().toUpperCase();
									return cleanData;
								},
							},
						},
					},
				],
			});

			// Desactiva la búsqueda al presionar una tecla
			$('div.dataTables_filter input').unbind();

			// Agrega el botón de búsqueda si no existe
			if (!$('#divbotonbuscar').length) {
				$(
					"<div id='divbotonbuscar'><button id='buscar' class='btn btn-light btn-buscar-tablas'>Buscar</button></div>"
				).insertBefore('.dataTables_filter input');
			}

			$('.dataTables_filter').css('display', 'none');

			// Configura el evento de clic para el botón de búsqueda
			$('#buscar')
				.off('click')
				.on('click', function (e) {
					loadArriendo_List_Inactivos(); // Asegúrate de que llame a esta función
				});
		}
	});
}

function formateoDivisa(valor) {
	// Convertir el valor a un número flotante para asegurar el formato
	var numero = parseFloat(valor);

	// Verificar si el número es válido
	if (isNaN(numero)) {
		return '$ 0';
	}

	// Formatear el número con separador de miles y sin decimales
	return (
		'$ ' +
		numero.toLocaleString('es-CL', {
			minimumFractionDigits: 0, // Esto elimina los decimales
			maximumFractionDigits: 0, // Esto elimina los decimales
		})
	);
}

//*********************   Limpia   **************************/

function limpiarFiltros() {
	// Verificar si algún campo tiene valor
	// Limpiar los valores de los campos
	//document.getElementById('EstadoArriendo').value = '';
	document.getElementById('codigo_propiedad').value = '';
	document.getElementById('Propietario').value = '';
	document.getElementById('Arrendatario').value = '';
	// document.getElementById('estadoPropiedad').value = '';
	// document.getElementById('estadoContrato').value = '';
	document.getElementById('FichaArriendo').value = '';

	$('#FichaArriendo').val('');
	$('.spinnerArrendatario').css('display', 'none');

	localStorage.clear();
	document.location.href = 'index.php?component=arriendo&view=arriendo_list';
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
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

//*********************************************************************

//Validacion de monto

function validarComisionArriendo() {
	var monedaSeleccionada = document.getElementById(
		'monedaComisionArriendo'
	).value;
	var comisionArriendoInput = document.getElementById('comisionArriendo').value;

	if (monedaSeleccionada === '1' || monedaSeleccionada === 'Porcentaje') {
		// Validar que el valor no sea mayor a 100
		if (parseInt(comisionArriendoInput) > 100) {
			//console.log("No puede ser mayor que 100");
			//alert('El valor de la comisión no puede ser mayor a 100%');
			// Establecer el valor en 100
			Swal.fire({
				title: 'Atención ',
				text: 'Al seleccionar Porcentaje de la comisión de arriendo no debe superar el 100%',
				icon: 'warning',
			});
			document.getElementById('comisionArriendo').value = 100;
		}
	}
}

function validarMontoMulta() {
	var monedaSeleccionada = document.getElementById('monedaMulta').value;
	var montoMulta = document.getElementById('montoMultaAtraso').value;

	if (monedaSeleccionada === '1' || monedaSeleccionada === 'Porcentaje') {
		// Validar que el valor no sea mayor a 100
		if (parseInt(montoMulta) > 100) {
			//console.log("No puede ser mayor que 100");
			//alert('El valor de la comisión no puede ser mayor a 100%');
			// Establecer el valor en 100
			Swal.fire({
				title: 'Atención ',
				text: 'Al seleccionar Porcentaje de la comisión de arriendo no debe superar el 100%',
				icon: 'warning',
			});
			document.getElementById('montoMultaAtraso').value = 100;
		}
	}
}

function validarTipoReajuste() {
	var tipoReajuste = document.getElementById('tipoReajuste').value;
	var CantidadReajuste = document.getElementById('CantidadReajuste').value;
	//	console.log("tipoReajuste", tipoReajuste);
	//	console.log("CantidadReajuste", CantidadReajuste);
	if (tipoReajuste === 'IPC' || tipoReajuste === 'Fijo porcentual') {
		// Validar que el valor no sea mayor a 100
		if (parseInt(CantidadReajuste) > 100) {
			// console.log("No puede ser mayor que 100");
			//alert('El valor de la comisión no puede ser mayor a 100%');
			// Establecer el valor en 100
			Swal.fire({
				title: 'Atención ',
				text:
					'Al seleccionar ' +
					tipoReajuste +
					' en reajuste no debe superar el 100%',
				icon: 'warning',
			});
			document.getElementById('CantidadReajuste').value = 100;
		}
	}
}

function limpiarReajustes() {
	var tipoReajuste = document.getElementById('tipoReajuste').value;
	var CantidadReajuste = document.getElementById('CantidadReajuste').value;
	var selectElement = document.getElementById('meses');
	var selectedOption = selectElement.options[selectElement.selectedIndex];
	console.log('selectedOption ', selectedOption);
	if (tipoReajuste === 'Sin reajuste') {
		if (CantidadReajuste == null || CantidadReajuste == '') {
			document.getElementById('CantidadReajuste').value = '';
			document.getElementById('CantidadReajuste').disabled = true;
			document.getElementById('permiteReajusteNegativo').disabled = true;
			document.getElementById('meses').disabled = true;
			return;
		}
		Swal.fire({
			title: '¿Estás seguro?',
			text: 'Al seleccionar Sin Reajuste se reestableceran los valores',
			icon: 'warning',
			showDenyButton: true,
			confirmButtonText: 'Si',
			denyButtonText: 'No',
		}).then((result) => {
			if (result.isConfirmed) {
				document.getElementById('CantidadReajuste').value = '';
				document.getElementById('CantidadReajuste').disabled = true;
				document.getElementById('permiteReajusteNegativo').disabled = true;

				$('#meses').val(null).trigger('change');
				document.getElementById('meses').disabled = true;
			} else if (result.isDenied) {
			}
		});
	} else {
		document.getElementById('CantidadReajuste').disabled = false;
		document.getElementById('permiteReajusteNegativo').disabled = false;
		document.getElementById('meses').disabled = false;
	}
}

function validarComisionAdministracion() {
	var monedaSeleccionada = document.getElementById(
		'monedaComisionAdministracion'
	).value;
	var comisionArriendoInput = document.getElementById(
		'comisionAdministracion'
	).value;
	if (monedaSeleccionada === '1' || monedaSeleccionada === 'Porcentaje') {
		// Validar que el valor no sea mayor a 100
		if (parseInt(comisionArriendoInput) > 100) {
			//console.log("No puede ser mayor que 100");
			//alert('El valor de la comisión no puede ser mayor a 100%');
			// Establecer el valor en 100
			Swal.fire({
				title: 'Atención ',
				text: 'Al seleccionar Porcentaje de la comisión de Administracion no debe superar el 100%',
				icon: 'warning',
			});

			//comisionArriendoInput.value = 100;
			document.getElementById('comisionAdministracion').value = 100;
		}
	}
}

function enviarRentdesk() {
	
	var formData = new FormData(document.getElementById('formulario'));

	var url = window.location.href;
	var parametros = new URL(url).searchParams;

	formData.append('token_arrendatario', parametros.get('token'));

	// Capturar los valores de los montos
	var dataMeses = {};
	$('#meses input').each(function () {
		var inputName = $(this).attr('name'); // Obtener el nombre del input
		var inputValue = $(this).val(); // Obtener el valor del input
		dataMeses[inputName] = inputValue; // Almacenar en el objeto
	});

	// Capturar los valores de los selectores dentro del div "meses" para monedas y aplicar
	var dataMonedas = {};
	var dataAplica = {};

	$('#meses select').each(function () {
		var selectName = $(this).attr('name'); // Obtener el nombre del select
		var selectValue = $(this).val(); // Obtener el valor seleccionado

		// Comprobar si es un selector de moneda o de periodicidad
		if (selectName.startsWith('diasPagoTipoMoneda')) {
			dataMonedas[selectName] = selectValue; // Lo almacenamos en el objeto de monedas
		} else if (selectName.startsWith('OpcionAplicar')) {
			dataAplica[selectName] = selectValue; // Lo almacenamos en el objeto de periodicidad
		}
	});

	var facturarComisionAdministracion = document.getElementById('facturarComisionAdministracion').value

	// Convertimos los objetos a JSON y los agregamos al formData
	formData.append('dataMeses', JSON.stringify(dataMeses));
	formData.append('dataMonedas', JSON.stringify(dataMonedas));
	formData.append('dataAplica', JSON.stringify(dataAplica));
	formData.append('facturarComisionAdministracion', facturarComisionAdministracion);
	// const propiedad_input = document.getElementById("codigo_propiedad");
	// var propiedad = propiedad_input.value;

	// const arrendatario_input = document.getElementById("arrendatario");
	// var arrendatario = arrendatario_input.value;

	// const codeudor_input = document.getElementById("codeudor");
	// var codeudor = codeudor_input.value;

	// if (propiedad == null || propiedad == "") {
	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe agregar una propiedad",
	//     icon: "warning",
	//   });
	//   // console.log("propiedad vacia");
	//   return;
	// }

	// if (arrendatario == null || arrendatario == "") {

	//   //ValidarPropiedadAarrendar(propiedad);

	//   Swal.fire({
	//     title: "Atención ",
	//     text: "Debe agregar una arrendatario",
	//     icon: "warning",
	//   });
	//   //  console.log("arrendatario vacia");
	//   return;
	// }

	Swal.fire({
		title: '¿Estás seguro de guardar los cambios?',
		text: '',
		icon: 'warning',
		showDenyButton: true,
		denyButtonText: 'No',
		confirmButtonText: 'Si',
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: 'components/arriendo/models/insert_update.php',
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
					Swal.fire({
						title: 'Arriendo guardado',
						text: '',
						icon: 'success',
						confirmButtonText: 'Continuar',
					}).then((result) => {
						if (result.value) {
							//document.location.href = "index.php?component=arriendo&view=arriendo_list";
							document.location.reload();
						}
					});
					return false;
				} else {
					Swal.fire({
						title: 'Error',
						text: 'No se actualizar el arriendo ' + mensaje,
						icon: 'error',
					});
					// alert("No se logro crear arriendo");
					return false;
				}
			});
		} else if (result.isDenied) {
			// Si el usuario hace clic en "Cancelar"
			// Aquí puedes cerrar el modal de SweetAlert si lo deseas
		}
	});
} //function enviarRentdesk

function creaArriendo() {
	var formData = new FormData(document.getElementById('formulario'));

	const propiedad_input = document.getElementById('codigo_propiedad');
	var propiedad = propiedad_input.value;

	const arrendatario_input = document.getElementById('arrendatario');
	var arrendatario = arrendatario_input.value;

	const codeudor_input = document.getElementById('codeudor');
	var codeudor = codeudor_input.value;

	if (propiedad == null || propiedad == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar una propiedad',
			icon: 'warning',
		});
		// console.log("propiedad vacia");
		return;
	}

	if (arrendatario == null || arrendatario == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar una arrendatario',
			icon: 'warning',
		});
		return;
	}

	$.ajax({
		url: 'components/arriendo/models/insert_arriendo_base.php',
		type: 'post',
		dataType: 'html',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		// console.log("res");
		// console.log(res);
		var retorno = res.split(',xxx,');
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var token = retorno[3];

		if (resultado == 'OK') {
			//$.showAlert({ title: "Atención", body: mensaje });

			//alert("Arriendo creado exitosamente, puede continuar con formulario");
			Swal.fire({
				title: 'Arriendo Creado',
				text: 'Arriendo creado exitosamente, puede continuar con formulario',
				icon: 'success',
				confirmButtonText: 'Continuar',
			}).then((result) => {
				if (result.value) {
					document.location.href =
						'index.php?view=arriendo_editar&component=arriendo&token=' + token;
				}
			});
			// document.location.href =
			//"index.php?view=arriendo_editar&component=arriendo&token=" + token;
			onchange();
		} else {
			$.showAlert({ title: 'Error', body: mensaje });
			Swal.fire({
				title: 'Error',
				text: 'No se logro crear arriendo',
				icon: 'error',
			});
			// alert("No se logro crear arriendo");
			return false;
		}
	});
} //function enviarRentdesk

function cargaDocumento(componente) {
	var formData = new FormData(document.getElementById('formulario'));

	var url = window.location.href;
	console.log(url);
	var parametros = new URL(url).searchParams;
	console.log(parametros.get('token'));
	formData.append('token_arrendatario', parametros.get('token'));

	const titulo_input = document.getElementById('documentoTitulo');
	var titulo = titulo_input.value;

	if (titulo == null || titulo == '') {
		Swal.fire({
			title: 'Atención',
			text: 'Debe agregar un titulo',
			icon: 'warning',
		});
		// console.log("titulo vacio");
		return;
	}

	console.log('Enviando Documentos');
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	$.ajax({
		url: 'components/arriendo/models/insert_archivo.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		//  console.log("res");
		//console.log(res);
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
			//resetearFieldsetDocumento();

			cargarDocumentos();
			resetFormGuardar();
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

function enviar() {
	var archivo = $('#archivo').val();
	var archivo_bd = $('#archivo_bd').val();

	if (archivo == '' && archivo_bd == 'N') {
		$.showAlert({ title: 'Atención', body: 'Debe Adjuntar el mandato' });
		return;
	}

	if (archivo) {
		// console.log("EXISTE ARCHIVO");
		$.showAlert({ title: 'Atención', body: 'Debe Adjuntar el mandato' });
		return;
	}

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	var formData = new FormData(document.getElementById('formulario'));

	$.ajax({
		url: 'components/arriendo/models/insert_update.php',
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
				'index.php?component=arriendo&view=arriendo&token=' + token;
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
		$('#tabla').DataTable({
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
			var table = $('#tabla').DataTable();
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
		$('#tabla').DataTable({
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
			var table = $('#tabla').DataTable();
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
		$('#tabla').DataTable({
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
			var table = $('#tabla').DataTable();
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

function onChangePersona() {
	var selectedValuePropiedad = $('#propiedad').val();
	var selectedValueArrendatario = $('#arrendatario').val();
	var selectedValueCodeudor = $('#codeudor').val();
	$('#section-Informacion-old').hide();
	/*
  $("#section-Contrato").hide();
  $("#section-Reajuste").hide();
  $("#section-Comisiones").hide();
  $("#section-Otros").hide();
  $("#section-Documentos").hide();
  $("#Titulo-contrato").hide();

  $("#bt-volver").hide();
  $("#bt-aceptar").hide();
	*/
	$('#arrendatario').attr('disabled', true);
	$('#codeudor').attr('disabled', true);

	try {
		var tipoReajuste = document.getElementById('tipoReajuste').value;
	} catch (error) {
		var tipoReajuste = '';
	}

	//console.log(" tipoReajuste inicio", tipoReajuste)
	if (tipoReajuste === 'Sin reajuste') {
		document.getElementById('CantidadReajuste').value = '';
		document.getElementById('CantidadReajuste').disabled = true;
		document.getElementById('permiteReajusteNegativo').disabled = true;

		$('#meses').val(null).trigger('change');
		document.getElementById('meses').disabled = true;
	}

	if (selectedValuePropiedad && selectedValueArrendatario) {
		$('#section-Contrato').show();
		$('#section-Reajuste').show();
		$('#section-Comisiones').show();
		$('#section-Otros').show();
		$('#section-Documentos').show();
		$('#Titulo-contrato').show();
		$('#bt-volver').show();
		$('#bt-aceptar').show();
		$('#propiedad').prop('disabled', true);
		$('#arrendatario').attr('disabled', true);
		$('#codeudor').attr('disabled', true);
	}
}

/*Funciones de busqueda Propiedad*/

function buscarPropiedadAutocomplete(valor, tipo) {
	var codigo = document.getElementById(tipo).value;

	var caracteres = codigo.length;
	//Si por lo menos ha ingresado 3 caracteres comenzamos a autocompletar
	if (caracteres >= 1) {
		$.ajax({
			type: 'POST',
			url: 'components/arriendo/models/buscar_propiedades_autocomplete.php',
			data: 'codigo=' + codigo + '&tipo=' + tipo,
			success: function (data) {
				$('#suggestions_' + tipo)
					.fadeIn(500)
					.html(data);
				$('.suggest-element').on('click', function () {
					var direccion = $(this).text();
					var partes = direccion.split(' / ');
					var codigo_propiedad = partes[0];
					$('#codigo_propiedad').val(codigo_propiedad);
					return false;
				});
			},
		});
	} else {
		ocultarAutocomplete(tipo);
	}
}

function buscarPropiedadAutocompleteGenerica(valor, tipo) {
	var codigo = document.getElementById(tipo).value;

	var caracteres = codigo.length;
	//Si por lo menos ha ingresado 3 caracteres comenzamos a autocompletar
	if (caracteres >= 1) {
		$.ajax({
			type: 'POST',
			url: 'components/arriendo/models/buscar_propiedades_autocomplete_generica.php',
			data: 'codigo=' + codigo + '&tipo=' + tipo,
			success: function (data) {
				$('#suggestions_' + tipo)
					.fadeIn(500)
					.html(data);
				$('.suggest-element').on('click', function () {
					var valorSugerido = $(this).text(); // Obtener el texto de la sugerencia
					var primerValor = valorSugerido.split('|')[0].trim(); // Obtener el primer valor antes del '/'
					$('#' + tipo).val(primerValor); // Llenar el campo con el valor sugerido
					$('#suggestions_' + tipo).fadeOut(500); // Ocultar las sugerencias
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

	document.getElementById('codigo_propiedad').value = codigo;

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	$.ajax({
		type: 'POST',
		url: 'components/arriendo/models/valida_propiedad.php',
		data: 'codigo=' + codigo,
		dataType: 'html',
		success: function (res) {
			console.log(res);
			var retorno = res.split(',xxx,');
			var resultado = retorno[1];
			var mensaje = retorno[2];
			var token = retorno[3];

			if (resultado == 'ERROR') {
				Swal.fire({
					icon: 'warning',
					title: 'Propiedad se encuentra arrendada',
					text: 'Actualmente, esta propiedad está bajo un contrato de arriendo vigente.',
				}).then((result) => {
					/* Read more about isConfirmed, isDenied below */
					if (result.isConfirmed) {
						location.reload();
					} else {
						location.reload();
					}
				});

				// Swal.fire({
				//   title: "Propiedad se encuentra arrendada",
				//   text: "El arriendo debe estar en estado finalizado antes de volver a rentar",
				//   icon: "warning",
				//   showCancelButton: true,
				//   confirmButtonText: "Si",
				//   cancelButtonText: "No"
				// }).then((result) => {
				//   if (result.isConfirmed) {
				//     // Redireccionar a una página web
				//     window.location.href = "index.php?view=arriendo_editar&component=arriendo&token=" + token;
				//   } else {
				//     $("#arrendatario").attr("disabled", true);
				//     $("#codeudor").attr("disabled", true);
				//   }
				// });
			} else {
				$('#arrendatario').attr('disabled', false);
				$('#codeudor').attr('disabled', false);
			}
		},
	});

	//document.getElementById("codigo").value = $(elemento).text();
}

function ocultarAutocomplete(tipo) {
	$('#suggestions_' + tipo).fadeOut(500);
}

/*Funciones de busqueda Personas (clientes)*/

function buscarPersonaAutocomplete(valor, tipo) {
	var codigo = document.getElementById(tipo).value;

	var caracteres = codigo.length;
	//Si por lo menos ha ingresado 3 caracteres comenzamos a autocompletar
	if (caracteres >= 3) {
		$('.spinnerArrendatario').css('display', 'block');

		$.ajax({
			type: 'POST',
			url: 'components/arriendo/models/buscar_persona_autocomplete.php',
			data: 'codigo=' + codigo + '&tipo=' + tipo,
			success: function (data) {
				$('#suggestions_' + tipo)
					.fadeIn(500)
					.html(data);
				$('.suggest-element').on('click', function () {
					var valorSugerido = $(this).text(); // Obtener el texto de la sugerencia
					var primerValor = valorSugerido.split('|')[1].trim(); // Obtener el primer valor antes del '/'
					$('#' + tipo).val(primerValor); // Llenar el campo con el valor sugerido
					$('#suggestions_' + tipo).fadeOut(500); // Ocultar las sugerencias
					$('.spinnerArrendatario').css('display', 'none');
					return false;
				});
			},
		});
	} else {
		ocultarAutocomplete(tipo);
		$('.spinnerArrendatario').css('display', 'none');
	}
}

//******************************************************************************

function guardarCheque() {
	var formData = new FormData(document.getElementById('cheque_formulario'));
	var jsonInformacionNueva = obtenerValoresFormulario('cheque_formulario');
	var id_ficha = $('#id_ficha').val();
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	//console.log(parametros.get("token"));
	formData.append('token', parametros.get('token'));
	$.ajax({
		url: 'components/arriendo/models/insert_cheque.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			Swal.fire({
				title: 'Cheque registrado',
				text: 'El cheque se registro correctamente',
				icon: 'success',
			});
			var id_cheque = res;
			cargarCheques();
			registroHistorial(
				'Crear',
				'',
				jsonInformacionNueva,
				'Cheque',
				id_ficha,
				id_cheque
			);
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			Swal.fire({
				title: 'Atención',
				text: 'El cheque no se registro',
				icon: 'warning',
			});
		});
	$('#cheque_formulario')[0].reset();
	$('#modalChequesIngreso').modal('hide');
	cargarCheques();
}

function cargarCheques() {
	$.ajax({
		url: 'components/arriendo/models/listado_cheques.php',
		type: 'POST',
		dataType: 'json',
		data: { idFicha: $('#id_ficha').val() },
		cache: false,
		success: function (data) {
			var tbody = $('#cheques tbody');
			tbody.empty();
			if (data != null) {
				$.each(data, function (index, item) {
					let desposito = item.desposito ? 'checked' : '';
					let cobrar = item.cobrar ? 'checked' : '';
					var newRow = $('<tr>');

					newRow.append(
						'<td>' + moment(item.fecha_cobro).format('DD-MM-YYYY') + '</td>'
					);
					newRow.append('<td>' + item.razon + '</td>');
					newRow.append('<td>$' + item.monto.toLocaleString() + '</td>');
					newRow.append('<td>' + item.numero_documento + '</td>');
					newRow.append('<td>' + item.nombre + '</td>');
					newRow.append('<td>' + item.girador + '</td>');
					newRow.append(
						`<td><div class="d-flex">
                      <label class="switch">
                        <input name="desposito" class="form-check-input switchCheques" type="checkbox" role="switch" ${desposito} data-token="${item.token
						}">
                        <span class="slider round"></span>
                        <span class="switchText">${item.desposito ? 'Si' : 'No'
						}</span>
                      </label>
                  </div></td>`
					);
					newRow.append(
						`<td><div class="d-flex">
                      <label class="switch">
                        <input name="cobrar" class="form-check-input switchCheques" type="checkbox" role="switch" ${cobrar} data-token="${item.token
						}">
                        <span class="slider round"></span>
                        <span class="switchText">${item.cobrar ? 'Si' : 'No'
						}</span>
                      </label>
                  </div></td>`
					);

					newRow.append(
						`<td>
                      <div class='d-flex align-items-center' style='gap: .5rem;'>
                        <a href="#" type="button" class="btn btn-secondary m-0" style="padding: .5rem;${item.comentario === '' ? 'visibility: hidden;' : ''
						}"  aria-label="Info" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="${item.comentario
						}">
                          <i class="fa-solid fa-circle-info" style="font-size: .75rem;"></i>
                        </a>
                        <a data-bs-toggle='modal' onclick='cargarChequesEditar(${item.id
						}, ${item.monto}, "${item.razon}", ${item.banco
						}, "${moment(item.fecha_cobro).format('YYYY-MM-DD')}", "${item.girador
						}", ${item.numero_documento}, ${item.cantidad}, "${item.comentario
						}")' data-bs-target='#modalChequesEditar' type='button' class='btn btn-info m-0 d-flex align-items-center' style='padding: .5rem;' aria-label='Editar' title='Editar'>
                          <i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i>
                        </a>
                        <button onclick='eliminarCheques(${item.id}, ${item.monto
						}, "${item.razon}", ${item.banco}, "${moment(
							item.fecha_cobro
						).format('YYYY-MM-DD')}", "${item.girador}", ${item.numero_documento
						}, ${item.cantidad
						})' type='button' class='btn btn-danger m-0 d-flex align-items-center' style='padding: .5rem;' title='Eliminar'>
                          <i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
                        </button>
                      </div>
                      </td>`
					);

					tbody.append(newRow);
				});
				// Aquí inicializamos los tooltips para los botones dinámicos
				$('[data-bs-toggle="tooltip"]').tooltip();
			} else {
				tbody.append(
					"<tr><td colspan='9' style='text-align:center'> No hay Cheques</td></tr>"
				);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error('Error al cargar cheques:', textStatus, errorThrown);
		},
	});
}

// ************* bruno ****************

function cargarChequesEditar(
	idCheque,
	monto,
	razon,
	banco,
	fecha_cobro,
	girador,
	numero_documento,
	cantidad,
	comentario
) {
	// Formatear monto
	var montoFormateado = monto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

	// Asignar valores a los campos del formulario
	$('#Cheque_Monto_Editar').val(montoFormateado);
	$('#Cheque_Razon_Editar').val(razon);
	$('#Cheque_Fecha_Editar').val(fecha_cobro);
	$('#Cheque_Girador_Editar').val(girador);
	$('#Cheque_Numero_Doc_Editar').val(numero_documento);
	$('#Cantidad_Cheque_Editar').val(cantidad);
	$('#tipo_banco_editar').val(banco);
	$('#ID_Cheque_Editar').val(idCheque);
	$('#Comentario_Cheque_Editar').val(comentario);

	// Obtener valores del formulario y guardar en sessionStorage
	var jsonInformacionAntigua = obtenerValoresFormulario(
		'cheque_formulario_Editar'
	);
	sessionStorage.setItem('informacionAntigua', jsonInformacionAntigua);

	// Imprimir jsonInformacionAntigua en la consola para depuración
}

// *********** bruno *************

function editarCheque() {
	var formData = new FormData(
		document.getElementById('cheque_formulario_Editar')
	);
	var jsonInformacionNueva = obtenerValoresFormulario(
		'cheque_formulario_Editar'
	);
	var objeto_json = JSON.parse(jsonInformacionNueva);
	var id_cheque = objeto_json.ID_Cheque_Editar;
	var id_ficha = $('#id_ficha').val();

	$.ajax({
		url: 'components/arriendo/models/editar_cheque.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			console.log('Cheque actualizado:', res); // Verificar la respuesta del servidor
			Swal.fire({
				title: 'Cheque actualizado',
				text: 'El cheque se actualizo correctamente',
				icon: 'success',
			});

			var jsonInformacioantigua = capturarInformacionAntigua();
			registroHistorial(
				'Modificar',
				jsonInformacioantigua,
				jsonInformacionNueva,
				'Cheque',
				id_ficha,
				id_cheque
			);

			// Asegurarse de que la tabla se recargue correctamente
			cargarCheques();

			// Mover el cierre del modal aquí para asegurarse de que no interfiera
			$('#modalChequesEditar').modal('hide');
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			console.error('Error al actualizar cheque:', textStatus, errorThrown);
			Swal.fire({
				title: 'Atención',
				text: 'El cheque no se actualizo',
				icon: 'warning',
			});
		});
}

function eliminarCheques(
	idCheque,
	monto,
	razon,
	banco,
	fecha_cobro,
	girador,
	numero_documento,
	cantidad
) {
	var id_ficha = $('#id_ficha').val();
	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Una vez eliminado, no podrás recuperar este cheque',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Eliminar',
		denyButtonText: 'Cancelar',
	}).then((result) => {
		if (result.isConfirmed) {
			// Si el usuario hace clic en "Eliminar"
			var infoEliminado =
				'Monto Cheque eliminado: ' +
				monto +
				', Razon Cheque eliminado: ' +
				razon +
				', Banco Cheque eliminado: ' +
				banco +
				', Fecha Cobro Cheque eliminado: ' +
				fecha_cobro +
				', Girador Cheque eliminado: ' +
				girador +
				', Nummero de documento Cheque eliminado: ' +
				numero_documento +
				', Cantidad Cheque eliminado: ' +
				cantidad;
			$.ajax({
				url: 'components/arriendo/models/delete_cheque.php', // URL del archivo PHP que manejará la solicitud
				type: 'POST', // Método de solicitud POST
				dataType: 'text', // Tipo de datos que esperas recibir del servidor (puede ser json, html, xml, etc.)
				data: { idCheque: idCheque }, // Datos que deseas enviar al servidor
				success: function (response) {
					// Función que se ejecuta cuando la solicitud es exitosa
					Swal.fire({
						title: 'Cheque eliminado',
						text: 'El cheque se elimino correctamente',
						icon: 'success',
					});
					registroHistorial(
						'Eliminar',
						'',
						infoEliminado,
						'Cheque',
						id_ficha,
						idCheque
					);
					cargarCheques();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					// Función que se ejecuta si hay un error en la solicitud
					console.error('Error en la solicitud:', textStatus, errorThrown); // Imprimir el error en la consola
					// Puedes mostrar un mensaje de error al usuario o realizar otras acciones aquí
				},
			});
		} else if (result.isDenied) {
			// Si el usuario hace clic en "Cancelar"
			// Aquí puedes cerrar el modal de SweetAlert si lo deseas
		}
	});
}

//******************************* CARGA Lista DOCUMENTOS *************************************

function cargarDocumentos() {
	// Realizar la solicitud AJAX para obtener los datos
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	var token_arriendo = parametros.get('token');
	//console.log(parametros.get('token'));
	//console.log("Ingresando a ajax");
	$.ajax({
		url: 'components/arriendo/models/listado_documentos.php',
		type: 'POST',
		dataType: 'json',
		//data:  "token="+ parametros.get('token') ,
		data: { token: token_arriendo },
		cache: false,
		success: function (data) {
			//console.log("entrando  a la funcion");
			//  console.log(data);
			if (data != null) {
				//  console.log("la data no es nula");
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
		   <button type='button' id='btn-eliminar-documento' onclick='eliminarDocumento("${item.token}")' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i></button></div></td>`
					);
					// Agregar la fila al cuerpo de la tabla
					tbody.append(newRow);
				});
			} else {
				var tbody = $('#documentos tbody');
				tbody.empty();
				var newRow = $('<tr>');
				//  console.log("error");
				newRow.append(
					"<td colspan='9' style='text-align:center'> No hay Documentos</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			// console.log("error ");
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
	var url = 'upload\\arriendo\\' + archivo + '.' + extension;

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

/*
function eliminarDocumento(idDocumento) {
  console.log(idDocumento);
  
   var formData = new FormData(document.getElementById("formulario"));
  formData.append('tokenEliminar', idDocumento);

  Swal.fire({
	title: "¿Estás seguro?",
	text: "Una vez eliminado, no podrás recuperar este documento",
	icon: "warning",
	showDenyButton: true,
	confirmButtonText: "Eliminar",
	denyButtonText: "Cancelar",
  }).then((result) => {
	if (result.isConfirmed) {
	  // Si el usuario hace clic en "Eliminar"
	  $.ajax({
		url: "components/arriendo/models/delete_documento.php", // URL del archivo PHP que manejará la solicitud
		type: "POST", // Método de solicitud POST
		dataType: "text", // Tipo de datos que esperas recibir del servidor (puede ser json, html, xml, etc.)
		data: formData, // Datos que deseas enviar al servidor
		success: function (response) {
		  // Función que se ejecuta cuando la solicitud es exitosa
		  Swal.fire({
			title: "Documento eliminado",
			text: "El documento se elimino correctamente",
			icon: "success",
		  });
		  cargarDocumentos();
		},
		error: function (jqXHR, textStatus, errorThrown) {
		  // Función que se ejecuta si hay un error en la solicitud
		  console.error("Error en la solicitud:", textStatus, errorThrown); // Imprimir el error en la consola
		  // Puedes mostrar un mensaje de error al usuario o realizar otras acciones aquí
		},
	  });
	} else if (result.isDenied) {
	  // Si el usuario hace clic en "Cancelar"
	  // Aquí puedes cerrar el modal de SweetAlert si lo deseas
	}
  });
}*/

function eliminarDocumento(idDocumento) {
	// jhernandez bloqueo de formulario cuando el arriendo este finalizado.
	var estado_contrato_seleccionado = $(
		'#estadoContrato option:selected'
	).text();

	if (estado_contrato_seleccionado === 'Finalizado') {
		Swal.fire({
			title: 'No puedes eliminar el documento de una propiedad inactiva',
			text: '',
			icon: 'warning',
		});
	} else {
		// Obtener el formulario y crear un objeto FormData
		var formData = new FormData(document.getElementById('formulario'));
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
					url: 'components/arriendo/models/delete_documento.php',
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
}

function editarDocumento() {
	var formData = new FormData(document.getElementById('formulario'));
	var url = window.location.href;
	console.log(url);
	var parametros = new URL(url).searchParams;
	var token_arriendo = parametros.get('token');
	formData.append('token_arrendatario', parametros.get('token'));

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
		url: 'components/arriendo/models/editar_documento.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		//  console.log("res");
		//  console.log(res);
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
	var formData = new FormData(document.getElementById('formulario'));
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
		url: 'components/arriendo/models/editar_titulo_documento.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		//  console.log("res");
		// console.log(res);
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

//**********************************FIN DOCUMENTOS***********************************************************

//*****************************  SERVICIO Y PAGOS  ****************************************

function guardaServicio() {
	// Capturar valores de los campos
	const tipoServicio = document.getElementById('TipoServicio').value;
	const proveedor = document.getElementById('TipoProveedorServicio').value;
	const numeroCliente = document.getElementById('numeroCliente').value;

	var url = window.location.href;
	var parametros = new URL(url).searchParams;
	var token_arrendatario = parametros.get('token');

	// Validar campos requeridos
	if (!tipoServicio) {
		Swal.fire({
			title: 'Atención',
			text: 'Debe seleccionar un servicio básico',
			icon: 'warning',
		});
		return;
	}
	if (!proveedor) {
		Swal.fire({
			title: 'Atención',
			text: 'Debe seleccionar un proveedor',
			icon: 'warning',
		});
		return;
	}

	// Crear un objeto con los datos a enviar
	const formData = {
		TipoServicio: tipoServicio,
		TipoProveedorServicio: proveedor,
		numeroCliente: numeroCliente,
		token_arrendatario: token_arrendatario,
	};

	// Enviar datos con AJAX
	$.ajax({
		url: 'components/arriendo/models/insert_seguro.php', // Ruta al archivo PHP
		type: 'POST', // Tipo de solicitud
		data: formData, // Datos a enviar
		cache: false,
		success: function (res) {
			// Procesar la respuesta del servidor
			const retorno = res.split(',xxx,');
			const resultado = retorno[1];
			const mensaje = retorno[2];

			if (resultado === 'OK') {
				Swal.fire({
					title: 'Carga Correcta del servicio básico',
					text: 'Se guardó el registro del servicio básico',
					icon: 'success',
				});
				resetearFieldsetServicio();
				$('#modalServicioIngreso').modal('hide'); // Cerrar modal
				cargarServicios(); // Refrescar servicios
			} else {
				Swal.fire({
					title: 'Atención',
					text: mensaje,
					icon: 'warning',
				});
			}
		},
		error: function () {
			Swal.fire({
				title: 'Error',
				text: 'No se pudo conectar con el servidor.',
				icon: 'error',
			});
		},
	});
}

function guardaSeguro() {
	var formData = new FormData(document.getElementById('formulario'));
	var url = window.location.href;
	console.log(url);
	var parametros = new URL(url).searchParams;
	var token_arriendo = parametros.get('token');
	formData.append('token_arrendatario', parametros.get('token'));

	const TipoServicioSeguro_input =
		document.getElementById('TipoServicioSeguro');
	var TipoServicioSeguro = TipoServicioSeguro_input.value;

	if (TipoServicioSeguro == null || TipoServicioSeguro == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe seleccionar un Seguro',
			icon: 'warning',
		});
		return;
	}
	if (TipoServicioSeguro != null && TipoServicioSeguro != '') {
		const TipoProveedorServicioSeguro_input = document.getElementById(
			'TipoProveedorSeguro'
		);
		var TipoProveedorServicioSeguro = TipoServicioSeguro_input.value;

		const montoServicioSeguro_input = document.getElementById(
			'montoServicioSeguro'
		);
		var montoServicioSeguro = montoServicioSeguro_input.value;

		const PlanServicioSeguro_input = document.getElementById('PlanSeguro');
		var PlanServicioSeguro = PlanServicioSeguro_input.value;

		const ServicioSeguroFechaInicio_input = document.getElementById(
			'servicioSeguroFechaInicio'
		);
		var ServicioSeguroFechaInicio = ServicioSeguroFechaInicio_input.value;

		const ServicioSeguroFechaVencimiento_input = document.getElementById(
			'servicioSeguroFechaVencimiento'
		);
		var ServicioSeguroFechaVencimiento =
			ServicioSeguroFechaVencimiento_input.value;

		if (ServicioSeguroFechaInicio == null || ServicioSeguroFechaInicio == '') {
			Swal.fire({
				title: 'Atención ',
				text: 'Debe agregar una fecha de inicio de Seguro',
				icon: 'warning',
			});
			return;
		}

		if (
			ServicioSeguroFechaVencimiento == null ||
			ServicioSeguroFechaVencimiento == ''
		) {
			Swal.fire({
				title: 'Atención ',
				text: 'Debe agregar una fecha de vencimiento de Seguro',
				icon: 'warning',
			});
			return;
		}

		if (montoServicioSeguro == null || montoServicioSeguro == '') {
			Swal.fire({
				title: 'Atención ',
				text: 'Debe agregar un monto de Seguro',
				icon: 'warning',
			});
			return;
		}

		if (PlanServicioSeguro == null || PlanServicioSeguro == '') {
			Swal.fire({
				title: 'Atención ',
				text: 'Debe agregar un número de póliza',
				icon: 'warning',
			});
			return;
		}
	}

	console.log('Enviando a insertar seguros');
	$.ajax({
		url: 'components/arriendo/models/insert_seguro.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		//  console.log("res");
		//  console.log(res);
		var retorno = res.split(',xxx,');
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var token = retorno[3];

		if (resultado == 'OK') {
			Swal.fire({
				title: 'Carga Correcta del seguro',
				text: '',
				icon: 'success',
			});
			resetearFieldsetSeguro();
			$('#modalSeguroIngreso').modal('hide');
			cargarSeguros();
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

function resetearFieldsetSeguro() {
	// Obtener referencia al fieldset por su ID
	var fieldset = document.getElementById('section-ServicioYSeguros');

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
	var selectElement = document.getElementById('TipoServicioSeguro');
	var selectElementProveedor = document.getElementById('TipoProveedorSeguro');
	var selectElementPeriocidad = document.getElementById(
		'periocidadServicioSeguro'
	);
	var selectElementTipoMoneda = document.getElementById('monedaServicioSeguro');
	console.log('selectElement', selectElement);
	$('#TipoProveedorSeguro').attr('disabled', true);

	if (selectElement) {
		selectElement.selectedIndex = 0;
	}
	if (selectElementProveedor) {
		selectElementProveedor.selectedIndex = 0;
	}
	if (selectElementPeriocidad) {
		selectElementPeriocidad.selectedIndex = 0;
	}
	if (selectElementTipoMoneda) {
		selectElementTipoMoneda.selectedIndex = 0;
	}
}

function resetearFieldsetServicio() {
	// Obtener referencia al fieldset por su ID
	var fieldset = document.getElementById('section-ServicioYSeguros');

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
	var selectElement = document.getElementById('TipoServicio');
	var selectElementProveedor = document.getElementById('TipoProveedorServicio');
	var selectElementPeriocidad = document.getElementById('periocidadServicio');
	var selectElementTipoMoneda = document.getElementById('monedaServicio');
	console.log('selectElement', selectElement);
	$('#TipoProveedorServicio').attr('disabled', true);

	if (selectElement) {
		selectElement.selectedIndex = 0;
	}
	if (selectElementProveedor) {
		selectElementProveedor.selectedIndex = 0;
	}
	if (selectElementPeriocidad) {
		selectElementPeriocidad.selectedIndex = 0;
	}
	if (selectElementTipoMoneda) {
		selectElementTipoMoneda.selectedIndex = 0;
	}
}

function buscaProveedor(tipo) {
	var formData = new FormData(document.getElementById('formulario'));
	var url = window.location.href;
	console.log(url);
	var parametros = new URL(url).searchParams;
	var token_arriendo = parametros.get('token');
	formData.append('token_arrendatario', parametros.get('token'));

	const TipoServicioSeguro_input =
		document.getElementById('TipoServicioSeguro');
	var TipoServicioSeguro = TipoServicioSeguro_input.value;

	console.log('Busca seguro/seguro');
	$.ajax({
		url: 'components/arriendo/models/busca_proveedor.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		// console.log("res");
		// console.log(res);
		var retorno = res.split(',xxx,');
		var resultado = retorno[1];
		var servicioBasico = retorno[2];
		var seguros = retorno[3];

		if (resultado == 'OK' && tipo == 'seguro') {
			document.getElementById('TipoProveedorSeguro').innerHTML = seguros;
			$('#TipoProveedorSeguro').attr('disabled', false);
		}
		if (resultado == 'OK' && tipo == 'basico') {
			document.getElementById('TipoProveedorServicio').innerHTML =
				servicioBasico;
			$('#TipoProveedorServicio').attr('disabled', false);
		}
	});
} //function enviarRentdesk

function buscaProveedorEditar(tipo) {
	var formData = new FormData(document.getElementById('formulario'));
	var url = window.location.href;
	var parametros = new URL(url).searchParams;
	var token_arriendo = parametros.get('token');
	formData.append('token_arrendatario', parametros.get('token'));

	if (tipo == 'basico') {
		const TipoEditarServicio_input =
			document.getElementById('TipoEditarServicio');
		var TipoServicioSeguro = TipoEditarServicio_input.value;
		formData.append('TipoServicioEditar', TipoServicioSeguro);
	}

	if (tipo == 'seguro') {
		const TipoEditarSeguro_input = document.getElementById('TipoEditarSeguro');
		var TipoEditarSeguro = TipoEditarSeguro_input.value;
		formData.append('TipoEditarSeguro', TipoEditarSeguro);
	}

	console.log('Busca seguro/seguro');
	$.ajax({
		url: 'components/arriendo/models/busca_proveedor_editar.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		// console.log("res");
		//console.log(res);
		var retorno = res.split(',xxx,');
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var mensajeProveedor = retorno[3];

		if (resultado == 'OK' && tipo == 'seguro') {
			$('#TipoProveedorSeguro').attr('disabled', false);
			document.getElementById('TipoProveedorEditarSeguro').innerHTML =
				mensajeProveedor;
		}
		if (resultado == 'OK' && tipo == 'basico') {
			$('#TipoProveedorServicio').attr('disabled', false);
			document.getElementById('TipoProveedorEditar').innerHTML = mensaje;
		}
	});
} //function enviarRentdesk

function cargarServicios() {
	// Realizar la solicitud AJAX para obtener los datos
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	var token_arriendo = parametros.get('token');
	//console.log(parametros.get('token'));
	//console.log("Ingresando a ajax");
	$.ajax({
		url: 'components/arriendo/models/listado_servicios.php',
		type: 'POST',
		dataType: 'json',
		//data:  "token="+ parametros.get('token') ,
		data: { token: token_arriendo },
		cache: false,
		success: function (data) {
			//console.log("entrando  a la funcion");
			//console.log(data);
			if (data != null) {
				// console.log("la data no es nula");
				var tbody = $('#servicios tbody');
				// Limpiar el cuerpo de la tabla por si hay datos anteriores
				tbody.empty();
				// Iterar sobre los datos y agregar filas a la tabla
				$.each(data, function (index, item) {
					// Crear una nueva fila de la tabla
					var newRow = $('<tr>');

					// Agregar celdas a la fila con los datos
					newRow.append('<td>' + item.nombre + '</td>');
					newRow.append('<td>' + item.proveedor + '</td>');
					newRow.append('<td>' + item.plan_servicio + '</td>');
					newRow.append('<td>' + item.numero_cliente + '</td>');
					newRow.append('<td>' + item.tipo_moneda + '</td>');

					if (item.tipo_moneda == 'UF') {
						newRow.append(
							"<td style='text-align: right; max-width:120px; width:120px;'>" +
							item.monto +
							'</td>'
						);
					} else {
						var montoFormateado = item.monto.toLocaleString('es-CL', {
							style: 'currency',
							currency: 'CLP',
						});
						newRow.append(
							"<td style='text-align: right; max-width:120px; width:120px;'>" +
							montoFormateado +
							'</td>'
						);
					}

					newRow.append('<td>' + item.periodo + '</td>');

					newRow.append(
						'<td>' + moment(item.fecha_inicio).format('DD-MM-YYYY') + '</td>'
					);
					newRow.append(
						'<td>' +
						moment(item.fecha_vencimiento).format('DD-MM-YYYY') +
						'</td>'
					);
					if (
						item.fecha_modificacion != null &&
						item.fecha_modificacion != ''
					) {
						newRow.append(
							'<td>' +
							(item.fecha_modificacion
								? moment(item.fecha_modificacion).format('DD-MM-YYYY')
								: '-') +
							"  <i class='fa-solid fa-circle-info' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Modificado por : " +
							item.nombre_usuario +
							"'></i></td>"
						);
					} else {
						newRow.append('<td>-</td>');
					}
					newRow.append(
						`<td><div class='d-flex' style='gap: .5rem;'>
			<!-- <a data-bs-toggle='modal' data-bs-target='#modalEditarServicio' type='button' 
			 onclick='cargarServicioEditar(${item.id}, ${item.monto},"${item.periodo}","${item.plan_servicio}","${item.tipo_servicio}","${item.tipo_moneda}","${item.fecha_inicio}","${item.fecha_vencimiento}",${item.id_proveedor},${item.id_tipo_servicio},"${item.token}","${item.numero_cliente}")' 
			 class='btn btn-info m-0 d-flex' style='padding: .5rem;' aria-label='Editar' title='Editar'><i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i></a> -->
			<button onclick='eliminarServicio(${item.id})' id='btn-eliminar-servicios'  type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i></button></div></td>`
					);
					// Agregar la fila al cuerpo de la tabla
					tbody.append(newRow);
					$('[data-bs-toggle="tooltip"]').tooltip();
				});
				console.log(data);
			} else {
				var tbody = $('#servicios tbody');
				tbody.empty();
				var newRow = $('<tr>');
				//  console.log("error");
				newRow.append(
					"<td colspan='9' style='text-align:center'> No hay servicios basicos</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			// console.log("error ");
			console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
		},
	});
}

function cargarSeguros() {
	// Realizar la solicitud AJAX para obtener los datos
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	var token_arriendo = parametros.get('token');
	//console.log(parametros.get('token'));
	//console.log("Ingresando a ajax");
	$.ajax({
		url: 'components/arriendo/models/listado_seguro.php',
		type: 'POST',
		dataType: 'json',
		//data:  "token="+ parametros.get('token') ,
		data: { token: token_arriendo },
		cache: false,
		success: function (data) {
			//console.log("entrando  a la funcion");
			//console.log(data);
			if (data != null) {
				//console.log("la data no es nula");
				var tbody = $('#seguros tbody');
				// Limpiar el cuerpo de la tabla por si hay datos anteriores
				tbody.empty();
				// Iterar sobre los datos y agregar filas a la tabla
				$.each(data, function (index, item) {
					// Crear una nueva fila de la tabla
					var newRow = $('<tr>');

					// Agregar celdas a la fila con los datos
					newRow.append('<td>' + item.nombre + '</td>');
					newRow.append('<td>' + item.proveedor + '</td>');
					newRow.append('<td>' + item.plan_servicio + '</td>');
					newRow.append('<td>' + item.tipo_moneda + '</td>');
					if (item.tipo_moneda == 'UF') {
						newRow.append(
							"<td style='text-align: right; max-width:120px; width:120px;'>" +
							item.monto +
							'</td>'
						);
					} else {
						var montoFormateado = item.monto.toLocaleString('es-CL', {
							style: 'currency',
							currency: 'CLP',
						});
						newRow.append(
							"<td style='text-align: right; max-width:120px; width:120px;'>" +
							montoFormateado +
							'</td>'
						);
					}

					newRow.append('<td>' + item.periodo + '</td>');

					newRow.append(
						'<td>' + moment(item.fecha_inicio).format('DD-MM-YYYY') + '</td>'
					);
					newRow.append(
						'<td>' +
						moment(item.fecha_vencimiento).format('DD-MM-YYYY') +
						'</td>'
					);
					if (
						item.fecha_modificacion != null &&
						item.fecha_modificacion != ''
					) {
						newRow.append(
							'<td>' +
							(item.fecha_modificacion
								? moment(item.fecha_modificacion).format('DD-MM-YYYY')
								: '-') +
							"  <i class='fa-solid fa-circle-info' data-bs-toggle='tooltip' data-bs-placement='top' title='Modificado por : " +
							item.nombre_usuario +
							"'></i></td>"
						);
					} else {
						newRow.append('<td>-</td>');
					}
					if (item.notificacion_seguro == true) {
						newRow.append('<td> Activo </td>');
					} else {
						newRow.append('<td> Inactivo </td>');
					}
					//console.log("Notifica : ",item.notificacion_seguro);
					newRow.append(
						`<td><div class='d-flex' style='gap: .5rem;'>
			<a data-bs-toggle='modal' data-bs-target='#modalEditarSeguro' type='button' 
			onclick='cargarSeguroEditar(${item.id}, ${item.monto},"${item.periodo}","${item.plan_servicio}","${item.tipo_servicio}","${item.tipo_moneda}","${item.fecha_inicio}","${item.fecha_vencimiento}",${item.id_proveedor},${item.id_tipo_servicio},"${item.token}","${item.notificacion_seguro}")'
			class='btn btn-info m-0 d-flex' style='padding: .5rem;' aria-label='Editar' title='Editar'><i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i></a>
			<button onclick='eliminarServicio(${item.id})' id='btn-eliminar-seguros' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i></button></div></td>`
					);
					// Agregar la fila al cuerpo de la tabla
					tbody.append(newRow);
				});
				//console.log(data);
			} else {
				var tbody = $('#seguros tbody');
				tbody.empty();
				var newRow = $('<tr>');
				// console.log("error");
				newRow.append(
					"<td colspan='9' style='text-align:center'> No hay seguros</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			//  console.log("error ");
			//console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
		},
	});
}

function eliminarServicio(idServicio) {
	//console.log(idServicio);

	// jhernandez bloqueo de formulario cuando el arriendo este finalizado.
	var estado_contrato_seleccionado = $(
		'#estadoContrato option:selected'
	).text();

	if (estado_contrato_seleccionado === 'Finalizado') {
		Swal.fire({
			title: 'No puedes eliminar un servicio de una propiedad inactiva',
			text: '',
			icon: 'warning',
		});
	} else {
		Swal.fire({
			title: '¿Estás seguro?',
			text: 'Una vez eliminado, no podrás recuperar este registro',
			icon: 'warning',
			showDenyButton: true,
			confirmButtonText: 'Eliminar',
			denyButtonText: 'Cancelar',
		}).then((result) => {
			if (result.isConfirmed) {
				// Si el usuario hace clic en "Eliminar"
				$.ajax({
					url: 'components/arriendo/models/delete_servicio.php', // URL del archivo PHP que manejará la solicitud
					type: 'POST', // Método de solicitud POST
					dataType: 'text', // Tipo de datos que esperas recibir del servidor (puede ser json, html, xml, etc.)
					data: { idServicio: idServicio }, // Datos que deseas enviar al servidor
					success: function (response) {
						// Función que se ejecuta cuando la solicitud es exitosa
						Swal.fire({
							title: 'Registro eliminado',
							text: '',
							icon: 'success',
						});
						cargarServicios();
						cargarSeguros();
					},
					error: function (jqXHR, textStatus, errorThrown) {
						// Función que se ejecuta si hay un error en la solicitud
						console.error('Error en la solicitud:', textStatus, errorThrown); // Imprimir el error en la consola
						// Puedes mostrar un mensaje de error al usuario o realizar otras acciones aquí
					},
				});
			} else if (result.isDenied) {
				// Si el usuario hace clic en "Cancelar"
				// Aquí puedes cerrar el modal de SweetAlert si lo deseas
			}
		});
	}
}

function cargarServicioEditar(
	id,
	monto,
	periodo,
	plan_servicio,
	tipo_servicio,
	tipo_moneda,
	fecha_inicio,
	fecha_vencimiento,
	id_proveedor,
	id_tipo_servicio,
	token,
	numero_cliente
) {
	// Asigna los valores a los campos del formulario
	$('#TipoEditarServicio').val(id_tipo_servicio).trigger('change');
	$('#TipoProveedorEditar').val(id_proveedor).trigger('change');
	$('#numeroClienteEditar').val(numero_cliente);
	$('#PlanEditar').val(plan_servicio);
	$('#monedaEditar').val(tipo_moneda).trigger('change');
	$('#periocidadEditar').val(periodo).trigger('change');
	$('#montoEditar').val(monto);
	$('#servicioFechaInicioEditar').val(fecha_inicio.split('T')[0]); // Elimina la hora
	$('#servicioFechaVencimientoEditar').val(fecha_vencimiento.split('T')[0]); // Elimina la hora
	$('#ServicioTokenEditar').val(token);

	// Por si usas Select2 u otro plugin que necesite actualización manual
	$('#TipoEditarServicio').trigger('change.select2');
	$('#TipoProveedorEditar').trigger('change.select2');

	console.log('Datos cargados en el formulario:', {
		id,
		monto,
		periodo,
		plan_servicio,
		tipo_servicio,
		tipo_moneda,
		fecha_inicio,
		fecha_vencimiento,
		id_proveedor,
		id_tipo_servicio,
		token,
		numero_cliente,
	});
}

function cargarSeguroEditar(
	idCheque,
	monto,
	periodo,
	plan_servicio,
	tipo_servicio,
	tipo_moneda,
	fecha_inicio,
	fecha_vencimiento,
	id_proveedor,
	id_tipo_servicio,
	token,
	notifica
) {
	var fechaInicioFormateada = moment(fecha_inicio).format('YYYY-MM-DD');
	var fechaVencimientoFormateada =
		moment(fecha_vencimiento).format('YYYY-MM-DD');
	var numeroMonto = monto;
	var montoFormateado = numeroMonto
		.toString()
		.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
	var monto = $('#montoEditarSeguro').val(montoFormateado);

	var token = $('#SeguroTokenEditar').val(token);
	// var monto = $("#montoEditarSeguro").val(monto);
	var periodo = $('#periocidadEditarSeguro').val(periodo);
	var plan_servicio = $('#PlanEditarSeguro').val(plan_servicio);
	//var tipo_servicio = $("#TipoEditarServicio").val(tipo_servicio);
	var tipo_moneda = $('#monedaEditarSeguro').val(tipo_moneda);
	var fecha_inicio = $('#seguroFechaInicioEditar').val(fechaInicioFormateada);
	var fecha_vencimiento = $('#seguroFechaVencimientoEditar').val(
		fechaVencimientoFormateada
	);
	$('#servicioSeguroNotificacionEditar').val(notifica);

	var idCheque = $('#ID_Cheque_Editar').val(idCheque);

	BuscarServicioEditar(tipo_servicio, id_proveedor, id_tipo_servicio);
}

function BuscarServicioEditar(tipo_servicio, id_proveedor, id_tipo_servicio) {
	//console.log(tipo_servicio);
	//console.log(id_proveedor);
	//console.log(id_tipo_servicio);
	var formData = new FormData(document.getElementById('formulario'));

	formData.append('tipo_servicio', tipo_servicio);
	formData.append('id_proveedor', id_proveedor);
	formData.append('id_tipo_servicio', id_tipo_servicio);

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	$.ajax({
		url: 'components/arriendo/models/buscar_servicio_editar.php',
		dataType: 'html',
		type: 'POST',
		data: formData,
		//data: {tipo_servicio: tipo_servicio, id_proveedor: id_proveedor , id_tipo_servicio: id_tipo_servicio},
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			//console.log("res");
			//console.log(res);
			var retorno = res.split(',xxx,');
			var resultado = retorno[1];
			var mensaje = retorno[2];
			var tipo = retorno[3];
			var mensaje_proveedor = retorno[4];

			if (resultado == 'OK' && tipo_servicio == 'basico') {
				//$("#TipoProveedorSeguro").attr("disabled", false);
				document.getElementById('TipoEditarServicio').innerHTML = mensaje;
				//$("#TipoProveedorSeguro").attr("disabled", false);
				document.getElementById('TipoProveedorEditar').innerHTML =
					mensaje_proveedor;
			}
			if (resultado == 'OK' && tipo_servicio == 'seguro') {
				//$("#TipoProveedorSeguro").attr("disabled", false);
				document.getElementById('TipoEditarSeguro').innerHTML = mensaje;
				//$("#TipoProveedorSeguro").attr("disabled", false);
				document.getElementById('TipoProveedorEditarSeguro').innerHTML =
					mensaje_proveedor;
			}
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			Swal.fire({
				title: 'Atención',
				text: 'ah surgido un problema - Por favor contactar a soporte',
				icon: 'warning',
			});
		});

	// $("#cheque_formulario_editar")[0].reset();
	//$("#modalEditarServicio").modal("hide");
	//cargarServicios();
}

function editarServicio() {
	var formData = new FormData(document.getElementById('formulario'));

	const TipoEditarServicio_input =
		document.getElementById('TipoEditarServicio');
	var TipoEditarServicio = TipoEditarServicio_input.value;

	const TipoProveedorEditar_input = document.getElementById(
		'TipoProveedorEditar'
	);
	var TipoProveedorEditar = TipoProveedorEditar_input.value;

	const montoEditar_input = document.getElementById('montoEditar');
	var montoEditar = montoEditar_input.value;

	const PlanEditar_input = document.getElementById('PlanEditar');
	var PlanEditar = PlanEditar_input.value;

	const monedaEditar_input = document.getElementById('monedaEditar');
	var monedaEditar = monedaEditar_input.value;

	const periocidadEditar_input = document.getElementById('periocidadEditar');
	var periocidadEditar = periocidadEditar_input.value;

	const servicioFechaInicioEditar_input = document.getElementById(
		'servicioFechaInicioEditar'
	);
	var servicioFechaInicioEditar = servicioFechaInicioEditar_input.value;

	const servicioFechaVencimientoEditar_input = document.getElementById(
		'servicioFechaVencimientoEditar'
	);
	var servicioFechaVencimientoEditar =
		servicioFechaVencimientoEditar_input.value;

	const ServicioTokenEditar_input = document.getElementById(
		'ServicioTokenEditar'
	);
	var ServicioTokenEditar = ServicioTokenEditar_input.value;

	const numeroClienteEditar_input = document.getElementById(
		'numeroClienteEditar'
	);
	var numeroClienteEditar = numeroClienteEditar_input.value;

	formData.append('TipoEditarServicio', TipoEditarServicio);
	formData.append('TipoProveedorEditar', TipoProveedorEditar);
	formData.append('montoEditar', montoEditar);
	formData.append('PlanEditar', PlanEditar);
	formData.append('monedaEditar', monedaEditar);
	formData.append('periocidadEditar', periocidadEditar);
	formData.append('servicioFechaInicioEditar', servicioFechaInicioEditar);
	formData.append(
		'servicioFechaVencimientoEditar',
		servicioFechaVencimientoEditar
	);
	formData.append('ServicioTokenEditar', ServicioTokenEditar);
	formData.append('numeroClienteEditar', numeroClienteEditar);

	$.ajax({
		url: 'components/arriendo/models/editar_servicio.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			// console.log(res);
			Swal.fire({
				title: 'Servicio basico actualizado correctamente',
				text: '',
				icon: 'success',
			});
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			Swal.fire({
				title: 'Atención',
				text: 'El registro no se actualizo',
				icon: 'warning',
			});
		});

	// $("#cheque_formulario_editar")[0].reset();
	$('#modalEditarServicio').modal('hide');
	cargarServicios();
}

function editarSeguro() {
	var formData = new FormData(document.getElementById('formulario'));

	const TipoEditarServicio_input = document.getElementById('TipoEditarSeguro');
	var TipoEditarServicio = TipoEditarServicio_input.value;

	const TipoProveedorEditar_input = document.getElementById(
		'TipoProveedorEditarSeguro'
	);
	var TipoProveedorEditar = TipoProveedorEditar_input.value;

	const montoEditar_input = document.getElementById('montoEditarSeguro');
	var montoEditar = montoEditar_input.value;

	const PlanEditar_input = document.getElementById('PlanEditarSeguro');
	var PlanEditar = PlanEditar_input.value;

	const monedaEditar_input = document.getElementById('monedaEditarSeguro');
	var monedaEditar = monedaEditar_input.value;

	const periocidadEditar_input = document.getElementById(
		'periocidadEditarSeguro'
	);
	var periocidadEditar = periocidadEditar_input.value;

	const servicioFechaInicioEditar_input = document.getElementById(
		'seguroFechaInicioEditar'
	);
	var servicioFechaInicioEditar = servicioFechaInicioEditar_input.value;

	const servicioFechaVencimientoEditar_input = document.getElementById(
		'seguroFechaVencimientoEditar'
	);
	var servicioFechaVencimientoEditar =
		servicioFechaVencimientoEditar_input.value;

	const ServicioTokenEditar_input =
		document.getElementById('SeguroTokenEditar');
	var ServicioTokenEditar = ServicioTokenEditar_input.value;

	formData.append('TipoEditarServicio', TipoEditarServicio);
	formData.append('TipoProveedorEditar', TipoProveedorEditar);
	formData.append('montoEditar', montoEditar);
	formData.append('PlanEditar', PlanEditar);
	formData.append('monedaEditar', monedaEditar);
	formData.append('periocidadEditar', periocidadEditar);
	formData.append('servicioFechaInicioEditar', servicioFechaInicioEditar);
	formData.append(
		'servicioFechaVencimientoEditar',
		servicioFechaVencimientoEditar
	);
	formData.append('ServicioTokenEditar', ServicioTokenEditar);

	$.ajax({
		url: 'components/arriendo/models/editar_seguro.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			//console.log(res);
			Swal.fire({
				title: 'Servicio basico actualizado correctamente',
				text: '',
				icon: 'success',
			});
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			Swal.fire({
				title: 'Atención',
				text: 'El registro no se actualizo',
				icon: 'warning',
			});
		});

	// $("#cheque_formulario_editar")[0].reset();
	$('#modalEditarSeguro').modal('hide');
	cargarSeguros();
}

/********************************* Pestaña  Historial   *************************************/
function cargarHistorialArriendo() {
	// Realizar la solicitud AJAX para obtener los datos
	var idFicha = $('#id_ficha').val();
	$.ajax({
		url: 'components/arriendo/models/listado_historial.php',
		type: 'POST',
		dataType: 'json',
		data: { idFicha: idFicha },
		cache: false,
		success: function (data) {
			//console.log(data);
			if (data != null) {
				var tbody = $('#Historial tbody');
				// Limpiar el cuerpo de la tabla por si hay datos anteriores
				tbody.empty();
				// Iterar sobre los datos y agregar filas a la tabla
				$.each(data, function (index, item) {
					// Crear una nueva fila de la tabla
					var newRow = $('<tr>');

					// Agregar celdas a la fila con los datos
					newRow.append(
						'<td>' + moment(item.fecha).format('DD-MM-YYYY') + '</td>'
					);

					newRow.append('<td>' + item.responsable + '</td>');
					newRow.append('<td> ' + item.accion + '</td>');
					newRow.append('<td>  ' + item.item + '</td>');
					newRow.append('<td> ' + item.id_item + '</td>');
					newRow.append('<td>' + item.descripcion + '</td>');

					// Agregar la fila al cuerpo de la tabla
					tbody.append(newRow);
				});
			} else {
				var tbody = $('#Historial tbody');
				tbody.empty();
				var newRow = $('<tr>');
				//console.log("error");
				newRow.append(
					"<td colspan='9' style='text-align:center'> No hay Historial</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			//console.log("error");
		},
	});
}

function guardarInfoComentario() {
	var formData = new FormData(document.getElementById('comentario_formulario'));
	//console.log("Entrando comentario");

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
		url: 'components/arriendo/models/insert_comentario.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
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
		url: 'components/arriendo/models/listado_info_comentarios.php',
		type: 'POST',
		dataType: 'json',
		data: { idFicha: idFicha },
		cache: false,
		success: function (data) {
			// console.log("DATA: ", data);
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
				// console.log("error");
				newRow.append(
					"<td colspan='9' style='text-align:center'> No hay Comentarios</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			// console.log("error", jqXHR,textStatus, errorThrown );
		},
	});
}

function cargarInfoComentarioEditar(idInfoComentario, comentario) {
	// console.log("PARAMETROS ENTRADA: ", {idInfoComentario, comentario});
	//console.log("ESTOY EN cargarInfoComentarioEditar");
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

	//console.log("objeto_json editar info comentario: ",objeto_json );

	var id_comentario = objeto_json.ID_Info_Comentario_Editar;
	var url = window.location.href;
	//console.log(url);
	var id_ficha = $('#id_ficha').val();
	var parametros = new URL(url).searchParams;

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/arriendo/models/editar_info_comentario.php',
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
	//console.log("idInfoComentario: ", idInfoComentario);

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
				url: 'components/arriendo/models/delete_info_comentario.php',
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

/*Metodos de listado Arrendatarios (Ficha tecnica) */
function cargarInfoArrendatario() {
	// Realizar la solicitud AJAX para obtener los datos
	var idFicha = $('#id_ficha').val();
	$.ajax({
		url: 'components/arriendo/models/listado_info_arrendatarios.php',
		type: 'POST',
		dataType: 'json',
		data: { idFicha: idFicha },
		cache: false,
		success: function (data) {
			// console.log("DATA: ", data);
			if (data != null) {
				var tbody = $('#info-arrendatarios tbody');
				// Limpiar el cuerpo de la tabla por si hay datos anteriores
				tbody.empty();
				// Iterar sobre los datos y agregar filas a la tabla
				$.each(data, function (index, item) {
					// Crear una nueva fila de la tabla
					var newRow = $('<tr>');
					// Agregar celdas a la fila con los datos
					newRow.append(
						'<td><a href="index.php?component=persona&view=persona&token=' +
						item.token_persona +
						'" target="_blank">' +
						formateoNulos(item.dni) +
						'</a></td>'
					);

					newRow.append(
						'<td>' + formateoNulos(item.nombre_arrendatario) + '</td>'
					);
					newRow.append(
						'<td>' + formateoNulos(item.correo_electronico) + '</td>'
					);
					newRow.append('<td>' + formateoNulos(item.telefono_movil) + '</td>');
					newRow.append('<td>' + formateoNulos(item.telefono_fijo) + '</td>');

					// Agregar la fila al cuerpo de la tabla
					tbody.append(newRow);
				});
			} else {
				var tbody = $('#info-arrendatarios tbody');
				tbody.empty();
				var newRow = $('<tr>');
				// console.log("error");
				newRow.append(
					"<td colspan='9' style='text-align:center'> No hay Arrendatarios</td>"
				);
				tbody.append(newRow);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			// console.log("error");
		},
	});
}
/*Metodo para precarga de listas (Ficha tecnica) */
function cargarInfoArriendo() {
	cargarInfoArrendatario();
	cargarInfoComentario();
}

function formateoNulos(text) {
	return !text || text === '' ? '-' : text;
}

/*Metodo para precarga de listas (Ficha tecnica) */
function cargarInfoArriendo() {
	cargarInfoArrendatario();
	cargarInfoComentario();
}

function formateoNulos(text) {
	return !text || text === '' ? '-' : text;
}

function cargarInfoCuentaCorriente() {
	cargarCCMovimientos();
	cargarCCMovimientoSaldoActual();
}

//FLAG
// Inicialización global de la DataTable
var ccMovimientosTable;

function cargarCCMovimientos() {
	var idFicha = $('#id_ficha').val();

	// Si la tabla ya está inicializada, solo recarga los datos
	if ($.fn.DataTable.isDataTable('#cc-movimientos')) {
		ccMovimientosTable.ajax
			.url(
				'components/arriendo/models/listado_cc_movimientos.php?idFicha=' +
				idFicha
			)
			.load();
		return;
	}

	// Inicializa la DataTable con datos AJAX
	ccMovimientosTable = $('#cc-movimientos').DataTable({
		ajax: {
			url: 'components/arriendo/models/listado_cc_movimientos.php',
			type: 'POST',
			data: { idFicha: idFicha },
			dataSrc: function (response) {
				if (response && response[0]?.fn_saldos_arrendatario.length > 0) {
					return response[0].fn_saldos_arrendatario;
				}
				return [];
			},
		},
		columns: [
			{
				data: null,
				render: function (data) {
					return moment(
						data.fecha_movimiento + ' ' + data.hora_movimiento,
						'DD-MM-YYYY HH:mm:ss'
					).format('DD-MM-YYYY HH:mm');
				},
			},
			{ data: 'razon' },
			{
				data: 'monto1',
				render: function (data) {
					return (
						"<span class='text-primary d-flex justify-content-end'>" +
						formateoNulos(formateoDivisa(data)) +
						'</span>'
					);
				},
			},
			{
				data: 'monto2',
				render: function (data) {
					return (
						"<span class='text-danger d-flex justify-content-end'>" +
						formateoNulos(formateoDivisa(data)) +
						'</span>'
					);
				},
			},
			{
				data: 'saldo',
				render: function (data) {
					if (data >= 0) {
						return (
							"<strong class='text-primary d-flex justify-content-end'>" +
							formateoNulos(formateoDivisa(data)) +
							'</strong>'
						);
					} else {
						return (
							"<strong class='text-danger d-flex justify-content-end'>" +
							formateoNulos(formateoDivisa(data)) +
							'</strong>'
						);
					}
				},
			},
		],
		order: [], // Desactiva el orden automático
		destroy: true, // Permite reinicializar la tabla si es necesario
		dom: 'Bfrtip', // Botones para exportar
		buttons: [
			{
				extend: 'excelHtml5',
				text: 'Descargar Excel',
				titleAttr: 'Exportar a Excel',
				className: 'btn btn-success',
			},
		],
		language: {
			emptyTable: 'No hay Movimientos',
		},
	});
}

function formateoDivisa(valor) {
	// Convertir el valor a un número flotante para asegurar el formato
	var numero = parseFloat(valor);

	// Verificar si el número es válido
	if (isNaN(numero)) {
		return '$ 0';
	}

	// Formatear el número con separador de miles y dos decimales
	return (
		'$ ' +
		numero.toLocaleString('es-CL', {
			minimumFractionDigits: 0, // Esto elimina los decimales
			maximumFractionDigits: 0, // Esto elimina los decimales
		})
	);
}

function cargarCCMovimientoSaldoActual() {
	// Realizar la solicitud AJAX para obtener los datos
	var idFicha = $('#id_ficha').val();
	$.ajax({
		url: 'components/arriendo/models/listado_cc_movimientos.php',
		type: 'POST',
		dataType: 'json',
		data: { idFicha: idFicha },
		cache: false,
		success: function (data) {
			// console.log("DATA: ", data);
			//let ccmovSaldoDia = document.getElementById("ccMovsaldoAlDia");

			if (data != null) {
				// Select the span element by its id

				//   console.log("MYSPAN: ", ccmovSaldoDia);
				let saldoAlDia = '';

				// Set the text content of the span element
				if (data[0]?.saldo >= 0) {
					saldoAlDia =
						"<strong class='text-primary'>" +
						formateoNulos(formateoDivisa(data[0]?.saldo)) +
						'</strong>';
				} else {
					saldoAlDia =
						"<strong class='text-danger'>" +
						formateoNulos(formateoDivisa(data[0]?.saldo)) +
						'</strong>';
				}

				//ccmovSaldoDia.innerHTML = saldoAlDia;
			} else {
				// Set the text content of the span element
				//ccmovSaldoDia.textContent = "-";
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// Manejar errores si es necesario
			// console.log("error", jqXHR,textStatus, errorThrown );
		},
	});
}

//FLAG
function guardarCcCargo() {
	var formData = new FormData(document.getElementById('cc_cargo'));
	var jsonInformacionNueva = obtenerValoresFormulario('cc_cargo');
	var id_ficha = $('#id_ficha').val();


	const ccTipoMovimientoCargo = document.getElementById(
		'ccTipoMovimientoCargo'
	);
	var ccTipoMovimientosCargo = ccTipoMovimientoCargo.value;

	const cc_pago_razon_input = document.getElementById('ccIngresoPagoRazon');
	var ccIngresoPagoRazon = cc_pago_razon_input.value;

	const cc_pago_monto_input = document.getElementById('ccIngresoPagoMonto');
	var ccIngresoPagoMonto = cc_pago_monto_input.value;

	const cc_pago_moneda_input = document.getElementById('ccIngresoPagoMoneda');
	var ccIngresoPagoMoneda = cc_pago_moneda_input.value;

	const cc_pago_fecha_input = document.getElementById('ccIngresoPagoFecha');
	var ccIngresoPagoFecha = cc_pago_fecha_input.value;

	var tipo_movimiento = $('#ccTipoMovimientoCargo').val();

	var ccIdresponsable = $('#idResponsableCargo').val();
	var cccta_contable = $('#cccta_contableCargo').val();


	if (tipo_movimiento != 4) {
		if (ccIngresoPagoRazon == null || ccIngresoPagoRazon == '') {
			Swal.fire({
				title: 'Atención ',
				text: 'Debe agregar una razón',
				icon: 'warning',
			});
			return;
		}

		if (ccIngresoPagoMonto == null || ccIngresoPagoMonto == '') {
			Swal.fire({
				title: 'Atención ',
				text: 'Debe agregar un monto',
				icon: 'warning',
			});
			return;
		}

		if (ccIngresoPagoMoneda == null || ccIngresoPagoMoneda == '') {
			Swal.fire({
				title: 'Atención ',
				text: 'Debe agregar una Moneda',
				icon: 'warning',
			});
			return;
		}

		if (ccIngresoPagoFecha == null || ccIngresoPagoFecha == '') {
			Swal.fire({
				title: 'Atención ',
				text: 'Debe agregar fecha de pago',
				icon: 'warning',
			});
			return;
		}

		formData.append('ccTipoMovimientoCargo', ccTipoMovimientosCargo);
		formData.append('ccIngresoPagoRazon', ccIngresoPagoRazon);
		formData.append('ccIngresoPagoMonto', ccIngresoPagoMonto);
		formData.append('ccIngresoPagoMoneda', ccIngresoPagoMoneda);
		formData.append('ccIngresoPagoFecha', ccIngresoPagoFecha);
		
		formData.append('ccIdresponsable', ccIdresponsable);
		formData.append('cccta_contable', cccta_contable);

		formData.append('id_ficha', id_ficha);


	}

	var url = window.location.href;
	var parametros = new URL(url).searchParams;
	formData.append('token', parametros.get('token'));

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/arriendo/models/insert_cc_pago.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			$('#modalCuentaCorrienteIngresoCargos').modal('hide');
			$('#cc_cargo')[0].reset();
			Swal.fire(
				'Pago registrado',
				'El pago se registró correctamente',
				'success'
			);
			ccMovimientosTable.ajax.reload(); // Recargar la tabla
		})
		.fail(function () {
			Swal.fire('Atención', 'El pago no se registró', 'warning');
		});
}

function GuardarAbonos() {
	var formData = new FormData(document.getElementById('cc_pago_no_liquidable'));
	var jsonInformacionNueva = obtenerValoresFormulario('cc_pago_no_liquidable');

	const ccTipoMovimientoAbonoInput = document.getElementById(
		'ccTipoMovimientoAbono'
	);
	var ccTipoMovimientoAbono = ccTipoMovimientoAbonoInput.value;

	const cc_pago_razon_input = document.getElementById(
		'ccIngresoPagoRazonAbono'
	);
	var ccIngresoPagoRazonAbono = cc_pago_razon_input.value;

	const cc_pago_monto_input = document.getElementById(
		'ccIngresoPagoMontoAbono'
	);
	var ccIngresoPagoMontoAbono = cc_pago_monto_input.value;

	const cc_pago_moneda_input = document.getElementById(
		'ccIngresoPagoMonedaAbono'
	);
	var ccIngresoPagoMonedaAbono = cc_pago_moneda_input.value;

	const cc_pago_fecha_input = document.getElementById(
		'ccIngresoPagoFechaAbono'
	);
	var ccIngresoPagoFechaAbono = cc_pago_fecha_input.value;

	var tipo_movimiento = $('#ccTipoMovimientoAbono').val();

	if (tipo_movimiento != 1) {
		if (ccIngresoPagoRazonAbono == null || ccIngresoPagoRazonAbono == '') {
			Swal.fire({
				title: 'Atención ',
				text: 'Debe agregar una razón',
				icon: 'warning',
			});
			return;
		}

		if (ccIngresoPagoMonedaAbono == null || ccIngresoPagoMonedaAbono == '') {
			Swal.fire({
				title: 'Atención ',
				text: 'Debe agregar una Moneda',
				icon: 'warning',
			});
			return;
		}

		if (ccIngresoPagoFechaAbono == null || ccIngresoPagoFechaAbono == '') {
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
				text: 'Debe seleccionar un tipo de movimiento',
				icon: 'warning',
			});
			return;
		}
	}

	if (ccIngresoPagoMontoAbono == null || ccIngresoPagoMontoAbono == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar un monto',
			icon: 'warning',
		});
		return;
	}

	var ccIdresponsable = $('#idResponsableAbono').val(); 
	var cccta_contable = $('#cccta_contable').val();

	formData.append('ccTipoMovimientoAbono', ccTipoMovimientoAbono);
	formData.append('ccIngresoPagoNLRazon', ccIngresoPagoRazonAbono);
	formData.append('ccIngresoPagoNLMonto', ccIngresoPagoMontoAbono);
	formData.append('ccIngresoPagoNLMoneda', ccIngresoPagoMonedaAbono);
	formData.append('ccIngresoPagoNLFecha', ccIngresoPagoFechaAbono);

	formData.append('ccIdresponsable', ccIdresponsable);
	formData.append('cccta_contable', cccta_contable);


	var id_ficha = $('#id_ficha').val();
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	//console.log(parametros.get("token"));
	formData.append('token', parametros.get('token'));

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/arriendo/models/insert_cc_abono.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			$('#modalCuentaCorrienteIngresoAbonos').modal('hide');
			$('#cc_abono')[0].reset();

			Swal.fire({
				title: ' registrado',
				text: 'El abono se registro correctamente',
				icon: 'success',
			});
			var id_comentario = res;
			var jsonInformacioantigua = capturarInformacionAntigua();

			cargarCCMovimientoSaldoActual();
			cargarCCMovimientos();
			registroHistorial(
				'Crear',
				'',
				jsonInformacionNueva,
				'Cuenta Corriente - Abono',
				id_ficha,
				id_comentario
			);
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			$('#modalCuentaCorrienteIngresoAbonos').modal('hide');

			Swal.fire({
				title: 'Atención',
				text: 'El abono no se registró',
				icon: 'warning',
			});
		});
	$('#cc_abono')[0].reset();
	$('#modalCuentaCorrienteIngresoAbonos').modal('hide');
	cargarCCMovimientoSaldoActual();
	cargarCCMovimientos();
}

function guardarCcDescuentoAutorizado() {
	var formData = new FormData(
		document.getElementById('cc_descuento_autorizado')
	);

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

	const cc_pago_fecha_input = document.getElementById(
		'ccIngresoDescAutorizadoFecha'
	);
	var ccIngresoDescAutorizadoFecha = cc_pago_fecha_input.value;

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

	var id_ficha = $('#id_ficha').val();
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	//console.log(parametros.get("token"));
	formData.append('token', parametros.get('token'));

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/arriendo/models/insert_cc_descuento_autorizado.php',
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
				title: 'Descuento Autorizado registrado',
				text: 'El descuento autorizado se registro correctamente',
				icon: 'success',
			});
			var id_comentario = res;
			var jsonInformacioantigua = capturarInformacionAntigua();

			cargarCCMovimientoSaldoActual();
			cargarCCMovimientos();
			registroHistorial(
				'Crear',
				'',
				jsonInformacionNueva,
				'Cuenta Corriente - Descuento Autorizado',
				id_ficha,
				id_comentario
			);
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			$('#modalCuentaCorrienteIngresoDescuentoAutorizado').modal('hide');

			Swal.fire({
				title: 'Atención',
				text: 'El descuento autorizado no se registró',
				icon: 'warning',
			});
		});
	$('#cc_descuento_autorizado')[0].reset();
	$('#modalCuentaCorrienteIngresoDescuentoAutorizado').modal('hide');
	cargarCCMovimientoSaldoActual();
	cargarCCMovimientos();
}

function guardarCcCobroExtra() {
	var formData = new FormData(document.getElementById('cc_cobro_extra'));
	var jsonInformacionNueva = obtenerValoresFormulario('cc_cobro_extra');

	const cc_cobro_razon_input = document.getElementById(
		'ccIngresoCobroExtraRazon'
	);
	var ccIngresoCobroExtraRazon = cc_cobro_razon_input.value;

	const cc_cobro_monto_input = document.getElementById(
		'ccIngresoCobroExtraMonto'
	);
	var ccIngresoCobroExtraMonto = cc_cobro_monto_input.value;

	const cc_cobro_moneda_input = document.getElementById(
		'ccIngresoCobroExtraMoneda'
	);
	var ccIngresoCobroExtraMoneda = cc_cobro_moneda_input.value;

	const cc_cobro_cuotas_input = document.getElementById(
		'ccIngresoCobroExtraCuotas'
	);
	var ccIngresoCobroExtraCuotas = cc_cobro_cuotas_input.value;

	const cc_cobro_fecha_input = document.getElementById(
		'ccIngresoCobroExtraFecha'
	);
	var ccIngresoCobroExtraFecha = cc_cobro_fecha_input.value;

	if (ccIngresoCobroExtraRazon == null || ccIngresoCobroExtraRazon == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar una razón',
			icon: 'warning',
		});
		return;
	}

	if (ccIngresoCobroExtraMonto == null || ccIngresoCobroExtraMonto == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar un monto',
			icon: 'warning',
		});
		return;
	}

	if (ccIngresoCobroExtraMoneda == null || ccIngresoCobroExtraMoneda == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar una Moneda',
			icon: 'warning',
		});
		return;
	}

	if (ccIngresoCobroExtraCuotas == null || ccIngresoCobroExtraCuotas == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe seleccionar cuotas',
			icon: 'warning',
		});
		return;
	}

	if (ccIngresoCobroExtraFecha == null || ccIngresoCobroExtraFecha == '') {
		Swal.fire({
			title: 'Atención ',
			text: 'Debe agregar fecha de pago',
			icon: 'warning',
		});
		return;
	}

	formData.append('ccIngresoCobroExtraRazon', ccIngresoCobroExtraRazon);
	formData.append('ccIngresoCobroExtraMonto', ccIngresoCobroExtraMonto);
	formData.append('ccIngresoCobroExtraMoneda', ccIngresoCobroExtraMoneda);
	formData.append('ccIngresoCobroExtraCuotas', ccIngresoCobroExtraCuotas);
	formData.append('ccIngresoCobroExtraFecha', ccIngresoCobroExtraFecha);

	var id_ficha = $('#id_ficha').val();
	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;
	//console.log(parametros.get("token"));
	formData.append('token', parametros.get('token'));

	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$.ajax({
		url: 'components/arriendo/models/insert_cc_cobro_extra.php',
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			$('#modalCuentaCorrienteIngresoCobroExtra').modal('hide');
			$('#cc_cobro_extra')[0].reset();

			Swal.fire({
				title: 'Cobro Extra registrado',
				text: 'El cobro extra se registro correctamente',
				icon: 'success',
			});
			var id_comentario = res;
			var jsonInformacioantigua = capturarInformacionAntigua();

			cargarCCMovimientoSaldoActual();
			cargarCCMovimientos();
			registroHistorial(
				'Crear',
				'',
				jsonInformacionNueva,
				'Cuenta Corriente - Cobro Extra',
				id_ficha,
				id_comentario
			);
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			$('#modalCuentaCorrienteIngresoCobroExtra').modal('hide');

			Swal.fire({
				title: 'Atención',
				text: 'El cobro extra no se registró',
				icon: 'warning',
			});
		});
	$('#cc_cobro_extra')[0].reset();
	$('#modalCuentaCorrienteIngresoCobroExtra').modal('hide');
	cargarCCMovimientoSaldoActual();
	cargarCCMovimientos();
}

// function guardarCR() {

//   var formData = new FormData(document.getElementById("cc_cobro_extra"));
//   var jsonInformacionNueva = obtenerValoresFormulario("cc_cobro_extra");

//   const crRazon = document.getElementById("crRazon");
//   var razon = crRazon.value;

//   const crMonto = document.getElementById("crMonto");
//   var monto = crMonto.value;

//   const crMoneda = document.getElementById("crMoneda");
//   var moneda = crMoneda.value;

//   const crFecha = document.getElementById("crImputarMes");
//   var imputarmes = crFecha.value;

//   const crDocumento = document.getElementById("ccFecha");
//   var fecha = crDocumento.value;

//   formData.append('razon', razon);
//   formData.append('monto', monto);
//   formData.append('moneda', moneda);
//   formData.append('imputarmes', imputarmes);
//   formData.append('fecha', fecha);

//   var id_ficha = $("#id_ficha").val();
//   var url = window.location.href;
//   var parametros = new URL(url).searchParams;
//   formData.append("token", parametros.get("token"));

//   $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//   $.ajax({
//       url: "components/arriendo/models/insert_ctr.php",
//       type: "post",
//       dataType: "text",
//       data: formData,
//       cache: false,
//       contentType: false,
//       processData: false,
//   })
//   .done(function (res) {
//       $("#modalCuentaCorrienteIngresoCobroExtra").modal("hide");
//       $("#cc_cobro_extra")[0].reset();

//       Swal.fire({
//           title: "Cobro Extra registrado",
//           text: "El cobro extra se registró correctamente",
//           icon: "success",
//       });
//       var id_comentario = res;
//       var jsonInformacioantigua = capturarInformacionAntigua();

//       cargarCCMovimientoSaldoActual()
//       cargarCCMovimientos();
//       registroHistorial(
//           "Crear",
//           "",
//           jsonInformacionNueva,
//           "Cuenta Corriente - Cobro Extra",
//           id_ficha,
//           id_comentario
//       );
//   })
//   .fail(function (jqXHR, textStatus, errorThrown) {
//       $("#modalCuentaCorrienteIngresoCobroExtra").modal("hide");

//       Swal.fire({
//           title: "Atención",
//           text: "El cobro extra no se registró",
//           icon: "warning",
//       });
//   });
//   $("#cc_cobro_extra")[0].reset();
//   $("#modalCuentaCorrienteIngresoCobroExtra").modal("hide");
//   cargarCCMovimientoSaldoActual()
//   cargarCCMovimientos();

// }

/*
 *
 *
 * jose trabajo aqui
 *
 *
 */

function validarFormulario() {
	let form = document.getElementById('cr_cobro');
	let razon = document.getElementById('crRazon').value.trim();
	let monto = document.getElementById('crMonto').value.trim();
	let anio = document.getElementById('crAnio').value.trim();
	let mes = document.getElementById('crMes').value;
	let fecha = document.getElementById('ccFecha').value;

	if (!razon || !monto || !anio || !mes || !fecha) {
		Swal.fire({
			title: 'Error',
			text: 'Todos los campos son obligatorios.',
			icon: 'error',
		});
		return false;
	}

	if (!/^[0-9]+$/.test(monto)) {
		Swal.fire({
			title: 'Error',
			text: 'El monto debe ser un número válido.',
			icon: 'error',
		});
		return false;
	}

	if (!/^[0-9]+$/.test(anio)) {
		Swal.fire({
			title: 'Error',
			text: 'El año debe ser un número válido.',
			icon: 'error',
		});
		return false;
	}

	return true;
}

function guardarCR() {
	if (!validarFormulario()) {
		return;
	}

	let form = document.getElementById('cr_cobro');
	let formData = new FormData(form);

	fetch('components/arriendo/models/insert_ctr.php', {
		method: 'POST',
		body: formData,
	})
		.then((response) => response.text())
		.then((data) => {
			cargarCCMovimientoSaldoActual();
			cargarCCMovimientos();

			// Restablecer el formulario
			form.reset();

			Swal.fire({
				title: 'Cargo agregado con exito',
				text: 'los datos fueron guardados correctamente',
				icon: 'success',
			});

			// Cerrar modal
			$('#modalCTR').modal('hide');
		})
		.catch((error) => console.error('Error:', error));
}

function EditarrCR() {
	// if (!validarFormulario()) {
	//   return;
	// }

	let form = document.getElementById('cr_cobroEditar');
	let formData = new FormData(form);

	fetch('components/arriendo/models/actualizar_cargo_a_renta.php', {
		method: 'POST',
		body: formData,
	})
		.then((response) => response.text())
		.then((data) => {
			cargarCCMovimientoSaldoActual();
			cargarCCMovimientos();

			// Restablecer el formulario
			form.reset();

			Swal.fire({
				title: 'Cargo agregado con exito',
				text: 'los datos fueron guardados correctamente',
				icon: 'success',
			});

			// Cerrar modal
			$('#modalCTREditar').modal('hide');
		})
		.catch((error) => console.error('Error:', error));
}

function ValidarArchivoImagen(e, peso) {
	// $.showAlert({ title: "Atención", body: "El Archivo debe ser una imagen, word, excel o pdf." });
	var fileExtension = ['jpeg', 'jpg', 'png', 'doc', 'docx', 'pdf'];
	var file = e.files[0];
	var maxSizeBytes = peso_archivo * 1024 * 1024;

	if (
		$.inArray($(e).val().split('.').pop().toLowerCase(), fileExtension) == -1
	) {
		Swal.fire({
			title: 'tipo archivo no permitido',
			text: 'El Archivo debe ser una imagen, word o pdf.',
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

	return true;
}

function confirmarEliminacion(id) {
	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Esta acción no se puede deshacer',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Sí, eliminar',
		cancelButtonText: 'Cancelar',
	}).then((result) => {
		if (result.isConfirmed) {
			eliminarCCMovimiento(id);
		}
	});
}

function eliminarCCMovimiento(id) {
	fetch('components/arriendo/models/eliminar_cr.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ id: id }),
	})
		.then((response) => {
			if (response.ok) {
				cargarCCMovimientoSaldoActual();
				cargarCCMovimientos();
			} else {
				console.error('Error al eliminar el elemento');
			}
		})
		.catch((error) => {
			console.error('Error en la solicitud AJAX:', error);
		});
}

function cargarInfoComentarioEditar(id, razon, monto, anio, mes, fecha) {
	$('#crRazonEditar').val(razon);
	$('#crMontoEditar').val(monto);
	$('#crAnioEditar').val(parseInt(mes.toString()));
	$('#crMesEditar').val(anio).change();
	var formattedDate = moment(fecha, 'DD-MM-YYYY h:mm').format('YYYY-MM-DD');
	$('#ccFechaEditar').val(formattedDate);
	$('#cobroEditar').val(id);
}

/*
 *
 *
 * jose trabajo aqui
 *
 *
 */

function cargaDocumentoCuentaCorrientePago() {
	var formData = new FormData(document.getElementById('cc_pago_doc'));

	var url = window.location.href;
	//console.log(url);
	var parametros = new URL(url).searchParams;

	//  const token_propiedad_defecto_input = document.getElementById("token_propiedad_defecto");
	//  var token_propiedad_defecto = token_propiedad_defecto_input.value;

	// console.log("TOKEN CREACION: ",parametros.get('token'));
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
		// console.log("titulo vacio");
		return;
	}

	// console.log("Enviando Documentos");
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	$.ajax({
		url: 'components/arriendo/models/insert_archivo_cc.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		//console.log("res");
		// console.log(res);
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
	//console.log(url);
	var parametros = new URL(url).searchParams;

	//  const token_propiedad_defecto_input = document.getElementById("token_propiedad_defecto");
	//  var token_propiedad_defecto = token_propiedad_defecto_input.value;

	// console.log("TOKEN CREACION: ",parametros.get('token'));
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
		// console.log("titulo vacio");
		return;
	}

	//console.log("Enviando Documentos");
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	$.ajax({
		url: 'components/arriendo/models/insert_archivo_cc_pago_nl.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		// console.log("res");
		// console.log(res);
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

function cargarHistorialArriendoList() {
	var idFicha = $('#id_ficha').val();

	$('#historial-table').DataTable({
		dom: 'B<"clear">lfrtip',
		destroy: true,
		targets: 'no-sort',
		bSort: false,
		order: [[0, 'desc']],
		pagingType: 'full_numbers', // Tipo de paginación
		pageLength: 10, // Número de filas por página
		lengthMenu: [
			[10, 25, 50, 100, 5000],
			[10, 25, 50, 100, 'Todos'],
		],
		// "columnDefs": [ { orderable: false, targets: [9] } ],
		columnDefs: [
			{
				render: (data, type, row) => {
					return formateoNulos(moment(data).format('DD-MM-YYYY HH:mm'));
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
					return formateoNulos(data);
				},
				targets: 5,
			},
		],
		ajax: {
			url:
				'components/arriendo/models/listado_historial_procesa.php?idFicha=' +
				idFicha,
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

	$('#historial-table').on('init.dt', function () {
		//  console.log("DataTables se ha inicializado correctamente en #historial-table");
	});
}

function cargarPagoChequesList() {
	var idFicha = $('#id_ficha').val();

	$('#pago-cheques-table').DataTable({
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
					return formateoNulos(moment(data).format('DD-MM-YYYY'));
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
					return formateoNulos(formateoDivisa(data));
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
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 6,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 7,
			},
			{
				render: (data, type, row) => {
					return formateoNulos(data);
				},
				targets: 8,
			},
			{ visible: false, targets: [8] },
		],
		ajax: {
			url: 'components/arriendo/models/arriendo_pago_cheques_list_procesa.php',
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

	$('#pago-cheques-table').on('init.dt', function () {
		//  console.log("DataTables se ha inicializado correctamente en #pago-cheques-table");
	});
}

$(document).ready(function () {
	function calcularValorCuota() {
		var montoGarantiaStr = $('#montoGarantia').val().replace(/\./g, '');
		var montoGarantia = parseInt(montoGarantiaStr);
		var numCuotas = parseInt($('#num_cuotas_garantia').val());

		if (!isNaN(montoGarantia) && !isNaN(numCuotas) && numCuotas > 0) {
			var valorCuota = Math.round(montoGarantia / numCuotas);
			$('#valor_cuota').text(formatoMoneda(valorCuota));
		} else {
			$('#valor_cuota').text('');
		}
	}
	function formatoMoneda(numero) {
		return '$' + numero.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
	}
	$('#montoGarantia, #num_cuotas_garantia').on(
		'input change',
		calcularValorCuota
	);
});

function resetFormGarantiaIngresoDescuento() {
	$('#formModalGarantiaIngresar')[0].reset();
	$('.error-descuento-garantia').html('');
} //function resetFormGarantiaIngresoDescuento()

//jose hernandez ingreso ganrantia abonos y descuentos

function guardarGarantia(token, tipo) {
	// Función para obtener los datos del formulario
	function obtenerDatos(tipo) {
		return {
			razon: $(`#modalGarantiaRazon${tipo}`).val(),
			monto: $(`#modalMontoGarantia${tipo}`).val(),
			moneda: $(`#modalMonedaGarantia${tipo}`).val(),
			pagado: $(`#modalGarantiaPagado${tipo}`).val(),
			fecha: $(`#modalGarantiaFecha${tipo}`).val(),
			tipo_movimiento: tipo === 'Abono' ? 0 : 1, // 0 para abono, 1 para descuento
		};
	}

	// Obtener datos del formulario correspondiente
	let datos = obtenerDatos(tipo);

	// Validar datos
	let mensaje = validarDatos(datos);

	if (mensaje) {
		$('.error-descuento-garantia').html(
			"<strong><span style='color:#313131;'>Atención: </span></strong>" +
			mensaje
		);
		return; // Salir de la función si hay errores
	}

	// Bloquear UI durante la petición
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	// Crear un objeto FormData desde el formulario
	var formData = new FormData(
		document.getElementById('formModalGarantiaIngresar')
	);
	formData.append('token', token);
	formData.append('tipo_movimiento', datos.tipo_movimiento); // Se asegura que `datos.tipo_movimiento` esté definido aquí

	// Enviar la solicitud AJAX
	$.ajax({
		type: 'POST',
		url: 'components/arriendo/models/insert_update_garantia.php',
		data: formData,
		processData: false,
		contentType: false,
		success: function (resp) {
			console.log(resp);

			if (resp === 'OK') {
				Swal.fire({
					title: 'Éxito',
					text:
						tipo === 'Abono'
							? 'Abono ingresado con éxito.'
							: 'Descuento ingresado con éxito.',
					icon: 'success',
				});
			} else {
				Swal.fire({
					title: 'Error',
					text: 'Error al ingresar la garantía.',
					icon: 'warning',
				});
				$('#modalGarantiaIngresoDescuento').click(function () {
					$('#myModal').hide();
				});
			}
		},
	});
}

// Función para validar los datos
function validarDatos(datos) {
	let mensaje = '';

	if (!datos.razon) {
		mensaje +=
			'<br>- Debes completar la Razón del ' +
			(datos.tipo_movimiento === 0 ? 'abono' : 'descuento') +
			'.';
	}
	if (!datos.monto) {
		mensaje += '<br>- Debes completar el monto.';
	}
	if (!datos.pagado) {
		mensaje += '<br>- Debes seleccionar si está pagado.';
	}
	if (!datos.fecha) {
		mensaje += '<br>- Debes completar la fecha.';
	}

	return mensaje;
}

function ConfiramarFinalizarArriendo(value) {
	var fechaAux = $('#fechaTerminoAux').val();

	if (value == 2) {
		Swal.fire({
			title: '¿Estás seguro de finalizar el arriendo?',
			text: 'Ingresa la fecha de término real',
			icon: 'warning',
			showDenyButton: true,
			confirmButtonText: 'Si',
			denyButtonText: 'No',
			input: 'date',
			inputAttributes: {
				autocapitalize: 'off',
			},
			preConfirm: (date) => {
				if (!date) {
					Swal.showValidationMessage('Debes ingresar una fecha válida');
				} else {
					return date;
				}
			},
		}).then((result) => {
			if (result.isConfirmed) {
				Swal.fire({
					title: 'El contrato esta listo para ser finalizado',
					text: `debes guardar los cambios para finalizar el contrato, haciendo click en guardar al final de la pagina.`,
					icon: 'success',
				});

				$('#fechaTermino').val(result.value);
			} else if (result.isDenied) {
				Swal.fire('El contrato no ha sido finalizado', '', 'info');
				$('#estadoContrato').val(1);
			}
		});
	} else if (value == 1) {
		$('#fechaTermino').val(fechaAux);
	}
}

// validacion para numeros con decimales jhernandez
function ValidarMontoMoneda(tipo) {
	if (tipo == 'administracion') {
		var val = $('#comisionArriendo').val();
		var regex = /^(\d{1,3}(\.\d{3})*(\.\d{1,2})?|\d+%?)$/;

		if (!regex.test(val)) {
			Swal.fire({
				text: `Por favor ingrese un número válido con decimales o un porcentaje.`,
				icon: 'warning',
			});

			$('#comisionArriendo').val(0);
			return;
		}
	} else if (tipo == 'corretaje') {
		var val = $('#comisionAdministracion').val();

		// Validar que el valor ingresado sea un número con decimales o un porcentaje
		var regex = /^(\d{1,3}(\.\d{3})*(\.\d{1,2})?|\d+%?)$/;
		if (!regex.test(val)) {
			Swal.fire({
				text: `Por favor ingrese un número válido con decimales o un porcentaje.`,
				icon: 'warning',
			});

			$('#comisionAdministracion').val(0);
			return;
		}
	} else if (tipo == 'reajuste') {
		var val = $('#CantidadReajuste').val();

		// Validar que el valor ingresado sea un número con decimales o un porcentaje
		var regex = /^(\d{1,3}(\.\d{3})*(\.\d{1,2})?|\d+%?)$/;
		if (!regex.test(val)) {
			Swal.fire({
				text: `Por favor ingrese un número válido con decimales o un porcentaje.`,
				icon: 'warning',
			});

			$('#CantidadReajuste').val(0);
			return;
		}
	} else if (tipo == 'tipomulta') {
		var val = $('#montoMultaAtraso').val();
		var tipoMulta = $('#monedaMulta').val();
		var regex = /^(\d{1,3}(\.\d{3})*(\.\d{1,2})?|\d+%?)$/;

		if (tipoMulta == 3) {
			if (val > 100) {
				Swal.fire({
					text: `Al seleccionar Porcentaje de la comisión de arriendo no debe superar el 100%.`,
					icon: 'warning',
				});

				$('#montoMultaAtraso').val(0);
			} else {
				if (!regex.test(val)) {
					Swal.fire({
						text: `Por favor ingrese un número válido con decimales o un porcentaje.`,
						icon: 'warning',
					});

					$('#montoMultaAtraso').val(0);
					return;
				}
			}
		} else {
			if (!regex.test(val)) {
				Swal.fire({
					text: `Por favor ingrese un número válido con decimales o un porcentaje.`,
					icon: 'warning',
				});

				$('#montoMultaAtraso').val(0);
				return;
			}
		}
	}
}

function VolverAceroArriendo() {
	arriendo = $('#monedaComisionArriendo option:selected').text();

	if (arriendo) {
		$('#comisionArriendo').val(0);
	}
}

function VolverAceroCorretaje() {
	corretaje = $('#monedaComisionAdministracion option:selected').text();

	if (corretaje) {
		$('#comisionAdministracion').val(0);
	}
}

//jhernandez tipo mmulta

function ValidarTipoMulta() {
	tipoMulta = $('#tipoMulta').val();

	const url = new URL(window.location.href);
	const params = new URLSearchParams(url.search);
	const token = params.get('token');

	if (!token) {
		$('#diascobro').val(0);
		$('#montoMultaAtraso').val('');
	}

	if (tipoMulta == 1) {
		$('#diascobro').show();
		$('#titulodiascobro').show();
	} else {
		$('#diascobro').hide();
		$('#titulodiascobro').hide();
	}
}

// validacion montos en modal servicios basicos.
function ValidarMontoServicios() {
	var val = $('#comisionArriendo').val();
	var regex = /^(\d{1,3}(\.\d{3})*(\.\d{1,2})?|\d+%?)$/;

	if (!regex.test(val)) {
		Swal.fire({
			text: `Por favor ingrese un número válido con decimales o un porcentaje.`,
			icon: 'warning',
		});

		$('#comisionArriendo').val(0);
		return;
	}
}

/********************************************************
 *
 * garantias
 *
 *********************************************************/

// jhernandez garantias
function EditarGarantia(id, fecha, razon, monto, pagado, notificado) {
	// Llenar el formulario con los datos
	$('#id_garantia').val(id);
	$('#fechaEditar').val(fecha);
	$('#razonEditar').val(razon);
	$('#montoEditar').val(monto);
	$('#pagadoEditar').val(pagado);
	$('#notificadoEditar').val(notificado);
}

// funcion que envia el formulario a actualizar
function ActualizarGarantia() {
	// Validar que no haya campos en blanco
	var fecha = $('#fechaEditar').val();
	var razon = $('#razonEditar').val();
	var monto = $('#montoEditar').val();
	var pagado = $('#pagadoEditar').val();
	var notificado = $('#notificadoEditar').val();

	if (!fecha || !razon || !monto) {
		Swal.fire({
			title: 'Atención ',
			text: 'Por favor, completa todos los campos antes de continuar.',
			icon: 'warning',
		});

		return; // Detener la ejecución si hay campos en blanco
	}

	var formData = new FormData(
		document.getElementById('formularioEditarGarantia')
	);

	$.ajax({
		type: 'POST',
		url: 'components/arriendo/models/editar_garantia.php',
		data: formData,
		processData: false,
		contentType: false,
		success: function (resp) {
			console.log(resp);
			console.log('trno..');

			if (resp === '1') {
				// Cambiado a "1"
				Swal.fire({
					title: 'Abono actualizado con éxito',
					text: '',
					icon: 'success',
				});
			} else {
				Swal.fire({
					title: 'Error al ingresar el abono',
					text: '',
					icon: 'error', // Cambiado a "error" para reflejar el error
				});
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
			alert('Ocurrió un error al actualizar la garantía. Inténtalo de nuevo.');
		},
	});
}

// Función para guardar Abono
// function GuardarAbonos() {
// 	var formData = new FormData(document.getElementById('cc_pago_no_liquidable'));
// 	var jsonInformacionNueva = obtenerValoresFormulario('cc_pago_no_liquidable');

// 	const ccTipoMovimientoAbonoInput = document.getElementById(
// 		'ccTipoMovimientoAbono'
// 	);
// 	var ccTipoMovimientoAbono = ccTipoMovimientoAbonoInput.value;

// 	const cc_pago_razon_input = document.getElementById(
// 		'ccIngresoPagoRazonAbono'
// 	);
// 	var ccIngresoPagoRazonAbono = cc_pago_razon_input.value;

// 	const cc_pago_monto_input = document.getElementById(
// 		'ccIngresoPagoMontoAbono'
// 	);
// 	var ccIngresoPagoMontoAbono = cc_pago_monto_input.value;

// 	const cc_pago_moneda_input = document.getElementById(
// 		'ccIngresoPagoMonedaAbono'
// 	);
// 	var ccIngresoPagoMonedaAbono = cc_pago_moneda_input.value;

// 	const cc_pago_fecha_input = document.getElementById(
// 		'ccIngresoPagoFechaAbono'
// 	);
// 	var ccIngresoPagoFechaAbono = cc_pago_fecha_input.value;

// 	var ccIdresponsable = $('#ccIdresponsable').val();
// 	var cccta_contable = $('#cccta_contable').val();

// 	if (tipo_movimiento != 1) {
// 		if (ccIngresoPagoRazonAbono == null || ccIngresoPagoRazonAbono == '') {
// 			Swal.fire({
// 				title: 'Atención ',
// 				text: 'Debe agregar una razón',
// 				icon: 'warning',
// 			});
// 			return;
// 		}

// 		if (ccIngresoPagoMonedaAbono == null || ccIngresoPagoMonedaAbono == '') {
// 			Swal.fire({
// 				title: 'Atención ',
// 				text: 'Debe agregar una Moneda',
// 				icon: 'warning',
// 			});
// 			return;
// 		}

// 		if (ccIngresoPagoFechaAbono == null || ccIngresoPagoFechaAbono == '') {
// 			Swal.fire({
// 				title: 'Atención ',
// 				text: 'Debe agregar fecha de pago',
// 				icon: 'warning',
// 			});
// 			return;
// 		}

// 		if (ccTipoMovimientoAbono == null || ccTipoMovimientoAbono == '') {
// 			Swal.fire({
// 				title: 'Atención ',
// 				text: 'Debe seleccionar un tipo de movimiento',
// 				icon: 'warning',
// 			});
// 			return;
// 		}
// 	}

// 	if (ccIngresoPagoMontoAbono == null || ccIngresoPagoMontoAbono == '') {
// 		Swal.fire({
// 			title: 'Atención ',
// 			text: 'Debe agregar un monto',
// 			icon: 'warning',
// 		});
// 		return;
// 	}

// 	alert(ccIdresponsable);
// 	alert(cccta_contable);

// 	formData.append('ccTipoMovimientoAbono', ccTipoMovimientoAbono);
// 	formData.append('ccIngresoPagoNLRazon', ccIngresoPagoRazonAbono);
// 	formData.append('ccIngresoPagoNLMonto', ccIngresoPagoMontoAbono);
// 	formData.append('ccIngresoPagoNLMoneda', ccIngresoPagoMonedaAbono);
// 	formData.append('ccIdresponsable', ccIdresponsable);
// 	formData.append('cccta_contable', cccta_contable);

// 	var id_ficha = $('#id_ficha').val();
// 	var url = window.location.href;
// 	//console.log(url);
// 	var parametros = new URL(url).searchParams;
// 	//console.log(parametros.get("token"));
// 	formData.append('token', parametros.get('token'));

// 	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

// 	$.ajax({
// 		url: 'components/arriendo/models/insert_cc_abono.php',
// 		type: 'post',
// 		dataType: 'text',
// 		data: formData,
// 		cache: false,
// 		contentType: false,
// 		processData: false,
// 	})
// 		.done(function (res) {
// 			$('#modalCuentaCorrienteIngresoAbonos').modal('hide');
// 			$('#cc_abono')[0].reset();
// 			Swal.fire(
// 				'Abono registrado',
// 				'El abono se registró correctamente',
// 				'success'
// 			);
// 			ccMovimientosTable.ajax.reload(); // Recargar la tabla
// 		})
// 		.fail(function () {
// 			Swal.fire('Atención', 'El abono no se registró', 'warning');
// 		});

// }


// Función para guardar Descuento
function guardarDescuento(token) {
	// Captura los datos del formulario
	var formData = new FormData($('#formModalGarantiaDescuento')[0]);
	formData.append('tipo_movimiento', 1); // 1 para descuento
	formData.append('token', token);

	// Envío de datos vía AJAX
	$.ajax({
		url: 'components/arriendo/models/insert_update_garantia_descuento.php',
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function (response) {
			if (response === 'OK') {
				// Aquí puedes cerrar el modal o actualizar la tabla
				Swal.fire({
					title: 'Descuento guardado con exito.',
					text: '',
					icon: 'success',
				});
				$('#modalGarantiaIngresoDescuento').modal('hide');

				// Redirecciona manteniendo el tab activo y refresca la página
				let currentUrl = new URL(window.location.href);
				currentUrl.searchParams.set('activeTab', 'arriendo-ft-garantia');
				window.location.href = currentUrl.href;
				window.location.reload(); // Refresca la página
			} else {
				alert('Error al guardar el descuento: ' + response);
			}
		},
		error: function () {
			alert('Error en la conexión.');
		},
	});
}

// ******************************** bruno *************************************

// Cargar cheques y agregar filas a la tabla
function cargarCheques() {
	$.ajax({
		url: 'components/arriendo/models/listado_cheques.php',
		type: 'POST',
		dataType: 'json',
		data: { idFicha: $('#id_ficha').val() },
		cache: false,
		success: function (data) {
			var tbody = $('#cheques tbody');
			tbody.empty();
			if (data != null) {
				$.each(data, function (index, item) {
					let desposito = item.desposito ? 'checked disabled' : '';
					let cobrar = item.cobrar ? 'checked' : '';
					let disabled = item.desposito ? 'disabled' : ''; // Verificamos aquí si debe estar deshabilitado

					var newRow = $('<tr>');

					// Crear columnas de la tabla con los datos
					newRow.append(
						'<td>' + moment(item.fecha_cobro).format('DD-MM-YYYY') + '</td>'
					);
					newRow.append('<td>' + item.razon + '</td>');
					newRow.append('<td>$' + item.monto.toLocaleString() + '</td>');
					newRow.append('<td>' + item.numero_documento + '</td>');
					newRow.append('<td>' + item.nombre + '</td>');
					newRow.append('<td>' + item.girador + '</td>');

					// Switch "Depósito"
					newRow.append(`
						<td>
							<div class="d-flex">
								<label class="switch">
									<input name="desposito" id="switchDeposito" class="form-check-input switchCheques" type="checkbox" role="switch" 
										${desposito} monto="${item.monto}" data-token="${item.token}" id-propiedad="${item.id_propiedad}">
									<span class="slider round"></span>
									<span class="switchText">${item.desposito ? 'Si' : 'No'}</span>
								</label>
							</div>
						</td>
					`);

					// Switch "Cobrar"
					newRow.append(`
						<td>
							<div class="d-flex">
								<label class="switch">
									<input name="cobrar" id="switchCobrar" class="form-check-input switchCheques" type="checkbox" role="switch" 
										${cobrar} ${disabled} data-token="${item.token}">
									<span class="slider round"></span>
									<span class="switchText">${item.cobrar ? 'Si' : 'No'}</span>
								</label>
							</div>
						</td>
					`);

					// Botones de editar y eliminar
					newRow.append(`
						<td>
							<div class='d-flex align-items-center' style='gap: .5rem;'>
								<a href="#" type="button" class="btn btn-secondary m-0" style="padding: .5rem; ${item.comentario === '' ? 'visibility: hidden;' : ''
						}" 
									aria-label="Info" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="${item.comentario
						}">
									<i class="fa-solid fa-circle-info" style="font-size: .75rem;"></i>
								</a>
								<a data-bs-toggle='modal' onclick='cargarChequesEditar(${item.id
						}, ${item.monto}, "${item.razon}", ${item.banco}, 
									"${moment(item.fecha_cobro).format(
							'YYYY-MM-DD'
						)}", "${item.girador}", ${item.numero_documento}, ${item.cantidad}, 
									"${item.comentario}")' data-bs-target='#modalChequesEditar' type='button' 
									class='btn btn-info m-0 d-flex align-items-center' style='padding: .5rem;' aria-label='Editar' title='Editar' ${disabled}>
									<i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i>
								</a>
								<button onclick='eliminarCheques(${item.id
						}, ${item.monto}, "${item.razon}", ${item.banco}, 
									"${moment(item.fecha_cobro).format(
							'YYYY-MM-DD'
						)}", "${item.girador}", ${item.numero_documento}, ${item.cantidad})' 
									type='button' class='btn btn-danger m-0 d-flex align-items-center' style='padding: .5rem;' title='Eliminar' ${disabled}>
									<i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
								</button>
							</div>
						</td>
					`);

					tbody.append(newRow);
				});

				// Inicializar tooltips para los botones dinámicos
				$('[data-bs-toggle="tooltip"]').tooltip();

				// Agregar evento de cambio a los switches de depósito
				// $('.switchDeposito').change(function () {
				// 	const isChecked = $(this).is(':checked');
				// 	const row = $(this).closest('tr');

				// 	// Deshabilitar los elementos en la misma fila
				// 	row.find('.switchCobrar, button, a').prop('disabled', isChecked);

				// 	// Deshabilitar el switch de depósito si se activa
				// 	if (isChecked) {
				// 		$(this).attr('disabled', true).prop('checked', true);
				// 		// Cambiar el texto del switch
				// 		$(this).next('.switchText').text('Si');
				// 	} else {
				// 		$(this).next('.switchText').text('No');
				// 	}
				// });
			} else {
				tbody.append(
					"<tr><td colspan='9' style='text-align:center'> No hay Cheques</td></tr>"
				);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error('Error al cargar cheques:', textStatus, errorThrown);
		},
	});
}

$(document).on('change', '.switchCheques, #switchDeposito', function () {
	let checkbox = $(this);
	let isDeposito = checkbox.is('#switchDeposito'); // Verificar si es el checkbox del depósito
	let token = checkbox.attr('data-token');
	let name = checkbox.attr('name') || 'deposito';
	let boolean = checkbox.is(':checked'); // Valor booleano del checkbox

	// Función para actualizar los switches con la clase `.switchCheques`
	function actualizarCheque() {
		return new Promise((resolve) => {
			actualizarEstados(name, token, boolean); // Llamada a la función
			let spanElement = checkbox.siblings('.switchText');
			spanElement.text(boolean ? 'Si' : 'No');
			resolve(); // Resolver la promesa cuando finalice la actualización
		});
	}

	if (isDeposito) {
		let monto = checkbox.attr('monto');
		let idPropiedad = checkbox.attr('id-propiedad');

		Swal.fire({
			title:
				'Una vez realizado el depósito, el estado no puede ser modificado nuevamente.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Cambiar',
			cancelButtonText: 'Cancelar',
			allowOutsideClick: false,
			allowEscapeKey: false,
		}).then((result) => {
			if (result.isConfirmed) {
				// Primero ejecutar la función de actualizar cheque
				actualizarCheque().then(() => {
					// Después realizar el depósito
					$.ajax({
						url: 'components/arriendo/models/actualizar_deposito.php',
						type: 'POST',
						data: { monto: monto, idPropiedad: idPropiedad },
						success: function (response) {
							console.log('Cheque depositado exitosamente:', response);
							cargarCheques(); // Recargar la página solo después de ambas operaciones
							cargarCCMovimientos();
						},
						error: function (jqXHR, textStatus, errorThrown) {
							console.error(
								'Error al actualizar depósito:',
								textStatus,
								errorThrown
							);
							Swal.fire({
								title: 'Error',
								text: 'No se pudo actualizar el depósito. Inténtalo de nuevo.',
								icon: 'error',
							});
						},
					});
				});
			} else {
				checkbox.prop('checked', false); // Revertir el cambio si se cancela
			}
		});
	} else {
		// Si no es depósito, solo se actualiza el cheque
		actualizarCheque();
	}
});

// Función para actualizar el estado en la base de datos usando AJAX
function actualizarEstados(nombre, token, boolean) {
	$.ajax({
		url: 'components/arriendo/models/actualizar_estado_cheques.php',
		type: 'POST',
		data: {
			name: nombre,
			token: token,
			boolean: boolean,
		},
		success: function (response) {
			console.log('Cheque actualizado exitosamente:', response);
			// Función para recargar o actualizar la vista de cheques
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error('Error al actualizar cheque:', textStatus, errorThrown);
		},
	});
}
























// jhernandez funcion para listar los tipos de movimientos de cuentas corrientes cargos.
function CargarSelectTipoMovimientosCC() {
	// Realizar la solicitud AJAX
	$.ajax({
		url: 'components/arriendo/models/TipoMovimientos.php',
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
				const id_responsable_cargo = $(this).find(':selected').data('idresponsablecargo');
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
        url: 'components/arriendo/models/TipoMovimientosAbono.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log('Datos recibidos:', data); // Verificar datos recibidos

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
                $('#cccta_contable').val(cta_corriente || '');
            });
        },
        error: function (xhr, status, error) {
            console.error('Error al obtener los datos:', error);
        },
    });
}
























$(document).ready(function () {
    CargarSelectTipoMovimientosCCAbono();
});



// jhernandez validacion de propiedades en estado retirada
function ValidarPropiedadAarrendar(id) {
	$.ajax({
		url:
			'components/arriendo/models/validarBusquedaPropiedad.php?id_propiedad=' +
			id,
		method: 'GET', // Método de la solicitud (puede ser GET o POST según sea necesario)
		success: function (data) {
			if (data) {
				Swal.fire({
					title: '',
					html: data,
					icon: 'warning',
				});

				$('#codigo_propiedad').val('');
			}
		},
		error: function (xhr, status, error) {
			// Manejo de errores
			console.error('Error al obtener los datos: ', error);
		},
	});
}

// validar moneda multa jhernandez
function validarTipoReajuste() {
	var monedaMulta = document.getElementById('tipoReajuste').value;
	var CantidadMonedaMulta = document.getElementById('CantidadReajuste').value;
	//	console.log("tipoReajuste", tipoReajuste);
	//	console.log("CantidadReajuste", CantidadReajuste);
	if (monedaMulta === 'IPC' || monedaMulta === 'Fijo porcentual') {
		// Validar que el valor no sea mayor a 100
		if (parseInt(CantidadMonedaMulta) > 100) {
			// console.log("No puede ser mayor que 100");
			//alert('El valor de la comisión no puede ser mayor a 100%');
			// Establecer el valor en 100
			Swal.fire({
				title: 'Atención ',
				text:
					'Al seleccionar ' +
					monedaMulta +
					' en reajuste no debe superar el 100%',
				icon: 'warning',
			});
			document.getElementById('CantidadReajuste').value = 100;
		}
	}
}

// jhernandez obtener los meses especiales
function ObtenerMesesEspeciales() {
	// var url = window.location.href;
	// var parametros = (new URL(url)).searchParams;
	// var token = parametros.get('token');
	// $.ajax({
	//   url: 'components/arriendo/models/MesesEspeciales.php',
	//   method: 'GET',
	//   dataType: 'json',
	//   data: {
	//     token: token,
	//   },
	//   success: function (data) {
	//     // Recorremos los datos recibidos
	//     data.forEach(function (item) {
	//       var idMes = item.id_mes;
	//       var idMoneda = item.id_moneda;
	//       // Obtenemos el nombre del mes según el id_mes
	//       var nombreMes = obtenerNombreMes(idMes);
	//       // Asignamos el valor de la moneda en el select correspondiente
	//       // $('#diasPagoTipoMoneda' + nombreMes).val(idMoneda);
	//       // // Asignar el valor de la periodicidad en el select correspondiente
	//       // $('#diasPagoPeriodicidad' + nombreMes).val(idPeriodicidad);
	//     });
	//   },
	//   error: function (xhr, status, error) {
	//     console.error('Error al obtener los datos: ', error);
	//   }
	// });
}

// Función auxiliar para obtener el nombre del mes en base al ID del mes
function obtenerNombreMes(idMes) {
	// var nombresMeses = [
	//   'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
	//   'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
	// ];
	// return nombresMeses[idMes - 1]; // Restamos 1 porque el array empieza en 0
}

// funcion para validar el tipo de moneda si es uf, porcentaje o peso
function TipoMascaraMoneda() {
	var tipo_moneda = $('#monedaMulta').val();

	if (tipo_moneda == '2') {
		// Moneda 'Pesos' (id == 2)

		$('#montoMultaAtraso').on('input', function () {
			var value = $(this).val().replace(/[^\d]/g, ''); // Solo números
			var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Formato de miles
			$(this).val(formattedValue);
		});
	} else {
		$('#montoMultaAtraso').on('input', function () {
			$('#').val(0);
			var value = $(this)
				.val()
				.replace(/[^0-9.,]/g, ''); // Solo permitir números, comas y puntos
			var parts = value.split(/[.,]/); // Separar parte entera y decimal

			if (parts.length > 2) {
				value = parts[0] + '.' + parts[1]; // Mantener solo la parte antes y después del primer separador decimal
			}
			$(this).val(value);
		});
	}
}

// funcion para mostrar el formulario segun el tipo movimiento cargos
function TipoFormularioCargosMovimientos() {
	var tipo_movimiento = $('#ccTipoMovimientoCargo').val();
	var div = $('#FormularioTipoMovimiento');

	if (tipo_movimiento == 4) {
		div.hide();
	} else {
		div.show();
	}
}

// funcion para mostrar campos del formulario segun tipo de mocimiento en abonos
function TipoFormularioAbonosMovimientos() {
	var tipo_movimiento = $('#ccTipoMovimientoAbono').val();
	var div = $('#FormularioMovimientosAbonos');

	if (tipo_movimiento == 1) {
		div.hide();
	} else {
		div.show();
	}
}

function configurarSelectReajuste(tipoReajuste) {
	$('.js-example-responsive').select2({
		width: '100%',
		placeholder: 'Seleccione',
	});

	// Verifica si el tipo de reajuste es 'Sin reajuste' y deshabilita el campo de meses si es necesario
	console.log('tipoReajuste inicio', tipoReajuste);
	if (tipoReajuste === 'Sin reajuste') {
		document.getElementById('CantidadReajuste').value = '';
		document.getElementById('CantidadReajuste').disabled = true;
		document.getElementById('permiteReajusteNegativo').disabled = true;

		$('#meses').val(null).trigger('change');
		document.getElementById('meses').disabled = true;
	}
}

// Llama a la función con el valor de tipoReajuste actual cuando el documento esté listo
$(document).ready(function () {
	const tipoReajuste = document.getElementById('tipoReajuste').value;
	configurarSelectReajuste(tipoReajuste);
});

function habilitarFormSegunTipoDoc() {
	var tipoServicio = $('#TipoServicio'); // Seleccionamos el elemento con jQuery
	var tipoServiciosDiv = $('#tipo_servicios'); // Div relacionado con Plan, Fecha Inicio, Fecha Fin
	var tipoMonedaDiv = $('#monedaServicio').closest('.form-group'); // Seleccionamos el contenedor de Tipo Moneda
	var montoDiv = $('#montoServicio').closest('.form-group'); // Seleccionamos el contenedor de Monto
	var periocidadDiv = $('#periocidadServicio').closest('.form-group'); // Seleccionamos el contenedor de Periocidad

	if (tipoServicio.length > 0) {
		// Verificamos que el elemento existe
		var id = parseInt(tipoServicio.val()); // Obtenemos el valor como entero
		if (id === 1 || id === 2 || id === 3) {
			// Agua, luz, gas
			tipoServiciosDiv.addClass('d-none'); // Ocultar el div relacionado con servicios específicos
			tipoMonedaDiv.addClass('d-none'); // Ocultar Tipo Moneda
			montoDiv.addClass('d-none'); // Ocultar Monto
			periocidadDiv.addClass('d-none'); // Ocultar Periocidad
		} else {
			tipoServiciosDiv.removeClass('d-none'); // Mostrar el div relacionado con servicios específicos
			tipoMonedaDiv.removeClass('d-none'); // Mostrar Tipo Moneda
			montoDiv.removeClass('d-none'); // Mostrar Monto
			periocidadDiv.removeClass('d-none'); // Mostrar Periocidad
		}
	} else {
		alert('No se encontró el elemento TipoServicio');
	}
}

// jhernandez
function inicializarTablaArriendos() {
	$.ajax({
		url: 'components/arriendo/models/arriendo_morosos_list.php',
		method: 'GET',
		success: function (data) {
			// Parsear los datos si son una cadena JSON
			let morosos = typeof data === 'string' ? JSON.parse(data) : data;

			// Limpiar el tbody antes de agregar nuevas filas
			let tbody = $('#Listado_Arriendos_morosos tbody');
			tbody.empty();

			// Recorrer los datos con un foreach y construir las filas
			morosos.forEach((item) => {
				let deudaAlDia = `$${parseFloat(item.saldo).toLocaleString()}`; // Formatear como moneda

				// Crear la fila
				let fila = `
                    <tr>
                        <td>${item.direccion}</td>
                        <td>${deudaAlDia}</td>
                        <td><a href="index.php?component=propiedad&amp;view=propiedad_ficha_tecnica&amp;token=${item.token}" class="link-info"> ${item.id_propiedad}</a></td>
                    </tr>
                `;

				// Agregar la fila al tbody
				tbody.append(fila);
			});

			// Inicializar o reinicializar DataTable
			$('#Listado_Arriendos_morosos').DataTable({
				destroy: true, // Para permitir la reinicialización
				searching: true, // Buscar en las filas
				paging: true, // Habilitar paginación
				info: true, // Mostrar información del estado de la tabla
			});

			console.log('Tabla inicializada con los datos:', morosos);
		},
		error: function (xhr, status, error) {
			alert('Error al obtener los datos: ' + error);
		},
	});
}

function LimpiarValorTipoMoneda() {
	// limpia el campo precio al cambiar el tipo de moneda

	$('#precioContrato').val('');
}



// llena los select al editar los campos 

function SeleccionacobrarComisionAdministracion() {

	// Obtener la URL actual
	const url = new URL(window.location.href);
	const token = url.searchParams.get('token');

	$.ajax({
		url: 'components/arriendo/models/SelectcobrarComisionAdministracion.php', // URL del archivo PHP
		method: 'POST', // Método POST
		dataType: 'json', // El servidor devolverá un JSON
		data: { // Parámetros que se envían al PHP
			token: token
		},
		success: function (data) {

			if (data && data[0]) {
				const $selectAdmin = $('#cobrarComisionAdministracion');
				const $selectArriendo = $('#cobrarComisionArriendo');

				// Seleccionar el valor correspondiente para cobrarComisionAdministracion
				$selectAdmin.val(data[0].adm_comision_cobro.toString());

				// Seleccionar el valor correspondiente para cobrarComisionArriendo
				$selectArriendo.val(data[0].arriendo_comision_cobro.toString());
			} else {
				console.error('Respuesta inesperada o sin datos.');
			}
		},
		error: function (xhr, status, error) {
			console.error('Error al cargar los datos:', error);
		}
	});
}
