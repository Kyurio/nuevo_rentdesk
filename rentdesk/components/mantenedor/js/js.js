
$(document).ready(function () {
  $(".js-example-responsive").select2({
    width: "100%",
    placeholder: "Seleccione",
  });
});

$(document).ready(function () {
  var elementosFormControl = document.querySelectorAll("select.form-control");
  elementosFormControl.forEach(function (elemento) {
    elemento.classList.add("form-select");
  });

  cargarCtaContableList();
  cargarRolList();
  loadUsuarios_List();
  //cargarSucursalList();
});

function resetFormById(idForm) {
  $("#" + idForm)[0].reset();
  $('#sucursal').val(null).trigger('change');

}

/*CUENTAS CONTABLES */
function cargarCtaContableList() {
  $("#mant-cta-contable-table").DataTable({
    dom: 'B<"clear">lfrtip',
    destroy: true,
    targets: "no-sort",
    bSort: false,
    order: [[0, "desc"]],
    pagingType: "full_numbers",
    pageLength: 10,
    lengthMenu: [
      [10, 25, 50, 100, 5000],
      [10, 25, 50, 100, "Todos"],
    ],
    columnDefs: [

      {
        render: (data, type, row) => {
          return data;
        },
        targets: 0,
      },

      {
        render: (data, type, row) => {
          return data;
        },
        targets: 1,
      },

      {
        render: (data, type, row) => {
          return formatNroCtaContable(data);
        },
        targets: 2,
      },

      {
        render: (data, type, row) => {
          return data;
        },
        targets: 3,
      },


      {
        render: (data, type, row) => {
          return data;
        },
        targets: 4,
      },

      
      {
        render: (data, type, row) => {
          const isChecked = data == 1 ? "checked" : "";
          const checkboxId = `ctaContableActivoEditar_${row[0]}`; // Assuming row[0] is unique for each row

          return `<div class="d-flex">
                        <label class="switch">
                            <input type="checkbox" id='${checkboxId}' name='${row[0]}' ${isChecked} onclick="handleEstadoCtaContableClick(event,this)">
                            <span class="slider round"></span>
                        </label>
                    </div>`;
        },
        targets: 5,
      },
      
      { visible: false, targets: [3] },
    ],
    ajax: {
      url: "components/mantenedor/models/mant_cta_contable_list_procesa.php",
      type: "POST",
    },
    language: {
      lengthMenu: "Mostrar _MENU_ registros por página",
      zeroRecords: "No se encontraron registros",
      info: "Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)",
      infoEmpty: "No existen registros para mostrar",
      infoFiltered: "(filtrado desde _MAX_ total de registros)",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar",
      paginate: {
        first: "Primero",
        last: "Último",
        next: "Siguiente",
        previous: "Anterior",
      },
      buttons: {
        copy: "Copiar",
      },
    },
    createdRow: function (row, data, dataIndex) {
        $(row).addClass("table-secondary");
      }
  });

  $("#mant-cta-contable-table").on("init.dt", function () {
    console.log("DataTables se ha inicializado correctamente en #mant-cta-contable-table");
  });

  $(".dataTables_filter").css("display", "none");
}

function loadUsuarios_List() {
  $("#mant-usuario-table").DataTable({
    dom: 'B<"clear">lfrtip',
    destroy: true,
    targets: "no-sort",
    bSort: false,
    order: [[0, "desc"]],
    pagingType: "full_numbers",
    pageLength: 10,
    lengthMenu: [
      [10, 25, 50, 100, 5000],
      [10, 25, 50, 100, "Todos"],
    ],
    columnDefs: [

      {
        render: (data, type, row) => {
          const isChecked = data == 1 ? "checked" : "";
          const checkboxId = `ctaContableActivoEditar_${row[0]}`; // Assuming row[0] is unique for each row

          return `<div class="d-flex">
                        <label class="switch">
                            <input type="checkbox" id='${checkboxId}' name='${row[0]}' ${isChecked} onclick="handleUsuarioClick(event,this)">
                            <span class="slider round"></span>
                        </label>
                    </div>`;
        },
        targets: 6,
      }
    ],
    ajax: {
      url: "components/mantenedor/models/mant_usuarios_list_procesa.php",
      type: "POST",
    },
    language: {
      lengthMenu: "Mostrar _MENU_ registros por página",
      zeroRecords: "No se encontraron registros",
      info: "Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)",
      infoEmpty: "No existen registros para mostrar",
      infoFiltered: "(filtrado desde _MAX_ total de registros)",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar",
      paginate: {
        first: "Primero",
        last: "Último",
        next: "Siguiente",
        previous: "Anterior",
      },
      buttons: {
        copy: "Copiar",
      },
    },
    createdRow: function (row, data, dataIndex) {
      if (data[4] === "1") {
        // Assuming "activo" field is a string, adjust as needed
        $(row).addClass("table-success");
      } else {
        $(row).addClass("table-secondary");
      }
    },
  });

  $("#mant-usuario-table").on("init.dt", function () {
    console.log("DataTables se ha inicializado correctamente en #mant-usuario-table");
  });

  $(".dataTables_filter").css("display", "none");
}

// Function to handle checkbox click event
function handleEstadoCtaContableClick(event, checkbox) {
  // Prevent the checkbox from changing its state immediately
  event.preventDefault();

  console.log("CHECKBOX: ", checkbox.name);
  const idCtaContable = checkbox.name;
  const isChecked = checkbox.checked;
  console.log("CHECKED: ", isChecked);
  const message = !isChecked
    ? "Deseas Desactivar la Cuenta Contable"
    : "Deseas Activar la Cuenta Contable";

  const confirm = !isChecked ? "Desactivar" : "Activar";

  Swal.fire({
    text: message,
    icon: "warning",
    showDenyButton: true,
    confirmButtonText: confirm,
    denyButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      editarEstadoCtaContable(idCtaContable, isChecked);
    }
  });
}

function handleUsuarioClick(event, checkbox) {
  // Prevent the checkbox from changing its state immediately
  event.preventDefault();

  console.log("CHECKBOX: ", checkbox.name);
  const idUsuario = checkbox.name;
  const isChecked = checkbox.checked;
  console.log("CHECKED: ", isChecked);
  const message = !isChecked
    ? "Deseas desactivar al usuario"
    : "Deseas activar al usuario";

  const confirm = !isChecked ? "Desactivar" : "Activar";

  Swal.fire({
    text: message,
    icon: "warning",
    showDenyButton: true,
    confirmButtonText: confirm,
    denyButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      editarEstadoUsuario(idUsuario, isChecked);
    }
  });
}

function guardarCtaContable() {
  var formData = new FormData(document.getElementById("ingreso_cta_contable"));

  var jsonInformacionNueva = obtenerValoresFormulario("ingreso_cta_contable");

  const cta_contable_input = document.getElementById("nombreCtaContable");
  var nombreCtaContable = cta_contable_input.value;

  const cta_contable_nro_input = document.getElementById("ctaContableNroCuenta");
  var ctaContableNroCuenta = cta_contable_nro_input.value;

  const cta_contable_activo_input = document.getElementById("ctaContableActivo");
  var ctaContableActivo = cta_contable_activo_input.checked;

  const cta_contable_tipo_input = document.getElementById("tipoCtaContable");
  var ctaContableTipo = cta_contable_tipo_input.value;

  /*VALIDACIONES FORMATOS */
  /*VALIDA QUE NOMBRE BENEFICIARIO TENGA FORMATO CORRECTO */
  if (nombreCtaContable == null || nombreCtaContable == "") {
    $("#nombreCtaContable")[0].setCustomValidity("Debe ingresar Nombre de Cuenta Contable");
    $("#nombreCtaContable")[0].reportValidity();

    return;
  }

  if (ctaContableNroCuenta == null || ctaContableNroCuenta == "") {
    $("#ctaContableNroCuenta")[0].setCustomValidity("Debe ingresar Número de Cuenta Contable");
    $("#ctaContableNroCuenta")[0].reportValidity();

    return;
  }

  if (ctaContableTipo == null || ctaContableTipo == "") {
    $("#ctaContableTipo")[0].setCustomValidity("Debe ingresar el tipo");
    $("#ctaContableTipo")[0].reportValidity();

    return;
  }


  formData.append("nombreCtaContable", nombreCtaContable);
  formData.append("ctaContableNroCuenta", ctaContableNroCuenta);
  formData.append("ctaContableActivo", ctaContableActivo);
  formData.append("ctaContableTipo", ctaContableTipo);

  var id_ficha = $("#id_ficha").val();
  var url = window.location.href;
  //console.log(url);
  var parametros = new URL(url).searchParams;
  //console.log(parametros.get("token"));
  formData.append("token", parametros.get("token"));

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/insert_cta_contable.php",
    type: "post",
    dataType: "text",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  })
    .done(function (res) {
      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
        $("#modalMantenedorIngresoCuentaContable").modal("hide");
        $("#ingreso_cta_contable")[0].reset();

        Swal.fire({
          title: "Cuenta Contable registrada",
          text: "La cuenta contable se registró correctamente",
          icon: "success",
        });
        var id_comentario = res;
        var jsonInformacioantigua = capturarInformacionAntigua();

        cargarCtaContableList();
        // registroHistorial(
        //   "Crear",
        //   "",
        //   jsonInformacionNueva,
        //   "Beneficiario",
        //   id_ficha,
        //   id_comentario
        // );
         return false;
      } else {
        $("#modalMantenedorIngresoCuentaContable").modal("hide");

        Swal.fire({
          title: "Atención",
          text: "La cuenta contable no se registró",
          icon: "warning",
        });

        return;
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      $("#modalMantenedorIngresoCuentaContable").modal("hide");

      Swal.fire({
        title: "Atención",
        text: "La Cuenta Contable no se registró",
        icon: "warning",
      });
    });
  $("#ingreso_cta_contable")[0].reset();
  $("#modalMantenedorIngresoCuentaContable").modal("hide");
  //cargarCtaContableList();
}

function cargarCtaContableEditar(idCtaContable, nombre, nro_cuenta, tipoMovimiento, activo) {
  // console.log("PARAMETROS ENTRADA: ", {idInfoComentario, comentario});
  console.log("ESTOY EN cargarCtaContableEditar");
  $("#nombreCtaContableEditar").val(nombre);
  $("#ctaContableNroCuentaEditar").val(nro_cuenta);
  $("#ctaContableTipoMovimientoEditar").val(tipoMovimiento);
  $("#ctaContableActivoEditar")[0].checked = activo == 1;
  $("#ID_Cta_Contable_Editar").val(idCtaContable);
}

function editarEstadoCtaContable(idCtaContable, activo) {
  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/editar_estado_cta_contable.php",
    type: "post",
    dataType: "text",
    data: { idCtaContable, activo },
  })
    .done(function (res) {
      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
        Swal.fire({
          title: "Cuenta Contable actualizada",
          text: "La cuenta contable se actualizó correctamente",
          icon: "success",
        });
        var id_comentario = res;
        var jsonInformacioantigua = capturarInformacionAntigua();

        cargarCtaContableList();
        // registroHistorial(
        //   "Crear",
        //   "",
        //   jsonInformacionNueva,
        //   "Beneficiario",
        //   id_ficha,
        //   id_comentario
        // );
        return false;
      } else {
        Swal.fire({
          title: "Atención",
          text: "La cuenta contable no se actualizó",
          icon: "warning",
        });

        return;
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "La cuenta contable no se actualizó",
        icon: "warning",
      });
    });

  //cargarCtaContableList();
}

function editarEstadoUsuario(idUsuario, activo) {
  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/editar_estado_usuario.php",
    type: "post",
    dataType: "text",
    data: { idUsuario, activo },
  })
    .done(function (res) {
      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
        Swal.fire({
          title: "Usuario actualizado",
          text: "El usuario se actualizó correctamente",
          icon: "success",
        });

        loadUsuarios_List();

        return false;
      } else {
        Swal.fire({
          title: "Atención",
          text: "El usuario no se actualizó",
          icon: "warning",
        });

        return;
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "El usuario no se actualizó",
        icon: "warning",
      });
    });

  //cargarCtaContableList();
}

function editarCtaContable() {
  var formData = new FormData(document.getElementById("cta_contable_formulario_editar"));

  formData.set("ctaContableActivoEditar", $("#ctaContableActivoEditar")[0].checked);

  var jsonInformacionNueva = obtenerValoresFormulario("cta_contable_formulario_editar");
  // Decodificar el texto JSON a un array asociativo
  var objeto_json = JSON.parse(jsonInformacionNueva);

  console.log("objeto_json editar : ", objeto_json);

  var id_comentario = objeto_json.ID_Cta_Contable_Editar;
  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/editar_cta_contable.php",
    type: "post",
    dataType: "text",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  })
    .done(function (res) {
      console.log(res);
      Swal.fire({
        title: "Cuenta Contable actualizada",
        text: "La cuenta contable se actualizó correctamente",
        icon: "success",
      });
      var jsonInformacioantigua = capturarInformacionAntigua();
      cargarCtaContableList();
      // registroHistorial(
      //   "Modificar",
      //   jsonInformacioantigua,
      //   jsonInformacionNueva,
      //   "Comentario",
      //   id_ficha,
      //   id_comentario
      // );
	  return false;
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "La cuenta contable no se actualizó",
        icon: "warning",
      });
    });

  $("#cta_contable_formulario_editar")[0].reset();
  $("#modalMantenedorEditarCuentaContable").modal("hide");
  //cargarCtaContableList();
}

function eliminarCtaContable(idCuentaContable) {
  console.log("ID CUENTA CONTABLE: ", idCuentaContable);

  Swal.fire({
    title: "¿Estás seguro?",
    text: "Una vez eliminado, no podrás recuperar esta cuenta contable",
    icon: "warning",
    showDenyButton: true,
    confirmButtonText: "Eliminar",
    denyButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      // Si el usuario hace clic en "Eliminar"
      $.ajax({
        url: "components/mantenedor/models/delete_cta_contable.php",
        type: "POST",
        dataType: "text",
        data: { idCtaContable: idCuentaContable },
        success: function (response) {
          Swal.fire({
            title: "Cuenta Contable eliminada",
            text: "La cuenta contable se eliminó correctamente",
            icon: "success",
          });
          cargarCtaContableList();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error en la solicitud:", textStatus, errorThrown);
          // Aquí puedes mostrar un mensaje de error al usuario o realizar otras acciones
        },
      });
    } else if (result.isDenied) {
      // Si el usuario hace clic en "Cancelar"
      // Aquí puedes cerrar el modal de SweetAlert si lo deseas
      Swal.close();
    }
  });
}

/*ROLES */
function cargarRolList() {
  $("#mant-rol-table").DataTable({
    dom: 'B<"clear">lfrtip',
    destroy: true,
    targets: "no-sort",
    bSort: false,
    order: [[0, "desc"]],
    pagingType: "full_numbers", // Tipo de paginación
    pageLength: 10, // Número de filas por página
    lengthMenu: [
      [10, 25, 50, 100, 5000],
      [10, 25, 50, 100, "Todos"],
    ],
    // "columnDefs": [ { orderable: false, targets: [9] } ],
    columnDefs: [
      {
        render: (data, type, row) => {
          return data;
        },
        targets: 0,
      },
      {
        render: (data, type, row) => {
          return data;
        },
        targets: 1,
      },
      {
        render: (data, type, row) => {
          return data;
        },
        targets: 2,
      },
      {
        render: (data, type, row) => {
          return data;
        },
        targets: 3,
      },
	  {
        render: (data, type, row) => {
          return data;
        },
        targets: 4,
      },
      {
        render: (data, type, row) => {
          const isChecked = data == 1 ? "checked" : "";
          const checkboxId = `rolActivoEditar_${row[0]}`; // Assuming row[0] is unique for each row

          return `<div class="d-flex">
                        <label class="switch">
                            <input type="checkbox" id='${checkboxId}' name='${row[0]}' ${isChecked} onclick="handleEstadoRolClick(event,this)">
                            <span class="slider round"></span>
                        </label>
                    </div>`;
        },
        targets: 5,
      },
      {
        render: (data, type, row) => {
          return data;
        },
        targets: 6,
      },
      { visible: false, targets: [4] },
    ],
    ajax: {
      url: "components/mantenedor/models/mant_roles_list_procesa.php",
      type: "POST",
    },
    language: {
      lengthMenu: "Mostrar _MENU_ registros por página",
      zeroRecords: "No se encontraron registros",
      info: "Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)",
      infoEmpty: "No existen registros para mostrar",
      infoFiltered: "(filtrado desde _MAX_ total de registros)",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar",
      paginate: {
        first: "Primero",
        last: "Último",
        next: "Siguiente",
        previous: "Anterior",
      },
      buttons: {
        copy: "Copiar",
      },
    },
    createdRow: function (row, data, dataIndex) {
        $(row).addClass("table-secondary");
      }
  });

  $("#mant-rol-table").on("init.dt", function () {
    console.log("DataTables se ha inicializado correctamente en #mant-rol-table");
  });

  $(".dataTables_filter").css("display", "none");
}

// Function to handle checkbox click event
function handleEstadoRolClick(event, checkbox) {
  // Prevent the checkbox from changing its state immediately
  event.preventDefault();

  console.log("CHECKBOX: ", checkbox.name);
  const idRol = checkbox.name;
  const isChecked = checkbox.checked;
  console.log("CHECKED: ", isChecked);
  const message = !isChecked ? "Deseas Desactivar el Rol" : "Deseas Activar el Rol";

  const confirm = !isChecked ? "Desactivar" : "Activar";

  Swal.fire({
    text: message,
    icon: "warning",
    showDenyButton: true,
    confirmButtonText: confirm,
    denyButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      editarEstadoRol(idRol, isChecked);
    }
  });
}

function guardarRol() {
  var formData = new FormData(document.getElementById("ingreso_rol"));

  var jsonInformacionNueva = obtenerValoresFormulario("ingreso_rol");

  const rol_nombre_input = document.getElementById("nombreRol");
  var nombreRol = rol_nombre_input.value;

  const rol_descripcion_input = document.getElementById("descripcionRol");
  var descripcionRol = rol_descripcion_input.value;

  const rol_activo_input = document.getElementById("rolActivo");
  var rolActivo = rol_activo_input.checked;

  /*VALIDACIONES FORMATOS */
  /*VALIDA QUE NOMBRE ROL FORMATO CORRECTO */
  if (nombreRol == null || nombreRol == "") {
    $("#nombreRol")[0].setCustomValidity("Debe ingresar Nombre de Rol");
    $("#nombreRol")[0].reportValidity();

    return;
  }

  if (descripcionRol == null || descripcionRol == "") {
    $("#descripcionRol")[0].setCustomValidity("Debe ingresar Descripción de Rol");
    $("#descripcionRol")[0].reportValidity();

    return;
  }

  formData.append("nombreRol", nombreRol);
  formData.append("descripcionRol", descripcionRol);
  formData.append("rolActivo", rolActivo);

  var id_ficha = $("#id_ficha").val();
  var url = window.location.href;
  //console.log(url);
  var parametros = new URL(url).searchParams;
  //console.log(parametros.get("token"));
  formData.append("token", parametros.get("token"));

  $(document).ajaxStart($.blockUI({
    baseZ: 900000,
})).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/insert_rol.php",
    type: "post",
    dataType: "text",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  })
    .done(function (res) {
      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
        $("#modalMantenedorIngresoRol").modal("hide");
        $("#ingreso_rol")[0].reset();

        Swal.fire({
          title: "Rol registrado",
          text: "El rol se registró correctamente",
          icon: "success",
        });
        var id_comentario = res;
        var jsonInformacioantigua = capturarInformacionAntigua();

        cargarRolList();
        // registroHistorial(
        //   "Crear",
        //   "",
        //   jsonInformacionNueva,
        //   "Beneficiario",
        //   id_ficha,
        //   id_comentario
        // );
         return false;
      } else {
        $("#modalMantenedorIngresoRol").modal("hide");

        Swal.fire({
          title: "Atención",
          text: mensaje,
          icon: "warning",
        });

        return;
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      $("#modalMantenedorIngresoRol").modal("hide");

      Swal.fire({
        title: "Atención",
        text: "El Rol no se registró",
        icon: "warning",
      });
    });
  $("#ingreso_rol")[0].reset();
  $("#modalMantenedorIngresoRol").modal("hide");
  //cargarRolList();
}

function cargarRolEditar(idRol, nombre, descripcion, activo) {
  // console.log("PARAMETROS ENTRADA: ", {idInfoComentario, comentario});
  console.log("ESTOY EN cargarRolEditar");
  $("#nombreRolEditar").val(nombre);
  $("#descripcionRolEditar").val(descripcion);
  $("#rolActivoEditar")[0].checked = activo == 1;
  $("#ID_Rol_Editar").val(idRol);
  
  editarRoles(idRol);
}

function resetModal(){
$('#reporteRolEditar').val(null).trigger('change');
$('#accionesRolEditar').val(null).trigger('change');
$('#arriendoRolEditar').val(null).trigger('change');
$('#clienteRolEditar').val(null).trigger('change');
$('#propiedadRolEditar').val(null).trigger('change');
$('#administracionRolEditar').val(null).trigger('change');
$('#facturacionRolEditar').val(null).trigger('change');
$('#archivoRolEditar').val(null).trigger('change');

}

function resetModalUsuario(){
$('#usuarioEditar').val(null).trigger('change');
$("#usuario_formulario_editar_pass")[0].reset();
}

function editarRoles(idRol) {

//$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
$(document).ajaxStart($.blockUI({
    baseZ: 900000,
})).ajaxStop($.unblockUI);
console.log("900001");
  $.ajax({
    url: "components/mantenedor/models/mant_roles_list_select.php?idRol="+idRol,
    dataType: "text",
	type: "POST",
	data:{idRol:idRol},
    //data: {tipo_servicio: tipo_servicio, id_proveedor: id_proveedor , id_tipo_servicio: id_tipo_servicio},
    contentType: false,
    processData: false,
  })
    .done(function (res) {

    var retorno = res.split(",xxx,");
    var resultado = retorno[1];
    var propiedad = retorno[2];
    var acciones = retorno[3];
    var administracion = retorno[4];
    var arriendo = retorno[5];
    var facturacion = retorno[6];
    var cliente = retorno[7];
    var reporte = retorno[8];
    var archivo = retorno[9];
	//console.log(reporte);

    if (resultado == "OK") {
	document.getElementById("administracionRolEditar").innerHTML = administracion;
	document.getElementById("propiedadRolEditar").innerHTML = propiedad;
	document.getElementById("clienteRolEditar").innerHTML = cliente;
	document.getElementById("arriendoRolEditar").innerHTML = arriendo;
	document.getElementById("accionesRolEditar").innerHTML = acciones;
	document.getElementById("facturacionRolEditar").innerHTML = facturacion;
	document.getElementById("reporteRolEditar").innerHTML = reporte;
  document.getElementById("archivoRolEditar").innerHTML = archivo;

    }
	

	  
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "ah surgido un problema - Por favor contactar a soporte",
        icon: "warning",
      });
    });

  // $("#cheque_formulario_editar")[0].reset();
  //$("#modalEditarServicio").modal("hide");
  //cargarServicios();
}


function descripcionRol(rol,descripcion){
	//console.log(descripcion);
		Swal.fire({
        title: "Información sobre el rol "+rol,
       // text: rol+" : "+descripcion,
		html: '<div style="text-align: left;">' + descripcion + '</div>' ,
        icon: "info",
      });
}

function cargarUsuarioEditar(id, nombre, paterno, materno,correo,pass,rut, activo,idRol) {
  // console.log("PARAMETROS ENTRADA: ", {idInfoComentario, comentario});

  $("#usuarioNombreEditar").val(nombre);
  $("#usuarioApellidoPatEditar").val(paterno);
  $("#usuarioApellidoMatEditar").val(materno);
  $("#usuarioCorreoEditar").val(correo);
  $("#usuarioCorreoEditarActual").val(correo);
//  $("#usuarioContraseñaEditar").val(pass);
  $("#usuarioRutEditar").val(rut);
 // $("#tipoRol").val(idRol);
  $("#ID_usuario_Editar").val(id);
  RolUsuarioEditar(idRol,id);
}

function cargarUsuarioEditarPass(id) {
  // console.log("PARAMETROS ENTRADA: ", {idInfoComentario, comentario});

  $("#ID_usuario_Editar_Pass").val(id);
}


function RolUsuarioEditar(idRol,id) {



$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
  $.ajax({
    url: "components/mantenedor/models/buscar_usuario_rol_editar.php?idRol="+idRol+"&id="+id,
    dataType: "text",
	type: "POST",
	data:{idRol:idRol},
    //data: {tipo_servicio: tipo_servicio, id_proveedor: id_proveedor , id_tipo_servicio: id_tipo_servicio},
    contentType: false,
    processData: false,
  })
    .done(function (res) {
    var retorno = res.split(",xxx,");
    var resultado = retorno[1];
    var mensaje = retorno[2];
    var sucursal = retorno[3];
	console.log(mensaje);

    if (resultado == "OK") {
	//$("#TipoProveedorSeguro").attr("disabled", false);	
	document.getElementById("usuarioRolEditar").innerHTML = mensaje;
	document.getElementById("usuarioEditar").innerHTML = sucursal;
    }

	  
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "ah surgido un problema - Por favor contactar a soporte",
        icon: "warning",
      });
    });

  // $("#cheque_formulario_editar")[0].reset();
  //$("#modalEditarServicio").modal("hide");
  //cargarServicios();
}

function editarEstadoRol(idRol, activo) {
  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/editar_estado_rol.php",
    type: "post",
    dataType: "text",
    data: { idRol, activo },
  })
    .done(function (res) {
      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
        Swal.fire({
          title: "Rol actualizada",
          text: "El Rol se actualizó correctamente",
          icon: "success",
        });
        var id_comentario = res;
        var jsonInformacioantigua = capturarInformacionAntigua();

        cargarRolList();
        // registroHistorial(
        //   "Crear",
        //   "",
        //   jsonInformacionNueva,
        //   "Beneficiario",
        //   id_ficha,
        //   id_comentario
        // );
        return false;
      } else {
        Swal.fire({
          title: "Atención",
          text: "El Rol no se actualizó",
          icon: "warning",
        });

        return;
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "El Rol no se actualizó",
        icon: "warning",
      });
    });
  //cargarRolList();
}

function editarRol() {
  var formData = new FormData(document.getElementById("rol_formulario_editar"));

  formData.set("rolActivoEditar", $("#rolActivoEditar")[0].checked);

  var jsonInformacionNueva = obtenerValoresFormulario("rol_formulario_editar");
  // Decodificar el texto JSON a un array asociativo
  var objeto_json = JSON.parse(jsonInformacionNueva);

  console.log("objeto_json editar : ", objeto_json);

  var id_comentario = objeto_json.ID_Rol_Editar;
  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;

  //$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
  $(document).ajaxStart($.blockUI({
    baseZ: 900000,
})).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/editar_rol.php",
    type: "post",
    dataType: "text",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  })
    .done(function (res) {
		
	
      Swal.fire({
        title: "Rol actualizado",
        text: "El rol se actualizó correctamente",
        icon: "success",
      });
      var jsonInformacioantigua = capturarInformacionAntigua();
      cargarRolList();
      // registroHistorial(
      //   "Modificar",
      //   jsonInformacioantigua,
      //   jsonInformacionNueva,
      //   "Comentario",
      //   id_ficha,
      //   id_comentario
      // );
	  return false;
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "El rol no se actualizó",
        icon: "warning",
      });
    });

  $("#rol_formulario_editar")[0].reset();
  $("#modalMantenedorEditarRol").modal("hide");
  //cargarRolList();
}

function togglePasswordVisibility(id,clase) {
    var passwordField = document.getElementById(id);	
    var toggleBtn = document.querySelector(clase);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleBtn.classList.remove("fa-eye-slash");
        toggleBtn.classList.add("fa-eye");
    } else {
        passwordField.type = "password";
        toggleBtn.classList.remove("fa-eye");
        toggleBtn.classList.add("fa-eye-slash");
    }
}

function editarUsuarioPass() {
  var formData = new FormData(document.getElementById("usuario_formulario_editar_pass"));


  var jsonInformacionNueva = obtenerValoresFormulario("usuario_formulario_editar_pass");
  // Decodificar el texto JSON a un array asociativo
  var objeto_json = JSON.parse(jsonInformacionNueva);

  console.log("objeto_json editar : ", objeto_json);

  var id_comentario = objeto_json.ID_usuario_Editar;
  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;
  
  
  
    var pass = document.getElementById("usuarioContraseñaEditar")?.value ?? '';
	
	 var pass_2 = document.getElementById("usuarioContraseñaEditarRepetida")?.value ?? '';
	console.log("pass: ",pass);



  /*VALIDA Contraseña */
  if (pass == null || pass == "") {
    $("#usuarioContraseñaEditar")[0].setCustomValidity("Debe ingresar  contraseña");
    $("#usuarioContraseñaEditar")[0].reportValidity();

    return;
  }
  
    /*VALIDA Contraseña 2 */
  if (pass_2 == null || pass_2 == "") {
    $("#usuarioContraseñaEditarRepetida")[0].setCustomValidity("Debe ingresar  contraseña");
    $("#usuarioContraseñaEditarRepetida")[0].reportValidity();

    return;
  }
  
  if (pass == pass_2){
	   $("#alertAviso").hide();
  }else{
	 $("#alertAviso").show();
	return;
  }


if (pass.length < 8) {
        Swal.fire({
        title: "Error al actualizar contraseña",
        text: "La contraseña debe tener un largo minimo de 8",
        icon: "warning",
      });
        event.preventDefault(); // Evita que se envíe el formulario
        return;
}
if (!/\d/.test(pass)) {
        Swal.fire({
        title: "Error al actualizar contraseña",
        text: "La contraseña debe tener al menos un número",
        icon: "warning",
      });
        event.preventDefault(); // Evita que se envíe el formulario
        return;
}
if (!/[A-Z]/.test(pass)) {
        Swal.fire({
        title: "Error al actualizar contraseña",
        text: "La contraseña debe tener al menos una letra mayúscula",
        icon: "warning",
      });
        event.preventDefault(); // Evita que se envíe el formulario
        return;
}

if (!/[a-z]/.test(pass)) {
        Swal.fire({
        title: "Error al actualizar contraseña",
        text: "La contraseña debe tener al menos una letra minuscula",
        icon: "warning",
      });
        event.preventDefault(); // Evita que se envíe el formulario
        return;
}
  

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/editar_usuario.php",
    type: "post",
    dataType: "text",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  })
    .done(function (res) {
			    $("#usuario_formulario_editar_pass")[0].reset();
		$("#modalMantenedorEditarPass").modal("hide");
		      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      //console.log("res", res);

      if (resultado == "OK") {
      Swal.fire({
        title: "Usuario actualizado",
        text: "El usuario se actualizó correctamente",
        icon: "success",
      });
    
loadUsuarios_List();
	  }else{
		Swal.fire({
        title: "Error al actualizar usuario",
        text: "El usuario no se actualizó correctamente",
        icon: "warning",
      });
	  }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "El usuario no se actualizó",
        icon: "warning",
      });
    });
  


}



function editarUsuario() {
  var formData = new FormData(document.getElementById("usuario_formulario_editar"));

  formData.set("UsuarioActivoEditar", $("#UsuarioActivoEditar")[0].checked);

  var jsonInformacionNueva = obtenerValoresFormulario("usuario_formulario_editar");
  // Decodificar el texto JSON a un array asociativo
  var objeto_json = JSON.parse(jsonInformacionNueva);

  console.log("objeto_json editar : ", objeto_json);

  var id_comentario = objeto_json.ID_usuario_Editar;
  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;
  
  
  
    var nombreUsuario = document.getElementById("usuarioNombreEditar")?.value ?? '';
  
  var usuarioApellidoPat = document.getElementById("usuarioApellidoPatEditar")?.value ?? '';

  var usuarioApellidoMat = document.getElementById("usuarioApellidoMatEditar")?.value ?? '';
  
  var usuarioCorreo = document.getElementById("usuarioCorreoEditar")?.value ?? '';
  
  var usuarioRut = document.getElementById("usuarioRutEditar")?.value ?? '';
  
  var tipoRol = document.getElementById("usuarioRolEditar")?.value ?? '';

  /*VALIDA QUE NOMBRE BENEFICIARIO TENGA FORMATO CORRECTO */
  if (nombreUsuario == null || nombreUsuario == "") {
    $("#usuarioNombreEditar")[0].setCustomValidity("Debe ingresar nombre del usuario");
    $("#usuarioNombreEditar")[0].reportValidity();

    return;
  }

  if (usuarioApellidoPat == null || usuarioApellidoPat == "") {
    $("#usuarioApellidoPatEditar")[0].setCustomValidity("Debe ingresar apellido paterno");
    $("#usuarioApellidoPatEditar")[0].reportValidity();

    return;
  }
  
    if (usuarioApellidoMat == null || usuarioApellidoMat == "") {
    $("#usuarioApellidoMatEditar")[0].setCustomValidity("Debe ingresar apellido materno");
    $("#usuarioApellidoMatEditar")[0].reportValidity();

    return;
  }
  
    if (!validarEmail(usuarioCorreo)) {
    $("#usuarioCorreoEditar")[0].setCustomValidity("Email inválido");
    $("#usuarioCorreoEditar")[0].reportValidity();
    return;
  }

  /*VALIDA QUE RUT TENGA FORMATO CORRECTO */
  console.log("usuarioRut: ", usuarioRut);
  if (!validarRutChile(usuarioRut)) {
    $("#usuarioRutEditar")[0].setCustomValidity("Rut inválido");
    $("#usuarioRutEditar")[0].reportValidity();

    return;
  }

      if (tipoRol == null || tipoRol == "") {
    $("#usuarioRolEditar")[0].setCustomValidity("Debe ingresar un rol");
    $("#usuarioRolEditar")[0].reportValidity();

    return;
  }

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/editar_usuario.php",
    type: "post",
    dataType: "text",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  })
    .done(function (res) {
			    $("#usuario_formulario_editar")[0].reset();
		$("#modalMantenedorEditarUsuario").modal("hide");
		      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      //console.log("res", res);

      if (resultado == "OK") {
      Swal.fire({
        title: "Usuario actualizado",
        text: "El usuario se actualizó correctamente",
        icon: "success",
      });
    
loadUsuarios_List();
	  }else{
		Swal.fire({
        title: "Error al actualizar usuario",
        text: "El usuario no se actualizó correctamente",
        icon: "warning",
      });
	  }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "El usuario no se actualizó",
        icon: "warning",
      });
    });
  


}


function insertUsuario() {
  var formData = new FormData(document.getElementById("ingreso_usuario"));

  //formData.set("UsuarioActivoEditar", $("#UsuarioActivoEditar")[0].checked);

  var jsonInformacionNueva = obtenerValoresFormulario("ingreso_usuario");
  // Decodificar el texto JSON a un array asociativo
  var objeto_json = JSON.parse(jsonInformacionNueva);

  console.log("objeto_json editar : ", objeto_json);

  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;
  
  var nombreUsuario = document.getElementById("UsuarioNombre")?.value ?? '';
  
  var usuarioApellidoPat = document.getElementById("usuarioApellidoPat")?.value ?? '';

  var usuarioApellidoMat = document.getElementById("UsuarioApellidoMat")?.value ?? '';
  
  var usuarioCorreo = document.getElementById("usuarioCorreo")?.value ?? '';
  
  var usuarioRut = document.getElementById("usuarioRut")?.value ?? '';
	
  var usuarioContraseña = document.getElementById("usuarioContraseña")?.value ?? '';
  
  var tipoRol = document.getElementById("tipoRol")?.value ?? '';
  
  var sucursal = document.getElementById("sucursal")?.value ?? '';

  /*VALIDA QUE NOMBRE BENEFICIARIO TENGA FORMATO CORRECTO */
  if (nombreUsuario == null || nombreUsuario == "") {
    $("#UsuarioNombre")[0].setCustomValidity("Debe ingresar nombre del usuario");
    $("#UsuarioNombre")[0].reportValidity();

    return;
  }

  if (usuarioApellidoPat == null || usuarioApellidoPat == "") {
    $("#usuarioApellidoPat")[0].setCustomValidity("Debe ingresar apellido paterno");
    $("#usuarioApellidoPat")[0].reportValidity();

    return;
  }
  
    if (usuarioApellidoMat == null || usuarioApellidoMat == "") {
    $("#UsuarioApellidoMat")[0].setCustomValidity("Debe ingresar apellido materno");
    $("#UsuarioApellidoMat")[0].reportValidity();

    return;
  }
  
      if (usuarioContraseña == null || usuarioContraseña == "") {
    $("#usuarioContraseña")[0].setCustomValidity("Debe ingresar contraseña");
    $("#usuarioContraseña")[0].reportValidity();

    return;
  }
  
    if (!validarEmail(usuarioCorreo)) {
    $("#usuarioCorreo")[0].setCustomValidity("Email inválido");
    $("#usuarioCorreo")[0].reportValidity();
    return;
  }

  /*VALIDA QUE RUT TENGA FORMATO CORRECTO */
  console.log("usuarioRut: ", usuarioRut);
  if (!validarRutChile(usuarioRut)) {
    $("#usuarioRut")[0].setCustomValidity("Rut inválido");
    $("#usuarioRut")[0].reportValidity();

    return;
  }

  if (tipoRol == null || tipoRol == "") {
    $("#tipoRol")[0].setCustomValidity("Debe ingresar un rol");
    $("#tipoRol")[0].reportValidity();

    return;
  }
  
   if (sucursal == null || sucursal == "") {
    $("#sucursal")[0].setCustomValidity("Debe ingresar una sucursal");
    $("#sucursal")[0].reportValidity();

    return;
  }
  
  
  
  
  

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/insert_usuario.php",
    type: "post",
    dataType: "text",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  }).done(function (res) {
	      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
		  	    $("#ingreso_usuario")[0].reset();
		$("#modalMantenedorIngresoUsuario").modal("hide");
      Swal.fire({
        title: "Usuario insertado correctamente",
        icon: "success",
      });
		loadUsuarios_List();
	  return false;
  }else{
	        Swal.fire({
        title: "Atención",
		text: mensaje,
        icon: "warning",
      });
  }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "Error al insertar usuario",
        icon: "warning",
      });
    });


  //cargarRolList();
}

function eliminarRol(idRol) {
  console.log("ID ROL: ", idRol);

  Swal.fire({
    title: "¿Estás seguro?",
    text: "Una vez eliminado, no podrás recuperar este rol",
    icon: "warning",
    showDenyButton: true,
    confirmButtonText: "Eliminar",
    denyButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      // Si el usuario hace clic en "Eliminar"
      $.ajax({
        url: "components/mantenedor/models/delete_rol.php",
        type: "POST",
        dataType: "text",
        data: { idRol: idRol },
        success: function (response) {
          Swal.fire({
            title: "Rol eliminado",
            text: "el  se eliminó correctamente",
            icon: "success",
          });
          cargarRolList();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error en la solicitud:", textStatus, errorThrown);
          // Aquí puedes mostrar un mensaje de error al usuario o realizar otras acciones
        },
      });
    } else if (result.isDenied) {
      // Si el usuario hace clic en "Cancelar"
      // Aquí puedes cerrar el modal de SweetAlert si lo deseas
      Swal.close();
    }
  });
}

function eliminarUsuario(id) {

  Swal.fire({
    title: "¿Estás seguro?",
    text: "Una vez eliminado, no podrás recuperar este usuario",
    icon: "warning",
    showDenyButton: true,
    confirmButtonText: "Eliminar",
    denyButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      // Si el usuario hace clic en "Eliminar"
      $.ajax({
        url: "components/mantenedor/models/delete_usuario.php",
        type: "POST",
        dataType: "text",
        data: { id: id },
        success: function (response) {
          Swal.fire({
            title: "Usuario eliminado",
            text: "el usuario se eliminó correctamente",
            icon: "success",
          });
          loadUsuarios_List();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error en la solicitud:", textStatus, errorThrown);
          // Aquí puedes mostrar un mensaje de error al usuario o realizar otras acciones
        },
      });
    } else if (result.isDenied) {
      // Si el usuario hace clic en "Cancelar"
      // Aquí puedes cerrar el modal de SweetAlert si lo deseas
      Swal.close();
    }
  });
}


 /*26-06-2024*/
function cargarSucursalList() {
  $("#mant-sucursal-table").DataTable({
    dom: 'B<"clear">lfrtip',
    destroy: true,
    targets: "no-sort",
    bSort: false,
    order: [[0, "desc"]],
    pagingType: "full_numbers",
    pageLength: 10,
    lengthMenu: [
      [10, 25, 50, 100, 5000],
      [10, 25, 50, 100, "Todos"],
    ],
    columnDefs: [

      {
        render: (data, type, row) => {
          const isChecked = data == 1 ? "checked" : "";
          const checkboxId = `servicioActivoEditar_${row[0]}`; // Assuming row[0] is unique for each row

          return `<div class="d-flex">
                        <label class="switch">
                            <input type="checkbox" id='${checkboxId}' name='${row[0]}' ${isChecked} onclick="handleSucursalCMClick(event,this)">
                            <span class="slider round"></span>
                        </label>
                    </div>`;
        },
        targets: 2,
      },
	        {
        render: (data, type, row) => {
          const isChecked = data == 1 ? "checked" : "";
          const checkboxId = `servicioActivoEditar_${row[0]}`; // Assuming row[0] is unique for each row

          return `<div class="d-flex">
                        <label class="switch">
                            <input type="checkbox" id='${checkboxId}' name='${row[0]}' ${isChecked} onclick="handleSucursalActivoClick(event,this)">
                            <span class="slider round"></span>
                        </label>
                    </div>`;
        },
        targets: 3,
      }
    ],
    ajax: {
      url: "components/mantenedor/models/mant_sucursal_list_procesa.php",
      type: "POST",
    },
    language: {
      lengthMenu: "Mostrar _MENU_ registros por página",
      zeroRecords: "No se encontraron registros",
      info: "Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)",
      infoEmpty: "No existen registros para mostrar",
      infoFiltered: "(filtrado desde _MAX_ total de registros)",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar",
      paginate: {
        first: "Primero",
        last: "Último",
        next: "Siguiente",
        previous: "Anterior",
      },
      buttons: {
        copy: "Copiar",
      },
    },
    createdRow: function (row, data, dataIndex) {
        $(row).addClass("table-secondary");
      }
  });

  $("#mant-sucursal-table").on("init.dt", function () {
    console.log("DataTables se ha inicializado correctamente en #mant-sucursal-table");
  });

  $(".dataTables_filter").css("display", "none");
}

function cargarSucursalEditar(idSucursal, nombre, casa_matriz, activo) {
  // console.log("PARAMETROS ENTRADA: ", {idInfoComentario, comentario});
  console.log("ESTOY EN cargarSucursalEditar");
  $("#sucursalNombreEditar").val(nombre);
  $("#sucursalMatrizEditar")[0].checked = casa_matriz == 1;
  $("#sucursalActivoEditar")[0].checked = activo == 1;
  $("#ID_sucursal_Editar").val(idSucursal);
}

function insertSucursal() {
  var formData = new FormData(document.getElementById("ingreso_sucursal"));

  //formData.set("UsuarioActivoEditar", $("#UsuarioActivoEditar")[0].checked);

  var jsonInformacionNueva = obtenerValoresFormulario("ingreso_sucursal");
  // Decodificar el texto JSON a un array asociativo
  var objeto_json = JSON.parse(jsonInformacionNueva);

  console.log("objeto_json editar : ", objeto_json);

  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;
  
  var nombreSucursal = document.getElementById("sucursalNombre")?.value ?? '';

  /*VALIDA QUE NOMBRE BENEFICIARIO TENGA FORMATO CORRECTO */
  if (nombreSucursal == null || nombreSucursal == "") {
    $("#sucursalNombre")[0].setCustomValidity("Debe ingresar nombre de la sucursal");
    $("#sucursalNombre")[0].reportValidity();

    return;
  }
  
  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/insert_sucursal.php",
    type: "post",
    dataType: "text",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  }).done(function (res) {
	      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
		$("#ingreso_sucursal")[0].reset();
		$("#modalMantenedorIngresoSucursal").modal("hide");
      Swal.fire({
        title: "Sucursal insertada correctamente",
        icon: "success",
      });
		cargarSucursalList();
	  return false;
  }else{
	        Swal.fire({
        title: "Atención",
		text: mensaje,
        icon: "warning",
      });
  }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "Error al insertar sucursal",
        icon: "warning",
      });
    });


  //cargarRolList();
}

function editarSucursal() {
  var formData = new FormData(document.getElementById("sucursal_formulario_editar"));

  //formData.set("UsuarioActivoEditar", $("#UsuarioActivoEditar")[0].checked);

  var jsonInformacionNueva = obtenerValoresFormulario("sucursal_formulario_editar");
  // Decodificar el texto JSON a un array asociativo
  var objeto_json = JSON.parse(jsonInformacionNueva);

  console.log("objeto_json editar : ", objeto_json);

  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;
  
  var nombreSucursal = document.getElementById("sucursalNombreEditar")?.value ?? '';

  /*VALIDA QUE NOMBRE BENEFICIARIO TENGA FORMATO CORRECTO */
  if (nombreSucursal == null || nombreSucursal == "") {
    $("#sucursalNombreEditar")[0].setCustomValidity("Debe ingresar nombre de la sucursal");
    $("#sucursalNombreEditar")[0].reportValidity();

    return;
  }
  
  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/editar_sucursal.php",
    type: "post",
    dataType: "text",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  }).done(function (res) {
	      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
		$("#sucursal_formulario_editar")[0].reset();
		$("#modalMantenedorEditarSucursalEditar").modal("hide");
      Swal.fire({
        title: "Sucursal editada correctamente",
        icon: "success",
      });
		cargarSucursalList();
	  return false;
  }else{
	        Swal.fire({
        title: "Atención",
		text: mensaje,
        icon: "warning",
      });
  }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "Error al editar sucursal",
        icon: "warning",
      });
    });


  //cargarRolList();
}


function handleSucursalCMClick(event, checkbox) {
  // Prevent the checkbox from changing its state immediately
  event.preventDefault();

  console.log("CHECKBOX: ", checkbox.name);
  const idCtaContable = checkbox.name;
  const isChecked = checkbox.checked;
  console.log("CHECKED: ", isChecked);
	  const message = !isChecked
    ? "Deseas Desactivar la sucursal como casa matriz"
    : "Deseas Activar la sucursal como casa matriz";



  const confirm = !isChecked ? "Desactivar" : "Activar";

  Swal.fire({
    text: message,
    icon: "warning",
    showDenyButton: true,
    confirmButtonText: confirm,
    denyButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      editarSucursalCasaM(idCtaContable, isChecked);
    }
  });
}

function handleSucursalActivoClick(event, checkbox) {
  // Prevent the checkbox from changing its state immediately
  event.preventDefault();

  console.log("CHECKBOX: ", checkbox.name);
  const idCtaContable = checkbox.name;
  const isChecked = checkbox.checked;
  console.log("CHECKED: ", isChecked);
	  const message = !isChecked
    ? "Deseas Desactivar la sucursal"
    : "Deseas Activar la sucursal";  


  const confirm = !isChecked ? "Desactivar" : "Activar";

  Swal.fire({
    text: message,
    icon: "warning",
    showDenyButton: true,
    confirmButtonText: confirm,
    denyButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      editarSucursalActivo(idCtaContable, isChecked);
    }
  });
}

function editarSucursalActivo(id,activo) {
  var formData = new FormData(document.getElementById("sucursal_formulario_editar"));
  console.log("activo: ",activo);
  console.log("id: ",id);
  //formData.set("UsuarioActivoEditar", $("#UsuarioActivoEditar")[0].checked);

  var jsonInformacionNueva = obtenerValoresFormulario("sucursal_formulario_editar");
  // Decodificar el texto JSON a un array asociativo
  var objeto_json = JSON.parse(jsonInformacionNueva);

  console.log("objeto_json editar : ", objeto_json);

  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;
  
  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/editar_sucursal_activo.php?activo="+activo+"&id="+id,
    type: "post",
    dataType: "text",
    data: { id, activo },
    cache: false,
    contentType: false,
    processData: false,
  }).done(function (res) {
	      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
      Swal.fire({
        title: "Sucursal editada correctamente",
        icon: "success",
      });
		cargarSucursalList();
	  return false;
  }else{
	        Swal.fire({
        title: "Atención",
		text: mensaje,
        icon: "warning",
      });
  }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "Error al editar sucursal",
        icon: "warning",
      });
    });


  //cargarRolList();
}


function editarSucursalCasaM(id,activo) {
  var formData = new FormData(document.getElementById("sucursal_formulario_editar"));

  //formData.set("UsuarioActivoEditar", $("#UsuarioActivoEditar")[0].checked);

  var jsonInformacionNueva = obtenerValoresFormulario("sucursal_formulario_editar");
  // Decodificar el texto JSON a un array asociativo
  var objeto_json = JSON.parse(jsonInformacionNueva);

  console.log("objeto_json editar : ", objeto_json);

  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;
  
  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/editar_sucursal_casa_matriz.php?activo="+activo+"&id="+id,
    type: "post",
    dataType: "text",
    data: { id, activo },
    cache: false,
    contentType: false,
    processData: false,
  }).done(function (res) {
	      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
      Swal.fire({
        title: "Sucursal editada correctamente",
        icon: "success",
      });
		cargarSucursalList();
	  return false;
  }else{
	        Swal.fire({
        title: "Atención",
		text: mensaje,
        icon: "warning",
      });
  }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "Error al editar sucursal",
        icon: "warning",
      });
    });


  //cargarRolList();
}


function eliminarSucursal(id) {

  Swal.fire({
    title: "¿Estás seguro?",
    text: "Una vez eliminado, no podrás recuperar esta sucursal",
    icon: "warning",
    showDenyButton: true,
    confirmButtonText: "Eliminar",
    denyButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      // Si el usuario hace clic en "Eliminar"
      $.ajax({
        url: "components/mantenedor/models/delete_sucursal.php?id="+id,
        type: "POST",
        dataType: "text",
        data: { id: id },
        success: function (response) {
          Swal.fire({
            title: "Sucursal eliminada",
            text: "La sucursal se eliminó correctamente",
            icon: "success",
          });
          cargarSucursalList();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error en la solicitud:", textStatus, errorThrown);
          // Aquí puedes mostrar un mensaje de error al usuario o realizar otras acciones
        },
      });
    } else if (result.isDenied) {
      // Si el usuario hace clic en "Cancelar"
      // Aquí puedes cerrar el modal de SweetAlert si lo deseas
      Swal.close();
    }
  });
}

function eliminarSbs(id) {

  Swal.fire({
    title: "¿Estás seguro?",
    text: "Una vez eliminado, no podrás recuperar esta subsidiaria",
    icon: "warning",
    showDenyButton: true,
    confirmButtonText: "Eliminar",
    denyButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      // Si el usuario hace clic en "Eliminar"
      $.ajax({
        url: "components/mantenedor/models/delete_subsidiaria.php?id="+id,
        type: "POST",
        dataType: "text",
        data: { id: id },
        success: function (response) {
          Swal.fire({
            title: "Subsidiaria eliminada",
            text: "La subsidiaria se eliminó correctamente",
            icon: "success",
          });
          cargarSbsList();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error en la solicitud:", textStatus, errorThrown);
          // Aquí puedes mostrar un mensaje de error al usuario o realizar otras acciones
        },
      });
    } else if (result.isDenied) {
      // Si el usuario hace clic en "Cancelar"
      // Aquí puedes cerrar el modal de SweetAlert si lo deseas
      Swal.close();
    }
  });
}


function editarSbsActivo(id,activo) {
  var formData = new FormData(document.getElementById("sbs_formulario_editar"));

  //formData.set("UsuarioActivoEditar", $("#UsuarioActivoEditar")[0].checked);

  var jsonInformacionNueva = obtenerValoresFormulario("sbs_formulario_editar");
  // Decodificar el texto JSON a un array asociativo
  var objeto_json = JSON.parse(jsonInformacionNueva);

  console.log("objeto_json editar : ", objeto_json);

  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;
  
  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/editar_subsidiaria_activo.php?activo="+activo+"&id="+id,
    type: "post",
    dataType: "text",
    data: { id, activo },
    cache: false,
    contentType: false,
    processData: false,
  }).done(function (res) {
	      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
      Swal.fire({
        title: "Subsidiaria editada correctamente",
        icon: "success",
      });
		cargarSbsList();
	  return false;
  }else{
	        Swal.fire({
        title: "Atención",
		text: mensaje,
        icon: "warning",
      });
  }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "Error al editar sucursal",
        icon: "warning",
      });
    });


  //cargarRolList();
}

function cargarSbsList() {
  $("#mant-sbs-table").DataTable({
    dom: 'B<"clear">lfrtip',
    destroy: true,
    targets: "no-sort",
    bSort: false,
    order: [[0, "desc"]],
    pagingType: "full_numbers",
    pageLength: 10,
    lengthMenu: [
      [10, 25, 50, 100, 5000],
      [10, 25, 50, 100, "Todos"],
    ],
    columnDefs: [

      {
        render: (data, type, row) => {
          const isChecked = data == 1 ? "checked" : "";
          const checkboxId = `servicioActivoEditar_${row[0]}`; // Assuming row[0] is unique for each row

          return `<div class="d-flex">
                        <label class="switch">
                            <input type="checkbox" id='${checkboxId}' name='${row[0]}' ${isChecked} onclick="handleSbsClick(event,this)">
                            <span class="slider round"></span>
                        </label>
                    </div>`;
        },
        targets: 3,
      }
    ],
    ajax: {
      url: "components/mantenedor/models/mant_subsidiaria_list_procesa.php",
      type: "POST",
    },
    language: {
      lengthMenu: "Mostrar _MENU_ registros por página",
      zeroRecords: "No se encontraron registros",
      info: "Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)",
      infoEmpty: "No existen registros para mostrar",
      infoFiltered: "(filtrado desde _MAX_ total de registros)",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar",
      paginate: {
        first: "Primero",
        last: "Último",
        next: "Siguiente",
        previous: "Anterior",
      },
      buttons: {
        copy: "Copiar",
      },
    },
    createdRow: function (row, data, dataIndex) {
        $(row).addClass("table-secondary");
      }
  });

  $("#mant-sbs-table").on("init.dt", function () {
    console.log("DataTables se ha inicializado correctamente en #mant-sucursal-table");
  });

  $(".dataTables_filter").css("display", "none");
}


function insertSbs() {
  var formData = new FormData(document.getElementById("ingreso_sbs"));

  //formData.set("UsuarioActivoEditar", $("#UsuarioActivoEditar")[0].checked);

  var jsonInformacionNueva = obtenerValoresFormulario("ingreso_sbs");
  // Decodificar el texto JSON a un array asociativo
  var objeto_json = JSON.parse(jsonInformacionNueva);

  console.log("objeto_json editar : ", objeto_json);

  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;
  
  var nombreSbs = document.getElementById("sbsNombre")?.value ?? '';
  
  var sbsRut = document.getElementById("sbsRut")?.value ?? '';

  /*VALIDA QUE NOMBRE BENEFICIARIO TENGA FORMATO CORRECTO */
  if (nombreSbs == null || nombreSbs == "") {
    $("#sbsNombre")[0].setCustomValidity("Debe ingresar nombre de la subsidiaria");
    $("#sbsNombre")[0].reportValidity();

    return;
  }
  
    /*VALIDA QUE NOMBRE BENEFICIARIO TENGA FORMATO CORRECTO */
  if (sbsRut == null || sbsRut == "") {
    $("#sbsRut")[0].setCustomValidity("Debe ingresar un rut");
    $("#sbsRut")[0].reportValidity();

    return;
  }
  
    if (!validarRutChile(sbsRut)) {
    $("#sbsRut")[0].setCustomValidity("Rut inválido");
    $("#sbsRut")[0].reportValidity();

    return;
  }
  
  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/insert_subsidiaria.php",
    type: "post",
    dataType: "text",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  }).done(function (res) {
	      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
		$("#ingreso_sbs")[0].reset();
		$("#modalMantenedorIngresoSbs").modal("hide");
      Swal.fire({
        title: "Subsidiaria insertada correctamente",
        icon: "success",
      });
		cargarSbsList();
	  return false;
  }else{
	        Swal.fire({
        title: "Atención",
		text: mensaje,
        icon: "warning",
      });
  }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "Error al insertar sucursal",
        icon: "warning",
      });
    });


  //cargarRolList();
}

function handleSbsClick(event, checkbox) {
  // Prevent the checkbox from changing its state immediately
  event.preventDefault();

  console.log("CHECKBOX: ", checkbox.name);
  const id = checkbox.name;
  const isChecked = checkbox.checked;
  console.log("CHECKED: ", isChecked);
  const message = !isChecked
    ? "Deseas desactivar subssidiaria"
    : "Deseas activar subssidiaria";

  const confirm = !isChecked ? "Desactivar" : "Activar";

  Swal.fire({
    text: message,
    icon: "warning",
    showDenyButton: true,
    confirmButtonText: confirm,
    denyButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      editarSbsActivo(id, isChecked);
    }
  });
}

function cargarSbsEditar(id, nombre, rut, activo) {

  $("#sbsNombreEditar").val(nombre);
  $("#sbsRutEditar").val(rut);
  $("#sbsActivoEditar")[0].checked = activo == 1;
  $("#ID_sbs_Editar").val(id);
}


function editarSbs() {
  var formData = new FormData(document.getElementById("sbs_formulario_editar"));

  //formData.set("UsuarioActivoEditar", $("#UsuarioActivoEditar")[0].checked);

  var jsonInformacionNueva = obtenerValoresFormulario("sbs_formulario_editar");
  // Decodificar el texto JSON a un array asociativo
  var objeto_json = JSON.parse(jsonInformacionNueva);

  console.log("objeto_json editar : ", objeto_json);

  var url = window.location.href;
  //console.log(url);
  var id_ficha = $("#id_ficha").val();
  var parametros = new URL(url).searchParams;
  
  var sbsNombre = document.getElementById("sbsNombreEditar")?.value ?? '';
  
   var sbsRut = document.getElementById("sbsRutEditar")?.value ?? '';

  /*VALIDA QUE NOMBRE BENEFICIARIO TENGA FORMATO CORRECTO */
  if (sbsNombre == null || sbsNombre == "") {
    $("#sbsNombreEditar")[0].setCustomValidity("Debe ingresar nombre de la subsidiaria");
    $("#sbsNombreEditar")[0].reportValidity();

    return;
  }
  
    /*VALIDA QUE NOMBRE BENEFICIARIO TENGA FORMATO CORRECTO */
  if (sbsRut == null || sbsRut == "") {
    $("#sbsRutEditar")[0].setCustomValidity("Debe ingresar rut de la subsidiaria");
    $("#sbsRutEditar")[0].reportValidity();

    return;
  }
  
      if (!validarRutChile(sbsRut)) {
    $("#sbsRutEditar")[0].setCustomValidity("Rut inválido");
    $("#sbsRutEditar")[0].reportValidity();

    return;
  }
  
  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    url: "components/mantenedor/models/editar_subsidiaria.php",
    type: "post",
    dataType: "text",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  }).done(function (res) {
	      var retorno = res.split(",xxx,");
      var resultado = retorno[1];
      var mensaje = retorno[2];
      var token = retorno[3];
      console.log("res", res);

      if (resultado == "OK") {
		$("#sbs_formulario_editar")[0].reset();
		$("#modalMantenedorEditarSbs").modal("hide");
		cargarSbsList();
      Swal.fire({
        title: "Subsidiaria editada correctamente",
        icon: "success",
      });
		
  }else{
	        Swal.fire({
        title: "Atención",
		text: mensaje,
        icon: "warning",
      });
  }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Swal.fire({
        title: "Atención",
        text: "Error al editar sucursal",
        icon: "warning",
      });
    });


  //cargarRolList();
}

function avisoSbs() {
  Swal.fire({
    title: "Aviso",
    text:
      "La subsidiaria se encuentra con datos asociados por lo que no se puede eliminar , si gusta se puede desactivar",
    icon: "warning",
  });
}

function avisoSucursal() {
  Swal.fire({
    title: "Aviso",
    text:
      "La sucursal se encuentra con datos asociados por lo que no se puede eliminar , si gusta se puede desactivar",
    icon: "warning",
  });
}
