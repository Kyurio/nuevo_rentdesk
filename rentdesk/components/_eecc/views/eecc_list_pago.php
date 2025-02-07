<script>loadListPago('<?php echo @$token_contrato;?>','<?php echo @$pag_origen;?>');</script>
<h2>Historial Pagos</h2>           
       <div class="herramientas">
       <button type="button" class="btn btn-default btn-sm recargar" onClick="document.location.reload();">
          <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
        </div>
        
<table id="tabla" class="display" cellspacing="0" width="100%">

        <thead>
            <tr>
                <th>Fecha</th>
                <th>Monto</th>
				<th>Medio Pago</th>
				<th>Liquidado</th>
                <th>Ver</th>
            </tr>
        </thead>
      
    </table>	

	<div class="col-lg-12 text-center">
			<a href="<?php echo $nav;?>">
             <button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>	
	</div>