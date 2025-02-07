<script src="js/region_ciudad_comuna.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
    loadPropiedad_List();
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

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
            <form id="filtros-busqueda" class="my-3" action="">

                <fieldset class="form-group border p-3">
                    <legend>
                        <h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Criterios de Búsqueda <small>(Debe ingresar al menos un campo)</small></h5>
                    </legend>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Código de Propiedad</label>
                                <!--   <input type="text" class="form-control" id="filtro_codigo_propiedad" name="filtro_codigo_propiedad" value="" placeholder="Ingrese Código">  -->
                                <input type="text" id="codigo_propiedad" value="" name="codigo_propiedad" class="form-control" placeholder="Cód.Propiedad o Dirección" onblur="ocultarAutocomplete('codigo_propiedad');" autocomplete='off' onkeyup='buscarPropiedadAutocompleteB(this.value,"codigo_propiedad");'>
                                <div id='suggestions_codigo_propiedad'></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Propietario</label>
                                <input type="text" id="propietario" name="propietario" class="form-control" placeholder="Nombre o rut"
                                    onblur="ocultarAutocomplete('propietario');" autocomplete="off" oninput="buscarClienteAutocompleteGenerica(this.value, 'propietario');">
                                <div id="suggestions_propietario" class="suggestions"></div>
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
                                                <input type="text" id="ejecutivo" value="" name="ejecutivo" class="form-control" placeholder="Nombre Ejecutivo" onblur="ocultarAutocomplete('codigo_propiedad');" autocomplete='off' onkeyup='buscarPropiedadAutocomplete(this.value,"codigo_propiedad");'>
                                                <div id='suggestions_codigo_propiedad'></div>
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
                            <button class="btn btn-info btn-mas-filtros" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiltros" aria-expanded="false" aria-controls="collapseFiltros" onClick="masMenosFiltros();" id="btnMasFiltros"> Más Filtros <i class='fas fa-chevron-down'></i></button>

                            <!-- <button type="submit"   class="btn btn-primary">Buscar</button>-->
                            <button type="button" class="btn btn-primary" onclick="loadPropiedad_List()">Buscar</button>

                        </div>

                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <div class="row">
        <div id="resultado" name="resultado" style="width:100%;"></div>
    </div>


    <!-- <div class="herramientas">
        <button type="button" class="btn btn-info btn-sm" onClick="document.location.href='index.php?component=propiedad&view=propiedad_list';">
            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
        <button type="button" class="btn btn-primary btn-sm" onclick="generarExcel('<?php echo $config->urlbase; ?>')">Generar excel</button>
    </div> -->

    <div class="card">
        <div class="card-body">

            <div class="table-responsive overflow-auto">

                <div class="col">
                    <button id="descargarExcelPropiedad" type="button" class="btn btn-outline-primary mb-4">Descargar Excel Completo</button>
                </div>

                <table id="propiedades" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>Ficha Técnica</th>
                            <th>Oficina captadora</th>
                            <th>Ejecutivo</th>
                            <th style="min-width:220px;"><i class='fa-solid fa-house-user' style='color:#313131;font-size:12px;' title='Propietario'></i> Propietario / <i class='fa-solid fa-user-large' style='font-size:10px; color:#707070;' title='Beneficiario'></i> Beneficiario</th>
                            <th>Tipo Propiedad</th>
                            <th style="min-width:220px;">Dirección</th>
                            <th>Comuna</th>
                            <th>Región</th>
                            <th>Estado</th>
                            <th>¿Asegurado?</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        <?php echo @$loadPaisComunaRegion; ?>
    });
</script>