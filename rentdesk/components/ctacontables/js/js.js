function enviar() {
  var archivo = $("#archivo").val();
  var archivo_bd = $("#archivo_bd").val();

  if (archivo == "" && archivo_bd == "N") {
    $.showAlert({ title: "Atención", body: "Debe Adjuntar el mandato" });
    return;
  }

  if (archivo) {
    console.log("EXISTE ARCHIVO");
    $.showAlert({ title: "Atención", body: "Debe Adjuntar el mandato" });
    return;
  }

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  var formData = new FormData(document.getElementById("formulario"));

  $.ajax({
    url: "components/propiedad/models/insert_update.php",
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
      document.location.href =
        "index.php?component=propiedad&view=propiedad&token=" + token;
      return false;
    } else {
      $.showAlert({ title: "Error", body: mensaje });
      return false;
    }
  });
} //function enviar

//Desde acá código para Datatable listado
//*****************************************************************************************
function loadReajustes() {
  $(document).ready(function () {
    $("#responsable").DataTable({
      order: [[0, "asc"]],
      processing: true,
      serverSide: true,
      pageLength: 25,
      columnDefs: [{ orderable: false, targets: [6, 7, 8, 9, 10] }],
      ajax: {
        url: "components/rol/models/rol_list_procesa.php",
        type: "POST",
      },
      language: {
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "No encontrado",
        info: "Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)",
        infoEmpty: "Sin resultados",
        infoFiltered:
          " <strong>Total de registros filtrados: _TOTAL_ </strong>",
        loadingRecords: "Cargando...",
        search: "buscar: ",
        processing: "Procesando...",
        paginate: {
          first: "Primero",
          last: "Último",
          next: "siguiente",
          previous: "anterior",
        },
      },
    });

    $("div.dataTables_filter input").unbind(); // se desactiva la busqueda al presionar una tecla

    $(
      "<div id='divbotonbuscar' ><i id='buscar' class='fas fa-search'></i></div>"
    ).insertBefore(".dataTables_filter input");

    //Para realizar la búsqueda al hacer click en el botón
    $("#buscar").click(function (e) {
      var table = $("#tabla").DataTable();
      table.search($("div.dataTables_filter input").val()).draw();
      //mostrar u ocultar botón para resetear las búsquedas y orden
    }); //$('#buscar').click(function(e){
  }); //$(document).ready(function()
} //function loadUsers()

//************************************************************************
function deletePropiedad(token) {
  $.showConfirm({
    title: "Por Favor Confirme.",
    body: "Realmente desea Eliminar El registro? No se puede deshacer.",
    textTrue: "Si",
    textFalse: "No",
    onSubmit: function (result) {
      if (result) {
        $.ajax({
          type: "POST",
          url: "components/propiedad/models/delete.php",
          data: "token=" + token,
          success: function (res) {
            var retorno = res.split(",xxx,");
            var resultado = retorno[1];
            var mensaje = retorno[2];
            var token = retorno[3];

            if (resultado == "OK") {
              $.showAlert({ title: "Atención", body: mensaje });
              document.location.reload();
              return false;
            } else {
              $.showAlert({ title: "Error", body: mensaje });
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
    $("#tabla").DataTable({
      order: [[1, "asc"]],
      processing: true,
      serverSide: true,
      pageLength: 10,
      columnDefs: [{ orderable: false, targets: [6] }],
      ajax: {
        url:
          "../models/listado_propietarios.php?token_propiedad=" +
          token +
          "&participacion=" +
          participacion,
        type: "POST",
      },
      language: {
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "No encontrado",
        info: "Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)",
        infoEmpty: "Sin resultados",
        infoFiltered:
          " <strong>Total de registros filtrados: _TOTAL_ </strong>",
        loadingRecords: "Cargando...",
        search: "buscar: ",
        processing: "Procesando...",
        paginate: {
          first: "Primero",
          last: "Último",
          next: "siguiente",
          previous: "anterior",
        },
      },
    });

    $("div.dataTables_filter input").unbind(); // se desactiva la busqueda al presionar una tecla

    $(
      "<div id='divbotonbuscar' ><i id='buscar' class='fas fa-search'></i></div>"
    ).insertBefore(".dataTables_filter input");

    //Para realizar la búsqueda al hacer click en el botón
    $("#buscar").click(function (e) {
      var table = $("#tabla").DataTable();
      table.search($("div.dataTables_filter input").val()).draw();
      //mostrar u ocultar botón para resetear las búsquedas y orden
    }); //$('#buscar').click(function(e){
  }); //$(document).ready(function()
} //function loadUsers()

function agregarPropietario(token, token_propiedad, participacion_ant) {
  console.log(" participacion_ant --> " + participacion_ant);
  var part_max = 100 - participacion_ant;
  $.showModal({
    title: "Ingrese % Participación",
    body:
      '<form><div class="form-group row">' +
      '<div class="col-9"><label for="text" class="col-form-label">% Participacion (Máximo ' +
      part_max +
      "%)</label></div>" +
      '<div class="col-3"><input type="number" maxlength="3" min="1" max="100" required="required" class="form-control" id="participacion"/></div>' +
      "</div></form>",
    footer:
      '<button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Ingresar</button>',
    onCreate: function (modal) {
      // create event handler for form submit and handle values
      $(modal.element).on("click", "button[type='submit']", function (event) {
        event.preventDefault();
        var $form = $(modal.element).find("form");
        var participacion = $form.find("#participacion").val();

        if (!/^([0-9])*$/.test(participacion)) {
          $.showAlert({
            title: "Atención",
            body: "El valor " + participacion + " no es un número valido",
          });
        } else {
          if (participacion < 1 || participacion > 100) {
            $.showAlert({
              title: "Atención",
              body: "El porcentaje debe ser un valor entre 1 y 100 ",
            });
          } else {
            if (participacion > part_max) {
              $.showAlert({
                title: "Atención",
                body:
                  "La suma de las participaciones de cada propietario no puede ser superior al 100%. El maximo a ingresar debe ser = " +
                  part_max,
              });
            } else {
              $.ajax({
                type: "POST",
                url: "../models/insert_delete_propietario.php",
                data: {
                  token: token,
                  token_propiedad: token_propiedad,
                  participacion: participacion,
                  accion: "I",
                },
                success: function (res) {
                  var retorno = res.split(",xxx,");
                  var resultado = retorno[1];
                  var mensaje = retorno[2];
                  if (resultado == "OK") {
                    $.showAlert({ title: "Atención", body: mensaje });
                    modal.hide();
                    parent.jQuery.fancybox.close();
                    parent.document.location.reload();
                    return false;
                  } else {
                    $.showAlert({ title: "Error", body: mensaje });
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
    title: "Por Favor Confirme.",
    body: "Realmente desea Eliminar El registro? No se puede deshacer.",
    textTrue: "Si",
    textFalse: "No",
    onSubmit: function (result) {
      if (result) {
        $.ajax({
          type: "POST",
          url: "components/propiedad/models/insert_delete_propietario.php",
          data: {
            token: token,
            token_propiedad: token_propiedad,
            participacion: 0,
            accion: "D",
          },
          success: function (res) {
            var retorno = res.split(",xxx,");
            var resultado = retorno[1];
            var mensaje = retorno[2];
            var token = retorno[3];

            if (resultado == "OK") {
              $.showAlert({ title: "Atención", body: mensaje });
              document.location.reload();
              return false;
            } else {
              $.showAlert({ title: "Error", body: mensaje });
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
    $("#tabla").DataTable({
      order: [[0, "desc"]],
      processing: true,
      serverSide: true,
      pageLength: 10,
      columnDefs: [{ orderable: false, targets: [6] }],
      ajax: {
        url: "../models/listado_check_in.php?token_propiedad=" + token,
        type: "POST",
      },
      language: {
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "No encontrado",
        info: "Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)",
        infoEmpty: "Sin resultados",
        infoFiltered:
          " <strong>Total de registros filtrados: _TOTAL_ </strong>",
        loadingRecords: "Cargando...",
        search: "buscar: ",
        processing: "Procesando...",
        paginate: {
          first: "Primero",
          last: "Último",
          next: "siguiente",
          previous: "anterior",
        },
      },
    });

    $("div.dataTables_filter input").unbind(); // se desactiva la busqueda al presionar una tecla

    $(
      "<div id='divbotonbuscar' ><i id='buscar' class='fas fa-search'></i></div>"
    ).insertBefore(".dataTables_filter input");

    //Para realizar la búsqueda al hacer click en el botón
    $("#buscar").click(function (e) {
      var table = $("#tabla").DataTable();
      table.search($("div.dataTables_filter input").val()).draw();
      //mostrar u ocultar botón para resetear las búsquedas y orden
    }); //$('#buscar').click(function(e){
  }); //$(document).ready(function()
} //function loadUsers()

//************************************************************************
function agregarCheckIn(token, token_propiedad) {
  $.showConfirm({
    title: "Por Favor Confirme.",
    body: "Realmente desea Asignar el registro?",
    textTrue: "Si",
    textFalse: "No",
    onSubmit: function (result) {
      if (result) {
        $.ajax({
          type: "POST",
          url: "../models/insert_check_in.php",
          data: "token=" + token + "&token_propiedad=" + token_propiedad,
          success: function (res) {
            var retorno = res.split(",xxx,");
            var resultado = retorno[1];
            var mensaje = retorno[2];
            var token = retorno[3];

            if (resultado == "OK") {
              $.showAlert({ title: "Atención", body: mensaje });
              parent.jQuery.fancybox.close();
              parent.document.location.reload();
              return false;
            } else {
              $.showAlert({ title: "Error", body: mensaje });
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
    title: "Por Favor Confirme.",
    body: "Realmente desea Eliminar el registro?",
    textTrue: "Si",
    textFalse: "No",
    onSubmit: function (result) {
      if (result) {
        $.ajax({
          type: "POST",
          url: "components/propiedad/models/delete_check_in.php",
          data: "token=" + token + "&token_propiedad=" + token_propiedad,
          success: function (res) {
            var retorno = res.split(",xxx,");
            var resultado = retorno[1];
            var mensaje = retorno[2];
            var token = retorno[3];

            if (resultado == "OK") {
              $.showAlert({ title: "Atención", body: mensaje });
              document.location.reload();
              return false;
            } else {
              $.showAlert({ title: "Error", body: mensaje });
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
  console.log("ARCHIVO SUBIDO: ", e);
  alert("Please select a PDF file ROL: ", e);
  $.showAlert({
    title: "Atención",
    body: "El Archivo debe ser una imagen, word, excel o pdf.",
  });
  var fileExtension = [
    "jpeg",
    "jpg",
    "png",
    "doc",
    "docx",
    "pdf",
    "xls",
    "xlsx",
  ];
  if (
    $.inArray($(e).val().split(".").pop().toLowerCase(), fileExtension) == -1
  ) {
    $.showAlert({
      title: "Atención",
      body: "El Archivo debe ser una imagen, word, excel o pdf.",
    });
    $(e).val("");
    return false;
  } else {
    return true;
  }
}

//************************************************************************************************

function borrarArchivo(token) {
  $.showConfirm({
    title: "Por Favor Confirme.",
    body: "Confirma la eliminación del Archivo. No se puede deshacer.",
    textTrue: "Si",
    textFalse: "No",
    onSubmit: function (result) {
      if (result) {
        $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
        $.ajax({
          type: "POST",
          url: "components/propiedad/models/borrar_mandato.php",
          data: "token=" + token,
          success: function (res) {
            var retorno = res.split(",xxx,");
            var resultado = retorno[1];
            var mensaje = retorno[2];
            var token = retorno[3];

            if (resultado == "OK") {
              $.showAlert({ title: "Atención", body: mensaje });
              document.location.reload();
              return false;
            } else {
              $.showAlert({ title: "Error", body: mensaje });
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

function loadChequesList() {
  $.ajax({
    url: "components/cheques/models/leer_valores_cheques.php",
    type: "GET",
    dataType: "json",
    data: {
      fechaDesde: $("#fechaDesde").val(),
      fechaHasta: $("#fechaHasta").val(),
    },
    success: function (data) {

      if (data.error) {
        console.error("Error:", data.error);
        $("#tablaCheques tbody").empty(); // Limpiar la tabla si hay error
        return;
      }

      var dataTable = $("#tablaCheques").DataTable();
      dataTable.clear().destroy(); // Limpiar y destruir la tabla existente

      $("#tablaCheques tbody").empty();

      $.each(data, function (index, item) {
        var row = `
              <tr>
                  <td>${item.ficha_arriendo}</td>
                  <td>${item.monto}</td>
                  <td>${item.girador}</td>
                  <td>${item.codigo_banco}</td>
                  <td>${item.nombre_banco}</td>
                  <td>${item.fecha_cobro}</td>
                  <td>${item.id_propiedad}</td>
                  <td>${item.direccion}</td>
                  <td>${item.numero}</td>
                  <td>${item.tipo_propiedad}</td>
              </tr>`;
        $("#tablaCheques tbody").append(row);
      });

      $("#tablaCheques").DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        language: {
          lengthMenu: "Mostrar _MENU_ registros por página",
          zeroRecords: "No se encontraron registros",
          info: "Mostrando página _PAGE_ de _PAGES_",
          infoEmpty: "No hay registros disponibles",
          infoFiltered: "(filtrado de _MAX_ registros totales)",
          search: "Buscar:",
          paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior",
          },
        },
      });
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud:", error);
    },
  });
}

$(document).ready(function () {
  $("#fechaDesde").datepicker({
    dateFormat: "dd/mm/yy",
    regional: "es",
  });
  $("#fechaHasta").datepicker({
    dateFormat: "dd/mm/yy",
    regional: "es",
  });

  $("#filtrarFechas").on("click", function () {
    loadChequesList();
  });

  // Cargar la lista de cheques al cargar la página
  loadChequesList();
});



// Función para formatear moneda
function formatCurrency(value) {
  var number = parseFloat(value);
  if (isNaN(number)) {
    return ''; // Retorna vacío si no es un número
  }
  return '$ ' + number.toLocaleString('es-ES', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

// Función para cargar administradores desde el servidor
// Función para cargar la lista de administradores en un select
function loadAdministradores() {
  $.ajax({
    url: 'components/contribucion/models/filtro_contribucion.php',
    type: 'GET',
    dataType: 'json',
    success: function (response) {
      var select = $('#selectAdministrador');
      select.empty(); // Limpiar opciones previas
      select.append('<option value="">Seleccione Administrador</option>'); // Opción predeterminada

      if (response.status === 'success') {
        $.each(response.data, function (index, admin) {
          select.append(`<option value="${admin.id_sucursal}">${admin.nombre}</option>`);
        });
      } else {
        console.error('Error: Formato de respuesta inesperado');
      }
    },
    error: function (xhr, status, error) {
      console.error('Error al cargar administradores:', error);
    }
  });
}

// Función para cargar la lista de contribuciones en el DataTable
function loadContribucionesList() {
  var fechaDesde = $('#fechaDesde').val();
  var fechaHasta = $('#fechaHasta').val();
  var idAdministrador = $('#selectAdministrador').val();

  // Validar si las fechas son correctas y existen
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
      idAdministrador: idAdministrador // Enviar el ID del administrador seleccionado
    },
    success: function (data) {
      if ($.fn.DataTable.isDataTable('#contribuciones')) {
        $('#contribuciones').DataTable().clear().destroy(); // Limpiar y destruir el DataTable actual si existe
      }
      $('#contribuciones tbody').empty();
      $.each(data.data, function (index, contribucion) {
        $('#contribuciones tbody').append(
          `<tr>
                      <td>${contribucion.rol}</td>
                      <td>${new Date(contribucion.fecha).toLocaleDateString('es-ES')}</td>
                      <td>${contribucion.tipo_rol}</td>
                      <td>${contribucion.idpropiedad}</td>
                      <td>${contribucion.direccion}</td>
                      <td>${contribucion.ano_contrib}</td>
                      <td>${contribucion.descripcion}</td>
                      <td>${contribucion.mes_contrib}</td>
                      <td>${formatCurrency(contribucion.cuota)}</td>
                      <td>${formatCurrency(contribucion.valor_cuota)}</td>
                      <td>${formatCurrency(contribucion.monto_pagado)}</td>
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
      });

      // Inicializar DataTable
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
        order: [[1, 'asc']], // Ordenar por la segunda columna (fecha) en orden ascendente
        buttons: [
          {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Descargar Excel',
            title: 'Listado contribuciones',
            className: 'btn btn-success',
            action: function (e, dt, button, config) {
              // Validar fechas antes de descargar Excel
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
    error: function (xhr, status, error) {
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
    success: function (response) {
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
    error: function (xhr, status, error) {
      console.error('Error al guardar contribución:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Ocurrió un error al intentar guardar la contribución.'
      });
    }
  });
}

$(document).ready(function () {
  loadAdministradores();

  // Configurar el evento para filtrar fechas
  $('#filtrarFechas').click(function () {
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

function updateFileName() {
  const fileInput = document.getElementById('excelFile');
  const fileNameDisplay = document.getElementById('fileName');

  if (fileInput.files.length > 0) {
    fileNameDisplay.textContent = fileInput.files[0].name;
  } else {
    fileNameDisplay.textContent = 'Ningún archivo seleccionado';
  }
}

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

  const file = input.files[0];
  const reader = new FileReader();

  reader.onload = function (e) {
    const data = new Uint8Array(e.target.result);
    const workbook = XLSX.read(data, { type: "array" });
    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
    const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

    const formattedData = jsonData.map((row, index) => {
      if (index === 0) return row; // Saltar la fila del encabezado

      return row.map(cell => {
        if (typeof cell === "number" && cell > 25569) {
          const jsDate = new Date((cell - 25569) * 86400 * 1000);
          jsDate.setDate(jsDate.getDate() + 1); // Ajustar el desfase de un día
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

  reader.onerror = function (error) {
    console.error("Error reading file:", error);
  };

  reader.readAsArrayBuffer(file);
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
        // Reemplazar la alerta por SweetAlert
        Swal.fire({
          position: 'center', // Cambiado a 'center' para centrar el mensaje
          icon: 'success',
          title: 'Datos cargados exitosamente.',
          showConfirmButton: false,
          timer: 1500
        });
        $('#modalExcelUpload').modal('hide');
        location.reload(); // Recargar la página o la tabla para ver los nuevos datos
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
    success: function (data) {
      // Clear the existing table body
      $('#detalleContribuciones tbody').empty();

      if (data && data.length > 0) {
        // Populate the table with data
        data.forEach(function (item) {
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
    error: function (xhr, status, error) {
      console.error('Error fetching contribuciones:', error);
      alert('Ocurrió un error al cargar los datos.');
    }
  });
}

function loadCuentasContablesList() {
  $.ajax({
    url: 'components/ctacontables/models/ver_cuenta_contable.php', // Cambia esto a la ruta de tu script PHP
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      if ($.fn.DataTable.isDataTable('#cuentasContables')) {
        $('#cuentasContables').DataTable().clear().destroy(); // Limpiar y destruir el DataTable actual si existe
      }
      $('#cuentasContables tbody').empty();
      $.each(data, function (index, cuenta) {
        $('#cuentasContables tbody').append(
          `<tr>
                      <td>${cuenta.ctacontable}</td>
                      <td>${cuenta.nombre}</td>
                      <td>${formatCurrency(cuenta.cargo)}</td>
                      <td>${formatCurrency(cuenta.abono)}</td>
                      <td>${formatCurrency(cuenta.saldo)}</td>
                      <td>${cuenta.ejectivo}</td>
                      <td>
                          <button class="btn btn-info" onclick="verDetalles('${cuenta.ctacontable}')">
                              Detalles
                          </button>
                      </td>
                  </tr>`
        );
      });

      // Inicializar DataTable
      $('#cuentasContables').DataTable({
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
        order: [[0, 'asc']] // Ordenar por la primera columna (Cta Contable) en orden ascendente
      });
    },
    error: function (xhr, status, error) {
      console.error('Error:', error);
      $('#cuentasContables tbody').empty();
    }
  });
}

$(document).ready(function () {
  loadCuentasContablesList();
});

function verDetalles(ctaContable) {
  localStorage.setItem('ctacontable', ctaContable);

  $.ajax({
    url: 'components/ctacontables/models/get_detalles_cuenta_contable.php',
    type: 'GET',
    dataType: 'json',
    data: { ctacontable: ctaContable },
    success: function (data) {
      if ($.fn.DataTable.isDataTable('#detallesTable')) {
        $('#detallesTable').DataTable().clear().destroy();
      }
      $('#detallesTable tbody').empty();

      $.each(data, function (index, detalle) {
        $('#detallesTable tbody').append(
          `<tr>
                      <td>${detalle.ctacontable}</td>
                      <td>${detalle.nombre}</td>
                      <td>${detalle.razon}</td>
                      <td>${formatCurrency(detalle.cargo)}</td>
                      <td>${formatCurrency(detalle.abono)}</td>
                      <td>${formatCurrency(detalle.saldo)}</td>
                  </tr>`
        );
      });

      // Inicializar DataTable con exportación a Excel en la vista de detalles
      $('#detallesTable').DataTable({
        language: {
          search: "Buscar:",
          paginate: { next: "Siguiente", previous: "Anterior" },
          info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
          infoEmpty: "Mostrando 0 a 0 de 0 entradas",
          infoFiltered: "(filtrado de _MAX_ entradas en total)"
        },
        lengthMenu: [10, 25, 50, 100],
        dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Exportar a Excel',
            title: `Detalles de Cuenta Contable - ${ctaContable}`,
            className: 'btn btn-success',
            action: function (e, dt, button, config) {
              if (data.length === 0) {
                Swal.fire({
                  icon: 'error',
                  title: 'Sin datos',
                  text: 'No hay datos disponibles para exportar.'
                });
                return;
              }
              $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
            }
          }
        ]
      });

      // Mostrar el modal de detalles
      $('#detallesModal').modal('show');
    },
    error: function (xhr, status, error) {
      console.error('Error al obtener los detalles:', error);
    }
  });
}


