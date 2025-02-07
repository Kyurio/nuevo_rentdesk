<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="js/validate-password/css/jquery.passwordRequirements.css" />
<script>
    $(document).ready(function() {
	cargarSucursalList();
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
            <h3>Sucursales</h3>
            <div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
                <button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#modalMantenedorIngresoSucursal">
                    <span>Ingresar</span>
                </button>
            </div>
            <div class="table-responsive overflow-auto">
                <table id="mant-sucursal-table" class="table" cellspacing="0" width="100%">

                    <thead>
                        <tr>
							<th>Id</th>
                            <th>Nombre</th>
							<th>Casa matriz</th>
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
<div class="modal fade" id="modalMantenedorIngresoSucursal" tabindex="-1" aria-labelledby="modalMantenedorIngresoSucursalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMantenedorIngresoSucursalLabel">Ingreso Sucursal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetFormById('ingreso_sucursal')"></button>
            </div>
            <div class="modal-body">
                <form id="ingreso_sucursal" action="" name="ingreso_sucursal" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Nombre sucursal</label>
                                <input required type="text" class="form-control" maxlength="60" name="sucursalNombre" id="sucursalNombre"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-2">
                            <div class="form-group d-flex flex-column">
                                <label for="sucursalMatriz" class="me-2 mb-0">Casa matriz</label>
                                <label class="switch">
                                    <input type="checkbox" id="sucursalMatriz" name="sucursalMatriz" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
						<div class="col-lg-2">
                            <div class="form-group d-flex flex-column">
                                <label for="sucursalActivo" class="me-2 mb-0">Activo</label>
                                <label class="switch">
                                    <input type="checkbox" id="sucursalActivo" name="sucursalActivo" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal" onclick="resetFormById('ingreso_sucursal')">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="insertSucursal()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Usuario - Edición Usuario-->
<div class="modal fade" id="modalMantenedorEditarSucursalEditar" tabindex="-1" aria-labelledby="modalMantenedorEditarSucursalEditarLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMantenedorEditarSucursalEditar">Edición Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetModalUsuario('sucursal_formulario_editar')" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sucursal_formulario_editar" name="sucursal_formulario_editar" action="javascript: editarSucursal();" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span>Nombre sucursal</label>
                                <input required type="text" class="form-control" maxlength="60" name="sucursalNombreEditar" id="sucursalNombreEditar"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-2">
                            <div class="form-group d-flex flex-column">
                                <label for="sucursalMatrizEditar" class="me-2 mb-0">Casa matriz</label>
                                <label class="switch">
                                    <input type="checkbox" id="sucursalMatrizEditar" name="sucursalMatrizEditar" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
						<div class="col-lg-2">
                            <div class="form-group d-flex flex-column">
                                <label for="sucursalActivoEditar" class="me-2 mb-0">Activo</label>
                                <label class="switch">
                                    <input type="checkbox" id="sucursalActivoEditar" name="sucursalActivoEditar" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" class="form-control" min="0" name="RolTokenEditar" id="RolTokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                    <input type="hidden" name="ID_sucursal_Editar" id="ID_sucursal_Editar">


                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-info" data-bs-dismiss="modal" onclick="resetModalSucursal()" >Cerrar</button>
                        <button type="submit" class="btn btn-danger">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
