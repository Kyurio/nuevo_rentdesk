// $(document).ready(function () {
//   console.log("ENTRA A POST region_ciudad_comuna_com");
//   var pais = $("#hiddenpaiscom").val();
//   var region = $("#hiddenregioncom").val();
//   var comuna = $("#hiddencomunacom").val();

//   $.ajax({
//     type: "POST",
//     url: "includes/region_ciudad_comuna_com.php",
//     data:
//       "accion=" +
//       "0" +
//       "&valor=" +
//       "" +
//       "&valorpais=" +
//       pais +
//       "&valorregion=" +
//       region +
//       "&valorcomuna=" +
//       comuna,
//     success: function (resp) {
//       var retorno = resp.split("xxx,");
//       var resultado1 = retorno[1];
//       var resultado2 = retorno[2];
//       var resultado3 = retorno[3];

//       try {
//         $("#divpaiscom").html(resultado1);
//         $("#divregioncom").html(resultado2);
//         $("#divcomunacom").html(resultado3);
//       } catch (err) {}
//     },
//   });
// });

//********************************************************************************

function seteaRegionComunaCom(accion, valor) {
  console.log("ENTRA A seteaRegionComunaCom");
  console.log("ACCION-VALOR: ", { accion, valor });

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    type: "POST",
    url: "includes/region_ciudad_comuna_com.php",
    data: "accion=" + accion + "&valor=" + valor,
    success: function (resp) {
      var retorno = resp.split("xxx,");
      var resultado = retorno[1];
      //alert(retorno);

      try {
        if (accion == "1" && valor != "") {
          $("#divregioncom").html(resp);
          $("#divcomunacom").html(
            " <select id='comunacom' name='comunacom' style='color:#6d6c6c' required data-validation-required disabled  class='form-control'  ><option value=''>Antes seleccione la Región</option></select>"
          );
        }

        if (accion == "2" && valor != "") $("#divcomunacom").html(resp);

        if (accion == "1" && valor == "") {
          $("#divregioncom").html(
            " <select id='regioncom' name='regioncom' style='color:#6d6c6c' required data-validation-required disabled  class='form-control'  ><option value=''>Antes seleccione El Pais</option></select>"
          );
          $("#divcomunacom").html(
            " <select id='comunacom' name='comunacom' style='color:#6d6c6c' required data-validation-required disabled  class='form-control'  ><option value=''>Antes seleccione la Región</option></select>"
          );
        }

        if (accion == "2" && valor == "") {
          $("#divcomunacom").html(
            " <select id='comunacom' name='comunacom' style='color:#6d6c6c' required data-validation-required disabled  class='form-control'  ><option value=''>Antes seleccione la Región</option></select>"
          );
        }
      } catch (err) {}
    },
  });
} //function seteaProvinciaComuna

function seteaPaisRegionComunaCom(accion, valor, valorPais, valorRegion, valorComuna) {
  console.log("ENTRA A seteaRegionComunaCom");
  console.log("ACCION-VALOR: ", { accion, valor });

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    type: "POST",
    url: "includes/region_ciudad_comuna_com.php",
    data:
      "accion=" +
      accion +
      "&valor=" +
      valor +
      "&valorpais=" +
      valorPais +
      "&valorregion=" +
      valorRegion +
      "&valorcomuna=" +
      valorComuna,
    success: function (resp) {
      var retorno = resp.split("xxx,");
      var resultado = retorno[1];
      //alert(retorno);

      try {
        if (accion == "1" && valor != "") {
          $("#divregioncom").html(resp);
          $("#divcomunacom").html(
            " <select id='comunacom' name='comunacom' style='color:#6d6c6c' required data-validation-required disabled  class='form-control'  ><option value=''>Antes seleccione la Región</option></select>"
          );
        }

        if (accion == "2" && valor != "") $("#divcomunacom").html(resp);

        if (accion == "1" && valor == "") {
          $("#divregioncom").html(
            " <select id='regioncom' name='regioncom' style='color:#6d6c6c' required data-validation-required disabled  class='form-control'  ><option value=''>Antes seleccione El Pais</option></select>"
          );
          $("#divcomunacom").html(
            " <select id='comunacom' name='comunacom' style='color:#6d6c6c' required data-validation-required disabled  class='form-control'  ><option value=''>Antes seleccione la Región</option></select>"
          );
        }

        if (accion == "2" && valor == "") {
          $("#divcomunacom").html(
            " <select id='comunacom' name='comunacom' style='color:#6d6c6c' required data-validation-required disabled  class='form-control'  ><option value=''>Antes seleccione la Región</option></select>"
          );
        }
      } catch (err) {}
    },
  });
}
