sessionStorage.clear();
sessionStorage.removeItem("fecha_desde");
sessionStorage.removeItem("fecha_hasta");



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
      document.location.href = "index.php?component=propiedad&view=propiedad&token=" + token;
      return false;
    } else {
      $.showAlert({ title: "Error", body: mensaje });
      return false;
    }
  });
} //function enviar

//Desde acá código para Datatable listado
//*****************************************************************************************
function loadReajuste() {
  $(document).ready(function () {
    $("#tabla").DataTable({
      order: [[0, "asc"]],
      processing: true,
      serverSide: true,
      pageLength: 25,
      columnDefs: [{ orderable: false, targets: [6, 7, 8, 9, 10] }],
      ajax: {
        url: "components/propiedad/models/propiedad_list_procesa.php",
        type: "POST",
      },
      language: {
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "No encontrado",
        info: "Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)",
        infoEmpty: "Sin resultados",
        infoFiltered: " <strong>Total de registros filtrados: _TOTAL_ </strong>",
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

    $("<div id='divbotonbuscar' ><i id='buscar' class='fas fa-search'></i></div>").insertBefore(
      ".dataTables_filter input"
    );

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
        infoFiltered: " <strong>Total de registros filtrados: _TOTAL_ </strong>",
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

    $("<div id='divbotonbuscar' ><i id='buscar' class='fas fa-search'></i></div>").insertBefore(
      ".dataTables_filter input"
    );

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
          data: { token: token, token_propiedad: token_propiedad, participacion: 0, accion: "D" },
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
        infoFiltered: " <strong>Total de registros filtrados: _TOTAL_ </strong>",
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

    $("<div id='divbotonbuscar' ><i id='buscar' class='fas fa-search'></i></div>").insertBefore(
      ".dataTables_filter input"
    );

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
  alert("Please select a PDF file MOVIMIENTO: ", e);
  $.showAlert({ title: "Atención", body: "El Archivo debe ser una imagen, word, excel o pdf." });
  var fileExtension = ["jpeg", "jpg", "png", "doc", "docx", "pdf", "xls", "xlsx"];
  if ($.inArray($(e).val().split(".").pop().toLowerCase(), fileExtension) == -1) {
    $.showAlert({ title: "Atención", body: "El Archivo debe ser una imagen, word, excel o pdf." });
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
} //function borrarArchivo(token)

function loadVariosAcreedores_List(){
  $(document).ready(function() {
	
	
	// Recuperar valores de los elementos del DOM de forma segura
	var fecha_desde = document.getElementById("fecha_desde")?.value ?? '';
	var fecha_hasta         = document.getElementById("fecha_hasta")?.value ?? '';
	
    sessionStorage.setItem("fecha_desde", fecha_desde);
	sessionStorage.setItem("fecha_hasta", fecha_hasta);	  

    var ajaxUrl = "components/movimiento/models/movimiento_varios_acreedores_list_procesa.php?" +
        "fecha_desde=" + encodeURIComponent(fecha_desde) + 
        "&fecha_hasta=" + encodeURIComponent(fecha_hasta);

    // Comprobar si la tabla ya ha sido inicializada
    if ($.fn.DataTable.isDataTable('#reajustes')) {
        // Recargar datos sin reinicializar
        var table = $('#reajustes').DataTable();
        table.ajax.url(ajaxUrl).load();
    } else {
        // Inicializar DataTable si no está ya inicializada
        $('#reajustes').DataTable({
            "order": [[0, "desc"]],
            "processing": true,
            "serverSide": true,
			"lengthMenu": [[10, 25, 50, 100, 5000], [10, 25, 50, 100, "Todos"]],
            "columnDefs": [{ orderable: false, targets: [0] ,

        render: (data, type, row) => {
          return '<div style="text-align:right;">' + formateoNulos(formateoDivisa(data)) + '</div>';
        },
        targets: 6
			}],
            "ajax": {
                "url": ajaxUrl,
                "type": "POST",
                "error": function(xhr, error, thrown) {
                    console.error("Error al cargar los datos:", error, thrown);
                }
            },
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No encontrado",
                "info": "Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)",
                "infoEmpty": "Sin resultados",
                "infoFiltered": " <strong>Total de registros filtrados: _TOTAL_ </strong>",
                "loadingRecords": "Cargando...",
                "search": "",
                "processing": "Procesando...",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "siguiente",
                    "previous": "anterior"
                }
            },
            "drawCallback": function(settings) {
                // Inicializar tooltips después de que la tabla se haya redibujado
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        // Desactiva la búsqueda al presionar una tecla
        $("div.dataTables_filter input").unbind();

        // Agrega el botón de búsqueda si no existe
        if (!$('#divbotonbuscar').length) {
            $("<div id='divbotonbuscar'><button id='buscar' class='btn btn-light btn-buscar-tablas'>Buscar</button></div>").insertBefore('.dataTables_filter input');
        }

        $(".dataTables_filter").css("display","none");

        // Configura el evento de clic para el botón de búsqueda
        $('#buscar').off('click').on('click', function(e) {
            // Recargar los datos de la tabla con los nuevos filtros
            loadVariosAcreedores_List();
        });
		
    }
});
 
	
}

function generarExcel(urlbase){
    // Recuperar valores de los elementos del DOM de forma segura
	var fecha_desde = sessionStorage.getItem("fecha_desde");
	var fecha_hasta = sessionStorage.getItem("fecha_hasta");

    var ajaxUrl = "components/movimiento/models/movimiento_varios_acreedores_list_procesa_excel.php?" +
        "fecha_desde=" + encodeURIComponent(fecha_desde) + 
        "&fecha_hasta=" + encodeURIComponent(fecha_hasta);
	$.ajax({
			type: 'GET',
			url: ajaxUrl,
			success: function(res){
				window.open(urlbase+'/upload/movimiento/excel/'+res, '_blank');
			}
	}); 
	
}
