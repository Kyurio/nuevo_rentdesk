function enviar() {
  var archivo = $("#archivo").val();
  var archivo_bd = $("#archivo_bd").val();
  var n = $("#n").val();

  if (archivo == "") {
    $.showAlert({ title: "Atención", body: "Debe Adjuntar el archivo" });
    return;
  }

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  var formData = new FormData(document.getElementById("formulario"));

  $.ajax({
    url: "components/cargaMasiva/models/insert_update.php",
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
        "index.php?component=cargaMasiva&view=cargaMasiva&t=" + $("#token").val() + "&n=" + n;
      return false;
    } else {
      $.showAlert({ title: "Error", body: mensaje });
      return false;
    }
  });
} //function enviar

//*************************************************************************************************

function validaArchivo(e) {
	console.log("ARCHIVO SUBIDO: ", e);
	alert("Please select a PDF file CARGA MASIVA: ", e);
  var fileExtension = ["txt", "csv", "TXT", "CSV"];
  if ($.inArray($(e).val().split(".").pop().toLowerCase(), fileExtension) == -1) {
    $.showAlert({ title: "Atención", body: "La carga debe corresponder a un archivo txt o csv" });
    $(e).val("");
    return false;
  } else {
    return true;
  }
}

//************************************************************************************************
//Desde acá código para Datatable listado
//*****************************************************************************************
function loadCargaMasivas(token, nav, n) {
  $(document).ready(function () {
    $("#tabla").DataTable({
      order: [[0, "desc"]],
      processing: true,
      serverSide: true,
      pageLength: 5,
      columnDefs: [{ orderable: false, targets: [5] }],
      ajax: {
        url:
          "components/cargaMasiva/models/cargaMasiva_list_procesa.php?token=" +
          token +
          "&nav=" +
          nav +
          "&n=" +
          n,
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

//************************************************************************************************
//Desde acá código para Datatable Log
//*****************************************************************************************
function loadCargaMasivaLog(token, nav) {
  $(document).ready(function () {
    $("#tabla").DataTable({
      order: [[0, "asc"]],
      processing: true,
      serverSide: true,
      pageLength: 10,
      columnDefs: [],
      ajax: {
        url:
          "components/cargaMasiva/models/cargaMasiva_list_procesa_log.php?token=" +
          token +
          "&nav=" +
          nav,
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
