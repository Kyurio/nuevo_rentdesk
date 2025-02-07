// Obtener la parte de la URL después del símbolo de interrogación
var queryString = window.location.search;
sessionStorage.clear();
sessionStorage.removeItem('nombre_codeudor');
// Parsear la cadena de consulta para obtener los parámetros y valores
var params = new URLSearchParams(queryString);

// Obtener el valor de un parámetro específico, por ejemplo, 'parametro'
var tokenUrl = params.get('token');
$(document).ready(function () {
	//BRUNO
	$('#descargarExcelCodeudor').on('click', function (e) {
		e.preventDefault();

		// Captura el valor del input (término de búsqueda)
		var searchTerm = $('#nombre_codeudor').val();

		$.ajax({
			url: 'components/codeudor/models/get_codeudor_excel.php', // tu ruta al PHP
			type: 'GET',
			data: { searchTerm: searchTerm }, // Enviamos el filtro de búsqueda al PHP
			dataType: 'json',
			success: function (response) {
				// 1) Transforma la respuesta para asignar encabezados de columnas
				if (!response || response.length === 0) {
					Swal.fire({
						icon: 'info',
						title: 'No se encontró ningún arriendo',
						showConfirmButton: true,
					});
					return; // Terminamos la ejecución de la función
				}
				var formattedData = response.map(function (row) {
					return {
						'Nombre Completo': row.nombre_completo,
						DNI: row.dni,
						Dirección: row.direccion,
						Correo: row.correo,
						'Tipo de Persona': row.tipo_persona,
					};
				});

				// 2) Crear la hoja (worksheet) usando la data formateada
				var worksheet = XLSX.utils.json_to_sheet(formattedData);

				// 3) Ajustar el ancho de las columnas (puedes modificar a tu gusto)
				worksheet['!cols'] = [
					{ wpx: 200 }, // Nombre Completo
					{ wpx: 100 }, // DNI
					{ wpx: 200 }, // Dirección
					{ wpx: 200 }, // Correo
					{ wpx: 150 }, // Tipo de Persona
				];

				// 4) Crear un nuevo libro de trabajo (workbook)
				var workbook = XLSX.utils.book_new();
				XLSX.utils.book_append_sheet(workbook, worksheet, 'Codeudores');

				// 5) Generar el archivo XLSX
				var wbout = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

				// 6) Crear un blob a partir del workbook
				var blob = new Blob([wbout], { type: 'application/octet-stream' });

				// 7) Crear URL para la descarga
				var url = URL.createObjectURL(blob);

				// 8) Crear un enlace temporal para forzar la descarga
				var a = document.createElement('a');
				a.href = url;
				a.download = 'codeudores.xlsx'; // Nombre del archivo XLSX
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

	if ($('#DNI').val() != '') {
		$('#button-addon2').hide();
		$('#DNI').css('border', '1px solid #dddddd');
		setTimeout(function () {
			busquedaDNI();
		}, 1000);
	}
});

function enviarRentdesk() {
	// $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	var formData = new FormData(document.getElementById('formulario-codeudor'));
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
		url: 'components/codeudor/models/insert_update.php',
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
				'index.php?component=codeudor&view=codeudor_list';
			return false;
		} else {
			$.showAlert({ title: 'Error', body: mensaje });
			return false;
		}
	});
} //function enviar

function loadCodeudor_List() {
	$(document).ready(function () {
		// Recuperar valores de los elementos del DOM de forma segura
		var dniCliente = $('#nombre_codeudor').val();

		sessionStorage.setItem('nombre_codeudor', dniCliente);
		var ajaxUrl =
			'components/codeudor/models/codeudor_list_procesa.php?' +
			'dniCodeudor=' +
			encodeURIComponent(dniCliente);

		// Comprobar si la tabla ya ha sido inicializada
		if ($.fn.DataTable.isDataTable('#codeudor')) {
			// Recargar datos sin reinicializar
			var table = $('#codeudor').DataTable();
			table.ajax.url(ajaxUrl).load();
		} else {
			// Inicializar DataTable si no está ya inicializada
			$('#codeudor').DataTable({
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
					loadArriendo_List();
				});
		}
	});
}

function avisoEliminar() {
	Swal.fire({
		title: 'Aviso',
		text: 'El codeudor se encuentra asignado a uno o mas arriendos por lo que no se puede ser eliminado',
		icon: 'warning',
	});
}

// function generarExcel(urlbase){
//     // Recuperar valores de los elementos del DOM de forma segura
// 	  var dniCliente = sessionStorage.getItem("nombre_codeudor");;

//     var ajaxUrl = "components/codeudor/models/codeudor_list_procesa_excel.php?" +
//         "dniCodeudor=" + encodeURIComponent(dniCliente);
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

		var formData = new FormData(document.getElementById('formulario-codeudor'));

		$.ajax({
			url: 'components/codeudor/models/insert_update.php',
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
					'index.php?component=codeudor&view=codeudor&token=' + token;
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
				url: 'components/codeudor/models/codeudor_list_procesa.php',
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
					url: 'components/codeudor/models/delete.php',
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
			url: 'components/codeudor/models/busca_persona.php',
			data: 'num_docu=' + num_docu + '&tipo_docu=' + tipo_docu,
			success: function (resp) {
				var retorno = resp.split('xxx,');
				var resultado = retorno[1];
				var token = retorno[3];
				var mensaje =
					'El numero de documento ingresado ya se encuentra registrado. Serás redirigido a la ficha del codeudor.';

				if (resultado == 'OK') {
					$.showAlert({ title: 'Atención', body: mensaje });
					document.location.href =
						'index.php?component=codeudor&view=codeudor&token=' + token;
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
					'components/codeudor/models/codeudor_list_procesa_prop_pago.php?token=' +
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
		url: 'components/codeudor/models/busca_lista_filtros.php',
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
	$('#DNI').prop('disabled', true);
	$('#button-addon2').hide();
	$('#DNI').css('border', '1px solid #dddddd');
	const input = document.getElementById('DNI');
	let inputPersonaToken = document.getElementById('persona');

	var dni = input.value;
	var dniBuscar = $('#DNI').val();

	if (dniBuscar !== '') {
		$.ajax({
			url: 'components/codeudor/models/busca_dni.php',
			type: 'POST',
			data: 'dni=' + dni,
			success: function (resp) {
				console.log(resp);
				var retorno = resp.split('||');
				var resultado = retorno[0];
				console.log('resultado');
				console.log(resultado);
				var mensaje = retorno[2];
				console.log(mensaje);

				if (resultado == 'ERROR') {
					///$.showAlert({ title: "Atención", body: mensaje });
					alert(
						'No existe DNI ' + dni + ' por lo se debe crear en la plataforma'
					);
					document.location.href = 'index.php?component=persona&view=persona';
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
	console.log(infoJSON[0]);
	if (infoJSON[0].id_propietario !== null && tokenUrl === null) {
		document.location.href =
			'index.php?component=codeudor&view=codeudor&token=' +
			infoJSON[0].token_cou;
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
	$('#submitCodeudor').show();
}

function eliminarCodeudor(id) {
	console.log(' id: ', id);
	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Se eliminara el rol de codeudor para este cliente',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Si',
		denyButtonText: 'No',
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: 'components/codeudor/models/elimina_rol.php?id=' + id,
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
					loadCodeudor_List();
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

function cargarListadoCodeudor() {
	//$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	$.ajax({
		url: 'components/codeudor/models/listado_codeudor.php',
		dataType: 'text',
		cache: false,
		contentType: false,
		processData: false,
	})
		.done(function (res) {
			var jsonRes = res;
			var data = JSON.parse(jsonRes);
			var tbody = $('#codeudor tbody');
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
          <a href="index.php?component=codeudor&view=codeudor&token=${elemento.tokencodeudor}" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
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
function buscarCodeudorAutocompleteGenerica(valor, tipo) {
	var codigo = document.getElementById(tipo).value;

	var caracteres = codigo.length;
	//Si por lo menos ha ingresado 3 caracteres comenzamos a autocompletar
	if (caracteres >= 3) {
		$.ajax({
			type: 'POST',
			url: 'components/codeudor/models/buscar_codeudor_autocomplete_generica.php',
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

function cargarListadoCodeudorFiltro() {
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	var dniCodeudor = $('#nombre_codeudor').val();
	var data = {
		dniCodeudor: dniCodeudor,
	};
	console.log(data);
	$.ajax({
		url: 'components/codeudor/models/listado_codeudor.php',
		dataType: 'text',
		type: 'post',
		data: data,
		cache: false,
	})
		.done(function (res) {
			if (res != 'ERROR') {
				var jsonRes = res;
				var data = JSON.parse(jsonRes);
				var tbody = $('#codeudor tbody');
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
          <a href="index.php?component=codeudor&view=codeudor&token=${elemento.tokencodeudor}" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
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
				var tbody = $('#codeudor tbody');
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

function crearCodeudor() {
	tokenPersona = $('#persona').val();

	$.ajax({
		url: 'components/codeudor/models/insert_codeudor.php', // Nombre del archivo PHP
		type: 'post',
		dataType: 'text',
		data: { tokenPersona: tokenPersona },
		success: function (response) {
			// Manejar la respuesta del servidor
			console;
			var partes = response.split('||');
			var resultado = partes[0];
			console.log('Respuesta del servidor: ', resultado);
			if (resultado == 'OK') {
				Swal.fire({
					title: 'Codeudor registrado',
					text: 'El codeudor ha sido registrado correctamente ',
					icon: 'success',
					showConfirmButton: true,
					allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
					willClose: () => {
						window.location.href =
							'index.php?component=codeudor&view=codeudor_list';
					},
				}).then((result) => {
					window.location.href =
						'index.php?component=codeudor&view=codeudor_list';
				});
			}
		},
		error: function (xhr, status, error) {
			// Manejar errores de la solicitud AJAX
			console.error('Error en la solicitud AJAX:', error);
		},
	});
	console.log('token persona ' + tokenPersona);
}
function editProp() {
	console.log('redireccionar, porq ue existe el codeudor');
}
