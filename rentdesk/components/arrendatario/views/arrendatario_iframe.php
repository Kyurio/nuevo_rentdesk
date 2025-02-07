<script>
$( document ).ready(function() {
$(".sidebar").css("display","none");
$(".navbar").css("display","none");
$(".main-panel").css("width","100%");
$(".btn-primary").css("display","none");
$(".footer").css("display","none");

$('input').attr('readonly', true);
$('select').attr('readonly', true);

});
</script>


 <script src="js/region_ciudad_comuna.js"></script>
 <script src="js/region_ciudad_comuna_com.js"></script>

            <h2>Arrendatario</h2>       
            <form name="formulario" id="formulario" method="post" action="javascript: enviar();" enctype="multipart/form-data">

                <div class="row">
						<div class="col-md-2">
							<div class="form-group">
							<label ><span class="obligatorio">*</span>Tipo Documento:</label>
								<?php echo $opcion_tipo_documento;?>	
							</div>
						</div>
						
						<div class="col-md-2">
							<div class="form-group">
							<label ><span class="obligatorio">*</span>Nro. Documento:</label>
								<input type="text" class="form-control" maxlength="250" name="numDocumento" id="numDocumento" placeholder="Numero de Documento" required data-validation-required value="<?php echo @$result->numDocumento;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);buscarpersona(this.value);"  >
							<div id="errorrut"></div>
							</div>
						</div>	
						
						<div class="col-md-2">
							<div class="form-group">
							<label >Dig. Verif.:</label>
								<input type="text" class="form-control" maxlength="1" name="digitoVerificador" id="digitoVerificador" placeholder="DV" value="<?php echo @$result->digitoVerificador;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							<label ><span class="obligatorio">*</span> Tipo Personalidad:</label>
								<?php echo $opcion_tipo_persona_legal;?>	
							</div>
						</div>	
						
						<div class="col-md-3">
							<div class="form-group">
							<label ><span class="obligatorio">*</span> Estado:</label>
								<?php echo $opcion_estado_persona;?>	
							</div>
						</div>	
				</div>	
				<div class="row">		
						<div class="col-md-4">
							<div class="form-group">
							<label ><span class="obligatorio">*</span> Nombre:</label>
								<input type="text" class="form-control" maxlength="250" name="nombre" id="nombre" placeholder="nombre" required data-validation-required value="<?php echo @$result->nombre;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
							</div>
						</div>	
						
						<div class="col-md-4">
							<div class="form-group">
							<label >Ap. Paterno:</label>
								<input type="text" class="form-control" maxlength="250" name="apellidoPat" id="apellidoPat" placeholder="apellido paterno" value="<?php echo @$result->apellidoPat;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
							</div>
						</div>	
						
						<div class="col-md-4">
							<div class="form-group">
							<label >Ap. Materno:</label>
								<input type="text" class="form-control" maxlength="250" name="apellidoMat" id="apellidoMat" placeholder="apellido materno" value="<?php echo @$result->apellidoMat;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
							</div>
						</div>	
				</div>			
				<div class="row">		
						<div class="col-md-4">
							<div class="form-group">
							<label >Fono:</label>
								<input type="text" class="form-control" maxlength="50" name="fono" id="fono" placeholder="Fono" value="<?php echo @$result->fono;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<label >Celular:</label>
								<input type="text" class="form-control" maxlength="50" name="celular" id="celular" placeholder="celular" value="<?php echo @$result->celular;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
							</div>
						</div>	
						<div class="col-md-4">
							<div class="form-group">
							<label >Email:</label>
								<input type="email" class="form-control" maxlength="250" name="email" id="email" placeholder="email" value="<?php echo @$result->email;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
							</div>
						</div>	
				</div>	
				
				<div class="row">
					<div class="col-md-12 panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Dirección Particular</h3> 
						</div>
						<div class="row">
							<div class="col-lg-4 form-group">  
								<label ><span class="obligatorio">*</span>Pais:</label>
								<div id="divpais"></div>
								<input type="hidden" id="hiddenpais" name="hiddenpais" value="<?php echo @$pais;?>">
							</div>
						
							<div class="col-lg-4 form-group"> 
								<label ><span class="obligatorio">*</span>Región:</label>
								<div id="divregion"></div>
								<input type="hidden" id="hiddenregion" name="hiddenregion" value="<?php echo @$region;?>">
							</div>
											   
							<div class="col-lg-4 form-group">  
								<label ><span class="obligatorio">*</span>Comuna:</label>
								<div id="divcomuna"></div>
								<input type="hidden" id="hiddencomuna" name="hiddencomuna" value="<?php echo @$comuna;?>">
							</div>
						</div>
						<div class="row">	
								<div class="col-md-12">
									<div class="form-group">
									<label >Dirección:</label>
										<input type="text" class="form-control" maxlength="500" name="direccion" id="direccion" placeholder="direccion" value="<?php echo @$result->direccion;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
									</div>
								</div>
						</div>	
					</div>	
				</div>	

				<div class="row">
					<div class="col-md-12 panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Dirección Comercial</h3> 
						</div>
						<div class="row">
							<div class="col-lg-4 form-group">  
								<label >Pais:</label>
								<div id="divpaiscom"></div>
								<input type="hidden" id="hiddenpaiscom" name="hiddenpaiscom" value="<?php echo @$paisCom;?>">
							</div>
						
							<div class="col-lg-4 form-group"> 
								<label >Región:</label>
								<div id="divregioncom"></div>
								<input type="hidden" id="hiddenregioncom" name="hiddenregioncom" value="<?php echo @$regionCom;?>">
							</div>
											   
							<div class="col-lg-4 form-group">  
								<label >Comuna:</label>
								<div id="divcomunacom"></div>
								<input type="hidden" id="hiddencomunacom" name="hiddencomunacom" value="<?php echo @$comunaCom;?>">
							</div>
						</div>
						<div class="row">	
								<div class="col-md-12">
									<div class="form-group">
									<label >Dirección:</label>
										<input type="text" class="form-control" maxlength="500" name="direccioncom" id="direccioncom" placeholder="direccion" value="<?php echo @$result->direccionCom;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
									</div>
								</div>
						</div>	
					</div>	
				</div>					

				<div class="row">
						<div class="col-md-3">
							<div class="form-group">
							<label >Banco:</label>
								<?php echo $opcion_banco;?>	
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<label >Numero de Cuenta:</label>
								<input type="text" class="form-control" maxlength="100" name="numCuenta" id="numCuenta" placeholder="numCuenta" value="<?php echo @$result->numCuenta;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
							</div>
						</div>	
						
                        <div class="form-group"></div>
						<input type="hidden" name="token" id="token" value="<?php echo @$result->token;?>">
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
