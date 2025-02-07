<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
    cargarLiquidacionesPagoPropietariosList();
</script>
<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propiedades</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propiedad&view=propiedad_list" style="text-decoration: none;color:#66615b">Propiedades</a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Liquidaciones - Pago a Propietarios</li>
            </ol>
        </div>
    </div>

</div>

<div class="content content-page">

    <!-- <div class="d-flex justify-content-end">

        <div class="card">
            <div class="card-body"> <a href='index.php?component=propiedad&view=propiedad' style="justify-content: center;
                    display: inline-flex;
                    align-items: center;
                    padding: 0;
                    gap: 0.5rem;
                    text-decoration: none;">
                    <i class="fa fa-plus-square fa-2x" aria-hidden="true"></i>
                    <span style="font-size:1rem;">Agregar un Reajuste</span>
                </a></div>
        </div>
    </div> -->
    <!-- <div class="herramientas">
        <button type="button" class="btn btn-default btn-sm recargar" onClick="document.location.reload();">
            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
    </div>
 -->

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Realizar Pago</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Pagos Realizados</button>
        </li>
    </ul>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="card">
                <div class="card-body ">
                    <div style="flex-flow: wrap;gap: 0.5rem;justify-content:space-between;align-items:center" class="d-flex mb-4">
                        <button type="button" class="btn btn-info btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Descuento">
                            <span>Seleccionar Todo</span>
                        </button> <button type="button" class="btn btn-info btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Descuento">
                            <span>Deeleccionar Todo</span>
                        </button>
                        <button type="button" class="btn btn-default btn-outline-primary btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Descuento">
                            <span>Descargar</span>
                        </button>
                        <button type="button" class="btn btn-info btn-sm ms-auto" onClick="document.location.reload();">
                            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
                        </button>
                    </div>
                    <div class="table-responsive overflow-auto">
                        <table id="prop-liq-pago-propietarios-table" class="table table-striped" cellspacing="0" width="100%">

                            <thead>
                                <tr>
                                    <th class="col-2">Propietario</th>
                                    <th class="col-1">Propiedad</th>
                                    <th class="col-1">Monto</th>
                                    <th class="col-1">Sin Descuentos/Abonos Extra</th>
                                    <th class="col-1">Nro. Transferencias</th>
                                    <th class="col-1">Seleccionar</th>

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="card">
                <div class="card-body ">
                    <!-- <div style="flex-flow: wrap;gap: 0.5rem;justify-content:space-between;align-items:center" class="d-flex mb-4">
                        <button type="button" class="btn btn-info btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Descuento">
                            <span>Seleccionar Todo</span>
                        </button> <button type="button" class="btn btn-info btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Descuento">
                            <span>Deeleccionar Todo</span>
                        </button>
                        <button type="button" class="btn btn-default btn-outline-primary btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Descuento">
                            <span>Descargar</span>
                        </button>
                        <button type="button" class="btn btn-default btn-sm recargar ms-auto" onClick="document.location.reload();">
                            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
                        </button>
                    </div> -->
                    <div class="table-responsive overflow-auto">
                        <table id="reajustes" class="table table-striped" cellspacing="0" width="100%">

                            <thead>
                                <tr>
                                    <th class="col-2">Fecha</th>
                                    <th class="col-1">Archivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataTablePropiedadLiqPagoPropietariosPagosRealizados as $row) : ?>
                                    <tr>
                                        <?php foreach ($row as $key => $cell) : ?>
                                            <?php if ($key === 1) : ?>
                                                <td><a href="" class="link-info"><?php echo $cell; ?></a></td>
                                            <?php else : ?>
                                                <td><?php echo $cell; ?></td>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




</div>