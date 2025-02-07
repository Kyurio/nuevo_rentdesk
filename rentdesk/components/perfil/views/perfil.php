<div id="header" style="margin-top:67px">

</div>
<div class="content content-page">

	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<form name="formularioUpload" id="formularioUpload" method="post" action="javascript: enviarFoto();" enctype="multipart/form-data">

					<div class="card card-user" style="min-height:50px !important;">
						<div class="image">
							<img src="images/fondo-perfil.jpg" alt="..." class="w-100">
						</div>
						<div class="card-body">
							<div class="author">
								<a href="#">

									<label for="fileUpload">
										<img id="uploadfoto" class="avatar border-gray" style="cursor: pointer;" src="upload/foto-perfil/<?php echo $foto; ?>" alt="...">
									</label>
									<div style="display:none;"><input type="file" id="fileUpload" name="fileUpload"> </div>


									<script>
										$(document).ready(function() {

											$("input[name=fileUpload]").change(function() {
												enviarFoto('<?php echo $foto; ?>');
											});

										});
									</script>

									<h5 class="title"><?php echo @$current_usuario->nombres; ?></h5>

								</a>
								<p class="description">
									<?php echo @$current_usuario->email; ?>
								</p>
								<div id="errorarchivo" style="color:red;"></div>
							</div>
						</div>
						<!-- <div class="card-footer">
          			            <hr>
          			            <div class="button-container">
          			              <div class="row">
          			                <div class="col-lg-3 col-md-6 col-6 ml-auto">

          			                </div>
          			              </div>
          			            </div>
          			          </div> -->
					</div>


				</form>


			</div>



			<div class="col-md-12" style="margin-bottom:200px;">
				<form name="formulario" id="formulario" method="post" action="javascript: enviar();" enctype="multipart/form-data">
					<div class="card card-user">
						<div class="card-header">
							<h5 class="card-title">Editar mi Perfil</h5>
						</div>
						<div class="card-body">
							<form>
								<div class="row">
									<div class="col-md-6 pr-1">
										<div class="form-group">
											<label>Compañía</label>
											<input type="text" class="form-control" disabled="" readonly placeholder="Company" value="<?php echo @$current_empresa->nombre; ?>">
										</div>
									</div>
									<div class="col-md-6 pl-1">
										<div class="form-group">
											<label for="exampleInputEmail1">Correo electrónico</label>
											<input type="email" class="form-control" placeholder="demo@arpis.cl" disabled="" readonly value="<?php echo @$result->email; ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 pr-1">
										<div class="form-group">
											<label>Nombres</label>
											<input type="text" class="form-control" maxlength="250" name="nombre_usuario" id="nombre_usuario" placeholder="" required data-validation-required value="<?php echo @$current_usuario->nombres; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 pr-1">
										<div class="form-group">
											<label>Dirección</label>
											<input type="text" class="form-control" maxlength="4000" name="direccion" id="direccion" placeholder="" value="<?php echo @$current_usuario->direccion; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
										</div>
									</div>
									<div class="col-md-6 pl-1">
										<div class="form-group">
											<label>Celular</label>
											<input type="text" class="form-control" maxlength="50" name="telefono" id="telefono" placeholder="" value="<?php echo @$current_usuario->telefono; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Cuéntanos algo de tí</label>
											<textarea class="form-control" maxlength="4000" name="observacion" id="observacion" placeholder="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"><?php echo @$current_usuario->observacion; ?></textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="update ml-auto mr-auto">
										<button type="submit" class="btn btn-primary btn-round">Enviar</button>
									</div>
								</div>
							</form>
						</div>
					</div>

				</form>
			</div>


		</div>
	</div>
</div>