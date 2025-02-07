<script src="js/region_ciudad_comuna.js"></script>

<script>
	$('#region').click(function() {


		var $select = $('#region');
		var $options = $select.find('option');

		$options.sort(function(a, b) {
			if (a.text > b.text) return 1;
			if (a.text < b.text) return -1;
			return 0;
		});
		$select.empty().append($options);
	});

	$(function() {
		$('#datetimepicker1').datetimepicker({
			format: "DD-MM-YYYY",
			defaultDate: moment("<?php echo  date('d-m-Y'); ?>", "DD-MM-YYYY")
		});
	});
</script>




<?php

$config		= new Config;
$peso_archivo = $config->maxSizeMB;
?>
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

<div class="content content-page">
	<div>
		<?php if ($token) : ?>
			<h1>Edición de Propiedad Nº <?php echo $id_ficha ?></h1>

			<script>
				$(document).ready(function() {
					$('#btDNI').trigger('click');
				});
			</script>

		<?php else :  $token_propiedad_defecto = bin2hex(random_bytes(16)); ?>
			<h1>Creación de Propiedad </h1>
		<?php endif; ?>
		<div id="infoPropiedad" class="alert alert-warning" style="color:#313131;" role="alert">
			<strong>Estimado usuario :</strong> Para continuar con el formulario de creación de una propiedad, es necesario ingresar el RUT del propietario. Si el propietario no existe en el sistema, será redirigido a la sección correspondiente para crear su perfil y asociarlo a la nueva propiedad.
		</div>
		<span>
			<span class="obligatorio">*</span> Indica Campos Obligatorios
		</span>
	</div>
	<form name="formulario" id="formulario-propiedad" method="post" action="javascript: enviarRentdesk();" enctype="multipart/form-data" class="my-3">


		<fieldset id="info-cliente" class="form-group border p-3">

			<input id="token_propiedad_defecto" type="hidden" class="form-control" value="<?php echo @$token_propiedad_defecto ?>">
			<input id="ficha_tecnica" type="hidden" class="form-control" value="<?php echo @$id_ficha ?>">

			<div class="row g-3">
				<div class="col-md-3">
					<label><span class="obligatorio">*</span> Busqueda por<?php if ($flag_solo_rut != 1) { ?> DNI /<?php } ?> RUT del propietario</label>
					<div class="input-group mb-3">
						<input id="DNI" type="text" class="form-control" oninput="<?php if ($flag_solo_rut == 1) {
																						echo "checkRut(this);";
																					} ?>" placeholder="RUT" aria-label="DNI" aria-describedby="button-addon2" required value="<?php echo isset($token) && $resultPropietario ? $resultPropietario->dni : ''; ?>">
						<button id="btDNI" class="btn btn-info m-0" type="button" id="button-addon2" onClick="busquedaDNI(); "
							data-bs-toggle="modal" data-bs-target="#modalArrendatario">
							Buscar
						</button>
					</div>
				</div>
			</div>
			<div>
				<?php if (!$token) : ?>
					<fieldset class="form-group border p-3" id="section-info-cliente-natural" style="display: none">
						<legend style="display: flex;
						align-items: center;
						justify-content: space-between;">
							<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Información Cliente</h5>
						</legend>
						<div class="row">
							<div class="col"><label><b>Nombre: </label> <label id="nombrePersona"></b></label></div>
							<div class="col"><label><b>Telefono: </label> <label id="telefonoMovilPersona"></b></label></div>
							<div class="col"><label><b>Email: </label> <label id="emailPersona"></b></label></div>
							<div class="col"><label><b>Tipo Persona: </label> <label id="tipoPersona"></b></label></div>

						</div>
						<div class="row" style="margin-top: 10px">
							<div class="col"><label><b>Direccion</label><br> <label id="direccionPersona"><b></label>
								<a href="" id="linkMaps" target="_blank">ver ubicacion</a>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-group border p-3" id="section-info-cliente-juridico" style="display: none">
						<legend style="display: flex;
						align-items: center;
						justify-content: space-between;">
							<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Información Cliente</h5>
						</legend>
						<div class="row">
							<div class="col"><label>Nombre Fantasia</label><br> <label id="nombrePersonaJuridica"></label></div>
							<div class="col"><label>Razón Social</label><br> <label id="razonPersonaJuridica"></label></div>
							<div class="col"><label>Teléfono</label><br> <label id="telefonoMovilPersonaJuridica"></label></div>
							<div class="col"><label>Email </label><br> <label id="emailPersonaJuridica"></label></div>
							<div class="col"><label>Tipo Persona</label><br> <label id="tipoPersonaJuridica"></label></div>

						</div>
						<div class="row" style="margin-top: 10px">
							<div class="col"><label>Dirección</label><br> <label id="direccionPersonaJuridica"></label>
								<a href="" id="linkMapsJuridica" target="_blank">ver ubicación</a>
							</div>
						</div>
					</fieldset>
				<?php endif; ?>
			</div>
		</fieldset>




		<div>

			<?php if (!@$token_propiedad_defecto) : ?>


				<fieldset class="form-group border p-3" id="section-prop">
					<legend>
						<h5 class="mt-0" style="font-size:14px !important; margin-bottom:5px !important;">Propietarios</h5>
					</legend>
					<div class="alert alert-warning" style="color:#313131;" role="alert">
						<strong>Estimado usuario :</strong> Si requiere agregar o editar los porcentajes de los propietarios debe dar click al siguiente boton o en el siguiente <a href="index.php?component=propiedad&view=propiedad_ficha_tecnica&token=<?php echo @$token_propiedad_actual ?>&nav=propietario">link.</a>
					</div>
					<div class="modal-header">


						<a href="index.php?component=propiedad&view=propiedad_ficha_tecnica&token=<?php echo @$token_propiedad_actual ?>&nav=propietario">
							<button id="bt-ficha-propietario" type="button" class="btn btn-info"> Editar Propietario </button>
						</a>
					</div>

					<div id="section-propietarios" class="row g-3 p-0" style="display: none;">
						<div class="card">
							<div class="card-body">
								<div class="table-responsive overflow-auto">

									<table id="info-propietarios-propiedad" class="table table-striped" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>Propietario</th>
												<th>RUT Propietario</th>
												<th>Tipo cliente</th>
												<th>Nombre Titular</th>
												<th>RUT Titular</th>
												<th>Cuenta Banco</th>
												<th>% Participación</th>
												<th>% Participación Relativa Final</th>
											</tr>
										</thead>
										<tbody>


										</tbody>
									</table>
								</div>
							</div>
						</div>

						<!-- 
		<fieldset class="form-group border p-3" id="section-propietarios" >
			
			<legend>
					<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Propietarios</h5>
			</legend>
			
					<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Agregar Propietarios" data-bs-toggle="modal" data-bs-target="#modalPropietarioIngreso" >
		            		<span>Agregar Propietarios</span>
		            </button>
		           
		<div class="modal fade" id="modalPropietarioIngreso" tabindex="-1" aria-labelledby="modalPropietariosLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog" style="max-width: 800px;">
			      <div class="modal-content">
				    <div class="modal-header">
				    	<h5 class="modal-title" id="modalPropietariosLabel">Ingreso Propietario</h5>
				    	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				    </div>
		            <div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
				<div class="modal-body" id="modalBodyOriginal">
					 	<div class="row g-3">
								<label><span class="obligatorio">*</span> Busqueda RUT</label>
								<div class="input-group mb-3">
									<input id="DNIPropietario" type="text" class="form-control" placeholder="RUT" aria-label="DNI" aria-describedby="button-addon2" required value="" onblur="ocultarAutocomplete('DNIPropietario');" autocomplete='off' onkeyup='buscarPropiedadAutocomplete(this.value,"DNIPropietario");'>
									<div id='suggestions_DNIPropietario' class="suggestionsAutoComplete"></div>
									<button class="btn btn-info m-0" type="button" id="busqueda-dni-propietarios" onClick="busquedaDNIPropietario()">Buscar</button>
								</div>
								<input type="hidden" id="persona_formulario" name="persona_formulario">
												<fieldset class="form-group border p-3" id="section-info-cliente-natural" style="display: none">
													<legend style="display: flex;
														align-items: center;
														justify-content: space-between;">
														<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Información Cliente</h5>
													</legend>
													<div class="row">
														<div class="col"><label>Nombre</label><br> <label id="nombrePersona"></label></div>
														<div class="col"><label>Telefono</label><br> <label id="telefonoMovilPersona"></label></div>
														<div class="col"><label>Email </label><br> <label id="emailPersona"></label></div>
														<div class="col"><label>Tipo Persona</label><br> <label id="tipoPersona"></label></div>

													</div>
													<div class="row" style="margin-top: 10px">
														<div class="col"><label>Direccion</label><br> <label id="direccionPersona"></label>
															<a href="" id="linkMaps" target="_blank">ver ubicacion</a>
														</div>
													</div>
												</fieldset>
												<fieldset class="form-group border p-3" id="section-info-cliente-juridico" style="display: none">
													<legend style="display: flex;
														align-items: center;
														justify-content: space-between;">
														<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Información Cliente</h5>
													</legend>
													<div class="row">
														<div class="col"><label>Nombre Fantasia</label><br> <label id="nombrePersonaJuridica"></label></div>
														<div class="col"><label>Razon Social</label><br> <label id="razonPersonaJuridica"></label></div>
														<div class="col"><label>Telefono</label><br> <label id="telefonoMovilPersonaJuridica"></label></div>
														<div class="col"><label>Email </label><br> <label id="emailPersonaJuridica"></label></div>
														<div class="col"><label>Tipo Persona</label><br> <label id="tipoPersonaJuridica"></label></div>

													</div>
													<div class="row" style="margin-top: 10px">
														<div class="col"><label>Direccion</label><br> <label id="direccionPersonaJuridica"></label>
															<a href="" id="linkMapsJuridica" target="_blank">ver ubicacion</a>
														</div>
													</div>
												</fieldset>
												<fieldset class="form-group border p-3" id="section-info-cta-bancaria" style="display: none">
													<legend style="display: flex;
														align-items: center;
														justify-content: space-between;">
														<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Cuenta Bancaria</h5>
													</legend>
													<div class="row">
														<div class="col"><label>Nombre Titular</label><br> <label id="ctaBancNombreTitular"></label></div>
														<div class="col"><label>Rut Titular</label><br> <label id="ctaBancRutTitular"></label></div>
														<div class="col"><label>Número Cuenta </label><br> <label id="ctaBancNumero"></label></div>
													</div>
												</fieldset>
						</div>
				</div>



				 </div>
					   
				 	<div class="modal-footer">
						<button type="button" class="btn btn-info"  onClick='resetForm();'>Cerrar</button>
						<button type="button" class="btn btn-danger"  id="addItemButton" onClick='cargaDocumento();'>Guardar</button>
					</div>
             </div>
			 </div>
		</div>
                    	<div class="card">
                    	    <div class="card-body">
                    	    	<div class="table-responsive overflow-auto">
                    	    
                    	    		<table id="documentos" class="table table-striped" cellspacing="0" width="100%">
                    	    			<thead>
                    	    				<tr>
												<th>Propietario</th>
												<th>RUT Propietario</th>
												<th>Nombre Titular</th>
                    	    					<th>RUT Titular</th>
                    	    					<th>Cuenta Banco</th>
												<th>% Participación</th>
												<th>Acciones</th>
                    	    				</tr>
                    	    			</thead>
                    	    			<tbody>
                    	    								
                    	    			
                    	    			</tbody>
                    	    		</table>
                    		    </div>
                    		</div>
                    	</div>
			</fieldset>
					-->
					</div>
				</fieldset>
			<?php endif; ?>

		</div>


		<div id="section-0" class="row g-3 p-0" style="display: none;">
			<div class="card">
				<div class="card-body">


					<div class="row">

						<div class="col-6">
							<label for="ejectuvio_encargado"><span class="obligatorio">*</span> Ejecutivo encargado.</label>

							<!-- 					
							<select id="selectEjecutivos" name="selectEjecutivos" class='form-control  form-select' required></select> -->

							<?php

							//************************************************************************************************************

							//************************************************************************************************************
							/*SELECTOR - listado ejectuvos a cargo - jhernandez- MANTENER PARA RENTDESK */

							$queryEjectuvios = "SELECT CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) as nombres, correo, id
							FROM propiedades.cuenta_usuario
							where habilitado = true";

							$data = array("consulta" => $queryEjectuvios);
							$resultadoEjecutivos  = $services->sendPostDirecto($url_services . '/util/objeto', $data);
							$resultdados = json_decode($resultadoEjecutivos);

							$opcion_encargado = "<option value=''>Seleccione</option>";

							foreach ($resultdados as $item) {

								if (@$result->id_ejecutiva_encargada == @$item->id) {
									$opcion_encargado = $opcion_encargado . "<option value='$item->id' selected >$item->nombres - $item->correo </option>";
								}

								$opcion_encargado = $opcion_encargado . "<option value='$item->id' >$item->nombres - $item->correo </option>";
							}


							$opcion_encargado = "<select id='selectEjecutivos' name='selectEjecutivos' class='form-control  form-select' >
							$opcion_encargado
							</select>";

							echo $opcion_encargado;

							?>

						</div>

						<div class="col-6">
							<label for="ejectuvio_encargado"><span class="obligatorio">*</span>Arriendo Asegurado</label>

							<!-- 					
							<select id="selectEjecutivos" name="selectEjecutivos" class='form-control  form-select' required></select> -->

							<?php

							//************************************************************************************************************

							//************************************************************************************************************
							/*SELECTOR - listado ejectuvos a cargo - jhernandez- MANTENER PARA RENTDESK */
							$queryAsegurados = "SELECT  COALESCE(asegurado, 'NO') AS asegurado FROM propiedades.propiedad where token= '$token'";

							$data = array("consulta" => $queryAsegurados);
							$resultadoAsegurados  = $services->sendPostDirecto($url_services . '/util/objeto', $data);
							$json = json_decode($resultadoAsegurados);

							if ($json) {
								foreach ($json as $item) {

									if ($item->asegurado == NULL) {


										$opcion_asegurado = "<option value='NO' selected>NO</option>";
									} else if ($item->asegurado == 'NO') {


										$opcion_asegurado = "<option value='SI' selected>SI</option>";
									} else {

										$opcion_asegurado = "<option value='NO' selected>NO</option>";
									}

									if ($result->asegurado) {
										if (@$result->asegurado == @$item->asegurado) {
											$opcion_asegurado = $opcion_asegurado . "<option value='$item->asegurado' selected >$item->asegurado</option>";
										}
									}
								}
							} else {
								$opcion_asegurado = "<option value='NO' selected>NO</option><option value='SI'>SI</option>";
								//$opcion_asegurado = "";
							}



							$opcion_asegurado = "<select id='asegurado' name='asegurado' class='form-control  form-select' >
							$opcion_asegurado
							</select>";

							echo $opcion_asegurado;

							?>

						</div>

					</div>




				</div>
			</div>
		</div>



		<div id="section-1" class="row g-3 p-0" style="display: none;">
			<div class="col-md-12 p-0">
				<fieldset class="form-group border p-3">


					<div class="row g-3">
						<!-- <div class="col-md-3">

							<div class="form-group">
								<label><span class="obligatorio">*</span> Propietario</label>

								<div class="input-group mb-3">
									<input type="text" class="form-control" placeholder="Propietario" aria-label="Propietario" aria-describedby="button-addon2" required>
									<button class="btn btn-info m-0" type="button" id="button-addon2" data-bs-toggle="modal" data-bs-target="#modalPropietario">Buscar</button>
								</div>
							</div>
						</div> -->


						<div class="col-md-3">
							<div class="form-group">
								<label for="tipoPropiedad"><span class="obligatorio">*</span> Tipo Propiedad</label>
								<?php echo $opcion_tipo_propiedad; ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label><span class="obligatorio">*</span> Estado</label>
								<?php echo $opcion_estado_propiedad; ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="sucursal"><span class="obligatorio">*</span> Sucursal</label>
								<?php echo $opcion_sucursal; ?>

							</div>
						</div>
						<div class="col-md-3">
							<label for="fechaIngreso">Fecha Ingreso</label>
							<input name="fechaIngreso" id="fechaIngreso" class="form-control" type="date" value="<?php echo @$result->fecha_ingreso; ?>" />
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
								<label for="direccion"><span class="obligatorio">*</span> Dirección</label>
								<span id="cuentaDireccion" class="conteo-input">0/250</span>
								<input type="text" name="direccion" id="direccion" class="form-control" oninput="conteoInput('direccion','cuentaDireccion');" maxlength="250" placeholder="Dirección" required value="<?php echo @$result->direccion; ?>">
							</div>
						</div>
						<!-- Comentado 15-05-2024 ya que existe otro complemento 
						<div class="col-md-2">
							<div class="form-group">
								<label for="complemento">Complemento</label>
								<select name="complemento" id="complemento" class="form-control form-select">
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
						-->
						<div class="col-md-2">
							<div class="form-group">
								<label for="nroComplemento">Nro.</label>
								<span id="cuentaNumero" class="conteo-input">0/8</span>
								<input type="text" class="form-control" pattern="[0-9]*" maxlength="8" name="nroComplemento" id="nroComplemento" oninput="conteoInput('nroComplemento','cuentaNumero');validarNumero(this);" placeholder="Nro." value="<?php echo @$result->numero; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label for="numeroDepto">Depto</label>
								<input type="text" class="form-control"  name="numeroDepto" id="numeroDepto"  placeholder="N° depto." value="<?php echo @$result->numero_depto; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>


						<div class="col-md-2">
							<div class="form-group">
								<label for="piso">Piso</label>
								<input type="text" class="form-control" name="piso" id="piso" placeholder="0" oninput="validarNumero(this);" value="<?php echo @$result->piso; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
							</div>
						</div>
					</div>

					<div class="row g-3">
						<div class="col-md-3">
							<div class="form-group">
								<label for="coordenadas">Coordenadas</label>
								<span id="cuentaCoordenadas" class="conteo-input">0/100</span>
								<input type="text" class="form-control" maxlength="100" name="coordenadas" id="coordenadas" oninput="conteoInput('coordenadas','cuentaCoordenadas');" placeholder="Cordenadas GPS" value="<?php echo @$result->coordenadas; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
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

						<div class=" col-lg-3 form-group">
							<label><span class="obligatorio">*</span> Comuna</label>
							<div id="divcomuna"></div>
							<input type="hidden" id="hiddencomuna" name="hiddencomuna" value="<?php echo @$comuna; ?>">
						</div>
					</div>

			</div>

			</fieldset>
		</div>

		<div id="section-2" class="col-md-12 p-0" style="display: none;">
			<fieldset class="form-group border p-3">

				<div class="row g-3">

					<div class="col-sm-2">
						<div class="form-group">
							<label for="mCuadrados">M2</label>
							<input type="number" oninput="validarNumero(this);validarM2(this);" id="mCuadrados" name="mCuadrados" class="form-control" placeholder="" min="0" value="<?php if ($result->m2) {
																																															echo @$result->m2;
																																														} else {
																																														} ?>">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label for="edificado">Edificado</label>
							<select name="edificado" id="edificado" class="form-control">
								<?php echo $opcion_edificado; ?>
							</select>

						</div>
					</div>

					<div class="col-sm-2">
						<div class="form-group">
							<label for="dormitorios">Dormitorios</label>
							<input type="text" oninput="validarNumero(this);" class="form-control" maxlength="2" name="dormitorios" id="dormitorios" value="<?php if ($result->dormitorios) {
																																								echo @$result->dormitorios;
																																							} else {
																																							} ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label for="dormitoriosServicio">Dorm. Servicio</label>
							<input type="text" oninput="validarNumero(this);" class="form-control"
								maxlength="2" name="dormitoriosServicio" id="dormitoriosServicio"
								value="<?php echo $result->dormitorios_servicio ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>

					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label for="banos">Baños</label>
							<input type="text" oninput="validarNumero(this);" class="form-control" maxlength="2" name="banos" id="banos" value="<?php if ($result->banos) {
																																					echo @$result->banos;
																																				} else {
																																				} ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label for="banosVisita">Baños Visita</label>
							<input type="text" oninput="validarNumero(this);" class="form-control" maxlength="2" name="banosVisita" id="banosVisita" placeholder="0" value="<?php if ($result->banos_visita) {
																																												echo @$result->banos_visita;
																																											} else {
																																											} ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>

					<div class="col-sm-2">
						<div class="form-group">
							<label for="estacionamientos">Estacionamientos</label>
							<input type="text" oninput="validarNumero(this);" class="form-control" maxlength="2" name="estacionamientos" id="estacionamientos" placeholder="0" value="<?php if ($result->estacionamientos) {
																																															echo @$result->estacionamientos;
																																														} else {
																																														} ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>

					<div class="col-sm-2">
						<div class="form-group">
							<label for="estacionamientos">Complemento Estacionamientos</label>
							<input type="text" class="form-control" name="Complementoestacionamientos" id="Complementoestacionamientos" placeholder="" value="<?php echo @$result->complemento_estacionamiento; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label for="bodegas">Bodegas</label>
							<input type="text" oninput="validarNumero(this);" class="form-control" maxlength="2" name="bodegas" id="bodegas" placeholder="0" value="<?php if ($result->bodegas) {
																																										echo @$result->bodegas;
																																									} ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label for="bodegas">Complemento Bodegas</label>
							<input type="text" class="form-control" name="Complementobodegas" id="Complementobodegas" placeholder="" value="<?php echo @$result->complemento_bodega; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label for="logia">Logia</label>
							<input type="number" oninput="validarNumero(this);" class="form-control" maxlength="2" name="logia" id="logia" placeholder="0" value="<?php echo @$result->logias; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label for="piscina">Piscina</label>
							<select name="piscina" id="piscina" class="form-control form-select">
								<?php echo $opcion_piscina; ?>
							</select>
						</div>
					</div>
				</div>
			</fieldset>
		</div>
		<div id="section-3" class="row g-3 p-0" style="display: none;">
			<div class="col-md-12 p-0">
				<fieldset class="form-group border p-3">
					<legend>
						<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Información Fiscal y Declaración Anual de Bienes Raíces</h5>
					</legend>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="rol"><span class="obligatorio">*</span> Rol</label>
								<span id="cuentaRol" class="conteo-input">0/10</span> <a target="_blank" href="https://www4.sii.cl/busquedarolesinternetui/#!/busquedaroles">(Buscador Rol SII)</a>
								<input type="text" pattern="[0-9-]*" class="form-control" maxlength="11" name="rol" id="rol" oninput="conteoInput('rol','cuentaRol');" placeholder="Rol" required value="<?php echo @$rol_propiedad ?>">
							</div>
						</div>

						<script>
							document.getElementById('rol').addEventListener('input', function(event) {
								let valor = event.target.value;
								let patron = /^[0-9]+(-?[0-9]*)$/; // Expresión regular para permitir solo un guion entre números

								if (!patron.test(valor)) {
									// Si el valor no coincide con el patrón, se eliminarán todos los caracteres no permitidos
									event.target.value = valor.replace(/[^0-9-]/g, '');
								}

								// Validación adicional para asegurarse de que solo haya un guion
								if ((valor.match(/-/g) || []).length > 1) {
									event.target.value = valor.slice(0, -1);
								}
							});
						</script>

						<div class="col-md-2">
							<div class="form-group">
								<label for="avaluoFiscal">Avalúo Fiscal</label> <a href="https://zeus.sii.cl/avalu_cgi/br/brc110.sh?" target="_blank">(Certificado avalúo)</a>
								<span id="cuentaAvaluo" class="conteo-input">0/15</span>
								<?php if ($token) : ?>
									<input type="text" class="form-control" maxlength="15" min="1" name="avaluoFiscal" id="avaluoFiscal" placeholder="0" oninput="conteoInput('avaluoFiscal','cuentaAvaluo');" value="<?php echo @number_format(@$result->avaluo_fiscal, 0, ',', '.'); ?>">
								<?php else : ?>
									<input type="text" class="form-control" maxlength="15" min="1" name="avaluoFiscal" id="avaluoFiscal" placeholder="0" oninput="conteoInput('avaluoFiscal','cuentaAvaluo');" value="">
								<?php endif; ?>
							</div>
						</div>

						<script>
							$("#avaluoFiscal").keyup(function(event) {
								if (event.which >= 37 && event.which <= 40) {
									event.preventDefault();
								}
								$(this).val(function(index, value) {
									return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
								});
							});
						</script>

						<div class="col-md-2">
							<div class="form-group">
								<label for="amoblado">Amoblado?</label>
								<select name="amoblado" id="amoblado" class="form-control form-select">
									<?php echo $opcion_amoblado; ?>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="dfl2">DFL2?</label>
								<select name="dfl2" id="dfl2" class="form-control form-select" onchange="validaDFL2(this);">
									<?php echo $opcion_dfl2; ?>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label for="destinoArriendo"><span class="obligatorio">*</span> Destino Arriendo</label>
								<?php echo $opcion_destino_arriendo; ?>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label for="naturaleza"><span class="obligatorio">*</span> Naturaleza</label>
								<!-- <?php echo $opcion_destino; ?> -->
								<select name="naturaleza" id="naturaleza" class="form-control  form-select" required>
									<?php echo $opcion_naturaleza; ?>
								</select>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label for="dj1835">Considerar en DJ1835?</label>
								<select name="dj1835" id="dj1835" class="form-control  form-select" disabled>
									<option selected value='S'>Si</option>
									<option value='N'>Ni</option>
								</select>
							</div>
						</div>



						<div class="col-sm-2">
							<div class="form-group">
								<label for="pagoContribucion"><span class="obligatorio">*</span> Paga Contribuciones</label>
								<select name="pagoContribucion" id="pagoContribucion" class="form-control  form-select" required>
									<?php echo $opcion_paga_constribuciones; ?>
								</select>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label for="exentoContribucion"><span class="obligatorio">*</span> Exento de Contribuciones</label>
								<select name="exentoContribucion" id="exentoContribucion" class="form-control  form-select" required>
									<?php echo $opcion_exento_contribucion; ?>
								</select>
							</div>
						</div>

					</div>
				</fieldset>
			</div>


			<div class="col-md-12 p-0">
				<fieldset class="form-group border p-3" style="display: none">
					<legend>
						<h5 class="mt-0" style="font-size:14px !important; margin-bottom:5px !important;">Retenciones</h5>
					</legend>
					<div class="row g-3">


						<div class="col-sm-3">
							<div class="form-group">

								<label for="monedaRetencion"><span class="obligatorio"></span> Moneda Retención</label>
								<?php echo $opcion_tipo_moneda; ?>

							</div>
						</div>


						<div class="col-sm-3">
							<div class="form-group">
								<label for="montoRetencion">Monto Retención</label>
								<?php if ($token) : ?>
									<input type="text" class="form-control" maxlength="20" name="montoRetencion" id="montoRetencion" placeholder="0" value="<?php echo $precio; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								<?php else : ?>
									<input type="text" class="form-control" maxlength="20" name="montoRetencion" id="montoRetencion" placeholder="0" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								<?php endif; ?>

							</div>
						</div>

						<script>
							$("#montoRetencion").keyup(function(event) {
								if (event.which >= 37 && event.which <= 40) {
									event.preventDefault();
								}
								$(this).val(function(index, value) {
									return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
								});
							});
						</script>



						<div class="col-sm-3">
							<div class="form-group">
								<label for="motivoRetencion">Motivo Retención</label>
								<?php echo $opcion_motivo_retencion; ?>

							</div>
						</div>

						<div class="col-sm-3">
							<div class="form-group">
								<label for="retenerHasta">Retener Hasta</label>
								<input name="retenerHasta" id="retenerHasta" class="form-control" type="date" value="<?php echo @$result->fecha_retener; ?>" />
								<span id="startDateSelected"></span>
							</div>

						</div>

					</div>
				</fieldset>
			</div>


			
			<div class="col-md-12 p-0">
				<fieldset class="form-group border p-3">
					<legend>
						<h5 class="mt-0" style="font-size:14px !important; margin-bottom:5px !important;">Cuenta de servicios</h5>
					</legend>
					<div class="row g-3">
						<div class="col-md-3">
							<div class="form-group">
								<label for="mostrarCuentasServicio">Mostrar cuentas de Servicios en Liquidación</label>
								<select name="mostrarCuentasServicio" id="mostrarCuentasServicio" class="form-control  form-select">
									<?php echo $opcion_liquidacion; ?>
								</select>
							</div>
						</div>

						<!--
						<div class="col-sm-5">
							<span class="obligatorio">*</span> <strong>Adjuntar Mandato</strong><br>

							<input name="archivo" type="file" id="archivo" onchange="validaArchivo(this);" class="btn btn-file  opacity-100 position-relative h-auto" />

							<input name="archivo_bd" type="hidden" id="archivo_bd" value="<?php echo htmlspecialchars(@$existe_archivo); ?>">

							<?php echo @$archivo; ?>
						</div>
-->


					</div>
				</fieldset>
				<fieldset class="form-group border p-3" id="section-Documentos">

					<legend>
						<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Documentos</h5>
					</legend>

					<button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" title="Agregar Documento" data-bs-toggle="modal" data-bs-target="#modalDocumentoIngreso">
						<span>Agregar Documento</span>
					</button>

					<div class="modal fade" id="modalDocumentoIngreso" tabindex="-1" aria-labelledby="modalDocumentoIngresoLabel" aria-hidden="true" data-bs-backdrop="static">
						<div class="modal-dialog" style="max-width: 800px;">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="modalChequesIngresoLabel">Ingreso Documento</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
									<div class="modal-body" id="modalBodyOriginal">
										<div class="row" style="width:100%;">
											<div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
												<div class="form-group">
													<label for="documentoTitulo">Titulo</label>
													<input type="text" class="form-control" min="0" name="documentoTitulo" id="documentoTitulo" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
												</div>
											</div>

										</div>

										<div class="row" id="seccionDocumento_archivo_0">
											<div class="col-lg-6">
												<div class="form-group">
													<label for="archivo">Carga Documento</label>
													<input id="archivo_0" name="archivo_0" type="file" onchange="validaArchivo(this,<?php echo $peso_archivo; ?>);" class="btn btn-file btn-xs opacity-100 position-relative h-auto  btn-upload" />
												</div>
											</div>
											<!--	                        
                        <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                            <div class="form-group">
                                <label for="documentoFecha">Fecha vencimiento</label>
                                <input name="documentoFecha" id="documentoFecha" onchange="recalcularMes()" class="form-control" type="date" value="" />
                                <span id="startDateSelected"></span>
                            </div>
                        </div>
-->
											<div class="col-lg-4">
												<div class="form-group">
													<label for="documentoFechaCreacion">Fecha vencimiento</label>
													<input name="documentoFecha_0" id="documentoFecha_0" onchange="recalcularMes()" class="form-control" type="date" value="" />
													<span id="startDateSelected"></span>
												</div>

											</div>

											<div class="col-lg-2 align-self-end" id="botonEliminaSeccion">
												<div class="form-group">
													<button id='eliminarRegistroDocumento' onclick='eliminarSeccion("seccionDocumento_archivo_0")' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'>
														<i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i>
													</button>
												</div>
											</div>
										</div>
									</div>



								</div>
								<div class="row">
									<div class="form-group">
										<label><strong>Importante :</strong> El Archivo debe ser una imagen, word, excel o pdf y no debe superar los <?php echo $peso_archivo; ?>MB </label>
									</div>
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-info" onClick='resetForm();'>Cerrar</button>
									<button type="button" class="btn btn-danger" id="addItemButton" onClick='cargaDocumento();'>Guardar</button>
								</div>
							</div>
						</div>
					</div>

					<div class="modal fade" id="modalDocumentoEditar" tabindex="-1" aria-labelledby="modalDocumentoEditarLabel" aria-hidden="true" data-bs-backdrop="static">
						<div class="modal-dialog" style="max-width: 800px;">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="modalDocumentoEditarLabel">Editar documento</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">

									<div class="row" style="width:100%;">
										<!-- Se deja solo el nombre documentoTituloEditar para editar
					 <div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                            <div class="form-group">
                                <label for="documentoTituloEditar">Titulo</label>
                                <input type="hidden" class="form-control" min="0" name="documentoTituloEditar" id="documentoTituloEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>

                    </div> -->
										<input type="hidden" class="form-control" min="0" name="documentoTituloEditar" id="documentoTituloEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">

										<div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
											<label for="archivoEditar">Carga Documento</label>
											<input id="archivoEditar" name="archivoEditar" type="file" onchange="validaArchivo(this,<?php echo $peso_archivo; ?>);" class="btn btn-file btn-xs opacity-100 position-relative h-auto  btn-upload" />
										</div>
										<div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
											<div class="form-group">
												<label for="documentoFechaEditar">Fecha vencimiento</label>
												<input name="documentoFechaEditar" id="documentoFechaEditar" onchange="recalcularMes()" class="form-control" type="date" value="" />
												<span id="startDateSelected"></span>
											</div>

										</div>
									</div>


									<div class="row">
										<div class="d-flex" style="gap: .5rem;">
											<a id="linkDocumento2" style="color: #51bcda;"></a>
											<a id="linkDocumento" download type="button" class="btn btn-info m-0 d-flex" style="padding: .5rem" aria-label="documentoActual" title="documentoActual"></a>
										</div>
										<div class="form-group">
											<label><strong>Importante :</strong> El Archivo debe ser una imagen, word, excel o pdf y no debe superar los <?php echo $peso_archivo; ?>MB </label>
										</div>
									</div>


									<input type="hidden" class="form-control" min="0" name="documentoTokenEditar" id="documentoTokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">

								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
									<button type="button" class="btn btn-danger" id="addItemButton" onClick='editarDocumento()'>Guardar</button>
								</div>
							</div>
						</div>
					</div>

					<div class="modal fade" id="modalTituloEditar" tabindex="-1" aria-labelledby="modalTituloEditarLabel" aria-hidden="true" data-bs-backdrop="static">
						<div class="modal-dialog" style="max-width: 800px;">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="modalDocumentoEditarLabel">Editar Titulo</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">

									<div class="row" style="width:100%;">
										<div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
											<div class="form-group">
												<label for="TituloEditar">Titulo</label>
												<input type="text" class="form-control" min="0" name="TituloEditar" id="TituloEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
											</div>

										</div>
									</div>


									<input type="hidden" class="form-control" min="0" name="TokenEditar" id="TokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">

								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
									<button type="button" class="btn btn-danger" id="addItemButton" onClick='editarTituloDocumento()'>Guardar</button>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<div class="table-responsive overflow-auto">

								<table id="documentos" class="table table-striped" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Título</th>
											<th>Nombre archivo</th>
											<th>Fecha Carga</th>
											<th>Fecha Vencimiento</th>
											<th>Documento</th>
											<th>Fecha modificación</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>


									</tbody>
								</table>
							</div>
						</div>
					</div>
				</fieldset>

			</div>
		</div>

		<div class="row g-3">
			<div class="col-lg-12 text-center">
				<a href="index.php?component=propiedad&view=propiedad_list">
					<button type="button" class="btn btn-info"> &lt;&lt; volver </button>
				</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button id="bt_aceptar_propiedad" type="submit" form="formulario-propiedad" class="btn btn-danger" style="display:none"> Aceptar </button>
			</div>





		</div>


		<input type="hidden" id="token" name="token" value="<?php echo @$_GET['token']; ?>">

		<!-- Hidden input field to store item data -->
		<input type="hidden" id="persona" name="persona" value="<?php echo $resultPropietario ? $resultPropietario->token_propietario : ''; ?>">

		<input type="hidden" id="hiddenregion" name="hiddenregion" value="<?php echo @$pais; ?>">
		<input type="hidden" id="hiddenregion" name="hiddenregion" value="<?php echo @$region; ?>">
		<input type="hidden" id="hiddenregion" name="hiddenregion" value="<?php echo @$comuna; ?>">

	</form>
</div>











<!-- Modal Propietario - Propiedad-->
<!-- <div class="modal fade" id="modalPropietario" tabindex="-1" aria-labelledby="modalPropietarioLabel" aria-hidden="true">
	<div class="modal-dialog  modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalPropietarioLabel">Propietario</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<?php /*
				include("./components/propietario/propietario_formulario.php");
				*/ ?>
			</div>
		 <div class="modal-footer">
				<button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary">Crear</button>
			</div> 
		</div>
	</div>
</div> -->

<script>
	$(document).ready(function() {
		<?php echo @$loadPaisComunaRegion; ?>
	});
</script>

<script>
	function validarNumero(input) {
		// Obtener el valor actual del campo
		var valor = input.value;

		// Eliminar guiones y caracteres no numéricos
		var valorNumerico = valor.replace(/[^0-9-]/g, '');

		// Eliminar guiones adicionales (por ejemplo, si el usuario ingresó "--")
		valorNumerico = valorNumerico.replace(/-{2,}/g, '-');

		valorNumerico = valorNumerico.replace(/-/g, '');

		// Si el valor ha cambiado, actualizar el campo
		if (valor !== valorNumerico) {
			input.value = valorNumerico;
		}
	}
</script>

<script>
	function validarM2(input) {
		var valor = parseFloat(input.value); // Obtener el valor ingresado y convertirlo a número
		console.log("validarM2", valor);
		var umbral = 140; // Umbral de validación, puedes cambiarlo según tus necesidades
		var alertaMostrada = localStorage.getItem('alertaMostrada');
		if (valor >= umbral && !alertaMostrada) {
			// Mostrar Sweet Alert si el valor supera el umbral
			Swal.fire({
				title: "Aviso",
				text: "las propiedades que cuenten con una superficie construida que no se supere los 140 m2 podria estar sujeto a DFL2",
				icon: "warning",
			});
			localStorage.setItem('alertaMostrada', true);
		}

	}
</script>




<script>
	function bloquear() {
		var estadoPropiedadBloqueo = $('#estadoPropiedad').val();
		if (estadoPropiedadBloqueo == 6) {

			$('#bt_aceptar_propiedad').hide();
			$('#pais').prop('disabled', true);
			$('#regioncom').prop('disabled', true);
			$('#comunacom').prop('disabled', true);


		}
	}
	setTimeout(bloquear, 1000);
</script>


