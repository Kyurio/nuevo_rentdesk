<?php
$config = new Config;
$peso_archivo = $config->maxSizeMB;
?>

<script src="js/region_ciudad_comuna.js"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
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
			<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=arriendo&view=arriendo_list" style="text-decoration: none;color:#66615b">Arriendos</a></li>
			<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">#<?php echo $id_ficha; ?></li>
		</ol>
	</div>
</div>

<div class="content content-page">

	<div class="row">
		<div class="col-lg-0 p-0" style="display:none;">
			<!-- Nav tabs -->
			<ul class="nav flex-column nav-pills p-0" id="myTab" role="tablist" aria-orientation="vertical" style="display: flex;gap:1rem">
				<li class="nav-item" role="presentation">
					<button onclick="cargarInfoArriendo()" class="d-flex nav-link active w-100" id="arriendo-ft-informacion-tab" data-bs-toggle="tab" data-bs-target="#informacion" type="button" role="tab" aria-controls="informacion" aria-selected="true">Información</button>
				</li>
				<li class="nav-item" role="presentation">
					<button onclick="cargarInfoCuentaCorriente()" class="d-flex nav-link w-100" id="arriendo-ft-cuentaCorriente-tab" data-bs-toggle="tab" data-bs-target="#cuentaCorriente" type="button" role="tab" aria-controls="cuentaCorriente" aria-selected="false">Cuenta Corriente</button>
				</li>
				<li class="nav-item" role="presentation">
					<button onclick="cargarCheques()" class="d-flex nav-link w-100" id="arriendo-ft-cheques-tab" data-bs-toggle="tab" data-bs-target="#cheques" type="button" role="tab" aria-controls="cheques" aria-selected="false">Cheques</button>
				</li>
				<!-- <li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="arriendo-ft-cobros-tab" data-bs-toggle="tab" data-bs-target="#cobros" type="button" role="tab" aria-controls="cobros" aria-selected="false">Cobros</button>
				</li> -->
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="arriendo-ft-garantia-tab" data-bs-toggle="tab" data-bs-target="#garantia" type="button" role="tab" aria-controls="garantia" aria-selected="false">Garantía</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="arriendo-ft-reajuste-tab" data-bs-toggle="tab" data-bs-target="#reajuste" type="button" role="tab" aria-controls="reajuste" aria-selected="false">Reajuste</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="arriendo-ft-recordatorios-tab" data-bs-toggle="tab" data-bs-target="#recordatorios" type="button" role="tab" aria-controls="recordatorios" aria-selected="false">Recordatorios</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" onClick="cargarHistorialArriendoList();" id="arriendo-ft-historial-tab" data-bs-toggle="tab" data-bs-target="#historial" type="button" role="tab" aria-controls="historial" aria-selected="false">Historial</button>
				</li>
			</ul>
		</div>


		<div class="col-lg-12">

			<div class="tab-content">

				<div class="tab-pane active" id="informacion" role="tabpanel" aria-labelledby="arriendo-ft-informacion-tab" tabindex="0">
					<div class="container-fluid">
						<div class="card">
							<div class="card-body">
								<h3>Información</h3>
								<div class="row">
									<div class="col-md-12">

										<fieldset class="form-group border p-3" id="section-info-arriendo">
											<div class="container">
												<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3">
													<div class="col">
														<label class="fw-bold">Nro. Ficha Técnica</label><br>
														<label id="ctaBancNombreTitular">#<?php echo $id_ficha; ?></label>
													</div>
													<div class="col">
														<label class="fw-bold">Propiedad</label><br>
														<label id="ctaBancNombreTitular"><a href="<?php echo "index.php?component=propiedad&view=propiedad_ficha_tecnica&token=" . $propiedad_token; ?>" class="link-info"><?php echo $propiedad_direccion; ?></a></label>
													</div>
													<div class="col">
														<label class="fw-bold">Propietario</label><br>
														<label id="ctaBancNombreTitular"><a href="<?php echo "index.php?component=propietario&view=propietario_ficha_tecnica&token=" . $propiedad_token_propietario; ?>" class="link-info"><?php echo $propiedad_nombre_propietario; ?></a></label>
													</div>
													<?php if (isset($propiedad_token_representante) && !empty($propiedad_token_representante)) : ?>

														<div class="col">
															<label class="fw-bold">Representante Legal</label><br>
															<label id="ctaBancNombreTitular"><a href="<?php echo "index.php?component=propietario&view=propietario_ficha_tecnica&token=" . $propiedad_token_representante; ?>" class="link-info"><?php echo $propiedad_nombre_representante; ?></a></label>
														</div>
													<?php endif; ?>

													<!-- <div class="col fw-bold"><label>Codeudor</label><br> <label id="ctaBancNombreTitular">#<?php echo $id_ficha; ?></label></div> -->
												</div>
											</div>
										</fieldset>

									</div>


								</div>

								<div class="row">
									<div class="col-md-12">
										<fieldset class="form-group border p-3" id="section-info-arriendo">
											<div class="container">
												<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3">
													<div class="col">
														<b><label class="fw-bold">Fecha Inicio:</label></b>
														<label id="ctaBancNombreTitular" class="text-secondary"><?php echo $fecha_inicio; ?></label>
													</div>
													<div class="col">
														<b><label class="fw-bold">Fecha Termino Real:</label></b>
														<label id="ctaBancNombreTitular" class="text-secondary"><?php echo $fecha_termino_real; ?></label>
													</div>
													<div class="col">
														<b><label class="fw-bold">Duración Contrato:</label></b>
														<label id="ctaBancNombreTitular" class="text-secondary"><?php echo $duracion_contrato_meses; ?></label>
													</div>
													<div class="col">
														<b><label class="fw-bold">Precio:</label></b>
														<label id="ctaBancNombreTitular" class="text-secondary"><?php echo $precio ?></label>
													</div>
													<div class="col">
														<b><label class="fw-bold">Moneda:</label></b>
														<label id="ctaBancNombreTitular" class="text-secondary"><?php echo $moneda_precio; ?></label>
													</div>
													<div class="col">
														<b><label class="fw-bold">Monto Garantía:</label></b>
														<label id="ctaBancNombreTitular" class="text-secondary"><?php echo @number_format($monto_garantia, 0, ',', '.'); ?></label>
													</div>
													<div class="col">
														<b><label class="fw-bold">Pago de garantía a propietario:</label></b>
														<label id="ctaBancNombreTitular" class="text-secondary"><?php echo $pago_garantia_propietario ? "SI" : "NO"; ?></label>
													</div>
													<div class="col">
														<b><label class="fw-bold">Cobrar mes Calendario:</label></b>
														<label id="ctaBancNombreTitular" class="text-secondary"><?php echo $cobro_mes_calendario ? "SI" : "NO"; ?></label>
													</div>
													<div class="col">
														<b><label class="fw-bold">Días para Pago desde último cobro:</label></b>
														<label id="ctaBancNombreTitular" class="text-secondary"><?php echo $dias_pago_gracia; ?></label>
													</div>
													<div class="col">
														<b><label class="fw-bold">Monto Multa por atraso:</label></b>
														<label id="ctaBancNombreTitular" class="text-secondary"><?php echo $monto_multa_atraso; ?></label>
													</div>
													<div class="col">
														<b><label class="fw-bold">Moneda Multa:</label></b>
														<label id="ctaBancNombreTitular" class="text-secondary"><?php echo $moneda_multa; ?></label>
													</div>

												</div>
											</div>
										</fieldset>
									</div>
								</div>
							</div>
						</div>

						<fieldset class="form-group border-0 p-3" id="section-info-arrendatarios">
							<h6 class="text-muted my-4 display-6">Arrendatario</h6>

							<div class="card">
								<div class="card-body">
									<div class="table-responsive overflow-auto">

										<table id="info-arrendatarios" class="table table-striped" cellspacing="0" width="100%">

											<thead>
												<tr>
													<th>Rut</th>
													<th>Nombre</th>
													<th>Correo Electrónico</th>
													<th>Teléfono Celular</th>
													<th>Teléfono Fijo</th>
												</tr>
											</thead>
											<tbody>



											</tbody>

											</thead>

										</table>
									</div>

								</div>
							</div>
						</fieldset>

						<fieldset class="form-group border-0 p-3" id="section-info-comentarios">
							<h6 class="text-muted my-4 display-6">Comentarios</h6>
							<!-- <button form="comentario_formulario" type="button" onclick="guardarInfoComentario();" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Añadir" data-bs-toggle="modal" data-bs-target="#modalServicioIngreso"> -->
							<button form="comentario_formulario" type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Añadir" data-bs-toggle="modal" data-bs-target="#modalInfoComentarioIngreso">
								<span>Agregar Comentario</span>
							</button>
							<div class="card">
								<div class="card-body">
									<div class="table-responsive overflow-auto">

										<table id="info-comentarios" class="table table-striped" cellspacing="0" width="100%">

											<thead>
												<tr>
													<th>Comentario</th>
													<th>Fecha Creación</th>
													<th>Fecha Modificación</th>
													<th>Usuario Modificación</th>
												</tr>
											</thead>
											<tbody>


											</tbody>


										</table>
									</div>

									<div class="mb-3">

									</div>
								</div>
							</div>
						</fieldset>

					</div>
				</div>

				<div class="tab-pane" id="retenciones" role="tabpanel" aria-labelledby="retenciones-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Retenciones</h3>
								<div class="row">

								</div>
								<div class="row">
									<h6 class="text-muted my-4 display-6">Movimientos</h6>

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="retenciones" class="table table-striped" cellspacing="0" width="100%">

													<thead>
														<tr>
															<th>Fecha</th>
															<th>Razón</th>
															<th>Abonos</th>
															<th>Descuentos</th>
															<th>Saldo</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>

														<?php foreach ($dataTableRetenciones as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>

																<div class="d-flex" style="gap: .5rem;">

																</div>
																</td>
															</tr>
														<?php endforeach; ?>
													</tbody>

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

				<div class="tab-pane" id="cuentaCorriente" role="tabpanel" aria-labelledby="arriendo-ft-cuentaCorriente-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Cuenta Corriente</h3>
								<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">

									<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#modalCuentaCorrienteIngresoCargos">
										<span>Ingresar Cargo</span>
									</button>

									<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#modalCuentaCorrienteIngresoAbonos">
										<span>Ingresar Abono</span>
									</button>

									<!-- <button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#modalCuentaCorrienteIngresoCobroExtra">
										<span>Ingresar Cobro extra</span>
									</button>

									<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#modalCTR">
										<span>Cargo a la renta CTR</span>
									</button> -->

								</div>

								<div class="row">
									<h6 class="text-muted my-4 display-6">Movimientos</h6>

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="cc-movimientos" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Razón</th>
															<th>Abonos</th>
															<th>Cobros</th>
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

				<div class="tab-pane" id="cheques" role="tabpanel" aria-labelledby="arriendo-ft-cheques-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Cheques</h3>

								<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
									<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Agregar Cheque" data-bs-toggle="modal" data-bs-target="#modalChequesIngreso">
										<span>Agregar Cheque</span>
									</button>

								</div>

								<div class="row">

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="cheques" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha Cobro</th>
															<th>Razón</th>
															<th>Monto</th>
															<th>Nro. Documento</th>
															<th>Banco</th>
															<th>Girador</th>
															<th>Depositado</th>
															<th>Cobrado</th>
															<th>Acciones</th>
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

				<div class="tab-pane" id="cobros" role="tabpanel" aria-labelledby="arriendo-ft-cobros-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Cobros</h3>

								<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
									<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Cobrar" data-bs-toggle="modal" data-bs-target="#modalCobrosCobrar">
										<span>Cobrar</span>
									</button>

								</div>
								<div class="row">
									<h6 class="text-muted my-4 display-6">Cobros Generados</h6>

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="cobros" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Saldo Anterior</th>
															<th>Monto Cobro</th>
															<th>Saldo Final</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>

														<?php foreach ($dataTableCobros as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>
																<td>
																	<div class="d-flex" style="gap: .5rem;">

																		<button type="button" class="btn btn-info m-0 d-flex" style="padding: .5rem;" title="Enviar por Correo">
																			<i class="fa-regular fa-envelope" style="font-size: .75rem;"></i>
																		</button>
																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
													</tbody>

												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<h6 class="text-muted my-4 display-6">Pagos Comisión de Arriendo</h6>
									<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
										<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar" data-bs-toggle="modal" data-bs-target="#modalCobrosIngresoPagoComisionArriendo">
											<span>Ingresar</span>
										</button>

									</div>
									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="infoArrendatario" class="table table-striped" cellspacing="0" width="100%">

													<thead>
														<tr>
															<th>Fecha</th>
															<th>Glosa</th>
															<th>Monto</th>
															<th>Acciones</th>
														</tr>
													</thead>

													<?php foreach (@$dataTableInfoArrendatario as $row) : ?>
														<tr>
															<?php foreach ($row as $key => $cell) : ?>

																<td><?php echo $cell; ?></td>

															<?php endforeach; ?>

															<td>
																<div class="d-flex" style="gap: .5rem;">

																</div>
															</td>
														</tr>
													<?php endforeach; ?>

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

				<div class="tab-pane" id="garantia" role="tabpanel" aria-labelledby="arriendo-ft-garantia-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Garantía</h3>
								<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">

									<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Abono"
										data-bs-toggle="modal" data-bs-target="#modalGarantiaIngresoDescuento">
										<span>Ingresar Descuento</span>
									</button>

									<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Descuento"
										data-bs-toggle="modal" data-bs-target="#modalGarantiaIngresoAbono" onClick="resetFormGarantiaIngresoDescuento();">
										<span>Ingresar Abono</span>
									</button>

									<button type="button" class="btn btn-default btn-outline-primary btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Descargar Liquidación">
										<span>Descargar Liquidación</span>
									</button>

									<a target="_blank" href="components/arriendo/models/liquidar_garantia_fpdf.php?token=<?php echo $token ?>" type="button" class="btn btn-default btn-outline-danger btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Cerrar Garantía">
										<span>Cerrar Garantía</span>
									</a>

								</div>
								<div class="row">
									<ul>
									</ul>
								</div>
								<div class="row">
									<h6 class="text-muted my-4 display-6">Movimientos</h6>

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="garantia" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Razón</th>
															<th>Tipo Moneda</th>
															<th>Monto</th>
															<th>Pagado</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>

														<?php foreach ($dataTableGarantia as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>
																<td>
																	<div class="d-flex" style="gap: .5rem;">
																		<button type="button"
																			class="btn btn-info m-0"
																			style="padding: .5rem; white-space: nowrap;"
																			title="Ingresar Abono"
																			data-bs-toggle="modal"
																			data-bs-target="#modalEditarGarantiaIngresoAbono"
																			onclick="EditarGarantia('<?php echo $id; ?>', '<?php echo $fecha; ?>', '<?php echo $razon; ?>', '<?php echo $monto; ?>', '<?php echo $pagado; ?>', '<?php echo $notificado ?>')">
																			<i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
																		</button>


																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
													</tbody>



												</table>

											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<h6 class="text-muted my-4 display-6">Comentarios y Documentos</h6>
									<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
										<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar" data-bs-toggle="modal" data-bs-target="#modalGarantiaIngresoComentarioDocumento">
											<span>Ingresar comentario o documento</span>
										</button>

									</div>
									<div class="card">
										<div class="card-body">
											<h6 class="text-muted my-4 display-6">Documentos</h6>

											<div class="table-responsive overflow-auto">

												<table id="garantiaDocumentos" class="table table-striped" cellspacing="0" width="100%">


													<tbody>

														<?php foreach ($dataTableGarantiaDocumentos as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>

																<td>
																	<div class="d-flex" style="gap: .5rem;">

																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
													</tbody>

													</thead>

												</table>
											</div>

										</div>
									</div>
									<div class="card">
										<div class="card-body">
											<h6 class="text-muted my-4 display-6">Comentarios</h6>

											<div class="table-responsive overflow-auto">

												<table id="garantiaComentarios" class="table table-striped" cellspacing="0" width="100%">

													<tbody>

														<?php foreach ($dataTableGarantiaComentarios as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>

																<td>
																	<div class="d-flex" style="gap: .5rem;">

																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
													</tbody>

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

				<!-- Modal Garantía - Ingresar Abono-->
				<div class="modal fade" id="modalGarantiaIngresoAbono" tabindex="-1"
					aria-labelledby="modalGarantiaIngresoAbonoLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">

							<div class="modal-header">
								<h5 class="modal-title" id="modalGarantiaIngresoAbonoLabel">Ingresar Abono</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>

							<div class="modal-body">

								<!-- Modal Garantía - Ingresar Abono-->
								<form id="formModalGarantiaIngresar" method="post" enctype="multipart/form-data">

									<div class="row">
										<div class="col mb-3">
											<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Razón del Abono</label>
											<input type="text" class="form-control" id="modalGarantiaRazonAbono" name="modalGarantiaRazonAbono" placeholder="Ingrese Razón" maxlength="160">
										</div>
									</div>

									<div class="row">
										<div class="col-md-6 mb-3">
											<label for="modalMonto" class="form-label"><span class="obligatorio">*</span> Monto</label>
											<input type="text"
												class="form-control"
												id="modalMontoGarantiaAbono"
												name="modalMontoGarantiaAbono"
												placeholder="Ingrese Monto">
										</div>

										<script>
											$("#modalMontoGarantiaAbono").keyup(function(event) {
												if (event.which >= 37 && event.which <= 40) {
													event.preventDefault();
												}
												$(this).val(function(index, value) {
													return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
												});
											});
										</script>

										<div class="col-md-6 mb-3">
											<label for="modalMoneda" class="form-label"><span class="obligatorio">*</span> Moneda</label>
											<input type="text" class="form-control" id="modalMonedaGarantiaAbono" name="modalMonedaGarantiaAbono" readonly placeholder="Pesos" value="Pesos">
										</div>

									</div>

									<div class="row">
										<div class="col-md-12">
											<strong>Documentos</strong><br>

											<input id="archivoDG" name="archivoDG" type="file" onchange="validaArchivo(this,<?php echo $peso_archivo; ?>);" class="btn btn-file  opacity-100 position-relative h-auto w-100" />

											<input type="hidden" name="archivo_bdAbono" id="archivo_bdAbono" value="">

											<?php echo @$archivo; ?>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6 mb-3">
											<label for="modalMonto" class="form-label"><span class="obligatorio">*</span> Pagado</label>
											<select id="modalGarantiaPagadoAbono" name="modalGarantiaPagadoAbono" class="select-form form-control">
												<option value="">Seleccionar</option>
												<option value="No">No</option>
												<option value="Si">Si</option>
											</select>
										</div>
										<div class="col-md-6 mb-3">
											<label for="modalGarantiaFecha" class="form-label"><span class="obligatorio">*</span> Fecha</label>
											<input type="date" class="form-control" id="modalGarantiaFechaAbono" name="modalGarantiaFechaAbono" value="">
										</div>
									</div>

									<div class="modal-footer">
										<div class="error-formulario error-descuento-garantia" style="width:100%;"></div>
										<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
										<button type="button" class="btn btn-primary"
											onClick="guardarAbono('<?php echo @$token; ?>');">Guardar</button>

									</div>

								</form>

							</div>
						</div>
					</div>
				</div>

				<!-- Modal Garantía - Ingresar Descuento-->
				<div class="modal fade" id="modalGarantiaIngresoDescuento" tabindex="-1" aria-labelledby="modalGarantiaIngresoDescuentoLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="modalGarantiaIngresoAbonoLabel">Ingresar Descuento Garantía</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">

								<form id="formModalGarantiaDescuento" name="formModalGarantiaDescuento" action="" method="post" enctype="multipart/form-data">

									<div class="row">
										<div class="col mb-3">
											<label for="razonDescuento" class="form-label"><span class="obligatorio">*</span> Razón del Descuento</label>
											<input type="text" class="form-control" id="razonDescuento" name="razonDescuento">
										</div>
									</div>

									<div class="row">
										<div class="col-md-6 mb-3">
											<label for="montoDescuento" class="form-label"><span class="obligatorio">*</span> Monto</label>
											<input type="text" class="form-control" id="montoDescuento" name="montoDescuento" placeholder="Ingrese Monto">
										</div>
										<script>
											$("#montoDescuento").keyup(function(event) {
												if (event.which >= 37 && event.which <= 40) {
													event.preventDefault();
												}
												$(this).val(function(index, value) {
													return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
												});
											});
										</script>

										<div class="col-md-6 mb-3">
											<label for="monedaDescuento" class="form-label"><span class="obligatorio">*</span> Moneda</label>
											<input type="text" class="form-control" id="monedaDescuento" name="monedaDescuento" readonly placeholder="Pesos" value="Pesos">
										</div>
									</div>

									<div class="row">
										<div class="col-md-12 mb-3">
											<label for="pagadoDescuento" class="form-label"><span class="obligatorio">*</span> Pagado</label>
											<select id="pagadoDescuento" name="pagadoDescuento" class="select-form form-control">
												<option value="">Seleccionar</option>
												<option value="No">No</option>
												<option value="Si">Si</option>
											</select>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12 mb-3">
											<label for="fechaDescuento" class="form-label"><span class="obligatorio">*</span> Fecha</label>
											<input type="date" class="form-control" id="fechaDescuento" name="fechaDescuento">
										</div>
									</div>

									<input type="hidden" name="tipo_movimiento" value="1"> <!-- Tipo de movimiento para descuento -->

								</form>


							</div>
							<div class="modal-footer">
								<div class="error-formulario error-descuento-garantia" style="width:100%;"></div>
								<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
								<button type="button" class="btn btn-primary"
									onClick="guardarDescuento('<?php echo @$token; ?>');">Guardar</button>

							</div>
						</div>
					</div>
				</div>

				<!-- Modal editar Garantía -->
				<div class="modal fade" id="modalEditarGarantiaIngresoAbono" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="modalLabel">Editar Garantía</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>

							<div class="modal-body">

								<form id="formularioEditarGarantia" name="formularioEditarGarantia" method="post" enctype="multipart/form-data">

									<input type="hidden" name="id_garantia" id="id_garantia">

									<div class="row">
										<div class="col mb-3">
											<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Razón</label>
											<input type="text" class="form-control" id="razonEditar" name="razonEditar" placeholder="Ingrese Razón">
										</div>
									</div>

									<div class="row">
										<div class="col-md-8 mb-3">
											<label for="modalMonto" class="form-label"><span class="obligatorio">*</span> Monto</label>
											<input type="text" class="form-control" id="montoEditar" name="montoEditar" placeholder="Ingrese Monto">
										</div>

										<script>
											$("#montoEditar").keyup(function(event) {
												if (event.which >= 37 && event.which <= 40) {
													event.preventDefault();
												}
												$(this).val(function(index, value) {
													return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
												});
											});
										</script>

										<div class="col-md-4 mb-3">
											<label for="modalMoneda" class="form-label"><span class="obligatorio">*</span> Moneda</label>
											<input type="text" class="form-control" id="monedaEditar" name="monedaEditar" placeholder="Pesos" disabled>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">
											<strong>Documentos</strong><br>

											<input id="archivo" name="archivo" type="file" onchange="validaArchivo(this);" class="btn btn-file  opacity-100 position-relative h-auto w-100" />
											<input type="hidden" name="archivo_bd" id="archivo_bd" name="archivo_bd" value="<?php echo htmlspecialchars(@$existe_archivo); ?>">

											<?php echo @$archivo; ?>
										</div>
									</div>

									<div class="row">
										<div class="col-md-4 mb-3">
											<label for="modalMoneda" class="form-label"><span class="obligatorio">*</span> Fecha</label>
											<input type="date" class="form-control" id="fechaEditar" name="fechaEditar" placeholder="20/02/2024">
										</div>
									</div>

								</form>

							</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
								<button type="button" class="btn btn-primary" onclick="ActualizarGarantia()">Guardar</button>
							</div>

						</div>
					</div>
				</div>

				<div class="tab-pane" id="liquidacionesCoPropietarios" role="tabpanel" aria-labelledby="liquidacionesCoPropietarios-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Liquidaciones co-propietarios</h3>

								<div class="row">

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="liqCoPropietarios" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Co-Propietario</th>
															<th>Monto Total a Pagar</th>
															<th>Porcentaje</th>
															<th>Monto a Pagar</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>

														<?php foreach ($dataTableLiqCoPropietarios as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>
																<td>
																	<div class="d-flex" style="gap: .5rem;">

																		<a href="https://gladius-control-prop.s3.sa-east-1.amazonaws.com/property/106418/co_ownership_liquidations/liquidaci_n-copropietario-60011.pdf?X-Amz-Expires=604800&X-Amz-Date=20240220T123851Z&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAUDM5QTO3L5F4JLQF%2F20240220%2Fsa-east-1%2Fs3%2Faws4_request&X-Amz-SignedHeaders=host&X-Amz-Signature=4dc0315b2556a3ccac04702af4cb9196272c56a7638bedf402cb6a49fe887d6a" target="_blank" type="button" class="btn btn-info m-0" style="padding: .5rem;" title="Ver liquidación">
																			<i class="fa-regular fa-file-pdf" style="font-size: .75rem;"></i>
																		</a>
																		<button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
																			<i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
																		</button>
																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
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

				<div class="tab-pane" id="notasDeCredito" role="tabpanel" aria-labelledby="notasDeCredito-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Notas de Crédito</h3>

								<div class="row">

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="notasCredito" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Folio Factura</th>
															<th>Monto</th>
															<th>Glosa</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>

														<?php foreach ($dataTableNotasDeCredito as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>
																<td>
																	<div class="d-flex" style="gap: .5rem;">

																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
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

				<div class="tab-pane" id="reajuste" role="tabpanel" aria-labelledby="arriendo-ft-reajuste-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Reajuste</h3>

								<div class="row">
									<div class="col-md-12">
										<dl>
											<dt>Tipo Reajuste</dt>
											<dd>IPC</dd>
											<dt>Permite Reajuste Negativo</dt>
											<dd>NO</dd>
											<dt>Meses Reajuste</dt>
											<dd>Enero, Julio</dd>
										</dl>
									</div>
								</div>
								<div class="row">
									<h6 class="text-muted my-4 display-6">Reajustes efectuados</h6>

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="notasCredito" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha</th>
															<th>Precio Original</th>
															<th>Precio Reaustado</th>
															<th>Tasa Reajuste</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>

														<?php foreach ($dataTableReajuste as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>
																<td>
																	<div class="d-flex" style="gap: .5rem;">

																		<button type="button" class="btn btn-info m-0 d-flex" style="padding: .5rem;" title="Enviar por Correo">
																			<i class="fa-regular fa-envelope" style="font-size: .75rem;"></i>
																		</button>
																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
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

				<div class="tab-pane" id="recordatorios" role="tabpanel" aria-labelledby="arriendo-ft-recordatorios-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Recordatorios</h3>

								<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
									<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Agregar Recordatorio" data-bs-toggle="modal" data-bs-target="#modalRecordatoriosNuevo">
										<span>Agregar Recordatorio</span>
									</button>

								</div>
								<div class="row">
									<!-- <h4>Movimientos</h4> -->

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="recordatorios" class="table table-striped" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th>Fecha Notificación</th>
															<th>Tipo</th>
															<th>Descripción</th>
															<th>Ejecutivo</th>
															<th>Realizado</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>

														<?php foreach ($dataTableRecordatorios as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>
																<td>
																	<div class="d-flex" style="gap: .5rem;">
																		<a href="" type="button" class="btn btn-info m-0 d-flex" style="padding: .5rem;" aria-label="Editar" title="Editar" data-bs-toggle="modal" data-bs-target="#modalRecordatoriosNuevo">
																			<i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
																		</a>

																		<button type="button" class="btn btn-danger m-0 d-flex" style="padding: .5rem;" title="Eliminar">
																			<i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
																		</button>
																	</div>
																</td>
															</tr>
														<?php endforeach; ?>
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

				<div class="tab-pane" id="historial" role="tabpanel" aria-labelledby="arriendo-ft-historial-tab" tabindex="0">
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
															<th style="width: 85px">Fecha</th>
															<th>Responsable</th>
															<th>Acción</th>
															<th>Recurso</th>
															<th>ID</th>
															<th>Cambios</th>
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

			</div>

		</div>
	</div>

	<!-- Modal Cuenta Corriente - Ingresar Abono-->
	<div class="modal fade" id="modalCuentaCorrienteIngresoAbonos" tabindex="-1" aria-labelledby="modalCuentaCorrienteIngresoAbonosLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaCorrienteIngresoAbonosLabel">Ingresar Abono</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="cc_abono" name="cc_abono" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col mb-3">
								<label for="ccTipoMovimientoAbono" class="form-label"><span class="obligatorio">*</span> Tipo Movimiento</label>
								<select class="form-select form-select-sm" aria-label="Default select example" name="ccTipoMovimientoAbono" id="ccTipoMovimientoAbono"
									onchange="TipoFormularioAbonosMovimientos()">
									<option value="" selected>Selecciona una opcion</option>
								</select>

								<input type="hidden" id="idResponsableAbono" name="idResponsableAbono">
								<input type="hidden" id="cccta_contable" name="cccta_contable">

							</div>
						</div>

						<div id="FormularioMovimientosAbonos">

							<div class="row">
								<div class="col mb-3">
									<label for="ccIngresoPagoRazonAbono" class="form-label"><span class="obligatorio">*</span> Razón</label>
									<input type="text" class="form-control" id="ccIngresoPagoRazonAbono" placeholder="Ingrese Razón">
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 mb-3">
									<label for="ccIngresoPagoFechaAbono" class="form-label"><span class="obligatorio">*</span> Fecha</label>
									<input type="date" class="form-control" id="ccIngresoPagoFechaAbono">
								</div>
							</div>

						</div>


						<div class="row">
							<div class="col-md-8 mb-3">
								<label for="ccIngresoPagoMontoAbono" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="text" min="0" class="form-control" id="ccIngresoPagoMontoAbono" placeholder="Ingrese Monto">
							</div>

							<script>
								$("#ccIngresoPagoMontoAbono").keyup(function(event) {
									if (event.which >= 37 && event.which <= 40) {
										event.preventDefault();
									}
									$(this).val(function(index, value) {
										return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
									});
								});
							</script>
							<div class="col-md-4 mb-3">
								<label for="ccIngresoPagoMonedaAbono" class="form-label"><span class="obligatorio">*</span> Moneda</label>
								<select readonly disabled name="ccIngresoPagoMonedaAbono" id="ccIngresoPagoMonedaAbono" class="form-control">
									<option value="2" id="2">Pesos</option>
								</select>
							</div>
						</div>

					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="GuardarAbonos()">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Cuenta Corriente - Ingresar Cargo-->
	<div class="modal fade" id="modalCuentaCorrienteIngresoCargos" tabindex="-1" aria-labelledby="modalCuentaCorrienteIngresoCargosLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaCorrienteIngresoPagoLabel">Ingresar Cargo</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="cc_cargo" name="cc_cargo" method="post" enctype="multipart/form-data">

						<div class="row">
							<div class="col mb-3">
								<label for="ccTipoMovimientoCargo" class="form-label"><span class="obligatorio">*</span> Tipo Movimiento</label>
								<select class="form-select form-select-sm" aria-label="Default select example" name="ccTipoMovimientoCargo" id="ccTipoMovimientoCargo"
									onchange="TipoFormularioCargosMovimientos()">
								</select>
							</div>

							<input type="hidden" id="idResponsableCargo" name="idResponsableCargo">
							<input type="hidden" id="cccta_contableCargo" name="cccta_contableCargo">

						</div>


						<div id="FormularioTipoMovimiento">

							<div class="row">
								<div class="col mb-3">
									<label for="ccIngresoPagoRazon" class="form-label"><span class="obligatorio">*</span> Razón</label>
									<input type="text" class="form-control" id="ccIngresoPagoRazon" placeholder="Ingrese Razón">
								</div>
							</div>
							<div class="row">
								<div class="col-md-8 mb-3">
									<label for="ccIngresoPagoMonto" class="form-label"><span class="obligatorio">*</span> Monto</label>
									<input type="text" min="0" class="form-control" id="ccIngresoPagoMonto" placeholder="Ingrese Monto">
								</div>
								<script>
									$("#ccIngresoPagoMonto").keyup(function(event) {
										if (event.which >= 37 && event.which <= 40) {
											event.preventDefault();
										}
										$(this).val(function(index, value) {
											return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
										});
									});
								</script>
								<div class="col-md-4 mb-3">
									<label for="ccIngresoPagoMoneda" class="form-label"><span class="obligatorio">*</span> Moneda</label>
									<select readonly disabled name="ccIngresoPagoMoneda" id="ccIngresoPagoMoneda" class="form-control">
										<option value="2" id="2">Pesos</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 mb-3">
									<label for="ccIngresoPagoFecha" class="form-label"><span class="obligatorio">*</span> Fecha</label>
									<input type="date" class="form-control" id="ccIngresoPagoFecha">
								</div>
							</div>

						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="guardarCcCargo()">Guardar</button>
				</div>
			</div>
		</div>
	</div>




	<!-- Modal Cuenta Corriente - Ingresar Pago No Liquidable-->
	<div class="modal fade" id="modalCuentaCorrienteIngresoPagoNoLiquidable" tabindex="-1" aria-labelledby="modalCuentaCorrienteIngresoPagoNoLiquidableLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaCorrienteIngresoPagoNoLiquidableLabel">Ingresar Pago No Liquidable</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="cc_pago_no_liquidable" name="cc_pago_no_liquidable" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col mb-3">
								<label for="ccIngresoPagoNLRazon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<input type="text" class="form-control" id="ccIngresoPagoNLRazon" placeholder="Ingrese Razón">
							</div>
						</div>
						<div class="row">
							<div class="col-md-8 mb-3">
								<label for="ccIngresoPagoNLMonto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="number" min="0" class="form-control" id="ccIngresoPagoNLMonto" placeholder="Ingrese Monto">
							</div>
							<div class="col-md-4 mb-3">
								<label for="ccIngresoPagoNLMoneda" class="form-label"><span class="obligatorio">*</span> Moneda</label>
								<select readonly disabled name="ccIngresoPagoNLMoneda" id="ccIngresoPagoNLMoneda" class="form-control">
									<option value="2" id="2">Pesos</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 mb-3">
								<label for="ccIngresoPagoNLFecha" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="date" class="form-control" id="ccIngresoPagoNLFecha">
							</div>
						</div>


					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="guardarCcPagoNoLiquidable()">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Cuenta Corriente - Ingresar Descuento Autorizado-->
	<div class="modal fade" id="modalCuentaCorrienteIngresoDescuentoAutorizado" tabindex="-1" aria-labelledby="modalCuentaCorrienteIngresoDescuentoAutorizadoLabel" aria-hidden="true">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaCorrienteIngresoDescuentoAutorizadoLabel">Ingresar Descuento Autorizado</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="cc_descuento_autorizado" name="cc_descuento_autorizado" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col mb-3">
								<label for="ccIngresoDescAutorizadoRazon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<input type="text" class="form-control" id="ccIngresoDescAutorizadoRazon" placeholder="Ingrese Razón">
							</div>
						</div>
						<div class="row">
							<div class="col-md-8 mb-3">
								<label for="ccIngresoDescAutorizadoMonto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="number" min="0" class="form-control" id="ccIngresoDescAutorizadoMonto" placeholder="Ingrese Monto">
							</div>
							<div class="col-md-4 mb-3">
								<label for="ccIngresoDescAutorizadoMoneda" class="form-label"><span class="obligatorio">*</span> Moneda</label>
								<select readonly disabled name="ccIngresoDescAutorizadoMoneda" id="ccIngresoDescAutorizadoMoneda" class="form-control">
									<option value="2" id="2">Pesos</option>
								</select>
							</div>
						</div>


						<!-- <div class="row">
							<div class="col-md-12">
								<strong>Documentos</strong><br>

								<input id="archivo" name="archivo" type="file" onchange="validaArchivo(this);" class="btn btn-file  opacity-100 position-relative h-auto w-100" />

								<input type="hidden" name="archivo_cuenta_corrientebd" id="archivo_cuenta_corrientebd" value="<?php echo htmlspecialchars(@$existe_archivo); ?>">

								<?php echo @$archivo; ?>
							</div>
						</div> -->

						<div class="row">
							<div class="col-md-8 mb-3">
								<label for="ccIngresoDescAutorizadoCobraComision" class="form-label"><span class="obligatorio">*</span> Cobra Comisión</label>
								<select name="ccIngresoDescAutorizadoCobraComision" id="ccIngresoDescAutorizadoCobraComision" class="form-control">
									<option value="true" id="1">SI</option>
									<option value="false" id="2">NO</option>
								</select>
							</div>
							<div class="col-md-4 mb-3">
								<label for="ccIngresoDescAutorizadoFecha" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="date" class="form-control" id="ccIngresoDescAutorizadoFecha">
							</div>
						</div>


					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="guardarCcDescuentoAutorizado()">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Cuenta Corriente - Ingresar Cobro Mensualidad-->
	<div class="modal fade" id="modalCuentaCorrienteIngresoCobroMensualidad" tabindex="-1" aria-labelledby="modalCuentaCorrienteIngresoCobroMensualidadLabel" aria-hidden="true">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaCorrienteIngresoCobroMensualidadLabel">Ingresar Cobro Mensualidad</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="">

						<div class="row">

							<div class="col-md-6 mb-3">
								<label for="modalMoneda" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="date" class="form-control" id="modalMoneda" placeholder="20/02/2024">

							</div>
						</div>


					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Cuenta Corriente - Ingresar Cobro Extra-->
	<div class="modal fade" id="modalCuentaCorrienteIngresoCobroExtra" tabindex="-1" aria-labelledby="modalCuentaCorrienteIngresoCobroExtraLabel" aria-hidden="true">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaCorrienteIngresoCobroExtraLabel">Ingresar Cobro Extra</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="cc_cobro_extra" name="cc_cobro_extra" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col mb-3">
								<label for="ccIngresoCobroExtraRazon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<input type="text" class="form-control" id="ccIngresoCobroExtraRazon" placeholder="Ingrese Razón">
							</div>
						</div>
						<div class="row">
							<div class="col-md-8 mb-3">
								<label for="ccIngresoCobroExtraMonto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="number" min="0" class="form-control" id="ccIngresoCobroExtraMonto" placeholder="Ingrese Monto">
							</div>
							<div class="col-md-4 mb-3">
								<label for="ccIngresoCobroExtraMoneda" class="form-label"><span class="obligatorio">*</span> Moneda</label>
								<select readonly disabled name="ccIngresoCobroExtraMoneda" id="ccIngresoCobroExtraMoneda" class="form-control">
									<option value="2" id="2">Pesos</option>
								</select>
							</div>
						</div>


						<!-- <div class="row">
							<div class="col-md-12">
								<strong>Documentos</strong><br>

								<input id="archivo" name="archivo" type="file" onchange="validaArchivo(this);" class="btn btn-file  opacity-100 position-relative h-auto w-100" />

								<input type="hidden" name="archivo_cuenta_corrientebd" id="archivo_cuenta_corrientebd" value="<?php echo htmlspecialchars(@$existe_archivo); ?>">

								<?php // echo @$archivo; 
								?>
							</div>
						</div> -->

						<div class="row">
							<div class="col-md-8 mb-3">
								<label for="ccIngresoCobroExtraCuotas" class="form-label"><span class="obligatorio">*</span> Cuotas</label>
								<input type="number" min="1" class="form-control" id="ccIngresoCobroExtraCuotas" value="1">
							</div>
							<div class="col-md-4 mb-3">
								<label for="ccIngresoCobroExtraFecha" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="date" class="form-control" id="ccIngresoCobroExtraFecha">
							</div>
						</div>


					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="guardarCcCobroExtra()">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Cuenta CR Ingreso-->
	<div class="modal fade" id="modalCTR" tabindex="-1" aria-labelledby="modalCTR" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaCorrienteIngresoCobroExtraLabel">Ingresar Cargo a la Renta CR</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

					<form id="cr_cobro" name="cr_cobro" method="post" enctype="multipart/form-data" onsubmit="guardarCR(); return false;">

						<div class="row mb-3">
							<div class="col-8 mb-3">
								<label for="ccIngresoCobroExtraRazon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<input type="text" class="form-control" id="crRazon" name="razon" placeholder="Ingrese Razón" required>
							</div>
							<div class="col-4 mb-3">
								<label for="ccIngresoCobroExtraFecha" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="date" class="form-control" id="ccFecha" name="fecha" required>
							</div>
						</div>

						<div class="row">

							<div class="col-3 mb-3">
								<label for="ccIngresoCobroExtraMonto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="number" min="0" class="form-control" id="crMonto" name="monto" placeholder="Monto" required>
							</div>

							<div class="col-3 mb-3">
								<label for="ccIngresoCobroExtraMoneda" class="form-label"><span class="obligatorio">*</span> Moneda</label>
								<select readonly disabled name="moneda" id="crMoneda" class="form-control" required>
									<option value="2" id="2">Pesos</option>
								</select>
							</div>

							<div class="col-3 mb-3">
								<label for="ccIngresoCobroExtraAnio" class="form-label"><span class="obligatorio">*</span>Imputar Año</label>
								<input type="number" min="1900" max="2099" step="1" value="<?= date('Y') ?>" class="form-control" id="crAnio" name="anio" placeholder="Ingrese Año" required>
							</div>

							<div class="col-3 mb-3">
								<label for="ccIngresoCobroExtraMes" class="form-label"><span class="obligatorio">*</span>Imputar Mes</label>
								<select class="form-control" id="crMes" name="mes" required>
									<option value="1">Enero</option>
									<option value="2">Febrero</option>
									<option value="3">Marzo</option>
									<option value="4">Abril</option>
									<option value="5">Mayo</option>
									<option value="6">Junio</option>
									<option value="7">Julio</option>
									<option value="8">Agosto</option>
									<option value="9">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>

						</div>

						<div class="row">

							<div class="row">



							</div>


						</div>

						<div class="row">
							<div class="col-md-12">
								<strong>Documentos</strong><br>
								<input id="archivo" name="archivo" type="file" onchange="validaArchivo(this);" class="btn btn-file opacity-100 position-relative h-auto w-100" accept="image/*" />
								<input type="hidden" name="CrDocumento" id="CrDocumento">
							</div>
						</div>

						<input type="hidden" name="token" id="token" value="<?php echo @$token; ?>">

					</form>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="guardarCR()">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Cuenta CR Editar -->
	<div class="modal fade" id="modalCTREditar" tabindex="-1" aria-labelledby="modalCTREditar" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCuentaCorrienteIngresoCobroExtraLabel">Editar Cargo a la renta CTR</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="cr_cobroEditar" name="cr_cobroEditar" method="post" enctype="multipart/form-data" onsubmit="EditarrCR(); return false;">

						<div class="row mb-3">
							<div class="col-8 mb-3">
								<label for="ccIngresoCobroExtraRazon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<input type="text" class="form-control" id="crRazonEditar" name="razon" placeholder="Ingrese Razón" required>
							</div>
							<div class="col-4 mb-3">
								<label for="ccIngresoCobroExtraFecha" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="date" class="form-control" id="ccFechaEditar" name="fecha" required>
							</div>
						</div>

						<div class="row">
							<div class="col-md-3 mb-3">
								<label for="ccIngresoCobroExtraMonto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="number" min="0" class="form-control" id="crMontoEditar" name="monto" placeholder="Ingrese Monto" required>
							</div>

							<div class="col-md-3 mb-3">
								<label for="ccIngresoCobroExtraMoneda" class="form-label"><span class="obligatorio">*</span> Moneda</label>
								<select readonly disabled name="moneda" id="crMoneda" class="form-control" required>
									<option value="2" id="2">Pesos</option>
								</select>
							</div>

							<div class="col-md-3 mb-2">
								<label for="ccIngresoCobroExtraAnio" class="form-label"><span class="obligatorio">*</span>Imputar Año</label>
								<input type="number" min="1900" max="2099" step="1" class="form-control" id="crAnioEditar" name="anio" placeholder="Ingrese Año" required>
							</div>

							<div class="col-md-3 mb-3">
								<label for="ccIngresoCobroExtraMes" class="form-label"><span class="obligatorio">*</span>Imputar Mes</label>
								<select class="form-control" id="crMesEditar" name="mes" required>
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>

						</div>

						<div class="row">
							<div class="col-md-12">
								<strong>Documentos</strong><br>
								<input id="archivo" name="archivo" type="file" onchange="validaArchivo(this);" class="btn btn-file opacity-100 position-relative h-auto w-100" accept="image/*" />
								<input type="hidden" name="CrDocumento" id="CrDocumento">
							</div>
						</div>

						<input type="hidden" id="cobroEditar" name="cobroEditar">
						<input type="hidden" name="token" id="token" value="<?php echo @$token; ?>">
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="EditarrCR()">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!--bruno-->
	<div class="modal fade" id="modalChequesEditar" tabindex="-1" aria-labelledby="modalChequesEditarLabel" aria-hidden="true" data-bs-backdrop="static">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalChequesEditarLabel">Editar Cheque</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form name="cheque_formulario_Editar" id="cheque_formulario_Editar" method="post" action="javascript: editarCheque();" enctype="multipart/form-data" class="my-3">
						<div class="row">
							<div class="col mb-3">
								<label for="Cheque_Monto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="text" min="0" name="Cheque_Monto_Editar" class="form-control" id="Cheque_Monto_Editar" placeholder="Ingrese Monto" required>
							</div>
							<script>
								$("#Cheque_Monto_Editar").keyup(function(event) {
									if (event.which >= 37 && event.which <= 40) {
										event.preventDefault();
									}
									$(this).val(function(index, value) {
										return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
									});
								});
							</script>
							<div class="col mb-3">
								<label for="Cheque_Razon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<select name="Cheque_Razon_Editar" class="form-control" id="Cheque_Razon_Editar" required>
									<option value="">Seleccione</option>
									<option value="Pago arriendo">Pago arriendo</option>
									<option value="Garantía">Garantía</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col mb-3">
								<label for="tipo_banco_Editar" class="form-label"><span class="obligatorio">*</span>Banco</label>
								<?php echo $selectBancoEditar ?>
							</div>
							<div class="col mb-3">
								<label for="Cheque_Fecha" class="form-label">Fecha Cobro</label>
								<input type="date" name="Cheque_Fecha_Editar" class="form-control" id="Cheque_Fecha_Editar" placeholder="Fecha" required>
							</div>
						</div>
						<div class="row">
							<div class="col mb-3">
								<label for="Cheque_Girador" class="form-label">Girador</label>
								<input type="text" name="Cheque_Girador_Editar" class="form-control" id="Cheque_Girador_Editar" placeholder="Seleccione Girador" required>
							</div>
							<div class="col mb-3">
								<label for="Cheque_Numero_Doc" class="form-label">Nro. Documento</label>
								<input min="0" type="number" name="Cheque_Numero_Doc_Editar" class="form-control" id="Cheque_Numero_Doc_Editar" placeholder="Ingrese Nro. Documento" required>
								<input type="hidden" name="ID_Cheque_Editar" id="ID_Cheque_Editar">
							</div>
						</div>
						<div class="row">
							<div class="col-12 mb-3">
								<label for="Comentario_Cheque_Editar" class="form-label">Comentario</label>
								<textarea name="Comentario_Cheque_Editar" class="form-control px-3" id="Comentario_Cheque_Editar" rows="4" maxlength="250" placeholder=" Escriba su comentario"></textarea>
							</div>
						</div>
						<div class="modal-footer mb-0">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-primary">Guardar</button>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>

	<!-- Modal Cheques -->
	<div class="modal fade" id="modalChequesEditar" tabindex="-1" aria-labelledby="modalChequesEditarLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalChequesEditarLabel">Editar Cheque</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form name="cheque_formulario_Editar" id="cheque_formulario_Editar" method="post" action="javascript: editarCheque();" enctype="multipart/form-data" class="my-3">
						<div class="row">
							<div class="col mb-3">
								<label for="Cheque_Monto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="text" min="0" name="Cheque_Monto_Editar" class="form-control" id="Cheque_Monto_Editar" placeholder="Ingrese Monto" required>
							</div>
							<script>
								$("#Cheque_Monto_Editar").keyup(function(event) {
									if (event.which >= 37 && event.which <= 40) {
										event.preventDefault();
									}
									$(this).val(function(index, value) {
										return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
									});
								});
							</script>
							<div class="col mb-3">
								<label for="Cheque_Razon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<select name="Cheque_Razon_Editar" class="form-control" id="Cheque_Razon_Editar" required>
									<option value="">Seleccione</option>
									<option value="Pago arriendo">Pago arriendo</option>
									<option value="Garantía">Garantía</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col mb-3">
								<label for="tipo_banco_Editar" class="form-label"><span class="obligatorio">*</span>Banco</label>
								<?php echo $selectBancoEditar ?>
							</div>
							<div class="col mb-3">
								<label for="Cheque_Fecha" class="form-label">Fecha Cobro</label>
								<input type="date" name="Cheque_Fecha_Editar" class="form-control" id="Cheque_Fecha_Editar" placeholder="Fecha">
							</div>
						</div>
						<div class="row">
							<div class="col mb-3">
								<label for="Cheque_Girador" class="form-label">Girador</label>
								<input type="text" name="Cheque_Girador_Editar" class="form-control" id="Cheque_Girador_Editar" placeholder="Seleccione Girador">
							</div>
							<div class="col mb-3">
								<label for="Cheque_Numero_Doc" class="form-label">Nro. Documento</label>
								<input min="0" type="number" name="Cheque_Numero_Doc_Editar" class="form-control" id="Cheque_Numero_Doc_Editar" placeholder="Ingrese Nro. Documento">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="Cantidad_Numero" class="form-label">Cantidad</label>
								<input min="0" type="number" name="Cantidad_Cheque_Editar" class="form-control" id="Cantidad_Cheque_Editar" placeholder="Ingrese Cantidad">
								<input type="hidden" name="ID_Cheque_Editar" id="ID_Cheque_Editar">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-primary">Guardar</button>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>

	<!-- Modal Cobros - Cobrar-->
	<div class="modal fade" id="modalCobrosCobrar" tabindex="-1" aria-labelledby="modalCobrosCobrarLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCobrosCobrarLabel">Cobrar</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="">
						<div class="row">
							<div class="col mb-3">
								<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="email" class="form-control" id="modalRazon" placeholder="02-2024">
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">Crear</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Cobros - Ingreso Pago Comisión de Arriendo-->
	<div class="modal fade" id="modalCobrosIngresoPagoComisionArriendo" tabindex="-1" aria-labelledby="modalCobrosIngresoPagoComisionArriendoLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCobrosIngresoPagoComisionArriendoLabel">Ingresar Pago Comisión de Arriendo</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="">
						<div class="row">
							<div class="col mb-3">
								<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Fecha</label>
								<input type="email" class="form-control" id="modalRazon" placeholder="02-2024">
							</div>
						</div>
						<div class="row">
							<div class="col mb-3">
								<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Glosa</label>
								<input type="email" class="form-control" id="modalRazon" placeholder="Ingrese Glosa">
							</div>
						</div>
						<div class="row">
							<div class="col mb-3">
								<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="email" class="form-control" id="modalRazon" placeholder="Ingrese Monto">
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">Crear</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Garantía - Ingresar Comentario Documento-->
	<div class="modal fade" id="modalGarantiaIngresoComentarioDocumento" tabindex="-1" aria-labelledby="modalGarantiaIngresoComentarioDocumentoLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalGarantiaIngresoComentarioDocumentoLabel">Ingresar Comentario o Documento</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="">


						<div class="row">
							<div class="col-md-12">
								<strong>Documentos</strong><br>

								<input id="archivo" name="archivo" type="file" onchange="validaArchivo(this);" class="btn btn-file  opacity-100 position-relative h-auto w-100" />

								<input type="hidden" name="archivo_bd" id="archivo_bd" value="<?php echo htmlspecialchars(@$existe_archivo); ?>">

								<?php echo @$archivo; ?>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="exampleFormControlTextarea1" class="form-label">Añadir Comentario</label>
								<textarea class="form-control" id="exampleFormControlTextarea1" style="padding:1rem" placeholder="Ingrese un comentario..."></textarea>

							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Recordatorios - Nuevo Recordatorio-->
	<div class="modal fade" id="modalRecordatoriosNuevo" tabindex="-1" aria-labelledby="modalRecordatoriosNuevoLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalRecordatoriosNuevoLabel">Nuevo Recordatorio</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="">
						<div class="row">
							<div class="col mb-3">
								<label for="modalRazon" class="form-label"><span class="obligatorio">*</span> Tipo Recordatorio</label>
								<input type="email" class="form-control" id="modalRazon" placeholder="Seleccione tipo">
							</div>
						</div>
						<div class="row">
							<div class="col-md mb-3">
								<label for="modalMonto" class="form-label"><span class="obligatorio">*</span> Fecha Notificación</label>
								<input type="email" class="form-control" id="modalMonto" placeholder="20/02/2024">
							</div>
							<div class="col-md mb-3">
								<label for="modalMoneda" class="form-label">Descripción</label>
								<input type="email" class="form-control" id="modalMoneda" placeholder="Pesos">
							</div>
						</div>
						<div class="row">
							<div class="col-md mb-3">
								<label for="modalMoneda" class="form-label">Ejecutivo</label>
								<input type="email" class="form-control" id="modalMoneda" placeholder="Seleccione Ejecutivo">
							</div>
							<div class="col-md mb-3">
								<label for="modalMoneda" class="form-label">Realizado</label>
								<input type="email" class="form-control" id="modalMoneda" placeholder="NO">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mb-3" style="display: flex;align-items: center;gap:.5rem">
								<label for="modalMoneda" class="form-label m-0">Repetir</label>
								<input type="email" class="form-control" id="modalMoneda" placeholder="0">
								<label for="modalMoneda" class="form-label m-0">veces cada</label>
								<input type="email" class="form-control" id="modalMoneda" placeholder="1">
								<input type="email" class="form-control" id="modalMoneda" placeholder="días">

							</div>
						</div>

					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary">Guardar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Comentarios - Ingreso-->
	<div class="modal fade" id="modalInfoComentarioIngreso" tabindex="-1" aria-labelledby="modalInfoComentarioIngresoLabel" aria-hidden="true" data-bs-backdrop="static">
		<div class="modal-dialog" style="max-width: 800px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalEditarInfoComentarioLabel">Agregar Comentario</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="" id="comentario_formulario" name="comentario_formulario" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-12">
								<label for="ComentarioIngreso" class="form-label">Comentario</label>
								<textarea class="form-control" id="ComentarioIngreso" style="padding:1rem" placeholder="Ingrese un comentario..." value=""></textarea>
							</div>
						</div>
						<input type="hidden" name="id_ficha" id="id_ficha" value="<?php echo $id_ficha; ?>">

						<div class="modal-footer mt-4">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
							<button type="button" class="btn btn-danger" id="addItemButton" onclick="guardarInfoComentario()">Guardar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Comentarios - Edición-->
	<div class="modal fade" id="modalEditarInfoComentario" tabindex="-1" aria-labelledby="modalEditarInfoComentarioLabel" aria-hidden="true" data-bs-backdrop="static">
		<div class="modal-dialog" style="max-width: 800px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalEditarInfoComentarioLabel">Edición Comentario</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="comentario_formulario_editar" name="comentario_formulario_editar" action="javascript: editarInfoComentario();" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-12">
								<label for="ComentarioEditar" class="form-label">Comentario</label>
								<textarea class="form-control" name="ComentarioEditar" id="ComentarioEditar" style="padding:1rem" placeholder="Ingrese un comentario..." value=""></textarea>
							</div>
						</div>
						<input type="hidden" class="form-control" min="0" name="InfoComentarioTokenEditar" id="InfoComentarioTokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						<input type="hidden" name="ID_Info_Comentario_Editar" id="ID_Info_Comentario_Editar">

						<div class="modal-footer mt-4">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-danger">Guardar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- -->
	<div class="modal fade" id="modalEditarInfoComentario" tabindex="-1" aria-labelledby="modalEditarInfoComentarioLabel" aria-hidden="true" data-bs-backdrop="static">
		<div class="modal-dialog" style="max-width: 800px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalEditarInfoComentarioLabel">Edición Comentario</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="comentario_formulario_editar" name="comentario_formulario_editar" action="javascript: editarInfoComentario();" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-12">
								<label for="ComentarioEditar" class="form-label">Comentario</label>
								<textarea class="form-control" name="ComentarioEditar" id="ComentarioEditar" style="padding:1rem" placeholder="Ingrese un comentario..." value=""></textarea>
							</div>
						</div>
						<input type="hidden" class="form-control" min="0" name="InfoComentarioTokenEditar" id="InfoComentarioTokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						<input type="hidden" name="ID_Info_Comentario_Editar" id="ID_Info_Comentario_Editar">

						<div class="modal-footer mt-4">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-danger">Guardar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!--bruno-->

	<!-- Modal Cheques - INGRESAR Cheque-->
	<div class="modal fade" id="modalChequesIngreso" tabindex="-1" aria-labelledby="modalChequesIngresoLabel" aria-hidden="true" data-bs-backdrop="static">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalChequesIngresoLabel">Ingreso Cheque</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form name="cheque_formulario" id="cheque_formulario" method="post" action="javascript: guardarCheque();" enctype="multipart/form-data" class="my-3">
						<div class="row">
							<div class="col mb-3">
								<label for="Cheque_Monto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="text" min="0" name="Cheque_Monto" class="form-control" id="Cheque_Monto" placeholder="Ingrese Monto" required>
							</div>
							<script>
								$("#Cheque_Monto").keyup(function(event) {
									if (event.which >= 37 && event.which <= 40) {
										event.preventDefault();
									}
									$(this).val(function(index, value) {
										return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
									});
								});
							</script>
							<div class="col mb-3">
								<label for="Cheque_Razon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<select name="Cheque_Razon" class="form-control" id="Cheque_Razon" required>
									<option value="">Seleccione</option>
									<option value="Pago arriendo">Pago arriendo</option>
									<option value="Garantía">Garantía</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col mb-3">
								<label for="tipo_banco" class="form-label"><span class="obligatorio">*</span>Banco</label>
								<?php echo $selectBanco ?>
							</div>
							<div class="col mb-3">
								<label for="Cheque_Fecha" class="form-label">Fecha Cobro</label>
								<input type="date" name="Cheque_Fecha" class="form-control" id="Cheque_Fecha" placeholder="Fecha" required>
							</div>
						</div>
						<div class="row">
							<div class="col mb-3">
								<label for="Cheque_Girador" class="form-label">Girador</label>
								<input type="text" name="Cheque_Girador" class="form-control" id="Cheque_Girador" placeholder="Seleccione Girador" required>
							</div>
							<div class="col mb-3">
								<label for="Cheque_Numero_Doc" class="form-label">Nro. Documento</label>
								<input min="0" type="number" name="Cheque_Numero_Doc" class="form-control" id="Cheque_Numero_Doc" placeholder="Ingrese Nro. Documento" required>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="Cantidad_Cheque" class="form-label">Cantidad</label>
								<select name="Cantidad_Cheque" class="form-control selectpicker" id="Cantidad_Cheque" data-dropup-auto="false">
									<option value="" disabled selected>Seleccione Cantidad</option>
									<option value="12">12</option>
									<option value="11">11</option>
									<option value="10">10</option>
									<option value="9">9</option>
									<option value="8">8</option>
									<option value="7">7</option>
									<option value="6">6</option>
									<option value="5">5</option>
									<option value="4">4</option>
									<option value="3">3</option>
									<option value="2">2</option>
									<option value="1">1</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-12 mb-3">
								<label for="Comentario_Cheque" class="form-label">Comentario</label>
								<textarea name="Comentario_Cheque" class="form-control px-3" id="Comentario_Cheque" rows="4" maxlength="250" placeholder=" Escriba su comentario"></textarea>
							</div>
						</div>
						<input type="hidden" name="id_ficha" id="id_ficha" value="<?php echo $id_ficha; ?>">
						<div class="modal-footer mb-0">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-primary" id="btnIngresarCheque">Guardar</button>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>


	<!-- Modal Cheques - Editar Cheque-->
	<div class="modal fade" id="modalChequesEditar" tabindex="-1" aria-labelledby="modalChequesEditarLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalChequesEditarLabel">Editar Cheque</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form name="cheque_formulario_Editar" id="cheque_formulario_Editar" method="post" action="javascript: editarCheque();" enctype="multipart/form-data" class="my-3">
						<div class="row">
							<div class="col mb-3">
								<label for="Cheque_Monto" class="form-label"><span class="obligatorio">*</span> Monto</label>
								<input type="text" min="0" name="Cheque_Monto_Editar" class="form-control" id="Cheque_Monto_Editar" placeholder="Ingrese Monto" required>
							</div>
							<script>
								$("#Cheque_Monto_Editar").keyup(function(event) {
									if (event.which >= 37 && event.which <= 40) {
										event.preventDefault();
									}
									$(this).val(function(index, value) {
										return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
									});
								});
							</script>
							<div class="col mb-3">
								<label for="Cheque_Razon" class="form-label"><span class="obligatorio">*</span> Razón</label>
								<select name="Cheque_Razon_Editar" class="form-control" id="Cheque_Razon_Editar" required>
									<option value="">Seleccione</option>
									<option value="Pago arriendo">Pago arriendo</option>
									<option value="Garantía">Garantía</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col mb-3">
								<label for="tipo_banco_Editar" class="form-label"><span class="obligatorio">*</span>Banco</label>
								<?php echo $selectBancoEditar ?>
							</div>
							<div class="col mb-3">
								<label for="Cheque_Fecha" class="form-label">Fecha Cobro</label>
								<input type="date" name="Cheque_Fecha_Editar" class="form-control" id="Cheque_Fecha_Editar" placeholder="Fecha">
							</div>
						</div>
						<div class="row">
							<div class="col mb-3">
								<label for="Cheque_Girador" class="form-label">Girador</label>
								<input type="text" name="Cheque_Girador_Editar" class="form-control" id="Cheque_Girador_Editar" placeholder="Seleccione Girador">
							</div>
							<div class="col mb-3">
								<label for="Cheque_Numero_Doc" class="form-label">Nro. Documento</label>
								<input min="0" type="number" name="Cheque_Numero_Doc_Editar" class="form-control" id="Cheque_Numero_Doc_Editar" placeholder="Ingrese Nro. Documento">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="Cantidad_Numero" class="form-label">Cantidad</label>
								<input min="0" type="number" name="Cantidad_Cheque_Editar" class="form-control" id="Cantidad_Cheque_Editar" placeholder="Ingrese Cantidad">
								<input type="hidden" name="ID_Cheque_Editar" id="ID_Cheque_Editar">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-primary">Guardar</button>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>






</div>