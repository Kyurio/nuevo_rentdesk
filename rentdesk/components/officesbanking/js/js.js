$(document).ready(function () {
	// Cargar los datos en la tabla
	CargarListadoArchivos();
	CargarLiquidacionsProcesadas();

	// Variable para evitar ejecuciones duplicadas
	let isMarkingAll = false;

	// Evento para el botón "Marcar Todos"
	$('#marcarTodosCierres')
		.off('click')
		.on('click', function () {
			if (!isMarkingAll) {
				isMarkingAll = true; // Marcar que estamos en la acción de marcar todos
				$('.switchCheques')
					.each(function () {
						$(this).attr('checked', true); // Añade el atributo checked
					})
					.change(); // Dispara el evento de cambio aquí

				isMarkingAll = false; // Restablecer el flag
			}
		});

	// Evento para el botón "Desmarcar Todos"
	$('#DesmarcarTodosCierres')
		.off('click')
		.on('click', function () {
			$('.switchCheques').removeAttr('checked').change(); // Elimina el atributo checked
		});

	$(document).on('click', '.btn-liquidaciones-download', function () {
		const archivo_officebanking = $(this).data('officebanking');
		const tipo = $(this).data('tipo');

		// Enviar solicitud al servidor para obtener los datos de los documentos
		$.ajax({
			url: 'components/officesbanking/models/DescargarDocumento.php',
			method: 'POST',
			data: { archivo_officebanking: archivo_officebanking, tipo: tipo },
			dataType: 'json', // Especificar que se espera una respuesta JSON
			success: function (response) {
				if (response.success && response.data) {
					// Iterar sobre los documentos y generar descargas automáticas
					response.data.forEach((documento) => {
						const enlaceDescarga = document.createElement('a');
						enlaceDescarga.href = documento.ruta.replace(/\\/g, '/'); // Asegurarse de que la ruta sea compatible con URL
						enlaceDescarga.download = documento.nombre_archivo; // Nombre del archivo para la descarga
						enlaceDescarga.target = '_blank'; // Abrir en una nueva pestaña si necesario
						enlaceDescarga.style.display = 'none';

						document.body.appendChild(enlaceDescarga);
						enlaceDescarga.click(); // Dispara el clic para descargar automáticamente
						document.body.removeChild(enlaceDescarga);
					});
				} else {
					console.error('Error: No se encontraron documentos para descargar.');
				}
			},
			error: function (xhr, status, error) {
				console.error('Error al enviar la solicitud AJAX:', error);
			},
		});
	});

	// Evento para el botón "Generar Office Banking"
	$('#generarOfficeBanking')
		.off('click')
		.on('click', async function () {
			const offbnk = [];
			const thomson = [];
			const idDate = getIdDate();
			let allTasksCompleted = { generarExcel: false, descargarTxt: false };

			const checkedItems = $('.switchCheques:checked');
			for (let i = 0; i < checkedItems.length; i++) {
				const cierre = $(checkedItems[i]).closest('tr').find('td:first').text();

				try {
					// Obtener las liquidaciones (aunque no las iteremos, necesitamos el id_liquidacion)
					const liquidaciones = await $.ajax({
						url: 'components/officesbanking/models/GetIdLiquidaciones.php',
						method: 'GET',
						data: { cierre: cierre },
						dataType: 'json',
					});

					// Asumimos que solo necesitamos el primer id_liquidacion (o el único)
					const id_liquidacion = liquidaciones[0].id;

					// Actualizar la liquidación
					await $.ajax({
						url: 'components/officesbanking/models/UpdateOfficeBankingLiquidacion.php',
						method: 'POST',
						data: { id_liquidacion: id_liquidacion, id_date: idDate },
						dataType: 'json',
					});

					// Generar datos para Office Banking
					const officeBanking = await $.ajax({
						url: 'components/officesbanking/models/GenerarOfficeBanking.php',
						method: 'GET',
						data: { cierre: cierre },
						dataType: 'json',
					});

					const officeBankingDoc = await $.ajax({
						url: 'components/officesbanking/models/GenerarDocumetoOfficeBanking.php',
						method: 'GET',
						data: { cierre: cierre },
						dataType: 'json',
					});
					offbnk.push(officeBankingDoc);

					// Generar datos para Thomson
					const thomsonData = await $.ajax({
						url: 'components/officesbanking/models/GenerarThompson.php',
						method: 'GET',
						data: { cierre: cierre },
						dataType: 'json',
					});
					const thomsonDoc = await $.ajax({
						url: 'components/officesbanking/models/GenerarDocumentosThomson.php',
						method: 'GET',
						data: { cierre: cierre },
						dataType: 'json',
					});
					thomson.push(thomsonDoc);
				} catch (error) {
					console.error('Error procesando cierre:', cierre, error);
				}
			}

			try {
				await generarExcel(offbnk, idDate, allTasksCompleted);
				await descargarTxt(thomson, idDate, allTasksCompleted);
			} catch (error) {
				console.error('Error procesando los datos finales:', error);
			}

			// Monitorear el estado de las tareas para recargar la página
			const monitorTasks = setInterval(() => {
				if (allTasksCompleted.generarExcel && allTasksCompleted.descargarTxt) {
					clearInterval(monitorTasks);
					location.reload();
				}
			}, 500);
		});
});

function generarExcel(responseArray, id_officebanking, tasks) {
	return new Promise((resolve, reject) => {
		if (responseArray && responseArray.length > 0) {
			let allData = [];
			// Recorrer cada nivel del array para extraer `fn_genera_archivo_officeb`
			responseArray.forEach((subArray) => {
				if (Array.isArray(subArray)) {
					subArray.forEach((item) => {
						if (
							item.fn_genera_archivo_officeb &&
							item.fn_genera_archivo_officeb.length > 0
						) {
							allData = allData.concat(item.fn_genera_archivo_officeb);
						}
					});
				}
			});

			if (allData.length > 0) {
				const columnsOrder = [
					'rut_beneficiario',
					'nombre_beneficiario',
					'no', // Puedes ajustar estos nombres según tus columnas reales
					'no',
					'no',
					'modalidad_pago',
					'no',
					'cta_abono',
					'codigo_banco',
					'no',
					'no',
					'no_factura',
					'monto_factura',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'no',
					'monto_total_abono',
					'no',
					'email_beneficiario',
					'glosa',
				];

				// Formatear los datos según las columnas definidas
				const formattedData = allData.map((item) => {
					let row = {};
					columnsOrder.forEach((column) => {
						row[column] = column in item ? item[column] || '' : ''; // Asignar valores o vacíos
					});
					return row;
				});

				// Crear el archivo Excel
				const workbook = XLSX.utils.book_new();
				const worksheet = XLSX.utils.json_to_sheet(formattedData, {
					header: columnsOrder,
				});
				XLSX.utils.book_append_sheet(workbook, worksheet, 'OfficeBanking');

				// Establecer el ancho de las columnas
				worksheet['!cols'] = columnsOrder.map(() => ({ wpx: 50 }));

				// Generar el archivo como Blob
				const excelBlob = XLSX.write(workbook, {
					bookType: 'xlsx',
					type: 'binary',
				});
				const blob = new Blob([s2ab(excelBlob)], {
					type: 'application/octet-stream',
				});

				// Helper para convertir a binario
				function s2ab(s) {
					const buf = new ArrayBuffer(s.length);
					const view = new Uint8Array(buf);
					for (let i = 0; i < s.length; i++) {
						view[i] = s.charCodeAt(i) & 0xff;
					}
					return buf;
				}

				// Descargar el archivo en el navegador
				const fechaActual = new Date();
				const fechaFormateada = fechaActual.toISOString().replace(/[:.]/g, '-');
				const nombreArchivo = `transferencias-${fechaFormateada}.xlsx`;
				const url = URL.createObjectURL(blob);

				const a = document.createElement('a');
				a.href = url;
				a.download = nombreArchivo;
				document.body.appendChild(a);
				a.click();
				document.body.removeChild(a);
				URL.revokeObjectURL(url); // Liberar memoria

				// Crear FormData y adjuntar el archivo
				const formData = new FormData();
				formData.append('file', blob, nombreArchivo);
				formData.append('nombreArchivo', nombreArchivo);

				// Enviar el archivo a guardar
				guardarArchivo(formData, 'officebanking', id_officebanking);
			}
		}

		tasks.generarExcel = true;
		resolve();
	});
}

function descargarTxt(datos, id_officebanking, tasks) {
	return new Promise((resolve, reject) => {
		// Combinar los datos de todos los bloques
		const datosCombinados = combinarDatos(datos);

		// Formatear los datos como texto de tabla
		const texto = formatToTable(datosCombinados);

		if (texto === false) {
			return;
		}

		// Crear un Blob para el texto
		const blob = new Blob([texto], { type: 'text/plain' });

		// Generar el nombre del archivo con marca de tiempo
		const fecha = new Date();
		const anio = fecha.getFullYear();
		const mes = String(fecha.getMonth() + 1).padStart(2, '0');
		const dia = String(fecha.getDate()).padStart(2, '0');
		const hora = String(fecha.getHours()).padStart(2, '0');
		const minuto = String(fecha.getMinutes()).padStart(2, '0');
		const segundo = String(fecha.getSeconds()).padStart(2, '0');
		const milisegundos = String(fecha.getMilliseconds()).padStart(3, '0');

		const nombreArchivo = `ERP-${anio}${mes}${dia}${hora}${minuto}${segundo}${milisegundos}-0300.txt`;

		// Descargar el archivo en el navegador
		const url = URL.createObjectURL(blob);
		const a = document.createElement('a');
		a.href = url;
		a.download = nombreArchivo;
		document.body.appendChild(a);
		a.click();
		document.body.removeChild(a);
		URL.revokeObjectURL(url); // Liberar memoria

		// Enviar el archivo al servidor mediante AJAX
		const formData = new FormData();
		formData.append('file', blob, nombreArchivo);
		formData.append('nombreArchivo', nombreArchivo);
		formData.append('id_officebanking', id_officebanking);

		guardarArchivo(formData, 'thomson', id_officebanking);

		tasks.descargarTxt = true;
		resolve();
	});
}

function combinarDatos(datos) {
	// Imprimir los datos correctamente
	console.log('Datos a combinar:', JSON.stringify(datos, null, 2)); // Ver la estructura completa

	let combinados = [];

	// Verificar si el primer nivel de datos es un array de arrays
	if (Array.isArray(datos) && datos.length > 0) {
		// Iterar sobre el primer nivel de datos (array de arrays)
		datos.forEach((entryArray) => {
			// Iterar sobre cada subarray dentro de cada array principal
			if (Array.isArray(entryArray)) {
				entryArray.forEach((entry) => {
					// Verificar si 'fn_genera_archivo_thomson' existe dentro de cada objeto
					if (
						entry.fn_genera_archivo_thomson &&
						Array.isArray(entry.fn_genera_archivo_thomson)
					) {
						const bloques = entry.fn_genera_archivo_thomson;

						// Aplanar los arrays anidados y combinar sus elementos
						bloques.forEach((subArray) => {
							if (Array.isArray(subArray) && subArray.length > 0) {
								combinados = combinados.concat(subArray); // Unir los subarrays en combinados
							} else {
								combinados.push(subArray); // Si no es un subarray, agregar el objeto directamente
							}
						});
					}
				});
			}
		});
	}

	// Ver los datos combinados
	console.log('Datos combinados:', JSON.stringify(combinados, null, 2));

	return combinados;
}

function formatToTable(data) {
	let output = '';

	// // Verificar si el array es válido y tiene datos
	// if (!Array.isArray(data) || data.length === 0) {
	// 	return 'No hay datos disponibles';
	// }

	if (!Array.isArray(data) || data.length === 0) {
		return false;
	}

	// Obtener las cabeceras de las columnas usando el primer objeto del array
	const headers = Object.keys(data[0]);
	output += headers.join('\t') + '\n'; // Agregar las cabeceras separadas por tabuladores

	// Iterar sobre las filas para agregar los valores
	data.forEach((row) => {
		const values = Object.values(row); // Obtener todos los valores del objeto como un array
		output += values.join('\t') + '\n'; // Unir los valores con tabuladores y agregar un salto de línea
	});

	return output;
}

function guardarArchivo(formData, tipo, id_officebanking) {
	$.ajax({
		url: 'components/officesbanking/models/UploadFile.php',
		method: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		success: function (response) {
			const data = JSON.parse(response);
			if (data.success) {
				const postData = {
					filePath: data.ruta,
					fileName: data.fileName,
					tipo: tipo,
					id_officebanking: id_officebanking,
				};

				// Segunda petición AJAX para insertar en la base de datos
				$.ajax({
					url: 'components/officesbanking/models/InsertFile.php',
					method: 'POST',
					data: postData,
					success: function (response) {
						console.log('Archivo guardado exitosamente:', response);
					},
					error: function (xhr, status, error) {
						console.error(
							'Error al guardar el archivo en la base de datos:',
							error
						);
					},
				});
			} else {
				console.error('Error al subir el archivo:', data.message);
			}
		},
		error: function (xhr, status, error) {
			console.error('Error al subir el archivo al servidor:', error);
		},
	});
}

function getIdDate() {
	const fecha = new Date();
	const anio = fecha.getFullYear();
	const mes = String(fecha.getMonth() + 1).padStart(2, '0');
	const dia = String(fecha.getDate()).padStart(2, '0');
	const hora = String(fecha.getHours()).padStart(2, '0');
	const minuto = String(fecha.getMinutes()).padStart(2, '0');
	const segundo = String(fecha.getSeconds()).padStart(2, '0');
	const milisegundos = String(fecha.getMilliseconds()).padStart(3, '0');

	const nombre = `${anio}-${mes}-${dia} ${hora}:${minuto}:${segundo}:${milisegundos}`;
	return nombre;
}

function CargarListadoArchivos() {
	$.ajax({
		url: 'components/officesbanking/models/GetLiquidaciones.php',
		method: 'GET',
		dataType: 'json',
		success: function (data) {
			var tableBody = $('#archivos tbody');
			// Guardamos los datos originales para poder filtrarlos después
			var originalData = data.slice(); // Hacemos una copia de los datos originales

			// Función para llenar la tabla con los datos proporcionados
			function fillTable(dataToFill) {
				tableBody.empty(); // Limpiamos el contenido actual
				$.each(dataToFill, function (index, item) {
					tableBody.append(
						`<tr>
                            <td>${item.cierre}</td>
                            <td>${item.fecha_liquidacion}</td>
                            <td>${item.cantidad}</td>
                            <td>
                                <div class="d-flex">
                                    <label class="switch">
                                        <input name="officeBanking" class="form-check-input switchCheques" type="checkbox" role="switch">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-detalle" type="button" data-id="${item.cierre}" data-bs-toggle="modal" data-bs-target="#DetalleLiquidaciones">
                                    <i class="fa-solid fa-info"></i>
                                </button>
                            </td>
                        </tr>`
					);
				});
			}

			// Llenamos la tabla inicialmente con todos los datos
			fillTable(originalData);

			// Evento para los switches
			$(document).on('change', '.switchCheques', function () {
				if ($(this).is(':checked')) {
					$(this).siblings('.switchText').text('Si');
				} else {
					$(this).siblings('.switchText').text('No');
				}
			});

			// Configuramos DataTable
			$('#liquidaciones').DataTable();

			// Evento para la barra de búsqueda
			$('#barraBusquedaLiquidacion').on('input', function () {
				var searchValue = $(this).val().trim(); // Obtenemos el valor de búsqueda
				var filteredData = originalData.filter(function (item) {
					return item.cierre.toString().includes(searchValue); // Filtramos los datos
				});
				fillTable(filteredData); // Llenamos la tabla con los datos filtrados
			});

			// Evento para los botones de detalle
			$(document).on('click', '.btn-detalle', function () {
				var cierre = $(this).data('id');
				CargarDetalleLiquidaciones(cierre);
			});
		},
		error: function (xhr, status, error) {
			console.error('Error en la solicitud:', error);
		},
	});
}

function CargarLiquidacionsProcesadas() {
	$.ajax({
		url: 'components/officesbanking/models/GetLiquidacionesProcesadas.php',
		method: 'GET',
		dataType: 'json',
		success: function (response) {
			// Limpiar cualquier contenido previo
			$('#documentos-liquidaciones-tab-pane .content-page').html(`
                <h1>Documentos Descargables</h1>

                <table id="tabla-liquidaciones" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha (ID)</th>
                            <th>Descargar OfficeBanking</th>
                            <th>Descargar Thomson</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            `);

			// Agregar filas a la tabla
			response.forEach(function (item) {
				$('#tabla-liquidaciones tbody').append(`
                    <tr>
                        <td>${item.archivo_officebanking}</td>
                       <!-- Primer botón para OfficeBanking -->
					<td><button class="btn btn-secondary btn-liquidaciones-download" data-officebanking="${item.archivo_officebanking}" data-tipo="officebanking"><i class="fa-solid fa-file-arrow-down"></i></button></td>
					<!-- Segundo botón para Thomson -->
					<td><button class="btn btn-secondary btn-liquidaciones-download"  data-officebanking="${item.archivo_officebanking}" data-tipo="thomson"><i class="fa-solid fa-file-arrow-down"></i></button></td>
                    </tr>
                `);
			});
		},
		error: function (xhr, status, error) {
			console.error('Error en la solicitud:', error);
			$('#documentos-liquidaciones-tab-pane .content-page').html(`
                <h1>Error</h1>
                <p>No se pudieron cargar los datos. Por favor, intente nuevamente.</p>
            `);
		},
	});
}

function CargarDetalleLiquidaciones(cierre) {
	$.ajax({
		url: 'components/officesbanking/models/GetDetalleLiquidaciones.php',
		method: 'GET',
		dataType: 'json',
		data: {
			cierre: cierre,
		},
		success: function (data) {
			// Limpiar la tabla antes de llenarla
			var tableBody = $('#detalleLiquidaciones tbody');
			tableBody.empty(); // Limpiar el contenido anterior

			$.each(data, function (index, item) {
				// Agregar una fila a la tabla
				tableBody.append(
					`<tr>
                        <td>${item.liquidacion}</td>
                        <td>${item.ficha_propiedad}</td>
                        <td>${item.direccion}</td>
                        <td>${item.id_propietario}</td>
                        <td>${item.nombre}</td>
                        <td>${item.ficha_arriendo}</td>
                        <td>${item.cierre}</td>
                    </tr>`
				);
			});

			// Inicializar DataTables
			$('#detalleLiquidaciones').DataTable();
		},
		error: function (xhr, status, error) {
			console.error('Error en la solicitud:', error);
		},
	});
}

// propiedades por liquidar
function CargarListadoPropiedades() {
	$.ajax({
		url: 'components/officesbanking/models/GetListadoPropiedades.php', // Cambia esto a la ruta de tu script PHP
		method: 'GET',
		dataType: 'json',
		success: function (response) {
			// Asegúrate de que los datos estén en el formato esperado
			if (response.length > 0 && response[0].fn_propiedades_por_liquidar) {
				var data = response[0].fn_propiedades_por_liquidar;

				// Llenar la tabla con los datos
				var tableBody = $('#listadoPropiedades tbody');
				tableBody.empty(); // Limpiar el cuerpo de la tabla antes de agregar nuevos datos

				$.each(data, function (index, item) {
					// Formatear el monto en formato de moneda chilena (CLP)
					var montoFormateado = new Intl.NumberFormat('es-CL', {
						style: 'currency',
						currency: 'CLP',
					}).format(item.saldo);

					// Agregar una fila a la tabla
					tableBody.append(
						'<tr>' +
							'<td>' +
							item.idpropiedad +
							'</td>' +
							'<td>' +
							item.idcontrato +
							'</td>' + // Aquí asumo que "nombre_propietario" debería ser "idcontrato" según los datos que pasaste
							'<td class="me-auto">' +
							montoFormateado +
							'</td>' +
							'<td>' +
							'<label class="switch">' +
							'<input type="checkbox" id="rolActivoEditar_' +
							item.idpropiedad +
							'" name="' +
							item.idpropiedad +
							'" checked onclick="handleEstadoRolClick(event, this)">' +
							'<span class="slider round"></span>' +
							'</label>' +
							'</td>' +
							'</tr>'
					);
				});

				// Inicializar DataTables
				$('#listadoPropiedades').DataTable({
					destroy: true, // Permite reinicializar la tabla si ya estaba inicializada
				});
			} else {
				console.error('Datos no encontrados en el formato esperado.');
			}
		},
		error: function (xhr, status, error) {
			console.error('Error en la solicitud:', error);
		},
	});
}

// Función para generar un monto aleatorio
function generarMontoAleatorio(min, max) {
	return Math.floor(Math.random() * (max - min + 1)) + min;
}
