<?php


 
?>


<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- <title><?php echo $config->sitename; ?></title> -->
  <title>Fuenzalida Propiedades</title>

  <link href="../favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <!-- Bootstrap -->
  <!-- <link href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->

  <!--VERSIÓN 5.3-->
  <link href="../js/bootstrap-5.3/css/bootstrap.min.css" rel="stylesheet">

  <link href="../template/css/main.css" rel="stylesheet" />
  <link href="../js/bootstrap/css/bootstrap-dialog.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="../js/datatable/media/css/jquery.dataTables.css">






  <!-- Custom CSS -->


  <!-- Custom Fonts -->

  <!-- <link href="template/font-awesome/css/all.css" rel="stylesheet"> -->

  <link href="../template/font-awesome-6.5.1/css/all.css" rel="stylesheet">




  <link href="../template/css/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../js/fancybox/jquery.fancybox.min.css">

  <link href="../template/css/style.css" rel="stylesheet">

  <!--SELECT2-->
  <!-- <link href="js/select2/select2.min.css" rel="stylesheet" /> -->


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

  <script src="../js/jquery-1.12.1.min.js"></script>
  <!--JQUERY MIGRATE 1.4.1-->
  <script src="https://code.jquery.com/jquery-migrate-1.4.1.js"></script>

  <!--JQUERY MIGRATE 3.4.0-->
  <script src="https://code.jquery.com/jquery-migrate-3.4.0.js"></script>

  <script src="../js/jquery-3.7.1.js"></script>



  <!-- <script src="js/bootstrap/js/bootstrap.min.js"></script> -->

  <!--VERSIÓN 5.3-->
  <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="js/bootstrap-5.3/js/bootstrap.min.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="../js/bootstrap/js/bootstrap-dialog.js"></script>
  <script src="../js/validadores.js"></script>
  <script src="../js/funciones.js"></script>

  <script src="../js/fancybox/jquery.fancybox.min.js"></script>

  <!-- <script type="text/javascript" language="javascript" src="js/datatable/media/js/jquery.dataTables.js"></script> -->
  <!--DATATABLES LATEST 1.13.8-->

  <link href="../js/datatable-latest-1.13.8/datatables.min.css" rel="stylesheet">
  <!-- <script src="js/datatable-latest-1.13.8/datatables.min.js"></script> -->

  <!--SELECT2-->
  <!-- <script src="js/select2/select2.min.js"></script> -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



  <script language="JavaScript" src="../js/jquery.blockUI.js"></script>

  <?php echo $incluir_js; ?>
  <?php echo $incluir_css; ?>

  <script>
    function toggleSubMenu(submenuId) {
      var submenu = document.getElementById(submenuId);
      submenu.style.display = (submenu.style.display === 'block') ? 'none' : 'block';
    }
  </script>





<script type="text/javascript">

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

</script>






</head>

<body>
  <div class="wrapper">
    <div class="sidebar" data-color="white" data-active-color="danger">
      <div class="logo">
        <a href="https://apps.fuenzalida.com/intranetFP/index.php" class="simple-text logo-normal">
          <div class="logo-image-big">
            <img src="../images/logo_fuenzalida_propiedades.svg" style="height:56px; width:auto;">
          </div>
        </a>
      </div>



      <?php
      include("components/menu/menu_lateral.php");
      ?>




    </div>
    <div class="main-panel">
      <?php
      include("components/menu/menu_superior.php");
      ?>


      <div class="row">


      <br>&nbsp;<br><br>&nbsp;<br><br>&nbsp;<br>






<!-- Inicio Gráficos ********************************************************************************************************************************************************************** -->

<div class="container">
	<div class="row">
		<div  class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4" ><div id="graficoTortaMorosos" style="border: 1px solid #ccc;box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);
-webkit-box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);-moz-box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);"></div></div>
		<div  class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4"><div id="graficoTortaPorLiquidar" style="border: 1px solid #ccc;box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);
-webkit-box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);-moz-box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);"></div></div>
		<div  class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4"><div id="graficoTortaPorPagar" style="border: 1px solid #ccc;box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);
-webkit-box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);-moz-box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);"></div></div>
	</div>





  <div  class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4" style="float:left;">
    <div id="mesualMoroso" style="  height: 400px; padding:10px; margin:12px; margin-top:30px;border: 1px solid #ccc;box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);-webkit-box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);-moz-box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);"></div>
  </div>




  <div  class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4" style="float:left;" >
    <div id="mesualLiquidar" style="  height: 400px; padding:10px; margin:12px; margin-top:30px;border: 1px solid #ccc;box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);-webkit-box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);-moz-box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);"></div>
  </div>


  <div  class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4" style="float:left;" >
    <div id="mesualPagar" style="  height: 400px; padding:10px; margin:12px; margin-top:30px;border: 1px solid #ccc;box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);-webkit-box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);-moz-box-shadow: -4px 5px 13px -2px rgba(202,202,202,0.75);"></div>
  </div>



</div>

<!-- Fin Gráficos ********************************************************************************************************************************************************************** -->


<br>&nbsp;<br><br>&nbsp;<br><br>&nbsp;<br><br>&nbsp;<br>


        <?php


        include("controller/controller.php");

        ?>
      </div>



      <footer style="bottom:0;padding-top:200px;padding:2rem 4rem;background-color: #f9203d;  display:flex; justify-content:space-between;align-items:center;">
        <img src="../images/logo_fuenzalida_propiedades_dark.svg" style="height:56px; width:auto;">

        <div style="color: #fff">
          <div style="font-size: .75rem;">Nuestros Servicios</div>
          <ul class="p-0 m-0" style="list-style-type:none;">
            <li style="font-size: 1rem;">Contacta a tu Ejecutivo</li>
            <li style="font-size: 1rem;">Arrienda tu Propiedad</li>
          </ul>
        </div>

      </footer>
    </div>

  </div>


  <!--
        <div class="">
        </div>
        <footer class="footer footer-black  footer-white ">
          <div class="container-fluid">
            <div class="row">
              <nav class="footer-nav">
                <ul>
                  <li>
                    <a href="https://apps.fuenzalida.com/intranetFP/index.php" class="simple-text logo-normal">
                      <div class="logo-image-big">
                        <img src="images/logo.png">
                      </div>
                    </a>
                    Versión <?php echo $version_app; ?>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </footer>
      </div>
    </div>

              -->






  <!-- 

  <div style="background-color: #f9203d; width:100%; margin-top:200px;">
        <img src="images/fuenzalida_footer.png">
      </div> -->





  <script type="text/javascript" src="../js/datetimepicker/moment.js"></script>
  <script type="text/javascript" src="../js/datetimepicker/es_moment.js"></script>
  <script type="text/javascript" src="../js/datetimepicker/bootstrap-datetimepicker.min.js"></script>
  <script type="text/javascript" src="../login/js/bootstrap-show-modal.js"></script>
  <script src="../js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="../js/paper-dashboard.min.js?v=2.0.0" type="text/javascript"></script>

  <script type="text/javascript" src="../js/jquerymask/jquery.mask.min.js"></script>

  <script src="../js/rut/jquery.rut.js"></script>


</body>



</html>