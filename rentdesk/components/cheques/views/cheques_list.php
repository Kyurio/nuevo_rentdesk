<div id="header" class="header-page">
    <div>
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item">
                    <a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b">
                        <span>Inicio</span>
                    </a>
                </li>
                <li class="breadcrumb-item" style="color: #e62238" aria-current="page">
                    <a href="index.php?component=propiedad&view=propiedad_list" style="text-decoration: none;color:#66615b">Propiedades</a>
                </li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Cheques</li>
            </ol>
        </div>
    </div>
</div>

<div class="herramientas">
    <button type="button" class="btn btn-info btn-sm" onClick="document.location.href='index.php?component=cheques&view=cheques_list';">
        <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
    </button>

</div>

<div class="row">
    <div class="col-md-3">
        <div class="mt-2">
            <label for="fechaDesde">Desde:</label>
            <input type="date" id="fechaDesde" name="fechaDesde" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="mt-2">
            <label for="fechaHasta">Hasta:</label>
            <input type="date" id="fechaHasta" name="fechaHasta" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="mt-2">
            <label for="fechaHasta">Estado:</label>
            <select class="form-control" id="estado_cheque" name="estado_cheque">
                <option selected value="false">Por Depositar</option>
                <option value="true">Depositados</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <button id="filtrarFechas" class="btn btn-primary mt-4">Filtrar</button>
    </div>
</div>

<div class="row top-100">
    <div class="col p-0">
        <!-- Se ha eliminado el formulario de búsqueda -->

    </div>
</div>

<div class="content content-page">

    <div class="col">
        <button id="depositarCheques" type="button" class="btn btn-secondary">Depositar Cheques</button>
    </div>

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive overflow-auto">

                        <table id="tablaCheques" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Ficha Arriendo</th>
                                    <th>ID Propiedad</th>
                                    <th>Fecha Cobro</th>
                                    <th>Tipo Propiedad</th>
                                    <th>Nombre Banco</th>
                                    <th>Girador</th>
                                    <th>Número Cheque</th>
                                    <th>Monto</th>
                                    <th>Depositado</th>
                                    <th>Cobrado</th>
                                    <th>Comentario</th> <!-- Nueva columna -->
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

<script>
    function formatCurrency(value) {
        // Asegúrate de que el valor sea un número
        var number = parseFloat(value);
        if (isNaN(number)) {
            return ''; // Retorna vacío si no es un número
        }

        // Formatear el número con separadores de miles y dos decimales
        return '$ ' + number.toLocaleString('es-ES', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }


    function loadChequesList() {

        var fechaDesde = $('#fechaDesde').val();
        var fechaHasta = $('#fechaHasta').val();
        var estadoCheque = $('#estado_cheque').val();


        if (fechaDesde && fechaHasta && fechaHasta < fechaDesde) {
            Swal.fire({
                title: 'Precaución',
                text: 'La fecha "Hasta" no puede ser menor que la fecha "Desde".',
                icon: 'info',
                confirmButtonText: 'OK'
            });
            return;
        }

        $.ajax({
            url: 'components/cheques/models/leer_valores_cheques.php',
            type: 'GET',
            dataType: 'json',
            data: {
                fechaDesde: fechaDesde,
                fechaHasta: fechaHasta,
                estado: estadoCheque,
            },
            success: function(data) {

                if (data.error) {
                    console.error('Error:', data.error);
                    $('#tablaCheques tbody').empty();
                    return;
                }

                var dataTable = $('#tablaCheques').DataTable();
                dataTable.clear().destroy();

                $('#tablaCheques tbody').empty();

                $.each(data, function(index, cheque) {

                    if (cheque.depositado == false) {

                       var depositoContenido =`<label class="switch">
                            <input name="deposito" class="form-check-input switchChecks" type="checkbox" role="switch" 
                                    monto="${cheque.monto}" token-cheque="${cheque.tokencheque}" data-token="${cheque.token}" id-propiedad="${cheque.id_propiedad}">
                            <span class="slider round"></span>
                        </label>`
                        $("#depositarCheques").show();

                    } else {
                        var depositoContenido = "Depositado"; // Si desposito es false, mostrar "Depositado"
                        $("#depositarCheques").hide();
                    }


                    $('#tablaCheques tbody').append(
                        `<tr>
                            <td><a href="index.php?component=arriendo&view=arriendo_ficha_tecnica&token=${cheque.tokenfichaarriendo}">${cheque.ficha_arriendo}</a></td>
                            <td><a href="index.php?component=propiedad&view=propiedad_ficha_tecnica&token=${cheque.token}">${cheque.id_propiedad}</a> - ${cheque.direccion}</td>
                            <td>${cheque.fecha_cobro}</td>
                            <td>${cheque.tipo_propiedad}</td>
                            <td>${cheque.nombre_banco}</td>
                            <td>${cheque.girador}</td>
                            <td>${cheque.numero_cheque}</td>
                            <td>${formatCurrency(cheque.monto)}</td>
                            <td>
                                <div class="d-flex">
                                    ${depositoContenido} 
                                </div>
                            </td>
                            <td>${cheque.cobrado}</td>
                            <td>${cheque.comentario}</td>
                        </tr>`
                    );
                });



                $('#tablaCheques').DataTable({

                    language: {
                        search: "Buscar:",
                        paginate: {
                            next: "Siguiente",
                            previous: "Anterior"
                        },
                        info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                        infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                        infoFiltered: "(filtrado de _MAX_ entradas en total)"
                    },
                    lengthMenu: [10, 25, 50, 100],
                    dom: 'Bfrtip',
                    order: [
                        [0, 2, "asc"]
                    ],
                    buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Descargar Excel',
                        title: 'Listado cheques',
                        className: 'btn btn-success',
                        action: function(e, dt, button, config) {
                            var fechaDesde = $('#fechaDesde').val();
                            var fechaHasta = $('#fechaHasta').val();

                            // Validación de fechas solo si ambas fechas están presentes
                            if (fechaDesde && fechaHasta && fechaHasta < fechaDesde) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Fechas no válidas',
                                    text: 'La fecha hasta no puede ser menor que la fecha desde.'
                                });
                                return; // Detener la acción de exportación
                            }
                            // Ejecutar la acción de exportación de Excel
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                        }
                    }],
                    columnDefs: [{
                            targets: 0, // Primera columna (ficha_arriendo)
                            width: "5%" // Ajusta el ancho según lo que necesites
                        },
                        {
                            targets: 1, // Segunda columna (id_propiedad y direccion)
                            width: "30%"
                        },
                        {
                            targets: 2, // Tercera columna (fecha_cobro)
                            width: "5%"
                        },
                        {
                            targets: 3, // Cuarta columna (tipo_propiedad)
                            width: "10%"
                        },
                        {
                            targets: 4, // Quinta columna (nombre_banco)
                            width: "15%"
                        },
                        {
                            targets: 5, // Sexta columna (girador)
                            width: "20%"
                        },
                        {
                            targets: 6, // Séptima columna (numero_cheque)
                            width: "5%"
                        },
                        {
                            targets: 7, // Octava columna (monto)
                            width: "10%"
                        },
                        {
                            targets: 8, // Novena columna (deposito)
                            width: "5%"
                        },
                        {
                            targets: 9, // Décima columna (cobrado)
                            width: "10%"
                        },
                        {
                            targets: 10, // Última columna (comentario)
                            width: "20%"
                        }
                    ]
                });
            },

            error: function(xhr, status, error) {
                console.error('Error:', error);
                $('#tablaCheques tbody').empty();
            }
        });

    }



    function depositarCheques(monto, idPropiedad) {
        // Retornamos la PROMESA
        return $.ajax({
            url: 'components/cheques/models/depositar_cheques.php',
            type: 'POST',
            data: {
                monto: monto,
                idPropiedad: idPropiedad
            },
            dataType: 'json',
            success: function(response) {
                // Podrías manejar algo puntual acá si quieres
                if (response.status === 'error') {
                    console.warn(`Error depositando cheque ID ${idPropiedad}: ${response.message}`);
                } else {
                    console.log(`Cheque ID ${idPropiedad} depositado OK`);
                }
            },
            error: function(xhr, status, error) {
                // Manejo de error a nivel de XHR
                console.error(`Error XHR: cheque ID ${idPropiedad}`, error);
            }
        });
    }

    function actualizarCheques(token) {
        return $.ajax({
            url: 'components/cheques/models/actualizar_estado_cheques.php',
            type: 'POST',
            data: {
                token: token,
            },
            dataType: 'json',
            success: function(response) {
                console.log('Cheque actualizado:', response);
            },
            error: function(xhr, status, error) {
                console.error('Error al actualizar cheque:', error);
            }
        });
    }

    $('#filtrarFechas').click(function() {
        loadChequesList();
    });


    $(document).ready(function() {

        $('#generarExcel').click(function() {
            $('#tablaCheques').DataTable().button('.buttons-excel').trigger();
        });

        loadChequesList();

        // Evento para depositar cheques
        $('#depositarCheques').click(function() {
            // 1. Encontrar todos los switches que estén CHECKED
            let chequesSeleccionados = [];
            $('input[name="deposito"]:checked').each(function() {
                let monto = $(this).attr('monto');
                let idPropiedad = $(this).attr('id-propiedad');
                let token = $(this).attr('token-cheque');
                chequesSeleccionados.push({
                    monto,
                    idPropiedad,
                    token,
                });
            });

            // 2. Validar que haya al menos un cheque
            if (chequesSeleccionados.length === 0) {
                Swal.fire({
                    title: 'Alerta',
                    text: 'No se han seleccionado cheques para depositar.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // 3. Para cada cheque, ejecutamos depositarCheques(...) y almacenamos la promesa
            let promises = chequesSeleccionados.map(cheque => {
                return depositarCheques(cheque.monto, cheque.idPropiedad)
                    .then((resDeposit) => {
                        // Si el depósito fue exitoso, llamamos a actualizarCheques
                        // y retornamos ESA promesa para encadenar
                        if (resDeposit.status === 'success') {
                            return actualizarCheques(cheque.token);
                        } else {
                            // Si hubo error en depositarCheques, retornamos esa respuesta
                            // para marcarlo como error y no llamar a actualizarCheques
                            return resDeposit;
                        }
                    })
                    .catch((error) => {
                        // Maneja el error si depositarCheques falló con un reject 
                        // (error de red, error 500, etc.)
                        return {
                            status: 'error',
                            message: error
                        };
                    });
            });

            // 4. Cuando TODAS las promesas finalicen (éxito o error), realizamos la acción final
            Promise.all(promises).then((results) => {
                // "results" es un array con la respuesta de cada $.ajax() que se resolvió
                // Podrías revisar si hubo algún error devuelto, etc.

                // EJEMPLO: Verificamos si alguna respuesta tuvo "status" = "error"
                let huboErrores = false;
                for (let i = 0; i < results.length; i++) {
                    if (results[i].status === 'error') {
                        huboErrores = true;
                        break;
                    }
                }

                if (!huboErrores) {
                    // Todo salió bien => Mensaje de éxito
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Los cheques se han depositado correctamente.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        loadChequesList(); // refrescar la tabla
                    });
                } else {
                    // Al menos uno falló => Mensaje de error genérico
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo uno o más cheques que no se pudieron depositar.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        loadChequesList(); // si prefieres recargar la tabla igual
                    });
                }

            }).catch((error) => {
                // Este .catch se dispara si alguna de las promesas se "rechazó"
                // (p.ej. falla de red, error 500, etc.)
                console.error('Error en Promise.all:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error general al depositar los cheques.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
    });
</script>