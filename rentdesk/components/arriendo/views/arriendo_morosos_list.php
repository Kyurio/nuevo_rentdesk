<script>
    loadPropiedad();
</script>
<div id="header" class="header-page">
    <!-- <h2 class="mb-3">Arriendos</h2> -->
    <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb d-flex align-items-center m-0">
            <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
            <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=arriendo&view=arriendo_list" style="text-decoration: none;color:#66615b">Arriendos</a></li>
            <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Arriendos Morosos</li>
            <li>

                <div class="" style="margin-left:20px;">
                    <button type="button" class="btn btn-info btn-sm   text-start" onClick="document.location.reload();">
                        <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
                    </button>
                </div>

            </li>



        </ol>
    </div>


</div>
<div class="content" style="min-height: 100vh;padding-top:30px">

    <h3>Arriendos Morosos</h3>

    <div class="card">
        <div class="card-body ">
            <div class="table-responsive overflow-auto">

                <table id="Listado_Arriendos_morosos" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Propiedad</th>
                            <th>Deuda</th>
                            <th>Ficha Técnica</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>


            </div>
        </div>
    </div>


</div>