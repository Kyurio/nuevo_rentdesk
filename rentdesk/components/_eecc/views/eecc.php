            <h2>Estado de Cuenta</h2>       
            <form name="formulario" id="formulario" method="post" action="javascript: enviar();" enctype="multipart/form-data">

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
					<div class="col-md-3">	
						<div class="form-group">
                        <label >Canon Arriendo:</label>
                            <input type="text" class="form-control" maxlength="250" name="rol" id="rol" readonly value="<?php echo  formatea_number(@$result->precio,$_SESSION["cant_decimales"],$_SESSION["separador_mil"]);?>"  >
                        </div>
					</div>	
					<div class="col-md-3">		
						<div class="form-group">
                        <label >Moneda:</label>
                            <input type="text" class="form-control" maxlength="250" name="tipo_moneda" id="tipo_moneda" readonly value="<?php echo @$result->tipo_moneda;?>"  >
                        </div>
					</div>	
					<div class="col-md-3">	
						<div class="form-group">
                        <label >Periodo:</label>
                            <input type="text" class="form-control" maxlength="250" name="periodo" id="periodo" readonly value="<?php echo @$result->periodo;?>"  >
                        </div>
					 </div>
					 <div class="col-md-3">
                       <div class="form-group">
                        <label >Fecha Vencimiento:</label>
                            <input type="text" class="form-control" maxlength="250" name="fecha_vencimiento" id="fecha_vencimiento" readonly value="<?php echo fecha_postgre_a_normal(@$result->fecha_vencimiento);?>"  >
                        </div>
					 </div>	
						<input type="hidden" name="token" id="token" value="<?php echo @$result->token;?>">
				</div>
				
				<div class="row">
					<div class="col-md-3">	
						<div class="form-group">
							<label >Monto Total:</label>
                            <input type="text" class="form-control" maxlength="250" name="monto_total" id="monto_total" readonly value="<?php echo formatea_number($total,$_SESSION["cant_decimales"],$_SESSION["separador_mil"]);?>"  >
                        </div>
					</div>
				</div>
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
						
						

                <div class="col-lg-12 text-center">
                    <a href="<?php echo $nav;?>">
                    <button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>
                      
                </div>
            </form>
