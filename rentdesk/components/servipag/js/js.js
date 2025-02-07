// Función para dar formato de moneda chilena, ya existente
function formatoMonedaChile(valor) {
	return new Intl.NumberFormat('es-CL', {
		style: 'currency',
		currency: 'CLP',
	}).format(valor);
}

// Función para formatear la fecha en el formato día/mes/año
function formatoFecha(fecha) {
	const opciones = { day: '2-digit', month: '2-digit', year: 'numeric' };
	const fechaFormateada = new Date(fecha).toLocaleDateString('es-CL', opciones);
	return fechaFormateada;
}

// funcion para leer el listado de servipag registrado en la bd
function LeerServipag() {
	// Realizar una solicitud AJAX para obtener los datos
	$.ajax({
		url: 'components/servipag/models/leercargaservipag.php',
		method: 'GET',
		dataType: 'json',
		success: function (data) {
			// Vaciar la tabla antes de rellenarla
			const tableBody = $('#servipagTable tbody');
			tableBody.empty();

			// Usar $.each para recorrer los datos y agregarlos a la tabla
			$.each(data, function (index, item) {
				const row = `
                    <tr>     
					    <td>${item.id}</td>
                        <td>${item.rut}</td>
						<td>${item.ficha_propiedad}</td>
                        <td><a href="index.php?component=arriendo&view=arriendo_ficha_tecnica&token=${
													item.token
												}" target="_blank"> ${item.id_arriendo}</a> ${
					item.direccion
				}</td>
                        <td>${item.estado}</td>
                        <td>${formatoFecha(item.fecha_pago)}</td>
                        <td>${formatoMonedaChile(item.valor_arriendo)}</td>
                        <td>${formatoMonedaChile(item.monto_pagado)}</td>
                        <td>${formatoMonedaChile(item.diferencia)}</td>
                    </tr>
                `;
				tableBody.append(row);
			});

			// Inicializar o reiniciar el DataTable después de llenar la tabla
			if ($.fn.DataTable.isDataTable('#servipagTable')) {
				$('#servipagTable').DataTable().destroy();
			}
		},
		error: function (xhr, status, error) {
			console.error('Error al cargar los datos:', error);
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'No se pudieron cargar los datos. Inténtalo nuevamente más tarde.',
			});
		},
	});
}

// funcion para carga el txt en la bd
async function CargarServipag() {
	const fileInput = document.getElementById('formFile');

	if (fileInput.files.length === 0) {
		Swal.fire({
			icon: 'warning',
			title: 'Archivo no seleccionado',
			text: 'Por favor, selecciona un archivo antes de continuar.',
		});
		return;
	}

	const formData = new FormData();
	formData.append('file', fileInput.files[0]);

	// Mostrar mensaje de procesando
	Swal.fire({
		title: 'Procesando...',
		text: 'Por favor espera mientras procesamos el archivo.',
		icon: 'info',
		allowOutsideClick: false,
		showConfirmButton: false,
		didOpen: () => {
			Swal.showLoading();
		},
	});

	try {
		const response = await fetch(
			'components/servipag/models/cargarservipag.php',
			{
				method: 'POST',
				body: formData,
			}
		);

		// Convertir la respuesta en JSON
		const result = await response.json();

		// Si el servidor retornó success: true se muestra el mensaje de éxito
		if (result.success) {
			Swal.fire({
				icon: 'success',
				title: 'Éxito',
				text: result.message,
			});
		} else {
			// En caso de success: false, se muestra una alerta de error con el mensaje recibido
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: result.message,
			});
		}
	} catch (error) {
		Swal.fire({
			icon: 'error',
			title: 'Error de conexión',
			text: 'No pudimos conectar con el servidor. Por favor verifica tu conexión.',
		});
	} finally {
		LeerServipag();
	}
}

// procesar listado
function ProcesarListado() {
	const tableRows = $('#servipagTable tbody tr');

	// Verificar si la tabla está vacía
	if (tableRows.length === 0) {
		Swal.fire({
			icon: 'warning',
			title: 'Tabla vacía',
			text: 'No hay datos para procesar.',
		});
		return; // Detener la ejecución si no hay filas
	}

	const dataToSend = [];

	// Recorrer cada fila de la tabla para capturar los datos
	tableRows.each(function () {
		const row = $(this).find('td');
		const dataRow = {
			id_servipag: row.eq(0).text().trim(),
			id_propiedad: row.eq(2).text().trim(),
			fecha_pago: row.eq(5).text().trim(),
			monto_pagado: row.eq(7).text().replace(/\D/g, ''),
		};
		dataToSend.push(dataRow);
	});

	// Mostrar mensaje de "Procesando"
	Swal.fire({
		title: 'Procesando',
		text: 'Por favor, espera mientras procesamos los datos...',
		icon: 'info',
		allowOutsideClick: false,
		showConfirmButton: false,
		didOpen: () => {
			Swal.showLoading();
		},
	});

	// Enviar los datos al backend
	$.ajax({
		url: 'components/servipag/models/pago_transferencias.php', // Reemplaza con la ruta correcta
		method: 'POST',
		contentType: 'application/json',
		data: JSON.stringify(dataToSend),
		success: function (response) {
			Swal.close();
			Swal.fire({
				icon: 'success',
				title: 'Procesado',
				text: 'Los datos se enviaron correctamente.',
			});

			LeerServipag();
		},
		error: function (xhr, status, error) {
			Swal.close();
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Hubo un error al procesar los datos.',
			});
		},
	});

	LeerServipag();
}

// ejecucion automatica
$(document).ready(function () {
	LeerServipag();
});
