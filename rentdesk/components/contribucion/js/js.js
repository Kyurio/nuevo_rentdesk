   // Función para formatear moneda
    function formatCurrency(value) {
        var number = parseFloat(value);
        if (isNaN(number)) {
            return ''; // Retorna vacío si no es un número
        }
        return '$ ' + number.toLocaleString('es-ES', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }


// Función para cargar la lista de administradores en un select
function loadAdministradores() {
  $.ajax({
      url: 'components/contribucion/models/filtro_contribucion.php',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
          var select = $('#selectAdministrador');
          select.empty(); // Limpiar opciones previas
          select.append('<option value="">Seleccione Administrador</option>'); // Opción predeterminada
          
          if (response.status === 'success') {
              $.each(response.data, function(index, admin) {
                  select.append(`<option value="${admin.id_sucursal}">${admin.nombre}</option>`);
              });
          } else {
              console.error('Error: Formato de respuesta inesperado');
          }
      },
      error: function(xhr, status, error) {
          console.error('Error al cargar administradores:', error);
      }
  });
}

// Función para cargar la lista de contribuciones en el DataTable
function loadContribucionesList() {
  var fechaDesde = $('#fechaDesde').val();
  var fechaHasta = $('#fechaHasta').val();
  var idAdministrador = $('#selectAdministrador').val();

  if (fechaDesde && fechaHasta && fechaHasta < fechaDesde) {
      Swal.fire({
          title: 'Precaución',
          text: 'La fecha "Hasta" no puede ser menor que la fecha "Desde".',
          icon: 'info',
          confirmButtonText: 'OK'
      });
      return;
  }

  $.ajax({
      url: 'components/contribucion/models/leer_valores_contribuciones.php',
      type: 'GET',
      dataType: 'json',
      data: {
          fechaDesde: fechaDesde,
          fechaHasta: fechaHasta,
          idAdministrador: idAdministrador
      },
      success: function(data) {
          if ($.fn.DataTable.isDataTable('#contribuciones')) {
              $('#contribuciones').DataTable().clear().destroy();
          }
          $('#contribuciones tbody').empty();

          $.each(data.data, function(index, contribucion) {
              $('#contribuciones tbody').append(
                                `<tr>
                          <td>${contribucion.rol}</td> <!-- Código de la propiedad -->
                          
                          <td>${new Date(contribucion.fecha).toLocaleDateString('es-ES')}</td> <!-- Fecha de registro -->
                          <td>${contribucion.estado}</td> <!-- Estado de la contribución -->
                          <td>${contribucion.tipo_rol}</td> <!-- Tipo de rol (PRINCIPAL o SECUNDARIO) -->
                          <td>${contribucion.direccion}</td> <!-- Dirección de la propiedad -->
                          <td>${contribucion.fecha_pago ? new Date(contribucion.fecha_pago).toLocaleDateString('es-ES') : ''}</td> <!-- Fecha de pago -->
                          <td>${contribucion.ano_contrib}</td> <!-- Año de la contribución -->
                          <td>${contribucion.descripcion}</td> <!-- Descripción de la propiedad -->
                          <td>${contribucion.idpropiedad}</td> <!-- ID de la propiedad -->
                          <td>${contribucion.mes_contrib}</td> <!-- Mes de la contribución -->
                          <td>${formatCurrency(contribucion.valor_cuota)}</td> <!-- Valor de la cuota -->
                          <td>${formatCurrency(contribucion.monto_pagado)}</td> <!-- Monto pagado -->
                          <td>
                          <button class="btn btn-info editar-contribucion-btn" onclick="abrirModalEditarContribucion('${contribucion.idpropiedad}', '${contribucion.rol}', '${contribucion.idvaloresroles}', '${contribucion.cuota}', '${contribucion.mes_contrib}', '${contribucion.fecha_pago}', '${contribucion.ano_contrib}', '${contribucion.valor_cuota}', '${contribucion.monto_pagado}')">
                              <i class="fa-solid fa-pen-to-square"></i>
                          </button>
                      </td>
                  </tr>`
              );

              // Almacenar valores en localStorage
              localStorage.setItem(`contribucion_${index}_id_propiedad`, contribucion.idpropiedad);
              localStorage.setItem(`contribucion_${index}_rol`, contribucion.rol);
              localStorage.setItem(`contribucion_${index}_idvaloresroles`, contribucion.idvaloresroles);
              localStorage.setItem(`contribucion_${index}_valor_cuota`, contribucion.valor_cuota);
              localStorage.setItem(`contribucion_${index}_mes_contrib`, contribucion.mes_contrib);
              localStorage.setItem(`contribucion_${index}_ano_contrib`, contribucion.ano_contrib);
          });

          // Mostrar el contenido de localStorage en la consola
          console.log('Contenido de localStorage:', localStorage);

          $('#contribuciones').DataTable({
              language: {
                  search: "Buscar:",
                  paginate: {
                      next: "Siguiente",
                      previous: "Anterior"
                  },
                  info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                  infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                  infoFiltered: "(filtrado de _MAX_ entradas en total)"
              },
              lengthMenu: [10, 25, 50, 100],
              dom: 'Bfrtip',
              order: [[1, 'asc']],
              buttons: [
                  {
                      extend: 'excelHtml5',
                      text: '<i class="fas fa-file-excel"></i> Descargar Excel',
                      title: 'Listado contribuciones',
                      className: 'btn btn-success',
                      action: function(e, dt, button, config) {
                          if (fechaDesde && fechaHasta && fechaHasta < fechaDesde) {
                              Swal.fire({
                                  icon: 'error',
                                  title: 'Fechas no válidas',
                                  text: 'La fecha hasta no puede ser menor que la fecha desde.'
                              });
                              return;
                          }
                          $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                      }
                  }
              ]
          });
      },
      error: function(xhr, status, error) {
          console.error('Error:', error);
          $('#contribuciones tbody').empty();
      }
  });
}


// Función para abrir el modal de edición de contribuciones
function abrirModalEditarContribucion(idPropiedad, rol, valoresRol, cuota, mes, fechaPago, anoContrib, valorCuota, montoPagado) {
  $('#id_propiedad').val(idPropiedad);
  $('#rolContribucion').val(rol);
  $('#idvaloresroles').val(valoresRol);
  $('#fechaPagoContribucion').val(fechaPago || ''); // Valor predeterminado si está indefinido
  $('#anoContribucion').val(anoContrib || '' || '0');
  $('#valorCuotaContribucion').val(valorCuota || '0');
  $('#montoPagadoContribucion').val(montoPagado || '0');
  $('#mes').val(mes || '');

  // Inicializar el datepicker para el campo de año
  $('#anoContribucion').datepicker({
      format: "yyyy",       // Solo muestra el año
      viewMode: "years",    // Empieza la vista en años
      minViewMode: "years"  // Permite seleccionar solo el año
  });

  $('#modalEditarContribucion').modal('show');
}

// Función para guardar los cambios en la contribución
function guardarContribucion() {
  var idValoresRoles = localStorage.getItem('contribucion_0_idvaloresroles'); // Usar ejemplo del primer elemento

  if (!idValoresRoles) {
      Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'El valor de idvaloresroles no está disponible.'
      });
      return;
  }

  var data = $('#formEditarContribucion').serialize() + `&idvaloresroles=${idValoresRoles}`;

  $.ajax({
      url: 'components/contribucion/models/editar_contribucion.php',
      type: 'POST',
      dataType: 'json',
      data: data,
      success: function(response) {
          if (response.status === 'success') {
              Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: 'Contribución actualizada correctamente.'
              });
              $('#modalEditarContribucion').modal('hide');
              loadContribucionesList();
          } else {
              Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: response.message
              });
          }
      },
      error: function(xhr, status, error) {
          console.error('Error al guardar contribución:', error);
          Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Ocurrió un error al intentar guardar la contribución.'
          });
      }
  });
}

$(document).ready(function() {
  loadAdministradores();
  
  // Configurar el evento para filtrar fechas
  $('#filtrarFechas').click(function() {
      loadContribucionesList();
      // Mostrar mensaje de éxito centrado con SweetAlert
      Swal.fire({
          position: 'center', // Cambiado a 'center' para centrar el mensaje
          icon: 'success',
          title: 'Los datos han sido filtrados',
          showConfirmButton: false,
          timer: 1500
      });
  });

  // Cargar la lista de contribuciones al inicio
  loadContribucionesList();
});

let excelFileLoaded = false;

function updateFileName() {
    const fileInput = document.getElementById('excelFile');
    const fileNameDisplay = document.getElementById('fileName');
    const uploadButton = document.getElementById('subirConvertirBtn');
    excelFileLoaded = fileInput.files.length > 0;

    if (excelFileLoaded) {
        fileNameDisplay.textContent = fileInput.files[0].name;
        uploadButton.disabled = false;
        // Hide the tooltip and reset title when a file is selected
        uploadButton.removeAttribute('data-bs-toggle');
        uploadButton.removeAttribute('title');
        // Destroy any existing tooltips (if any)
        const tooltip = bootstrap.Tooltip.getInstance(uploadButton);
        if (tooltip) tooltip.dispose();
    } else {
        fileNameDisplay.textContent = 'Ningún archivo seleccionado';
        uploadButton.disabled = true;
        // Enable the tooltip when no file is selected
        uploadButton.setAttribute('data-bs-toggle', 'tooltip');
        uploadButton.setAttribute('title', 'Esta función se activará únicamente cuando se suba un archivo Excel de contribuciones.');
        // Initialize the tooltip if it is not already initialized
        const tooltip = new bootstrap.Tooltip(uploadButton);
    }
}

// Initialize tooltip when the page loads
document.addEventListener('DOMContentLoaded', function () {
    const uploadButton = document.getElementById('subirConvertirBtn');
    new bootstrap.Tooltip(uploadButton); // Initialize tooltip
});

function convertExcelToJson() {
    const input = document.getElementById("excelFile");
    if (input.files.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, seleccione un archivo Excel.',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Estás seguro que quieres actualizar la contribución?",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, actualizar"
    }).then((result) => {
        if (result.isConfirmed) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: "array" });
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

                const formattedData = jsonData.map((row, index) => {
                    if (index === 0) return row; // Skip header row

                    return row.map(cell => {
                        if (typeof cell === "number" && cell > 25569) {
                            const jsDate = new Date((cell - 25569) * 86400 * 1000);
                            jsDate.setDate(jsDate.getDate() + 1); // Adjust for date offset
                            const day = String(jsDate.getDate()).padStart(2, '0');
                            const month = String(jsDate.getMonth() + 1).padStart(2, '0');
                            const year = jsDate.getFullYear();
                            return `${year}-${month}-${day}`;
                        }
                        return cell;
                    });
                });

                console.log("Formatted JSON data:", formattedData);
                uploadDataToServer(formattedData);
            };

            reader.onerror = function(error) {
                console.error("Error reading file:", error);
            };

            reader.readAsArrayBuffer(file);
        }
    });
}

function uploadDataToServer(jsonData) {
    fetch('components/contribucion/models/upload_excel.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ data: jsonData })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Datos cargados exitosamente.',
                showConfirmButton: false,
                timer: 1500
            });

            // Limpiar los datos anteriores en la tabla temporal antes de insertar los nuevos
            fetch('components/contribucion/models/clear_temp_table.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ table: 'propiedades.propiedad_contribuciones_temp' })
            })
            .then(response => response.json())
            .then(clearResult => {
                if (clearResult.success) {
                    // Llamar a la función para actualizar las contribuciones
                    propiedades.fn_actualiza_contribuciones();

                    location.reload(); // Recargar la página para actualizar los datos
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo limpiar los datos temporales.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(error => console.error('Error:', error));

            $('#modalExcelUpload').modal('hide');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar los datos: ' + result.message,
                confirmButtonText: 'Aceptar'
            });
        }
    })
    .catch(error => console.error('Error:', error));
}


function abrirModalContribuciones() {
  $.ajax({
      url: 'components/contribucion/models/contribuciones_list.php', // Update this with the correct path
      method: 'GET',
      dataType: 'json',
      success: function(data) {
          // Clear the existing table body
          $('#detalleContribuciones tbody').empty();
          
          if (data && data.length > 0) {
              // Populate the table with data
              data.forEach(function(item) {
                  $('#detalleContribuciones tbody').append(`
                      <tr>
                          <td>${item.rol}</td>
                          <td>${item.fecha_contribucion}</td>
                          <td>${item.id_propiedad}</td>
                          <td>${item.ano_contrib}</td>
                          <td>${item.mes_contrib}</td>
                          <td>${item.num_cuota}</td>
                          <td>${formateoDivisa(item.valor_cuota)}</td>  <!-- Format valor_cuota -->
                          <td>${formateoDivisa(item.monto_contrib)}</td> <!-- Format monto_contrib -->
                      </tr>
                  `);
              });
          } else {
              $('#detalleContribuciones tbody').append(`
                  <tr>
                      <td colspan="9" class="text-center">No hay datos disponibles.</td> <!-- Adjusted colspan -->
                  </tr>
              `);
          }
          
          // Show the modal
          $('#modalViewContribuciones').modal('show');
      },
      error: function(xhr, status, error) {
          console.error('Error fetching contribuciones:', error);
          alert('Ocurrió un error al cargar los datos.');
      }
  });
}


function generarRetencion() {
  // Mostrar la alerta de confirmación en español, adaptada para la retención
  Swal.fire({
    title: "¿Estás seguro?",
    text: "No podrás revertir esta acción, se generará una retención para las contribuciones seleccionadas.",
    icon: "info",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, generar retención"
  }).then((result) => {
    if (result.isConfirmed) {
      // Crear un array para almacenar los datos de retención
      let retenciones = [];

      // Recorrer las claves de localStorage y obtener los datos relacionados
      for (let index = 0; ; index++) {
        // Recuperar los datos de localStorage relacionados con las contribuciones
        var idPropiedad = localStorage.getItem(`contribucion_${index}_id_propiedad`);
        var rol = localStorage.getItem(`contribucion_${index}_rol`);
        var numCuota = localStorage.getItem(`contribucion_${index}_num_cuota`);
        var valorCuota = localStorage.getItem(`contribucion_${index}_valor_cuota`);
        var mes = localStorage.getItem(`contribucion_${index}_mes_contrib`);

        // Detener el bucle si no hay más datos de contribuciones
        if (!idPropiedad) break;

        // Verificar que idPropiedad no sea vacío
        if (!idPropiedad.trim()) {
          console.error('Error: idPropiedad está vacío para el índice ' + index);
          continue; // Saltar este registro si idPropiedad está vacío
        }

        // Verificar si los demás campos están vacíos y asignarles valores predeterminados si es necesario
        numCuota = numCuota || 0;  // Asegurar que numCuota no sea undefined o vacío
        valorCuota = valorCuota || 0;  // Asegurar que valorCuota no sea undefined o vacío
        mes = mes || "valor_default";  // Asignar un valor predeterminado para mes si está vacío

        // Agregar el objeto de retención al array
        retenciones.push({
          idPropiedad: idPropiedad,
          rol: rol || "valor_default",  // Asignar valor predeterminado si rol está vacío
          valorCuota: valorCuota,
          mesContrib: mes,
          numCuota: numCuota
        });
      }

      // Validar que todos los objetos de retenciones tienen un idPropiedad válido
      for (let i = 0; i < retenciones.length; i++) {
        if (!retenciones[i].idPropiedad || !retenciones[i].idPropiedad.trim()) {
          console.error('Error: idPropiedad vacío o nulo en la retención en el índice ' + i);
          Swal.fire({
            icon: 'error',
            title: 'Error al generar la retención',
            text: 'El idPropiedad está vacío en uno de los registros de retención. Por favor, revisa los datos.'
          });
          return; // Detener la ejecución si algún idPropiedad es inválido
        }
      }

      // Crear el objeto JSON con el campo "json_entrada" para enviar al servidor
      const dataToSend = {
        json_entrada: retenciones
      };

      // Mostrar los datos de retenciones en la consola para depuración
      console.log("Datos de retenciones generados:", dataToSend);

      // Enviar los datos al servidor o hacer otras acciones según necesites
      $.ajax({
        url: 'components/contribucion/models/GenerarRetencionController.php',
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',  // Asegúrate de que los datos se envíen como JSON
        data: JSON.stringify(dataToSend), // Enviar los datos como un string JSON
        success: function(response) {
          if (response.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Retención generada',
              text: 'La retención se ha generado y los datos se han guardado correctamente.'
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error al generar la retención',
              text: response.message || 'Ocurrió un error al generar la retención. Por favor, intenta nuevamente.'
            });
          }
        },
        error: function(xhr, status, error) {
          console.error('Error al guardar la retención:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al intentar guardar la retención. Por favor, intenta nuevamente.'
          });
        }
      });
    }
  });
}
