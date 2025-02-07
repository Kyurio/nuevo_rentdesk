            <h2>Banco</h2>       
            <form name="formulario" id="formulario" method="post" action="javascript: enviar();" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">
                        <label ><span class="obligatorio">*</span> Descripción:</label>
                            <input type="text" class="form-control" maxlength="250" name="descripcion" id="descripcion" placeholder="descripción" required data-validation-required autofocus value="<?php echo @$result->descripcion;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>

                        <div class="form-group">
                        <label >Código Interno:</label>
                            <input type="text" class="form-control" maxlength="250" name="codigo_banco" id="codigo_banco" placeholder="Código Interno"  value="<?php echo @$result->codigoBanco;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
						
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Código SBIF:</label>
                            <input type="text" class="form-control" maxlength="3"  name="codigo_sbif" id="codigo_sbif" placeholder="Código SBIF" required data-validation-required value="<?php echo @$result->codigoSbif;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
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
                    <a href="javascript:window.history.back();">
					<button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>	
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary" > Aceptar </button>
                    </div>

                </div>

            </form>
