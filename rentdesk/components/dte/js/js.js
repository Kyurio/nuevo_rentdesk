// Cargar los datos en la tabla
async function TablaLlenarLiquidaciones() {
    try {
        // Verificar y destruir DataTable si ya existe
        const tableSelector = '#liq-generacion-masiva-table';
        if ($.fn.DataTable.isDataTable(tableSelector)) {
            $(tableSelector).DataTable().destroy();
        }

        // Fetch para obtener los datos
        const response = await fetch('components/dte/models/GetDTE.php');
        if (!response.ok) throw new Error(`Error: ${response.status} ${response.statusText}`);

        const data = await response.json();

        // Referencia al cuerpo de la tabla y limpieza
        const tableBody = document.getElementById("cierre-liquidaciones-tab-pane");
        tableBody.innerHTML = '';

        // Crear filas dinámicamente
        const fragment = document.createDocumentFragment();
        data.forEach(item => {
            const fecha = new Date(item.fecha_liquidacion);
            const fechaFormateada = `${('0' + fecha.getDate()).slice(-2)}-${('0' + (fecha.getMonth() + 1)).slice(-2)}-${fecha.getFullYear()}`;
            const montoFormateadoArriendo = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(item.comision_arriendo);
            const montoFormateadoAdministracion = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(item.comision_administracion);

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="d-flex">
                        <label class="switch">
                            <input type="checkbox" class="row-check" onchange="toggleGenerarDTE()">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <input type="hidden" value="${item.id_liquidacion}">
                    <input type="hidden" value="${item.documento_comision}">
                    <input type="hidden" value="${item.documento_arriendo}">
                </td>
                <td>${item.id_liquidacion}</td>
                <td>${montoFormateadoArriendo}</td>
                <td>${montoFormateadoAdministracion}</td>
                <td>${item.direccion}</td>
                <td>${fechaFormateada}</td>
            `;
            fragment.appendChild(row);
        });
        tableBody.appendChild(fragment);

        // Inicializar DataTable
        $(tableSelector).DataTable({
            order: [[1, 'asc']], // Ordenar por el ID de liquidación
            pageLength: 10,      // Registros por página
        });
    } catch (error) {
        console.error('Error al obtener los datos:', error);
    }
}

// Función para habilitar/deshabilitar el botón Generar DTE
function toggleGenerarDTE() {
    const checkboxes = document.querySelectorAll('.row-check');
    const generarDTEButton = document.getElementById('generarDTE');
    const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
    generarDTEButton.disabled = !anyChecked;
}

// Función para seleccionar o deseleccionar todas las filas
function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.row-check');
    const button = document.getElementById('select-all');

    // Determinar si todos están seleccionados
    const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

    // Cambiar el estado de los checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });

    // Actualizar el texto y estilo del botón
    if (!allChecked) {
        button.innerHTML = 'Deseleccionar todos';
        button.classList.remove('btn-info');
        button.classList.add('btn-danger');
    } else {
        button.innerHTML = 'Seleccionar todos';
        button.classList.remove('btn-danger');
        button.classList.add('btn-info');
    }

    // Actualizar el estado del botón Generar DTE
    toggleGenerarDTE();
}

// genera el documento y envia a la url correcta segun el tipo de doc 
async function GenerarDocumento() {
    const checkboxes = document.querySelectorAll('.row-check:checked');
    if (checkboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin selección',
            text: 'Por favor, selecciona al menos un DTE para continuar.',
            confirmButtonText: 'Entendido',
        });
        return;
    }

    const selectedItems = Array.from(checkboxes).map(checkbox => {
        const row = checkbox.closest('tr');
        if (!row) return null;
        return {
            idLiquidacion: row.querySelector('input[type="hidden"]:nth-child(2)')?.value || null,
            documentoComision: row.querySelector('input[type="hidden"]:nth-child(3)')?.value || null,
            documentoArriendo: row.querySelector('input[type="hidden"]:nth-child(4)')?.value || null,
        };
    }).filter(item => item !== null);

    Swal.fire({
        title: 'Generando documentos...',
        text: 'Por favor, espera mientras se procesan los documentos.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });

    let totalSuccess = 0;
    let totalErrors = 0;
    const errorMessages = [];

    for (const item of selectedItems) {
        const type = item.documentoComision || item.documentoArriendo;
        let action = '';

        switch (type) {
            case '1':
                action = 'components/dte/models/GenerarXMLFactura.php';
                break;
            case '3':
                action = 'components/dte/models/GenerarXMLBoleta.php';
                break;
            case '9':
                action = 'components/dte/models/GenerarXMLNotaCredito.php';
                break;
            default:
                errorMessages.push(`Liquidación ${item.idLiquidacion}: Tipo de documento no reconocido.`);
                totalErrors++;
                continue;
        }

        const formData = new FormData();
        formData.append('id_liquidacion', item.idLiquidacion);

        try {
            const response = await fetch(action, {
                method: 'POST',
                body: formData,
            });

            const jsonResponse = await response.json();
            for (const result of jsonResponse) {
                if (result.status === 'success') {
                    totalSuccess++;
                } else {
                    totalErrors++;
                    errorMessages.push(result.message || 'Error desconocido.');
                }
            }
        } catch (error) {
            totalErrors++;
            errorMessages.push(`Liquidación ${item.idLiquidacion}: Error al procesar (${error.message}).`);
        }
    }

    Swal.close();

    // Mostrar resultados finales con SweetAlert
    Swal.fire({
        icon: totalErrors > 0 ? 'warning' : 'success',
        title: 'Proceso terminado',
        html: `
            <p><strong>Éxitos:</strong> ${totalSuccess}</p>
            ${totalErrors > 0 ? `<p><strong>Errores:</strong> ${totalErrors}</p><p>Detalles:<br>${errorMessages.join('<br>')}</p>` : ''}
        `,
        confirmButtonText: 'Entendido',
    });

    if (totalErrors > 0) {
        console.warn('Errores detallados:', errorMessages);
    }

    await TablaLlenarLiquidaciones();
    $('#historial-dte-tab').click();
}


// listado historial liquidacione
async function HistorialLiquidaciones() {
    try {
        // Llamada AJAX para obtener los datos
        const response = await $.ajax({
            url: "components/dte/models/GetHistorialLiquidaciones.php", // URL del archivo PHP
            method: "GET",
            dataType: "json" // Asegurar que el retorno sea JSON
        });

        // Obtener la tabla y vaciar su contenido
        const tableBody = $("#liq-historial");
        tableBody.empty(); // Limpiar el contenido previo

        // Construir las filas para la tabla
        const tableRows = response.map(item => {
            // Ajustar ruta del PDF
            let pdfUrl = `/components/dte/models/boletas/${item.folio}.pdf`;

            // Convertir la fecha al formato dd-mm-yyyy
            const fecha = new Date(item.fecha_liquidacion);
            const fechaFormateada = `${('0' + fecha.getDate()).slice(-2)}-${('0' + (fecha.getMonth() + 1)).slice(-2)}-${fecha.getFullYear()}`;

            // Formatear monto como CLP
            const monto = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(item.monto);

            // Crear fila
            return `
                <tr>
                    <td>${fechaFormateada}</td>
                    <td>${item.id_ficha_arriendo}</td>
                    <td>${item.id_ficha_propiedad}</td>
                    <td>${item.direccion}</td>      
                    <td>${item.id_liquidacion}</td>
                    <td>${item.tipo_comision}</td>
                    <td>${item.folio}</td>
                    <td>${item.tipo_documento_texto}</td>
                    <td>
                        <button 
                            title='Descargar PDF'
                            class="btn btn-success btn-sm descargar-pdf" 
                            onclick="obtenerDTE('${item.tipo_documento}', '${item.folio}')">
                            <i class='fa-regular fa-file-pdf'></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join("");

        // Insertar filas en la tabla
        tableBody.append(tableRows);

        // Destruir DataTable existente antes de reinicializar (evita errores)
        if ($.fn.DataTable.isDataTable('#tablaHistorial')) {
            $('#tablaHistorial').DataTable().destroy();
        }

        // Inicializar DataTables con opciones
        $('#tablaHistorial').DataTable({
            responsive: true, // Habilitar diseño responsive
            paging: true, // Activar paginación
            searching: true, // Activar búsqueda
            ordering: false, // Permitir ordenar columnas
        });

    } catch (err) {
        console.error("Error cargando datos: ", err);
    }
}

// Función para enviar los datos al servidor y descargar el PDF
async function obtenerDTE(tipoDocumento, folio) {
    const url = 'components/dte/models/DescargarDTE.php'; // Cambia esto por la ruta real de tu script PHP
    const formData = new FormData();

    // Agregar datos al formulario
    formData.append('tipo_documento', tipoDocumento);
    formData.append('folio', folio);

    try {
        // Mostrar un SweetAlert de carga
        Swal.fire({
            title: 'Cargando...',
            text: 'Estamos procesando tu solicitud.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Hacer la solicitud al servidor
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
        });

        // Parsear la respuesta JSON
        const data = await response.json();

        Swal.close(); // Cerrar el SweetAlert de carga

        if (data.pdfLink) {
            // Mostrar éxito y abrir el enlace en una nueva pestaña
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'El archivo PDF se descargará en una nueva pestaña.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.open(data.pdfLink, '_blank');
            });
        } else {
            // Manejar el error devuelto por el servidor
            Swal.fire({
                icon: 'warning',
                title: 'Atencion',
                text: data.error || 'No se pudo obtener el enlace al PDF.',
            });
        }
    } catch (error) {
        Swal.close(); // Cerrar el SweetAlert de carga en caso de error
        // Manejar errores de red u otros errores inesperados
        Swal.fire({
            icon: 'error',
            title: 'Error inesperado',
            text: 'Ocurrió un error al intentar obtener el DTE.',
            footer: `<p>Detalles del error: ${error.message}</p>`
        });
        console.error('Error al obtener el DTE:', error);
    }
}


// Cargar la tabla y agregar eventos a los botones al cargar el DOM
$(document).ready(function () {
    HistorialLiquidaciones();
    TablaLlenarLiquidaciones().then(() => {
        // Asignar evento al botón de seleccionar/deseleccionar después de cargar la tabla
        document.getElementById('select-all').addEventListener('click', toggleSelectAll);
    });
});
