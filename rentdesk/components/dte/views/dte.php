<main style="min-height: 600px;">
    <br><br><br><br>

    <ul class="nav  nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="genera-dte-tab" data-bs-toggle="tab"
                data-bs-target="#genera-dte-tab-pane" type="button" role="tab" aria-controls="genera-dte-tab-pane" aria-selected="true">Generacion Masiva DTE</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="historial-dte-tab" data-bs-toggle="tab" data-bs-target="#historial-dte-tab-pane"
                type="button" role="tab" aria-controls="historial-dte-tab-pane" aria-selected="false" onclick="HistorialLiquidaciones()">DTE Generados</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="genera-dte-tab-pane" role="tabpanel" aria-labelledby="genera-dte-tab" tabindex="0">

            <div class="card mt-5">
                
                <div class="card-header">
                    <!-- Botones de selección y generación de DTE -->
                    <button id="select-all" class="btn btn-info mb-3">Seleccionar Todos</button>
                    <button id="generarDTE" class="btn btn-secondary mb-3" onclick="GenerarDocumento()" disabled>Generar DTE</button>
                </div>


                <div class="card-body">

                    <!-- Tabla de generación masiva de DTE -->
                    <table id="liq-generacion-masiva-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Seleccionar</th>
                                <th>ID de Liquidación</th>
                                <th>Comisión de Corretaje</th>
                                <th>Comisión de Administración</th>
                                <th>Dirección</th>
                                <th>Fecha de Liquidación</th>
                            </tr>
                        </thead>
                        <tbody id="cierre-liquidaciones-tab-pane">
                            <!-- Los datos se cargarán aquí con JavaScript -->
                        </tbody>
                    </table>
                    
                </div>
            </div>


        </div>
        <div class="tab-pane fade" id="historial-dte-tab-pane" role="tabpanel" aria-labelledby="historial-dte-tab" tabindex="0">

            <div class="card mt-5">
                <div class="card-header">

                    <h3>DTE Generados</h3>

                </div>
                <div class="card-body">

                    <table id="tablaHistorial" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <td>Fecha</td>
                                <th>Arriendo</th>
                                <th>Propiedad</th>
                                <th>Dirección</th>
                                <th>Liquidacion</th>
                                <th>Tipo</th>
                                <th>Folio</th>
                                <th>Tipo Documento</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="liq-historial">
                            <!-- Filas generadas dinámicamente -->
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

</main>