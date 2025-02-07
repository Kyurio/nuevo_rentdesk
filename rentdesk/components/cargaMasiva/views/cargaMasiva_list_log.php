<script>loadCargaMasivaLog('<?php echo @$token;?>','<?php echo @$pag_origen;?>');</script>
<h2>Log Errores Cargas Masivas <?php echo @$nombre;?></h2>           

<div class="herramientas">
<button type="button" class="btn btn-info btn-sm" onClick="document.location.reload();">
  <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
</button>
</div>
  <form name="formulario" id="formulario" method="post" action="" enctype="multipart/form-data">       
	<table id="tabla" class="display" cellspacing="0" width="100%">

			<thead>
				<tr>
					<th>Id.</th>
					<th>Contenido Linea</th>
					<th>Descripción del Error</th>
				</tr>
			</thead>
		  
	</table>
	<div class="form-group"></div>
	<div class="row"> 
		<div class="col-lg-12 text-center">
			<a href="<?php echo $nav;?>">
			<button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>	
		</div>

	</div>
 </form>