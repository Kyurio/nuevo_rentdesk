<script src="js/region_ciudad_comuna.js"></script>

<?php
if ($flag_solo_rut == 1) {
?>
	<script>
		$(document).ready(function() {

			setTimeout(() => {
				$("#tipo_documento").val(1);
				$("#tipo_documento_repre").val(1);
			}, 1000);
		});
	</script>
<?php
}
?>

<div id="header" class="header-page">
	<div>
		<!-- <h2 class="mb-3">Propietario</h2> -->
		<div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
			<ol class="breadcrumb d-flex align-items-center m-0">
				<li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
				<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=persona&view=persona_list" style="text-decoration: none;color:#66615b">Cliente</a></li>
				<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Cliente</li>
			</ol>
		</div>
	</div>
</div>

<div class="content content-page">

	<div>
		<span>
			<span class="obligatorio">*</span> Indica Campos Obligatorios
		</span>
		<p id="textoRegistroCliente"></p>
	</div>
	<?php
	if (isset($token)) {
	?>
		<form name="formulario" id="formulario" method="post" action="javascript: editarCliente();" enctype="multipart/form-data" class="my-3">
		<?php
	} else {
		?>
			<form name="formulario" id="formulario" method="post" action="javascript: guardarCliente();" enctype="multipart/form-data" class="my-3">
			<?php
		}
			?>
			<div class="row g-3">
				<fieldset class="form-group border p-3">
					<div class="row g-3">
						<!-- <div class="col-md-2">
						<div class="form-group">
							<label for="tipos"><span class="obligatorio">*</span> Tipo</label>
							<select class="form-control js-example-responsive" data-select2-id="tipos" name="tipos[]" multiple="multiple">
								<option value="1" data-select2-id="ta1">Propietario</option>
								<option value="2" data-select2-id="ta2">Arrendatario</option>
								<option value="2" data-select2-id="ta3">Codeudor</option>
							</select>
						</div>
					</div> -->

						<div class="col-md-3" style="<?php if ($flag_solo_rut == 1) {
															echo "display: none";
														} ?>">
							<div class="form-group">
								<label><span class="obligatorio">*</span> Tipo Documento</label>
								<?php echo $opcion_tipo_documento; ?>
							</div>
						</div>
												
						<?php if($_GET['token']): ?>
						<div class="col-md-3">
							<div class="form-group">
								<label><span class="obligatorio">*</span> <?php if ($flag_solo_rut != 1) { ?> Nro. Documento /<?php } ?> RUT</label>
								<span id="cuentaDni" class="conteo-input">0/50</span>
								<input type="text" class="form-control" maxlength="50" name="dniEditar" id="dniEditar" placeholder="RUT" value="<?php echo $result->dni; ?>" readonly>
								<div id="errorrut"></div>

								
							</div>
						</div>
						<?php else: ?>
							<div class="col-md-3">
							<div class="form-group">
								<label><span class="obligatorio">*</span> <?php if ($flag_solo_rut != 1) { ?> Nro. Documento /<?php } ?> RUT</label>
								<span id="cuentaDni" class="conteo-input">0/50</span>
								<input type="text" class="form-control" maxlength="50" name="dni" id="dni" oninput="conteoInput('dni','cuentaDni');<?php if ($flag_solo_rut == 1) {
																																						echo "checkRut(this);";
																																					} ?>" placeholder=" <?php if ($flag_solo_rut != 1) { ?> Nro. Documento /<?php } ?> RUT" required data-validation-required value="<?php echo @$result->dni; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);buscarpersona(this.value);">
								<div id="errorrut"></div>
							</div>
						</div>
						<?php endif; ?>

						<!-- <div class="col-md-2">
							<div class="form-group">
												<label>Dig. Verif.</label>
												<input type="text" class="form-control" maxlength="1" name="digitoVerificador" id="digitoVerificador" placeholder="DV" value="<?php echo @$result->digitoVerificador; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
											</div>
										</div> -->
						<div class="col-md-3">
							<div class="form-group">
								<label><span class="obligatorio">*</span> Tipo Persona</label>
								<?php echo $opcion_tipo_persona_legal; ?>
							</div>
						</div>

					</div>
				</fieldset>

				<fieldset class="form-group border p-3" id="containerInfoPersona" style="display: none;">


					<div class="row g-3" id="tipoPersona2Section" style="display: none;">
						<div class="col-md-4">
							<div class="form-group">
								<label><span class="obligatorio">*</span> Giro</label>
								<span id="cuentaGiro" class="conteo-input">0/100</span>
								<input type="text" class="form-control" maxlength="100" name="giro" id="giro" oninput="conteoInput('giro','cuentaGiro');" placeholder="Giro" required data-validation-required value="<?php echo @$result->datosJuridica->giro; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Nombre Fantasía</label>
								<span id="cuentaFantasia" class="conteo-input">0/150</span>
								<input type="text" class="form-control" maxlength="150" name="nombreFantasia" id="nombreFantasia" oninput="conteoInput('nombreFantasia','cuentaFantasia');" placeholder="Nombre Fantasía" value="<?php echo @$result->datosJuridica->nombreFantasia; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Razón Social</label>
								<span id="cuentaRazon" class="conteo-input">0/250</span>
								<input type="text" class="form-control" maxlength="250" name="razonSocial" id="razonSocial" oninput="conteoInput('razonSocial','cuentaRazon');" placeholder="Razón Social" value="<?php echo @$result->datosJuridica->razonSocial; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
					</div>
					<div class="row g-3" id="tipoPersona1Section" style="display: none;">
						<div class="col-md-4">
							<div class="form-group">
								<label><span class="obligatorio">*</span> Nombre</label>
								<span id="cuentaNombre" class="conteo-input">0/60</span>
								<input type="text" class="form-control" maxlength="60" name="nombre" id="nombre" oninput="conteoInput('nombre','cuentaNombre');" placeholder="Nombre" required data-validation-required value="<?php echo @$result->datosNatural->nombres; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Ap. Paterno</label>
								<span id="cuentaPaterno" class="conteo-input">0/60</span>
								<input type="text" class="form-control" maxlength="60" name="apellidoPat" id="apellidoPat" oninput="conteoInput('apellidoPat','cuentaPaterno');" placeholder="Apellido paterno" value="<?php echo @$result->datosNatural->apellidoPaterno; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Ap. Materno</label>
								<span id="cuentaMaterno" class="conteo-input">0/60</span>
								<input type="text" class="form-control" maxlength="60" name="apellidoMat" id="apellidoMat" oninput="conteoInput('apellidoMat','cuentaMaterno');" placeholder="Apellido materno" value="<?php echo @$result->datosNatural->apellidoMaterno; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
					</div>

					<div class="row g-3" id="datosContacto" style="display:none;">
						<div class="col-md-4">
							<div class="form-group">
								<label for="telefonoFijo">Teléfono Fijo</label>
								<span id="cuentaTelefonoFijo" class="conteo-input">0/30</span>
								<input type="text" class="form-control" maxlength="30" name="telefonoFijo" id="telefonoFijo" oninput="conteoInput('telefonoFijo','cuentaTelefonoFijo');" placeholder="Teléfono Fijo" value="<?php echo @$result->telefonoFijo; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="telefonoMovil">Teléfono Móvil</label>
								<span id="cuentaMovil" class="conteo-input">0/30</span>
								<input type="text" class="form-control" maxlength="30" name="telefonoMovil" id="telefonoMovil" oninput="conteoInput('telefonoMovil','cuentaMovil');" placeholder="Teléfono Móvil" value="<?php echo @$result->telefonoMovil; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Correo Electrónico</label>
								<span id="cuentaCorreo" class="conteo-input">0/30</span>
								<input type="email" class="form-control" maxlength="250" name="correoElectronico" id="correoElectronico" oninput="conteoInput('correoElectronico','cuentaCorreo');" placeholder="Correo Electrónico" value="<?php echo @$result->correoElectronico; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
					</div>

					<div class="row g-3" id="DatosPersonaNatural" style="display: none;">

						<div class="col-md-4">
							<label for="fechaNacimiento">Fecha Nacimiento</label>
							<input name="fechaNacimiento" id="fechaNacimiento" class="form-control" type="date" value="<?php echo substr($result->datosNatural->fechaNacimiento, 0, 10) ?>" />
							<span id="startDateSelected"></span>
						</div>




						<div class="col-md-4" id="estadoCivil" style="display: none;">
							<div class="form-group">
								<label for="estado_civil">Estado Civil</label>
								<?php echo $opcion_estado_civil; ?>
								<!-- <select id="estadoCivil" name="estadoCivil" class="form-control">
								<option selected="selected" value="" label="Seleccione" id=""></option>
								<option value="soltero" id="ec1">Soltero/a</option>
								<option value="casado" id="ec2">Casado/a</option>
								<option value="viudo" id="ec3">Viudo/a</option>
								<option value="divorciado" id="ec4">Divorciado/a</option>
							</select> -->
							</div>
						</div>
						<!-- <div class="col-md-4">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Estado</label>
							<?php echo $opcion_estado_persona; ?>
						</div>
					</div> -->




					</div>
				</fieldset>
				<fieldset class="form-group border p-3" id="containerInfoPersonaDireccion" style="display: none;">

					<div class="row g-3">


						<div class="col-lg-3 form-group">
							<label><span class="obligatorio">*</span> País</label>
							<div id="divpais"></div>
							<input type="hidden" id="hiddenpais" name="hiddenpais" value="<?php echo @$pais; ?>">
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



					<div class="row g-3">
						<div class="col-md-6">
							<div class="form-group">
								<label for="direccion"><span class="obligatorio">*</span> Dirección</label>
								<span id="cuentaDireccion" class="conteo-input">0/250</span>
								<input type="text" name="direccion" id="direccion" class="form-control" maxlength="250" oninput="conteoInput('direccion','cuentaDireccion');" placeholder="Dirección" required value="<?php echo @$result->direcciones[0]->direccion; ?>">
							</div>
						</div>


						<div class="col-md-2">
							<div class="form-group">
								<label for="nroComplemento">Nro.</label>
								<span id="cuentaNumero" class="conteo-input">0/8</span>
								<input type="text" class="form-control" maxlength="8" name="nroComplemento" id="nroComplemento" oninput="conteoInput('nroComplemento','cuentaNumero');" placeholder="Nro." value="<?php echo @$result->direcciones[0]->numero; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>


					</div>
					<!-- 
				<div class="row g-3">
				
					<div class="col-lg-3 form-group">
						<label>Tipo propiedad</label>
						<div id="divTipoPropiedad"></div>
						<?php // echo$opcion_tipo_propiedad; 
						?>
					</div>
				
					<div class="col-lg-3 form-group">
						<label> Comentario Adicional 1</label>
						<span id="cuentaComplemento" class="conteo-input">0/100</span>
						<div id="divComplemento"></div>
						<input type="text" class="form-control" maxlength="100" name="complemento" id="complemento" oninput="conteoInput('complemento','cuentaComplemento');"  value="<?php echo @$direccion_comentario; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
					</div>
					<div class="col-lg-3 form-group">
						<label> Comentario Adicional 2</label>
						<span id="cuentaInformacionAdicional" class="conteo-input">0/100</span>
						<div id="divInformacionAdicional"></div>
						<input type="text" class="form-control" maxlength="100" name="InformacionAdicional" id="InformacionAdicional" oninput="conteoInput('InformacionAdicional','cuentaInformacionAdicional');"   value="<?php echo @$direccion_comentario2; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">					
					</div>
				</div>
					-->




				</fieldset>

			</div>

			<div id="representante-juridico" style="display:none">
				<span>
					<span class="obligatorio">*</span> Representante Legal
				</span>
				<br><br>
				<fieldset class="form-group border p-3" id="containerRepresentante" style="display:none">
					<button id="addRepresentante" type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Agregar Documento" data-bs-toggle="modal" data-bs-target="#modalRepresentante">
						<span>Agregar Representante Legal</span>
					</button>
					<!--- Modal del representante Legal  -->
					<div class="modal fade" id="modalRepresentante" tabindex="-1" aria-labelledby="modalRepresentanteLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="modalRepresentanteLabel">Ingreso Representante</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<div id="container" class="container mt-5">
										<div class="progress px-1" style="height: 3px;">
											<div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
										<div class="step-container d-flex justify-content-between">
											<div class="step-circle" onclick="displayStep(1)">1</div>
											<div class="step-circle" onclick="displayStep(2)">2</div>
											<!-- <div class="step-circle" onclick="displayStep(3)">3</div> -->
										</div>

										<div class="step step-1">
											<!-- Step 1 form fields here -->
											<div class="mb-3">
												<div class="row">
													<div class="col-4" style="<?php if ($flag_solo_rut == 1) {
																					echo "display: none";
																				} ?>">
														<label for="field1" class="form-label"> Tipo Documento:</label>
														<?php echo $opcion_tipo_documento_repre ?>
													</div>
													<div class="col-8">
														<label for="field1" class="form-label"> <?php if ($flag_solo_rut != 1) { ?> Nro. Documento /<?php } ?> RUT</label>
														<input type="text" class="form-control" id="NDocumento" name="NDocumento" oninput="<?php if ($flag_solo_rut == 1) {
																																				echo "checkRut(this);";
																																			} ?>" form="Form2">
													</div>
												</div>

											</div>
											<button type="button" id="buscaPersona" onclick="BuscarPersona()" class="btn btn-primary next-step">Siguiente</button>
										</div>

										<div class="step step-2" style="display: none;">
											<!-- Step 2 form fields here -->
											<div class="mb-3">
												<div class="row">
													<div class="col">
														<div class="form-group">
															<label>Rut | DNI:</label>
															<br>
															<label id="dniRepresentante" style="padding: 10px 0 10px 0 ">aca va el rut </label>
															<input type="hidden" id="dniRepre" form="Form2" value="" name="dniRepre">
														</div>
													</div>
													<div class="col">
														<div class="form-group">
															<label><span class="obligatorio">*</span> Nombre</label>
															<input type="text" class="form-control" maxlength="60" name="nombreRepresentante" id="nombreRepresentante" oninput="conteoInput('nombre','cuentaNombre');" placeholder="Nombre" data-validation-required="" value="" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" form="Form2">
															<div id="LabelnombreRepresentante" style="display:none; padding: 10px 0 10px 0 "></div>
														</div>
													</div>

												</div>
												<div class="row">
													<div class="col">
														<div class="form-group">
															<label>Ap. Paterno</label>
															<span id="cuentaPaterno" class="conteo-input">0/60</span>
															<input type="text" class="form-control" maxlength="60" name="apellidoPateRepresentante" id="apellidoPateRepresentante" oninput="conteoInput('apellidoPat','cuentaPaterno');" placeholder="Apellido paterno" value="" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" form="Form2">
															<div id="LabelapellidoPateRepresentante" style="display:none; padding: 10px 0 10px 0 "></div>
														</div>
													</div>
													<div class="col">
														<div class="form-group">
															<label>Ap. Materno</label>
															<span id="cuentaMaterno" class="conteo-input">0/60</span>
															<input type="text" class="form-control" maxlength="60" name="apellidoMateRepresentante" id="apellidoMateRepresentante" oninput="conteoInput('apellidoMat','cuentaMaterno');" placeholder="Apellido materno" value="" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" form="Form2">
															<div id="LabelapellidoMateRepresentante" style="display:none; padding: 10px 0 10px 0 "></div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col">
														<div class="form-group">
															<label for="telefonoFijo">Teléfono Fijo</label>
															<span id="cuentaTelefonoFijo" class="conteo-input">0/30</span>
															<input type="text" class="form-control" maxlength="30" name="telefonoFijoRepresentante" id="telefonoFijoRepresentante" oninput="conteoInput('telefonoFijo','cuentaTelefonoFijo');" placeholder="Teléfono Fijo" value="<?php echo @$result->telefonoFijo; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" form="Form2">
															<div id="LabeltelefonoFijoRepresentante" style="display:none; padding: 10px 0 10px 0 "></div>
														</div>
													</div>
													<div class="col">
														<div class="form-group">
															<label for="telefonoMovil">Teléfono Móvil</label>
															<span id="cuentaMovil" class="conteo-input">0/30</span>
															<input type="text" class="form-control" maxlength="30" name="telefonoMovilRepresentante" id="telefonoMovilRepresentante" oninput="conteoInput('telefonoMovil','cuentaMovil');" placeholder="Teléfono Móvil" value="<?php echo @$result->telefonoMovil; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" form="Form2">
															<div id="LabeltelefonoMovilRepresentante" style="display:none; padding: 10px 0 10px 0 "></div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col">
														<div class="form-group">
															<label>Correo Electrónico</label>
															<input type="email" class="form-control" maxlength="250" name="correoElectronicoRepresentante" id="correoElectronicoRepresentante" oninput="conteoInput('correoElectronico','cuentaCorreo');" placeholder="Correo Electrónico" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" form="Form2">
															<div id="LabelcorreoElectronicoRepresentante" style="display:none; padding: 10px 0 10px 0;"></div>
														</div>
													</div>
													<div class="col">
														<div class="form-group">
															<label><span class="obligatorio">*</span> País</label>
															<?php echo $selectpais ?>
															<div id="LabelpaisRepresentante" style="display:none; padding: 10px 0 10px 0;"></div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col">
														<div class="form-group">
															<label><span class="obligatorio">*</span> Región</label>
															<select name="regionRepresentante" id="regionRepresentante" class="form-control form-select" onchange="selectUbicacion('region')" disabled form="Form2">
																<option value="">Antes Seleccione un País</option>
															</select>
															<div id="LabelregionRepresentante" style="display:none; padding: 10px 0 10px 0;"></div>
														</div>
													</div>
													<div class="col">
														<div class="form-group">
															<label><span class="obligatorio">*</span> Comuna</label>
															<select name="comunaRepresentante" id="comunaRepresentante" class="form-control form-select" disabled form="Form2">
																<option value="">Antes Seleccione un Región</option>
															</select>
															<div id="LabelcomunaRepresentante" style="display:none; padding: 10px 0 10px 0;"></div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col">
														<div class="form-group">
															<label for="direccion"><span class="obligatorio">*</span> Dirección</label>
															<input type="text" class="form-control" maxlength="250" name="direccionRepresentante" id="direccionRepresentante" placeholder="Dirección" form="Form2">
															<div id="LabeldireccionRepresentante" style="display:none; padding: 10px 0 10px 0;"></div>
														</div>
													</div>
													<div class="col">
														<div class="form-group">
															<label for="nroComplemento">Nro.</label>
															<input type="number" class="form-control" maxlength="250" name="numeroRepresentante" id="numeroRepresentante" placeholder="Numero" form="Form2">
															<div id="LabelnumeroRepresentante" style="display:none; padding: 10px 0 10px 0;"></div>
														</div>
													</div>
												</div>
											</div>
											<input type="hidden" id="hiddenToken" name="hiddenToken" value="" form="Form2">
											<input type="hidden" id="hiddenRepre" name="hiddenRepre" value="" form="Form2">
											<button type="button" class="btn btn-primary prev-step" id="backRepre">Volver</button>
											<button type="button" id="bottonGuardaRepre" onclick="guardarRepresentante()" class="btn btn-success">Añadir Representante</button>
										</div>

										<!--<div class="step step-3" style="display: none;">
                        		 Step 3 form fields here 
                        		<div class="mb-3">
                            		<label for="field3" class="form-label">Field 3:</label>
                            		<input type="text" class="form-control" id="field3" name="field3"  form="Form2">
                        		</div>
                        		<button type="button" class="btn btn-primary prev-step">Volver</button>
                        		<button type="submit" class="btn btn-success">Guardar</button>
                    		</div>
								-->
									</div>
								</div>

							</div>
						</div>
					</div>
					<div class="table-responsive overflow-auto">
						<table id="rLegal" class="table table-striped" cellspacing="0" width="100%" style="display: none">

							<thead>
								<tr>
									<!-- <th>Tipo</th> -->
									<th>Nombre</th>
									<th>Nro. Documento</th>
									<th>Correo Electrónico</th>
									<th>Dirección</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>




				</fieldset>
			</div>

			<fieldset class="form-group border p-3" id="containerCuenta" style="display:none">
				<div class="row g-3">
					<div class="col-md">
						<fieldset class="form-group border-0 p-3">
							<legend>
								<h5 class="mt-0" style="font-size:12px !important;margin-bottom:5px !important;">Datos Cuenta</h5>
							</legend>
							<div class="row g-3">
								<div class="col-lg-4">
									<div class="form-group">
										<label><span class="obligatorio">*</span> Nombre Titular</label>
										<input type="text" class="form-control" maxlength="100" name="nombreTitular" id="nombreTitular" placeholder="Nombre Titular" required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label><span class="obligatorio">*</span> RUT</label>
										<input type="text" class="form-control" maxlength="100" name="rutTitular" id="rutTitular" oninput="checkRut(this);" placeholder="Número de Identificación" required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
									</div>
								</div>

								<div class="col-lg-4">
									<div class="form-group">
										<label><span class="obligatorio">*</span> Email Titular</label>
										<input type="text" class="form-control" maxlength="100" name="emailTitular" id="emailTitular" placeholder="Email Titular" required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
									</div>
								</div>

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
										<input type="text" class="form-control" maxlength="100" name="numCuenta" id="numCuenta" placeholder="Número Cuenta" required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
									</div>
								</div>
							</div>

						</fieldset>
					</div>
				</div>

			</fieldset>

			<div class="row g-3">
				<div class="col-lg-12 text-center" style="margin-top:40px; margin-bottom:60px;">

					<button type="button" class="btn btn-info" onclick="window.history.back();"> volver </button>

					<button type="submit" class="btn btn-danger"> Aceptar </button>
				</div>
			</div>

			<input type="hidden" id="token" name="token" value="<?php echo @$_GET['token']; ?>">
			<input type="hidden" id="hiddenRepresentante" name="hiddenRepresentante" value="">
			<input type="hidden" id="hiddenregion" name="hiddenregion" value="<?php echo @$pais; ?>">
			<input type="hidden" id="hiddenregion" name="hiddenregion" value="<?php echo @$region; ?>">
			<input type="hidden" id="hiddenregion" name="hiddenregion" value="<?php echo @$comuna; ?>">


			</form>
</div>


<script>
	$(document).ready(function() {
		<?php echo @$loadPaisComunaRegion; ?>
	});
</script>