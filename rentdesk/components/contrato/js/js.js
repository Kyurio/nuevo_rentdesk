function enviar() {
  var token_arrendatario = $("#token_arrendatario").val();
  var token_propiedad = $("#token_propiedad").val();
  var token_usuario = $("#token_usuario").val();

  if (token_arrendatario == "") {
    $.showAlert({ title: "Atención", body: "Debe ingresar el Arrendatario" });
    return;
  }

  if (token_propiedad == "") {
    $.showAlert({ title: "Atención", body: "Debe ingresar la propiedad" });
    return;
  }

  if (token_usuario == "") {
    $.showAlert({ title: "Atención", body: "Debe ingresar el ejecutivo(a)" });
    return;
  }

  var archivo = $("#archivo").val();
  var archivo_bd = $("#archivo_bd").val();

  if (archivo == "" && archivo_bd == "N") {
    $.showAlert({ title: "Atención", body: "Debe Adjuntar el mandato" });
    return;
  }

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
  var formData = new FormData(document.getElementById("formulario"));

  $.ajax({
    url: "components/contrato/models/insert_update.php",
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
      document.location.href = "index.php?component=contrato&view=contrato&token=" + token;
      return false;
    } else {
      $.showAlert({ title: "Error", body: mensaje });
      return false;
    }
  });
} //function enviar

function agregarProducto() {
  var producto = $("#producto").val();
  var fecha_inicio = $("#fecha_inicio").val();
  var valor_cuota = $("#valor_cuota").val();
  var plazo = $("#plazo").val();
  var token_contrato = $("#token_contrato").val();

  if (producto == "") {
    $.showAlert({ title: "Atención", body: "Debe ingresar el producto" });
    return;
  }

  if (fecha_inicio == "") {
    $.showAlert({ title: "Atención", body: "Debe ingresar la fecha de inicio" });
    return;
  }

  if (valor_cuota == "") {
    $.showAlert({ title: "Atención", body: "Debe ingresar el valor del producto" });
    return;
  }

  if (plazo == "") {
    $.showAlert({
      title: "Atención",
      body: "Debe ingresar el plazo en el que se cancelara el producto",
    });
    return;
  }

  var min_valor = $("#producto").find(":selected").data("min_valor");
  var val_arriendo = $("#valor_arriendo").val();

  if (min_valor == "") {
    min_valor = 0;
  }

  if (min_valor > 0) {
    if (min_valor > val_arriendo) {
      var mensaje =
        "Este producto solo puede ser cargado en arriendos igual o superior a " + min_valor;
      $.showAlert({ title: "Error", body: mensaje });
      return;
    }
  }

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  var formData = new FormData(document.getElementById("formulario"));

  $.ajax({
    url: "components/contrato/models/insert_update_producto.php",
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
      document.location.href = "index.php?component=contrato&view=contrato&token=" + token_contrato;
      return false;
    } else {
      $.showAlert({ title: "Error", body: mensaje });
      return false;
    }
  });
} //function enviar

//Desde acá código para Datatable listado
//*****************************************************************************************
function loadContrato(token, nav) {
  $(document).ready(function () {
    $("#tabla").DataTable({
      order: [[0, "desc"]],
      processing: true,
      serverSide: true,
      pageLength: 10,
      columnDefs: [{ orderable: false, targets: [6, 7] }],
      ajax: {
        url:
          "components/contrato/models/contrato_list_procesa.php?token_propiedad=" +
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

//************************************************************************
function deleteContrato(token) {
  $.showConfirm({
    title: "Por Favor Confirme.",
    body: "Realmente desea Eliminar El registro? No se puede deshacer.",
    textTrue: "Si",
    textFalse: "No",
    onSubmit: function (result) {
      if (result) {
        $.ajax({
          type: "POST",
          url: "components/contrato/models/delete.php",
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

//************************************************************************
function deleteProducto(token, token_contrato) {
  $.showConfirm({
    title: "Por Favor Confirme.",
    body: "Realmente desea Eliminar El registro? No se puede deshacer.",
    textTrue: "Si",
    textFalse: "No",
    onSubmit: function (result) {
      if (result) {
        $.ajax({
          type: "POST",
          url: "components/contrato/models/delete_producto.php",
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
function loadUsuarios() {
  $(document).ready(function () {
    $("#tabla").DataTable({
      order: [[0, "asc"]],
      processing: true,
      serverSide: true,
      pageLength: 10,
      columnDefs: [{ orderable: false, targets: [2, 3] }],
      ajax: {
        url: "../models/listado_usuarios.php",
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

function agregarUser(token, nombre) {
  parent.jQuery("#token_usuario").val(token);
  parent.jQuery("#nombre_usuario").val(nombre);
  parent.jQuery.fancybox.close();
}

//**************************************************************************************************************

//*****************************************************************************************
function loadPropiedad() {
  $(document).ready(function () {
    $("#tabla").DataTable({
      order: [[0, "asc"]],
      processing: true,
      serverSide: true,
      pageLength: 10,
      columnDefs: [{ orderable: false, targets: [7] }],
      ajax: {
        url: "../models/listado_propiedades.php",
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

function agregarPropiedad(token, nombre) {
  parent.jQuery("#token_propiedad").val(token);
  parent.jQuery("#propiedad").val(nombre);
  parent.jQuery.fancybox.close();
}

//**************************************************************************************************************

//*****************************************************************************************
function loadArrendatario() {
  $(document).ready(function () {
    $("#tabla").DataTable({
      order: [[0, "asc"]],
      processing: true,
      serverSide: true,
      pageLength: 10,
      columnDefs: [{ orderable: false, targets: [6] }],
      ajax: {
        url: "../models/listado_arrendatarios.php",
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

function agregarArrendatario(token, nombre) {
  parent.jQuery("#token_arrendatario").val(token);
  parent.jQuery("#arrendatario").val(nombre);
  parent.jQuery.fancybox.close();
}

function activarContrato(val) {
  if (val == "N") {
    $.showAlert({ title: "Atención", body: "Debe ingresar a lo menos un producto" });
    return;
  } else {
    $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

    var formData = new FormData(document.getElementById("formulario"));
    $.ajax({
      url: "components/contrato/models/insert_activacion.php",
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
        document.location.href = "index.php?component=contrato&view=contrato&token=" + token;
        return false;
      } else {
        $.showAlert({ title: "Error", body: mensaje });
        return false;
      }
    });
  }
} //function enviar

function cambiaProducto() {
  var editable = $("#producto").find(":selected").data("editable");
  var valor = $("#producto").find(":selected").data("valor");
  var min_valor = $("#producto").find(":selected").data("min_valor");
  var val_arriendo = $("#valor_arriendo").val();

  if (min_valor == "") {
    min_valor = 0;
  }

  if (min_valor > 0) {
    if (min_valor > val_arriendo) {
      var mensaje = "Este producto solo puede ser cargado en arriendos superiores a " + min_valor;
      $.showAlert({ title: "Error", body: mensaje });
      return;
    }
  }

  if (editable == "N") {
    $("#valor_cuota").val(valor);
    $("#valor_cuota").prop("readonly", true);
  } else {
    $("#valor_cuota").val("");
    $("#valor_cuota").prop("readonly", false);
  }
}

function cambiaPack(fecha, separador_mil) {
  var json = $("#pack").find(":selected").data("datos");
  var cant_prod = 0;
  var content_js = "";
  var content_hidden = "";
  var content = '<table border="1" cellspacing="0" cellpadding="0" class=\'tabla-propiedad\'>';
  content += "<tbody>";
  content += "<tr>";
  content += "<td height='28'><strong>Producto</strong></td>";
  content += "<td height='28'><strong>Responsable</strong></td>";
  content += "<td height='28'><strong>Tipo Monto</strong></td>";
  content += "<td height='28'><strong>Fecha Inicio</strong></td>";
  content += "<td height='28'><strong>Valor</strong></td>";
  content += "<td height='28'><strong>Tipo Moneda</strong></td>";
  content += "<td height='28'><strong>Plazo</strong></td>";
  content += "</tr>";

  $(json).each(function (indice, elemento) {
    if (elemento.editable == "N") {
      var readonly = "readonly";
    } else {
      var readonly = "";
    }

    if (elemento.valor == "null" || elemento.valor == null) {
      var valor = "";
    } else {
      var valor = elemento.valor;
    }

    content += "<tr>";
    content += "<td>" + elemento.descripcion_prod;
    content +=
      '<input type="hidden" name="prod_' +
      indice +
      '" id="prod_' +
      indice +
      '" value="' +
      elemento.token +
      '">';
    content +=
      '<input type="hidden" name="nom_' +
      indice +
      '" id="nom_' +
      indice +
      '" value="' +
      elemento.descripcion_prod +
      '">';
    content +=
      '<input type="hidden" name="min_valor_' +
      indice +
      '" id="min_valor_' +
      indice +
      '" value="' +
      elemento.min_valor +
      '">';
    content +=
      '<input type="hidden" name="tipo_prod_' +
      indice +
      '" id="tipo_prod_' +
      indice +
      '" value="' +
      elemento.id_tipo_producto +
      '">';
    content += "</td>";
    content += "<td>" + elemento.tipo_responsable + "</td>";
    content += "<td>" + elemento.tipo_monto + "</td>";
    content += '<td> <div class="input-group" id="datetimepicker_' + indice + '"> ';
    content +=
      '		   <input data-token="' +
      elemento.token +
      '" type="text" class="form-control" maxlength="50" name="fecha_inicio_' +
      indice +
      '" id="fecha_inicio_' +
      indice +
      '" placeholder="dd-mm-yyyy"  required data-validation-required value="' +
      fecha +
      '" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >';
    content +=
      '		   <span class="input-group-addon calendariodatepickter"><i class="fa fa-calendar" aria-hidden="true"></i></span>';
    content += "	</div></td>";
    content +=
      '<td><input data-token="' +
      elemento.token +
      '" type="text" class="form-control" min="1" name="valor_cuota_' +
      indice +
      '" id="valor_cuota_' +
      indice +
      '" placeholder="valor" required ' +
      readonly +
      ' data-validation-required value="' +
      valor +
      "\" onBlur=\"elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'2','" +
      separador_mil +
      "');\"  >";
    content += "</td>";
    content += "<td>" + elemento.tipo_moneda + "</td>";
    content +=
      '<td> <input data-token="' +
      elemento.token +
      '" type="number" class="form-control" min="1" name="plazo_' +
      indice +
      '" id="plazo_' +
      indice +
      '" placeholder="plazo" required data-validation-required  value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  ></td>';
    content += "</tr>";

    content_js +=
      "<script> $(function () { $('#datetimepicker_" +
      indice +
      '\').datetimepicker({format : "DD-MM-YYYY",	defaultDate: moment("' +
      fecha +
      '","DD-MM-YYYY") }); }); </script>';

    cant_prod = indice + 1;
  });

  content += "</tbody>";
  content += "</table>";

  content_hidden = '<input type="hidden" name="cant_prod" id="cant_prod" value=' + cant_prod + ">";

  content += content_js;
  content += content_hidden;
  $(".contenedor_productos").html("");
  $(".contenedor_productos").html(content);
}

//*************************************************************************************************

function validaArchivo(e) {
  console.log("ARCHIVO SUBIDO: ", e);
  alert("Please select a PDF file CONTRATO: ", e);

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
          url: "components/contrato/models/borrar_contrato.php",
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

//************************************************************************
function terminarContrato(token) {
  $.showConfirm({
    title: "Por Favor Confirme.",
    body: "Realmente desea Forzar El Termino del contrato? No se puede deshacer.",
    textTrue: "Si",
    textFalse: "No",
    onSubmit: function (result) {
      if (result) {
        $.ajax({
          type: "POST",
          url: "components/contrato/models/forzar_termino.php",
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

function agregarPack() {
  var cant_productos = $("#cant_prod").val();
  var indice_prod_arriendo = -1;
  var indice_prod_min_valor = -1;
  var valor_arriendo = 0;
  var min_valor_act = null;
  var min_valor = null;
  var min_valor_calculado = null;

  if (cant_productos == 0) {
    $.showAlert({ title: "Atención", body: "Debe ingresar productos" });
    return;
  }

  /*Proceso de Validaciones*/
  var i;
  for (i = 0; i < cant_productos; i++) {
    var producto = $("#prod_" + i).val();
    var fecha_inicio = $("#fecha_inicio_" + i).val();
    var valor_cuota = $("#valor_cuota_" + i).val();
    var plazo = $("#plazo_" + i).val();
    var tipo_producto = $("#tipo_prod_" + i).val();
    var min_valor = $("#min_valor_" + i).val();

    if (producto == "") {
      $.showAlert({ title: "Atención", body: "Debe ingresar el producto" });
      return;
    }

    if (fecha_inicio == "") {
      $.showAlert({ title: "Atención", body: "Debe ingresar la fecha de inicio" });
      $("#fecha_inicio_" + i).focus();
      return;
    }

    if (valor_cuota == "") {
      $.showAlert({ title: "Atención", body: "Debe ingresar el valor del producto" });
      $("#valor_cuota_" + i).focus();
      return;
    }

    if (plazo == "") {
      $.showAlert({
        title: "Atención",
        body: "Debe ingresar el plazo en el que se cancelara el producto",
      });
      $("#plazo_" + i).focus();
      return;
    }

    if (tipo_producto == "1") {
      indice_prod_arriendo = i;
      valor_arriendo = parseInt(replaceAll(valor_cuota, ".", ""));
    }

    if (min_valor != "0") {
      min_valor_act = parseInt(min_valor);
      if (min_valor_calculado == null) {
        min_valor_calculado = min_valor_act;
        indice_prod_min_valor = i;
      } else {
        if (min_valor_act < min_valor_calculado) {
          min_valor_calculado = min_valor_act;
          indice_prod_min_valor = i;
        }
      }
    }
  }

  if (indice_prod_arriendo < 0) {
    $.showAlert({ title: "Atención", body: "Debe ingresar un producto de Arriendo" });
    return;
  }

  if (min_valor_calculado != null) {
    if (valor_arriendo < min_valor_calculado) {
      var producto_min_valor = $("#nom_" + indice_prod_min_valor).val();
      $.showAlert({
        title: "Atención",
        body:
          'El producto "' +
          producto_min_valor +
          '" solo puede ser aplicado para arriendos iguales o superiores a ' +
          min_valor_calculado +
          " ",
      });
      $("#valor_cuota_" + indice_prod_arriendo).focus();
      return;
    }
  }

  /*Proceso de Guardado*/
  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
  var token_contrato = $("#token_contrato").val();
  var j;
  var resumen = "";
  for (j = 0; j < cant_productos; j++) {
    var nom_producto = $("#nom_" + j).val();
    var producto = $("#prod_" + j).val();
    var fecha_inicio = $("#fecha_inicio_" + j).val();
    var valor_cuota = $("#valor_cuota_" + j).val();
    var plazo = $("#plazo_" + j).val();

    $.ajax({
      type: "POST",
      async: false,
      url: "components/contrato/models/insert_update_producto.php",
      data:
        "producto=" +
        producto +
        "&fecha_inicio=" +
        fecha_inicio +
        "&valor_cuota=" +
        valor_cuota +
        "&plazo=" +
        plazo +
        "&token_contrato=" +
        token_contrato,
      success: function (res) {
        var retorno = res.split(",xxx,");
        var resultado = retorno[1];
        var mensaje = retorno[2];
        var token = retorno[3];

        if (resultado == "OK") {
          resumen += '<div class="col-5" style="border: 1px solid;">' + nom_producto + "</div>";
          resumen += '<div class="col-7" style="border: 1px solid;">' + mensaje + "</div>";
        } else {
          resumen += '<div class="col-5" style="border: 1px solid;">' + nom_producto + "</div>";
          resumen += '<div class="col-7" style="border: 1px solid;">' + mensaje + "</div>";
        }
      },
    });
  }

  var msg = '<form><div class="form-group row">' + resumen + "</div></form>";
  $.showModal({
    title: "Resultado Operación",
    body: msg,
    footer: '<button type="submit" class="btn btn-primary">OK</button>',
    onCreate: function (modal) {
      // create event handler for form submit and handle values
      $(modal.element).on("click", "button[type='submit']", function (event) {
        event.preventDefault();
        modal.hide();
      });
    },
    onDispose: function () {
      document.location.href = "index.php?component=contrato&view=contrato&token=" + token_contrato;
    },
  });
} //function enviar

function replaceAll(text, busca, reemplaza) {
  while (text.toString().indexOf(busca) != -1) text = text.toString().replace(busca, reemplaza);
  return text;
}
