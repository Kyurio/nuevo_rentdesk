<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
    cargarArriendoEliminarMorasList();
</script>

<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propiedades</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propiedad&view=propiedad_list" style="text-decoration: none;color:#66615b">Propiedades</a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Pago Arriendo - Eliminar Moras</li>
            </ol>
        </div>
    </div>
</div>

<div class="content content-page">


    <div class="card">
        <div class="card-body ">
            <div style="flex-flow: wrap;gap: 0.5rem;justify-content:space-between;align-items:center" class="d-flex mb-4">
                <button type="button" class="btn btn-info btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Seleccionar Todo" onclick="selectAllCheckboxes()">
                    <span>Seleccionar Todo</span>
                </button>
                <button type="button" class="btn btn-info btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Deseleccionar Todo" onclick="deselectAllCheckboxes()">
                    <span>Deseleccionar Todo</span>
                </button>
                <button ondblclick="" id="eliminar-moras" type="button" class="btn btn-danger btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Eliminar Moras" onclick="removerCheckboxes()">
                    <span>Eliminar Moras</span>
                </button>

                <button type="button" class="btn btn-info btn-sm ms-auto" onClick="document.location.reload();">
                    <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
                </button>
            </div>
            <div class="table-responsive overflow-auto">
                <table id="prop-pago-arriendo-eliminar-moras-table" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <td>ID</td>
                            <th>Arriendo</th>
                            <th>Arrendatario</th>
                            <th><span style=" color: #e62238;" >Monto a Saldar</span></th>
             
                            <th><span style="display: flex; justify-content: flex-end;">Seleccionar</span></th>

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>
