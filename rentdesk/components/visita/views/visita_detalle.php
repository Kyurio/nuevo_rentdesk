 <script>
   $(function () {


$('[data-fancybox="gallery"]').fancybox({
	// Options will go here
});



});

<?php 
 

?>

</script>
   
 
<h2 class="h-linea"><a href="index.php?component=visita&amp;view=visita&token=<?php echo $token;?>&token_propiedad=<?php echo $token_propiedad;?>&nav=<?php echo @$_GET["nav"];?>" style="color:#83cbce;" >Visita</a>
 | <a href="index.php?component=visita&view=visita_detalle&token=<?php echo $token;?>&token_propiedad=<?php echo $token_propiedad;?>&nav=<?php echo @$_GET["nav"];?>"   >Detalle de la visita</a>
 </h2>
   <br>  <br>  
 
 
<div class="container" style="width:100%; margin-top:20px;">

<br> 

 <form name="formulario1" id="formulario1" method="post" action="javascript: enviarItem();" style="width:100%;">
<div class="row">

			<div class="col-sm-3 form-group">

					 <label><span class="obligatorio">*</span>Agregar Item:</label>
					 <?php echo $select_item;?>
					 </div>
			 
			
<input type="hidden" name="token" id="token" value="<?php echo @$token;?>">

			<div class="col-lg-3 form-group"> 
			<label >Sufijo:</label>
			<input type="text" class="form-control" name="sufijo" id="sufijo" placeholder="Ej: Principal, 1, de visitas" value="" />
			</div>
			
			<div class="col-lg-3 form-group"> 
			<button type="submit" class="btn btn-primary" style="margin-top:25px;"> Agregar </button>
			</div>
	
</div>
</form>	

<div class="linea-horizontal"></div>

			 
			 <?php echo $item_general;?>
			 
 

 
 

 
	
	


 
		<?php if($tipo_ingreso_ori == 'Checkout'){ ?>
			 
 		<div>
		<strong><i class='fas fa-circle' style='color:#8b8b8b;' ></i></strong> No se ha revisado el checkout.<br>
		<strong><i class='fas fa-circle' style='color:#00e710;' ></i></strong> Checkout revisado sin observaciones.<br>
		<strong><i class='fas fa-circle' style='color:#ff0000;' ></i></strong> Checkout revisado con observaciones.<br>
		</div>
			<div>
				<spam style='color:#00CD0E; font-weight:bold;'>* </spam> Respuesta Ingresada en Checkin
			</div>	
		<?php } ?>
	<br>  <br>

	
  

	 
	 
	 

	 
	 
	 
	 
	 
	 
</div> <!-- container -->
   
   
   
                

           
