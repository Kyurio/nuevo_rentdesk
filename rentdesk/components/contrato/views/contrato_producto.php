 <script>
   $(function () {
		  $('#datetimepicker1').datetimepicker({
				format : "DD-MM-YYYY",
				defaultDate: moment("<?php echo  date('d-m-Y');?>","DD-MM-YYYY")
			});
    });
			
</script>
 <h2>Producto</h2>       
            <form name="formulario" id="formulario" method="post" action="javascript: agregarProducto();" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-md-3">
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Producto:</label>
							<?php echo $opcion_producto;?>	
						</div>
					</div>	
					<div class="col-md-3">	
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Fecha Inicio:</label>
							<div class="input-group" id="datetimepicker1">
								<input type="text" class="form-control" maxlength="50" name="fecha_inicio" id="fecha_inicio" placeholder="dd-mm-yyyy"  required data-validation-required <?php echo @$readonly;?>  value="<?php echo fecha_postgre_a_normal(@$result->fecha_inicio);?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
							   <span class="input-group-addon calendariodatepickter"><i class="fa fa-calendar" aria-hidden="true"></i></span>
							</div>
                        </div>
					</div>		
					<div class="col-md-3">	
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Valor:</label>
                            <input type="text" class="form-control" min="1" name="valor_cuota" id="valor_cuota" placeholder="valor" required data-validation-required <?php echo @$readonly;?>  value="<?php echo formatea_number(@$result->valor_cuota,'2',$_SESSION["separador_mil"]);?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'2','<?php echo $_SESSION["separador_mil"];?>');"  >
                        </div>
					</div>	
					<div class="col-md-3">		
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Plazo:</label>
                            <input type="number" class="form-control" min="1" name="plazo" id="plazo" placeholder="plazo" required data-validation-required <?php echo @$readonly;?>  value="<?php echo @$result->plazo;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
					</div>
				</div>	
				<div class="row">	
					<div class="col-md-4">		
						<div class="form-group">
                        <label >Fecha Fin:</label>
                            <input type="text" class="form-control" maxlength="50" name="fecha_fin" id="fecha_fin" placeholder="" readonly value="<?php echo fecha_postgre_a_normal(@$result->fecha_fin);?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
					</div>	
					<div class="col-md-4">	
						<div class="form-group">
                        <label >Fecha Ult. Vencimiento:</label>
                            <input type="text" class="form-control" maxlength="50" name="fecha_ult_vcto" id="fecha_ult_vcto" placeholder="" readonly value="<?php echo fecha_postgre_a_normal(@$result->fecha_ult_vcto);?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
					</div>

					<div class="col-md-4">	
						<div class="form-group">
                        <label >Descripcion</label>
                            <input type="text" class="form-control" name="texto_linea" id="texto_linea" placeholder="" value="<?php echo @$result->texto_linea;?>" readonly onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
					</div>	
				</div>	
				<div class="row">					
					<div class="col-md-4">		
						<div class="form-group">
                        <label >Cuotas Pagadas:</label>
                            <input type="number" class="form-control" name="cuota_pag" id="cuota_pag" placeholder="" value="<?php echo @$result->cuota_pag;?>" readonly onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
					</div>
					<div class="col-md-4">	
						<div class="form-group">
                        <label >Cuotas Pendientes:</label>
                            <input type="number" class="form-control" name="cuota_pend" id="cuota_pend" placeholder="" value="<?php echo @$result->cuota_pend;?>" readonly onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
					</div>	
					<div class="col-md-4">	
						<div class="form-group">
                        <label >Cuota Actual:</label>
                            <input type="number" class="form-control" name="cuota_act" id="cuota_act" placeholder="" value="<?php echo @$result->cuota_act;?>" readonly onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
					 </div>	
				</div>		
				<div class="row">		
                        <div class="form-group"></div>
						<input type="hidden" name="token" id="token" value="<?php echo @$result->token;?>">
						<input type="hidden" name="token_contrato" id="token_contrato" value="<?php echo @$token_contrato;?>">
						<input type="hidden" name="valor_arriendo" id="valor_arriendo" value="<?php echo @$valor_arriendo;?>">
				</div>		
				<div class="row">
                    <div class="col-lg-12 text-center">
				
                    <a href="<?php echo $nav;?>">
                    <button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>
			
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php 
							if($puede_guardar == 'S'){ 
						?>
                        <button type="submit" class="btn btn-primary" > Aceptar </button>
						<?php 
							}
						?>
                    </div>

                </div>

            </form>