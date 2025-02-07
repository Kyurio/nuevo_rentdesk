<script>
	$(document).ready(function() {
		<?php //echo $filtros_jquery;
		?>
		//$('.campos13').mask('0000/00');
	});
</script>
<h2>Filtros para el Reporte <?php echo $nombre_repporte; ?></h2>
<form name="formulario" id="formulario" method="post" action="javascript: enviar('<?php echo $url_reportes_eje; ?>');" enctype="multipart/form-data">


	<div class="row">


		<?php echo $empresa; ?>
		<?php echo $sucursal; ?>
		<?php echo $filtros_reporte; ?>
		<?php echo $select_tipo_reporte; ?>

	</div>





	<div class="row">


		<div class="col-md-4">
			<div class="form-group boton-empresa">
				<input type="hidden" id="campos" name="campos" value="<?php echo $nombres_campos; ?>">
				<input type="hidden" id="token" name="token" value="<?php echo $token; ?>">

			</div>
		</div>


		<div class="col-lg-12 text-center">
			<a href="<?php echo $nav; ?>">
				<button type="button" class="btn btn-primary"> &lt;&lt; volver </button></a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button type="submit" class="btn btn-primary"> Generar Reporte </button>
		</div>
	</div>




</form>