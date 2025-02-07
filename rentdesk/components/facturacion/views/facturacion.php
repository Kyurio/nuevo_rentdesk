<script src="js/region_ciudad_comuna.js"></script>


<script>
	$(function() {
		$('#datetimepicker1').datetimepicker({
			format: "DD-MM-YYYY",
			defaultDate: moment("<?php echo  date('d-m-Y'); ?>", "DD-MM-YYYY")
		});
	});
</script>
<div id="header" class="header-page">
	<!-- <h2 class="mb-3">Propiedad</h2> -->
	<div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
		<ol class="breadcrumb d-flex align-items-center m-0">
			<li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
			<!-- <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propiedad&view=propiedad_list" style="text-decoration: none;color:#66615b">Propiedades</a></li> -->
			<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Facturación</li>
		</ol>
	</div>
</div>

<div class="content content-page" >

	<div>
		<span>
			<span class="obligatorio">*</span> Indica Campos Obligatorios
		</span>
	</div>
	<form name="formulario" id="formulario" method="post" action="javascript: enviar();" enctype="multipart/form-data" class="my-3">
		<div class="row g-3 p-0">
			<div class="col-md-12 p-0">
				<fieldset class="form-group border p-3">
					<div class="row g-3">
						<div class="col-md-3">
							<div class="form-group">
								<label for="oficina">Tipo Factura</label>
								<select name="oficina" id="oficina" class="form-control">
									<option value="vigente" -id="89">Factura Electrónica Afecta</option>
									<option value="suspendida" -id="127">Factura Electrónica No Afecta o Exenta</option>
									<option value="en-corretaje" -id="128">Boleta Electrónica Afecta</option>
									<option value="en-corretaje" -id="128">Boleta Electrónica Exenta</option>


								</select>
							</div>
						</div>
						<div class="col-md-3">

							<div class="form-group">
								<label for="oficina">Forma de Pago</label>
								<select name="oficina" id="oficina" class="form-control">
									<option value="vigente" -id="89">Crédito</option>
									<option value="suspendida" -id="127">Contado</option>
									<option value="en-corretaje" -id="128">Sin Costo</option>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Razón Social / Nombre</label>
								<input type="text" class="form-control" maxlength="250" name="direccion" id="direccion" placeholder="Razón Social / Nombre" required data-validation-required value="<?php echo @$result->direccion; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>


						<div class="col-md-3">
							<div class="form-group">
								<label>RUT</label>
								<input type="text" class="form-control" maxlength="250" name="direccion" id="direccion" placeholder="RUT" required data-validation-required value="<?php echo @$result->direccion; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Actividad Comercial</label>
								<input type="text" class="form-control" maxlength="250" name="direccion" id="direccion" placeholder="Actividad Comercial" required data-validation-required value="<?php echo @$result->direccion; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Email</label>
								<input type="text" class="form-control" maxlength="250" name="direccion" id="direccion" placeholder="Email" required data-validation-required value="<?php echo @$result->direccion; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

						<div class="col-md-3">
							<label for="fechaIngreso"><span class="obligatorio">*</span> Fecha</label>
							<input name="fechaIngreso" id="fechaIngreso" class="form-control" type="date" value="<?php echo fecha_postgre_a_normal(@$result->fecha_ingreso); ?>" />
							<span id="startDateSelected"></span>
						</div>

					</div>
				</fieldset>
			</div>
			<div class="col-md-12 p-0">
				<fieldset class="form-group border p-3">



					<div class="row g-3">
						<div class="col-md-6">
							<div class="form-group">
								<label><span class="obligatorio">*</span> Dirección</label>
								<input type="text" class="form-control" maxlength="250" name="direccion" id="direccion" placeholder="Dirección" required data-validation-required value="<?php echo @$result->direccion; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>


						<div class="col-lg-3 form-group">
							<label><span class="obligatorio">*</span> Región</label>
							<div id="divregion"></div>
							<input type="hidden" id="hiddenregion" name="hiddenregion" value="<?php echo @$region; ?>">
						</div>

						<div class="col-lg-3 form-group">
							<label><span class="obligatorio">*</span> Comuna</label>
							<div id="divcomuna"></div>
							<input type="hidden" id="hiddencomuna" name="hiddencomuna" value="<?php echo @$comuna; ?>">
						</div>
					</div>

				</fieldset>
			</div>

			<div class="row g-3 p-0">
				<div class="col-md-12 p-0">
					<fieldset class="form-group border p-3">
						<div>
							<legend>
								<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Items</h5>

							</legend>

							<button type="button" class="btn btn-info btn-sm">Agregar Item</button>

						</div>

						<div class="card">
							<div class="card-body">
								<form action="">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Glosa</label>
												<input type="text" class="form-control" maxlength="250" name="rol" id="rol" placeholder="Glosa" required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
											</div>

											<div class="form-group">
												<label>Descripción</label>
												<input type="text" class="form-control" maxlength="15" min="1" name="avaluo_fiscal" id="avaluo_fiscal" placeholder="Descripción" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'<?php echo $_SESSION["cant_decimales"]; ?>','<?php echo $_SESSION["separador_mil"]; ?>');">
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>Monto</label>
														<input type="text" class="form-control" maxlength="15" min="1" name="avaluo_fiscal" id="avaluo_fiscal" placeholder="Monto" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'<?php echo $_SESSION["cant_decimales"]; ?>','<?php echo $_SESSION["separador_mil"]; ?>');">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Cantidad</label>
														<input type="number" class="form-control" maxlength="15" min="1" name="avaluo_fiscal" id="avaluo_fiscal" placeholder="Cantidad" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'<?php echo $_SESSION["cant_decimales"]; ?>','<?php echo $_SESSION["separador_mil"]; ?>');">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Descuento</label>
														<input type="text" class="form-control" maxlength="15" min="1" name="avaluo_fiscal" id="avaluo_fiscal" placeholder="Descuento" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'<?php echo $_SESSION["cant_decimales"]; ?>','<?php echo $_SESSION["separador_mil"]; ?>');">
													</div>
												</div>
												<div class="col-md-6">

													<div class="form-group">
														<label for="oficina">Unidad Descuento</label>
														<select name="oficina" id="oficina" class="form-control">
															<option value="vigente" -id="89">Pesos</option>
															<option value="suspendida" -id="127">UF</option>
															<option value="en-corretaje" -id="128">Porcentaje</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>
								<button class="btn btn-danger btn-sm">Quitar Item</button>
							</div>
						</div>

					</fieldset>
				</div>



			</div>
			<div class="row g-3 p-0">
				<div class="col-md-12 p-0">
					<fieldset class="form-group border p-3">
						<div>
							<legend>
								<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Referencias</h5>

							</legend>

							<button type="button" class="btn btn-info btn-sm">Agregar Item</button>

						</div>

						<div class="card">
							<div class="card-body">
								<form action="">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="oficina">Tipo Documento</label>
												<select name="oficina" id="oficina" class="form-control">
													<option value="vigente" -id="89">Crédito</option>
													<option value="suspendida" -id="127">Contado</option>
													<option value="en-corretaje" -id="128">Sin Costo</option>
												</select>
											</div>


										</div>

										<div class="col-md-3">
											<label for="fechaIngreso"><span class="obligatorio">*</span> Fecha</label>
											<input name="fechaIngreso" id="fechaIngreso" class="form-control" type="date" value="<?php echo fecha_postgre_a_normal(@$result->fecha_ingreso); ?>" />
											<span id="startDateSelected"></span>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label>Folio</label>
												<input type="number" class="form-control" maxlength="15" min="1" name="avaluo_fiscal" id="avaluo_fiscal" placeholder="Folio" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'<?php echo $_SESSION["cant_decimales"]; ?>','<?php echo $_SESSION["separador_mil"]; ?>');">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>IND</label>
												<input type="text" class="form-control" maxlength="15" min="1" name="avaluo_fiscal" id="avaluo_fiscal" placeholder="IND" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'<?php echo $_SESSION["cant_decimales"]; ?>','<?php echo $_SESSION["separador_mil"]; ?>');">
											</div>
										</div>

										<div class="col-md-6">
											<div class=" form-group">
												<label>Motivo</label>
												<input type="text" class="form-control" maxlength="15" min="1" name="avaluo_fiscal" id="avaluo_fiscal" placeholder="Motivo" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'<?php echo $_SESSION["cant_decimales"]; ?>','<?php echo $_SESSION["separador_mil"]; ?>');">
											</div>
										</div>
									</div>
								</form>
								<button class="btn btn-danger btn-sm">Quitar Item</button>
							</div>
						</div>

					</fieldset>
				</div>



			</div>


		</div>
	</form>







	<input type="hidden" name="token" id="token" value="<?php echo @$result->token; ?>">



	<?php if ($token != "") { ?>
		<div class="row">
			<div class="col-md-12"><br /></div>
		</div>
		<div class="row">
			<?php if ($tiene_check_in == 'N') { ?>
				<a data-fancybox='' data-type='iframe' href='components/propiedad/views/modal_check_in.php?token_propiedad=<?php echo @$result->token; ?>'><i class='far fa-eye'></i> Asignar Check-In</a>
			<?php } else { ?>
				<label>Check-In Asignado</label>
			<?php } ?>
			<div style="clear:both; width:100%;"></div>
			<div class="col-md-12 text-left">
				<?php echo $lista_check_in; ?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12"><br /></div>
		</div>
		<div class="row">
			<input type="hidden" name="participacion" id="participacion" value="<?php echo @participacion_total; ?>">
			<?php if ($participacion_total < 100) { ?>
				<a data-fancybox='' data-type='iframe' href='components/propiedad/views/modal_propietarios.php?token_propiedad=<?php echo @$result->token; ?>&participacion=<?php echo @$participacion_total; ?>'><i class='fas fa-user-plus'></i> Agregar Propietario</a>
			<?php } ?>
			<div style="clear:both; width:100%;"></div>
			<div class="col-md-12 text-left">
				<?php echo $lista_propietarios; ?>
			</div>
		</div>
	<?php } ?>


	<div class="col-lg-12 text-center">
		<a href="<?php echo $nav; ?>">
			<button type="button" class="btn btn-info"> &lt;&lt; volver </button></a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<button type="submit" class="btn btn-danger"> Aceptar </button>
	</div>


	</form>

</div>