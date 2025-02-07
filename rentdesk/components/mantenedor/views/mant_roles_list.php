<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {

    });
</script>



<div id="header" class="header-page">
    <!-- <h2 class="mb-3">Arriendos</h2> -->
    <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb d-flex align-items-center m-0">
            <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
            <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Mantenedor</li>

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

    <div class="card">
        <div class="card-body ">
            <h3>Roles</h3>
            <div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
                <button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#modalMantenedorIngresoRol">
                    <span>Ingresar</span>
                </button>
            </div>
            <div class="table-responsive overflow-auto">
                <table id="mant-rol-table" class="table" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
							<th>Acceso</th>
                            <th>Habilitado</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>


</div>

<!-- Modal Rol - Ingresar Rol-->
<div class="modal fade" id="modalMantenedorIngresoRol" aria-labelledby="modalMantenedorIngresoRolLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMantenedorIngresoRolLabel">Ingreso Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetFormById('ingreso_rol')"></button>
            </div>
            <div class="modal-body">
                <form id="ingreso_rol" action="" name="ingreso_rol" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Nombre</label>
                                <span id="rolNombre" class="conteo-input">0/60</span>
                                <input required type="text" class="form-control" maxlength="60" name="nombreRol" id="nombreRol" oninput="conteoInput('nombreRol','rolNombre');" placeholder="Nombre" required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="descripcionRol">Descripción</label>
                                <span id="rolDescripcion" class="conteo-input">0/30</span>
                                <input required type="text" class="form-control" maxlength="30" name="descripcionRol" id="descripcionRol" oninput="conteoInput('descripcionRol','rolDescripcion');" placeholder="Descripción" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group d-flex flex-column">
                                <label for="rolActivo" class="me-2 mb-0">Activo</label>
                                <label class="switch">
                                    <input type="checkbox" id="rolActivo" name="rolActivo" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="administracionRol">Modulo Administracion</label>
								<?php echo @$rol_administracion; ?>
								</div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="propiedadesRol">Modulo propiedades</label>
								<?php echo @$rol_propiedad; ?>
								</div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="clienteRol">Modulo cliente</label>
								<?php echo @$rol_cliente; ?>
								</div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="arriendoRol">Modulo arriendos</label>
								<?php echo @$rol_arriendo; ?>
								</div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="accionesRol">Modulo acciones</label>
								<?php echo @$rol_acciones; ?>
								</div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="facturacionRol">Modulo facturación</label>
								<?php echo @$rol_facturacion; ?>
								</div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="reporteRol">Modulo reportes</label>
								<?php echo @$rol_reporte; ?>
								</div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="reporteRol">Modulo Archivos</label>
								<?php echo @$rol_archivo; ?>
								</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal" onclick="resetFormById('ingreso_rol')">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarRol()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Rol - Edición Rol-->
<div class="modal fade" id="modalMantenedorEditarRol" tabindex="-1" aria-labelledby="modalMantenedorEditarRolLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMantenedorEditarRolLabel">Edición Rol</h5>
                <button type="button" class="btn-close" onclick="resetModal();" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rol_formulario_editar" name="rol_formulario_editar" action="javascript: editarRol();" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Nombre</label>
                                <span id="rolEditarNombre" class="conteo-input">0/60</span>
                                <input required type="text" class="form-control" maxlength="60" name="nombreRolEditar" id="nombreRolEditar" oninput="conteoInput('nombreRolEditar','rolEditarNombre');" placeholder="Nombre" required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="descripcionRolEditar">Descripción</label>
                                <span id="rolEditarDescripcion" class="conteo-input">0/30</span>
                                <input required type="text" class="form-control" maxlength="30" name="descripcionRolEditar" id="descripcionRolEditar" oninput="conteoInput('descripcionRolEditar','rolEditarDescripcion');" placeholder="Descripción" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group d-flex flex-column">
                                <label for="rolActivoEditar" class="me-2 mb-0">Activo</label>
                                <label class="switch">
                                    <input type="checkbox" id="rolActivoEditar" name="rolActivoEditar">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="administracionRolEditar">Modulo Administracion</label>
								<select class='form-control js-example-responsive' name='administracionRolEditar[]' id='administracionRolEditar' multiple='multiple'>
								</select>
								</div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="propiedadesRolEditar">Modulo propiedades</label>
								<select class='form-control js-example-responsive' name='propiedadRolEditar[]' id='propiedadRolEditar' multiple='multiple'>
								</select>
								</div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="clienteRolEditar">Modulo cliente</label>
								<select class='form-control js-example-responsive' name='clienteRolEditar[]' id='clienteRolEditar' multiple='multiple'>
								</select>
								</div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="arriendoRolEditar">Modulo arriendos</label>
								<select class='form-control js-example-responsive' name='arriendoRolEditar[]' id='arriendoRolEditar' multiple='multiple'>
								</select>
								</div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="accionesRolEditar">Modulo acciones</label>
								<select class='form-control js-example-responsive' name='accionesRolEditar[]' id='accionesRolEditar' multiple='multiple'>
								</select>
								</div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="facturacionRolEditar">Modulo facturación</label>
								<select class='form-control js-example-responsive' name='facturacionRolEditar[]' id='facturacionRolEditar' multiple='multiple'>
								</select>
								</div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="reporteRolEditar">Modulo reportes</label>
								<select class='form-control js-example-responsive' name='reporteRolEditar[]' id='reporteRolEditar' multiple='multiple'>
								</select>
								</div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="reporteRolEditar">Modulo Archivos</label>
								<select class='form-control js-example-responsive' name='archivoRolEditar[]' id='archivoRolEditar' multiple='multiple'>
								</select>
								</div>
                        </div>

                    </div>
                    <input type="hidden" class="form-control" min="0" name="RolTokenEditar" id="RolTokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                    <input type="hidden" name="ID_Rol_Editar" id="ID_Rol_Editar">

                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-info" data-bs-dismiss="modal" onclick="resetModal();">Cerrar</button>
                        <button type="submit" class="btn btn-danger">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>