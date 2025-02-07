<script>
    //loadArrendatarios();
    //CargarListadoArrendatarios();
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        loadArrendatario_List();
    });
</script>

<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Arrendatarios</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Arrendatarios</li>
                <li>

                    <div class="" style="margin-left:20px;">
                        <button type="button" class="btn btn-info btn-sm   text-start" onClick="CargarListadoArrendatarios() ">
                            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
                        </button>
                    </div>

                </li>
            </ol>
        </div>
    </div>


</div>

<div class="content content-page">

    <div class="d-flex justify-content-end">
        <div class="card">
            <div class="card-body card-button"> <a href='index.php?component=arrendatario&view=arrendatario' style="justify-content: center;
                display: inline-flex;
                align-items: center;
                padding: 0;
                gap: 0.5rem;
                text-decoration: none;"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i>
                    <span style="font-size:1rem;">Agregar un Arrendatario</span>
                </a></div>
        </div>
    </div>
    <!--
    <div class="herramientas">
        <button type="button" class="btn btn-info btn-sm" onClick="document.location.reload();">
            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
    </div>
    -->
    <!-- <div class="card">
        <div class="card-body">
            <div class="table-responsive overflow-auto">

                <table id="arrendatarios" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>RUT</th>
                            <th>Propiedades</th>
                            <th>Propietarios</th>
                            <th>Correo Electrónico</th>
                            <th>Teléfono Celular</th>
                            <th>Ficha Técnica</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataTableArriendos as $row) : ?>
                            <tr>
                                <?php foreach ($row as $key => $cell) : ?>

                                    <?php if (in_array($key, [2]) && (isset($cell) && $cell !== "-")) : ?>
                                        <td><a href="index.php?component=propiedad&view=propiedad_ficha_tecnica" class="link-info"><?php echo $cell; ?></a></td>
                                    <?php elseif (in_array($key, [3]) && (isset($cell) && $cell !== "-")) : ?>
                                        <td><a href="index.php?component=propietario&view=propietario_ficha_tecnica" class="link-info"><?php echo $cell; ?></a></td>
                                    <?php elseif (in_array($key, [6]) && (isset($cell) && $cell !== "-")) : ?>
                                        <td>
                                            <span class="pe-2"><?php echo $cell; ?></span>
                                            <a href="index.php?component=arrendatario&view=arrendatario_ficha_tecnica" type="button" class="btn btn-info m-0" style="padding: .5rem;" title="Ver Ficha Técnica">
                                                <i class="fa-solid fa-magnifying-glass" style="font-size: .75rem;"></i>
                                            </a>
                                        </td>
                                    <?php else : ?>
                                        <td><?php echo $cell; ?></td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                       
                                <td>
                                    <div class="d-flex" style="gap: .5rem;">
                                        <a href="index.php?component=arrendatario&view=arrendatario" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
                                            <i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
                                            <i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div> -->
    <div class="row top-100">
        <div class="col p-0">
            <fieldset class="form-group border p-3">
                <legend>
                    <h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Criterios de Búsqueda</h5>
                </legend>
                <div class="row g-3">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" id="nombre_arrendatario" name="nombre_arrendatario" value=""
                                placeholder="Nombre o Rut "
                                onblur="ocultarAutocomplete('nombre_arrendatario'); "
                                autocomplete='off' onkeyup='buscarArrendatarioAutocompleteGenerica(this.value,"nombre_arrendatario");'>
                            <div id='suggestions_nombre_arrendatario' class="suggestionsAutoComplete"></div>
                        </div>
                    </div>



                    <!---                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> RUT/DNI</label>
                                <input type="text" class="form-control" id="filtro_dni" name="filtro_dni" value="" placeholder="Ingrese RUT/DNI">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Correo Electrónico</label>
                                <input type="text" class="form-control" id="filtro_correo" name="filtro_correo" value="" placeholder="Ingrese Correo Electrónico">
                            </div>
                        </div>
                        --->
                </div>
                <div class="row g-3">
                    <div class="col">
                        <button type="button" class="btn btn-primary" onclick="loadArrendatario_List()">Buscar</button>
                    </div>

                </div>
            </fieldset>

        </div>
    </div>


    <div class="card">
        <div class="card-body">





            <div class="table-responsive overflow-auto">
                <div class="col">
                    <button id="descargarExcelArrendatario" type="button" class="btn btn-outline-primary">Descargar Excel Completo</button>
                </div>
                <table id="arrendatario" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>Ficha Técnica</th>
                            <th>Nombre</th>
                            <th>Nro. Documento</th>
                            <th>Tipo Persona</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>




        </div>
    </div>
</div>