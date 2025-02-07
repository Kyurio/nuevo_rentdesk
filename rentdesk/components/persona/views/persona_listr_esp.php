<script>
   // loadPropietarios();
   CargarListadoPersonas();
</script>
<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propietarios</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Cliente</li>


                <li>

                    <div class="" style="margin-left:20px;">
                        <button type="button" class="btn btn-info btn-sm   text-start" onClick="CargarListadoPersonas()">
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

        <div class="card card-button">
            <div class="card-body"> <a href='index.php?component=persona&view=persona' style="justify-content: center;
                display: inline-flex;
                align-items: center;
                padding: 0;
                gap: 0.5rem;
                text-decoration: none;"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i>
                    <span style="font-size:1rem;">Agregar un Cliente</span>
                </a></div>
        </div>
    </div>




    <div class="row top-100">
        <div class="col p-0">
           
                <fieldset class="form-group border p-3">
                    <legend>
                        <h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Criterios de Búsqueda <small>(Debe ingresar al menos un campo)</small></h5>
                    </legend>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" value="" 
                                placeholder="Nombre o Rut" 
                                onblur="ocultarAutocomplete('nombre_cliente');" 
                                autocomplete='off' onkeyup='buscarClienteAutocompleteGenerica(this.value,"nombre_cliente");'>
                                <div id='suggestions_nombre_cliente'  class="suggestionsAutoComplete"></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tiposFiltro">Tipo</label>
                                <select class="form-control js-example-responsive" data-select2-id="tiposFiltro" id="tiposFiltro" name="tiposFiltro[]" multiple="multiple">
                                    <option value="Propietario" data-select2-id="ta1">Propietario</option>
                                    <option value="Arrendatario" data-select2-id="ta2">Arrendatario</option>
                                    <option value="Codeudor" data-select2-id="ta3">Codeudor</option>
                                </select>
                            </div>
                        </div>

                        <!--
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" class="form-control" maxlength="250" name="direccion" id="direccion" placeholder="Dirección"  data-validation-required value="<?php echo @$result->direccion; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                    -->
                    </div>
                    <div class="row g-3">
                        <div class="col">
                            <button type="submit" class="btn btn-primary" onclick="cargarListadoPersonasFiltro()">Buscar</button>
                        </div>

                    </div>
                </fieldset>
           
            <div class="row" style="display:none">
                <button class="btn btn-info btn-mas-filtros" style="width:auto; text-align:left;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Filtros <i class="fas fa-chevron-down"></i></button>
                <div class="collapse col-12 col-md-12 col-lg-12 p-0" id="collapseExample">
                    <form>
                        <fieldset class="form-group border p-3">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Código de Propiedad</label>
                                        <input type="text" class="form-control" id="cod_propiedad" name="cod_propiedad" value="" placeholder="Ingrese Código">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tiposFiltro2">Tipo</label>
                                        <select class="form-control js-example-responsive" data-select2-id="tiposFiltro2" name="tiposFiltro2[]" multiple="multiple">
                                            <option value="1" data-select2-id="ta1">Propietario</option>
                                            <option value="2" data-select2-id="ta2">Arrendatario</option>
                                            <option value="2" data-select2-id="ta3">Codeudor</option>
                                        </select>
                                    </div>
                                </div>

                                <!--   
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Dirección</label>
                                        <input type="text" class="form-control" maxlength="250" name="direccion" id="direccion" placeholder="Dirección"  data-validation-required value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                                    </div>
                                </div>  
                                -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="oficina">Oficina</label>
                                        <select name="oficina" id="oficina" class="form-control">
                                            <option value="CENTRO-MONEDA" id="89">CENTRO-MONEDA</option>
                                            <option value="NUEVA LAS CONDES" id="127">NUEVA LAS CONDES</option>
                                            <option value="LA SERENA" id="128">LA SERENA</option>
                                            <option value="PROVIDENCIA" id="129">PROVIDENCIA</option>
                                            <option value="REÑACA" id="130">REÑACA</option>
                                            <option value="SAN MIGUEL" id="131">SAN MIGUEL</option>
                                            <option value="ADMINISTRACIONES" id="132">ADMINISTRACIONES</option>
                                            <option value="MAIPU PLAZA" id="133">MAIPU PLAZA</option>
                                            <option value="SANTIAGO-CENTRO" id="134">SANTIAGO-CENTRO</option>
                                            <option value="OFICINA PLAN B (SEGURO)" id="135">OFICINA PLAN B (SEGURO)</option>
                                            <option value="MAIPU PAJARITOS" id="136">MAIPU PAJARITOS</option>
                                            <option value="TALAGANTE" id="137">TALAGANTE</option>
                                            <option value="PLAZA EGAÑA" id="138">PLAZA EGAÑA</option>
                                            <option value="ROSARIO SUR" id="139">ROSARIO SUR</option>
                                            <option value="LA FLORIDA" id="140">LA FLORIDA</option>
                                            <option value="ISABEL LA CATOLICA" id="141">ISABEL LA CATOLICA</option>
                                            <option value="VITACURA" id="142">VITACURA</option>
                                            <option value="VICUÑA MACKENNA" id="143">VICUÑA MACKENNA</option>
                                            <option value="LA REINA" id="144">LA REINA</option>
                                            <option value="PUENTE ALTO" id="145">PUENTE ALTO</option>
                                            <option value="NULL" id="146">NULL</option>
                                            <option value="BULNES" id="147">BULNES</option>
                                            <option value="ÑUÑOA" id="148">ÑUÑOA</option>
                                            <option value="APOQUINDO" id="149">APOQUINDO</option>
                                            <option value="LOS DOMINICOS" id="150">LOS DOMINICOS</option>
                                            <option value="CONCEPCION" id="151">CONCEPCION</option>
                                            <option value="NUEVA COSTANERA" id="152">NUEVA COSTANERA</option>
                                            <option value="LAS CONDES" id="153">LAS CONDES</option>
                                            <option value="LAS TRANQUERAS" id="154">LAS TRANQUERAS</option>
                                            <option value="ANA MARIA DUQUE" id="155">ANA MARIA DUQUE</option>
                                            <option value="PEÑALOLEN" id="156">PEÑALOLEN</option>
                                            <option value="ESCUELA MILITAR" id="157">ESCUELA MILITAR</option>
                                            <option value="LA DEHESA" id="158">LA DEHESA</option>
                                            <option value="E-MAIL" id="159">E-MAIL</option>
                                            <option value="TABANCURA" id="160">TABANCURA</option>
                                            <option value="LOS MILITARES" id="161">LOS MILITARES</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> RUT</label>
                                        <input type="text" class="form-control" id="rut" name="rut" value="" placeholder="Ingrese Rut">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="" placeholder="Ingrese Nombre">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row g-3">
                                <div class="col">
                                    <button type="button" class="btn btn-primary">Buscar</button>
                                </div>

                            </div> -->
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="herramientas">
        <button type="button" class="btn btn-info btn-sm" onClick="CargarListadoPersonas();">
            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
    </div>
    <!-- <div class="card">
        <div class="card-body">
            <div class="table-responsive overflow-auto">

                <table id="personas" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>RUT</th>
                            <th>Correo Electrónico</th>
                            <th>Teléfono Celular</th>
                            <th>Ficha Técnica</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataTablePersonas as $row) : ?>
                            <tr>
                                <?php foreach ($row as $key => $cell) : ?>


                                    <?php if (in_array($key, [5]) && (isset($cell) && $cell !== "-")) : ?>
                                        <td>
                                            <span class="pe-2"><?php echo $cell; ?></span>
                                            <a href="index.php?component=persona&view=persona_ficha_tecnica" type="button" class="btn btn-info m-0" style="padding: .5rem;" title="Ver Ficha Técnica">
                                                <i class="fa-solid fa-magnifying-glass" style="font-size: .75rem;"></i>
                                            </a>
                                        </td>
                                    <?php else : ?>
                                        <td><?php echo $cell; ?></td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            
                                <td>
                                    <div class="d-flex" style="gap: .5rem;">
                                        <a href="index.php?component=persona&view=persona" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
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
                <div class="table-responsive overflow-auto">

                </div>
            </div>
        </div>
    </div> -->

    <div class="card">
        <div class="card-body">
            <?php //if (count($dataTablePersonas) > 0) : ?>

                <div class="table-responsive overflow-auto">
                    <table id="clientes" class="table table-striped" cellspacing="0" width="100%">

                        <thead>
                            <tr>
                                <!-- <th>Tipo</th> -->
                                <th>Nombre</th>
                                <th>Nro. Documento</th>
                                <th>Correo Electrónico</th>
                                <th>Tipo Personalidad</th>
                                <th>Dirección</th>
                                <th>Ficha Técnica</th>
                                <th>Roles</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php //foreach ($dataTablePersonas as $obj) : ?>
                               <!----
                                <tr>
                                    <td><?php //echo $obj['nombre'] ?></td>
                                    <td><?php //echo $obj['dni']; ?></td>
                                    <td><?php //echo $obj['correo_electronico']; ?></td>
                                    <td><?php //echo $obj['tipo_personalidad']; ?></td>
                                    <td><?php //echo $obj['direccion']; ?></td>
                                    <td><?php //echo $obj['ficha_tecnica']; ?></td>
                                    <td>
                                        <div class="d-flex" style="gap: .5rem;">
                                            <a href="<?php //echo redirectToPersonaUrl($obj['token']) ?>" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
                                                <i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
                                                <i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                 ->
                            <?php //endforeach; ?>


                            <?php /*foreach ($dataTablePersonas as $row) : ?>
                            <tr>
                                <?php foreach ($row as $key => $cell) : ?>
                                    <?php if (in_array($key, [1]) && (isset($cell) && $cell !== "-")) : ?>
                                        <td>
                                            <div>Nombre Ejecutivo</div>
                                            <div>Sucursal X</div>
                                            <strong><?php echo $cell; ?></strong>
                                        </td>
                                    <?php elseif (in_array($key, [3]) && (isset($cell) && $cell !== "-")) : ?>
                                        <td><a href="index.php?component=propietario&view=propietario_ficha_tecnica" class="link-info"><?php echo $cell; ?></a></td>
                                    <?php elseif (in_array($key, [13]) && (isset($cell) && $cell !== "-")) : ?>
                                        <td>
                                            <span class="pe-2"><?php echo $cell; ?></span>
                                            <a href="index.php?component=propiedad&view=propiedad_ficha_tecnica" type="button" class="btn btn-info m-0" style="padding: .5rem;" title="Ver Ficha Técnica">
                                                <i class="fa-solid fa-magnifying-glass" style="font-size: .75rem;"></i>
                                            </a>
                                        </td>
                                    <?php else : ?>
                                        <td><?php echo $cell; ?></td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                         
                                <td>
                                    <div class="d-flex" style="gap: .5rem;">
                                        <a href="index.php?component=propiedad&view=propiedad" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
                                            <i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
                                            <i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; */ ?>


                        </tbody>

                    </table>
                </div>
            <?php// else : ?>
                <div class="text-center m-3">
                    <p>No hay registros por el momento</p>
                </div>
            <?php// endif; ?>
        </div>
    </div>