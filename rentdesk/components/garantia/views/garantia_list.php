<script>
    loadReajuste();
</script>
<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propiedades</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=arriendo&view=arriendo_list" style="text-decoration: none;color:#66615b">Arriendos</a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Garantías</li>
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
        <button type="button" class="btn btn-info btn-sm recargar" onClick="document.location.reload();">
            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
    </div>


    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Garantías Abiertas</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Garantías Cerradas</button>
        </li>
    </ul>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive overflow-auto">
                        <table id="garantias-abiertas" class="table table-striped" cellspacing="0" width="100%">

                            <thead>
                                <tr>
                                    <th class="col-2">Arriendo</th>
                                    <th class="col-1">Monto a Devolver</th>
                                    <th class="col-1">Fecha de Término Real</th>
                                    <th class="col-1">Fecha Desde Término</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataTableGarantiasAbiertas as $row) : ?>
                                    <tr>
                                        <?php foreach ($row as $key => $cell) : ?>
                                            <?php if ($key === 0) : ?>
                                                <td><a href="#" class="link-info"><?php echo $cell; ?></a></td>
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
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive overflow-auto">
                        <table id="garantias-cerradas" class="table table-striped" cellspacing="0" width="100%">

                            <thead>
                                <tr>
                                    <th class="col-2">Arriendo</th>
                                    <th class="col-1">Monto a Devolver</th>
                                    <th class="col-1">Fecha de Término Real</th>
                                    <th class="col-1">Fecha Desde Término</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataTableGarantiasCerradas as $row) : ?>
                                    <tr>
                                        <?php foreach ($row as $key => $cell) : ?>
                                            <?php if ($key === 0) : ?>
                                                <td><a href="#" class="link-info"><?php echo $cell; ?></a></td>
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
    </div>


</div>