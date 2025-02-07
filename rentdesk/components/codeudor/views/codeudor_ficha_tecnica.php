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
			<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=codeudor&view=codeudor_list" style="text-decoration: none;color:#66615b">Codeudores</a></li>
			<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Codeudor AARON ISAAC MANQUEO ORMEÑO</li>
		</ol>
	</div>
</div>

<div class="content content-page" >

	<div class="row">
		<div class="col-lg-2 p-0" style="display:none;">
			<!-- Nav tabs -->
			<ul class="nav flex-column nav-pills p-0" id="myTab" role="tablist" aria-orientation="vertical" style="display: flex;gap:1rem">
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link active w-100" id="codeudor-ft-informacion-tab" data-bs-toggle="tab" data-bs-target="#informacion" type="button" role="tab" aria-controls="informacion" aria-selected="true">Información</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="codeudor-ft-arriendos-tab" data-bs-toggle="tab" data-bs-target="#arriendos" type="button" role="tab" aria-controls="arriendos" aria-selected="false">Arriendos</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="d-flex nav-link w-100" id="codeudor-ft-historial-tab" data-bs-toggle="tab" data-bs-target="#historial" type="button" role="tab" aria-controls="historial" aria-selected="false">Historial</button>
				</li>
			</ul>
		</div>
		<div class="col-lg-12">

			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane active" id="informacion" role="tabpanel" aria-labelledby="codeudor-ft-informacion-tab" tabindex="0">
					<div class="container-fluid">
						<div class="card">
							<div class="card-body">
								<h3>Información</h3>
								<div class="row">
									<div class="col-md-6">
										<dl>
											<dt>Nombre</dt>
											<dd>AARON ISAAC MANQUEO ORMEÑO</dd>
											<dt>Tipo Cocumento</dt>
											<dd>Rut</dd>
											<dt>Nro. Documento</dt>
											<dd>9.498.957-4</dd>
											<dt>Correo Electrónico</dt>
											<dd>macarenaibaceta@fuenzalida.com</dd>
											<dt>Teléfono Fijo</dt>
											<dd>-</dd>
											<dt>Teléfono Celular</dt>
											<dd>968397414</dd>
										</dl>
									</div>
									<div class="col-md-6">
										<dl>
											<dt>Dirección</dt>
											<dd>ANDRES BELLO 2777 Oficina 1903, Las Condes, Región Metropolitana</dd>
										</dl>
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
				<div class="tab-pane" id="arriendos" role="tabpanel" aria-labelledby="codeudor-ft-arriendos-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Arriendos</h3>

								<div class="row">
									<!-- <h4>Movimientos</h4> -->

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="infoCuentasBancarias" class="table table-striped" cellspacing="0" width="100%">

													<thead>
														<tr>
															<th>Ficha Técnica</th>
															<th>Propiedad</th>
															<th>Inicio</th>
															<th>Fin</th>
														</tr>
													</thead>
													<tbody>

														<?php foreach ($dataTableArriendos as $row) : ?>
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

												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="historial" role="tabpanel" aria-labelledby="codeudor-ft-historial-tab" tabindex="0">
					<div class="container-fluid">

						<div class="card">
							<div class="card-body">
								<h3>Historial</h3>
								<!-- <div class="row">
									<ul>
										<li><span>Saldo al día: ...</span></li>
										<li><span>Saldo histórico: ...</span></li>
									</ul>


								</div> -->
								<div class="row">
									<!-- <h4>Movimientos</h4> -->

									<div class="card">
										<div class="card-body">
											<div class="table-responsive overflow-auto">

												<table id="retenciones" class="table table-striped" cellspacing="0" width="100%">
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
													<tbody>

														<?php foreach ($dataTableHistorial as $row) : ?>
															<tr>
																<?php foreach ($row as $key => $cell) : ?>

																	<td><?php echo $cell; ?></td>

																<?php endforeach; ?>
																<td>
																	<div class="d-flex" style="gap: .5rem;">
																		<!-- <a href="index.php?component=propiedad&view=propiedad" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
										<i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
									</a> -->
																		<!-- <a href="https://gladius-control-prop.s3.sa-east-1.amazonaws.com/property/106418/co_ownership_liquidations/liquidaci_n-copropietario-60011.pdf?X-Amz-Expires=604800&X-Amz-Date=20240220T123851Z&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAUDM5QTO3L5F4JLQF%2F20240220%2Fsa-east-1%2Fs3%2Faws4_request&X-Amz-SignedHeaders=host&X-Amz-Signature=4dc0315b2556a3ccac04702af4cb9196272c56a7638bedf402cb6a49fe887d6a" target="_blank" type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Ver liquidación">
											<i class="fa-regular fa-file-pdf" style="font-size: .75rem;"></i>
										</a>
										<button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
											<i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
										</button> -->
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
			</div>
		</div>
	</div>

</div>