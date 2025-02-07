<script>
	$(function() {
		$('#datetimepicker1').datetimepicker({
			format: "DD-MM-YYYY",
			defaultDate: moment("<?php echo  date('d-m-Y'); ?>", "DD-MM-YYYY")
		});

		$('#datetimepicker2').datetimepicker({
			format: "DD-MM-YYYY"
		});
	});
</script>
<div id="header" class="header-page">
	<!-- <h2 class="mb-3">Contrato</h2> -->
	<div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
		<ol class="breadcrumb d-flex align-items-center m-0">
			<li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
			<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=contrato&view=contrato_list" style="text-decoration: none;color:#66615b">Contratos</a></li>
			<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Contrato</li>
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
		<div class="row g-3">
			<fieldset class="form-group border p-3">

				<div class="row">
					<div class="col-md">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Arrendatario <a data-fancybox='' data-type='iframe' href='components/contrato/views/modal_arrendatarios.php'><i class='fas fa-user-plus'></i></a>
								<?php echo $link_arrendatario; ?></label>
							<input type="hidden" name="token_arrendatario" id="token_arrendatario" value="<?php echo @$result->token_arrendatario; ?>">
							<input type="text" class="form-control" maxlength="250" name="arrendatario" id="arrendatario" placeholder="arrendatario" required data-validation-required readonly value="<?php echo @$result->arrendatario; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>

					<div class="col-md">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Propiedad <a data-fancybox='' data-type='iframe' href='components/contrato/views/modal_propiedades.php'><i class='fas fa-user-plus'></i></a>
								<?php echo $link_propiedad; ?></label>
							<input type="hidden" name="token_propiedad" id="token_propiedad" value="<?php echo @$result->token_propiedad; ?>">
							<input type="text" class="form-control" maxlength="250" name="propiedad" id="propiedad" placeholder="propiedad" required data-validation-required readonly value="<?php echo @$result->propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>

					<div class="col-md">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Ejecutivo(a) <a data-fancybox='' data-type='iframe' href='components/contrato/views/modal_usuarios.php'><i class='fas fa-user-plus'></i></a>
							</label>
							<input type="hidden" name="token_usuario" id="token_usuario" value="<?php echo @$result->token_usuario; ?>">
							<input type="text" class="form-control" maxlength="250" name="nombre_usuario" id="nombre_usuario" placeholder="ejecutivo(a)" required data-validation-required readonly value="<?php echo @$result->nombre_usuario; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset class="form-group border p-3">

				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Ref. Contrato:</label>
							<input type="text" class="form-control" maxlength="50" name="ref_contrato" id="ref_contrato" placeholder="Referencia" value="<?php echo @$result->ref_contrato; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Estado</label>
							<?php echo $opcion_estado_contrato; ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="fechaContrato">Fecha Contrato</label>
							<input name="fechaContrato" id="fechaContrato" class="form-control" type="date" value="<?php echo fecha_postgre_a_normal(@$result->fecha_ingreso); ?>" />
							<span id="startDateSelected"></span>
							<!-- <label><span class="obligatorio">*</span> Fecha Contrato:</label>
					<div class="input-group" id="datetimepicker1">
						<input type="text" class="form-control" maxlength="50" name="fecha_contrato" id="fecha_contrato" placeholder="dd-mm-yyyy" required data-validation-required value="<?php echo fecha_postgre_a_normal(@$result->fecha_contrato); ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						<span class="input-group-addon calendariodatepickter"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div> -->
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="fechaTerminoContrato">Fecha Término Contrato</label>
							<input name="fechaTerminoContrato" id="fechaTerminoContrato" class="form-control" type="date" value="<?php echo fecha_postgre_a_normal(@$result->fecha_contrato); ?>" />
							<span id="startDateSelected"></span>
							<!-- <label>Fecha Termino Contrato:</label>
					<div class="input-group" id="datetimepicker2">
						<input type="text" class="form-control" maxlength="50" name="fecha_termino_contrato" id="fecha_termino_contrato" placeholder="dd-mm-yyyy" value="<?php echo fecha_postgre_a_normal(@$result->fecha_termino_contrato); ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						<span class="input-group-addon calendariodatepickter"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div> -->
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Período Reajuste:</label>
							<?php echo $opcion_periodo_reajuste; ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Día Vencimiento:</label>
							<input type="number" class="form-control" maxlength="2" min="1" max="31" name="dia_vencimiento" id="dia_vencimiento" placeholder="0" required data-validation-required value="<?php echo @$result->dia_vencimiento; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Monto Garantia:</label>
							<input type="text" class="form-control" maxlength="9" min="1" name="monto_garantia" id="monto_garantia" placeholder="0" required data-validation-required value="<?php echo formatea_number(@$result->monto_garantia, $_SESSION["cant_decimales"], $_SESSION["separador_mil"]); ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'<?php echo $_SESSION["cant_decimales"]; ?>','<?php echo $_SESSION["separador_mil"]; ?>');">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label><span class="obligatorio">*</span> Tipo Moneda:</label>
							<?php echo $opcion_tipo_moneda; ?>
						</div>
					</div>
				</div>


				<div class="row">

					<div class="col-sm-6">
						<strong>Adjuntar Contrato :</strong><br>
						<input id="archivo" name="archivo" type="file" onChange="validaArchivo(this);" class="btn btn-file btn-xs opacity-100 position-relative h-auto" />
						<input type="hidden" name="archivo_bd" id="archivo_bd" value="<?php echo @$existe_archivo; ?>">

						<?php echo @$archivo; ?>
					</div>


				</div>

			</fieldset>

		</div>
		<!-- 
	<div class="row">
		<div class="col-md-12"><br /></div>
	</div> -->
		<div class="row">
			<?php if (@$result->id_estado_contrato != '3') { ?>
				<?php if ($muestra_boton_productos === 'S') { ?>
					<a href="index.php?component=contrato&view=contrato_producto&token_contrato=<?php echo @$result->token; ?>&nav=<?php echo $pag_origen; ?>">
						<button type="button" class="btn btn-primary">Agregar Producto</button></a>

					<a href="index.php?component=contrato&view=contrato_pack&token_contrato=<?php echo @$result->token; ?>&nav=<?php echo $pag_origen; ?>">
						<button type="button" class="btn btn-primary">Agregar Pack</button></a>
				<?php } ?>
			<?php } ?>
			<div style="clear:both; width:100%;"></div>
			<div class="col-md-12 text-left">
				<?php echo $lista_productos; ?>
			</div>
		</div>


		<div class="form-group"></div>
		<input type="hidden" name="token" id="token" value="<?php echo @$result->token; ?>">
		<div class="row">
			<div class="col-lg-12 text-center">
				<a href="<?php echo $nav; ?>">
					<button type="button" class="btn btn-primary"> &lt;&lt; volver </button></a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php if (@$result->id_estado_contrato != '3') { ?>
					<button type="submit" class="btn btn-danger"> Aceptar </button>
					<?php if ($muestra_boton_activar == 'S') { ?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="#" onclick="activarContrato('<?php echo $puede_activar; ?>');">
							<button type="button" class="btn btn-primary"> Activar </button>
						<?php } ?>
						<?php if ($muestra_boton_pago == 'S') { ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="<?php echo $url_pago; ?>">
								<button type="button" class="btn btn-primary"> Ingresar Pago </button>
							<?php } ?>
							<?php if ($forzar_termino_contrato == 'S') { ?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="#" onclick="terminarContrato('<?php echo @$result->token; ?>');">
									<button type="button" class="btn btn-primary"> Forzar Termino Contrato </button>
								<?php } ?>
							<?php } ?>
			</div>

		</div>

	</form>
</div>