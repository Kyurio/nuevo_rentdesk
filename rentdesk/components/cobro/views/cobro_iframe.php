<script>
	$(document).ready(function() {
		$(".sidebar").css("display", "none");
		$(".navbar").css("display", "none");
		$(".main-panel").css("width", "100%");
		$(".btn-primary").css("display", "none");
		$(".footer").css("display", "none");

		$('input').attr('readonly', true);
		$('select').attr('readonly', true);

	});
</script>

<script src="js/region_ciudad_comuna.js"></script>


<script>
	$(function() {
		$('#datetimepicker1').datetimepicker({
			format: "DD-MM-YYYY",
			defaultDate: moment("<?php echo  date('d-m-Y'); ?>", "DD-MM-YYYY")
		});
	});
</script>
<h2>Propiedad</h2>
<form name="formulario" id="formulario" method="post" action="javascript: enviar();" enctype="multipart/form-data">

	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label><span class="obligatorio">*</span> Estado:</label>
				<?php echo $opcion_estado_propiedad; ?>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label><span class="obligatorio">*</span> Código Propiedad:</label>
				<input type="text" class="form-control" maxlength="250" name="codigoPropiedad" id="codigoPropiedad" placeholder="Código de Propiedad" required data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label><span class="obligatorio">*</span> Tipo Propiedad:</label>
				<?php echo $opcion_tipo_propiedad; ?>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label><span class="obligatorio">*</span> Rol:</label>
				<input type="text" class="form-control" maxlength="250" name="rol" id="rol" placeholder="rol" required data-validation-required value="<?php echo @$result->rol; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
	</div>



	<div class="row">

		<div class="col-lg-4 form-group">
			<label><span class="obligatorio">*</span>Pais:</label>
			<div id="divpais"></div>
			<input type="hidden" id="hiddenpais" name="hiddenpais" value="<?php echo @$pais; ?>">
		</div>

		<div class="col-lg-4 form-group">
			<label><span class="obligatorio">*</span>Región:</label>
			<div id="divregion"></div>
			<input type="hidden" id="hiddenregion" name="hiddenregion" value="<?php echo @$region; ?>">
		</div>

		<div class="col-lg-4 form-group">
			<label><span class="obligatorio">*</span>Comuna:</label>
			<div id="divcomuna"></div>
			<input type="hidden" id="hiddencomuna" name="hiddencomuna" value="<?php echo @$comuna; ?>">
		</div>
	</div>





	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label><span class="obligatorio">*</span> Dirección:</label>
				<input type="text" class="form-control" maxlength="250" name="direccion" id="direccion" placeholder="Dirección" required data-validation-required value="<?php echo @$result->direccion; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Número:</label>
				<input type="text" class="form-control" maxlength="250" name="numero" id="numero" placeholder="número" value="<?php echo @$result->numero; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Depto:</label>
				<input type="text" class="form-control" maxlength="250" name="numeroDepto" id="numeroDepto" placeholder="número depto" value="<?php echo @$result->numero_depto; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Piso:</label>
				<input type="number" class="form-control" maxlength="2" name="piso" id="piso" placeholder="0" value="<?php echo @$result->piso; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2">
			<div class="form-group">
				<label>Terreno:</label>
				<select name="terreno" id="terreno" class="form-control">
					<?php echo $opcion_terreno; ?>
				</select>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Edificado:</label>
				<select name="edificado" id="edificado" class="form-control">
					<?php echo $opcion_edificado; ?>
				</select>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Dormitorios:</label>
				<input type="number" class="form-control" maxlength="2" name="dormitorios" id="dormitorios" placeholder="0" value="<?php echo @$result->dormitorios; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Baños:</label>
				<input type="number" class="form-control" maxlength="2" name="banos" id="banos" placeholder="0" value="<?php echo @$result->banos; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Dorm. Servicio:</label>
				<input type="number" class="form-control" maxlength="2" name="dormitoriosServicio" id="dormitoriosServicio" placeholder="0" value="<?php echo @$result->dormitorios_servicio; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Baños Visita:</label>
				<input type="number" class="form-control" maxlength="2" name="banosVisita" id="banosVisita" placeholder="0" value="<?php echo @$result->banos_visita; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2">
			<div class="form-group">
				<label>Estacionamientos:</label>
				<input type="number" class="form-control" maxlength="2" name="estacionamientos" id="estacionamientos" placeholder="0" value="<?php echo @$result->estacionamientos; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Bodegas:</label>
				<input type="number" class="form-control" maxlength="2" name="bodegas" id="bodegas" placeholder="0" value="<?php echo @$result->bodegas; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Logia:</label>
				<input type="number" class="form-control" maxlength="2" name="logia" id="logia" placeholder="0" value="<?php echo @$result->logia; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Piscina?:</label>
				<select name="piscina" id="piscina" class="form-control">
					<?php echo $opcion_piscina; ?>
				</select>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Coordenadas:</label>
				<input type="text" class="form-control" maxlength="100" name="coordenadas" id="coordenadas" placeholder="Cordenadas GPS" value="<?php echo @$result->coordenadas; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Fecha Ingreso:</label>
				<div class="input-group" id="datetimepicker1">
					<input type="text" class="form-control" maxlength="50" name="fechaIngreso" id="fechaIngreso" placeholder="dd-mm-yyyy" value="<?php echo fecha_postgre_a_normal(@$result->fecha_ingreso); ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
					<span class="input-group-addon calendariodatepickter"><i class="fa fa-calendar" aria-hidden="true"></i></span>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label>Amoblado?:</label>
				<select name="amoblado" id="amoblado" class="form-control">
					<?php echo $opcion_amoblado; ?>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>DFL2?:</label>
				<select name="dfl2" id="dfl2" class="form-control">
					<?php echo $opcion_dfl2; ?>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Avaluo Fiscal:</label>
				<input type="text" class="form-control" maxlength="15" min="1" name="avaluo_fiscal" id="avaluo_fiscal" placeholder="0" value="<?php echo formatea_number(@$result->avaluo_fiscal, $_SESSION["cant_decimales"], $_SESSION["separador_mil"]); ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'<?php echo $_SESSION["cant_decimales"]; ?>','<?php echo $_SESSION["separador_mil"]; ?>');">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label><span class="obligatorio">*</span> Destino Arriendo:</label>
				<?php echo $opcion_destino; ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label>Sucursal:</label>
				<?php echo $opcion_sucursal; ?>
			</div>
		</div>

		<div class="form-group"></div>
		<input type="hidden" name="token" id="token" value="<?php echo @$result->token; ?>">

	</div>

	<div class="row">

		<div class="col-sm-12"><span class="obligatorio">*</span> <strong>Adjuntar Mandato:</strong><br>
			<input id="archivo" name="archivo" type="file" onChange="validaArchivo(this);" class="btn btn-success btn-xs" />
			<input type="hidden" name="archivo_bd" id="archivo_bd" value="<?php echo @$existe_archivo; ?>">

			<?php echo @$archivo; ?>
		</div>


	</div>

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
			<button type="button" class="btn btn-primary"> &lt;&lt; volver </button></a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<button type="submit" class="btn btn-primary"> Aceptar </button>
	</div>


</form>