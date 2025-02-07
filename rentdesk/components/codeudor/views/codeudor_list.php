<script>
    //loadPropietarios();
    //cargarListadoCodeudor();
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        loadCodeudor_List();
    });
</script>

<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propietarios</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Codeudores</li>


                <li>

                    <div class="" style="margin-left:20px;">
                        <button type="button" class="btn btn-info btn-sm   text-start" onClick="cargarListadoCodeudor() ">
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
            <div class="card-body card-button"> <a href='index.php?component=codeudor&view=codeudor&nav=<?php echo $pag_origen; ?>' style="justify-content: center;
                display: inline-flex;
                align-items: center;
                padding: 0;
                gap: 0.5rem;
                text-decoration: none;"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i>
                    <span style="font-size:1rem;">Agregar un Codeudor</span>
                </a></div>
        </div>
    </div>



    <!-- <div class="card">
        <div class="card-body">
            <div class="table-responsive overflow-auto">

                <table id="propietarios" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Propiedades</th>
                            <th>Arrendatarios</th>
                            <th>Propietarios</th>
                            <th>Correo Electrónico</th>
                            <th>Teléfono Celular</th>
                            <th>Ficha Técnica</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                  
                    </tbody>

                </table>
                <div class="table-responsive overflow-auto">

                </div>
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
                            <input type="text" class="form-control" id="nombre_codeudor" name="nombre_codeudor" value=""
                                placeholder="Nombre o Rut "
                                onblur="ocultarAutocomplete('nombre_codeudor');"
                                autocomplete='off' onkeyup='buscarCodeudorAutocompleteGenerica(this.value,"nombre_codeudor");'>
                            <div id='suggestions_nombre_codeudor' class="suggestionsAutoComplete"></div>
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
                    </div>     -->
                    <div class="row g-3">
                        <div class="col">
                            <button type="submit" class="btn btn-primary" onclick="loadCodeudor_List()">Buscar</button>
                        </div>

                    </div>

            </fieldset>


        </div>
    </div>



    <div class="card">
        <div class="card-body">

            <div class="table-responsive overflow-auto">
                <div class="col">
                    <button id="descargarExcelCodeudor" type="button" class="btn btn-outline-primary mb-4">Descargar Excel Completo</button>
                </div>
                <table id="codeudor" class="table table-striped" cellspacing="0" width="100%">

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