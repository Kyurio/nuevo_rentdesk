<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>

<div class="content content-page">
    <table class="table table-striped" id="archivos">
        <thead>
            <tr>
                <th scope="col">ID cierre</th>
                <th scope="col">Fecha</th>
                <th scope="col">Cantidad de liquidaciones</th>
                <th scope="col">Genera Office Banking</th>
                <th scope="col">Detalles</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <!-- Modal detalle liquidaciones -->
    <div class="modal fade" id="modalDetalleLiquidaciones" tabindex="-1" aria-labelledby="modalDetalleLiquidaciones" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalDetalleLiquidaciones">Detalle</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="text" id="token">

                    <table class="table" id="detalleLiquidaciones">
                        <thead>
                            <tr>
                                <th scope="col">Fecha Movimiento</th>
                                <th scope="col">Monto</th>
                                <th scope="col">Razon</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal cierre -->
    <div class="modal fade" id="ModalListadoPropiedeades" tabindex="-1" aria-labelledby="ModalListadoPropiedeades" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Listado Propiedades</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <button id="habilitarTodos" class="btn btn-primary">Habilitar Todos</button>
                    <button id="deshabilitarTodos" class="btn btn-secondary">Deshabilitar Todos</button>

                    <table class="table table-striped" id="listadoPropiedades">
                        <thead>
                            <tr>
                                <th scope="col">Propiedad</th>
                                <th scope="col">Propietario</th>
                                <th scope="col">Monto a Liquidar</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal  -->
    <div class="modal fade" id="DetalleLiquidaciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cierre Liquidación</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                    <table class="table" id="detalleLiquidaciones">
                        <thead>
                            <tr>
                                <th scope="col">Liquidación</th>
                                <th scope="col">Ficha Propiedad</th>
                                <th scope="col">Dirección</th>
                                <th scope="col">Propietario</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Ficha Arriendo</th>
                                <th scope="col">Cierre</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>