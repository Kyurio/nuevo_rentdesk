            <h2>Detalle Pago</h2>       
            <form name="formulario" id="formulario" method="post" action="javascript: enviar();" enctype="multipart/form-data">

                <div class="row">
					<div class="col-md-3">
                       <div class="form-group">
                        <label >Fecha Pago:</label>
                            <input type="text" class="form-control" maxlength="250" name="fecha_pago" id="fecha_pago" readonly value="<?php echo fecha_postgre_a_normal(@$result->fecha_pago);?>"  >
                        </div>
					 </div>	
					 
					 <div class="col-md-3">	
						<div class="form-group">
							<label >Monto Pagado:</label>
                            <input type="text" class="form-control" maxlength="250" name="monto_pago" id="monto_pago" readonly value="<?php echo  formatea_number(@$result->monto_pago,$_SESSION["cant_decimales"],$_SESSION["separador_mil"]);?>"  >
                        </div>
					</div>
					
					<div class="col-md-3">	
						<div class="form-group">
							<label >Medio Pago:</label>
                            <input type="text" class="form-control" maxlength="250" name="medio_pago" id="medio_pago" readonly value="<?php echo @$result->medio_pago;?>"  >
                        </div>
					</div>
					<div class="col-md-3">	
						<div class="form-group">
							<label >Cód. Autorización:</label>
                            <input type="text" class="form-control" maxlength="250" name="cod_autorizacion" id="cod_autorizacion" readonly value="<?php echo @$result->cod_autorizacion;?>"  >
                        </div>
					</div>
					<input type="hidden" name="token" id="token" value="<?php echo @$result->token;?>">
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
