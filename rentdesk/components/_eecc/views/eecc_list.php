<script>loadEECC('<?php echo @$token_contrato;?>','<?php echo @$pag_origen;?>');</script>
<h2>Historial Estados de Cuenta</h2>           
       <div class="herramientas">
       <button type="button" class="btn btn-default btn-sm recargar" onClick="document.location.reload();">
          <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
        </div>
        
<table id="tabla" class="display" cellspacing="0" width="100%">

        <thead>
            <tr>
                <th>Periodo</th>
                <th>Fecha Vencimiento</th>
				<th>Pagado</th>
                <th>Ver/Editar</th>
            </tr>
        </thead>
      
    </table>	

	<div class="col-lg-12 text-center">
			<a href="<?php echo $nav;?>">
             <button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>	
	</div>