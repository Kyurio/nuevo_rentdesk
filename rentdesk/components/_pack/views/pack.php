               
			   <h2>Pack</h2>
			   
            <form name="formulario3" id="formulario3" method="post" action="javascript: enviar();">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label ><span class="obligatorio">*</span> Descripción:</label>
                            <input type="text" class="form-control" maxlength="250" name="descripcion" id="descripcion" placeholder="descripción" required data-validation-required autofocus value="<?php echo @$result->descripcion;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
						<div class="form-group">
                        <label >Activo:</label>
							<select name="activo" id="activo" class="form-control" > 
							<?php echo $opcion_activo;?>	
							</select> 	
						</div>
						<div class="form-group"></div>
					</div>	
				</div>
				<div class="row">	
                     <div class="col-lg-12 text-center">
					
						<a href="<?php echo $nav;?>">
						<button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary" > Guardar </button>
                    </div>
                </div>
				<div class="row">
					<div class="col-md-12">
                        <div class="form-group">
							<?php echo $lista_productos;?>
						</div>	
						<input type="hidden" name="token" id="token" value="<?php echo @$token;?>">
					</div>	
				</div>			

            </form>