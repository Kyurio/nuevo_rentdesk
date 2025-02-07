<script>loadVisita('<?php echo @$token_propiedad;?>','<?php echo @$pag_origen;?>');</script>    

<h2>Listado de Visitas</h2>
	<?php if($token_propiedad == ""){?>
		<a href="index.php?component=visita&amp;view=visita&amp;token=<?php echo @$token;?>&amp;token_propiedad=<?php echo @$token_propiedad;?>&amp;nav=<?php echo @$pag_origen;?>"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i> <span style="font-size:18px; padding-bottom:10px;">Agregar un Check-In</span></a>               
	<?php }else{?>
			<a href="index.php?component=visita&amp;view=visita&amp;token=<?php echo @$token;?>&amp;token_propiedad=<?php echo @$token_propiedad;?>&amp;nav=<?php echo @$pag_origen;?>"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i> <span style="font-size:18px; padding-bottom:10px;">Agregar una Visita</span></a>               
	<?php }?>
	<div class="herramientas">
	<button type="button" class="btn btn-default btn-sm recargar" onClick="document.location.reload();">
	  <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
	</button>
	</div>
        
<table id="tabla" class="display" cellspacing="0" width="100%">


        <thead>
            <tr>
                <th>Fecha</th>
				<th>Tipo</th>
				<th>Dirección</th>
				<th>Estado</th>
				<th>Informe</th>
  				<th>Ver</th>
                <th>Eliminar</th>
            </tr>
        </thead>
      
    </table>
	
	<?php if($token_propiedad != ""){?>
		<div class="col-lg-12 text-center">
				<a href="<?php echo $nav;?>">
				 <button type="button" class="btn btn-primary" >  &lt;&lt; volver </button></a>	
		</div>
	<?php }?>