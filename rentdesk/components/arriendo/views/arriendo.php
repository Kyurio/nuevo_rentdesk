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


<div class="d-flex justify-content-end">
	<div class="card">
		<div class="card-body card-button"> <a href='index.php?component=arrendatario&view=arrendatario&nav=<?php echo $pag_origen; ?>' style="justify-content: center;
                display: inline-flex;
                align-items: center;
                padding: 0;
                gap: 0.5rem;
                text-decoration: none;"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i>
				<span style="font-size:1rem;">Agregar un Arrendatario</span>
			</a></div>
	</div>
</div>

<div class="content content-page">

	<div>
		<?php if ($token): ?>
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
						<!--
						<div class="form-group">
							<label for="propiedad"><span class="obligatorio">*</span> Propiedad</label>
							<?php echo $opcion_propiedad; ?>
						</div> -->
						<div class="col-md">
							<div class="form-group">
								<label> <span class="obligatorio">*</span> Código de Propiedad</label>
								<!--   <input type="text" class="form-control" id="filtro_codigo_propiedad" name="filtro_codigo_propiedad" value="" placeholder="Ingrese Código">  -->
								<input type="text" id="codigo_propiedad" value="" name="codigo_propiedad" class="form-control" placeholder="Cód.Propiedad o Dirección o Nro Contrato"
									onblur="ocultarAutocomplete('codigo_propiedad');" autocomplete='off'
									onkeyup='buscarPropiedadAutocomplete(this.value,"codigo_propiedad");' required>
								<div id='suggestions_codigo_propiedad'></div>
							</div>
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
							<label for="codeudor"></span> Codeudor</label>
							<?php echo $opcion_codeudor; ?>
						</div>
					</div>
				</div>
				<button type="button" class="btn btn-danger" class="btn btn-danger" onclick="creaArriendo();"> Crear Arriendo </button>

			</fieldset>



	</form>


	<script>
		// Evitar que el formulario se envíe cuando se presiona "Enter"
		document.getElementById("formulario").addEventListener("keypress", function(event) {
			if (event.key === "Enter") {
				event.preventDefault(); // Prevenir que "Enter" envíe el formulario
			}
		});

		// Manejar el envío del formulario solo con el botón de click
		document.getElementById("formulario").addEventListener("submit", function(event) {
			event.preventDefault(); // Evita el envío por defecto
			enviarRentdesk(); // Llama a tu función de envío
		});
	</script>
</div>