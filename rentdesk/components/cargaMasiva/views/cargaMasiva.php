<script>
	loadCargaMasivas('<?php echo @$token; ?>', '<?php echo @$pag_origen; ?>', '<?php echo $get_nombre; ?>');
</script>

<div id="header" style="margin-top:67px">

</div>
<div class="content content-page" >
	<h2 style="margin-bottom:5px !important;">Cargas Masiva <?php echo @$nombre; ?> </h2>

	<form name="formulario" id="formulario" method="post" action="javascript: enviar();" enctype="multipart/form-data">

		<div class="col-sm-12"><strong>Adjuntar Archivo :</strong><br>
			<input id="archivo" name="archivo" type="file" onChange="validaArchivo(this);" class="btn btn-success btn-xs" />
		</div>

		<div class="herramientas">
			<button type="button" class="btn btn-info btn-sm" onClick="document.location.reload();">
				<span class="glyphicon glyphicon-refresh"></span> Recargar Datos
			</button>
		</div>

		<table id="tabla" class="display" cellspacing="0" width="100%">

			<thead>
				<tr>
					<th>Id. Carga</th>
					<th>Usuario</th>
					<th>Fecha</th>
					<th>Nombre Archivo</th>
					<th>Ver Archivo</th>
					<th>Estado</th>
					<th>Ver Log</th>
				</tr>
			</thead>

		</table>

		<div class="form-group"></div>
		<input type="hidden" name="token" id="token" value="<?php echo @$token; ?>">
		<input type="hidden" name="n" id="n" value="<?php echo $get_nombre; ?>">
		<div class="row">
			<div class="col-lg-12 text-center">
				<a href="index.php?component=cargaMasiva&view=cargaMasiva_list">
					<button type="button" class="btn btn-primary"> &lt;&lt; volver </button></a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="submit" class="btn btn-primary"> Aceptar </button>

			</div>

		</div>

	</form>
</div>