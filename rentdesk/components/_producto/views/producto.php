      
		   <h2>Producto</h2>       
            <form name="formulario" id="formulario" method="post" action="javascript: enviar();" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-6">
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Tipo Producto:</label>
							<?php echo $opcion_tipo_producto;?>	
						</div>
						
						<div class="form-group">
						<input type="hidden" name="id_tipo_monto" id="id_tipo_monto" value="<?php echo @$result->tipoMonto->idTipoMonto;?>">
                        <label ><span class="obligatorio">*</span> Tipo Monto:</label>
							<?php echo $opcion_tipo_monto;?>	
						</div>

						<div class="form-group">
						<input type="hidden" name="id_tipo_moneda" id="id_tipo_moneda" value="<?php echo @$result->tipoMoneda->idTipoMoneda;?>">
                        <label >Tipo Moneda:</label>
							<?php echo $opcion_tipo_moneda;?>	
						</div>
						
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Editable?:</label>
							<select name="editable" id="editable" class="form-control" onChange="validaEditable(this);" > 
							<?php echo $opcion_editable;?>	
							</select> 	
						</div>
						
						<div class="form-group">
                        <label >Valor:</label>
                            <input type="text" class="form-control" name="valor" id="valor" placeholder="valor" value="<?php echo formatea_number(@$result->valor,'2',$_SESSION["separador_mil"]) ;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'2','<?php echo $_SESSION["separador_mil"];?>');"  >
                        </div>
						
						<div class="form-group">
                        <label >Valor Arriendo Mínimo para Contratar:</label>
                            <input type="text" class="form-control" name="min_valor" id="min_valor" placeholder="0" value="<?php echo formatea_number(@$result->minValor,$_SESSION["cant_decimales"],$_SESSION["separador_mil"]) ;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,'<?php echo $_SESSION["cant_decimales"];?>','<?php echo $_SESSION["separador_mil"];?>');"  >
                        </div>
						
						<div class="form-group">
						<input type="hidden" name="id_tipo_responsable" id="id_tipo_responsable" value="<?php echo @$result->tipoResponsable->idTipoResponsable;?>">
                        <label ><span class="obligatorio">*</span> Tipo Responsable:</label>
							<?php echo $opcion_tipo_responsable;?>	
						</div>
						
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Descripción:</label>
                            <input type="text" class="form-control" maxlength="250" name="descripcionProd" id="descripcionProd" placeholder="descripción" required data-validation-required value="<?php echo @$result->descripcionProd;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
						
						<div class="form-group">
                        <label ><span class="obligatorio">*</span>  Texto Estado de cuenta/Liquidación:</label>
                            <input type="text" class="form-control" maxlength="250" name="texto_eecc" id="texto_eecc" placeholder="" required data-validation-required value="<?php echo @$result->textoEecc;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>

						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Renovable?:</label>
							<select name="renovable" id="renovable" class="form-control" > 
							<?php echo $opcion_renovable;?>	
							</select> 	
						</div>
						
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Reajustable?:</label>
							<select name="reajustable" id="reajustable" class="form-control" > 
							<?php echo $opcion_reajustable;?>	
							</select> 	
						</div>
						
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Paga IVA?:</label>
							<select name="pagaIva" id="pagaIva" class="form-control" > 
							<?php echo $opcion_paga_iva;?>	
							</select> 	
						</div>
						
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Pago Proporcional al uso del Mes?:</label>
							<select name="proporcionalMes" id="proporcionalMes" class="form-control" > 
							<?php echo $opcion_proporcional_mes;?>	
							</select> 	
						</div>
						
						<div class="form-group">
                        <label ><span class="obligatorio">*</span> Selecionable?:</label>
							<select name="seleccionable" id="seleccionable" class="form-control" > 
							<?php echo $opcion_seleccionable;?>	
							</select> 	
						</div>
						
						<div class="form-group">
						<input type="hidden" name="id_monto_mayor" id="id_monto_mayor" value="<?php echo @$result->montoMayor;?>">
                        <label >Tiene Monto Mayor?:</label>
							<select name="montoMayor" id="montoMayor" class="form-control" onChange="validaMontoMayor(this);" > 
							<?php echo $opcion_monto_mayor;?>	
							</select> 	
						</div>
						
						<div class="form-group">
                        <label >Dias de Gracia Monto Mayor:</label>
                            <input type="number" class="form-control" name="diasGraciaMontoMayor" id="diasGraciaMontoMayor" placeholder="Dias de Gracia Monto Mayor" value="<?php echo @$result->diasGraciaMontoMayor;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"  >
                        </div>
						
						<div class="form-group">
						<input type="hidden" name="id_prod_monto_mayor" id="id_prod_monto_mayor" value="<?php echo @$result->idMontoMayor;?>">
                        <label >Productos Monto Mayor:</label>
							<?php echo $opcion_prod_monto_mayor;?>	
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
                    <a href="index.php?component=producto&view=producto_list">
                    <button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>
					
					<?php if($muestra_Guardar == "S"){ ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary" > Aceptar </button>
					<?php } ?>
                    </div>

                </div>

            </form>
<script>cargarSetting();</script>     