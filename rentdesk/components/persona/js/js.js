$(document).ready(function () {
	$('.js-example-responsive').select2({
		width: '100%',
		placeholder: 'Seleccione Tipo(s)',
	});

	//carga el rut del cliente  agregado por jhernandez
	var rutcapturado = localStorage.getItem('Rutaregistrar');
	texto =
		'Por favor completa los datos del cliente para asignarlo a la propiedad';

	if (rutcapturado) {
		$('#dni').val(rutcapturado);
		$('#textoRegistroCliente').text(texto);
	} else {
		$('#textoRegistroCliente').text('');
		$('#dni').val('');
	}
});

$(document).ready(function () {
	$('#telefonoFijo').on('input', function () {
		// Eliminar cualquier carácter no numérico
		$(this).val(
			$(this)
				.val()
				.replace(/[^0-9+\s]/g, '')
		);
	});
	$('#telefonoMovil').on('input', function () {
		// Eliminar cualquier carácter que no sea numérico, espacio o '+'
		$(this).val(
			$(this)
				.val()
				.replace(/[^0-9+\s]/g, '')
		);
	});
	$('#telefonoFijoRepresentante').on('input', function () {
		// Eliminar cualquier carácter no numérico
		$(this).val(
			$(this)
				.val()
				.replace(/[^0-9+\s]/g, '')
		);
	});
	$('#telefonoMovilRepresentante').on('input', function () {
		// Eliminar cualquier carácter que no sea numérico, espacio o '+'
		$(this).val(
			$(this)
				.val()
				.replace(/[^0-9+\s]/g, '')
		);
	});
	$('#dni').on('input', function () {
		// Eliminar cualquier carácter no numérico
		$(this).val(
			$(this)
				.val()
				.replace(/[^0-9kK-]/g, '')
		);
	});
	$('#NDocumento').on('input', function () {
		// Eliminar cualquier carácter no numérico
		$(this).val(
			$(this)
				.val()
				.replace(/[^0-9kK-]/g, '')
		);
	});

	//BRUNO
	$('#descargarExcelPersona').on('click', function (e) {
		e.preventDefault();
		// Captura el valor del input (para filtrar en el backend, si corresponde)
		var searchTerm = $('#nombre_cliente').val();
		$.ajax({
			url: 'components/persona/models/get_persona_excel.php',
			type: 'GET',
			data: { searchTerm: searchTerm },
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
				// 1) Transforma la respuesta para renombrar columnas en el Excel
				var formattedData = response.map(function (row) {
					return {
						'Nombre Completo': row.nombre_completo, // Viene de la columna "nombre_completo"
						DNI: row.dni, // Viene de la columna "dni"
						Correo: row.correo, // Viene de la columna "correo"
						'Tipo de Persona': row.tipo_persona, // Viene de la columna "tipo_persona"
						Dirección: row.direccion, // Viene de la columna "direccion"
					};
				});
				// 2) Crear la hoja (worksheet) usando los datos formateados
				var worksheet = XLSX.utils.json_to_sheet(formattedData);
				// 3) Ajustar el ancho de las columnas (opcional)
				worksheet['!cols'] = [
					{ wpx: 200 }, // Nombre Completo
					{ wpx: 100 }, // DNI
					{ wpx: 180 }, // Correo
					{ wpx: 120 }, // Tipo de Persona
					{ wpx: 200 }, // Dirección
				];
				// 4) Crear un nuevo libro de trabajo (workbook)
				var workbook = XLSX.utils.book_new();
				XLSX.utils.book_append_sheet(workbook, worksheet, 'Personas');
				// 5) Generar el archivo XLSX en un formato binario (array)
				var wbout = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
				// 6) Crear un Blob a partir del workbook
				var blob = new Blob([wbout], { type: 'application/octet-stream' });
				// 7) Crear un objeto URL para la descarga
				var url = URL.createObjectURL(blob);
				// 8) Crear un enlace temporal para forzar la descarga
				var a = document.createElement('a');
				a.href = url;
				a.download = 'personas.xlsx'; // Nombre del archivo Excel
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
});

$(document).ready(function () {
	$('.js-example-responsive .tipo-2').select2({
		width: '100%',
		placeholder: 'Seleccione Tipo(s)',
	});

	setTimeout(guardarInformacionDespuesDe3Segundos, 3000);
});

// Función para obtener valores del formulario y almacenarlos en sessionStorage después de 3 segundos
function guardarInformacionDespuesDe3Segundos() {
	// Obtener los valores del formulario
	var jsonInformacionAntigua = obtenerValoresFormulario('formulario');

	// Almacenar los valores en sessionStorage
	sessionStorage.setItem('informacionAntigua', jsonInformacionAntigua);
}

$(document).ready(function () {
	// Add change event listener to the select element
	onChangeTipoPersona();

	// $("#tipo_persona_legal").change(onChangeTipoPersona());
});

sessionStorage.removeItem('nombre_cliente');
sessionStorage.removeItem('tiposFiltro');

var personaDNI = sessionStorage.getItem('personaDNI');
sessionStorage.removeItem('personaDNI');
$(document).ready(function () {
	// Verificar si el valor existe
	if (personaDNI != '' && personaDNI != null) {
		// El valor existe en sessionStorage
		$('#dni').val(personaDNI);
		sessionStorage.setItem('DNI_desde_Propiedad', personaDNI);
	} else {
		$(
			'#nombreTitular, #rutTitular, #emailTitular, #banco, #cta-banco, #numCuenta'
		).removeAttr('required');

		// El valor no existe en sessionStorage
		//console.log("El valor NO existe en sessionStorage.");
	}
});

function onChangeTipoPersona() {
	var selectedValue = $('#tipo_persona_legal').val();
	$('#datosContacto').hide();
	$('#containerInfoPersona').hide();
	// Hide all sections

	$('#containerInfoPersonaDireccion').hide();
	$('#tipoPersona1Section').hide();
	$('#tipoPersona2Section').hide();
	$('#estadoCivil').hide();
	$('#DatosPersonaNatural').hide();
	$('#tipoPersona1Section input, #tipoPersona1Section select').prop(
		'required',
		false
	);
	$('#tipoPersona2Section input, #tipoPersona2Section select').prop(
		'required',
		false
	);
	$('#containerRepresentante').hide();
	$('#representante-juridico').hide();
	$('#containerCuenta').hide();
	// Show the section corresponding to the selected value
	if (selectedValue === '1') {
		$('#datosContacto').show(500);
		$('#containerInfoPersona').show();
		$('#containerInfoPersonaDireccion').show(500);
		$('#tipoPersona1Section').show();
		$('#estadoCivil').show();
		$('#DatosPersonaNatural').show();
		$('#tipoPersona1Section input, #tipoPersona1Section select').prop(
			'required',
			true
		);
		$('#tipoPersona2Section input, #tipoPersona2Section select').prop(
			'required',
			false
		);
		console.log('persona DNi es : ' + personaDNI);
		if (personaDNI) {
			$('#containerCuenta').show(500);
		}
	} else if (selectedValue === '2') {
		$('#datosContacto').show(500);
		$('#containerInfoPersona').show();
		$('#containerInfoPersonaDireccion').show(500);
		$('#tipoPersona2Section').show();
		$('#tipoPersona2Section input, #tipoPersona2Section select').prop(
			'required',
			true
		);
		$('#tipoPersona1Section input, #tipoPersona1Section select').prop(
			'required',
			false
		);
		$('#containerRepresentante').show();
		$('#representante-juridico').show();
		if (personaDNI) {
			$('#containerCuenta').show(500);
		}
	}
}

function avisoEliminar() {
	Swal.fire({
		title: 'Aviso',
		text: 'El cliente no se puede eliminar por que se encuentra activo en el sistema',
		icon: 'warning',
	});
}

function eliminarCliente(id) {
	console.log(' id: ', id);
	Swal.fire({
		title: '¿Estás seguro?',
		text: 'Se eliminara el cliente del sistema con todos sus roles',
		icon: 'warning',
		showDenyButton: true,
		confirmButtonText: 'Si',
		denyButtonText: 'No',
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: 'components/persona/models/elimina_rol.php?id=' + id,
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
						title: 'Cliente eliminado',
						icon: 'success',
					});
					loadCliente_List();
					return false;
				} else {
					Swal.fire({
						title: 'Problemas al eliminar Cliente',
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

function generarExcel(urlbase) {
	console.log('generarExcel');
	console.log('urlBase: ', urlbase);
	var dniCliente = sessionStorage.getItem('nombre_cliente');
	// var tiposFiltro = sessionStorage.getItem("tiposFiltro");
	var tiposFiltro = JSON.parse(sessionStorage.getItem('tiposFiltro'));
	var propietario = 0;
	var arrendatario = 0;
	var codeudor = 0;

	if (tiposFiltro != null && tiposFiltro != '') {
		tiposFiltro.forEach(function (element) {
			if (element == 'Propietario') {
				propietario = 1;
			} else if (element == 'Arrendatario') {
				arrendatario = 1;
			} else if (element == 'Codeudor') {
				codeudor = 1;
			}
		});
	}

	var ajaxUrl =
		'components/persona/models/listado_personas_procesa_excel.php?' +
		'dniCliente=' +
		encodeURIComponent(dniCliente) +
		'&propietario=' +
		encodeURIComponent(propietario) +
		'&arrendatario=' +
		encodeURIComponent(arrendatario) +
		'&codeudor=' +
		encodeURIComponent(codeudor);
	$.ajax({
		type: 'GET',
		url: ajaxUrl,
		success: function (res) {
			window.open('/upload/persona/excel/' + res, '_blank');
		},
	});
}

function enviarRentdesk() {
	// var rut =
	//   document.getElementById("numDocumento").value +
	//   "-" +
	//   document.getElementById("digitoVerificador").value;
	// var tipo = document.getElementById("tipo_documento").value;
	// var rutOk = "1";

	// $("#errorrut").html("");

	// if (tipo == "1") {
	//   rutOk = verificaRut(rut);
	// } //if(  tipo=="1"  )

	// if (rutOk == "1") {
	//   $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	var formData = new FormData(document.getElementById('formulario'));

	/*  Tomar como referencia para las alertas
  Swal.fire({
            icon: "error",
            title: "No existe DNI",
            text:", Prueba",
          }).then((result) => {
            // Redirigir a una página específica si se hace clic en 'Aceptar'
            if (result.isConfirmed) {
              alert("Persona actualizada/creada exitosamente");
            }
          });
*/

	$.ajax({
		url: 'components/persona/models/insert_update.php',
		type: 'post',
		dataType: 'html',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
	}).done(function (res) {
		console.log('Respuesta' + res);
		var retorno = res.split(',xxx,');
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var token = retorno[3];
		var DNI_desde_Propiedad = sessionStorage.getItem('DNI_desde_Propiedad');
		if (resultado == 'OK') {
			// $.showAlert({ title: "Atención", body: mensaje });
			//document.location.href = "index.php?component=persona&view=persona_list";
			Swal.fire({
				title: alertaJSON.clientesCorrecto.titulo,
				text: alertaJSON.clientesCorrecto.mensaje,
				icon: alertaJSON.clientesCorrecto.icono,
				showConfirmButton: true,
				allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
				willClose: () => {
					// Redireccionar a otra página cuando la alerta se cierre
					//ifpara validar si redirecciono a propietario
					if (DNI_desde_Propiedad) {
						window.location.href =
							'index.php?component=propietario&view=propietario';
					} else {
						window.location.href =
							'index.php?component=propietario&view=propietario';
					}
				},
			}).then((result) => {
				// Verificar si el usuario confirmó la alerta
				if (result.isConfirmed) {
					// Redireccionar a otra página si se confirma la alerta
					if (DNI_desde_Propiedad) {
						window.location.href =
							'index.php?component=propietario&view=propietario';
					} else {
						window.location.href =
							'index.php?component=propietario&view=propietario';
					}
				}
			});
			return false;
		} else {
			//alert("No se logro crear persona " + mensaje);
			//$.showAlert({ title: "Error", body: mensaje });
			Swal.fire({
				title: 'La persona no pudo ser registrado',
				text: 'La persona no se pudo registrar correctamente',
				icon: 'warning',
				showConfirmButton: true,
				allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
				willClose: () => {
					// Redireccionar a otra página cuando la alerta se cierre
					window.location.href =
						'index.php?component=persona&view=persona_list';
				},
			}).then((result) => {
				// Verificar si el usuario confirmó la alerta
				if (result.isConfirmed) {
					// Redireccionar a otra página si se confirma la alerta
					window.location.href =
						'index.php?component=persona&view=persona_list';
				}
			});
			return false;
		}
	});
	// } else {
	//   $.showAlert({ title: "Atención", body: "Debe ser un Rut con su dígito verificador válido." });
	// }
}

function enviarBusquedaPersona() {
	// var rut =
	//   document.getElementById("numDocumento").value +
	//   "-" +
	//   document.getElementById("digitoVerificador").value;
	// var tipo = document.getElementById("tipo_documento").value;
	// var rutOk = "1";

	$('#errorrut').html('');

	rutOk = verificaRut(rut);

	if (rutOk == '1') {
		$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

		var formData = new FormData(document.getElementById('formulario'));

		$.ajax({
			url: 'components/persona/models/insert_update.php',
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
					'index.php?component=persona&view=persona&token=' + token;
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

		var formData = new FormData(document.getElementById('formulario'));

		$.ajax({
			url: 'components/persona/models/insert_update.php',
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
					'index.php?component=persona&view=persona&token=' + token;
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
}

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
				url: 'components/persona/models/propietario_list_procesa.php',
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
					url: 'components/persona/models/delete.php',
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
			url: 'components/persona/models/busca_persona.php',
			data: 'num_docu=' + num_docu + '&tipo_docu=' + tipo_docu,
			success: function (resp) {
				var retorno = resp.split('|');
				var resultado = retorno[1];
				var token = retorno[2];

				if (resultado == 'false') {
					Swal.fire({
						title: 'Atención',
						text: 'El numero de documento ingresado ya se encuentra registrado. Serás redirigido a la ficha de la persona.',
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Aceptar',
					}).then((result) => {
						if (result.isConfirmed) {
							document.location.href =
								'index.php?component=persona&view=persona&token=' + token;
						}
					});
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
					'components/persona/models/propietario_list_procesa_prop_pago.php?token=' +
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

document.addEventListener('DOMContentLoaded', function () {
	conteoInput('dni', 'cuentaDni');
	conteoInput('giro', 'cuentaGiro');
	conteoInput('nombreFantasia', 'cuentaFantasia');
	conteoInput('razonSocial', 'cuentaRazon');
	conteoInput('nombre', 'cuentaNombre');
	conteoInput('apellidoPat', 'cuentaPaterno');
	conteoInput('apellidoMat', 'cuentaMaterno');
	conteoInput('telefonoFijo', 'cuentaTelefonoFijo');
	conteoInput('telefonoMovil', 'cuentaMovil');
	conteoInput('correoElectronico', 'cuentaCorreo');
	conteoInput('direccion', 'cuentaDireccion');
	conteoInput('nroComplemento', 'cuentaNumero');
	conteoInput('complemento', 'cuentaComplemento');
	conteoInput('InformacionAdicional', 'cuentaInformacionAdicional');
});

function valdidarTipoDni() {
	var valorSeleccionado = $('#tipo_documento option:selected').text();
	if (valorSeleccionado == 'RUT') {
		$('#dni').attr('oninput', "checkRut(this);conteoInput('dni','cuentaDni');");
		var valorDNI = document.getElementById('dni').value;
		if (valorDNI === '') {
		} else {
			checkRutFormat('dni', true);
		}
		// var inputpoto = checkRut("77777777-7");
		//console.log(inputpoto);
	} else {
		$('#dni').removeAttr('oninput');
		checkRutFormat('dni', false);
		$('#tu_input').on('input', function () {
			// No hagas nada
		});
		//$("#dni").attr("oninput", "conteoInput('dni','cuentaDni');");
	}
}

function valdidarTipoDni_repre() {
	var valorSeleccionado = $('#tipo_documento_repre option:selected').text();
	if (valorSeleccionado == 'RUT') {
		$('#NDocumento').attr('oninput', 'checkRut(this)');
		var valorDNI = document.getElementById('NDocumento').value;
		if (valorDNI === '') {
		} else {
			checkRutFormat('NDocumento', true);
		}
		// var inputpoto = checkRut("77777777-7");
		//console.log(inputpoto);
	} else {
		$('#NDocumento').removeAttr('oninput');
		checkRutFormat('NDocumento', false);
		$('#tu_input').on('input', function () {
			// No hagas nada
		});
		//$("#dni").attr("oninput", "conteoInput('dni','cuentaDni');");
	}
}

var currentStep = 1;
var updateProgressBar;

function displayStep(stepNumber) {
	if (stepNumber >= 1 && stepNumber <= 3) {
		$('.step-' + currentStep).hide();
		$('.step-' + stepNumber).show();
		currentStep = stepNumber;
		updateProgressBar();
	}
}

$(document).ready(function () {
	$('#multi-step-form').find('.step').slice(1).hide();

	$('.next-step').click(function () {
		////////////Codigo añadido José Barrera Validar que pueda seguir

		var tipo_doc = $('#tipo_documento_repre').val();
		var check;
		if (tipo_doc == '' || NDocumento == '') {
			Swal.fire({
				title: 'Complete los datos',
				text: 'Por favor completes los datos para continuar',
				icon: 'info',
			});
		} else {
			var valorSeleccionado = $('#tipo_documento_repre option:selected').text();
			// var check;
			if (valorSeleccionado == 'RUT') {
				check = checkRutFormat('NDocumento', true);
			} else {
				check = true;
			}

			if (check == true) {
				if (currentStep < 3) {
					$('.step-' + currentStep).addClass(
						'animate__animated animate__fadeOutLeft'
					);
					currentStep++;
					setTimeout(function () {
						$('.step')
							.removeClass('animate__animated animate__fadeOutLeft')
							.hide();
						$('.step-' + currentStep)
							.show()
							.addClass('animate__animated animate__fadeInRight');
						updateProgressBar();
					}, 500);
				}
			} else {
				$('#NDocumento')[0].setCustomValidity('Rut Invalido');
				$('#NDocumento')[0].reportValidity();
			}
		}
	});

	$('.prev-step').click(function () {
		if (currentStep > 1) {
			$('.step-' + currentStep).addClass(
				'animate__animated animate__fadeOutRight'
			);
			currentStep--;
			setTimeout(function () {
				$('.step')
					.removeClass('animate__animated animate__fadeOutRight')
					.hide();
				$('.step-' + currentStep)
					.show()
					.addClass('animate__animated animate__fadeInLeft');
				updateProgressBar();
			}, 500);
		}
	});

	updateProgressBar = function () {
		var progressPercentage = ((currentStep - 1) / 1) * 100;
		$('.progress-bar').css('width', progressPercentage + '%');
	};
});

function BuscarPersona() {
	var dni = $('#NDocumento').val();

	$.ajax({
		url: 'components/persona/models/busca_dni.php',
		type: 'POST',
		data: 'dni=' + dni,
		success: function (resp) {
			var retorno = resp.split('||');
			var resultado = retorno[0];
			var mensaje = retorno[2];

			if (resultado == 'ERROR') {
				$('#dniRepresentante').text(dni);
				$('#nuevoCliente').val('true');
				$('#dniRepre').val(dni);

				$('#LabelnombreRepresentante').html('');
				$('#LabelnombreRepresentante').hide();
				$('#nombreRepresentante').val('');
				$('#nombreRepresentante').show();

				$('#LabelapellidoPateRepresentante').html('');
				$('#LabelapellidoPateRepresentante').hide();
				$('#apellidoPateRepresentante').val('');
				$('#apellidoPateRepresentante').show();

				$('#LabelapellidoMateRepresentante').html('');
				$('#LabelapellidoMateRepresentante').hide();
				$('#apellidoMateRepresentante').val('');
				$('#apellidoMateRepresentante').show();

				$('#LabeltelefonoFijoRepresentante').html('');
				$('#LabeltelefonoFijoRepresentante').hide();
				$('#telefonoFijoRepresentante').val('');
				$('#telefonoFijoRepresentante').show();

				$('#LabeltelefonoMovilRepresentante').html('');
				$('#LabeltelefonoMovilRepresentante').hide();
				$('#telefonoMovilRepresentante').val('');
				$('#telefonoMovilRepresentante').show();

				$('#LabelcorreoElectronicoRepresentante').html('');
				$('#LabelcorreoElectronicoRepresentante').hide();
				$('#correoElectronicoRepresentante').val('');
				$('#correoElectronicoRepresentante').show();

				$('#LabelpaisRepresentante').html('');
				$('#LabelpaisRepresentante').hide();
				$('#paisRepresentante').val('');
				$('#paisRepresentante').show();

				$('#LabelregionRepresentante').html('');
				$('#LabelregionRepresentante').hide();
				$('#regionRepresentante').val('');
				$('#regionRepresentante').show();

				$('#LabelcomunaRepresentante').html('');
				$('#LabelcomunaRepresentante').hide();
				$('#comunaRepresentante').val('');
				$('#comunaRepresentante').show();

				$('#LabeldireccionRepresentante').html('');
				$('#LabeldireccionRepresentante').hide();
				$('#direccionRepresentante').val('');
				$('#direccionRepresentante').show();

				$('#LabelnumeroRepresentante').html('');
				$('#LabelnumeroRepresentante').hide();
				$('#numeroRepresentante').val('');
				$('#numeroRepresentante').show();

				$('#hiddenRepre').val('');
				$('#hiddenToken').val('');
			} else {
				//$("#modalRepresentante").modal("hide");
				//$("#addRepresentante").hide();
				var personaJson = retorno[3];
				var personaJson = JSON.parse(personaJson);
				$.each(personaJson, function (index, persona) {
					// Crear una fila de tabla para cada persona

					$('#dniRepresentante').html(dni);
					$('#dniRepre').val(dni);

					$('#LabelnombreRepresentante').html(persona.nombres);
					$('#LabelnombreRepresentante').show();
					$('#nombreRepresentante').val(persona.nombres);
					$('#nombreRepresentante').hide();

					$('#LabelapellidoPateRepresentante').html(persona.apellido_paterno);
					$('#LabelapellidoPateRepresentante').show();
					$('#apellidoPateRepresentante').val(persona.apellido_paterno);
					$('#apellidoPateRepresentante').hide();

					$('#LabelapellidoMateRepresentante').html(persona.apellido_materno);
					$('#LabelapellidoMateRepresentante').show();
					$('#apellidoMateRepresentante').val(persona.apellido_materno);
					$('#apellidoMateRepresentante').hide();

					$('#LabeltelefonoFijoRepresentante').html(persona.telefono_fijo);
					$('#LabeltelefonoFijoRepresentante').show();
					$('#telefonoFijoRepresentante').val(persona.telefono_fijo);
					$('#telefonoFijoRepresentante').hide();

					$('#LabeltelefonoMovilRepresentante').html(persona.telefono_movil);
					$('#LabeltelefonoMovilRepresentante').show();
					$('#telefonoMovilRepresentante').val(persona.telefono_movil);
					$('#telefonoMovilRepresentante').hide();

					$('#LabelcorreoElectronicoRepresentante').html(
						persona.correo_electronico
					);
					$('#LabelcorreoElectronicoRepresentante').show();
					$('#correoElectronicoRepresentante').val(persona.correo_electronico);
					$('#correoElectronicoRepresentante').hide();

					$('#LabelpaisRepresentante').html(persona.pais);
					$('#LabelpaisRepresentante').show();
					$('#paisRepresentante').val(persona.pais);
					$('#paisRepresentante').hide();

					$('#LabelregionRepresentante').html(persona.region);
					$('#LabelregionRepresentante').show();
					$('#regionRepresentante').val(persona.region);
					$('#regionRepresentante').hide();

					$('#LabelcomunaRepresentante').html(persona.comuna);
					$('#LabelcomunaRepresentante').show();
					$('#comunaRepresentante').val(persona.comuna);
					$('#comunaRepresentante').hide();

					$('#LabeldireccionRepresentante').html(persona.direccion);
					$('#LabeldireccionRepresentante').show();
					$('#direccionRepresentante').val(persona.direccion);
					$('#direccionRepresentante').hide();

					$('#LabelnumeroRepresentante').html(persona.numero);
					$('#LabelnumeroRepresentante').show();
					$('#numeroRepresentante').val(persona.numero);
					$('#numeroRepresentante').hide();
					$('#nuevoCliente').val('false');
					//$("#rLegal").css("display", "");

					$('#hiddenRepre').val(persona.id_persona);
					$('#hiddenToken').val(persona.token);
				});
				console.log(personaJson);
			}
		},
	});
}

function BuscarPersonaDni(dni) {
	var dni = dni;
	console.log(dni);
	$.ajax({
		url: 'components/propietario/models/busca_dni.php',
		type: 'POST',
		data: 'dni=' + dni,
		success: function (resp) {
			var retorno = resp.split('||');
			var resultado = retorno[0];
			var mensaje = retorno[2];

			if (resultado == 'ERROR') {
				$('#dniRepresentante').text(dni);

				$('#dniRepre').val(dni);
			} else {
				$('#modalRepresentante').modal('hide');
				$('#addRepresentante').hide();
				var personaJson = retorno[3];
				var personaJson = JSON.parse(personaJson);
				$.each(personaJson, function (index, persona) {
					// Crear una fila de tabla para cada persona
					var fila =
						"<tr id='rowRepresentante'>" +
						'<td>' +
						persona.nombres +
						' ' +
						persona.apellido_paterno +
						' ' +
						persona.apellido_materno +
						'</td>' +
						'<td>' +
						persona.dni +
						'</td>' +
						'<td>' +
						persona.correo_electronico +
						'</td>' +
						'<td>' +
						persona.direccion +
						' ' +
						persona.numero +
						', ' +
						persona.comuna +
						', ' +
						persona.region +
						'</td>' +
						'<td>---</td>' +
						'</tr > ';

					// Agregar la fila a la tabla

					$('#rLegal tbody').append(fila);
					$('#rLegal').css('display', '');
				});
				console.log(personaJson);
			}
		},
	});
}

function selectUbicacion(tipo) {
	if (tipo == 'pais') {
		var idPais = $('#paisRepresentante').val();
		$.ajax({
			url: 'components/persona/models/busca_region.php',
			type: 'POST',
			dataType: 'json',
			data: { idPais: idPais },
			cache: false,
			success: function (data) {
				if (data != null) {
					$('#regionRepresentante').empty();

					// Habilitar el select
					$('#regionRepresentante').prop('disabled', false);
					// Añadir opción por defecto al primer select
					$('#regionRepresentante').append(
						$('<option>', {
							value: '',
							text: 'Seleccione una Región',
						})
					);
					// Iterar sobre los datos recibidos y agregar opciones al select
					$.each(data, function (key, value) {
						$('#regionRepresentante').append(
							$('<option>', {
								value: value.id,
								text: value.nombre,
							})
						);
					});
				} else {
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				// Manejar errores si es necesario
				console.log('error');
			},
		});
	}
	if (tipo == 'region') {
		var idRegion = $('#regionRepresentante').val();
		$.ajax({
			url: 'components/persona/models/busca_region.php',
			type: 'POST',
			dataType: 'json',
			data: { idRegion: idRegion },
			cache: false,
			success: function (data) {
				if (data != null) {
					$('#comunaRepresentante').empty();

					// Habilitar el select
					$('#comunaRepresentante').prop('disabled', false);
					// Añadir opción por defecto al primer select
					$('#comunaRepresentante').append(
						$('<option>', {
							value: '',
							text: 'Seleccione una Comuna',
						})
					);
					// Iterar sobre los datos recibidos y agregar opciones al select
					$.each(data, function (key, value) {
						$('#comunaRepresentante').append(
							$('<option>', {
								value: value.id,
								text: value.nombre,
							})
						);
					});
				} else {
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				// Manejar errores si es necesario
				console.log('error');
			},
		});
	}
}

function guardarRepresentante() {
	var dniRepre = $('#dniRepre').val();
	var tipoDni = 1;
	var EstadoCivil = 1;
	var nombreRepresentante = $('#nombreRepresentante').val();
	var apellidoPateRepresentante = $('#apellidoPateRepresentante').val();
	var apellidoMateRepresentante = $('#apellidoMateRepresentante').val();
	var telefonoFijoRepresentante = $('#telefonoFijoRepresentante').val();
	var telefonoMovilRepresentante = $('#telefonoMovilRepresentante').val();
	var correoElectronicoRepresentante = $(
		'#correoElectronicoRepresentante'
	).val();
	var paisRepresentante = $('#paisRepresentante').val();
	var regionRepresentante = $('#regionRepresentante').val();
	var comunaRepresentante = $('#comunaRepresentante').val();
	var direccionRepresentante = $('#direccionRepresentante').val();
	var numeroRepresentante = $('#numeroRepresentante').val();
	var hiddenRepre = $('#hiddenRepre').val();
	var hiddentoken = $('#hiddenToken').val();
	var nombreregionRepresentante = $(
		'#regionRepresentante option:selected'
	).text();
	var nombrecomunaRepresentante = $(
		'#comunaRepresentante option:selected'
	).text();
	var tipo_documento_repre = $('#tipo_documento_repre').val();

	if (nombreregionRepresentante == '') {
		nombreregionRepresentante = $('#LabelregionRepresentante').text();
	}
	if (nombrecomunaRepresentante == '') {
		nombrecomunaRepresentante = $('#LabelcomunaRepresentante').text();
	}

	var data = {
		tipo_documento_repre: tipo_documento_repre,
		dniRepre: dniRepre,
		tipoDni: tipoDni,
		EstadoCivil: EstadoCivil,
		nombreRepresentante: nombreRepresentante,
		apellidoPateRepresentante: apellidoPateRepresentante,
		apellidoMateRepresentante: apellidoMateRepresentante,
		telefonoFijoRepresentante: telefonoFijoRepresentante,
		telefonoMovilRepresentante: telefonoMovilRepresentante,
		correoElectronicoRepresentante: correoElectronicoRepresentante,
		paisRepresentante: paisRepresentante,
		regionRepresentante: regionRepresentante,
		comunaRepresentante: comunaRepresentante,
		direccionRepresentante: direccionRepresentante,
		numeroRepresentante: numeroRepresentante,
	};
	var actionItem = `<td>
      <div class='d-flex' style='gap: .5rem;'>
        <button type='button' class='btn btn-info m-0' style='padding: .5rem;' aria-label='Editar' title='Editar' onclick="editRepre('${hiddentoken}')">
           <i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i>
        </button>
        <button type='button' class='btn btn-danger m-0' style='padding: .5rem;' title='Eliminar' onclick='eliminarRepresentante()'>
          <i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
        </button>
      </div>
    </td>`;
	var fila =
		"<tr id='rowRepresentante'>" +
		'<td>' +
		nombreRepresentante +
		' ' +
		apellidoPateRepresentante +
		' ' +
		apellidoMateRepresentante +
		'</td>' +
		'<td>' +
		dniRepre +
		'</td>' +
		'<td>' +
		correoElectronicoRepresentante +
		'</td>' +
		'<td>' +
		direccionRepresentante +
		' ' +
		numeroRepresentante +
		', ' +
		nombreregionRepresentante +
		', ' +
		nombrecomunaRepresentante +
		'</td>' +
		actionItem +
		'</tr > ';

	// Agregar la fila a la tabla

	if (hiddenRepre != '') {
		$('#hiddenRepresentante').val(hiddenRepre);
		$('#rLegal tbody').append(fila);
		$('#rLegal').css('display', '');
		$('#modalRepresentante').modal('hide');
		$('#addRepresentante').hide();
	} else {
		if (
			nombreRepresentante == '' ||
			paisRepresentante == '' ||
			regionRepresentante == '' ||
			comunaRepresentante == '' ||
			direccionRepresentante == ''
		) {
			Swal.fire({
				title: 'Complete los datos',
				text: 'Por favor completes los datos para continuar',
				icon: 'info',
			});
		} else {
			$.ajax({
				url: 'components/persona/models/insert_representante.php', // Nombre del archivo PHP
				method: 'POST', // Método HTTP
				data: data, // Datos a enviar
				dataType: 'text', // Tipo de datos esperados en la respuesta
				success: function (response) {
					// Manejar la respuesta del servidor
					$('#hiddenRepresentante').val(response);
					console.log('Respuesta del servidor: ', response);
					$('#rLegal tbody').append(fila);
					$('#rLegal').css('display', '');
					$('#modalRepresentante').modal('hide');
					$('#addRepresentante').hide();
				},
				error: function (xhr, status, error) {
					// Manejar errores de la solicitud AJAX
					console.error('Error en la solicitud AJAX:', error);
				},
			});
		}
	}
}

function guardarCliente() {
	var tipo_persona_legal = $('#tipo_persona_legal').val();
	var hiddenRepresentante = $('#hiddenRepresentante').val();
	var token = $('#tokenPropiedad').val();

	if (tipo_persona_legal == 2 && hiddenRepresentante == '') {
		Swal.fire({
			title: 'Atención',
			text: 'Es necesario el representante legal si la persona es jurídica',
			icon: 'warning',
			confirmButtonText: 'Entendido',
		});
	} else {
		var formData = new FormData(document.getElementById('formulario'));
		// Crea un objeto JSON vacío
		var jsonObject = {};

		// Itera sobre cada entrada en el FormData y agrega los datos al objeto JSON
		formData.forEach(function (value, key) {
			jsonObject[key] = value;
		});

		// Convierte el objeto JSON a una cadena JSON
		var jsonString = JSON.stringify(jsonObject);

		$.ajax({
			url: 'components/persona/models/insert_cliente.php',
			type: 'post',
			dataType: 'text',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
		})
			.done(function (res) {
				console.log(res);
				var partes = res.split('||');
				var resultado = partes[0];

				//carga el rut del cliente
				var rutcapturado = localStorage.getItem('Rutaregistrar');

				if (rutcapturado) {
					window.location.href =
						'index.php?component=persona&view=persona_list';
				} else {
					if (resultado == 'OK') {
						///Guarda Historial
						var jsonInformacionNueva = obtenerValoresFormulario('formulario');
						registroHistorial(
							'Crear',
							'',
							jsonInformacionNueva,
							'Cliente',
							partes[1],
							'0'
						);

						Swal.fire({
							title: alertaJSON.clientesCorrecto.titulo,
							text: alertaJSON.clientesCorrecto.mensaje,
							icon: alertaJSON.clientesCorrecto.icono,
							showConfirmButton: true,
							allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
							willClose: () => {
								// Redireccionar a otra página cuando la alerta se cierre
								//ifpara validar si redirecciono a propietario
								if (personaDNI != null) {
									sessionStorage.setItem('dniParaPropiedad', personaDNI);
									window.location.href =
										'index.php?component=propiedad&view=propiedad';
								} else {
									window.location.href =
										'index.php?component=persona&view=persona_list';
								}
							},
						}).then((result) => {
							// Verificar si el usuario confirmó la alerta
							if (result.isConfirmed) {
								// Redireccionar a otra página si se confirma la alerta
								if (personaDNI != null) {
									sessionStorage.setItem('dniParaPropiedad', personaDNI);
									window.location.href =
										'index.php?component=propiedad&view=propiedad';
								} else {
									window.location.href =
										'index.php?component=persona&view=persona_list';
								}
							}
						});

						// PASO ID FORMULARIO PARA OBTENER VALORES ANTES INSERT
					} else {
						Swal.fire({
							title: 'El Cliente ya se encuentra registrado',
							text: 'Se le redireccionara para que pueda ver y modificar al cliente ingresado',
							icon: 'info',
							showConfirmButton: true,
							allowOutsideClick: false, // Evita que el usuario cierre haciendo clic fuera del cuadro
							willClose: () => {
								window.location.href =
									'index.php?component=persona&view=persona&token=' + res;
							},
						}).then((result) => {
							window.location.href =
								'index.php?component=persona&view=persona&token=' + res;
						});
					}
				}
			})
			.fail(function (jqXHR, textStatus, errorThrown) {});
	}
}

function eliminarRepresentante() {
	$('#tipo_documento_repre').val('');
	$('#NDocumento').val('');
	$('#backRepre').click();
	$('#hiddenRepresentante').val('');
	$('#rLegal').hide();
	$('#addRepresentante').show('');
	$('#rowRepresentante').remove();
}

function editRepre(value) {
	// Lógica para mostrar Sweet Alert
	Swal.fire({
		title: '¿Desea modificar el usuario?',
		text: 'Si acepta, saldrá de esta página.',
		icon: 'question',
		// Cambia el orden de los botones aquí
		showCancelButton: true,
		confirmButtonText: 'Aceptar',
		cancelButtonText: 'Volver',
	}).then((result) => {
		if (result.isConfirmed) {
			// Redireccionar o realizar acción para modificar el usuario
			window.location.href =
				'index.php?component=persona&view=persona&token=' + value;
		} else {
			// Cerrar la página actual
			window.close();
		}
	});
}

function editarCliente() {
	var tipo_persona_legal = $('#tipo_persona_legal').val();
	var hiddenRepresentante = $('#hiddenRepresentante').val();
	if (tipo_persona_legal == 2 && hiddenRepresentante == '') {
		Swal.fire({
			title: 'Atención',
			text: 'Es necesario el representante legal si la persona es jurídica',
			icon: 'warning',
			confirmButtonText: 'Entendido',
		});
	} else {
		var formData = new FormData(document.getElementById('formulario'));
		// Crea un objeto JSON vacío
		var jsonObject = {};

		// Itera sobre cada entrada en el FormData y agrega los datos al objeto JSON
		formData.forEach(function (value, key) {
			jsonObject[key] = value;
		});

		console.log(formData);

		// Convierte el objeto JSON a una cadena JSON
		var jsonString = JSON.stringify(jsonObject);

		$.ajax({
			url: 'components/persona/models/update_cliente.php',
			type: 'post',
			dataType: 'text',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
		})
			.done(function (res) {
				console.log('asasas' + res);
				var retorno = res.split('||');
				var resultado = retorno[0];
				var jsonInformacioantigua = capturarInformacionAntigua();
				var jsonInformacionNueva = obtenerValoresFormulario('formulario');

				registroHistorial(
					'Modificar',
					jsonInformacioantigua,
					jsonInformacionNueva,
					'Cliente',
					retorno[1],
					'0'
				);
				if (resultado == 'OK') {
					Swal.fire({
						icon: 'success',
						title: 'Actualización Exitosa',
						text: 'Todos los datos se han actualizado correctamente.',
						showConfirmButton: true,
						allowOutsideClick: false, // No permite cerrar la alerta haciendo clic fuera de ella
						allowEscapeKey: false, // No permite cerrar la alerta presionando la tecla "Esc"
					}).then((result) => {
						if (result.isConfirmed) {
						}
					});
				}
			})
			.fail(function (jqXHR, textStatus, errorThrown) {});
	}
}

function loadCliente_List() {
	$(document).ready(function () {
		// Recuperar valores de los elementos del DOM de forma segura
		var dniCliente = $('#nombre_cliente').val();
		var tiposFiltro = $('#tiposFiltro').val();
		sessionStorage.setItem('tiposFiltro', JSON.stringify(tiposFiltro));
		sessionStorage.setItem('nombre_cliente', dniCliente);

		var propietario = 0;
		var arrendatario = 0;
		var codeudor = 0;
		tiposFiltro.forEach(function (element) {
			if (element == 'Propietario') {
				propietario = 1;
			} else if (element == 'Arrendatario') {
				arrendatario = 1;
			} else if (element == 'Codeudor') {
				codeudor = 1;
			}
		});

		var ajaxUrl =
			'components/persona/models/listado_personas_procesa.php?' +
			'dniCliente=' +
			encodeURIComponent(dniCliente) +
			'&propietario=' +
			encodeURIComponent(propietario) +
			'&arrendatario=' +
			encodeURIComponent(arrendatario) +
			'&codeudor=' +
			encodeURIComponent(codeudor);

		// Comprobar si la tabla ya ha sido inicializada
		if ($.fn.DataTable.isDataTable('#clientes')) {
			// Recargar datos sin reinicializar
			var table = $('#clientes').DataTable();
			table.ajax.url(ajaxUrl).load();
		} else {
			// Inicializar DataTable si no está ya inicializada
			$('#clientes').DataTable({
				order: [[0, 'desc']],
				processing: true,
				serverSide: true,
				lengthMenu: [
					[10, 25, 50, 100, 5000],
					[10, 25, 50, 100, 'Todos'],
				],
				columnDefs: [{ orderable: false, targets: [0, 1, 2, 3, 4, 5, 6, 7] }],
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
						text: '<i class="fas fa-file-excel"></i> Descargar Excel',
						title: 'Listado de Clientes',
						className: 'btn btn-success',
						customize: function (xlsx) {
							var sheet = xlsx.xl.worksheets['sheet1.xml'];

							// Eliminar la columna de acciones en el Excel
							const columnIndexToRemove = 7; // Ajusta este índice según la posición de la columna de acciones
							$(
								'row c[r^="' +
									String.fromCharCode(65 + columnIndexToRemove) +
									'"]'
							).remove(); // Elimina todas las celdas de esa columna
							$(sheet)
								.find(
									'row c[r^="' +
										String.fromCharCode(65 + columnIndexToRemove) +
										'"]'
								)
								.remove(); // Elimina todas las celdas de esa columna
						},

						format: {
							body: function (data, row, column, node) {
								// Asegurarse de convertir cada dato a mayúsculas y limpiar links
								let cleanData = $(node).text().toUpperCase();
								return cleanData;
							},
						},
						// <!-- actualizacion de generar excel -->
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
					loadCliente_List(); // Asegúrate de llamar a la función correcta
				});
		}
	});
}
/*
function CargarListadoPersonas() {
  //$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
  $.ajax({
    url: "components/persona/models/listado_personas.php",
    dataType: "text",
    cache: false,
    contentType: false,
    processData: false,
  })
    .done(function (res) {
      var jsonRes = res;
      var data = JSON.parse(jsonRes);
      var tbody = $("#clientes tbody");
      tbody.empty();
      data.forEach(function (elemento) {
        var newRow = $("<tr>");
        if (elemento.id_tipo_persona == 1) {
          var nombreCliente =
            elemento.nombres +
            " " +
            elemento.apellido_paterno +
            " " +
            elemento.apellido_materno;
        } else {
          var nombreCliente = elemento.razon_social;
        }
        newRow.append("<td>" + nombreCliente + "</td>");
        newRow.append("<td>" + elemento.dni + "</td>");
        newRow.append("<td>" + elemento.correo_electronico + "</td>");
        newRow.append("<td>" + elemento.tipo_persona + "</td>");
        newRow.append(
          "<td>" +
            elemento.direccion +
            " " +
            elemento.numero +
            ", " +
            elemento.comuna +
            " " +
            elemento.region +
            ",  " +
            elemento.pais +
            "</td>"
        );

        newRow.append("<td>-</td>");
        var roles = "<div>-Cliente</div>";
        if (elemento.tokenpropietario != null) {
          roles = roles + "<div>-Propietario</div>";
        }
        if (elemento.tokenarrendatario != null) {
          roles = roles + "<div>-Arrendatario</div>";
        }
        if (elemento.tokencodeudor != null) {
          roles = roles + "<div>-Codeudor</div>";
        }
        newRow.append("<td>" + roles + "</td>");
        newRow.append(`
        <td>
        <div class="d-flex" style="gap: .5rem;">
          <a href="index.php?component=persona&view=persona&token=${elemento.token}" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
           <i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
          </a>
          <button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
           <i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
          </button>
        </div>
        </td>`);
        newRow.append("</tr>");
        tbody.append(newRow);
      });
    })
    .fail(function (jqXHR, textStatus, errorThrown) {});
}
*/
function cargarListadoPersonasFiltro() {
	var dniCliente = $('#nombre_cliente').val();
	var tiposFiltro = $('#tiposFiltro').val();
	var propietario = 0;
	var arrendatario = 0;
	var codeudor = 0;
	tiposFiltro.forEach(function (element) {
		if (element == 'Propietario') {
			propietario = 1;
		} else if (element == 'Arrendatario') {
			arrendatario = 1;
		} else if (element == 'Codeudor') {
			codeudor = 1;
		}
	});
	var data = {
		dniCliente: dniCliente,
		tiposFiltro: tiposFiltro,
		propietario: propietario,
		arrendatario: arrendatario,
		codeudor: codeudor,
	};

	$.ajax({
		url: 'components/persona/models/listado_personas.php',
		dataType: 'text',
		type: 'post',
		data: data,
		cache: false,
	})
		.done(function (res) {
			if (res != 'ERROR') {
				var jsonRes = res;
				var data = JSON.parse(jsonRes);
				var tbody = $('#clientes tbody');
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
					newRow.append('<td>' + elemento.dni + '</td>');
					newRow.append('<td>' + elemento.correo_electronico + '</td>');
					newRow.append('<td>' + elemento.tipo_persona + '</td>');
					newRow.append(
						'<td>' +
							elemento.direccion +
							' ' +
							elemento.numero +
							', ' +
							elemento.comuna +
							' ' +
							elemento.region +
							',  ' +
							elemento.pais +
							'</td>'
					);

					newRow.append('<td>-</td>');
					var roles = '<div>-Cliente</div>';
					if (elemento.tokenpropietario != null) {
						roles = roles + '<div>-Propietario</div>';
					}
					if (elemento.tokenarrendatario != null) {
						roles = roles + '<div>-Arrendatario</div>';
					}
					if (elemento.tokencodeudor != null) {
						roles = roles + '<div>-Codeudor</div>';
					}
					newRow.append('<td>' + roles + '</td>');
					newRow.append(`
        <td>
        <div class="d-flex" style="gap: .5rem;">
          <a href="index.php?component=persona&view=persona&token=${elemento.token}" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
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
				var tbody = $('#clientes tbody');
				tbody.empty();
				var newRow = $('<tr>');
				newRow.append(
					"<td colspan='8'><p style='text-align:center'>Ningun registro coincide con la busqueda</p></td>"
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
				console.log;
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

/*
function addCuenta() {
  var nombreTitular = $("#nombreTitular").val();
  var rutTitular = $("#rutTitular").val();
  var emailTitular = $("#emailTitular").val();
  var banco = $("#banco").val();
  var nameBanco = $("#banco").text();
  var ctabanco = $("#cta-banco").val();
  var nameCtaBanco = $("#cta-banco").text();

  if (
    nombreTitular == "" ||
    rutTitular == "" ||
    emailTitular == "" ||
    banco == "" ||
    ctabanco == ""
  ) {
    Swal.fire({
      title: "Complete lo datos",
      text: "Porfavor complete los datos de la cuenta ",
      icon: "warning",
    });
  }
}
*/
