function largoPass(e) {
  if (e.value.length < 6) {
    $("#password").addClass("input-error");
  } else {
    $("#password").removeClass("input-error");
  }
} //function largoPass()

//***************************************************************************
$(function () {
  $("#formlogin").submit(function (e) {
    e.preventDefault(); //para que no se envíe el formulario

    var mensajeError = "";

    var url = "login/models/login.php";

    $.ajax({
      type: "POST",
      url: url,
      data: $("#formlogin").serialize(),
      success: function (resp) {
        var retorno = resp.split("|");
        var resultado = retorno[1];
        var cantidadEmpresas = retorno[2];

        if (resultado == "0") {
          mensajeError = "Datos de acceso incorrectos.";
          $("#password").val("");
        }

        if (resultado == "1") {
          mensajeError = "No fue posible comunicarse con el servicio de autenticación";
          $("#password").val("");
        }

        if (resultado == "2") {
          mensajeError = "Ocurrio un error al obtener las empresas, comuniquese con el administrador.";
          $("#password").val("");
        }

        if (resultado == "3") {
          mensajeError = "El usuario no tiene empresas asignadas.";
          $("#password").val("");
        }

        if (resultado == "x") {
          mensajeError = "Formulario inválido." ;
        }

        if (resultado == "ERROR") {
          mensajeError = "Datos de acceso inválidos. Intente nuevamente." ;
        }

        if(resultado!="ok")
        $(".error-login").html("<div class='alert alert-danger' role='alert'>"+mensajeError+"</div>");

        if (resultado == "ok" && cantidadEmpresas > 1) {
          var opcion = retorno[3];
          formSeleccionarEmpresa(opcion);
        }

        if (resultado == "ok" && cantidadEmpresas == "1") {
          document.location.href = "index.php?component=dashboard&view=dashboard";
        }

        //****************************************
      },
    });
  });

  $("#formChangePass").submit(function (e) {
    e.preventDefault(); //para que no se envíe el formulario
    var password_new = $("#password_new").val();
    var password_new_rep = $("#password_new_rep").val();

    if (password_new != password_new_rep) {
      $.showAlert({
        title: "Atención",
        body: "la nueva clave y la repeticion no coinciden. Por favor ingreselas nuevamente.",
      });
      $("#password_act").focus();
    } else {
      if (password_new.length < 6) {
        $.showAlert({ title: "Atención", body: "La clave debe tener a lo menos 6 caracteres" });
        $("#password_act").focus();
      } else {
        var url = "login/models/changepassword.php";
        $.ajax({
          type: "POST",
          url: url,
          data: $("#formChangePass").serialize(),
          success: function (resp) {
            var retorno = resp.split("|");
            var resultado = retorno[1];
            var mensaje = retorno[2];

            console.log(resultado+"--"+retorno);

            if (resultado == "ERROR") {
     
              $("#password_act").focus();
            } else {
              $.showAlert({ title: "Atención", body: mensaje });
              document.location.href = "index.php?component=dashboard&view=dashboard";
            }
            //****************************************
          },
        });
      }
    }
  });
});

function formOlvido() {
  $.showModal({
    title: "Olvido de Contraseña",
    body:
      '<form name="formolvido" id="formolvido"><div class="form-group row"><div style="padding:15px;">Ingrese su correo y le serán enviadas las instrucciones para recuperar su contraseña.</div>' +
      '<div class="col-3"><label for="text" class="col-form-label">Email</label></div>' +
      '<div class="col-9"><input type="text" class="form-control" id="olvidoemail" name="olvidoemail" placeholder="Ingrese su correo"/></div>' +
      "</div>" +
      '<div class="form-group row">' +
      "</div></form>",
    footer:
      '<button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Enviar</button>',
    onCreate: function (modal) {
      // create event handler for form submit and handle values
      $(modal.element).on("click", "button[type='submit']", function (event) {
        event.preventDefault();
        var $form = $(modal.element).find("form");

        /*
            $.showAlert({
                title: "Result",
                body:
                    "<b>text:</b> " + $form.find("#text").val() + "<br/>" +
                    "<b>select:</b> " + $form.find("#select").val() + "<br/>" +
                    "<b>textarea:</b> " + $form.find("#textarea").val()
            })
			
			*/

        var validado = 0;
        var correo = $form.find("#olvidoemail").val();

        var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

        if (regex.test(correo.trim())) {
          validado = 1;
        } else {
          validado = 0;
        }

        if (validado == 1) {
          olvido($form.find("#olvidoemail").val());

          modal.hide();
        } else {
          $.showAlert({ title: "Atención", body: "Debe ingresar un correo válido." });
        }

        //modal.hide();
      });
    },
  });
} //function formOlvido

function olvido(email) {
  if (email != "") {
    var url = "login/models/olvido.php";
    var data = "&email=" + email;

    $.ajax({
      type: "POST",
      url: url,
      data: data,
      success: function (resp) {
        //alert(resp);
        var retorno = resp.split("xxx,");
        var resultado = retorno[1];

        if (resultado == "0") {
          $.showAlert({
            title: "Atención",
            body: "El email ingresado no se encuentra registrado.",
          });
          return false;
        }

        if (resultado == "1") {
          $.showAlert({
            title: "Atención",
            body: "Las instrucciones para recuperar su clave se han enviado a su correo electrónico.",
          });
          return true;
        }
      },
    });
  } else {
    $.showAlert({ title: "Atención", body: "Debe completar el correo electrónico." });
  }
} // function olvido

//***************************************************************************************************************************************

$(document).ready(function () {
  var longitud = false,
    minuscula = false,
    numero = false,
    mayuscula = false;
  $(".segclave")
    .keyup(function () {
      var pswd = $(this).val();
      if (pswd.length < 6) {
        $("#length").removeClass("valid").addClass("invalid");
        longitud = false;
      } else {
        $("#length").removeClass("invalid").addClass("valid");
        longitud = true;
      }

      //validate letter
      if (pswd.match(/[A-z]/)) {
        $("#letter").removeClass("invalid").addClass("valid");
        minuscula = true;
      } else {
        $("#letter").removeClass("valid").addClass("invalid");
        minuscula = false;
      }

      //validate capital letter
      if (pswd.match(/[A-Z]/)) {
        $("#capital").removeClass("invalid").addClass("valid");
        mayuscula = true;
      } else {
        $("#capital").removeClass("valid").addClass("invalid");
        mayuscula = false;
      }

      //validate number
      if (pswd.match(/\d/)) {
        $("#number").removeClass("invalid").addClass("valid");
        numero = true;
      } else {
        $("#number").removeClass("valid").addClass("invalid");
        numero = false;
      }
    })
    .focus(function () {
      $("#pswd_info").show();
    })
    .blur(function () {
      $("#pswd_info").hide();
    });

  $("#registro").submit(function (event) {
    if (longitud && minuscula && numero && mayuscula) {
      //  alert("password correcto");
      //  $("#registro").submit();
    } else {
      alert("Password invalido.");
      event.preventDefault();
    }
  });
});

function restauraClave(token) {
  var iguales = 0;
  var password1 = document.getElementById("password").value;
  var password2 = document.getElementById("password2").value;

  if (password1 == password2) iguales = 1;

  if (iguales == "1") {
    var url = "login/models/procesa_recuperar.php";
    var data = "&pass=" + password1 + "&token=" + token;

    $.ajax({
      type: "POST",
      url: url,
      data: data,
      success: function (resp) {
        //alert(resp);
        var retorno = resp.split("xxx,");
        var resultado = retorno[1];

        if (resultado == "0") {
          $.showAlert({
            title: "Atención",
            body: "El enlace ha caducado. Vuelva a solicitar la recuperación de contraseña.",
          });
          return true;
        }

        if (resultado == "1") {
          $.showAlert({
            title: "Atención",
            body: "La contraseña ha sido cambiada. Puede acceder con su nueva contraseña <a href='index.php'>haciendo click aquí.</a>",
          });
          return true;
        }
      },
    });
  } else {
  } //if( iguales=="1" )

  $.showAlert({
    title: "Atención",
    body: "Las contraseñas ingresadas no coinciden. Deben ser iguales.",
  });
} //function restauraClave(token)

//****************************************************************

function minusculas(e) {
  e.value = e.value.toLowerCase();
}

//**************************************************************************************************************************
//**************************************************************************************************************************

function formSeleccionarEmpresa(opcion) {
  $.showModal({
    title: "Selecci&oacute;n de Empresa",
    body:
      '<form name="formempresa" id="formempresa">' +
      '<div class="form-group row">' +
      '<div style="padding:15px;">Seleccione la Empresa con la cual trabajar&aacute;.</div>' +
      "</div>" +
      '<div class="row">' +
      '<div class="col-3"><label for="text" class="col-form-label">Empresa</label></div>' +
      '<div class="col-9">' +
      opcion +
      "</div>" +
      '<div class="form-group row">' +
      "</div>" +
      "</form>",
    footer:
      '<button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Aceptar</button>',
    onCreate: function (modal) {
      // create event handler for form submit and handle values
      $(modal.element).on("click", "button[type='submit']", function (event) {
        event.preventDefault();
        var $form = $(modal.element).find("form");

        var empresa = $form.find("#empresa").val();
        var sucursal = "";

        if (empresa == "") {
          $.showAlert({ title: "Atención", body: "Debe seleccionar una empresa" });
        } else {
          seteaEmpresa(empresa, sucursal);
        }
      });
    },
  });
} //function formOlvido

function seteaEmpresa(empresa, sucursal) {
  var url = "login/models/seteaEmpresa.php";
  $.ajax({
    type: "POST",
    url: url,
    data: "&empresa=" + empresa + "&sucursal=" + sucursal,
    success: function (resp) {
      var retorno = resp.split(",xxx,");
      var resultado = retorno[1];

      if (resultado == "ok") {
        document.location.href = "index.php?component=dashboard&view=dashboard";
      } else {
        $.showAlert({
          title: "Atención",
          body: "Error al setear empresa, por favor intentelo nuevamente.",
        });
        return;
      }
    },
  });
} //seteaEmpresa
