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
	<!-- <h2 class="mb-3">Arriendo</h2> -->
	<div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
		<ol class="breadcrumb d-flex align-items-center m-0">
			<li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
			<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=arriendo&view=arriendo_list" style="text-decoration: none;color:#66615b">Arriendos</a></li>
			<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Arriendo</li>
		</ol>
	</div>
</div>

<div class="content content-page">

	<div>
		<?php if($token): ?>
	<h1> Edición de Arriendo</h1>
	<?php else: ?>
	<h1> Creación de Arriendo</h1>
	<?php endif; ?>
		<span>
			<span class="obligatorio">*</span> Indica Campos Obligatorios
		</span>
	</div>
	<form name="formulario" id="formulario" method="post" action="javascript: enviarRentdesk();" enctype="multipart/form-data" class="my-3">
		<div class="row g-3">
			<fieldset class="form-group border p-3">
				<!-- <legend>
			<h5 class="mt-0">Detalles</h5>
		</legend> -->
				<div class="row g-3">
					<div class="col-md">
						<div class="form-group">
							<label for="propiedad"><span class="obligatorio">*</span> Propiedad</label>
							<?php echo $opcion_propiedad; ?>
						</div>
					</div>
					<div class="col-md">
									<div class="form-group">
										<label for="Arrendatarios"><span class="obligatorio">*</span> Arrendatarios</label>
										<?php echo $opcion_arrendatario; ?>
									</div>
					</div>
					<div class="col-md">
						<div class="form-group">
							<label for="codeudor"><span class="obligatorio">*</span> Codeudor</label>
							<?php echo $opcion_codeudor; ?>
						</div>
					</div>
				</div>
				
			</fieldset>
	<div id="Titulo-contrato">
		<span>
			<span ></span> Datos del contrato de arriendo
		</span>
	</div>
			
			<fieldset class="form-group border p-3" id="section-Contrato">
				<legend>
					<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Contrato</h5>
				</legend>
				<div class="row g-3">
					<div class="col-md-2">
						<label for="fechaInicio"><span class="obligatorio">*</span> Fecha Inicio</label>
						<input name="fechaInicio" id="fechaInicio" class="form-control" type="date" value="<?php echo $fecha_inicio ?>" />
						<span id="startDateSelected"></span>
					</div>
					<div class="col-md-2">
						<label for="fechaTermino"> Fecha Término Real</label>
						<input name="fechaTermino" id="fechaTermino" class="form-control" type="date" value="<?php echo $fecha_termino_real ?>" />
						<!-- <span id="startDateSelected"></span> -->
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="duracionContrato"><span class="obligatorio">*</span> Duración Contrato</label>
							<input type="number" min="0" class="form-control" name="duracionContrato" id="duracionContrato" placeholder="Duración Contrato" required data-validation-required autofocus value="<?php echo $duracion_contrato_meses ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="precioContrato"><span class="obligatorio">*</span> Precio</label>
							<input type="number" min="0" class="form-control" name="precioContrato" id="precioContrato" placeholder="Precio" required data-validation-required autofocus value="<?php echo $precio ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="monedaContrato"><span class="obligatorio">*</span> Moneda</label>
							<select name="monedaContrato" id="monedaContrato" class="form-control">
								<option selected="selected" value="Pesos" id="1">Pesos</option>
								<option value="UF" id="2">UF</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="montoGarantia"><span class="obligatorio">*</span> Monto Garantía</label>
							<input type="number" min="0" class="form-control" name="montoGarantia" id="montoGarantia" placeholder="Monto Garantía" required data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>


					<div class="col-sm-4">
						<div class="form-group">
							<label for="pagoGarantiaProp"><span class="obligatorio">*</span> ¿Pago de garantía a propietario?</label>
							<select name="pagoGarantiaProp" id="pagoGarantiaProp" class="form-control">
								<option selected="selected" value="SI" id="1">Si</option>
								<option value="NO" id="2">No</option>
							</select>
						</div>
					</div>

					<div class="col-sm-4">
						<div class="form-group">
							<label for="cobroMesCalendario"><span class="obligatorio">*</span> ¿Cobrar mes Calendario?</label>
							<select name="cobroMesCalendario" id="cobroMesCalendario" class="form-control">
								<option selected="selected" value="SI" id="1">Si</option>
								<option value="NO" id="2">No</option>
							</select>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="tipoMulta">Tipo Multa</label>
							<select name="tipoMulta" id="tipoMulta" class="form-control">
								<option value="Por día" id="1">Por día</option>
								<option value="Monto Fijo" id="2">Monto Fijo</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="diasPagoUltimoCobro"><span class="obligatorio">*</span> Días para Pago desde último cobro</label>
							<select name="diasPagoUltimoCobro" id="diasPagoUltimoCobro" class="form-control">
                             <option value="1" id="1">1</option> 
                             <option value="2" id="2">2</option> 
                             <option value="3" id="3">3</option> 
                             <option value="4" id="4">4</option> 
                             <option selected="selected" value="5" id="5">5</option> 
                             <option value="6" id="6">6</option> 
                             <option value="7" id="7">7</option> 
                             <option value="8" id="8">8</option> 
                             <option value="9" id="9">9</option> 
                             <option value="10" id="10">10</option> 
                             <option value="11" id="11">11</option> 
                             <option value="12" id="12">12</option> 
                             <option value="13" id="13">13</option> 
                             <option value="14" id="14">14</option> 
                             <option value="15" id="15">15</option> 
                             <option value="16" id="16">16</option> 
                             <option value="17" id="17">17</option> 
                             <option value="18" id="18">18</option> 
                             <option value="19" id="19">19</option> 
                             <option value="20" id="20">20</option> 
                             <option value="21" id="21">21</option> 
                             <option value="22" id="22">22</option> 
                             <option value="23" id="23">23</option> 
                             <option value="24" id="24">24</option> 
                             <option value="25" id="25">25</option> 
                             <option value="26" id="26">26</option> 
                             <option value="27" id="27">27</option> 
                             <option value="28" id="28">28</option> 
                             <option value="29" id="29">29</option> 
                             <option value="30" id="30">30</option> 
                             <option value="31" id="31">31</option> 
							 </select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="montoMultaAtraso"><span class="obligatorio">*</span> Monto Multa por atraso</label>
							<input type="number" min="0" class="form-control" name="montoMultaAtraso" id="montoMultaAtraso" placeholder="Monto multa atraso" required data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="monedaMulta"><span class="obligatorio">*</span> Moneda Multa</label>
							<select name="monedaMulta" id="monedaMulta" class="form-control">
								<option selected="selected" value="Pesos" id="1">Pesos</option>
								<option value="UF" id="2">UF</option>
								<option value="Porcentaje" id="3">Porcentaje</option>

							</select>
						</div>
					</div>
				</div>

			</fieldset>
			<fieldset class="form-group border p-3" id="section-Reajuste">
				<legend>
					<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Reajuste</h5>
				</legend>
				<div class="row g-3">
					<div class="col-md">
						<div class="form-group">
							<label for="tipoReajuste"><span class="obligatorio">*</span> Tipo Reajuste</label>
							<select name="tipoReajuste" id="tipoReajuste" class="form-control">
								<option selected="selected" value="Sin reajuste" id="1">Sin reajuste</option>
								<option value="IPC" id="2">IPC</option>
								<option value="Fijo Porcentual" id="3">Fijo Porcentual</option>
								<option value="Fijo en Pesos" id="4">Fijo en Pesos</option>
							</select>
						</div>
					</div>
					<div class="col-md">
						<div class="form-group">
							<label for="mesesReajuste"><span class="obligatorio">*</span> Meses Reajuste</label>
							<select class="form-control js-example-responsive" name="meses[]" multiple="multiple">
								<option value="1" id="22134">Enero</option>
								<option value="2" id="22135">Febrero</option>
								<option value="3" id="22136">Marzo</option>
								<option value="4" id="22137">Abril</option>
								<option value="5" id="22138">Mayo</option>
								<option value="6" id="22139">Junio</option>
								<option value="7" id="22140">Julio</option>
								<option value="8" id="22141">Agosto</option>
								<option value="9" id="22142">Septiembre</option>
								<option value="10" id="22143">Octubre</option>
								<option value="11" id="22144">Noviembre</option>
								<option value="12" id="22145">Diciembre</option>
							</select>
						</div>
					</div>
					<div class="col-md">
						<div class="form-group">
							<label for="permiteReajusteNegativo">¿Permite Reajuste Negativo?</label>
							<select name="permiteReajusteNegativo" id="permiteReajusteNegativo" class="form-control">
								<?php echo $opcion_piscina; ?>
							</select>
						</div>
					</div>
					<div class="col-md">
						<div class="form-group">
							<label for="cantReajuste">Cantidad Reajuste</label>
							<div class="input-group mb-3">
								<input type="number" min="0" class=" form-control" name="CantidadReajuste" id="CantidadReajuste" placeholder="Cantidad Reajuste" aria-label="Cantidad Reajuste" aria-describedby="basic-addon2">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<hr>
						<div id="meses" class="row g-2">
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro">Enero</label>
									<input type="number" min="0" class="form-control" name="diasPagoUltimoCobroEnero" id="diasPagoUltimoCobroEnero" placeholder="Monto" data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro">Febrero</label>
									<input type="number" min="0" class="form-control" name="diasPagoUltimoCobroFebrero" id="diasPagoUltimoCobroFebrero" placeholder="Monto"  data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro">Marzo</label>
									<input type="number" min="0" class="form-control" name="diasPagoUltimoCobroMarzo" id="diasPagoUltimoCobroMarzo" placeholder="Monto"  data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro">Abril</label>
									<input type="number" min="0" class="form-control" name="diasPagoUltimoCobroAbril" id="diasPagoUltimoCobroAbril" placeholder="Monto"  data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro">Mayo</label>
									<input type="number" min="0" class="form-control" name="diasPagoUltimoCobroMayo" id="diasPagoUltimoCobroMayo" placeholder="Monto"  data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro">Junio</label>
									<input type="number" min="0" " class=" form-control" name="diasPagoUltimoCobroJunio" id="diasPagoUltimoCobroJunio" placeholder="Monto"  data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro">Julio</label>
									<input type="number" min="0" class="form-control" name="diasPagoUltimoCobroJulio" id="diasPagoUltimoCobroJulio" placeholder="Monto"  data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro">Agosto</label>
									<input type="number" min="0" class="form-control" name="diasPagoUltimoCobroAgosto" id="diasPagoUltimoCobroAgosto" placeholder="Monto"  data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro">Septiembre</label>
									<input type="number" min="0" class="form-control" name="diasPagoUltimoCobroSeptiembre" id="diasPagoUltimoCobroSeptiembre" placeholder="Monto"  data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro">Octubre</label>
									<input type="number" min="0" class="form-control" name="diasPagoUltimoCobroOctubre" id="diasPagoUltimoCobroOctubre" placeholder="Monto"  data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro">Noviembre</label>
									<input type="number" min="0" class="form-control" name="diasPagoUltimoCobroNoviembre" id="diasPagoUltimoCobroNoviembre" placeholder="Monto"  data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro">Diciembre</label>
									<input type="number" min="0" class="form-control" name="diasPagoUltimoCobroDiciembre" id="diasPagoUltimoCobroDiciembre" placeholder="Monto"  data-validation-required autofocus value="<?php echo @$result->codigo_propiedad; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								</div>
							</div>
						</div>
						<div id="monedas" class="row g-2">
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"><strong>Moneda</strong></label>
								      <select name="diasPagoTipoMonedaEnero" id="diasPagoTipoMonedaEnero" class="form-control">
								              <option selected="selected" value="Pesos" id="1">Pesos</option>
								              <option value="UF" id="2">UF</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									  <select name="diasPagoTipoMonedaFebrero" id="diasPagoTipoMonedaFebrero" class="form-control">
								              <option selected="selected" value="Pesos" id="1">Pesos</option>
								              <option value="UF" id="2">UF</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									  <select name="diasPagoTipoMonedaMarzo" id="diasPagoTipoMonedaMarzo" class="form-control">
								              <option selected="selected" value="Pesos" id="1">Pesos</option>
								              <option value="UF" id="2">UF</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="diasPagoTipoMonedaAbril" id="diasPagoTipoMonedaAbril" class="form-control">
								              <option selected="selected" value="Pesos" id="1">Pesos</option>
								              <option value="UF" id="2">UF</option>
							        </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="diasPagoTipoMonedaMayo" id="diasPagoTipoMonedaMayo" class="form-control">
								              <option selected="selected" value="Pesos" id="1">Pesos</option>
								              <option value="UF" id="2">UF</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="diasPagoTipoMonedaJunio" id="diasPagoTipoMonedaJunio" class="form-control">
								              <option selected="selected" value="Pesos" id="1">Pesos</option>
								              <option value="UF" id="2">UF</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoTipoMoneda"></label>
									<select name="diasPagoTipoMonedaJulio" id="diasPagoTipoMonedaJulio" class="form-control">
								              <option selected="selected" value="Pesos" id="1">Pesos</option>
								              <option value="UF" id="2">UF</option>
							          </select>								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
										<select name="diasPagoTipoMonedaAgosto" id="diasPagoTipoMonedaAgosto" class="form-control">
								              <option selected="selected" value="Pesos" id="1">Pesos</option>
								              <option value="UF" id="2">UF</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="diasPagoTipoMonedaSeptiembre" id="diasPagoTipoMonedaSeptiembre" class="form-control">
								              <option selected="selected" value="Pesos" id="1">Pesos</option>
								              <option value="UF" id="2">UF</option>
							        </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="diasPagoTipoMonedaOctubre" id="diasPagoTipoMonedaOctubre" class="form-control">
								              <option selected="selected" value="Pesos" id="1">Pesos</option>
								              <option value="UF" id="2">UF</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="diasPagoTipoMonedaNoviembre" id="diasPagoTipoMonedaNoviembre" class="form-control">
								              <option selected="selected" value="Pesos" id="1">Pesos</option>
								              <option value="UF" id="2">UF</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="diasPagoTipoMonedaDiciembre" id="diasPagoTipoMonedaDiciembre" class="form-control">
								              <option selected="selected" value="Pesos" id="1">Pesos</option>
								              <option value="UF" id="2">UF</option>
							          </select>
								</div>
							</div>
						</div>
						<div id="aplica" class="row g-2">
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"><strong>Aplicar</strong></label>
									<select name="OpcionAplicarEnero" id="OpcionAplicarEnero" class="form-control">
								              <option selected="selected" value="Una vez" id="1">Una vez</option>
								              <option value="Siempre" id="2">Siempre</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="OpcionAplicaFebrero" id="OpcionAplicaFebrero" class="form-control">
								              <option selected="selected" value="Una vez" id="1">Una vez</option>
								              <option value="Siempre" id="2">Siempre</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="OpcionAplicarMarzo" id="OpcionAplicarMarzo" class="form-control">
								              <option selected="selected" value="Una vez" id="1">Una vez</option>
								              <option value="Siempre" id="2">Siempre</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="OpcionAplicarAbril" id="OpcionAplicarAbril" class="form-control">
								              <option selected="selected" value="Una vez" id="1">Una vez</option>
								              <option value="Siempre" id="2">Siempre</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="OpcionAplicarMayo" id="OpcionAplicarMayo" class="form-control">
								              <option selected="selected" value="Una vez" id="1">Una vez</option>
								              <option value="Siempre" id="2">Siempre</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="OpcionAplicarJunio" id="OpcionAplicarJunio" class="form-control">
								              <option selected="selected" value="Una vez" id="1">Una vez</option>
								              <option value="Siempre" id="2">Siempre</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="OpcionAplicarJulio" id="OpcionAplicarJulio" class="form-control">
								              <option selected="selected" value="Una vez" id="1">Una vez</option>
								              <option value="Siempre" id="2">Siempre</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="OpcionAplicarAgosto" id="OpcionAplicarAgosto" class="form-control">
								              <option selected="selected" value="Una vez" id="1">Una vez</option>
								              <option value="Siempre" id="2">Siempre</option>
							          </select>
								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="OpcionAplicarSeptiembre" id="OpcionAplicarSeptiembre" class="form-control">
								              <option selected="selected" value="Una vez" id="1">Una vez</option>
								              <option value="Siempre" id="2">Siempre</option>
							          </select>								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="OpcionAplicarOctubre" id="OpcionAplicarOctubre" class="form-control">
								              <option selected="selected" value="Una vez" id="1">Una vez</option>
								              <option value="Siempre" id="2">Siempre</option>
							          </select>								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="OpcionAplicarNoviembre" id="OpcionAplicarNoviembre" class="form-control">
								              <option selected="selected" value="Una vez" id="1">Una vez</option>
								              <option value="Siempre" id="2">Siempre</option>
							          </select>								</div>
							</div>
							<div class="col-md">
								<div class="form-group">
									<label for="diasPagoUltimoCobro"></label>
									<select name="OpcionAplicarDiciembre" id="OpcionAplicarDiciembre" class="form-control">
								              <option selected="selected" value="Una vez" id="1">Una vez</option>
								              <option value="Siempre" id="2">Siempre</option>
							          </select>								
							    </div>
							</div>
						</div>
					</div>

				</div>
			</fieldset>
			<fieldset class="form-group border p-3" id="section-Comisiones">
				<legend>
					<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Comisiones</h5>
				</legend>
				<div class="row g-3">
					<div class="col-md">
						<fieldset class="form-group border-0 p-3">
							<legend>
								<h5 class="mt-0" style="font-size:12px !important;margin-bottom:5px !important;">Arriendo</h5>
							</legend>
							<div class="row g-2 ">
								<div class="col-lg-4">
									<div class="form-group">
										<label for="cobrarComisionArriendo">¿Cobrar comisión de Arriendo?</label>
										<select name="cobrarComisionArriendo" id="cobrarComisionArriendo" class="form-control">
								               <option selected="selected" value="SI" id="1">Si</option>
								               <option value="NO" id="2">No</option>
										</select>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label for="comisionArriendo"><span class="obligatorio">*</span> Comisión Arriendo</label>
										<input type="number" class="form-control" min="0" name="comisionArriendo" id="comisionArriendo" placeholder="0" value="<?php echo @$result->dormitorios; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
									</div>
								</div>

								<div class="col-lg-4">
									<div class="form-group">
										<label for="monedaComisionArriendo"><span class="obligatorio">*</span> Moneda Comisión Arriendo</label>
										<select name="monedaComisionArriendo" id="monedaComisionArriendo" class="form-control">
											<option value="Pesos" id="1">Pesos</option>
											<option value="UF" id="2">UF</option>
											<option selected="selected" value="Porcentaje" id="3">Porcentaje</option>
										</select>
									</div>
								</div>

								<div class="col-lg-4">
									<div class="form-group">
										<label for="facturarComisionArriendo">¿Facturar comisión de Arriendo?</label>
										<select name="facturarComisionArriendo" id="facturarComisionArriendo" class="form-control">
											   <option selected="selected" value="SI" id="1">Si</option>
								               <option value="NO" id="2">No</option>
										</select>
									</div>
								</div>

								<div class="col-lg-8">
									<div class="form-group">
										<label for="tipoFacturaComisionArriendo"><span class="obligatorio">*</span> Tipo Factura Comisión Arriendo</label>
										<select name="tipoFacturaComisionArriendo" id="tipoFacturaComisionArriendo" class="form-control">
											<option selected="selected" value="taxable_electronic_invoice" id="6428">Factura electrónica afecta</option>
											<option value="non_taxable_electronic_invoice" id="22169">Factura electrónica no afecta o exenta</option>
											<option value="taxable_electronic_ticket" id="22170">Boleta electrónica afecta</option>
											<option value="non_taxable_electronic_ticket" id="22171">Boleta electrónica exenta</option>
											<option value="honors_electronic_ticket" id="22172">Boleta de honorarios</option>
										</select>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-md">
						<fieldset class="form-group border-0 p-3">
							<legend>
								<h5 class="mt-0" style="font-size:12px !important;margin-bottom:5px !important;">Administración</h5>
							</legend>
							<div class="row g-2 ">
								<div class="col-lg-4">
									<div class="form-group">
										<label for="cobrarComisionAdministracion">¿Cobrar comisión de Administración?</label>
										<select name="cobrarComisionAdministracion" id="cobrarComisionAdministracion" class="form-control">
											   <option selected="selected" value="SI" id="1">Si</option>
								               <option value="NO" id="2">No</option>
										</select>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label for="comisionAdministracion"><span class="obligatorio">*</span> Comisión Administración</label>
										<input type="number" class="form-control" min="0" name="comisionAdministracion" id="comisionAdministracion" placeholder="0" value="<?php echo @$result->dormitorios; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
									</div>
								</div>

								<div class="col-lg-4">
									<div class="form-group">
										<label for="monedaComisionAdministracion"><span class="obligatorio">*</span> Moneda Comisión Administración</label>
										<select name="monedaComisionAdministracion" id="monedaComisionAdministracion" class="form-control">
											<option value="Pesos" id="1">Pesos</option>
											<option value="UF" id="2">UF</option>
											<option selected="selected" value="Porcentaje" id="3">Porcentaje</option>
										</select>
									</div>
								</div>

								<div class="col-lg-4">
									<div class="form-group">
										<label for="facturarComisionAdministracion">¿Facturar comisión de Administración?</label>
										<select name="facturarComisionAdministracion" id="facturarComisionAdministracion" class="form-control">
											   <option selected="selected" value="SI" id="1">Si</option>
								               <option value="NO" id="2">No</option>
										</select>
									</div>
								</div>

								<div class="col-lg-8">
									<div class="form-group">
										<label for="tipoFacturaComisionAdministracion"><span class="obligatorio">*</span> Tipo Factura Comisión Administración</label>
										<select name="tipoFacturaComisionAdministracion" id="tipoFacturaComisionAdministracion" class="form-control">
											<option selected="selected" value="taxable_electronic_invoice" id="6428">Factura electrónica afecta</option>
											<option value="non_taxable_electronic_invoice" id="22169">Factura electrónica no afecta o exenta</option>
											<option value="taxable_electronic_ticket" id="22170">Boleta electrónica afecta</option>
											<option value="non_taxable_electronic_ticket" id="22171">Boleta electrónica exenta</option>
											<option value="honors_electronic_ticket" id="22172">Boleta de honorarios</option>
										</select>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label for="facturarComisionAdministracion">¿Cobrar comisión de administración en primera liquidación?</label>
										<select name="facturarComisionAdministracion" id="facturarComisionAdministracion" class="form-control">
												<option selected="selected" value="SI" id="1">Si</option>
								               <option value="NO" id="2">No</option>
										</select>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
				</div>


				<!-- <div class="row">
				<div class="col-md ps-0">
					<h6>Arriendo</h6>
					<div class="row g-2 ">
						<div class="col-sm-4">
							<div class="form-group">
								<label for="cobrarComisionArriendo">¿Cobrar comisión de Arriendo?</label>
								<select name="cobrarComisionArriendo" id="cobrarComisionArriendo" class="form-control">
									<?php echo $opcion_piscina; ?>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="comisionArriendo"><span class="obligatorio">*</span> Comisión Arriendo</label>
								<input type="number" class="form-control" min="0" name="comisionArriendo" id="comisionArriendo" placeholder="0" value="<?php echo @$result->dormitorios; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for="monedaComisionArriendo"><span class="obligatorio">*</span> Moneda Comisión Arriendo</label>
								<select name="monedaComisionArriendo" id="monedaComisionArriendo" class="form-control">
									<option selected="selected" value="Pesos" id="1">Pesos</option>
									<option value="UF" id="2">UF</option>
									<option value="Porcentaje" id="3">Porcentaje</option>
								</select>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for="facturarComisionArriendo">¿Facturar comisión de Arriendo?</label>
								<select name="facturarComisionArriendo" id="facturarComisionArriendo" class="form-control">
									<?php echo $opcion_piscina; ?>
								</select>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<label for="tipoFacturaComisionArriendo"><span class="obligatorio">*</span> Tipo Factura Comisión Arriendo</label>
								<select name="tipoFacturaComisionArriendo" id="tipoFacturaComisionArriendo" class="form-control">
									<option selected="selected" value="taxable_electronic_invoice" id="6428">Factura electrónica afecta</option>
									<option value="non_taxable_electronic_invoice" id="22169">Factura electrónica no afecta o exenta</option>
									<option value="taxable_electronic_ticket" id="22170">Boleta electrónica afecta</option>
									<option value="non_taxable_electronic_ticket" id="22171">Boleta electrónica exenta</option>
									<option value="honors_electronic_ticket" id="22172">Boleta de honorarios</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="d-flex w-auto">
					<div class="vr"></div>
				</div>
				<div class="col-md ps-0">
					<h6>Administración</h6>
					<div class="row g-2 ">

						<div class="col-sm-4">
							<div class="form-group">
								<label for="cobrarComisionAdministracion">¿Cobrar comisión de Administración?</label>
								<select name="cobrarComisionAdministracion" id="cobrarComisionAdministracion" class="form-control">
									<?php echo $opcion_piscina; ?>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="comisionAdministracion"><span class="obligatorio">*</span> Comisión Administración</label>
								<input type="number" class="form-control" min="0" name="comisionAdministracion" id="comisionAdministracion" placeholder="0" value="<?php echo @$result->dormitorios; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for="monedaComisionAdministracion"><span class="obligatorio">*</span> Moneda Comisión Administración</label>
								<select name="monedaComisionAdministracion" id="monedaComisionAdministracion" class="form-control">
									<option selected="selected" value="Pesos" id="1">Pesos</option>
									<option value="UF" id="2">UF</option>
									<option value="Porcentaje" id="3">Porcentaje</option>
								</select>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for="facturarComisionAdministracion">¿Facturar comisión de Administración?</label>
								<select name="facturarComisionAdministracion" id="facturarComisionAdministracion" class="form-control">
									<?php echo $opcion_piscina; ?>
								</select>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<label for="tipoFacturaComisionAdministracion"><span class="obligatorio">*</span> Tipo Factura Comisión Administración</label>
								<select name="tipoFacturaComisionAdministracion" id="tipoFacturaComisionAdministracion" class="form-control">
									<option selected="selected" value="taxable_electronic_invoice" id="6428">Factura electrónica afecta</option>
									<option value="non_taxable_electronic_invoice" id="22169">Factura electrónica no afecta o exenta</option>
									<option value="taxable_electronic_ticket" id="22170">Boleta electrónica afecta</option>
									<option value="non_taxable_electronic_ticket" id="22171">Boleta electrónica exenta</option>
									<option value="honors_electronic_ticket" id="22172">Boleta de honorarios</option>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="facturarComisionAdministracion">¿Cobrar comisión de administración en primera liquidación?</label>
								<select name="facturarComisionAdministracion" id="facturarComisionAdministracion" class="form-control">
									<?php echo $opcion_piscina; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div> -->

			</fieldset>
			<fieldset class="form-group border p-3" id="section-Otros">
				<legend>
					<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Otros</h5>
				</legend>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="form-group">
							<label>Amoblado?</label>
							<select name="amoblado" id="amoblado" class="form-control">
								<?php echo $opcion_amoblado; ?>
							</select>
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset class="form-group border p-3" id="section-Documentos" >

				<div class="row">


					<div class="col-sm-6">
						<strong>Documentos</strong><br>

						<input id="archivo" name="archivo" type="file" onchange="validaArchivo(this);" class="btn btn-file btn-xs opacity-100 position-relative h-auto" />

						<input type="hidden" name="archivo_bd" id="archivo_bd" value="<?php echo htmlspecialchars(@$existe_archivo); ?>">

						<?php echo @$archivo; ?>
					</div>


				</div>

			</fieldset>
		</div>
		<!-- <?php if ($token != "") { ?>
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
		<?php } ?> -->


		<div class="col-lg-12 text-center">
			<a href="<?php echo $nav; ?>">
				<button type="button" class="btn btn-info" id="bt-volver"> &lt;&lt; volver </button></a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button type="submit" class="btn btn-danger" class="btn btn-danger" id="bt-aceptar"> Guardar </button>
		</div>


	</form>
</div>