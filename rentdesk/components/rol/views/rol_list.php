<div id="header" class="header-page">
    <div>
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propiedad&view=propiedad_list" style="text-decoration: none;color:#66615b">Propiedades</a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Roles</li>
            </ol>
        </div>
    </div>

</div>

<!-- cabecera    -->
<div class="content content-page">

    <!-- contenido de la pagina  -->
    <div class="tab-content" id="nav-tabContent">

        <!-- tabla -->
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive overflow-auto">


                        <!-- Tabla para mostrar los datos -->
                        <div class="table-responsive overflow-auto">
                            <table id="tablaValoresRol" class="table table-striped">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <!-- <th>Descripcìon o Razòn</th> -->
                                        <th>Nùmero</th>
                                        <!-- <th>Valor Cuota</th> -->
                                        <th>¿Principal?</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se llenarán dinámicamente las filas -->
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Modal agregar valor  -->
<div class="modal modal-lg fade" id="ModalAgregarValor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Valor Rol</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="ingreso_valor_rol" action="" name="ingreso_valor_rol" method="post" enctype="multipart/form-data">
                    <div class="row g-3">

                        <input type="hidden" id="id_propiedad" name="id_propiedad">

                        <div class="col-lg-5">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Año</label>
                                <input type="text" name="valorRolAño" id="valorRolAño" o class="form-control" />
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="valorRolvalor">Valor</label>
                                <span id="valorRolvalor" class="conteo-input">0/30</span>
                                <input required type="number" class="form-control" maxlength="30" name="ValorRol" id="ValorRol" oninput="conteoInput('ValorRol','valorRolvalor');" placeholder="Valor" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="form-group">
                                <select class="form-select" aria-label="cuota" name="mes" id="mes">
                                    <option selected>Selecciona una cuota</option>
                                    <option value="1">Abril</option>
                                    <option value="2">Junio</option>
                                    <option value="3">Septiembre</option>
                                    <option value="4">Noviembre</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGrabar">Grabar</button>
            </div>

        </div>
    </div>
</div>

<!-- Modal editar valor -->
<div class="modal modal-lg fade" id="ModalEditarValor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1050;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Valor Rol</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar_valor_rol" action="" name="editar_valor_rol" method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label><span class="obligatorio">*</span> Año</label>
                                <span id="rolValorAñoEdit" class="conteo-input">0/4</span>
                                <input required type="number" class="form-control" maxlength="4" name="valorRolAñoEdit" id="valorRolAñoEdit" oninput="conteoInput('valorRolAñoEdit','rolValorAñoEdit');" placeholder="Año" data-validation-required="" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="valorRolvalorEdit">Valor</label>
                                <span id="valorRolvalorEdit" class="conteo-input">0/30</span>
                                <input required type="number" class="form-control" maxlength="30" name="ValorRolEdit" id="ValorRolEdit" oninput="conteoInput('ValorRolEdit','valorRolvalorEdit');" placeholder="Valor" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <select class="form-select" aria-label="cuota" name="mesEdit" id="mesEdit">
                                    <option selected>Selecciona una cuota</option>
                                    <option value="1">Abril</option>
                                    <option value="2">Junio</option>
                                    <option value="3">Septiembre</option>
                                    <option value="4">Noviembre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="idEdit" name="idEdit">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarCambios">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal detalle -->
<div class="modal modal-xl fade" id="ModalDetalle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Valores Rol</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="d-flex justify-content">
                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ModalAgregarValor">
                        Agregar Valor
                    </button>
                </div>

                <!-- contenido de la pagina  -->
                <div class="tab-content" id="nav-tabDetalleRolValo">

                    <!-- tabla -->
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="card">
                            <div class="card-body ">
                                <div class="table-responsive overflow-auto">


                                    <!-- Tabla para mostrar los datos -->
                                    <div class="table-responsive overflow-auto">
                                        <table id="tablaValoresRolDetalle" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <!-- <th>ID</th> -->
                                                    <!-- <th>Año</th> -->
                                                    <th>Valor</th>
                                                    <th>Cuota</th>
                                                    <th>¿Cobrado?</th>
                                                    <th>¿Pagado?</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Aquí se llenarán dinámicamente las filas -->
                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // picker year
        $("#valorRolAño").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        });

        // extrae el detalle de la funcion por id 
        function extraerdetalleid(id) {

            $.ajax({
                url: 'components/rol/models/lerr_detalle_valores_rol.php',
                type: 'GET',
                data: {
                    id: id
                }, // Enviar el ID como parte de los datos de la solicitud
                dataType: 'json',
                success: function(data) {
                    // Limpiar la tabla antes de llenarla
                    $('#tablaValoresRolDetalle tbody').empty();

                    // Iterar sobre los datos recibidos y construir las filas de la tabla
                    $.each(data, function(index, item) {
                        var row = `
                            <tr>
                                <td>${item.valor}</td>
                                <td>${item.mes}</td>
                                <td>
                                    <div class="d-flex">
                                        <label class="switch"> 
                                              <input value="1" type="checkbox" id="rolActivoCobrado_${item.id}" name="cobrado_${item.id}" ${item.cobrado ? 'checked' : ''} onclick="confirmarCambioEstado(${item.id}, 'cobrado',this.checked)">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <label class="switch">
                                            <input value="1"  type="checkbox" id="rolActivoPagado_${item.id}" name="pagado_${item.id}" ${item.pagado ? 'checked' : ''} onclick="confirmarCambioEstado(${item.id}, 'pagado', this.checked)">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                <button class="btn btn-info editar-btn me-2" data-bs-toggle="modal" data-bs-target="#ModalEditarValor" data-id="${item.id}" data-año="${item.año}" data-valor="${item.valor}" data-cuota="${item.cuota}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="btn btn-danger eliminar-btn me-2" data-id="${item.id}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                </td>
                            </tr>`;
                        $('#tablaValoresRolDetalle tbody').append(row);
                    });

                    // Asignar eventos a los checkboxes
                    $('input[type="checkbox"]').on('click', function() {

                        if (this.id.startsWith('rolActivoCobrado_')) {
                            var id = this.id.split('_')[1];
                            confirmarCambioEstado(id, 'cobrado', this.checked);

                        } else if (this.id.startsWith('rolActivoPagado_')) {
                            var id = this.id.split('_')[1];
                            confirmarCambioEstado(id, 'pagado', this.checked);
                        }
                    });

                    // Asignar eventos a los botones de eliminar
                    $('.eliminar-btn').click(function() {
                        var idEliminar = $(this).data('id');
                        confirmarEliminacion(idEliminar);
                    });

                    // Asignar eventos a los botones de editar
                    $('.editar-btn').click(function() {
                        var idEditar = $(this).data('id');
                        var añoEditar = $(this).data('año');
                        var valorEditar = $(this).data('valor');
                        var cuotaEditar = $(this).data('cuota');

                        // Llenar el formulario del modal con los datos
                        $('#idEdit').val(idEditar);
                        $('#valorRolAñoEdit').val(añoEditar);
                        $('#ValorRolEdit').val(valorEditar);
                        $('#mesEdit').val(cuotaEditar);
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error al cargar datos:', textStatus);
                }
            });

        }

        //muestra los datos
        function cargarDatos() {
            $.ajax({
                url: 'components/rol/models/leer_valores_rol.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Limpiar la tabla antes de llenarla
                    $('#tablaValoresRol').DataTable().clear().destroy();

                    // Iterar sobre los datos recibidos y construir las filas de la tabla
                    $.each(data, function(index, item) {
                        var row = `
                        <tr>
                            <td>${item.numero}</td>
            
                            <td>${item.principal}</td>
                            <td>
                                <button class="btn btn-success pasar-id-btn me-2" data-id="${item.id_propiedad}" data-bs-toggle="modal" data-bs-target="#ModalDetalle">
                                   <i class="fa-regular fa-eye"></i>
                                </button>

                                                      <button class="btn btn-dark pasar-id-btn me-2" data-bs-toggle="modal" 
                        data-bs-target="#ModalDetalle" data-id="${item.id_propiedad}" data-principal="${item.principal}" data-numero="${item.numero}">
                           <i class="fa-regular fa-eye"></i>
                        </button>
                            </td>
                        </tr>`;
                        $('#tablaValoresRol tbody').append(row);
                    });

                    // Inicializar DataTables
                    $('#tablaValoresRol').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "language": {
                            "lengthMenu": "Mostrar _MENU_ registros por página",
                            "zeroRecords": "No se encontraron registros",
                            "info": "Mostrando página _PAGE_ de _PAGES_",
                            "infoEmpty": "No hay registros disponibles",
                            "infoFiltered": "(filtrado de _MAX_ registros totales)",
                            "search": "Buscar:",
                            "paginate": {
                                "first": "Primero",
                                "last": "Último",
                                "next": "Siguiente",
                                "previous": "Anterior"
                            }
                        }
                    });

                    // Asignar eventos a los botones de eliminar
                    $('.pasar-id-btn').click(function() {
                        var id = $(this).data('id');
                        pasarID(id)
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error al cargar datos:', textStatus);
                }
            });
        }

        //actualizar registros
        function EditarRolSeleccionado(id, principal, numero) {

            alert(id);
            alert(principal);
            alert(numero);

        }

            // Asignar eventos a los botones de eliminar usando delegación
    $(document).on('click', '.pasar-id-btn', function() {
        var id = $(this).data('id');
        var principal = $(this).data('principal');
        var numero = $(this).data('numero');
        if (principal !== undefined && numero !== undefined) {
            EditarRolSeleccionado(id, principal, numero);
        } else {
            pasarID(id);
        }
    });

        // Función para eliminar el registro mediante AJAX
        function eliminarRegistro(id) {

            $.ajax({
                url: '/rentdesk_final/components/rol/models/eliminar_valores_rol.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response) {
                    Swal.fire('¡Eliminado!', 'El registro ha sido eliminado.', 'success');
                    cargarDatos(); // Volver a cargar los datos después de eliminar
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire('Error!', 'Hubo un problema al eliminar el registro.', 'error');
                }
            });
        }

        // Función para confirmar la eliminación usando 
        function confirmarEliminacion(id) {


            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    eliminarRegistro(id);
                }
            });
        }

        // funcion para confirar el cambio de estado
        function confirmarCambioEstado(id, tipo, isChecked) {

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres cambiar el estado?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiarlo',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (tipo === 'cobrado') {

                        CambiarCobrado(id, isChecked);
                    } else if (tipo === 'pagado') {

                        CambiarEstadoPago(id, isChecked);
                    }
                } else {
                    // Revertir el cambio en el switch si se cancela la acción
                    if (tipo === 'cobrado') {
                        document.getElementById(`rolActivoCobrado_${id}`).checked = !isChecked;
                    } else if (tipo === 'pagado') {
                        document.getElementById(`rolActivoPagado_${id}`).checked = !isChecked;
                    }
                }
            });
        }

        // funcion para cambiar el estado true o ffalse de la columna pago
        function CambiarEstadoPago(id, isChecked) {

            $.ajax({
                url: 'components/rol/models/actualiza_valores_rol_pagado.php',
                type: 'POST',
                data: {
                    id: id,
                    pagado: isChecked,
                },
                success: function(response) {
                    Swal.fire('¡Éxito!', 'El estado de cobrado se ha actualizado correctamente.', 'success');
                    cargarDatos();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire('Error', 'Hubo un problema al actualizar el estado de cobrado.', 'error');
                }
            });
        }

        // funcion para cambiar el estado true o ffalse de la columna cobrado
        function CambiarCobrado(id, isChecked) {
            $.ajax({
                url: 'components/rol/models/actualizar_valores_rol_cobrado.php',
                type: 'POST',
                data: {
                    id: id,
                    cobrado: isChecked
                },
                success: function(response) {
                    Swal.fire('¡Éxito!', 'El estado de pagado se ha actualizado correctamente.', 'success');
                    cargarDatos();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire('Error', 'Hubo un problema al actualizar el estado de pagado.', 'error');
                }
            });
        }

        // Llamar a la función para cargar los datos al cargar la página
        cargarDatos();

        // funcion para pasar el id del detalle y 
        function pasarID(id) {
            console.log(id);
            // Asignar el valor del ID de la propiedad al input correspondiente
            $('#id_propiedad').val(id);
            $('#id_propiedad').html(id);

            extraerdetalleid(id)

        }

        // Manejar clic en el botón "Grabar" dentro del modal de agregar
        $('#btnGrabar').on('click', function() {


            // Obtener los valores del formulario
            var id_propiedad = $('#id_propiedad').val();
            var valorRolAño = $('#valorRolAño').val();
            var ValorRol = $('#ValorRol').val();
            var mes = $('#mes').val();

            // Validación básica
            if (!valorRolAño || !ValorRol || mes === "") {
                Swal.fire('Error', 'Por favor, complete todos los campos requeridos.', 'error');
                return;
            }

            // Crear un objeto FormData
            var formData = new FormData($('#ingreso_valor_rol')[0]);

            // Enviar los datos usando AJAX
            $.ajax({
                url: '/rentdesk_final/components/rol/models/Insertar_valores_rol.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire('¡Éxito!', 'Datos enviados correctamente: ' + response, 'success');
                    $('#ModalAgregarValor').modal('hide'); // Opcional: Cerrar el modal después de enviar el formulario
                    cargarDatos(); // Volver a cargar los datos
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire('Error', 'Error al enviar los datos: ' + textStatus, 'error');
                }
            });
        });

        // Manejar clic en el botón "Guardar Cambios" dentro del modal de editar
        $('#btnGuardarCambios').on('click', function() {
            // Obtener los valores del formulario
            var idEdit = $('#idEdit').val();
            var valorRolAño = $('#valorRolAñoEdit').val();
            var ValorRol = $('#ValorRolEdit').val();
            var mes = $('#mesEdit').val();

            // Validación básica
            if (!valorRolAño || !ValorRol || mes === "") {
                Swal.fire('Error', 'Por favor, complete todos los campos requeridos.', 'error');
                return;
            }

            // Crear un objeto FormData
            var formData = new FormData($('#editar_valor_rol')[0]);

            // Enviar los datos usando AJAX
            $.ajax({
                url: '/rentdesk_final/components/rol/models/actualizar_valores_rol.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire('¡Éxito!', 'Datos actualizados correctamente: ' + response, 'success');
                    $('#ModalEditarValor').modal('hide'); // Opcional: Cerrar el modal después de enviar el formulario
                    cargarDatos(); // Volver a cargar los datos
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire('Error', 'Error al actualizar los datos: ' + textStatus, 'error');
                }
            });
        });

        // Función para manejar el cierre del modal y limpiar los campos
        $('#ModalEditarValor').on('hidden.bs.modal', function() {
            $('#editar_valor_rol')[0].reset();
            $('#idEdit').val('');
        });

    });
</script>