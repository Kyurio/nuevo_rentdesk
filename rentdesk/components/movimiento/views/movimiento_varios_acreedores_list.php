<script>
    //loadVariosAcreedores_List();
</script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
     $( document ).ready(function() {
		loadVariosAcreedores_List();
}); 
</script>
<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propiedades</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <!-- <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=arriendo&view=arriendo_list" style="text-decoration: none;color:#66615b">Arriendos</a></li> -->
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Movimientos - Varios Acreedores</li>
            </ol>
        </div>
    </div>

</div>

<?php 

$config		= new Config;

?>


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
    <!-- <div class="herramientas">
        <button type="button" class="btn btn-default btn-sm recargar" onClick="document.location.reload();">
            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
    </div>
 -->


    <div class="card">
        <div class="card-body ">
            <div class="row g-3">
			<!--
                <button type="button" class="btn btn-default btn-outline-primary btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Descuento">
                    <span>Descargar Movimientos</span>
                </button>
			-->	
				<div class="col-md-2">
						<label for="fecha_desde"><span class="obligatorio">*</span> Fecha desde</label>
						<input name="fecha_desde" id="fecha_desde" onchange="recalcularMes()" class="form-control" type="date" value="<?php echo @$fecha_desde ?>" />
						<span id="startDateSelected"></span>
					</div>
					<div class="col-md-2">
						<label for="fecha_hasta"> Fecha hasta</label>
						<input name="fecha_hasta" id="fecha_hasta" onchange="recalcularMes()" class="form-control" type="date" value="<?php echo @$fecha_hasta ?>" />
						<!-- <span id="startDateSelected"></span> -->
					</div>
                <div class="col-md-2">	
					<button type="button" class="btn btn-primary" onclick="loadVariosAcreedores_List();" >Buscar</button>
				</div>
            </div>
			<div class="herramientas">
			<button type="button" class="btn btn-primary btn-sm" onclick="generarExcel('<?php echo $config->urlbase;?>')">Generar excel</button>
			                <button type="button" class="btn btn-info btn-sm" onClick="document.location.reload();">
                    <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
                </button>
			</div>
            <div class="table-responsive overflow-auto">
                <table id="reajustes" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
						    <th>Id cierre</th>
                            <th>Fecha</th>
                            <th>Propiedad</th>
                            <th style="min-width:220px;" ><i class='fa-solid fa-house-user' style='color:#313131;font-size:12px;' title='Propietario' ></i> Propietario / <i class='fa-solid fa-user-large' style='font-size:10px; color:#707070;' title='Beneficiario'></i> Beneficiario</th>
                            <th>Tipo</th>
                            <th>Razón</th>
                            <th>Monto</th>

                        </tr>
                    </thead>

                </table>
				
            </div>
        </div>
    </div>
</div>