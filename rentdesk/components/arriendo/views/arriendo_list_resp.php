<script>
    loadPropiedad();
	cargarFiltrosGuardados();
</script>

<script>

    // Función para almacenar los valores del formulario en el almacenamiento local
    function guardarFiltros() {
        var estadoArriendo = document.getElementById('EstadoArriendo').value;
        var codigoPropiedad = document.getElementById('codigo_propiedad').value;
        var propietario = document.getElementById('Propietario').value;
        var arrendatario = document.getElementById('Arrendatario').value;
		var estadoPropiedad = document.getElementById('estadoPropiedad').value;
		var estadoContrato = document.getElementById('estadoContrato').value;
        // Guardar los valores en el almacenamiento local
        localStorage.setItem('estadoArriendo', estadoArriendo);
        localStorage.setItem('codigoPropiedad', codigoPropiedad);
        localStorage.setItem('propietario', propietario);
        localStorage.setItem('arrendatario', arrendatario);
		localStorage.setItem('estadoPropiedad', estadoPropiedad);
        localStorage.setItem('estadoContrato', estadoContrato);
    }

    // Función para cargar los valores del almacenamiento local en el formulario
    function cargarFiltrosGuardados() {
        document.getElementById('EstadoArriendo').value = localStorage.getItem('estadoArriendo') || '';
        document.getElementById('codigo_propiedad').value = localStorage.getItem('codigoPropiedad') || '';
        document.getElementById('Propietario').value = localStorage.getItem('propietario') || '';
        document.getElementById('Arrendatario').value = localStorage.getItem('arrendatario') || '';
		document.getElementById('estadoPropiedad').value = localStorage.getItem('estadoPropiedad') || '';
        document.getElementById('estadoContrato').value = localStorage.getItem('estadoContrato') || '';
		    // Obtener los valores actuales de los campos
		var estadoArriendo = document.getElementById('EstadoArriendo').value;
		var codigoPropiedad = document.getElementById('codigo_propiedad').value;
		var propietario = document.getElementById('Propietario').value;
		var arrendatario = document.getElementById('Arrendatario').value;
		var estadoPropiedad = document.getElementById('estadoPropiedad').value;
		var estadoContrato = document.getElementById('estadoContrato').value;
	
		// Verificar si alguno de los campos tiene un valor
		if (estadoArriendo || codigoPropiedad || propietario || arrendatario || estadoPropiedad || estadoContrato) {
			// Mostrar el botón
			document.getElementByName('limpiarFiltrosboton').style.display = 'inline-block';
		} 
		else {
			// Ocultar  si ninguno de los campos tiene un valor
			document.getElementById('limpiarFiltrosboton').style.display = 'none';
		}
    }
    // Llamar a la función para cargar los filtros guardados cuando se carga la página
    window.onload = cargarFiltrosGuardados;
	
</script>


<div id="header" class="header-page">
    <!-- <h2 class="mb-3">Arriendos</h2> -->
    <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb d-flex align-items-center m-0">
            <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
            <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Arriendos</li>

            <li>

                <div class="" style="margin-left:20px;">
                    <button type="button" class="btn btn-info btn-sm   text-start" onClick="document.location.reload();">
                        <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
                    </button>
                </div>

            </li>



        </ol>
    </div>


</div>
<div class="content" style="min-height: 100vh;padding-top:30px">


    <div class="container" style="display: flex; justify-content: space-between; max-width: 100%;">


        <div class="text-right" style="width: 100%;">
            <div style="width: 250px; margin-left: auto;">
                <div class="card card-button">
                    <div class="card-body">
                        <a href='index.php?component=arriendo&view=arriendo' style="justify-content: center;
                                display: flex;
                                align-items: center;
                                padding: 0;
                                gap: 0.5rem;
                                text-decoration: none;">
                            <i class="fa fa-plus-square fa-2x" aria-hidden="true"></i>
                            <span style="font-size:1rem;"> Agregar un Arriendo</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div style="clear:both; width:100%; height:1px;"></div>
	
	    <div class="row top-100">
        <div class="col p-0">
            <form class="my-3" method="post" onsubmit="guardarFiltros();">
                <fieldset class="form-group border p-3">
                    <legend>
                        <h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Criterios de Búsqueda <small>(Debe ingresar al menos un campo)</small></h5>
                    </legend>
                    <div class="row g-3">
<!-- 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="EstadoArriendo">Estado Arriendo</label>
                                <select id='EstadoArriendo' name='EstadoArriendo' class='form-control' data-select2-id='EstadoArriendo'  required>
								    <option value="" data-select2-id="" selected>Seleccione</option>
                                    <option value="1" data-select2-id="ta1">Activo</option>
                                    <option value="2" data-select2-id="ta2">Inactivo</option>
                                    <option value="3" data-select2-id="ta3">Todos</option>
                                </select>
                            </div>
                        </div>
-->						
						<div class="col-md-4">
                            <div class="form-group">
                                <label> Código de Propiedad</label>
                             <!--   <input type="text" class="form-control" id="filtro_codigo_propiedad" name="filtro_codigo_propiedad" value="" placeholder="Ingrese Código">  -->
								<input type="text" id="codigo_propiedad" value="" name="codigo_propiedad" class="form-control" placeholder="Cód.Propiedad o Dirección"  onblur="ocultarAutocomplete('codigo_propiedad');" autocomplete='off' onkeyup='buscarPropiedadAutocompleteGenerica(this.value,"codigo_propiedad");'><div id='suggestions_codigo_propiedad'></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Propietario</label>
								<input type="text" id="Propietario" value="" name="Propietario" class="form-control" placeholder="Nombre o RUT o correo"  onblur="ocultarAutocomplete('Propietario');" autocomplete='off' onkeyup='buscarPersonaAutocomplete(this.value,"Propietario");'><div id='suggestions_Propietario'></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Arrendatario</label>
								<input type="text" id="Arrendatario" value="" name="Arrendatario" class="form-control" placeholder="Nombre o RUT o correo"  onblur="ocultarAutocomplete('Arrendatario');" autocomplete='off' onkeyup='buscarPersonaAutocomplete(this.value,"Arrendatario");'><div id='suggestions_Arrendatario'></div>
                            </div>
                        </div>
						<div class="col-md-4">
                            <div class="form-group">
                                <label for="estadoPropiedad">Estado Propiedad</label>
								<?php echo $estado_propiedad ?>
                            </div>
                        </div>
						<div class="col-md-4">
                            <div class="form-group">
                                <label for="estadoContrato">Estado Contrato</label>
								<?php echo $estado_contrato ?>
							</div>
						</div>
                    <div class="row g-3">
                        <div class="col">
							<button id="limpiarFiltrosboton" type="button" class="btn btn-info" onclick='limpiarFiltros();' > Limpiar Filtros</button>
							<button type="submit" class="btn btn-primary" >Buscar</button>
                        </div>

                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Activos</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Inactivos</button>
        </li>
    </ul>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="card">
                <div class="card-body ">
				  <?php if (count($dataTableArriendoActivos) > 0) : ?>
                    <div class="table-responsive overflow-auto">
                        <table id="arriendos-activos" class="table table-striped" cellspacing="0" width="100%">

                            <thead>
                                <tr>
								    <th>Ficha Técnica</th>
                                    <th>Propiedad</th>
									<th>Estado Propiedad</th>
									<th>Estado Contrato</th>
									<th style="min-width:220px;" ><i class='fa-solid fa-house-user' style='color:#313131;font-size:12px;' title='Propietario' ></i> Propietario / <i class='fa-solid fa-user-large' style='font-size:10px; color:#707070;' title='Beneficiario'></i> Beneficiario</th>
                                    <th>Arrendatario</th>
                                    <th>Fecha Inicio</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
							
							<?php foreach ($dataTableArriendoActivos as $obj) : ?>
                                <tr>
								    <td>
									    <a href="index.php?component=arriendo&view=arriendo_ficha_tecnica&token=<?php echo $obj['token']; ?>" class="link-info" style="padding: .5rem;" title="Ver Ficha Técnica">
                                            <?php echo $obj['ficha_tecnica']; ?>
                                        </a>
									</td>
                                    <td><?php echo $obj['propiedad']; ?></td>
									<td><?php echo $obj['estado_propiedad']; ?></td>
									<td><?php echo $obj['estado_contrato']; ?></td>
                                    <td><?php echo $obj['Propietario']; ?></td>
                                    <td><?php echo $obj['Arrendatario']; ?></td>
                                    <td><?php echo $obj['Fecha_Inicio']; ?></td>
                                    <td>$<?php echo $obj['Precio']; ?></td>


                                    <td>
                                        <div class="d-flex" style="gap: .5rem;">
                                            <a href="<?php echo redirectToPropiedadUrl($obj['token']) ?>" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
                                                <i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
                                            </a>
											<!--
                                            <button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
                                                <i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
                                            </button>-->
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
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="card">
                <div class="card-body ">
				<?php if (count($dataTableArriendoDesactivados) > 0) : ?>
                    <div class="table-responsive overflow-auto">
                        <table id="arriendos-inactivos" class="table table-striped" cellspacing="0" width="100%">

                            <thead>
                                <tr>
								    <th>Ficha Técnica</th>
                                    <th>Propiedad</th>
									<th>Estado Propiedad</th>
									<th>Estado Contrato</th>
                                    <th>Propietario</th>
                                    <th>Arrendatario</th>
                                    <th>Fecha Inicio</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
							
							<?php foreach ($dataTableArriendoDesactivados as $obj) : ?>
                                <tr>
								    <td>
									    <a href="index.php?component=arriendo&view=arriendo_ficha_tecnica&token=<?php echo $obj['token']; ?>" class="link-info" style="padding: .5rem;" title="Ver Ficha Técnica">
                                            <?php echo $obj['ficha_tecnica']; ?>
                                        </a>
									</td>
                                    <td><?php echo $obj['propiedad']; ?></td>
									<td><?php echo $obj['estado_propiedad']; ?></td>
									<td><?php echo $obj['estado_contrato']; ?></td>
                                    <td><?php echo $obj['Propietario']; ?></td>
                                    <td><?php echo $obj['Arrendatario']; ?></td>
                                    <td><?php echo $obj['Fecha_Inicio']; ?></td>
                                    <td style="text-align: right;">$<?php echo $obj['Precio']; ?></td>


                                    <td>
                                        <div class="d-flex" style="gap: .5rem;">
                                            <a href="<?php echo redirectToPropiedadUrl($obj['token']) ?>" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
                                                <i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
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
    </div>
</div>