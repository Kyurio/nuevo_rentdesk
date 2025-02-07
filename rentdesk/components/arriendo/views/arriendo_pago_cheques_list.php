<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
    cargarPagoChequesList();
</script>
<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propiedades</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=arriendo&view=arriendo_list" style="text-decoration: none;color:#66615b">Arriendos</a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Pago Arriendo - Cheques</li>
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


    <div class="card">
        <div class="card-body ">
            <div style="flex-flow: wrap;gap: 0.5rem;justify-content:space-between;align-items:center" class="d-flex mb-4">
                <!-- <button type="button" class="btn btn-default btn-outline-primary btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Descuento">
                    <span>Descargar Movimientos</span>
                </button> -->
                <button type="button" class="btn btn-info btn-sm ms-auto" onClick="document.location.reload();">
                    <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
                </button>
            </div>
            <div class="table-responsive overflow-auto">
                <table id="pago-cheques-table" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>Arriendo</th>
                            <th>Fecha Cobro</th>
                            <th>Razón</th>
                            <th>Monto</th>
                            <th>Banco</th>
                            <th>Girador</th>
                            <th>Nro. Documento</th>
                            <th>Cantidad</th>
                            <th>Banco</th>

                            <!-- <th>Arriendo</th>
                            <th>Propiedad</th> -->

                            <!-- <th>Depósito</th> -->
                            <!-- <th >Acciones</th> -->

                        </tr>
                        <!-- <tr>
                            <th>Arriendo</th>
                            <th>Propiedad</th>
                            <th>Monto</th>
                            <th>Nro. Documento</th>
                            <th>Fecha Cobro</th>
                            <th>Banco</th>
                            <th>Girador</th>
                            <th>Depósito</th>

                        </tr> -->
                    </thead>
                    <tbody>


                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>