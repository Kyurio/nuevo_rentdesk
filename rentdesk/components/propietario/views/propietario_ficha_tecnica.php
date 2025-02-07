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
	$(document).ready(function() {
		cargarCuentasBancarias();
		cargarPropiedadesPropietario();
		cargarLiquidaciones();
		cargarMovimientosPropietario();
	});
</script>
<div id="header" class="header-page">
	<!-- <h2 class="mb-3">Ficha Técnica</h2> -->
	<div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
		<ol class="breadcrumb d-flex align-items-center m-0">
			<li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
			<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propietario&view=propietario_list" style="text-decoration: none;color:#66615b">Propietarios</a></li>
			<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page"><?php echo $nombre; ?></li>
		</ol>
	</div>
</div>

<div class="content content-page">

	<div class="row">
		<div class="col-lg-2 p-0" style="display:none;">
			<!-- Nav tabs -->
			<ul class="nav flex-column nav-pills p-0 " id="myTab" role="tablist" aria-orientation="vertical" style="display: flex;gap:1rem">
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link active w-100" id="propietario-ft-informacion-tab" data-bs-toggle="tab" data-bs-target="#informacion" type="button" role="tab" aria-controls="informacion" aria-selected="true">Información</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="propietario-ft-cuentaCorriente-tab" data-bs-toggle="tab" data-bs-target="#cuentaCorriente" type="button" role="tab" aria-controls="cuentaCorriente" aria-selected="false">Cuenta Corriente</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="propietario-ft-propiedades-tab" data-bs-toggle="tab" data-bs-target="#propiedades" type="button" role="tab" aria-controls="propiedades" aria-selected="false">Propiedades</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="propietario-ft-liquidaciones-tab" data-bs-toggle="tab" data-bs-target="#liquidaciones" type="button" role="tab" aria-controls="liquidaciones" aria-selected="false">Liquidaciones</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" onClick="cargarHistorialArriendoList();" id="propietario-ft-historial-tab" data-bs-toggle="tab" data-bs-target="#historial" type="button" role="tab" aria-controls="historial" aria-selected="false">Historial</button>
				</li>
			</ul>
		</div>
		<div class="col-lg-12">

			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane active" id="informacion" role="tabpanel" aria-labelledby="propietario-ft-informacion-tab" tabindex="0">
					<div class="container-fluid">
						<div class="card">
							<div class="card-body">
								<h3>Información</h3>
								<div class="row">
									<?php if ($tipo_persona == "NATURAL") { ?>
										<div class="col-md-4">
											<dt>Nombre</dt>
											<dd><?php echo $nombre; ?></dd>
										</div>
										<div class="col-md-4">
											<dt>Tipo Documento</dt>
											<dd><?php echo $tipo_dni; ?></dd>
										</div>
										<div class="col-md-4">
											<dt>Nro. Documento</dt>
											<dd><?php echo $dni; ?></dd>
										</div>
										<div class="col-md-4">
											<dt>Correo Electrónico</dt>
											<dd><?php echo $correo_electronico; ?></dd>
										</div>
										<div class="col-md-4">
											<dt>Teléfono Fijo</dt>
											<dd><?php if ($telefono_fijo == "") {
													echo "-";
												} else {
													echo $telefono_fijo;
												}  ?></dd>
										</div>
										<div class="col-md-4">
											<dt>Teléfono Celular</dt>
											<dd><?php if ($telefono_movil == "") {
													echo "-";
												} else {
													echo $telefono_movil;
												}  ?></dd>
										</div>
									<?php
									} else if ($tipo_persona == "JURIDICA") {
									?>
										<div class="col-md-4">
											<dt>Razón Social</dt>
											<dd><?php echo $nombre; ?></dd>
										</div>
										<div class="col-md-4">
											<dt>Tipo Documento</dt>
											<dd><?php echo $tipo_dni; ?></dd>
										</div>
										<div class="col-md-4">
											<dt>Nro. Documento</dt>
											<dd><?php echo $dni; ?></dd>
										</div>
										<div class="col-md-4">
											<dt>Correo Electrónico</dt>
											<dd><?php echo $correo_electronico; ?></dd>
										</div>
										<div class="col-md-4">
											<dt>Teléfono Fijo</dt>
											<dd><?php if ($telefono_fijo == "") {
													echo "-";
												} else {
													echo $telefono_fijo;
												}  ?></dd>
										</div>
										<div class="col-md-4">
											<dt>Teléfono Celular</dt>
											<dd><?php if ($telefono_movil == "") {
													echo "-";
												} else {
													echo $telefono_movil;
												}  ?></dd>
										</div>
									<?php
									}
									?>

								</div>
								<div class="row">
									<div class="col-md-6">
										<dl>
											<dt>Dirección</dt>
											<dd><?php echo $direccion; ?></dd>
										</dl>
									</div>
								</div>
								<div class="row">
									<h6 class="text-muted my-4 display-6">Cuentas Bancarias</h6>

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="infoCuentasBancarias" class="table table-striped" cellspacing="0" width="100%">

													<thead>
														<tr>
															<th>Banco</th>
															<th>Tipo Cuenta</th>
															<th>Nro. Cuenta</th>
															<th>Nombre Titular</th>
															<th>RUT Titular</th>
															<th>Correo Electrónico</th>

														</tr>
													</thead>
													<tbody>



													</tbody>

													</thead>

												</table>
											</div>

										</div>
									</div>
								</div>
								<div class="row">
									<h6 class="text-muted my-4 display-6">Comentarios</h6>

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="infoComentarios" class="table table-striped" cellspacing="0" width="100%">

													<!-- <thead>
														<tr>
															<th></th>
															<th></th>
														</tr>
													</thead> -->
													<tbody>

														<?php foreach ($dataTableInfoComentarios as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>
																<!-- <td>
																								<a href="index.php?component=propiedad&view=propiedad_ficha_tecnica" type="button" class="btn btn-info m-0" style="padding: .5rem;" title="Ver Ficha Técnica">
																								<i class="fa-solid fa-magnifying-glass" style="font-size: .75rem;"></i>
																								</a>
																								</td> -->
																<td>
																	<div class="d-flex" style="gap: .5rem;">
																		<!-- <a href="index.php?component=propiedad&view=propiedad" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
																			<i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
																		</a>
																		<button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
																			<i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
																		</button> -->
																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
													</tbody>

													</thead>

												</table>
											</div>
											<div class="mb-3">
												<label for="exampleFormControlTextarea1" class="form-label">Añadir Comentario</label>
												<textarea class="form-control" id="exampleFormControlTextarea1" style="padding:1rem" placeholder="Ingrese un comentario..."></textarea>
												<button type="button" class="btn btn-info" style="padding: .5rem;" title="Eliminar">
													<span>Añadir</span>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="cuentaCorriente" role="tabpanel" aria-labelledby="propietario-ft-cuentaCorriente-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Cuenta Corriente</h3>
								<div class="row">
									<ul style="display:none">
										<li><span>Saldo al día: -$1.827</span></li>
										<li><span>Saldo histórico: -$1.827</span></li>
									</ul>


								</div>
								<div class="row">
									<h4>Movimientos</h4>

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="CcTable" class="table table-striped" cellspacing="0" width="100%">

													<thead>
														<tr>
															<th>Fecha</th>
															<th>Procedencia</th>
															<th>Razón</th>
															<th>Abonos</th>
															<th>Descuentos</th>
															<th>Saldos</th>
														</tr>
													</thead>
													<tbody>


													</tbody>

												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="propiedades" role="tabpanel" aria-labelledby="propietario-ft-propiedades-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Propiedades</h3>

								<div class="row">
									<!-- <h4>Movimientos</h4> -->

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="infoPropiedades" class="table table-striped" cellspacing="0" width="100%">

													<thead>
														<tr>
															<th style="width: 50px">Ficha</th>
															<th>Dirección</th>
															<th>% Participación</th>
														</tr>
													</thead>
													<tbody>

													</tbody>

												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="liquidaciones" role="tabpanel" aria-labelledby="propietario-ft-liquidaciones-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Liquidaciones</h3>
								<!-- <div class="row">
									<ul>
										<li><span>Saldo al día: ...</span></li>
										<li><span>Saldo histórico: ...</span></li>
									</ul>


								</div> -->
								<div class="row">
									<!--
									<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
										<li class="nav-item" role="presentation">
											<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Actuales</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Antiguas</button>
										</li>
									</ul>
																	-->
									<!-- <h4>Movimientos</h4> -->
									<div class="tab-content" id="nav-tabContent">
										<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="nav-home-tab">
											<div class="card">
												<div class="card-body">
													<div class="table-responsive overflow-auto">

														<table id="liProp1" class="table table-striped" cellspacing="0" width="100%">
															<thead>
																<tr>
																	<th>Fecha Liquidacion</th>
																	<th>Ficha Arriendo </th>
																	<th>Comisión</th>
																	<th>IVA</th>
																	<th>Abonos</th>
																	<th>Descuentos</th>
																	<th>Total</th>
																</tr>
															</thead>
															<tbody>

															</tbody>


														</table>
													</div>
												</div>
											</div>
										</div>
										<!--
										<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
											<div class="card">
												<div class="card-body">
													<div class="table-responsive overflow-auto">

														<table id="retenciones" class="table table-striped" cellspacing="0" width="100%">
															<thead>
																<tr>
																	<th>ID</th>
																	<th>Fecha</th>
																	<th>Propiedad</th>
																	<th>Valor Arriendo</th>
																	<th>Pago Recibido</th>
																	<th>Comisión</th>
																	<th>IVA</th>
																	<th>Abonos</th>
																	<th>Descuentos</th>
																	<th>Total</th>
																</tr>
															</thead>
															<tbody>

																
																	
																	</tr>
															</tbody>

															<tfoot>
																<tr>
																	<td></td>
																	<td></td>
																	<td><strong>Total</strong></td>
																	<td></td>
																	<td>$380.000</td>
																	<td>$15.200</td>
																	<td>$2.888</td>
																	<td>$0</td>
																	<td>$346.000</td>
																	<td>$15.912</td>

																</tr>
															</tfoot>
														</table>
													</div>
												</div>
											</div>
										</div>

																	-->
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="historial" role="tabpanel" aria-labelledby="propietario-ft-historial-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Historial</h3>

								<div class="row">
									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="historial-table" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Responsable</th>
															<th>Acción</th>
															<th>Recurso</th>
															<th>ID</th>
															<th>Cambios</th>
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
				</div>
			</div>
		</div>
	</div>

</div>