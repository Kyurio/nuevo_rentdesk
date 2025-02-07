/*
$(document).ready(function () {
  var pais = $("#hiddenpais").val();
  var region = $("#hiddenregion").val();
  var comuna = $("#hiddencomuna").val();

  $.ajax({
    type: "POST",
    url: "includes/region_ciudad_comuna.php",
    data:
      "accion=" +
      "0" +
      "&valor=" +
      "" +
      "&valorpais=" +
      pais +
      "&valorregion=" +
      region +
      "&valorcomuna=" +
      comuna,
    success: function (resp) {
      var retorno = resp.split("xxx,");
      var resultado1 = retorno[1];
      var resultado2 = retorno[2];
      var resultado3 = retorno[3];

      try {
        $("#divpais").html(resultado1);
        $("#divregion").html(resultado2);
        $("#divcomuna").html(resultado3);
      } catch (err) {}
    },
  });
});
*/

//********************************************************************************

function seteaRegionComuna(accion, valor, region, comuna) {
  console.log("ENTRA A seteaRegionComuna");
  console.log("ACCION-VALOR: ", { accion, valor });

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    type: "POST",
    url: "includes/region_ciudad_comuna.php",
    data:
      "accion=" +
      accion +
      "&valor=" +
      valor +
      "&valorpais=" +
      valor +
      "&valorregion=" +
      region +
      "&valorcomuna=" +
      comuna,
    success: function (resp) {
      var retorno = resp.split("xxx,");
      var resultadoPais = retorno[1];
      var resultadoRegion = retorno[2];
      var resultadoComuna = retorno[3];

      try {
        if (accion == "0") {
          $("#divpais").html(resultadoPais);
          $("#divregion").html(resultadoRegion);
          $("#divcomuna").html(resultadoComuna);
        } // if(accion=="0")

        if (accion == "1" && valor != "") {
          $("#divregion").html(resp);
          $("#divcomuna").html(
            " <select id='comuna' name='comuna' style='color:#6d6c6c'  disabled  class='form-control'  ><option value=''>Antes seleccione la Región</option></select>"
          );
        }

        if (accion == "2" && valor != "") $("#divcomuna").html(resp);
		if (accion == "4" && valor != "") $("#divcomuna").html(resp);

        if (accion == "1" && valor == "") {
          $("#divregion").html(
            " <select id='region' name='region' style='color:#6d6c6c' required data-validation-required disabled  class='form-control'  ><option value=''>Antes seleccione El Pais</option></select>"
          );
          $("#divcomuna").html(
            " <select id='comuna' name='comuna' style='color:#6d6c6c'  disabled  class='form-control'  ><option value=''>Antes seleccione la Región</option></select>"
          );
        }

        if (accion == "2" && valor == "") {
          $("#divcomuna").html(
            " <select id='comuna' name='comuna' style='color:#6d6c6c'  disabled  class='form-control'  ><option value=''>Antes seleccione la Región</option></select>"
          );
        }
		
		if (accion == "4" && valor == "") {
          $("#divcomuna").html(
            " <select id='comuna' name='comuna' style='color:#6d6c6c'  disabled  class='form-control'  ><option value=''>Antes seleccione la Región</option></select>"
          );
        }
		
		if (accion == "3" && valor != "") {
          $("#divregion").html(resp);
          $("#divcomuna").html(
            " <select id='comuna' name='comuna' style='color:#6d6c6c' disabled data-validation-required class='form-control'  ><option value=''>Antes seleccione la Región</option></select>"
          );
        }
      } catch (err) {}
    },
  });
} //function seteaProvinciaComuna
