<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="js/validate-password/css/jquery.passwordRequirements.css" />
<script>
    $(document).ready(function() {
cargarSbsList();
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
            <h3>Subsidiaria</h3>
            <div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
                <button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#modalMantenedorIngresoSbs">
                    <span>Ingresar</span>
                </button>
            </div>
            <div class="table-responsive overflow-auto">
                <table id="mant-sbs-table" class="table" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
							<th>Rut</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>


</div>

<!-- Modal Usuario - Ingresar Usuario-->
<div class="modal fade" id="modalMantenedorIngresoSbs" tabindex="-1" aria-labelledby="modalMantenedorIngresoSbsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMantenedorIngresoSbsLabel">Ingreso Subsidiaria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetFormById('ingreso_sbs')"></button>
            </div>
            <div class="modal-body">
                <form id="ingreso_sbs" action="" name="ingreso_sbs" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Nombre</label>
                                <input required type="text" class="form-control" maxlength="60" name="sbsNombre" id="sbsNombre"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Rut</label>
                                <input required type="text" class="form-control" oninput="checkRut(this);" maxlength="60" name="sbsRut" id="sbsRut"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-2">
                            <div class="form-group d-flex flex-column">
                                <label for="sbsActivo" class="me-2 mb-0">Activo</label>
                                <label class="switch">
                                    <input type="checkbox" id="sbsActivo" name="sbsActivo" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal" onclick="resetFormById('ingreso_sbs')">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="insertSbs()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Usuario - Edición Usuario-->
<div class="modal fade" id="modalMantenedorEditarSbs" tabindex="-1" aria-labelledby="modalMantenedorEditarSbsLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMantenedorEditarSbsLabel">Edición Subsidiaria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetModalUsuario('sbs_formulario_editar')" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sbs_formulario_editar" name="sbs_formulario_editar" action="javascript: editarSbs();" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Nombre</label>
                                <input required type="text" class="form-control" maxlength="60" name="sbsNombreEditar" id="sbsNombreEditar"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Rut</label>
                                <input required type="text" oninput="checkRut(this);" class="form-control" maxlength="60" name="sbsRutEditar" id="sbsRutEditar"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-2">
                            <div class="form-group d-flex flex-column">
                                <label for="sbsActivoEditar" class="me-2 mb-0">Activo</label>
                                <label class="switch">
                                    <input type="checkbox" id="sbsActivoEditar" name="sbsActivoEditar" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" class="form-control" min="0" name="RolTokenEditar" id="RolTokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                    <input type="hidden" name="ID_sbs_Editar" id="ID_sbs_Editar">


                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-info" data-bs-dismiss="modal" onclick="resetModalSbs()" >Cerrar</button>
                        <button type="submit" class="btn btn-danger">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>