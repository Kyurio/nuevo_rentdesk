<script src="js/region_ciudad_comuna.js"></script>


<script>
	$(function() {
		$('#datetimepicker1').datetimepicker({
			format: "DD-MM-YYYY",
			defaultDate: moment("<?php echo  date('d-m-Y'); ?>", "DD-MM-YYYY")
		});
	});

	const triggerTabList = document.querySelectorAll('#myTab button')
	triggerTabList.forEach(triggerEl => {
		const tabTrigger = new bootstrap.Tab(triggerEl)

		triggerEl.addEventListener('click', event => {
			event.preventDefault()
			tabTrigger.show()
		})
	})
</script>
<div id="header" class="header-page">
	<!-- <h2 class="mb-3">Ficha Técnica</h2> -->
	<div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
		<ol class="breadcrumb d-flex align-items-center m-0">
			<li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
			<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propiedad&view=propiedad_list" style="text-decoration: none;color:#66615b">Propiedades</a></li>
			<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Propiedad X</li>
		</ol>
	</div>
</div>

<div class="content content-page" >

	<div class="row">
		<div class="col-lg-2 p-0">
			<!-- Nav tabs -->
			<ul class="nav flex-column nav-pills p-0" id="myTab" role="tablist" aria-orientation="vertical" style="display: flex;gap:1rem">
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link active w-100" id="informacion-tab" data-bs-toggle="tab" data-bs-target="#informacion" type="button" role="tab" aria-controls="informacion" aria-selected="true">Información</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="cuentaCorriente-tab" data-bs-toggle="tab" data-bs-target="#cuentaCorriente" type="button" role="tab" aria-controls="cuentaCorriente" aria-selected="false">Cuenta Corriente</button>
				</li>
				<!-- <li class="nav-item" role="presentation">
				<button class="d-flex nav-link w-100" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="false">Messages</button>
			</li> -->
				<!-- <li class="nav-item" role="presentation">
				<button class="d-flex nav-link w-100" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Settings</button>
			</li> -->
			</ul>
		</div>
		<div class="col-lg-10">

			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane active" id="informacion" role="tabpanel" aria-labelledby="informacion-tab" tabindex="0">
					<div class="container-fluid">
						<div class="card">
							<div class="card-body">
								<h3>Información</h3>
								<div class="row">
									<div class="col-md-6">
										<dl>
											<dt>Tipo Propiedad</dt>
											<dd>...</dd>
											<dt>Estado</dt>
											<dd>...</dd>
											<dt>Oficina</dt>
											<dd>...</dd>
											<dt>Fecha Ingreso</dt>
											<dd>...</dd>
										</dl>
									</div>
									<div class="col-md-6">
										<dl>
											<dt>Dirección</dt>
											<dd>...</dd>
											<dt>Complemento</dt>
											<dd>...</dd>
										</dl>
									</div>
									<hr>
									<div class="col-md-12">

										<dl style="display: grid;
												grid-template-rows: auto auto;
												width: 100%;
												grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
											<dt>M2</dt>
											<dd>...</dd>
											<dt>Edificado</dt>
											<dd>...</dd>
											<dt>Dormitorios</dt>
											<dd>...</dd>
											<dt>Dorm. Servicio</dt>
											<dd>...</dd>
											<dt>Baños</dt>
											<dd>...</dd>
											<dt>Baños Visita</dt>
											<dd>...</dd>
											<dt>Estacionamiento</dt>
											<dd>...</dd>
											<dt>Bodegas</dt>
											<dd>...</dd>
											<dt>Logia</dt>
											<dd>...</dd>
											<dt>Piscina</dt>
											<dd>...</dd>
										</dl>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="cuentaCorriente" role="tabpanel" aria-labelledby="cuentaCorriente-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Cuenta Corriente</h3>
								<div class="row">
									<ul>
										<li><span>Saldo al día: ...</span></li>
										<li><span>Saldo histórico: ...</span></li>
									</ul>


								</div>
								<div class="row">
									<h4>Movimientos</h4>

									<div class="card">
										<div class="card-body">
											<table id="tabla" class="display" cellspacing="0" width="100%">

												<thead>
													<tr>
														<th>Fecha</th>
														<th>Razón</th>
														<th>Abonos</th>
														<th>Descuentos</th>
														<th>Saldos</th>
													</tr>
													<tr>
														<td>-</td>
														<td>-</td>
														<td>-</td>
														<td>-</td>
														<td>-</td>
													</tr>
												</thead>

											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab" tabindex="0">
				<div class="container-fluid">

					<div class="card">
						<div class="card-body">
							<h3>DENTRO DE HOME</h3>

						</div>
					</div>
				</div>
			</div> -->
				<!-- <div class="tab-pane" id="settings" role="tabpanel" aria-labelledby="settings-tab" tabindex="0">
				<div class="container-fluid">

					<div class="card">
						<div class="card-body">
							<h3>DENTRO DE HOME</h3>

						</div>
					</div>
				</div>
			</div> -->
			</div>
		</div>
	</div>

</div>