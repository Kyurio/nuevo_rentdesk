<script>
    loadContrato('<?php echo @$token_propiedad; ?>', '<?php echo @$pag_origen; ?>');
</script>

<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Contratos</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Contratos</li>
            </ol>
        </div>
    </div>


</div>

<div class="content content-page" >

    <div class="d-flex justify-content-end">
        <div class="card">
            <div class="card-body"> <a href='index.php?component=contrato&view=contrato' style="justify-content: center;
    display: inline-flex;
    align-items: center;
    padding: 0;
    gap: 0.5rem;
    text-decoration: none;"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i>
                    <span style="font-size:1rem;">Agregar un Contrato</span>
                </a></div>
        </div>
    </div>
    <div class="herramientas">
        <button type="button" class="btn btn-info btn-sm" onClick="document.location.reload();">
            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabla" class="display" cellspacing="0" width="100%">

                <thead>
                    <tr>
                        <th>Id. Contrato</th>
                        <th>Num. Doc.</th>
                        <th>Arrendatario</th>
                        <th>Propiedad</th>
                        <th>Estado</th>
                        <th>Ejecutivo</th>
                        <th>Acciones</th>
                    </tr>
                    <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                            <div class="d-flex" style="gap: .5rem;">
                                <a href="index.php?component=contrato&view=contrato" type="button" class="btn btn-primary m-0" style="padding: .5rem;">
                                    <i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
                                </a>
                                <button type="button" class="btn btn-danger m-0" style="padding: .5rem;">
                                    <i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </thead>

            </table>
        </div>
    </div>


    <?php if ($token_propiedad != "") { ?>
        <div class="col-lg-12 text-center">
            <a href="<?php echo $nav; ?>">
                <button type="button" class="btn btn-primary"> &lt;&lt; volver </button></a>
        </div>
    <?php } ?>


</div>