    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<div id="header" class="header-page">
	<div>
		<!-- <h2 class="mb-3">Propietario</h2> -->
		<div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
			<ol class="breadcrumb d-flex align-items-center m-0">
				<li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
				<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propietario&view=propietario_list" style="text-decoration: none;color:#66615b">Reportes</a></li>
				<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Estadísticas</li>
			</ol>
		</div>
	</div>
</div>

	<div class="content content-page">

        <h2>Estadísticas <?php echo $nombre_repporte; ?></h2>
        <div class="row g-3">
             <form method="POST" action="">
				<div class="row g-3">
					<div class="col-md-3">
                        <input name="fechaInicio" id="fechaInicio" class="form-control" type="date" value="<?php if(isset($fecha_inicio)){echo $fecha_inicio;} ?>" onchange="actualizaFechaInicio()" required>
                    </div>
                    <div class="col-md-3">
                        <input name="fechaTermino" id="fechaTermino" class="form-control" type="date" value="<?php if(isset($fecha_termino)){echo $fecha_termino;} ?>" onchange="actualizaFechaTermino()" required>

                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-danger" style="margin-top: 0;"> Filtrar </button>
                    </div>
                </div>
                </form>
                <div class="row g-3">
                <form target="_blank" action ="./components/reporte/models/estadisticas_excel.php" method="POST">
                    <div class="col-md-3">
                        <input type="hidden" name="fechaInicioHidden" id="fechaInicioHidden" value="<?php if(isset($fecha_inicio)){echo $fecha_inicio;} ?>" >
                        <input type="hidden" name="fechaTerminoHidden" id="fechaTerminoHidden" value="<?php if(isset($fecha_termino)){echo $fecha_termino;} ?>" >
                        <button type="submit" class="btn btn-info"  style="margin-top: 0;" > Descargar Informacion  </button>
                    </div>
                </form>
                </div>
            
        </div>

		<div class="row g-3">
            <div class="col-md-8">
	    	    <canvas id="myChart" width="400" height="200"></canvas>
            </div>
        </div>
	</div>	

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');

        // Datos
        const data = {
            
            labels: <?php   echo $meses_str ; ?>,
            datasets: [
                {
                    label: 'Administracion',
                    data: <?php echo $administracion_str; ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Arriendo',
                    data: <?php echo $arriendo_str; ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        };

        // Configuración del gráfico
        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        };

        // Crear el gráfico
        const myChart = new Chart(ctx, config);

    </script>