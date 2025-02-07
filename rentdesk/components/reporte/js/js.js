//*****************************************************************************************
function seteaSucursal(empresa, sucursal) {
  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $.ajax({
    type: "POST",
    url: "components/reporte/models/setea_sucursal.php",
    data: "empresa=" + empresa + "&sucursal=" + sucursal,
    success: function (resp) {
      var retorno = resp.split("xxx,");
      var resultado = retorno[1];
      console.log(retorno);

      try {
        $("#divsucursal").html(resultado);
      } catch (err) {}
    },
  });
} //function seteaSucursal

//******************************************************************************************************

function enviar(url) {
  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  var datos = $("#formulario").serialize();

  $.ajax({
    type: "POST",
    url: "components/reporte/models/generar.php",
    data: datos,
    success: function (resp) {
      //window.open(url+"?token="+resp);
      window.location.href = url + "?token=" + resp;
    },
  });
}

function cargarGraficoEstadisticas() {
  $.ajax({
    url: "components/reporte/models/grafico_estadistica.php",
    type: "GET",
    dataType: "text",
    cache: false,
    success: function (res) {
      // Dividir la cadena en tres partes usando '||' como delimitador

      const partes = res.split("||");

      // Dividir cada parte usando '|' como delimitador para obtener los arrays individuales
      const meses = partes[0].split("|");

      const arrayResultado = JSON.parse("[" + cadenaSinComillas + "]");

      console.log(arrayResultado);
      const administracion = partes[1].split("|");
      const arriendo = partes[2].split("|");
      const ctx = document.getElementById("myChart").getContext("2d");

      // Datos
      const data = {
        labels: arrayResultado,
        datasets: [
          {
            label: "Administracion",
            data: administracion,
            backgroundColor: "rgba(75, 192, 192, 0.2)",
            borderColor: "rgba(75, 192, 192, 1)",
            borderWidth: 1,
          },
          {
            label: "Arriendo",
            data: arriendo,
            backgroundColor: "rgba(153, 102, 255, 0.2)",
            borderColor: "rgba(153, 102, 255, 1)",
            borderWidth: 1,
          },
        ],
      };

      // Configuración del gráfico
      const config = {
        type: "bar",
        data: data,
        options: {
          scales: {
            x: {
              stacked: true,
            },
            y: {
              stacked: true,
              beginAtZero: true,
            },
          },
        },
      };

      // Crear el gráfico
      const myChart = new Chart(ctx, config);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      // Manejar errores si es necesario
      console.log("error", jqXHR, textStatus, errorThrown);
    },
  });
}

function actualizaFechaInicio() {
  let fechaInicio = $("#fechaInicio").val();
  $("#fechaInicioHidden").val(fechaInicio);
}

function actualizaFechaTermino() {
  let fechaTermino = $("#fechaTermino").val();
  $("#fechaTerminoHidden").val(fechaTermino);
}
