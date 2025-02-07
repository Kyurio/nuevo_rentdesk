<?php echo @$script_empresa;?>
       


            <h2>Usuario</h2>       
            <form name="formulario" id="formulario" method="post" action="javascript: enviar();" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">
                        <label ><span class="obligatorio">*</span>Nombre del Usuario:</label>
							<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre Usuario (obligatorio)" required data-validation-required value="<?php echo @$result->nombreUsuario;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
						</div>

                        <div class="form-group">
                        <label ><span class="obligatorio">*</span>Email:</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email (obligatorio)" required data-validation-required value="<?php echo @$result->email;?>" onkeyup="minusculas(this);" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
						
											
						<div class="form-group">
                        <label ><span class="obligatorio">*</span>Tipo de Usuario:</label>
                            <?php echo $rol;?>
						</div>
						
						
						
						
						<div class="form-group">
                        <label ><span class="obligatorio">*</span>Contraseña<?php echo $ingrese_nuevo;?>:</label>
                            <input type="password" class="form-control" name="clave" id="clave" placeholder="<?php echo $pass_placeholder;?>" <?php echo $pass_required;?> data-validation-required  value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
						
											
			 
                        
                        <div class="form-group"></div>
						<input type="hidden" name="token" id="token" value="<?php echo @$result->token;?>">
                    
                    </div>
				</div>
				
				<hr>
				 <div class="row">
				 
						
						<div class="col-md-4">
								<div class="form-group campo-empresa">
									<label ><span class="obligatorio">*</span>Empresa:</label>
									<?php echo $opcion_empresa;?>
								</div>
						</div>
						
						<div class="col-md-4">
								<div class="form-group campo-empresa">
									<label ><span class="obligatorio">*</span>Sucursal:</label>
									<div id="divsucursal"><?php echo $opcion_sucursal;?></div>
								</div>
						</div>
						
					
						<div class="col-md-4">
							<div class="form-group boton-empresa">	
							   <a href="javascript:agregarEmpresa();">
							   <button type="button" class="btn btn-primary" style="margin-top:25px; " >  Agregar Empresa </button>
							   </a>
							 </div> 
						</div>
						<div class="row"> 
								<div style="clear:both; width:100%;"></div>
								<div class="col-md-12 text-left">
									<?php echo $lista_accesos_perfil;?>
								</div>
						</div>
						
				 
				 </div>
				
				<hr>
				
				
				<div class="row">               

                    <div class="col-lg-12 text-center">
                    <a href="index.php?component=user&view=user_list">
                    <button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary" > Guardar </button>
                    </div>
                    


                </div>

            </form>
