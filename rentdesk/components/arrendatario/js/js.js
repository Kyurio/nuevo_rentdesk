// Obtener la parte de la URL después del símbolo de interrogación
var queryString = window.location.search;
sessionStorage.clear();
sessionStorage.removeItem('nombre_arrendatario');
// Parsear la cadena de consulta para obtener los parámetros y valores
var params = new URLSearchParams(queryString);

// Obtener el valor de un parámetro específico, por ejemplo, 'parametro'
var tokenUrl = params.get('token');
$(document).ready(function () {
	if ($('#DNI').val() != '') {
		$('#button-addon2').hide();
		$('#DNI').css('border', '1px solid #dddddd');
		setTimeout(function () {
			busquedaDNI();
		}, 1000);
	}

	$('#descargarExcelArrendatario').on('click', function (e) {
		e.preventDefault();

		// Captura el valor del input
		var searchTerm = $('#nombre_arrendatario').val();

		$.ajax({
			url: 'components/arrendatario/models/get_arrendatario_excel.php',
			type: 'GET',
			data: { searchTerm: searchTerm }, // Enviamos el filtro
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
				// Podemos usar utils.json_to_sheet:
				var worksheet = XLSX.utils.json_to_sheet(response);

				// Crear un nuevo libro de trabajo (workbook)
				var workbook = XLSX.utils.book_new();
				XLSX.utils.book_append_sheet(workbook, worksheet, 'Arrendatarios');

				// Generar el archivo XLSX
				var wbout = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

				// Crear blob a partir del workbook
				var blob = new Blob([wbout], { type: 'application/octet-stream' });

				// Crear URL para descarga
				var url = URL.createObjectURL(blob);

				// Crear link temporal para forzar la descarga
				var a = document.createElement('a');
				a.href = url;
				a.download = 'arrendatarios.xlsx'; // Nombre del archivo
				document.body.appendChild(a);
				a.click();
				document.body.removeChild(a);

				// Liberar el objeto URL
				URL.revokeObjectURL(url);
			},
			error: function (xhr, status, error) {
				console.error('Error en la petición AJAX:', error);
			},
		});
	});
});

function enviarRentdesk() {
	// $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	var formData = new FormData(
		document.getElementById('formulario-arrendatario')
	);
	var formDataCuentasBancarias = document.getElementById('itemData').value;

	console.log(
		'formDataCuentasBancarias: ',
		JSON.parse(formDataCuentasBancarias)
	);

	JSON.parse(formDataCuentasBancarias).forEach((item, index) => {
		Object.entries(item).forEach(([key, value]) => {
			if (typeof value === 'object') {
				// Handle nested objects
				Object.entries(value).forEach(([nestedKey, nestedValue]) => {
					formData.append(`${key}[${index}][${nestedKey}]`, nestedValue);
				});
			} else {
				// Handle non-nested values
				formData.append(`${key}[${index}]`, value);
			}
		});
	});

	console.log('mergedFormData: ', formData);

	$.ajax({
		url: 'components/arrendatario/models/insert_update.php',
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
				'index.php?component=arrendatario&view=arrendatario_list';
			return false;
		} else {
			$.showAlert({ title: 'Error', body: mensaje });
			return false;
		}
	});
} //function enviar

function loadArrendatario_List() {
	$(document).ready(function () {
		// Recuperar valores de los elementos del DOM de forma segura
		var dniCliente = $('#nombre_arrendatario').val();
		sessionStorage.setItem('nombre_arrendatario', dniCliente);
		var ajaxUrl =
			'components/arrendatario/models/arrendatario_list_procesa.php?' +
			'dniArrendatario=' +
			encodeURIComponent(dniCliente);

		// Comprobar si la tabla ya ha sido inicializada
		if ($.fn.DataTable.isDataTable('#arrendatario')) {
			// Recargar datos sin reinicializar
			var table = $('#arrendatario').DataTable();
			table.ajax.url(ajaxUrl).load();
		} else {
			// Inicializar DataTable si no está ya inicializada
			$('#arrendatario').DataTable({
				order: [[0, 'desc']],
				processing: true,
				serverSide: true,
				lengthMenu: [
					[10, 25, 50, 100, 5000],
					[10, 25, 50, 100, 'Todos'],
				],
				columnDefs: [{ orderable: false, targets: [0, 1, 2, 3, 4] }],
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
				dom: 'Bfrtip', // Para el layout de los botones
				buttons: [
					{
						extend: 'excelHtml5',
						text: '<i class="fas fa-file-excel"></i> Descargar Excel',
						title: 'Listado de Arrendatarios',
						className: 'btn btn-success',
						exportOptions: {
							// Especifica aquí las columnas que quieres exportar (excluyendo la de acciones)
							columns: [0, 1, 2, 3],

							format: {
								body: function (data, row, column, node) {
									// Asegurarse de convertir cada dato a mayúsculas y limpiar links
									let cleanData = $(node).text().toUpperCase();
									return cleanData;
								},
							},
							// Excluye la columna 7 (acciones)
						},
						customize: function (xlsx) {
							var sheet = xlsx.xl.worksheets['sheet1.xml'];

							// Eliminar la columna de acciones en el Excel
							const columnIndexToRemove = 7; // Ajusta este índice según la posición de la columna de acciones
							$(sheet)
								.find(
									'row c[r^="' +
										String.fromCharCode(65 + columnIndexToRemove) +
										'"]'
								)
								.remove(); // Elimina todas las celdas de esa columna

							// Eliminar también la celda del encabezado de la columna de acciones
							$(sheet)
								.find(
									'row:first c[r^="' +
										String.fromCharCode(65 + columnIndexToRemove) +
										'"]'
								)
								.remove();

							// Formatear la columna de precio (ajusta el índice al correcto)
							const precioColumnIndex = 5; // Cambia esto al índice de la columna que contiene el precio (0 para la primera columna)

							$(sheet)
								.find('row')
								.each(function (rowIdx) {
									if (rowIdx > 0) {
										// Salta la fila de encabezado
										const cell = $(this).find(
											`c[r^="${String.fromCharCode(65 + precioColumnIndex)}"]`
										);
										if (cell.length) {
											const precio = parseFloat($(cell).text());
											const formattedPrice = formateoDivisa(precio);
											$(cell).text(formattedPrice);
										}
									}
								});
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
					// Recargar los datos de la tabla con los nuevos filtros
					loadArrendatario_List();
				});
		}
	});
}

function avisoEliminar() {
	Swal.fire({
		title: 'Aviso',
		text: 'El arrendatario se encuentra asignado a uno o mas arriendos por lo que no se puede ser eliminado',
		icon: 'warning',
	});
}

function eliminarArrendatario(id) {
	console.log(' id: ', id);
	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Se eliminara el rol de arrendatario para este cliente',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Si',
		denyButtonText: 'No',
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: 'components/arrendatario/models/elimina_rol.php?id=' + id,
				type: 'post',
				dataType: 'html',
				data: 'id=' + id,
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
						title: 'Rol eliminado',
						icon: 'success',
					});
					loadArrendatario_List();
					return false;
				} else {
					Swal.fire({
						title: 'Problemas al eliminar rol',
						icon: 'info',
					});
					return false;
				}
			});
		} else if (result.isDenied) {
			// Si el usuario hace clic en "Cancelar"
			// Aquí puedes cerrar el modal de SweetAlert si lo deseas
		}
	});
}

// function generarExcel(urlbase){
//     // Recuperar valores de los elementos del DOM de forma segura
// 	  var dniCliente = sessionStorage.getItem("nombre_arrendatario");

//     var ajaxUrl = "components/arrendatario/models/arrendatario_list_procesa_excel.php?" +
//         "dniArrendatario=" + encodeURIComponent(dniCliente);
// 	$.ajax({
// 			type: 'GET',
// 			url: ajaxUrl,
// 			success: function(res){
// 				window.open('/upload/arrendatario/excel/'+res, '_blank');
// 			}
// 	});

// }

function enviar() {
	var rut =
		document.getElementById('numDocumento').value +
		'-' +
		document.getElementById('digitoVerificador').value;
	var tipo = document.getElementById('tipo_documento').value;
	var rutOk = '1';

	$('#errorrut').html('');

	if (tipo == '1') {
		rutOk = verificaRut(rut);
	} //if(  tipo=="1"  )

	if (rutOk == '1') {
		$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

		var formData = new FormData(
			document.getElementById('formulario-arrendatario')
		);

		$.ajax({
			url: 'components/arrendatario/models/insert_update.php',
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
					'index.php?component=arrendatario&view=arrendatario&token=' + token;
				return false;
			} else {
				$.showAlert({ title: 'Error', body: mensaje });
				return false;
			}
		});
	} else {
		$.showAlert({
			title: 'Atención',
			body: 'Debe ser un Rut con su dígito verificador válido.',
		});
	} //if(rutOk=="1")
} //function enviar

//Desde acá código para Datatable listado
//*****************************************************************************************
function loadPropietarios() {
	$(document).ready(function () {
		$('#tabla').DataTable({
			order: [[0, 'asc']],
			processing: true,
			serverSide: true,
			pageLength: 25,
			columnDefs: [{ orderable: false, targets: [7, 8, 9] }],
			ajax: {
				url: 'components/arrendatario/models/arrendatario_list_procesa.php',
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
function deletePropietario(token) {
	$.showConfirm({
		title: 'Por Favor Confirme.',
		body: 'Realmente desea Eliminar El registro? No se puede deshacer.',
		textTrue: 'Si',
		textFalse: 'No',
		onSubmit: function (result) {
			if (result) {
				$.ajax({
					type: 'POST',
					url: 'components/arrendatario/models/delete.php',
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

function buscarpersona(num_docu) {
	var tipo_docu = $('#tipo_documento').val();
	if (num_docu != '' && tipo_docu != '') {
		$.ajax({
			type: 'POST',
			url: 'components/arrendatario/models/busca_persona.php',
			data: 'num_docu=' + num_docu + '&tipo_docu=' + tipo_docu,
			success: function (resp) {
				var retorno = resp.split('xxx,');
				var resultado = retorno[1];
				var token = retorno[3];
				var mensaje =
					'El numero de documento ingresado ya se encuentra registrado. Serás redirigido a la ficha del arrendatario.';

				if (resultado == 'OK') {
					$.showAlert({ title: 'Atención', body: mensaje });
					document.location.href =
						'index.php?component=arrendatario&view=arrendatario&token=' + token;
				}
			},
		});
	}
}

function loadPropiedadesLiqui(token, nav) {
	$(document).ready(function () {
		$('#tabla').DataTable({
			order: [[0, 'asc']],
			processing: true,
			serverSide: true,
			pageLength: 25,
			columnDefs: [{ orderable: false, targets: [7, 8, 9, 10] }],
			ajax: {
				url:
					'components/arrendatario/models/arrendatario_list_procesa_prop_pago.php?token=' +
					token +
					'&nav=' +
					nav,
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

//*************************************************************************************************************************

var Fn = {
	validaRut: function (rutCompleto) {
		rutCompleto = rutCompleto.replace('‐', '-');
		if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(rutCompleto)) return false;
		var tmp = rutCompleto.split('-');
		var digv = tmp[1];
		var rut = tmp[0];
		if (digv == 'K') digv = 'k';

		return Fn.dv(rut) == digv;
	},
	dv: function (T) {
		var M = 0,
			S = 1;
		for (; T; T = Math.floor(T / 10)) S = (S + (T % 10) * (9 - (M++ % 6))) % 11;
		return S ? S - 1 : 'k';
	},
};

function verificaRut(rut) {
	if (Fn.validaRut(rut)) {
		$('#errorrut').html('');
		return '1';
	} else {
		$('#errorrut').html('Rut inválido. Debe ingresar un Rut válido.');
		return '0';
	}
} //function verificaRut()

$(document).ready(function () {
	// Add change event listener to the select element
	onChangePersona();
});

function onChangePersona(token = null) {
	var selectedValue = $('#DNI').val();

	$('#section-1').hide();
	$('#section-2').hide();
	$('#section-3').hide();
	$('#section-4').hide();

	if (selectedValue) {
		$('#section-1').show();
		$('#section-2').show();
		$('#section-3').show();
		$('#section-4').show();
	}
}

var itemList = []; // Array to store items

function addItem() {
	var formData = new FormData(document.getElementById('myForm'));
	var item = {
		numIdentificacion: formData.get('numIdentificacion'),
		emailTitular: formData.get('emailTitular'),
		banco: formData.get('banco[]'),
		ctaBanco: formData.get('cta-banco[]'),
		numCuenta: formData.get('numCuenta'),
	};
	itemList.push(item); // Add item to list

	// Create table row and populate with item data
	var newRow = document.createElement('tr');
	newRow.innerHTML =
		'<td>' +
		item.numIdentificacion +
		'</td><td>' +
		item.emailTitular +
		'</td><td>' +
		item.banco +
		'</td><td>' +
		item.ctaBanco +
		'</td><td>' +
		item.numCuenta +
		'</td>';

	// Add row to table body
	document.getElementById('itemBody').appendChild(newRow);

	// Clear form fields
	document.getElementById('myForm').reset();
}

function busquedaFiltros() {
	console.log('ENTRÓ A BUSCAR POR FILTROS');

	var formData = new FormData(document.getElementById('filtros-busqueda'));

	$.ajax({
		url: 'components/arrendatario/models/busca_lista_filtros.php',
		type: 'post',
		dataType: 'html',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		document.getElementById('results').innerHTML = res;
	});
}

function busquedaDNI() {
	//var formData = dni;
	$('#DNI').prop('disabled', true);
	$('#button-addon2').hide();
	$('#DNI').css('border', '1px solid #dddddd');
	const input = document.getElementById('DNI');
	let inputPersonaToken = document.getElementById('persona');

	var dni = input.value;
	var dniBuscar = $('#DNI').val();

	if (dniBuscar !== '') {
		$.ajax({
			url: 'components/arrendatario/models/busca_dni.php',
			type: 'POST',
			data: 'dni=' + dni,
			success: function (resp) {
				var retorno = resp.split('||');
				var resultado = retorno[0];
				var mensaje = retorno[2];

				if (resultado == 'ERROR') {
					Swal.fire({
						title: '¡Persona no encontrada!',
						text: 'Serás redirigido para crear su registro.',
						icon: 'info',
						showConfirmButton: true,
						allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
						willClose: () => {
							// Redireccionar a otra página cuando la alerta se cierre
							window.location.href = 'index.php?component=persona&view=persona';
						},
					}).then((result) => {
						// Verificar si el usuario confirmó la alerta
						if (result.isConfirmed) {
							// Redireccionar a otra página si se confirma la alerta
							window.location.href = 'index.php?component=persona&view=persona';
						}
					});
				} else {
					var personaJson = retorno[3];
					var personaJson = JSON.parse(personaJson);
					inputPersonaToken.value = mensaje;
					cargarInfoPersonal(personaJson);
					onChangePersona();
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

function cargarInfoPersonal(infoJSON) {
	if (infoJSON[0].id_propietario !== null && tokenUrl === null) {
		document.location.href =
			'index.php?component=arrendatario&view=arrendatario&token=' +
			infoJSON[0].token_arrendatario;
	}
	if (infoJSON[0].tipo_persona === 'NATURAL') {
		$('#nombrePersona').text(
			infoJSON[0].nombres +
				' ' +
				infoJSON[0].apellido_paterno +
				' ' +
				infoJSON[0].apellido_materno
		);
		var telefono = '';
		if (infoJSON[0].telefono_fijo != '' && infoJSON[0].telefono_movil == '') {
			telefono = telefono + infoJSON[0].telefono_fijo;
		} else if (
			infoJSON[0].telefono_fijo == '' &&
			infoJSON[0].telefono_movil != ''
		) {
			telefono = telefono + infoJSON[0].telefono_movil;
		} else if (
			infoJSON[0].telefono_fijo != '' &&
			infoJSON[0].telefono_movil != ''
		) {
			telefono = infoJSON[0].telefono_fijo + ' - ' + infoJSON[0].telefono_movil;
		}
		$('#telefonoMovilPersona').text(telefono);
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
		var telefono = '';
		if (infoJSON[0].telefono_fijo != '' && infoJSON[0].telefono_movil == '') {
			telefono = telefono + infoJSON[0].telefono_fijo;
		} else if (
			infoJSON[0].telefono_fijo == '' &&
			infoJSON[0].telefono_movil != ''
		) {
			telefono = telefono + infoJSON[0].telefono_movil;
		} else if (
			infoJSON[0].telefono_fijo != '' &&
			infoJSON[0].telefono_movil != ''
		) {
			telefono = infoJSON[0].telefono_fijo + ' - ' + infoJSON[0].telefono_movil;
		}
		$('#telefonoMovilPersonaJuridica').text(telefono);
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
	$('#buttonSave').show();
}

function CargarListadoArrendatarios() {
	//$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	$.ajax({
		url: 'components/arrendatario/models/listado_arrendatario.php',
		dataType: 'text',
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			var jsonRes = res;
			var data = JSON.parse(jsonRes);
			var tbody = $('#arrendatario tbody');
			tbody.empty();
			data.forEach(function (elemento) {
				var newRow = $('<tr>');
				if (elemento.id_tipo_persona == 1) {
					var nombreCliente =
						elemento.nombres +
						' ' +
						elemento.apellido_paterno +
						' ' +
						elemento.apellido_materno;
				} else {
					var nombreCliente = elemento.razon_social;
				}
				newRow.append('<td>' + nombreCliente + '</td>');
				newRow.append('<td>' + elemento.tipo_dni + '</td>');
				newRow.append('<td>' + elemento.dni + '</td>');
				newRow.append('<td>' + elemento.tipo_persona + '</td>');
				newRow.append('<td>-</td>');
				newRow.append(`
        <td>
        <div class="d-flex" style="gap: .5rem;">
          <a href="index.php?component=arrendatario&view=arrendatario&token=${elemento.tokenarrendatario}" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
           <i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
          </a>
          <button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
           <i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
          </button>
        </div>
        </td>`);
				newRow.append('</tr>');
				tbody.append(newRow);
			});
		})
		.fail(function (jqXHR, textStatus, errorThrown) {});
}

function ocultarAutocomplete(tipo) {
	$('#suggestions_' + tipo).fadeOut(500);
}
function buscarArrendatarioAutocompleteGenerica(valor, tipo) {
	var codigo = document.getElementById(tipo).value;

	var caracteres = codigo.length;
	//Si por lo menos ha ingresado 3 caracteres comenzamos a autocompletar
	if (caracteres >= 3) {
		$.ajax({
			type: 'POST',
			url: 'components/arrendatario/models/buscar_arrendatario_autocomplete_generica.php',
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

function cargarListadoArrendatarioFiltro() {
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	var dniArrendatario = $('#nombre_arrendatario').val();

	var data = {
		dniArrendatario: dniArrendatario,
	};
	console.log(data);
	$.ajax({
		url: 'components/arrendatario/models/listado_arrendatario.php',
		dataType: 'text',
		type: 'post',
		data: data,
		cache: false,
	})
		.done(function (res) {
			if (res != 'ERROR') {
				var jsonRes = res;
				var data = JSON.parse(jsonRes);
				var tbody = $('#arrendatario tbody');
				tbody.empty();
				data.forEach(function (elemento) {
					var newRow = $('<tr>');
					if (elemento.id_tipo_persona == 1) {
						var nombreCliente =
							elemento.nombres +
							' ' +
							elemento.apellido_paterno +
							' ' +
							elemento.apellido_materno;
					} else {
						var nombreCliente = elemento.razon_social;
					}
					newRow.append('<td>' + nombreCliente + '</td>');
					newRow.append('<td>' + elemento.tipo_dni + '</td>');
					newRow.append('<td>' + elemento.dni + '</td>');
					newRow.append('<td>' + elemento.tipo_persona + '</td>');
					newRow.append('<td>-</td>');
					newRow.append(`
        <td>
        <div class="d-flex" style="gap: .5rem;">
          <a href="index.php?component=arrendatario&view=arrendatario&token=${elemento.tokenarrendatario}" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
           <i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
          </a>
          <button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
           <i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
          </button>
        </div>
        </td>`);
					newRow.append('</tr>');
					tbody.append(newRow);
				});
			} else {
				var tbody = $('#arrendatario tbody');
				tbody.empty();
				var newRow = $('<tr>');
				newRow.append(
					"<td colspan='6'><p style='text-align:center'>Ningun registro coincide con la busqueda</p></td>"
				);
				newRow.append('</tr>');
				tbody.append(newRow);
			}
		})
		.fail(function (jqXHR, textStatus, errorThrown) {});
}
function ocultarAutocomplete(tipo) {
	$('#suggestions_' + tipo).fadeOut(500);
}
function buscarClienteAutocompleteGenerica(valor, tipo) {
	var codigo = document.getElementById(tipo).value;
	var caracteres = codigo.length;
	//Si por lo menos ha ingresado 3 caracteres comenzamos a autocompletar
	if (caracteres >= 3) {
		$.ajax({
			type: 'POST',
			url: 'components/persona/models/buscar_cliente_autocomplete_generica.php',
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

/***************Funciones para cargar***************** */

function guardarProp() {
	//if ($('input[name="cta[]"]').length > 0) {
	// El elemento input con name='cta[]' existe
	var formData = new FormData(document.getElementById('form-cuentas'));

	$.ajax({
		url: 'components/arrendatario/models/insert_arrendatario.php', // Nombre del archivo PHP
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		success: function (response) {
			// Manejar la respuesta del servidor

			var partes = response.split('||');
			var resultado = partes[0];
			console.log('Respuesta del servidor: ', response);
			if (resultado == 'OK') {
				Swal.fire({
					title: 'Arrendatario registrado',
					text: 'El arrendatario ha sido registrado correctamente ',
					icon: 'success',
					showConfirmButton: true,
					allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
					willClose: () => {
						window.location.href =
							'index.php?component=arrendatario&view=arrendatario_list';
					},
				}).then((result) => {
					window.location.href =
						'index.php?component=arrendatario&view=arrendatario_list';
				});
			}
		},
		error: function (xhr, status, error) {
			// Manejar errores de la solicitud AJAX
			console.error('Error en la solicitud AJAX:', error);
		},
	});
	// } else {
	//   // El elemento input con name='cta[]' no existe
	//   Swal.fire({
	//     title: "Complete los datos",
	//     text: "Debe añadir minimo una cuenta",
	//     icon: "info",
	//   });
	// }
}
function editProp() {
	var formData = new FormData(document.getElementById('form-cuentas-edit'));
	$.ajax({
		url: 'components/arrendatario/models/update_arrendatario.php', // Nombre del archivo PHP
		type: 'post',
		dataType: 'text',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		success: function (response) {
			// Manejar la respuesta del servidor
			console.log(response);
			var partes = response.split('||');
			var resultado = partes[0];
			console.log('Respuesta del servidor: ', resultado);
			if (resultado == 'OK') {
				Swal.fire({
					title: 'Arrendatario registrado',
					text: 'El arrendatario ha sido registrado correctamente ',
					icon: 'success',
					showConfirmButton: true,
					allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
					willClose: () => {
						window.location.href =
							'index.php?component=arrendatario&view=arrendatario_list';
					},
				}).then((result) => {
					window.location.href =
						'index.php?component=arrendatario&view=arrendatario_list';
				});
			}
		},
		error: function (xhr, status, error) {
			// Manejar errores de la solicitud AJAX
			console.error('Error en la solicitud AJAX:', error);
		},
	});
}
$('#info-cuentas').on('click', '.eliminarFila', function () {
	$(this).closest('tr').remove(); // Eliminar la fila más cercana (padre) del botón
	console.log('holi');
});
function addForm() {
	var nombreTitular = $('#nombreTitular').val();
	var rutTitular = $('#rutTitular').val();
	var emailTitular = $('#emailTitular').val();
	var banco = $('#banco').val();
	var bancoSeleccionado = $('#banco option:selected').text();
	var cta = $('#cta-banco').val();
	var ctaSeleccionado = $('#cta-banco option:selected').text();
	var numCuenta = $('#numCuenta').val();

	var estadoRut = validarRutChile(rutTitular);
	var estadoMail = validarEmail(emailTitular);

	// if (
	//   nombreTitular == "" ||
	//   rutTitular == "" ||
	//   emailTitular == "" ||
	//   banco == "" ||
	//   cta == "" ||
	//   numCuenta == ""
	// ) {
	//   Swal.fire({
	//     title: "Complete los datos",
	//     text: "Por favor complete los datos para añadir la cuenta",
	//     icon: "info",
	//   });
	// } else
	if (estadoRut == false) {
		Swal.fire({
			title: 'Ingrese un rut Correcto',
			text: 'El rut ingresado no es valido ',
			icon: 'info',
		});
	} else if (estadoMail == false) {
		Swal.fire({
			title: 'Ingrese un email Correcto',
			text: 'El email ingresado no es valido ',
			icon: 'info',
		});
	} else {
		$('#nombreTitular').val('');
		$('#rutTitular').val('');
		$('#emailTitular').val('');
		$('#banco').val('');
		$('#cta-banco').val('');
		$('#numCuenta').val('');
		var tbody = $('#info-cuentas tbody');
		var newRow = $('<tr>');
		newRow.append(
			"<td> <input type='hidden' name='bank[]' value='" +
				banco +
				"'>" +
				bancoSeleccionado +
				'</td>'
		);
		newRow.append(
			"<td>  <input type='hidden' name='cta[]' value='" +
				cta +
				"'>" +
				ctaSeleccionado +
				'</td>'
		);
		newRow.append(
			"<td>  <input type='hidden' name='numCta[]' value='" +
				numCuenta +
				"'>" +
				numCuenta +
				'</td>'
		);
		newRow.append(
			"<td>  <input type='hidden' name='rutT[]' value='" +
				rutTitular +
				"'>" +
				rutTitular +
				'</td>'
		);
		newRow.append(
			"<td>  <input type='hidden' name='nombreT[]' value='" +
				nombreTitular +
				"'>" +
				nombreTitular +
				'</td>'
		);
		newRow.append(
			"<td>  <input type='hidden' name='mailT[]' value='" +
				emailTitular +
				"'>" +
				emailTitular +
				'</td>'
		);
		newRow.append(
			"<td><button type='button' class='btn btn-danger' onclick='borrarFila(this)'>Borrar</td>"
		);

		newRow.append('</tr>');
		tbody.append(newRow);
	}
}
function borrarFila(button) {
	// Obtener la fila actual
	var fila = button.parentNode.parentNode;
	// Borrar la fila
	fila.remove();
}

function addCuentaDirecta() {
	var formData = new FormData(document.getElementById('form-cuentas-edit'));
	var nombreTitular = $('#nombreTitular').val();
	var rutTitular = $('#rutTitular').val();
	var emailTitular = $('#emailTitular').val();
	var banco = $('#banco').val();
	var bancoSeleccionado = $('#banco option:selected').text();
	var cta = $('#cta-banco').val();
	var ctaSeleccionado = $('#cta-banco option:selected').text();
	var numCuenta = $('#numCuenta').val();

	var estadoRut = validarRutChile(rutTitular);

	var estadoMail = validarEmail(emailTitular);
	// if (
	//   nombreTitular == "" ||
	//   rutTitular == "" ||
	//   emailTitular == "" ||
	//   banco == "" ||
	//   cta == "" ||
	//   numCuenta == ""
	// ) {
	//   Swal.fire({
	//     title: "Complete los datos",
	//     text: "Por favor complete los datos para añadir la cuenta",
	//     icon: "info",
	//   });
	// } else
	if (estadoRut == false) {
		Swal.fire({
			title: 'Ingrese un rut Correcto',
			text: 'El rut ingresado no es valido ',
			icon: 'info',
		});
	} else if (estadoMail == false) {
		Swal.fire({
			title: 'Ingrese un email Correcto',
			text: 'El email ingresado no es valido ',
			icon: 'info',
		});
	} else {
		$.ajax({
			url: 'components/arrendatario/models/insert_arrendatario_directo.php', // Nombre del archivo PHP
			type: 'post',
			dataType: 'text',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				// Manejar la respuesta del servidor
				console.log(response);
				var partes = response.split('||');
				var resultado = partes[0];
				if (resultado == 'OK') {
					Swal.fire({
						title: 'Registro Exitoso',
						text: 'Su cuenta ha sido registrada correctamente.',
						icon: 'success',
						showConfirmButton: true,
						allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
						willClose: () => {
							location.reload(); // Recargar la página
						},
					}).then((result) => {
						location.reload(); // Recargar la página
					});
				}
			},
			error: function (xhr, status, error) {
				// Manejar errores de la solicitud AJAX
				console.error('Error en la solicitud AJAX:', error);
			},
		});
	}
}

function eliminarCuenta(id) {
	var id_cta = id;
	$.ajax({
		url: 'components/arrendatario/models/delete_cuenta.php', // Nombre del archivo PHP
		type: 'post',
		dataType: 'text',
		data: { id_cta: id_cta },
		cache: false,
		success: function (response) {
			var partes = response.split('||');
			var resultado = partes[0];
			if (resultado == 'OK') {
				Swal.fire({
					title: 'Registro eliminado',
					text: 'Su cuenta ha sido eliminada correctamente.',
					icon: 'success',
					showConfirmButton: true,
					allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
					willClose: () => {
						location.reload(); // Recargar la página
					},
				}).then((result) => {
					location.reload(); // Recargar la página
				});
			}
		},
		error: function (xhr, status, error) {
			// Manejar errores de la solicitud AJAX
			console.error('Error en la solicitud AJAX:', error);
		},
	});
}

function editarCuentaGuardar() {
	var nombreTitularEdit = $('#nombreTitularEdit').val();
	var rutTitularEdit = $('#rutTitularEdit').val();
	var emailTitularEdit = $('#emailTitularEdit').val();
	var bancoEdit = $('#bancoEdit').val();
	var ctabancoEdit = $('#cta-bancoEdit').val();
	var numCuentaEdit = $('#numCuentaEdit').val();
	var persona = $('#persona').val();
	var data = {
		nombreTitularEdit: nombreTitularEdit,
		rutTitularEdit: rutTitularEdit,
		emailTitularEdit: emailTitularEdit,
		bancoEdit: bancoEdit,
		ctabancoEdit: ctabancoEdit,
		numCuentaEdit: numCuentaEdit,
		persona: persona,
	};

	$.ajax({
		url: 'components/arrendatario/models/update_cuenta.php', // Nombre del archivo PHP
		type: 'post',
		dataType: 'text',
		data: data,
		cache: false,
		success: function (response) {
			// Manejar la respuesta del servidor
			console.log(response);
			var partes = response.split('||');
			var resultado = partes[0];
			if (resultado == 'OK') {
				Swal.fire({
					title: 'Actualizacion Exitosa',
					text: 'Su cuenta ha sido actualizada correctamente.',
					icon: 'success',
					showConfirmButton: true,
					allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
					willClose: () => {
						location.reload(); // Recargar la página
					},
				}).then((result) => {
					location.reload(); // Recargar la página
				});
			}
		},
		error: function (xhr, status, error) {
			// Manejar errores de la solicitud AJAX
			console.error('Error en la solicitud AJAX:', error);
		},
	});
}

function cargarCuenta(
	id_banco,
	rut_titular,
	nombre_titular,
	correo_electronico,
	id,
	id_tipo_cuenta,
	numero
) {
	$('#nombreTitularEdit').val(nombre_titular);
	$('#rutTitularEdit').val(rut_titular);
	$('#emailTitularEdit').val(correo_electronico);
	$('#bancoEdit').val(id_banco);
	$('#cta-bancoEdit').val(id_tipo_cuenta);
	$('#numCuentaEdit').val(numero);
	$('#persona').val(id);
}
// Función para validar un RUT chileno
function validarRutChile(rut) {
	rut = rut.replace(/[^0-9kK]/g, ''); // Eliminar caracteres no numéricos ni "k" ni "K"
	if (rut.length !== 8 && rut.length !== 9) return false; // El RUT debe tener 8 o 9 caracteres

	var dv = rut.charAt(rut.length - 1).toUpperCase(); // Último dígito o letra del RUT (DV)
	var rutNumerico = parseInt(rut.slice(0, -1), 10); // Parte numérica del RUT

	if (isNaN(rutNumerico)) return false; // Si la parte numérica no es un número, el RUT es inválido

	var suma = 0;
	var multiplo = 2;
	for (var i = 7; i >= 0; i--) {
		suma += (rutNumerico % 10) * multiplo;
		rutNumerico = Math.floor(rutNumerico / 10);
		multiplo = multiplo === 7 ? 2 : multiplo + 1;
	}

	var dvEsperado = 11 - (suma % 11);
	dvEsperado =
		dvEsperado === 11 ? 0 : dvEsperado === 10 ? 'K' : dvEsperado.toString();

	return dv === dvEsperado;
}

// Función para validar una dirección de correo electrónico
function validarEmail(email) {
	var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Expresión regular para validar email
	return re.test(email);
}

// copia los datos al formulario de cuentas bancarias
function CopiarDatos() {
	var nombre = $('#nombrePersona').text();
	var rut = $('#DNI').val();
	var email = $('#emailPersona').text();

	$('#nombreTitular').val(nombre);
	$('#rutTitular').val(rut);
	$('#emailTitular').val(email);
}
