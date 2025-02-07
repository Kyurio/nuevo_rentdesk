<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
    cargarLiquidacionesHistorico();
</script>
<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propiedades</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propiedad&view=propiedad_list" style="text-decoration: none;color:#66615b">Propiedades</a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Liquidaciones Historico</li>
            </ol>
        </div>
    </div>

</div>

<div class="content content-page">
    <form method="POST" action="">
        <div class="row g-3">
					<div class="col-md-3">
                        <input name="fechaInicio" id="fechaInicio" class="form-control" type="date" value="<?php if(isset($fecha_inicio)){echo $fecha_inicio;} ?>" onchange="actualizaFechaInicio()" required>
                    </div>
                    <div class="col-md-3">
                        <input name="fechaTermino" id="fechaTermino" class="form-control" type="date" value="<?php if(isset($fecha_termino)){echo $fecha_termino;} ?>" onchange="actualizaFechaTermino()" required>

                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-danger" style="margin-top: 0;"> Filtrar </button>
                    </div>
                </div>
        </form>
        <div class="card">
        <div class="card-body ">
            
            <div class="table-responsive overflow-auto">
                <table id="liq-histo" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th class="col-2">Folio Liquidacion</th>
                            <th class="col-2">Codigo Propiedad</th>
                            <th class="col-1">Codigo Arriendo</th>
                            <th class="col-1">Direccion</th>
                            <th class="col-1">Propietario</th>
                            <th class="col-1">Fecha Liquidacion</th>
                            <th class="col-1">Documento</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                </table>
            </div>
        </div>
        </div>
</div>