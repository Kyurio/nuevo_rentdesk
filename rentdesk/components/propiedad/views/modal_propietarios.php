<?php
@$token_propiedad     = $_GET["token_propiedad"];
@$participacion     = $_GET["participacion"];
?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title></title>

    <link href="../../../favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- Bootstrap -->
    <link href="../../../js/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../template/css/main.css" rel="stylesheet" />
    <link href="../../../js/bootstrap/css/bootstrap-dialog.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="../../../js/datatable/media/css/jquery.dataTables.css">



    <!-- Custom CSS -->


    <!-- Custom Fonts -->

    <link href="../../../template/font-awesome/css/all.css" rel="stylesheet">



    <link href="../../../template/css/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../../js/fancybox/jquery.fancybox.min.css">

    <link href="../../../template/css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="../../../js/jquery-1.12.1.min.js"></script>
    <script src="../../../js/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../../js/bootstrap/js/bootstrap-dialog.js"></script>
    <script src="../../../js/validadores.js"></script>
    <script src="../../../js/funciones.js"></script>

    <script src="../../../js/fancybox/jquery.fancybox.min.js"></script>

    <script type="text/javascript" language="javascript" src="../../../js/datatable/media/js/jquery.dataTables.js"></script>




    <script language="JavaScript" src="../../../js/jquery.blockUI.js"></script>

    <script src="../js/js.js"></script>


    <script>




    </script>

</head>

<style>
    .main-panel {
        width: 100% !important;
        background-color: #ffffff !important;
    }

    .main-panel>.content {
        padding: 30px !important;
        min-height: 100%;
        margin-top: 0px !important;
    }
</style>

<body class="">

    <div class="main-panel">
        <div class="content">



            <script>
                loadPropietarios('<?php echo @$token_propiedad; ?>', '<?php echo @$participacion; ?>');
            </script>
            <h2>Seleccione al Propietario</h2>

            <div class="herramientas">
                <button type="button" class="btn btn-default btn-sm recargar" onClick="javascript: document.location.reload();">
                    <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
                </button>
            </div>

            <table id="tabla" class="display" cellspacing="0" width="100%">

                <thead>
                    <tr>
                        <th>Tipo Doc.</th>
                        <th>Num. Doc.</th>
                        <th>Nombre</th>
                        <th>Ap. Paterno</th>
                        <th>Ap. Materno</th>
                        <th>Estado</th>
                        <th>Asignar</th>
                    </tr>
                </thead>

            </table>










        </div>
    </div>





    <script type="text/javascript" src="../../../js/datetimepicker/moment.js"></script>
    <script type="text/javascript" src="../../../js/datetimepicker/es_moment.js"></script>
    <script type="text/javascript" src="../../../js/datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="../../../login/js/bootstrap-show-modal.js"></script>


    <script src="../../../js/rut/jquery.rut.js"></script>


</body>



</html>