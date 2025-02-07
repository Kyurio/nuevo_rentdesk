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
			<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propiedad&view=propiedad_list" style="text-decoration: none;color:#66615b">Propiedades</a></li>
			<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Propiedad</li>
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
								<label><span class="obligatorio">*</span> Tipo Propiedad</label>
								<?php echo $opcion_tipo_propiedad; ?>
							</div>
						</div>
						<div class="col-md-3">
							<!-- <div class="form-group">
							<label><span class="obligatorio">*</span> Estado</label>
							<?php echo $opcion_estado_propiedad; ?>
						</div> -->
							<div class="form-group">
								<label for="oficina"><span class="obligatorio">*</span> Estado</label>
								<select name="oficina" id="oficina" class="form-control">
									<option value="vigente" -id="89">Vigente</option>
									<option value="suspendida" -id="127">Suspendida</option>
									<option value="en-corretaje" -id="128">En corretaje</option>
									<option value="en-reparación" -id="129">En reparación</option>
									<option value="retirada" -id="129">Retirada</option>

								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="oficina"><span class="obligatorio">*</span> Oficina</label>
								<select name="oficina" id="oficina" class="form-control">
									<option value="CENTRO-MONEDA" -id="89">CENTRO-MONEDA</option>
									<option value="NUEVA LAS CONDES" -id="127">NUEVA LAS CONDES</option>
									<option value="LA SERENA" -id="128">LA SERENA</option>
									<option value="PROVIDENCIA" -id="129">PROVIDENCIA</option>
									<option value="REÑACA" -id="130">REÑACA</option>
									<option value="SAN MIGUEL" -id="131">SAN MIGUEL</option>
									<option value="ADMINISTRACIONES" -id="132">ADMINISTRACIONES</option>
									<option value="MAIPU PLAZA" -id="133">MAIPU PLAZA</option>
									<option value="SANTIAGO-CENTRO" -id="134">SANTIAGO-CENTRO</option>
									<option value="OFICINA PLAN B (SEGURO)" -id="135">OFICINA PLAN B (SEGURO)</option>
									<option value="MAIPU PAJARITOS" -id="136">MAIPU PAJARITOS</option>
									<option value="TALAGANTE" -id="137">TALAGANTE</option>
									<option value="PLAZA EGAÑA" -id="138">PLAZA EGAÑA</option>
									<option value="ROSARIO SUR" -id="139">ROSARIO SUR</option>
									<option value="LA FLORIDA" -id="140">LA FLORIDA</option>
									<option value="ISABEL LA CATOLICA" -id="141">ISABEL LA CATOLICA</option>
									<option value="VITACURA" -id="142">VITACURA</option>
									<option value="VICUÑA MACKENNA" -id="143">VICUÑA MACKENNA</option>
									<option value="LA REINA" -id="144">LA REINA</option>
									<option value="PUENTE ALTO" -id="145">PUENTE ALTO</option>
									<option value="NULL" -id="146">NULL</option>
									<option value="BULNES" -id="147">BULNES</option>
									<option value="ÑUÑOA" -id="148">ÑUÑOA</option>
									<option value="APOQUINDO" -id="149">APOQUINDO</option>
									<option value="LOS DOMINICOS" -id="150">LOS DOMINICOS</option>
									<option value="CONCEPCION" -id="151">CONCEPCION</option>
									<option value="NUEVA COSTANERA" -id="152">NUEVA COSTANERA</option>
									<option value="LAS CONDES" -id="153">LAS CONDES</option>
									<option value="LAS TRANQUERAS" -id="154">LAS TRANQUERAS</option>
									<option value="ANA MARIA DUQUE" -id="155">ANA MARIA DUQUE</option>
									<option value="PEÑALOLEN" -id="156">PEÑALOLEN</option>
									<option value="ESCUELA MILITAR" -id="157">ESCUELA MILITAR</option>
									<option value="LA DEHESA" -id="158">LA DEHESA</option>
									<option value="E-MAIL" -id="159">E-MAIL</option>
									<option value="TABANCURA" -id="160">TABANCURA</option>
									<option value="LOS MILITARES" -id="161">LOS MILITARES</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<label for="fechaIngreso">Fecha Ingreso</label>
							<input name="fechaIngreso" id="fechaIngreso" class="form-control" type="date" value="<?php echo fecha_postgre_a_normal(@$result->fecha_ingreso); ?>" />
							<span id="startDateSelected"></span>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="col-md-12 p-0">
				<fieldset class="form-group border p-3">



					<div class="row g-3">
						<div class="col-md-4">
							<div class="form-group">
								<label><span class="obligatorio">*</span> Dirección</label>
								<input type="text" class="form-control" maxlength="250" name="direccion" id="direccion" placeholder="Dirección" required data-validation-required value="<?php echo @$result->direccion; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label for="complemento">Complemento</label>
								<select name="complemento" id="complemento" class="form-control">
									<!-- <?php echo $opcion_terreno; ?> -->
									<option value="" label=" " id="107"></option>
									<option selected="selected" value="Casa" id="52">Casa</option>
									<option value="Departamento" id="108">Departamento</option>
									<option value="Oficina" id="109">Oficina</option>
									<option value="Local Comercial" id="110">Local Comercial</option>
									<option value="Estacionamiento" id="111">Estacionamiento</option>
									<option value="Sitio" id="112">Sitio</option>
									<option value="Parcela" id="113">Parcela</option>
									<option value="Industrial" id="114">Industrial</option>
									<option value="Bodega" id="115">Bodega</option>
									<option value="Terreno Construcción" id="116">Terreno Construcción</option>
									<option value="Agrícola" id="117">Agrícola</option>
									<option value="Bungalow" id="118">Bungalow</option>
									<option value="Galpón" id="119">Galpón</option>
									<option value="Chalet" id="120">Chalet</option>
									<option value="Matriz" id="121">Matriz</option>
									<option value="Colectiva" id="122">Colectiva</option>
									<option value="Terreno" id="123">Terreno</option>
									<option value="Habitación" id="124">Habitación</option>
								</select>

							</div>





						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Nro.</label>
								<input type="text" class="form-control" maxlength="250" name="numero" id="numero" placeholder="Nro." value="<?php echo @$result->numero; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>Depto</label>
								<input type="text" class="form-control" maxlength="250" name="numeroDepto" id="numeroDepto" placeholder="N° depto." value="<?php echo @$result->numero_depto; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>


						<div class="col-md-2">
							<div class="form-group">
								<label>Piso</label>
								<input type="number" class="form-control" maxlength="2" name="piso" id="piso" placeholder="0" value="<?php echo @$result->piso; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>



					</div>




					<div class="row g-3">
						<div class="col-md-3">
							<div class="form-group">
								<label>Coordenadas</label>
								<input type="text" class="form-control" maxlength="100" name="coordenadas" id="coordenadas" placeholder="Cordenadas GPS" value="<?php echo @$result->coordenadas; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

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

				</fieldset>
			</div>
			<div class="col-md-12 p-0">
				<fieldset class="form-group border p-3">

					<div class="row g-3">

						<div class="col-sm-2">
							<div class="form-group">
								<label>M2</label>
								<input type="number" class="form-control" placeholder="0" min="0">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label>Edificado</label>
								<select name="edificado" id="edificado" class="form-control">
									<?php echo $opcion_edificado; ?>
								</select>
							</div>
						</div>

						<div class="col-sm-2">
							<div class="form-group">
								<label>Dormitorios</label>
								<input type="number" class="form-control" maxlength="2" name="dormitorios" id="dormitorios" placeholder="0" value="<?php echo @$result->dormitorios; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label>Dorm. Servicio</label>
								<input type="number" class="form-control" maxlength="2" name="dormitoriosServicio" id="dormitoriosServicio" placeholder="0" value="<?php echo @$result->dormitorios_servicio; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label>Baños</label>
								<input type="number" class="form-control" maxlength="2" name="banos" id="banos" placeholder="0" value="<?php echo @$result->banos; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label>Baños Visita</label>
								<input type="number" class="form-control" maxlength="2" name="banosVisita" id="banosVisita" placeholder="0" value="<?php echo @$result->banos_visita; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

						<div class="col-sm-2">
							<div class="form-group">
								<label>Estacionamientos</label>
								<input type="number" class="form-control" maxlength="2" name="estacionamientos" id="estacionamientos" placeholder="0" value="<?php echo @$result->estacionamientos; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label>Bodegas</label>
								<input type="number" class="form-control" maxlength="2" name="bodegas" id="bodegas" placeholder="0" value="<?php echo @$result->bodegas; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label>Logia</label>
								<input type="number" class="form-control" maxlength="2" name="logia" id="logia" placeholder="0" value="<?php echo @$result->logia; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label>Piscina?</label>
								<select name="piscina" id="piscina" class="form-control">
									<?php echo $opcion_piscina; ?>
								</select>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="row g-3 p-0">
				<div class="col-md-12 p-0">
					<fieldset class="form-group border p-3">
						<legend>
							<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Información Fiscal y Declaración Anual de Bienes Raíces</h5>
						</legend>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label><span class="obligatorio">*</span> Rol</label>
									<input type="text" class="form-control" maxlength="250" name="rol" id="rol" placeholder="Rol" required data-validation-required value="<?php echo @$result->rol; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Avaluo Fiscal</label>
									<input type="text" class="form-control" maxlength="15" min="1" name="avaluo_fiscal" id="avaluo_fiscal" placeholder="0" value="<?php echo formatea_number(@$result->avaluo_fiscal, $_SESSION["cant_decimales"], $_SESSION["separador_mil"]); ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'<?php echo $_SESSION["cant_decimales"]; ?>','<?php echo $_SESSION["separador_mil"]; ?>');">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Amoblado?</label>
									<select name="amoblado" id="amoblado" class="form-control">
										<?php echo $opcion_amoblado; ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>DFL2?</label>
									<select name="dfl2" id="dfl2" class="form-control">
										<?php echo $opcion_dfl2; ?>
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="destino-arriendo"><span class="obligatorio">*</span> Destino Arriendo</label>
									<!-- <?php echo $opcion_destino; ?> -->
									<select id="destino-arriendo" class="form-control" placeholder="Elige un destino del bien raíz">
										<option value="" label="Seleccione" id=""></option>
										<option value="housing" id="164">Habitacional</option>
										<option value="commercial" id="165">Comercial</option>
										<option value="parking" id="166">Estacionamiento</option>
										<option value="warehouse" id="167">Bodega</option>
										<option value="housing_and_commercial" id="168">Habitacional y Comercial</option>
										<option value="other" id="169">Otro</option>
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="naturaleza"><span class="obligatorio">*</span> Naturaleza</label>
									<!-- <?php echo $opcion_destino; ?> -->
									<select id="naturaleza" class="form-control">
										<option value="" label="Seleccione" id=""></option>
										<option value="agricultural" data-select2-id="172">Agrícola</option>
										<option selected="selected" value="non_agricultural" data-select2-id="20">No agrícola</option>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Considerar en DJ1835?</label>
									<select name="dj1835" id="dj1835" class="form-control">
										<?php echo $opcion_dfl2; ?>
									</select>
								</div>
							</div>



							<div class="col-sm-2">
								<div class="form-group">
									<label><span class="obligatorio">*</span> Paga Contribuciones</label>
									<select name="pagoContribucion" id="pagoContribucion" class="form-control">
										<?php echo $opcion_amoblado; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-group">
									<label><span class="obligatorio">*</span> Exento de Contribuciones</label>
									<select name="exentoContribucion" id="exentoContribucion" class="form-control">
										<?php echo $opcion_amoblado; ?>
									</select>
								</div>
							</div>

						</div>
					</fieldset>
				</div>



				<div class="col-md-12 p-0">
					<fieldset class="form-group border p-3">
						<legend>
							<h5 class="mt-0" style="font-size:14px !important; margin-bottom:5px !important;">Retenciones</h5>
						</legend>
						<div class="row g-3">
							<div class="col-sm-3">
								<div class="form-group">
									<label>Monto Retención</label>
									<input type="number" class="form-control" maxlength="2" name="montoRetencion" id="montoRetencion" placeholder="0" value="<?php echo @$result->banos_visita; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label><span class="obligatorio">*</span> Moneda Retención</label>
									<select name="monedaRetencion" id="monedaRetencion" class="form-control">
										<?php echo $opcion_amoblado; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>Motivo Retención</label>
									<select name="motivoRetencion" id="motivoRetencion" class="form-control">
										<?php echo $opcion_amoblado; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="fechaIngreso">Retener Hasta</label>
									<input name="fechaIngreso" id="fechaIngreso" class="form-control" type="date" value="<?php echo fecha_postgre_a_normal(@$result->fecha_ingreso); ?>" />
									<span id="startDateSelected"></span>
								</div>

							</div>
						</div>
					</fieldset>
				</div>
				<div class="col-md-12 p-0">
					<fieldset class="form-group border p-3">
						<legend>
							<h5 class="mt-0" style="font-size:14px !important; margin-bottom:5px !important;">Varios</h5>
						</legend>
						<div class="row g-3">
							<div class="col-md-3">
								<div class="form-group">
									<label>Mostrar cuentas de Servicios en Liquidación</label>
									<select name="mostrarCuentasServicio" id="mostrarCuentasServicio" class="form-control">
										<?php echo $opcion_amoblado; ?>
									</select>
								</div>
							</div>


							<div class="col-md-3">
								<div class="form-group">
									<label>Sucursal</label>
									<?php echo $opcion_sucursal; ?>
								</div>
							</div>


							<div class="col-sm-5">
								<span class="obligatorio">*</span> <strong>Adjuntar Mandato</strong><br>

								<input id="archivo" name="archivo" type="file" onchange="validaArchivo(this);" class="btn btn-file  opacity-100 position-relative h-auto" />

								<input type="hidden" name="archivo_bd" id="archivo_bd" value="<?php echo htmlspecialchars(@$existe_archivo); ?>">

								<?php echo @$archivo; ?>
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