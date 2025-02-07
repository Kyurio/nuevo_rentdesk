<div class="content content-page">
  <div id="header" style="margin-top:67px">
  </div>

  <?php include("select-subsidiaria.php"); ?>

  <?php
  if (isset($json_menu_sup)) {
    foreach ($json_menu_sup as $menu_superior) {
  ?>

      <div class="col-lg-3 col-md-6 col-sm-6">

        <div class="card card-stats">

          <div class="card-body ">
            <div class="row">
              <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                  <?php if (isset($menu_superior->icono) && !empty($menu_superior->icono)) : ?>
                    <i class="<?php echo $menu_superior->icono; ?>" style="color:#ff0000;"></i>
                  <?php elseif ($menu_superior->id_menu == 19) : ?>
                    <span style="color: #ff0000;">252</span>
                  <?php elseif ($menu_superior->id_menu == 20) : ?>
                    <span style="color: #ff0000;">43</span>
                  <?php else : ?>
                    <span style="color: #ff0000;">1</span>
                  <?php endif; ?>

                </div>


              </div>
              <div class="col-7 col-md-8">
                <div class="numbers">
                  <a href="#" style="color:#313131; text-decoration:none; font-family: Poppins SemiBold;">
                    <p class="card-title"><?php echo @$menu_superior->nombre; ?></p>
                  </a>
                  <p style="font-size:12px;"><?php
                                              if ($menu_superior->nombre == "Propiedades") {
                                                echo "Consulta el listado, busca una propiedad, crea una propiedad nueva, detalles financieros.";
                                              } else {
                                                echo "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam risus ipsum, laoreet.";
                                              }
                                              ?>
                  </p>
                  <p><a href="<?php echo @$menu_superior->url; ?>" class="boton-general" style="margin-bottom:20px; margin-top:10px; ">Ver</a></p>

                </div>
              </div>
            </div>
          </div>

        </div>

      </div>

  <?php
    }
  }
  ?>

</div>










<!-- Inicio Gráficos ********************************************************************************************************************************************************************** -->

<div class="container" style=" max-width:100% ; width:100% !important;">
<h4 style="padding-left:15px;">Gráficos Resumen</h4>
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

<br> &nbsp;<br> &nbsp;<br> &nbsp;<br> &nbsp;<br> &nbsp;<br> &nbsp;





</div>
