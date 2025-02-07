<script src="js/region_ciudad_comuna.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
    loadPropiedad_List();
</script>

<script>
/*
var tablaPropiedades;
    $(document).ready(function() {
         tablaPropiedades = $('#propiedades').DataTable({
            "pagingType": "full_numbers", // Tipo de paginación
            "pageLength": 10, // Número de filas por página
			"lengthMenu": [[10, 25, 50, 100, 5000], [10, 25, 50, 100, "Todos"]],
			"columnDefs": [ { orderable: false, targets: [9] } ],
			"language": {
				"lengthMenu": "Mostrar _MENU_ registros por página",
				"zeroRecords": "No encontrado",
				"info": "Mostrando página _PAGE_ de _PAGES_",
				"infoEmpty": "No existen registros para mostrar",
				"infoFiltered": "(filtrado desde _MAX_ total de registros)",
				"loadingRecords": "Cargando...",
				"processing":     "Procesando...",
				"search":     "Buscar",
				"paginate": {
					"first":      "Primero",
					"last":       "Último",
					"next":       "Siguiente",
					"previous":   "Anterior"
				},
				"buttons": {
					"copy":      "Copiar"
				},
			}
        });
    });
	*/
	/*
	      $('select[name="propiedades_length"]').on('change', function() {
        var valorPropiedadesLength = $(this).val();
        console.log('El valor de propiedades_length es:', valorPropiedadesLength);
    });*/
</script>


<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propiedades</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Propiedades</li>
            </ol>
        </div>
    </div>

</div>


<div class="content content-page">

    <div class="d-flex justify-content-end">

        <div class="card card-button">
            <div class="card-body"> <a href="<?php echo redirectToCreatePropiedad() ?>" style="justify-content: center;
                    display: inline-flex;
                    align-items: center;
                    padding: 0;
                    gap: 0.5rem;
                    text-decoration: none;"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i>
                    <span style="font-size:1rem;">Agregar una Propiedad</span>
                </a></div>
        </div>
    </div>



    <div class="row top-100">
        <div class="col p-0">
            <form id="filtros-busqueda" class="my-3" method="post" action="">
									<div class="col-md-4">
                                        <div class="form-group">
                                            <label>num_reg</label>
                             <!--   <input type="text" class="form-control" id="filtro_codigo_propiedad" name="filtro_codigo_propiedad" value="" placeholder="Ingrese Código">  -->
											<input type="text" id="num_reg" value="" name="ejecutivo" class="form-control" placeholder="Correo Ejecutivo"  onblur="ocultarAutocomplete('codigo_propiedad');" autocomplete='off' onkeyup='buscarPropiedadAutocomplete(this.value,"codigo_propiedad");'><div id='suggestions_codigo_propiedad'></div>
										</div>
                                    </div>
			
                <fieldset class="form-group border p-3">
                    <legend>
                        <h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Criterios de Búsqueda <small>(Debe ingresar al menos un campo)</small></h5>
                    </legend>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Código de Propiedad</label>
                             <!--   <input type="text" class="form-control" id="filtro_codigo_propiedad" name="filtro_codigo_propiedad" value="" placeholder="Ingrese Código">  -->
								<input type="text" id="codigo_propiedad" value="" name="codigo_propiedad" class="form-control" placeholder="Cód.Propiedad o Dirección"  onblur="ocultarAutocomplete('codigo_propiedad');" autocomplete='off' onkeyup='buscarPropiedadAutocomplete(this.value,"codigo_propiedad");'><div id='suggestions_codigo_propiedad'></div>
                            </div>
                        </div>
						<div class="col-md-4">
                                       <div class="form-group">
                                           <label>Propietario</label>
										<input type="text" id="propietario" value="" name="propietario" class="form-control" placeholder="Nombre o rut"  onblur="ocultarAutocomplete('codigo_propiedad');" autocomplete='off' onkeyup='buscarPropiedadAutocomplete(this.value,"codigo_propiedad");'><div id='suggestions_codigo_propiedad'></div>
									</div>
                         </div>
                    <div class="collapse col-12 col-md-12 col-lg-12 p-0" id="collapseFiltros">
                        <form>
                            <fieldset class="form-group border p-3">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tipo Propiedad</label>
                                            <?php echo $opcion_tipo_propiedad; ?>
                                        </div>
                                    </div>
									<div class="col-md-4">
                                        <div class="form-group">
                                            <label>Oficina captadora</label>
                                            <?php echo $opcion_sucursal; ?>
                                        </div>
                                    </div>
									<div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ejecutivo</label>
                             <!--   <input type="text" class="form-control" id="filtro_codigo_propiedad" name="filtro_codigo_propiedad" value="" placeholder="Ingrese Código">  -->
											<input type="text" id="ejecutivo" value="" name="ejecutivo" class="form-control" placeholder="Correo Ejecutivo"  onblur="ocultarAutocomplete('codigo_propiedad');" autocomplete='off' onkeyup='buscarPropiedadAutocomplete(this.value,"codigo_propiedad");'><div id='suggestions_codigo_propiedad'></div>
										</div>
                                    </div>
                                </div>
								<div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Estado propiedad</label>
                                            <?php echo $opcion_estado_propiedad; ?>
                                        </div>
                                    </div>
									<!--
									<div class="col-lg-3 form-group">
										<label><span class="obligatorio">*</span> País</label>
										<div id="divpais"></div>
										<input type="hidden" id="hiddenpais" name="hiddenpais" value="<?php echo @$pais; ?>">
									</div>
			-->
									<div class="col-md-4">
										<label> Región</label>
										<div id="divregion"></div>
										<input type="hidden" id="hiddenregion" name="hiddenregion" value="<?php echo @$region; ?>">
									</div>
			
									<div class="col-md-4">
										<label> Comuna</label>
										<div id="divcomuna"></div>
										<input type="hidden" id="hiddencomuna" name="hiddencomuna" value="<?php echo @$comuna; ?>">
									</div>

								</div>
                            </fieldset>
                        </form>
                    </div>

                    </div>
                    <div class="row g-3">
                        <div class="col">
							<button class="btn btn-info btn-mas-filtros"  type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiltros" aria-expanded="false" aria-controls="collapseFiltros" onClick="masMenosFiltros();" id="btnMasFiltros"> Más Filtros <i class='fas fa-chevron-down'></i></button>

                            <button type="submit" class="btn btn-primary">Buscar</button>

                        </div>

                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <div class="row">
        <div id="resultado" name="resultado" style="width:100%;"></div>
    </div>


    <div class="herramientas">
        <button type="button" class="btn btn-info btn-sm" onClick="document.location.href='index.php?component=propiedad&view=propiedad_list';">
            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
    </div>
    <div class="card">
        <div class="card-body">
            <?php if (count($dataTablePropiedades) > 0) : ?>

                <div class="table-responsive overflow-auto">
                    <table id="propiedades" class="table table-striped" cellspacing="0" width="100%">

                        <thead>
                            <tr>
                                <th>Ficha Técnica</th>
                                <th>Oficina captadora</th>
                                <th>Ejecutivo</th>
                                <th style="min-width:220px;" ><i class='fa-solid fa-house-user' style='color:#313131;font-size:12px;' title='Propietario' ></i> Propietario / <i class='fa-solid fa-user-large' style='font-size:10px; color:#707070;' title='Beneficiario'></i> Beneficiario</th>
								<th>Tipo Propiedad</th>
								<th style="min-width:220px;" >Dirección</th>
								<th>Comuna</th>
								<th>Región</th>
                                <th>Estado</th>

                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($dataTablePropiedades as $obj) : ?>
                                <tr>

                                    <td>
                                        <a href="<?php echo "index.php?component=propiedad&view=propiedad_ficha_tecnica&token=" . $obj['token']; ?>" class=" link-info" style="padding: .5rem;" title="Ver Ficha Técnica">
                                            <?php echo '#' . $obj['ficha_tecnica']; ?>
                                        </a>
                                    </td>

                                    <td><?php echo $obj['nombre_sucursal']; ?></td>
                                    <td><?php echo $obj['ejecutivo']; ?></td>
									<td><?php echo $obj['propietario']; ?></td>
									<td><?php echo $obj['tipo_propiedad']; ?></td>
									<td><?php echo $obj['direccion']; ?></td>
									<td><?php echo $obj['comuna']; ?></td>
                                    <td><?php echo $obj['region']; ?></td>
                                    <td><?php echo $obj['id_estado_propiedad']; ?></td>

                                    <td>
                                        <div class="d-flex" style="gap: .5rem;">
                                            <a href="<?php echo redirectToPropiedadUrl($obj['token']) ?>" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
                                                <i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
                                            </a>
											<button type="button" onclick="eliminarPropiedad(<?php echo $obj['ficha_tecnica'] ?>)" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
                                                <i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>

                    </table>
                </div>
            <?php else : ?>
                <div class="text-center m-3">
                    <p>No hay registros por el momento</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
	$(document).ready(function() {
		<?php echo @$loadPaisComunaRegion; ?>
	});
</script>