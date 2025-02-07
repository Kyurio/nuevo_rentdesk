<script>  
  $(function () {
		  $('#datetimepicker1').datetimepicker({
				format : "DD-MM-YYYY",
				defaultDate: moment('<?php echo date("d-m-Y");?>',"DD-MM-YYYY")
			});
    }); 

	
	var bancos = "<?php echo $options_bancos;?>";
	

</script>	


            <h2>Ingresar Pago</h2>       
            <form name="formulario" id="formulario" method="post" action="javascript: enviarPago('<?php echo $_SESSION["cant_decimales"];?>','<?php echo $_SESSION["separador_mil"];?>');" enctype="multipart/form-data">

               <div class="row">
					<div class="col-md-2">		
                        <div class="form-group">
                        <label >Cód. Propiedad: <?php echo $link_propiedad;?></label>
                            <input type="text" class="form-control" maxlength="250" name="codigo_propiedad" id="codigo_propiedad" readonly value="<?php echo @$result->codigo_propiedad;?>"  >
                        </div>
					</div>	
					<div class="col-md-6">		
						<div class="form-group">
                        <label >Dirección:</label>
                            <input type="text" class="form-control" maxlength="250" name="direccion" id="direccion" readonly value="<?php echo @$result->direccion;?>"  >
                        </div>
					</div>
					<div class="col-md-1">	
						<div class="form-group">
                        <label >Número:</label>
                            <input type="text" class="form-control" maxlength="250" name="numero" id="numero" readonly value="<?php echo @$result->numero;?>"  >
                        </div>
					</div>
					<div class="col-md-1">	
						<div class="form-group">
                        <label >Depto:</label>
                            <input type="text" class="form-control" maxlength="250" name="numero_depto" id="numero_depto" readonly value="<?php echo @$result->numero_depto;?>"  >
                        </div>
					</div>	
					<div class="col-md-2">		
						<div class="form-group">
                        <label >Rol:</label>
                            <input type="text" class="form-control" maxlength="250" name="rol" id="rol" readonly value="<?php echo @$result->rol;?>"  >
                        </div>
					</div>	
				</div>		
				<div class="row">	
					<div class="col-md-4">
						<div class="form-group">
                        <label >Pago Minimo:</label>
                            <input type="text" class="form-control" maxlength="250" name="monto_deuda" id="monto_deuda" readonly value="<?php echo formatea_number(@$monto_deuda,$_SESSION["cant_decimales"],$_SESSION["separador_mil"]);?>"  >
                        </div>
					</div>	
					<div class="col-md-4">	
						<div class="form-group">
                        <label >Periodo:</label>
                            <input type="text" class="form-control" maxlength="250" name="periodo" id="periodo" readonly value="<?php echo @$result->periodo;?>"  >
                        </div>
					 </div>
					 <div class="col-md-4">
                       <div class="form-group">
                        <label >Fecha Vencimiento:</label>
                            <input type="text" class="form-control" maxlength="250" name="fecha_vencimiento" id="fecha_vencimiento" readonly value="<?php echo fecha_postgre_a_normal(@$result->fecha_vencimiento);?>"  >
                        </div>
					 </div>	
				</div>		
				<div class="row">	
					<div class="col-md-4">	
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Medio de Pago:</label>
							<?php echo $opcion_medio_pago;?>	
						</div>
					</div>	
					<div class="col-md-4">	
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Pago a Realizar:</label>
                            <input type="text" class="form-control" maxlength="25" name="monto_pagado" id="monto_pagado" required data-validation-required value="0"  onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'<?php echo $_SESSION["cant_decimales"];?>','<?php echo $_SESSION["separador_mil"];?>');"  >
                        </div>
					</div>	
					<div class="col-md-4">		
						<div class="form-group">
                        <label >Cód. Autorización:</label>
                            <input type="text" class="form-control" maxlength="250" name="cod_autorizacion" id="cod_autorizacion" value=""  >
                        </div>
					</div>	

                        <div class="form-group"></div>
						<input type="hidden" name="token" id="token" value="<?php echo @$result->token;?>">
						<input type="hidden" name="token_contrato" id="token_contrato" value="<?php echo @$token_contrato;?>">
						<input type="hidden" name="monto_cheque" id="monto_cheque" value="">
				</div>	
				
				
				
				<div class="cheques">
					
				
				
				</div>
				
				
				
				<input id="cantidadCheques" name="cantidadCheques" type="hidden" value="0">
				
				
				<div class="row"><div class="col-md-12"><br/></div></div>
				
				<div class="row"> 
				
					<div class="col-md-12">
						<label >Detalle:</label>
						<div style="clear:both; width:100%;"></div>
						<div class="col-md-12 text-left">
							<?php echo $lista_items_pago;?>
						</div>
					</div>	
				</div>
				
						
				<div class="row">
                  <div class="col-lg-12 text-center">
                    <a href="<?php echo $nav;?>">
                    <button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					 <?php if (@$monto_deuda > 0) { ?>
                        <button type="submit" class="btn btn-primary" > Ingresar Pago </button>
					 <?php } ?>	
                    </div>

                </div>

            </form>
