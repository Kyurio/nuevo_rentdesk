<div class="tab-pane" id="contribucionesP" role="tabpanel" aria-labelledby="propiedad-ft-contribucionesP-tab" tabindex="0">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h3>Contribuciones</h3>
                <h6 class="text-muted my-4 display-6">Contribuciones</h6>

                <div class="d-flex justify-content-start align-items-center mb-3">
    <button type="button" class="btn btn-info m-0 me-2" title="Ingresar Excel" data-bs-toggle="modal" data-bs-target="#modalExcelUpload"><span>Cargar Contribuciones</span></button>

    <button class="btn btn-warning m-0 me-2" onclick="generarRetencion(this)">Generar Retención</button>

    <button type="button" class="btn btn-primary m-0 me-2" id="subirConvertirBtn" onclick="convertExcelToJson()" disabled title="Esta función se activará únicamente cuando se suba un archivo Excel de contribuciones.">Actualizar Contribución</button>

</div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="table-responsive overflow-auto">
                            <table id="contribuciones" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                <tr>
                                <th>Rol</th> <!-- Código de la propiedad -->
                                <!-- <th>Cuota</th> Número de cuota -->
                                <th>Fecha</th> <!-- Fecha de registro -->
                                <th>Estado</th> <!-- Estado de la contribución -->
                                <th>Tipo Rol</th> <!-- Tipo de rol (PRINCIPAL o SECUNDARIO) -->
                                <th>Dirección</th> <!-- Dirección de la propiedad -->
                                <th>Fecha Pago</th> <!-- Fecha de pago (puede estar vacía) -->
                                <th>Año Contribución</th> <!-- Año de la contribución -->
                                <th>Descripción</th> <!-- Descripción de la propiedad -->
                                <th>ID Propiedad</th> <!-- ID de la propiedad -->
                                <th>Mes Contribución</th> <!-- Mes de la contribución -->
                                <th>Valor Cuota</th> <!-- Valor de la cuota -->
                                <th>Monto Pagado</th> <!-- Monto pagado -->
                                <th>Acciones</th> <!-- Botones para editar -->
                            </tr>

                                </thead>
                                <tbody>
                                    <!-- El tbody lo llenará DataTables dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalEditarContribucion" tabindex="-1" aria-labelledby="modalEditarContribucionLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarContribucionLabel">Editar Contribución</h5>
                <button type="button" class="close" onclick="$('#modalEditarContribucion').modal('hide');" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarContribucion">
                    <input type="hidden" id="id_propiedad" name="id_propiedad">
                    <input type="hidden" id="idvaloresroles" name="idvaloresroles">
                    <div class="form-group">
                        <label for="rolContribucion">Rol</label>
                        <input type="text" class="form-control" id="rolContribucion" name="rol" readonly>
                    </div>
                    <div class="form-group">
                        <label for="mes">Mes</label>
                        <select class="form-control" name="mes_contrib" id="mesContribucion">
                            <option selected>Selecciona una cuota</option>
                            <option value="1">Abril</option>
                            <option value="2">Junio</option>
                            <option value="3">Septiembre</option>
                            <option value="4">Noviembre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="anoContribucion">Año Contribución</label>
                        <input type="text" class="form-control" id="anoContribucion" name="ano_contrib">
                    </div>
                    <div class="form-group">
                        <label for="valorCuotaContribucion">Valor Cuota</label>
                        <input type="text" class="form-control" id="valorCuotaContribucion" name="valor_cuota">
                    </div>
                    <div class="form-group">
                        <label for="montoPagadoContribucion">Monto Pagado</label>
                        <input type="text" class="form-control" id="montoPagadoContribucion" name="monto_pagado">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#modalEditarContribucion').modal('hide');">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarContribucion()">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para cargar Excel -->
<div class="modal fade" id="modalExcelUpload" tabindex="-1" aria-labelledby="modalExcelUploadLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExcelUploadLabel">Cargar Excel de contribuciones pagadas</h5>
                <button type="button" class="close" onclick="$('#modalExcelUpload').modal('hide');" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formExcelUpload" enctype="multipart/form-data">
                    <div class="form-group text-center">
                        <label for="excelFile" class="form-label">Seleccione el archivo Excel</label>
                        <div class="custom-file-container">
                            <input type="file" class="form-control" id="excelFile" accept=".xlsx, .xls" required onchange="updateFileName()">
                            <small id="fileName" class="form-text text-muted">Ningún archivo seleccionado</small>
                        </div>
                    </div>
                    <!-- Warning Message -->
                    <div class="alert alert-warning mt-3" role="alert">
                    <strong>Advertencia:</strong> Al subir el archivo Excel, los datos anteriores serán eliminados y reemplazados por la nueva información.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#modalExcelUpload').modal('hide');">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
