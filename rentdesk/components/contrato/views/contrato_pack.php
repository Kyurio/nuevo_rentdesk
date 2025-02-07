 <h2>Agregar Productos desde un Pack</h2>       
            <form name="formulario" id="formulario" method="post" action="javascript: agregarPack();" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-md-3">
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Pack:</label>
							<?php echo $opcion_pack;?>	
						</div>
					</div>	
				</div>	
				<div class="row"></div>
				<div class="row">
					<div class="contenedor_productos"></div>
					<input type="hidden" name="token_contrato" id="token_contrato" value="<?php echo @$token_contrato;?>">
				</div>
				<div class="row">
                    <div class="col-lg-12 text-center">
					
                    <a href="<?php echo $nav;?>">
                    <button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>
			
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
                        <button type="submit" class="btn btn-primary" > Aceptar </button>
                    </div>

                </div>

            </form>