<div class="tab-pane" id="cuentasContablesP" role="tabpanel" aria-labelledby="cuentas-contables-tab" tabindex="0">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h3>Cuentas Contables</h3>
                <h6 class="text-muted my-4 display-6">Cuentas Contables</h6>

                <div class="table-responsive overflow-auto">
                    <table id="cuentasContables" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Cta Contable</th>
                                <th>Nombre</th>
                                <th>Cargo</th>
                                <th>Abono</th>
                                <th>Saldo</th>
                                <th>Encargado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Este tbody se llenará dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar detalles de la cuenta contable -->
<div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detallesModalLabel">Detalles de Cuenta Contable</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="detallesTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
              <tr>
                <th>Cta Contable</th>
                <th>Nombre</th>
                <th>Razón</th>
                <th>Cargo</th>
                <th>Abono</th>
                <th>Saldo</th>
              </tr>
            </thead>
            <tbody>
              <!-- Este tbody se llenará dinámicamente -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
