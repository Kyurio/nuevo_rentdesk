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
            <h3>Cuentas Contables</h3>


            <div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
                <button type="button" class="btn btn-info m-0" style="padding: .5rem;white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#modalMantenedorIngresoCuentaContable">
                    <span>Ingresar</span>
                </button>
            </div>
            <div class="table-responsive overflow-auto">
                <table id="mant-cta-contable-table" class="table" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Número Cuenta</th>
                            <th>Habilitado</th>
                            <th>Tipo Movimiento</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>


</div>

<!-- Modal Cuenta Contable - Ingresar Cuenta Contable-->
<div class="modal fade" id="modalMantenedorIngresoCuentaContable" tabindex="-1" aria-labelledby="modalMantenedorIngresoCuentaContableLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMantenedorIngresoCuentaContableLabel">Ingreso Cuenta Contable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetFormById('ingreso_cta_contable')"></button>
            </div>
            <div class="modal-body">
                <form id="ingreso_cta_contable" action="" name="ingreso_cta_contable" method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Nombre</label>
                                <span id="ctaContableNombre" class="conteo-input">0/60</span>
                                <input required type="text" class="form-control" maxlength="60" name="nombreCtaContable" id="nombreCtaContable" oninput="conteoInput('nombreCtaContable','ctaContableNombre');" placeholder="Nombre" required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="ctaContableTipo">tipo movimiento</label>
                                <span id="ctaContableTipo" class="conteo-input">0/30</span>
                                <input required type="text" class="form-control" maxlength="30" name="tipoCtaContable" id="tipoCtaContable" oninput="conteoInput('tipoCtaContable','´¡hu');" placeholder="Tipo de Movimiento" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="ctaContableNroCuenta">Número de Cuenta</label>
                                <span id="ctaContableNro" class="conteo-input">0/30</span>
                                <input required type="text" class="form-control" maxlength="30" name="ctaContableNroCuenta" id="ctaContableNroCuenta" oninput="conteoInput('ctaContableNroCuenta','ctaContableNro');validarNumero(this);" placeholder="Número de Cuenta" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group d-flex flex-column">
                                <label for="ctaContableActivo" class="me-2 mb-0">Activo</label>
                                <label class="switch">
                                    <input type="checkbox" id="ctaContableActivo" name="ctaContableActivo" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal" onclick="resetFormById('ingreso_cta_contable')">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarCtaContable()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Cuenta Contable - Edición Cuenta Contable-->
<div class="modal fade" id="modalMantenedorEditarCuentaContable" tabindex="-1" aria-labelledby="modalMantenedorEditarCuentaContableLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMantenedorEditarCuentaContableLabel">Edición Cuenta Contable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cta_contable_formulario_editar" name="cta_contable_formulario_editar" action="javascript: editarCtaContable();" method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Nombre</label>
                                <span id="ctaContableEditarNombre" class="conteo-input">0/60</span>
                                <input required type="text" class="form-control" maxlength="60" name="nombreCtaContableEditar" id="nombreCtaContableEditar" oninput="conteoInput('nombreCtaContableEditar','ctaContableEditarNombre');" placeholder="Nombre" required data-validation-required onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="ctaContableNroCuentaEditar">Número de Cuenta</label>
                                <span id="ctaContableEditarNro" class="conteo-input">0/30</span>
                                <input required type="text" class="form-control" maxlength="30" name="ctaContableNroCuentaEditar" id="ctaContableNroCuentaEditar" oninput="conteoInput('ctaContableNroCuentaEditar','ctaContableEditarNro');validarNumero(this);" placeholder="Número de Cuenta" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="ctaContableTipo">tipo movimiento</label>
                                <span id="ctaContableTipoEditar" class="conteo-input">0/30</span>
                                <input required type="text" class="form-control" maxlength="30" name="ctaContableTipoMovimientoEditar" id="ctaContableTipoMovimientoEditar" oninput="conteoInput('ctaContableTipoMovimientoEditar','ctaContableTipoEditar');" placeholder="Tipo de Movimiento" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group d-flex flex-column">
                                <label for="ctaContableActivoEditar" class="me-2 mb-0">Activo</label>
                                <label class="switch">
                                    <input type="checkbox" id="ctaContableActivoEditar" name="ctaContableActivoEditar">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" class="form-control" min="0" name="CtaContableTokenEditar" id="CtaContableTokenEditar" placeholder="" value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                    <input type="hidden" name="ID_Cta_Contable_Editar" id="ID_Cta_Contable_Editar">

                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-info" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>