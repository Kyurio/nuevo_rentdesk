<form name="formulario-propietario" id="formulario-propietario" method="post" action="javascript: enviarRentdesk();" enctype="multipart/form-data" class="my-3">
	<div class="row g-3">
		<fieldset class="form-group border p-3">
			<div class="row g-3">
				<div class="col-md-3">
					<div class="form-group">
						<label><span class="obligatorio">*</span> Persona</label>
						<?php echo $opcion_persona; ?>
					</div>
				</div>
			</div>
		</fieldset>
		<!-- <fieldset id="section-1" class="form-group border p-3" style="display: none;">
				<div class="row g-3">

					<div class="col-md-2">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Tipo Documento</label>
							<?php echo $opcion_tipo_documento; ?>
						</div>
					</div>

					<div class="col-md-2">

						<div class="form-group">
							<label><span class="obligatorio">*</span> Nro. Documento</label>

							<div class="input-group mb-3">
								<input type="text" class="form-control" placeholder="Nro. Documento" aria-label="Nro. Documento" aria-describedby="button-addon2">
								<button class="btn btn-info m-0" type="button" id="button-addon2">Buscar</button>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Tipo Personalidad</label>
							<?php echo $opcion_tipo_persona_legal; ?>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="estadoCivil"><span class="obligatorio">*</span> Estado Civil</label>
							<select id="estadoCivil" class="form-control">
								<option selected="selected" value="" label="Seleccione" id=""></option>
								<option value="soltero" id="ec1">Soltero/a</option>
								<option value="casado" id="ec2">Casado/a</option>
								<option value="viudo" id="ec3">Viudo/a</option>
								<option value="divorciado" id="ec4">Divorciado/a</option>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Estado</label>
							<?php echo $opcion_estado_persona; ?>
						</div>
					</div>
				</div>
				<div class="row g-3">
					<div class="col-md-4">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Nombre</label>
							<input type="text" class="form-control" maxlength="250" name="nombre" id="nombre" placeholder="Nombre" required data-validation-required value="<?php echo @$result->nombre; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label>Ap. Paterno</label>
							<input type="text" class="form-control" maxlength="250" name="apellidoPat" id="apellidoPat" placeholder="Apellido paterno" value="<?php echo @$result->apellidoPat; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label>Ap. Materno</label>
							<input type="text" class="form-control" maxlength="250" name="apellidoMat" id="apellidoMat" placeholder="Apellido materno" value="<?php echo @$result->apellidoMat; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
				</div>
				<div class="row g-3">
					<div class="col-md-4">
						<div class="form-group">
							<label>Fono</label>
							<input type="text" class="form-control" maxlength="50" name="fono" id="fono" placeholder="Fono" value="<?php echo @$result->fono; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Celular</label>
							<input type="text" class="form-control" maxlength="50" name="celular" id="celular" placeholder="Celular" value="<?php echo @$result->celular; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Email</label>
							<input type="email" class="form-control" maxlength="250" name="email" id="email" placeholder="Email" value="<?php echo @$result->email; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
				</div>
			</fieldset> -->
		<!-- <fieldset id="section-2" class="form-group border p-3" style="display: none;">
				<legend>
					<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Dirección Particular</h5>
				</legend>
				<div class="row g-3">
					<div class="col-lg-4 form-group">
						<label><span class="obligatorio">*</span>Pais</label>
						<div id="divpais"></div>
						<input type="hidden" id="hiddenpais" name="hiddenpais" value="<?php echo @$pais; ?>">
					</div>

					<div class="col-lg-4 form-group">
						<label><span class="obligatorio">*</span>Región</label>
						<div id="divregion"></div>
						<input type="hidden" id="hiddenregion" name="hiddenregion" value="<?php echo @$region; ?>">
					</div>

					<div class="col-lg-4 form-group">
						<label><span class="obligatorio">*</span>Comuna</label>
						<div id="divcomuna"></div>
						<input type="hidden" id="hiddencomuna" name="hiddencomuna" value="<?php echo @$comuna; ?>">
					</div>
				</div>
				<div class="row g-3">
					<div class="col-md-12">
						<div class="form-group">
							<label>Dirección</label>
							<input type="text" class="form-control" maxlength="500" name="direccion" id="direccion" placeholder="Dirección" value="<?php echo @$result->direccion; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
				</div>
			</fieldset> -->
		<!-- <fieldset id="section-3" class="form-group border p-3" style="display: none;">
				<legend>
					<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Dirección Comercial</h5>
				</legend>
				<div class="row g-3">
					<div class="col-lg-4 form-group">
						<label>Pais</label>
						<div id="divpaiscom"></div>
						<input type="hidden" id="hiddenpaiscom" name="hiddenpaiscom" value="<?php echo @$paisCom; ?>">
					</div>

					<div class="col-lg-4 form-group">
						<label>Región</label>
						<div id="divregioncom"></div>
						<input type="hidden" id="hiddenregioncom" name="hiddenregioncom" value="<?php echo @$regionCom; ?>">
					</div>

					<div class="col-lg-4 form-group">
						<label>Comuna</label>
						<div id="divcomunacom"></div>
						<input type="hidden" id="hiddencomunacom" name="hiddencomunacom" value="<?php echo @$comunaCom; ?>">
					</div>
				</div>
				<div class="row g-3">
					<div class="col-md-12">
						<div class="form-group">
							<label>Dirección</label>
							<input type="text" class="form-control" maxlength="500" name="direccion" id="direccion" placeholder="Dirección" value="<?php echo @$result->direccionCom; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
				</div>
			</fieldset> -->
		<fieldset id="section-4" class="form-group border p-3" style="display: none;">
			<legend>
				<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Cuenta Bancaria</h5>
			</legend>
			<div class="row g-3">
				<div class="col-md">
					<fieldset class="form-group border-0 p-3">
						<legend>
							<h5 class="mt-0" style="font-size:12px !important;margin-bottom:5px !important;">Datos de Identificación</h5>
						</legend>
						<div class="row g-3">
							<div class="col-lg-6">
								<div class="form-group">
									<label><span class="obligatorio">*</span> Nombre Titular</label>
									<input type="text" class="form-control" maxlength="100" name="nombreTitular" id="nombreTitular" placeholder="Nombre Titular" required data-validation-required value="<?php echo @$result->numCuenta; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label><span class="obligatorio">*</span> Email Titular</label>
									<input type="text" class="form-control" maxlength="100" name="emailTitular" id="emailTitular" placeholder="Email Titular" required data-validation-required value="<?php echo @$result->propietario->cuentasBancarias[0]->correoElectronico; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="estadoCivil"><span class="obligatorio">*</span> Tipo de Documento</label>
									<!-- <?php echo $opcion_destino; ?> -->
									<select id="estadoCivil" class="form-control">
										<option selected="selected" value="" label="Seleccione" id=""></option>
										<option value="rut" id="td1">RUT</option>

									</select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label><span class="obligatorio">*</span> Número de Identificación</label>
									<input type="text" class="form-control" maxlength="100" name="numIdentifiacion" id="numIdentifiacion" placeholder="Número de Identificación" required data-validation-required value="<?php echo @$result->numCuenta; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="col-md">
					<fieldset class="form-group border-0 p-3">
						<legend>
							<h5 class="mt-0" style="font-size:12px !important;margin-bottom:5px !important;">Datos Bancarios</h5>
						</legend>
						<div class="row g-3">
							<div class="col-lg-4">
								<div class="form-group">
									<label for="banco"><span class="obligatorio">*</span> Banco</label>
									<?php echo $opcion_banco; ?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label for="cta-banco"><span class="obligatorio">*</span> Tipo de Cuenta</label>
									<?php echo $opcion_cta_banco; ?>

								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label><span class="obligatorio">*</span> Número de Cuenta</label>
									<input type="text" class="form-control" maxlength="100" name="numCuenta" id="numCuenta" placeholder="Número Cuenta" required data-validation-required value="<?php echo @$result->propietario->cuentasBancarias[0]->numero; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
			<div class="row g-3">
				<div class="form-group"></div>
				<input type="hidden" name="token" id="token" value="<?php echo @$result->token; ?>">
			</div>

		</fieldset>
	</div>
	<div class="row g-3">
		<div class="col-lg-12 text-center">
			<a href="<?php echo $nav; ?>">
				<button type="button" class="btn btn-info"> &lt;&lt; volver </button></a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button type="submit" class="btn btn-danger" form="formulario-propietario"> Aceptar </button>
		</div>
	</div>


</form>