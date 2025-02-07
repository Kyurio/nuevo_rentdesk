<div class="content content-page" style="margin-top:67px;">

  <div class="row g-3">

    <?php
    if ($json_menu_sup != null && $json_menu_sup != "") {
      foreach ($json_menu_sup as $menu_superior) {
    ?>

        <?php //var_dump($menu_superior); 
        ?>

        <div class="col-lg-3 col-md-6 col-sm-6">

          <div class="card card-stats">

            <div class="card-body ">
              <div class="row">
                <div class="col-5 col-md-4">
                  <div class="icon-big text-center icon-warning">


                    <?php if (isset($menu_superior['icon']) && !empty($menu_superior['icon'])) : ?>
                      <i class="<?php echo $menu_superior['icon']; ?>" style="color:#ff0000;"></i>
                    <?php elseif ($menu_superior['id'] == 52) : ?>
                      <span style="color: #ff0000;"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                    <?php elseif ($menu_superior['id'] == 53) : ?>
                      <span style="color: #ff0000;"><i class="fa-regular fa-file-lines"></i></span>
                    <?php elseif ($menu_superior['id'] == 65) : ?>
                      <span style="color: #ff0000;"><i class="fa-solid fa-house-fire"></i></span>
                    <?php else : ?>
                      <span style="color: #ff0000;"><i class="fa-solid fa-money-check-dollar"></i></span>
                    <?php endif; ?>

                  </div>


                </div>
                <div class="col-7 col-md-8">
                  <div class="numbers">
                    <a href="#" style="color:#313131; text-decoration:none; font-family: Poppins SemiBold;">
                      <p class="card-title"><a href="<?php echo @$menu_superior['url']; ?>"
                          style="color:#313131; text-decoration:none; font-family: Poppins SemiBold;">
                          <?php echo @$menu_superior['label']; ?></a></p>
                    </a>
                    <p style="font-size:12px;"><?php echo @$menu_superior['descripcion']; ?>
                    </p>
                    <p><a href="<?php echo @$menu_superior['url']; ?>" class="boton-general" style="margin-bottom:20px; margin-top:10px; ">Ver</a></p>

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

  <div class="container" style=" max-width:100% ; width:100% !important;">
    <div class="row">

      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
        <div class="card">
          <div class="card-body">
            <canvas id="myChart"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
        <div class="card">
          <div class="card-body">
            <canvas id="PropiedadesPorLiquidar"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
        <div class="card">
          <div class="card-body">
            <canvas id="PropiedadesPorPagar"></canvas>
          </div>
        </div>
      </div>

    </div>


  </div>

</div>