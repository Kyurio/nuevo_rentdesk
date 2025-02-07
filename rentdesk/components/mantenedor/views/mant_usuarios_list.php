<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="js/validate-password/css/jquery.passwordRequirements.css" />
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
            <h3>Usuarios</h3>
            <div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
                <button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#modalMantenedorIngresoUsuario">
                    <span>Ingresar</span>
                </button>
            </div>
            <div class="table-responsive overflow-auto">
                <table id="mant-usuario-table" class="table" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
							<th>Rut</th>
                            <th>Correo</th>
							<th>Sucursal</th>
							<th style="min-width:220px;">Rol</th>
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
<div class="modal fade" id="modalMantenedorIngresoUsuario" tabindex="-1" aria-labelledby="modalMantenedorIngresoUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMantenedorIngresoUsuarioLabel">Ingreso Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetFormById('ingreso_usuario')"></button>
            </div>
            <div class="modal-body">
                <form id="ingreso_usuario" action="" name="ingreso_usuario" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Nombre</label>
                                <input required type="text" class="form-control" maxlength="60" name="UsuarioNombre" id="UsuarioNombre"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Apellido Paterno</label>
                                <input required type="text" class="form-control" maxlength="60" name="usuarioApellidoPat" id="usuarioApellidoPat"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Apellido Materno</label>
                                <input required type="text" class="form-control" maxlength="60" name="UsuarioApellidoMat" id="UsuarioApellidoMat"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Correo</label>
                                <input required type="email" class="form-control" maxlength="60" name="usuarioCorreo" id="usuarioCorreo"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Contraseña</label>
                                <input required type="text" class="form-control" maxlength="60" name="usuarioContraseña" id="usuarioContraseña"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="usuarioRut"><span class="obligatorio">*</span>Rut</label>
                                <input required type="text" oninput="checkRut(this);" class="form-control" maxlength="30" name="usuarioRut" id="usuarioRut"  onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="usuarioRol"><span class="obligatorio">*</span>Rol</label>
								<?php echo $tipo_rol;?>
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="sucursalRol"><span class="obligatorio">*</span>Sucursal</label>
								<?php echo $sucursal;?>
                            </div>
                        </div>
						<div class="col-lg-2">
                            <div class="form-group d-flex flex-column">
                                <label for="UsuarioActivo" class="me-2 mb-0">Activo</label>
                                <label class="switch">
                                    <input type="checkbox" id="UsuarioActivo" name="UsuarioActivo" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal" onclick="resetFormById('ingreso_usuario')">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="insertUsuario()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Usuario - Edición Usuario-->
<div class="modal fade" id="modalMantenedorEditarUsuario" tabindex="-1" aria-labelledby="modalMantenedorEditarUsuarioLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMantenedorEditarUsuarioLabel">Edición Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetModalUsuario('usuario_formulario_editar')" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="usuario_formulario_editar" name="usuario_formulario_editar" action="javascript: editarUsuario();" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Nombre</label>
                                <input required type="text" class="form-control" maxlength="60" name="usuarioNombreEditar" id="usuarioNombreEditar"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Apellido Paterno</label>
                                <input required type="text" class="form-control" maxlength="60" name="usuarioApellidoPatEditar" id="usuarioApellidoPatEditar"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Apellido Materno</label>
                                <input required type="text" class="form-control" maxlength="60" name="usuarioApellidoMatEditar" id="usuarioApellidoMatEditar"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Correo</label>
                                <input required type="email" class="form-control" maxlength="60" name="usuarioCorreoEditar" id="usuarioCorreoEditar"   required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="usuarioRutEditar"><span class="obligatorio">*</span>Rut</label>
                                <input required type="text" oninput="checkRut(this);" class="form-control" maxlength="30" name="usuarioRutEditar" id="usuarioRutEditar"  onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="usuarioRolEditar"><span class="obligatorio">*</span>Rol</label>
								<select  id='usuarioRolEditar' name='usuarioRolEditar' class='form-control' >
                                </select>
                            </div>
                        </div>
						<div class="col-lg-4">
                            <div class="form-group">
                                <label for="usuarioEditar">Sucursal</label>
								<select class='form-control js-example-responsive' name='usuarioEditar[]' id='usuarioEditar' multiple='multiple'>
								</select>
								</div>
                        </div>						
						<div class="col-lg-2">
                            <div class="form-group d-flex flex-column">
                                <label for="UsuarioActivoEditar" class="me-2 mb-0">Activo</label>
                                <label class="switch">
                                    <input type="checkbox" id="UsuarioActivoEditar" name="UsuarioActivoEditar" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" class="form-control" min="0" name="RolTokenEditar" id="RolTokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                    <input type="hidden" name="ID_usuario_Editar" id="ID_usuario_Editar">
					<input type="hidden" name="usuarioCorreoEditarActual" id="usuarioCorreoEditarActual">


                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-info" data-bs-dismiss="modal" onclick="resetModalUsuario()" >Cerrar</button>
                        <button type="submit" class="btn btn-danger">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Usuario - Edición Usuario solo contraseña-->
<div class="modal fade" id="modalMantenedorEditarPass"  aria-labelledby="modalMantenedorEditarPassLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMantenedorEditarPassLabel">Edición Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetModalUsuario('usuario_formulario_editar_pass')" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="usuario_formulario_editar_pass" name="usuario_formulario_editar_pass" action="javascript: editarUsuarioPass();" method="post" enctype="multipart/form-data">
                    <div class="row">
						<div class="col-lg-6">
                            <div class="form-group">
                                <label>Contraseña (Solo ingresar para cambio de contraseña)</label>
								<div class="password-input-container">
                                <input type="password"  class="form-control pr-password" oninput="conteoInput('usuarioContraseñaEditar','cuentaCorreo');"  maxlength="60" name="usuarioContraseñaEditar" id="usuarioContraseñaEditar" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								<span class="toggle-password fa fa-eye-slash" onclick="togglePasswordVisibility('usuarioContraseñaEditar','.toggle-password')"></span>
								</div>
							</div>
                        </div>
												<div class="col-lg-6">
                            <div class="form-group">
                                <label>Ingrese nuevamente contraseña</label>
								<div class="password-input-container">
                                <input type="password" class="form-control" oninput="conteoInput('usuarioContraseñaEditarRepetida','cuentaCorreo');"  maxlength="60" name="usuarioContraseñaEditarRepetida" id="usuarioContraseñaEditarRepetida" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
								<span class="toggle-password-validacion fa fa-eye-slash" onclick="togglePasswordVisibility('usuarioContraseñaEditarRepetida','.toggle-password-validacion')"></span>
								</div>
                            </div>
                        </div>
						<div id="alertAviso" class="alert alert-danger" role="alert" style="display: none;">
							Las contraseñas no son iguales, por favor ingrese nuevamente.
						</div>

                    </div>
                    <input type="hidden" class="form-control" min="0" name="RolTokenEditarPass" id="RolTokenEditarPass" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                    <input type="hidden" name="ID_usuario_Editar_Pass" id="ID_usuario_Editar_Pass">


                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-info" data-bs-dismiss="modal" onclick="resetModalUsuario('usuario_formulario_editar_pass')" >Cerrar</button>
                        <button type="submit" class="btn btn-danger">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="js/validate-password/js/jquery.passwordRequirements.js"></script>
<script>
$(function(){
        $(".pr-password").passwordRequirements({
                  numCharacters: 8,
                  useLowercase: true,
                  useUppercase: true,
                  useNumbers: true,
                  useSpecial: false
                });
		    $(".pr-password").css("z-index", "9999999999");
    });
</script>