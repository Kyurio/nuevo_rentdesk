<?php


// $nombre_user  = $_SESSION["usuario_nombre"];
// $url_logout     = "/" . $config->urlbase . "/login/logout.php";
// $url_changepass = "index.php?component=changepassword&view=dashboard";

// $combo_empresas = "";
// if (@$_SESSION["cantidad_empresas"] > 1) {
//   $combo_empresas = @$_SESSION["combo_empresas"];
//   $combo_empresas = str_replace("'", "hgibsduutask6udkyuas", $combo_empresas);
//   $combo_empresas = str_replace('"', "uusdnlsduhfushdfsdfh", $combo_empresas);
// }
$config    = new Config;
$version_app = $config->version_app;
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

  <link href="favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <!-- Bootstrap -->
  <!-- <link href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->

  <!--VERSIÓN 5.3-->
  <link href="js/bootstrap-5.3/css/bootstrap.min.css" rel="stylesheet">

  <link href="template/css/main.css" rel="stylesheet" />
  <link href="js/bootstrap/css/bootstrap-dialog.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="js/datatable/media/css/jquery.dataTables.css">







  <!-- Custom CSS -->


  <!-- Custom Fonts -->

  <!-- <link href="template/font-awesome/css/all.css" rel="stylesheet"> -->

  <link href="template/font-awesome-6.5.1/css/all.css" rel="stylesheet">




  <link href="template/css/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox.min.css">

  <link href="template/css/style.css?v=<?php echo @$version_app; ?>" rel="stylesheet">

  <!--SELECT2-->
  <!-- <link href="js/select2/select2.min.css" rel="stylesheet" /> -->


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

  <script src="js/jquery-1.12.1.min.js"></script>
  <!--JQUERY MIGRATE 1.4.1-->
  <script src="https://code.jquery.com/jquery-migrate-1.4.1.js"></script>

  <!--JQUERY MIGRATE 3.4.0-->
  <script src="https://code.jquery.com/jquery-migrate-3.4.0.js"></script>

  <script src="js/jquery-3.7.1.js"></script>


  <script src="js/historial.js?v=<?php echo @$version_app; ?>"></script>
  <script src="js/alertas.js?v=<?php echo @$version_app; ?>"></script>
  <script src="js/rut_validador.js?v=<?php echo @$version_app; ?>"></script>
  <!-- <script src="js/bootstrap/js/bootstrap.min.js"></script> -->

  <!--VERSIÓN 5.3-->
  <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="js/bootstrap-5.3/js/bootstrap.min.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="js/bootstrap/js/bootstrap-dialog.js"></script>
  <script src="js/validadores.js?v=<?php echo @$version_app; ?>"></script>
  <script src="js/funciones.js?v=<?php echo @$version_app; ?>"></script>

  <script src="js/fancybox/jquery.fancybox.min.js"></script>

  <!-- <script type="text/javascript" language="javascript" src="js/datatable/media/js/jquery.dataTables.js"></script> -->
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
  <!--DATATABLES LATEST 1.13.8-->

  <link href="js/datatable-latest-1.13.8/datatables.min.css" rel="stylesheet">
  <!-- <script src="js/datatable-latest-1.13.8/datatables.min.js"></script> -->

  <!--SELECT2-->
  <!-- <script src="js/select2/select2.min.js"></script> -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- compoentes de bootstrpa para picker de años -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />

  <!-- Incluye las bibliotecas necesarias aquí -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- bruno -->
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>



  <?php echo $incluir_js; ?>
  <?php echo $incluir_css; ?>

  <script>
    function toggleSubMenu(submenuId) {
      var submenu = document.getElementById(submenuId);
      submenu.style.display = (submenu.style.display === 'block') ? 'none' : 'block';
    }
  </script>

  <script src="js/sweetalert/sweetalert2@11.js"></script>

</head>

<body>
  <div class="wrapper">
    <div class="sidebar" data-color="white" data-active-color="danger">
      <div class="logo">
        <a href="index.php?component=dashboard&view=dashboard" class="simple-text logo-normal">
          <div class="logo-image-big">
            <img src="images/logo_fuenzalida_propiedades.svg" style="height:56px; width:auto;">
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
      //include("includes/select-subsidiaria-vista.php"); 
      ?>


      <div class="row">
        <?php



        include("controller/controller.php");

        ?>
      </div>



      <footer style="bottom:0;padding-top:200px;padding:2rem 4rem;background-color: #f9203d;  display:flex; justify-content:space-between;align-items:center;">
        <img src="images/logo_fuenzalida_propiedades_dark.svg" style="height:56px; width:auto;">

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


  <!-- Incluye jQuery y DataTables aquí -->
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

  <script language="JavaScript" src="js/jquery.blockUI.js"></script>

  <script type="text/javascript" src="js/datetimepicker/moment.js"></script>
  <script type="text/javascript" src="js/datetimepicker/es_moment.js"></script>
  <script type="text/javascript" src="js/datetimepicker/bootstrap-datetimepicker.min.js"></script>
  <script type="text/javascript" src="login/js/bootstrap-show-modal.js"></script>
  <script src="js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/paper-dashboard.min.js?v=2.0.0" type="text/javascript"></script>


  <!-- js para  picker de año de bootstrap -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>

  <script type="text/javascript" src="js/jquerymask/jquery.mask.min.js"></script>

  <script src="js/rut/jquery.rut.js"></script>


</body>



</html>