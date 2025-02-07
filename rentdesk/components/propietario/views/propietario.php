<script src="js/region_ciudad_comuna.js"></script>
<script src="js/region_ciudad_comuna_com.js"></script>
<script>
    $(document).ready(function() {

        $('#DNI').on('keypress', function(event) {
            var keyCode = event.which;
            var keyChar = String.fromCharCode(keyCode);

            // Permitir solo letras, números, un espacio y un guion (si aún no hay uno)
            if (!/[a-zA-Z0-9\s]/.test(keyChar) && keyChar !== '-' && keyCode !== 8) {
                event.preventDefault();
            }

            // Permitir solo un guion
            if (keyChar === '-' && $(this).val().indexOf('-') !== -1) {
                event.preventDefault();
            }
        });

        $('#form2').submit(function(event) {
            var rut = $('#DNI').val();
            var rutRegex = /^\d{1,8}-[0-9Kk]$/;

            if (!rutRegex.test(rut)) {
                alert('Por favor, ingrese un RUT válido en el formato 12345678-K');
                event.preventDefault(); // Evita que el formulario se envíe
            }
        });
		
    });
</script>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		const addItemButton = document.getElementById("addItemButton");
		const itemList = document.getElementById("itemList");
		const itemDataInput = document.getElementById("itemData");

		let items = []; // Array to store item data

		// Function to render added items as a table
		function renderItems() {
			itemList.innerHTML = ""; // Clear the list

			// Create table header
			const tableHeader = document.createElement("thead");
			tableHeader.innerHTML = `
                  <tr>
                      <th>N° #</th>
					  <th>Nombre Titular</th>
                      <th>RUT Titular</th>
                      <th>Correo Electrónico</th>
                      <th>Id Banco</th>
                      <th>Id Tipo Cuenta</th>
                      <th>Nro. Cuenta</th>
                  </tr>
              `;

			// Create table body
			const tableBody = document.createElement("tbody");

			items.forEach((item, index) => {
				const row = document.createElement("tr");

				row.innerHTML = `
                      <td>${index + 1}</td>
					  <td>${item.nombreTitular}</td>
                      <td>${item.rutTitular}</td>
                      <td>${item.correoElectronico}</td>
                      <td>${item.banco.id}</td>
                      <td>${item.tipoCuenta.id}</td>
                      <td>${item.numero}</td>
								`;
				tableBody.appendChild(row);
			});

			// Append table header and body to the table
			itemList.appendChild(tableHeader);
			itemList.appendChild(tableBody);
		}

		// Function to set an item as principal
		function setPrincipal(index) {
			items.forEach((item, i) => {
				item.principal = i === index;
			});
			console.log("ITEMS: ", items);
			// renderItems();
		}

		// Event listener for adding an item
		addItemButton.addEventListener("click", function() {

			const nombreTitular = document.getElementById("nombreTitular").value;
			const rutTitular = document.getElementById("rutTitular").value;
			const emailTitular = document.getElementById("emailTitular").value;
			const banco = document.getElementById("banco").value;
			const ctaBanco = document.getElementById("cta-banco").value;
			const nameBanco = $('#banco option:selected').text();
			const nameCtaBanco = $('#cta-banco option:selected').text();
			const numCuenta = document.getElementById("numCuenta").value;
			if (nombreTitular == "" || rutTitular == "" || emailTitular == "" || banco == "" || ctaBanco == "" || numCuenta == "") {
				Swal.fire({
					title: "Complete lo datos",
					text: "Porfavor complete los datos de la cuenta ",
					icon: "warning",
				});
			} else {
				items.push({
					banco: {
						id: Number(banco),
					},
					correoElectronico: emailTitular,
					nombreTitular,
					rutTitular,
					habilitado: true,
					numero: numCuenta,
					principal: true,
					tipoCuenta: {
						id: Number(ctaBanco),
					},

				});

				// Update the hidden input field with the updated item data
				itemDataInput.value = JSON.stringify(items);


				//Limpiar formulario 

				//Se comenta
				// $('#nombreTitular').val("")
				// $('#rutTitular').val("")
				// $('#emailTitular').val("")
				// $('#banco').val("")
				// $('#cta-banco').val("")
				// $('#numCuenta').val("")
				// Render the updated list of items
				renderItems();
			}
		});
	});
</script>


<div id="header" class="header-page">
	<div>
		<!-- <h2 class="mb-3">Propietario</h2> -->
		<div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
			<ol class="breadcrumb d-flex align-items-center m-0">
				<li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
				<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propietario&view=propietario_list" style="text-decoration: none;color:#66615b">Propietarios</a></li>
				<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Propietario</li>
			</ol>
		</div>
	</div>
</div>

<div class="content content-page">

	<div>
		<span>
			<span class="obligatorio">*</span> Indica Campos Obligatorios
		</span>
	</div>
	<div class="row g-3">
		<fieldset class="form-group border p-3">
			<div class="row g-3">
				<div class="col-md-3">
					<label><span class="obligatorio">*</span> Busqueda por<?php if ($flag_solo_rut != 1) { ?> DNI /<?php } ?> RUT</label>
					<div class="input-group mb-3">
						<input type="text" class="form-control" id="DNI" name="nombre_cliente" placeholder="Nombre o Rut" value="<?php echo isset($token) && $result ? $result->dni : ''; ?>" onblur="ocultarAutocomplete('DNI');" form="form2" autocomplete='off' onkeyup='buscarClienteAutocompleteGenerica(this.value,"DNI");'>
						<button class="btn btn-info m-0" type="button" id="button-addon2" onClick="busquedaDNI();" data-bs-toggle="modal" data-bs-target="#modalArrendatario">Buscar</button>
					</div>
				</div>
			</div>
			<div class="">
				<div id='suggestions_DNI' class="suggestionsAutoComplete" style="margin-top: -20px"></div>
			</div>

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
		</fieldset>

	</div>



	<!-- Form to enter data of item -->

	<?php
	if ($cantidadResultados == 0) {
		if (isset($token)) { ?>

			<!--id="addItemForm"-->
			<form id="form-cuentas-edit" method="post" action="javascript: editProp()" enctype="multipart/form-data" class="my-3">
			<?php } else { ?>

				<form method="post" id="form-cuentas" action="javascript: guardarProp()" enctype="multipart/form-data" class="my-3">
				<?php } ?>

				<!-- Hidden input field to store item data -->
				<input type="hidden" id="persona" name="persona">
				<fieldset id="section-4" class="form-group border p-3" style="display: none;">
					<legend style="display: flex;
						align-items: center;
						justify-content: space-between;">
						<h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Cuentas Bancarias</h5>
					</legend>
					<div class="row g-3">
						<div class="col-md">

							<a class="btn btn-sm btn-success" onclick="CopiarDatos()">Copiar Datos Cliente</a>


							<fieldset class="form-group border-0 p-3">

								<legend>
									<h5 class="mt-0" style="font-size:12px !important;margin-bottom:5px !important;">Datos Cuenta</h5>
								</legend>
								<div class="row g-3">
									<div class="col-lg-4">
										<div class="form-group">
											<label><span class="obligatorio">*</span> Nombre Titular</label>
											<input type="text" class="form-control" maxlength="100" name="nombreTitular" id="nombreTitular" placeholder="Nombre Titular" data-validation-required value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label><span class="obligatorio">*</span> RUT</label>
											<input type="text" oninput="checkRut(this);" class="form-control" maxlength="100" name="rutTitular" id="rutTitular" placeholder="Rut" data-validation-required value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
										</div>
									</div>

									<div class="col-lg-4">
										<div class="form-group">
											<label><span class="obligatorio">*</span> Email Titular</label>
											<input type="email" class="form-control" maxlength="100" name="emailTitular" id="emailTitular" placeholder="Email Titular" data-validation-required value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
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
									<input type="hidden" name="idCuenta" value="">
									<div class="col-lg-4">
										<div class="form-group">
											<label><span class="obligatorio">*</span> Número de Cuenta</label>
											<input type="number" class="form-control" maxlength="100" name="numCuenta" id="numCuenta" placeholder="Número Cuenta" data-validation-required value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">

										</div>
									</div>
								</div>
								<?php if (isset($token)) { ?>
									<div class="d-flex justify-content-end">
										<button type="button" class="btn btn-info btn-sm" style="font-size: 14px;" onclick="addCuentaDirecta()">Agregar Cuenta</button>
									</div>
								<?php } else { ?>
									<div class="d-flex justify-content-end">
										<button type="button" class="btn btn-info btn-sm" style="font-size: 14px;" onclick="addForm()">Agregar Cuenta</button>
									</div>
								<?php } ?>
							</fieldset>
						</div>
					</div>

				<?php } else {
				echo '<input type="hidden" id="persona" name="persona">';
			}; ?>
				<!-- Dynamic list to render added items -->


				<!-- Hidden input field to store item data -->
				<input type="hidden" id="itemData" name="itemData">
				<fieldset id="section-4" class="form-group border-0 p-3">
					<table id="info-cuentas" class="table table-striped" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Banco</th>
								<th>Tipo de Cuenta</th>
								<th> Número de Cuenta</th>
								<th>RUT</th>
								<th>Nombre Titular</th>
								<th>Email Titular</th>
								<?php if (isset($token)) {
								?>
									<th>Acciones</th>
								<?php
								} ?>
							</tr>
						</thead>
						<tbody>
							<?php if (isset($token)) {

								//$arrayResultado = json_decode($resultadoCuentas);
								foreach ($arrayResultado as $resultado) {

							?>
									<tr>
										<td><?php echo $resultado->nombre_banco ?> </td>
										<td><?php echo $resultado->tipo_cuenta ?></td>
										<td><?php echo $resultado->numero ?></td>
										<td><?php echo $resultado->rut_titular ?></td>
										<td><?php echo $resultado->nombre_titular ?></td>
										<td><?php echo $resultado->correo_electronico ?></td>
										<td>
											<div class="d-flex" style="gap: .5rem;">
												<a data-bs-toggle="modal" onclick="cargarCuenta('<?php echo $resultado->id_banco ?>','<?php echo $resultado->rut_titular ?>','<?php echo $resultado->nombre_titular ?>','<?php echo $resultado->correo_electronico ?>','<?php echo $resultado->id ?>','<?php echo $resultado->id_tipo_cuenta ?>','<?php echo $resultado->numero ?>')" data-bs-target="#modalChequesEditarLabel" type="button" class="btn btn-info m-0 d-flex" style="padding: .5rem;" aria-label="Editar" title="Editar">
													<i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
												</a>
												<button onclick="eliminarCuenta(<?php echo $resultado->id ?>)" type="button" class="btn btn-danger m-0 d-flex" style="padding: .5rem;" title="Eliminar">
													<i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
												</button>
											</div>
										</td>
									</tr><!-- Modal Cheques - Editar Cheque-->

									<div class="modal fade" id="modalChequesEditarLabel" tabindex="-1" aria-labelledby="modalChequesEditarLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="modalChequesEditarLabel">Editar Cuenta</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body">
													<form name="cuenta_formulario_Editar" id="cuenta_formulario_Editar" method="post" enctype="multipart/form-data" class="my-3">
														<div class="row">
															<div class="col mb-3">
																<label for="nombreTitularEdit" class="form-label"><span class="obligatorio">*</span> Nombre Titular</label>
																<input type="text" class="form-control" maxlength="100" name="nombreTitularEdit" id="nombreTitularEdit"
																	placeholder="Nombre Titular" data-validation-required="" value=""
																	onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"
																	form='form22' required>
															</div>

															<div class="col mb-3">
																<label for="rutTitularEdit" class="form-label"><span class="obligatorio">*</span> RUT</label>
																<input type="text" oninput="checkRut(this);" class="form-control" maxlength="100" name="rutTitularEdit" id="rutTitularEdit"
																	placeholder="Rut" data-validation-required="" value=""
																	onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" form='form22' required>
															</div>
														</div>
														<div class="row">
															<div class="col mb-3">
																<label for="emailTitularEdit" class="form-label"><span class="obligatorio">*</span>Email Titular</label>
																<input type="email" class="form-control" maxlength="100" name="emailTitularEdit" id="emailTitularEdit"
																	placeholder="Email Titular" data-validation-required="" value=""
																	onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" form='form22' required>
															</div>
															<div class="col mb-3">
																<label for="bancoEdit" class="form-label"><span class="obligatorio">*</span>Banco</label>
																<?php echo $opcion_banco_edit; ?>
															</div>
														</div>
														<div class="row">
															<div class="col mb-3">
																<label for="tipoCuentaEdit" class="form-label"><span class="obligatorio">*</span>Tipo de Cuenta</label>
																<?php echo $opcion_cta_banco_edit; ?>
															</div>
															<div class="col mb-3">
																<label for="numCuentaEdit" class="form-label"><span class="obligatorio">*</span>Número de Cuenta</label>
																<input type="text" class="form-control" maxlength="100" name="numCuentaEdit" id="numCuentaEdit"
																	placeholder="Número Cuenta" data-validation-required="" value=""
																	onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" form='form22' required>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-info" data-bs-dismiss="modal" form='form22'> Cerrar</button>
															<button type="button" class="btn btn-primary" form='form22' onclick="editarCuentaGuardar()">Guardar</button>
														</div>
													</form>
												</div>

											</div>
										</div>
									</div>

							<?php }
							} ?>

						</tbody>
					</table>
				</fieldset>


				<!-- Submit button to send the list -->
				<!-- <button type="submit" form="addItemForm">Submit</button> -->
				</fieldset>



				<div class="row g-3">
					<div class="col-lg-12 text-center">
						<a href="index.php?component=propietario&view=propietario_list">
							<button type="button" class="btn btn-info"> &lt;&lt; volver </button>

							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<?php if (isset($token)) { ?>
								<a href="index.php?component=propietario&view=propietario_list">
									<button type="button" class="btn btn-danger"> Aceptar </button>
								</a>
							<?php } else {
							?>
								<button type="submit" id="guardarProp" style="display: none" class="btn btn-danger"> Aceptar </button>

							<?php } ?>
					</div>
				</div>

				</form>

</div>