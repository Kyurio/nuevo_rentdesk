<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
    cargarLiquidacionesGenMasivaList();
</script>
<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propiedades</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propiedad&view=propiedad_list" style="text-decoration: none;color:#66615b">Propiedades</a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Liquidaciones - Generación Masiva</li>
            </ol>
        </div>
    </div>

</div>

<div class="content content-page">

    <div class="card">
        <div class="card-body ">

            <div class="d-flex mb-4">


                <button type="button" class="btn btn-info me-2 btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;"
                    title="Seleccionar Todo" onclick="habilitarTodos()">
                    <span>Incluir Todo</span>
                </button>

                <button type="button" class="btn btn-danger me-2 btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;"
                    title="Deseleccionar Todo" onclick="deshabilitarTodos()">
                    <span>No Incluir Todo</span>
                </button>

                <button type="button" class="btn btn-success me-2 btn-sm pull-right m-0"
                    style="padding: .5rem; white-space: nowrap;"
                    id="ObtenerIDS"
                    onclick="GenerarLiquidaciones()">
                    <span>Generar Liquidaciones</span>
                </button>


            </div>
            <div class="table-responsive overflow-auto">
                <table id="liq-generacion-masiva-table" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th scope="col">Dirección</th>
                            <th scope="col">Propiedad</th>
                            <th scope="col">Arriendo</th>
                            <th scope="col">Monto</th>
                            <th scope="col">Cierre Conciliación</th>
                            <th scope="col">Medio de pago</th>
                            <th></th>
                        </tr>

                    </thead>
                    <tbody>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>