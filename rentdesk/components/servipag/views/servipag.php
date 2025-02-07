<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <form id="uploadForm" onsubmit="event.preventDefault(); CargarServipag();">

                <div class="d-flex">
                    <div class="p-2 w-100">
                        <label for="formFile" class="form-label">Subir archivo Servipag</label>
                        <input class="form-control" type="file" id="formFile" name="file" required>
                    </div>
                    <div class="p-2 flex-shrink-1">
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Subir</button>
                        </div>
                    </div>
                </div>

            </form>

            <button class="btn btn-dark" onclick="ProcesarListado()">Procesar Pagos</button>

        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <table id="servipagTable" class="table caption-top">
                <caption>Servipag</caption>
                <thead>
                    <tr>
                        <td>Nro</td>
                        <td>Rut</td>
                        <td>ID propiedad</td>
                        <td>Direccion</td>
                        <td>Contrato</td>
                        <td>fecha pago</td>
                        <td>Arriendo</td>
                        <td>Monto Pagado</td>
                        <td>Diferencia</td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>