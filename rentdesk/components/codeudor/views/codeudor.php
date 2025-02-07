<script src="js/region_ciudad_comuna.js"></script>
<script src="js/region_ciudad_comuna_com.js"></script>
<script>
        $(document).ready(function() {
            $('#DNI').on('keypress', function(event) {
                var keyCode = event.which;
                var keyChar = String.fromCharCode(keyCode);
                
                // Permitir solo letras, números y un guion (si aún no hay uno)
                if (!/[a-zA-Z0-9]/.test(keyChar) && keyChar !== '-' && keyCode !== 8) {
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
	/*
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
                      <th>Item #</th>
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
			const numCuenta = document.getElementById("numCuenta").value;

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

			// Render the updated list of items
			renderItems();
		});
	});
	*/
</script>


<div id="header" class="header-page">
	<div>
		<!-- <h2 class="mb-3">Propietario</h2> -->
		<div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
			<ol class="breadcrumb d-flex align-items-center m-0">
				<li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
				<li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=codeudor&view=codeudor_list" style="text-decoration: none;color:#66615b">Codeudores</a></li>
				<li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Codeudor</li>
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
						<label><span class="obligatorio">*</span> Busqueda por<?php if ($flag_solo_rut !=1) {?> DNI /<?php } ?> RUT</label>
						<div class="input-group mb-3">							            
                                <input type="text" class="form-control" id="DNI" name="nombre_cliente" 
                                placeholder="Nombre o Rut" value="<?php echo isset($token) && $result ? $result->dni : ''; ?>"
                                onblur="ocultarAutocomplete('DNI');"  form ="form2"
                                autocomplete='off' onkeyup='buscarClienteAutocompleteGenerica(this.value,"DNI");' >		
								<button class="btn btn-info m-0" type="button" id="button-addon2" onClick="busquedaDNI();" data-bs-toggle="modal" data-bs-target="#modalArrendatario">Buscar</button>
						</div>
					</div>
				</div>
				<div class="">
					<div id='suggestions_DNI'  class="suggestionsAutoComplete" style="margin-top: -20px"></div>
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
		<?php if(isset($token)){ ?>
		
		<!--id="addItemForm"-->
		<form  id="form-cuentas-edit" method="post" action="javascript: editProp()" enctype="multipart/form-data" class="my-3">
		<?php }
		else{ ?>
		<form  method="post" id="form-cuentas" action="javascript: crearCodeudor()" enctype="multipart/form-data" class="my-3">
		<?php }?>
		
	

				<!-- Submit button to send the list -->
				<!-- <button type="submit" form="addItemForm">Submit</button> -->
			</fieldset>
			<input type="hidden" id="persona" name="persona">
		

		<div class="row g-3">
			<div class="col-lg-12 text-center">
				<a href="index.php?component=codeudor&view=codeudor_list">
					<button type="button" class="btn btn-info"> &lt;&lt; volver </button></a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php if(isset($token)){ ?>
				<a href="index.php?component=codeudor&view=codeudor_list">			
				<button type="button" class="btn btn-danger" > Aceptar </button>
				</a>
					<?php } else{
						?>
			   <button type="submit" id="submitCodeudor" style="display:none"  class="btn btn-danger" > Aceptar </button>

					<?php } ?>			</div>
		</div>
</form>

</div>