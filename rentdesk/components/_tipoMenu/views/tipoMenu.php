            <h2>Tipo Menu</h2>       
            <form name="formulario" id="formulario" method="post" action="javascript: enviar();" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">
                        <label ><span class="obligatorio">*</span> Descripción:</label>
                            <input type="text" class="form-control" maxlength="250" name="descripcion" id="descripcion" placeholder="descripción" required data-validation-required autofocus value="<?php echo @$result->descripcion;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>

						<div class="form-group">
                        <label >Orden:</label>
                            <input type="number" class="form-control" name="orden" id="orden" placeholder="orden" value="<?php echo @$result->orden;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
						
						<div class="form-group">
                        <label >Activo:</label>
							<select name="activo" id="activo" class="form-control" > 
							<?php echo $opcion_activo;?>	
							</select> 	
						</div>
						
                        <div class="form-group"></div>
						<input type="hidden" name="token" id="token" value="<?php echo @$result->token;?>">
					</div>
				</div>
				<div class="row">
                    <div class="col-lg-12 text-center">
                    <a href="index.php?component=tipoMenu&view=tipoMenu_list">
                    <button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary" > Aceptar </button>
                    </div>

                </div>

            </form>
