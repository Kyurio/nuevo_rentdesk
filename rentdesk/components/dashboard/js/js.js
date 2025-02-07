function MenuCentro(div) {
  $("." + div).toggle("slow");
}








//INICIO MUESTRA DE GRÁFICOS********************************************************************************************************************** */

// Load Charts and the corechart package.
google.charts.load('current', { 'packages': ['corechart'] });




//***************************************************************************************************************************** */
//***************************************************************************************************************************** 




//***************************************************************************************************************************** */
//***************************************************************************************************************************** */


// Draw the pie chart for the Por Liquidar  when Charts is loaded.
google.charts.setOnLoadCallback(drawPorLiquidarChart);


// Callback that draws the pie chart for Por Liquidar .
function drawPorLiquidarChart() {

  // Create the data table for Por Liquidar .
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Topping');
  data.addColumn('number', 'Slices');
  data.addRows([
    ['Liquidadas', 2390],
    ['Por Liquidar', 133]
  ]);

  // Set options for Por Liquidar pie chart.
  var options = {
    title: 'Propiedades por Liquidar',
    width: 400,
    height: 300,
    colors: ['#00a940', '#f99200']
  };

  // Instantiate and draw the chart for Por Liquidar .
  var chart = new google.visualization.PieChart(document.getElementById('graficoTortaPorLiquidar'));
  chart.draw(data, options);
}



//***************************************************************************************************************************** */
//***************************************************************************************************************************** */




// Draw the pie chart for Pagar  when Charts is loaded.
google.charts.setOnLoadCallback(drawPorPagarChart);


function drawPorPagarChart() {

  // Create the data table for Pagar .
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Topping');
  data.addColumn('number', 'Slices');
  data.addRows([
    ['Al día', 2250],
    ['Por Pagar', 569]
  ]);

  // Set options for Morosos pie chart.
  var options = {
    title: 'Propiedades Por Pagar',
    width: 400,
    height: 300,
    colors: ['#00a940', '#f9d300']
  };

  // Instantiate and draw the chart for Por Pagar .
  var chart = new google.visualization.PieChart(document.getElementById('graficoTortaPorPagar'));
  chart.draw(data, options);
}

//***************************************************************************************************************** */
//***************************************************************************************************************** */

google.charts.load('current', { 'packages': ['bar'] });
google.charts.setOnLoadCallback(drawChartBarraMoroso);

function drawChartBarraMoroso() {
  var data = google.visualization.arrayToDataTable([
    ['Mes', 'Al día', 'Morosas'],
    ['Enero', 3170, 460],
    ['Febrero', 660, 1120],
    ['Marzo', 1030, 540]
  ]);

  var options = {
    chart: {
      title: 'Propiedades Morosas',
      subtitle: 'Comparación como referencia al día 15 de cada mes ',
    },
    colors: ['#00a940', '#e83f3f']
  };

  var chart = new google.charts.Bar(document.getElementById('mesualMoroso'));

  chart.draw(data, google.charts.Bar.convertOptions(options));
}



//***************************************************************************************************************** */
//***************************************************************************************************************** */

google.charts.load('current', { 'packages': ['bar'] });
google.charts.setOnLoadCallback(drawChartBarraLiquidar);

function drawChartBarraLiquidar() {
  var data = google.visualization.arrayToDataTable([
    ['Mes', 'Al día', 'Por Liquidar'],
    ['Enero', 2170, 360],
    ['Febrero', 1660, 1120],
    ['Marzo', 3030, 440]
  ]);

  var options = {
    chart: {
      title: 'Propiedades Por Liquidar',
      subtitle: 'Comparación como referencia al día 15 de cada mes ',
    },
    colors: ['#00a940', '#f99200']
  };

  var chart = new google.charts.Bar(document.getElementById('mesualLiquidar'));

  chart.draw(data, google.charts.Bar.convertOptions(options));
}



//***************************************************************************************************************** */
//***************************************************************************************************************** */

google.charts.load('current', { 'packages': ['bar'] });
google.charts.setOnLoadCallback(drawChartBarraPagar);

function drawChartBarraPagar() {
  var data = google.visualization.arrayToDataTable([
    ['Mes', 'Al día', 'Por Pagar'],
    ['Enero', 1170, 2360],
    ['Febrero', 660, 1620],
    ['Marzo', 2030, 1440]
  ]);

  var options = {
    chart: {
      title: 'Propiedades Por Pagar',
      subtitle: 'Comparación como referencia al día 15 de cada mes ',
    },
    colors: ['#00a940', '#f9d300']
  };

  var chart = new google.charts.Bar(document.getElementById('mesualPagar'));

  chart.draw(data, google.charts.Bar.convertOptions(options));
}



//***************************************************************************************************************** */
//***************************************************************************************************************** */


$(window).resize(function () {
  drawPorLiquidarChart();
  drawPorPagarChart();

  drawChartBarraMoroso();
  drawChartBarraLiquidar();
  drawChartBarraPagar();

});


//***************************************************************************************************************** */
//***************************************************************************************************************** */
function ChartsMoras() {
  // Llamadas a las dos URLs
  const morososRequest = $.ajax({
    url: 'components/dashboard/models/chartsMorosos.php',
    method: 'GET'
  });

  const noMorososRequest = $.ajax({
    url: 'components/dashboard/models/chartsNoMorosos.php',
    method: 'GET'
  });

  // Espera a que ambas peticiones terminen
  $.when(morososRequest, noMorososRequest).done(function (morososResponse, noMorososResponse) {
    // Procesa los datos de los morosos
    const morososData = (morososResponse);
    const morosos = (morososData).length;

    // Procesa los datos de los no morosos
    const noMorososData = (noMorososResponse[0]);
    const noMorosos = (noMorososData).length;


    // Crear el gráfico
    const ctx = document.getElementById('myChart');
    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Morosos', 'No Morosos'],
        datasets: [{
          label: '# de Propiedades',
          data: [morosos, noMorosos],
          backgroundColor: ['#EE4E4E', '#40A578'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Distribución de Propiedades Morosas y No Morosas'
          }
        }
      }
    });
  }).fail(function () {
   // alert('Error al obtener los datos de una o ambas fuentes.');
  });

 
}

function propiedadesPorLiquidar() {
  // Realiza las peticiones AJAX
  const propiedadesRequest = $.ajax({
    url: 'components/dashboard/models/chartsPropiedadesPorLiquidar.php',
    method: 'GET',
    dataType: 'json' // Asegúrate de que el servidor devuelva JSON
  });

  const propiedadesHabilitadasRequest = $.ajax({
    url: 'components/dashboard/models/chartsPropiedadesHabilitadas.php',
    method: 'GET',
    dataType: 'json' // Asegúrate de que el servidor devuelva JSON
  });

  // Espera a que ambas peticiones terminen
  $.when(propiedadesRequest, propiedadesHabilitadasRequest).done(function (response1, response2) {
    // Las respuestas están contenidas en arreglos, el primer elemento es el dato real
    const propiedadesData = response1[0];
    const habilitadasData = response2[0];

    // Verifica que ambas respuestas sean válidas
    if (!propiedadesData || typeof propiedadesData !== 'object') {
      //alert("Error: Respuesta inesperada del servidor para propiedades por liquidar.");
      return;
    }
    if (!habilitadasData || typeof habilitadasData !== 'object') {
      //alert("Error: Respuesta inesperada del servidor para propiedades habilitadas.");
      return;
    }

    // Extrae los datos necesarios
    const liquidables = propiedadesData.length; // Cantidad de propiedades por liquidar
    const habilitadas = habilitadasData.length; // Cantidad de propiedades habilitadas

 
    // Crear el gráfico
    const ctx = document.getElementById('PropiedadesPorLiquidar');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Por Liquidar', 'Habilitadas'],
        datasets: [{
          label: '# de Propiedades',
          data: [liquidables, habilitadas], // Puedes ajustar los valores según lo necesites
          backgroundColor: ['#EE4E4E', '#40A578'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Distribución de Propiedades'
          }
        }
      }
    });
  }).fail(function () {
  });
}

function PropiedadesPorPagar() {
  // Realiza las peticiones AJAX
  const propiedadesPorPagarRequest = $.ajax({
    url: 'components/dashboard/models/ChartsPropiedadesPorPagar.php',
    method: 'GET',
    dataType: 'json' // Asegúrate de que el servidor devuelva JSON
  });


  const propiedadesHabilitadasRequest = $.ajax({
    url: 'components/dashboard/models/chartsPropiedadesHabilitadas.php',
    method: 'GET',
    dataType: 'json' // Asegúrate de que el servidor devuelva JSON
  });

  // Espera a que ambas peticiones terminen
  $.when(propiedadesPorPagarRequest, propiedadesHabilitadasRequest).done(function (response1, response2) {
    // Procesa las respuestas (el primer elemento contiene los datos)
    const propiedadesPorPagarData = response1[0];
    const propiedadesHabilitadasData = response2[0];

    // Validación de respuestas
    if (!Array.isArray(propiedadesPorPagarData)) {
      return;
    }
    if (!Array.isArray(propiedadesHabilitadasData)) {
      return;
    }

    // Extrae los datos necesarios
    const porPagar = propiedadesPorPagarData.length; // Cantidad de propiedades por pagar
    const habilitadas = propiedadesHabilitadasData.length; // Cantidad de propiedades habilitadas

    // Debugging opcional
    console.log("Propiedades por pagar:", propiedadesPorPagarData);
    console.log("Propiedades habilitadas:", propiedadesHabilitadasData);

    // Crear el gráfico
    const ctx = document.getElementById('PropiedadesPorPagar');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Por Pagar', 'Habilitadas'],
        datasets: [{
          label: '# de Propiedades',
          data: [porPagar, habilitadas], // Datos a graficar
          backgroundColor: ['#EE4E4E', '#40A578'],// Colores del gráfico
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Distribución de Propiedades por Pagar y Habilitadas'
          }
        }
      }
    });
  }).fail(function () {
  });
}


document.addEventListener('DOMContentLoaded', () => {
  ChartsMoras();
  propiedadesPorLiquidar();
  PropiedadesPorPagar();
});