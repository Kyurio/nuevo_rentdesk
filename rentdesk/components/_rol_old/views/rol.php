               <h2>Rol</h2>

               <form name="formulario3" id="formulario3" method="post" action="javascript: enviar();">

               	<div class="row">
               		<div class="col-md-12">
               			<div class="form-group">
               				<label><span class="obligatorio">*</span> Nombre del Rol:</label>
               				<input type="text" class="form-control" name="nombre" id="nombre" placeholder="El nombre del nuevo rol a crear (obligatorio)" required data-validation-required autofocus value="<?php echo @$result->nombre; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
               			</div>
               		</div>
               	</div>
               	<div class="row">
               		<div class="col-md-12">
               			<div class="form-group">
               				<strong>Permisos:</strong><br>
               				-Selecciona los módulos a los cuales tendrá acceso <?php echo @$result->nombre; ?>:<br>
               				<?php echo $permisos; ?>
               			</div>
               			<input type="hidden" name="token" value="<?php echo @$result->token; ?>">
               		</div>
               	</div>
               	<div class="row">
               		<div class="col-lg-12 text-center">

               			<a href="index.php?component=rol&amp;view=rol_list">
               				<button type="button" class="btn btn-primary"> &lt;&lt; volver </button></a>
               			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               			<button type="submit" class="btn btn-primary"> Guardar </button>
               		</div>
               	</div>

               </form>