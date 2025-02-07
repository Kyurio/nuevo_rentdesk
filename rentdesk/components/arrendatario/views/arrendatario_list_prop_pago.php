<script>loadPropiedadesPago('<?php echo @$token;?>','<?php echo $pag_origen;?>');</script>
<h2>Propiedades Para Pago</h2>           
       <div class="herramientas">
       <button type="button" class="btn btn-info btn-sm" onClick="document.location.reload();">
          <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
        </div>
        
<table id="tabla" class="display" cellspacing="0" width="100%">

        <thead>
            <tr>
                <th>Código</th>
				<th>Tipo Propiedad</th>
				<th>Direción</th>
				<th>Número</th>
				<th>Depto</th>
				<th>Estado</th>
                <th>Ult. EECC</th>
				<th>Hist. EECC</th>
				<th>Pagar</th>
				<th>Hist. Pagos</th>
				<th>Contrato</th>
				<th>Ver</th>
            </tr>
        </thead>
      
    </table>
	<div class="col-lg-12 text-center">
			<a href="<?php echo $nav;?>">
             <button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>	
	</div>