function MenuCentro(div) {
  $("." + div).toggle("slow");
}

function actualizaSubsidiaria(baseUrl, subsidiaria) {
  console.log("baseUrl: ", baseUrl);
  console.log("SUBSIDIARIA DESDE actualizaSubsidiaria: ", subsidiaria);
  $.ajax({
    type: "POST",
    url: baseUrl + "components/dashboard/models/select-subsidiaria.php",
    data: "subsidiaria=" + subsidiaria,
    success: function (res) {
      document.location.reload();
    },
  });
}













//INICIO MUESTRA DE GRÁFICOS********************************************************************************************************************** */

// Load Charts and the corechart package.
google.charts.load('current', {'packages':['corechart']});




//***************************************************************************************************************************** */
//***************************************************************************************************************************** 

// Draw the pie chart for Morosos  when Charts is loaded.

google.charts.setOnLoadCallback(drawMorososChart);

function drawMorososChart() {

  // Create the data table for Morosos .
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Topping');
  data.addColumn('number', 'Slices');
  data.addRows([
    ['Al día', 1250],
    ['Morosas', 1569]
  ]);

  // Set options for Morosos pie chart.
  var options = {title:'Propiedades Morosas',
                 width:400,
                 height:300,
                 colors: ['#00a940', '#e83f3f']
                };

  // Instantiate and draw the chart for Morosos .
  var chart = new google.visualization.PieChart(document.getElementById('graficoTortaMorosos'));
  chart.draw(data, options);
}


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
  var options = {title:'Propiedades por Liquidar',
                 width:400,
                 height:300,
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
  var options = {title:'Propiedades Por Pagar',
                 width:400,
                 height:300,
                 colors: ['#00a940', '#f9d300']
                };

  // Instantiate and draw the chart for Por Pagar .
  var chart = new google.visualization.PieChart(document.getElementById('graficoTortaPorPagar'));
  chart.draw(data, options);
}

//***************************************************************************************************************** */
//***************************************************************************************************************** */

google.charts.load('current', {'packages':['bar']});
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

google.charts.load('current', {'packages':['bar']});
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

google.charts.load('current', {'packages':['bar']});
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


$(window).resize(function(){
  drawMorososChart();
  drawPorLiquidarChart();
  drawPorPagarChart();

  drawChartBarraMoroso();
  drawChartBarraLiquidar();
  drawChartBarraPagar();
 
});


//FIN MUESTRA DE GRÁFICOS********************************************************************************************************************** */