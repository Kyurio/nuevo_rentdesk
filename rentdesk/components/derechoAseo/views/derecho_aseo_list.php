<script>
    loadReajuste();
</script>
<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propiedades</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propiedad&view=propiedad_list" style="text-decoration: none;color:#66615b">Propiedades</a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Derechos de aseo</li>
            </ol>
        </div>
    </div>

</div>

<div class="content content-page" >

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
    <div class="herramientas">
        <button type="button" class="btn btn-default btn-sm recargar" onClick="document.location.reload();">
            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
    </div>

    <div class="card">
        <div class="card-body ">
            <div class="table-responsive overflow-auto">
                <table id="responsable" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th class="col-2">Propiedad</th>
                            <th class="col-1">Comuna</th>
                            <th class="col-1">Roles</th>
                            <th class="col-1">Estado</th>
                            <th class="col-1">Fecha Revisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataTableDerechoAseo as $row) : ?>
                            <tr>
                                <?php foreach ($row as $key => $cell) : ?>
                                    <?php if ($key === 0) : ?>
                                        <td><a href="index.php?component=propiedad&view=propiedad_ficha_tecnica" class="link-danger"><?php echo $cell; ?></a></td>
                                    <?php else : ?>
                                        <td><?php echo $cell; ?></td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>


</div>