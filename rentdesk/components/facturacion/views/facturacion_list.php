<script>
    loadReajuste();
</script>
<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propiedades</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <!-- <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=arriendo&view=arriendo_list" style="text-decoration: none;color:#66615b">Arriendos</a></li> -->
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Facturas</li>
            </ol>
        </div>
    </div>

</div>

<div class="content content-page" >

    <div class="d-flex justify-content-end">

        <div class="card">
            <div class="card-body"> <a href='index.php?component=facturacion&view=facturacion' style="justify-content: center;
                    display: inline-flex;
                    align-items: center;
                    padding: 0;
                    gap: 0.5rem;
                    text-decoration: none;">
                    <i class="fa fa-plus-square fa-2x" aria-hidden="true"></i>
                    <span style="font-size:1rem;">Agregar Factura</span>
                </a></div>
        </div>
    </div>



    <div class="card">
        <div class="card-body ">
            <div style="flex-flow: wrap;gap: 0.5rem;justify-content:space-between;align-items:center" class="d-flex mb-4">
                <button type="button" class="btn btn-default btn-outline-primary btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Descuento">
                    <span>Descargar Facturas</span>
                </button>
                <button type="button" class="btn btn-info btn-sm ms-auto" onClick="document.location.reload();">
                    <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
                </button>
            </div>
            <div class="table-responsive overflow-auto">
                <table id="reajustes" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th class="col-2">Fecha</th>
                            <th class="col-1">Folio</th>
                            <th class="col-1">Razón Social / Nombre</th>
                            <th class="col-1">Nro. Documento</th>
                            <th class="col-1">Monto</th>
                            <th class="col-1">Glosa</th>
                            <th class="col-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataTableFacturas as $row) : ?>
                            <tr>
                                <?php foreach ($row as $key => $cell) : ?>

                                    <td><?php echo $cell; ?></td>
                                <?php endforeach; ?>

                                <td>
                                    <div class="d-flex" style="gap: .5rem;">
                                        <a href="https://gladius-control-prop.s3.sa-east-1.amazonaws.com/invoice/85553/document/-2024022110281708522116.pdf?X-Amz-Expires=600&X-Amz-Date=20240221T210054Z&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAUDM5QTO3L5F4JLQF%2F20240221%2Fsa-east-1%2Fs3%2Faws4_request&X-Amz-SignedHeaders=host&X-Amz-Signature=02ca077ad1854d02c4b33701df90d88cde3acdbd221418effe0898b498aab4db" target="_blank" type="button" class="btn btn-info m-0" style="padding: .5rem;" title="Ver Factura">
                                            <i class="fa-regular fa-file-pdf" style="font-size: .75rem;"></i>
                                        </a>
                                        <!-- <a href="index.php?component=propiedad&view=propiedad" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
                                            <i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
                                        </a> -->
                                        <button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Nota de Crédito">
                                            Nota de Crédito<!-- <i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i> -->
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>