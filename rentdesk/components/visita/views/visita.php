 <script>
   $(function () {
              $('#datetimepicker1').datetimepicker({
	format : "DD-MM-YYYY",
    defaultDate: moment("<?php echo $fecha;?>","DD-MM-YYYY")
});





});

<?php 
 

?>

</script>
   
   
   <h2 class="h-linea"><a href="index.php?component=visita&amp;view=visita&token=<?php echo $token;?>&token_propiedad=<?php echo @$token_propiedad;?>&nav=<?php echo @$_GET["nav"];?>">Visita</a>
   <?php echo $menu_detalle;?>
   </h2>
   <br> 
 
<div class="container" style="width:100%; margin-top:20px;">
 <form name="formulario5" id="formulario5" method="post" action="javascript: enviar();">

    <div class="row">
        <div class="col-lg-6 col-md-6 form-group"> 
			<input type="hidden" name="id_tipo" id="id_tipo" value="<?php echo @$result->tipo;?>">
			<label ><span class="obligatorio">*</span>Tipo Visita:</label>
			<?php echo $select_tipo;?>
		</div>
	
		<div class="col-lg-3 col-md-3 form-group">  
			<label ><span class="obligatorio">*</span>Fecha:</label>
			<div class="input-group" id="datetimepicker1">
				<input type="text" class="form-control" name="fecha" id="fecha" required data-validation-required  placeholder="Fecha" value="<?php echo @$fecha;?>" />
				<span class="input-group-addon calendariodatepickter"><i class="fa fa-calendar" aria-hidden="true"></i></span>
				</div>
		</div>	
		<?php if(!$token==""){ ?>
		<div class="col-md-3">
			<div class="form-group">
			<label ><span class="obligatorio">*</span> Estado:</label>
				<?php echo $opcion_estado_visita;?>	
			</div>
		</div>	
		<?php } ?>
    </div>
	
	
    <div class="row">


		    <div class="col-lg-6 form-group"> 
			<label ><span class="obligatorio">*</span>Dirección:</label>
			<input type="text" class="form-control" name="direccion" id="direccion" placeholder="Dirección" required data-validation-required  value="<?php echo @$direccion;?>" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" />
			</div>
			
			<div class="col-lg-6 form-group"> 
			<label ><span class="obligatorio">*</span>Administradora:</label>
			<input type="text" class="form-control" name="administradora" id="administradora" placeholder="Administradora" required data-validation-required  value="<?php echo @$administradora;?>" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" />
			</div>
		
		
		
    </div>


    <div class="row">
	
			<div class="col-lg-6 form-group"> 
			<label ><span class="obligatorio">*</span>Correo Solicitante:</label>
			<input type="text" class="form-control" name="correosolicitante" id="correosolicitante" placeholder="Correo Solicitante" required data-validation-required  value="<?php echo @$correo_solicitante;?>"  onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);"/>
			</div>
			
			<div class="col-lg-6 form-group"> 
			<label ><span class="obligatorio">*</span>Correo Arrendatario:</label>
			<input type="text" class="form-control" name="correoarrendatario" id="correoarrendatario" placeholder="Correo Arrendatario" required data-validation-required  value="<?php echo @$correo_arrendatario;?>" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" />
			</div>
        
    </div>
	
	
    <div class="row">
       
	   <div class="col-lg-6 form-group"> 
			<label ><span class="obligatorio">*</span>Arrendatario Recibe:</label>
			<input type="text" class="form-control" name="arrendatariorecibe" id="arrendatariorecibe" placeholder="Arrendatario Recibe" required data-validation-required  value="<?php echo @$arrendatario_recibe;?>" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" />
			</div>
	   
	   	<div class="col-lg-6 form-group">
     <label><span class="obligatorio">*</span>Rut:</label>
	 <input type="text" class="form-control rutnovalido" name="rut" id="rut" placeholder="Rut" required="" data-validation-required="" value="<?php echo @$rut;?>" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" >
	 <div id="errorrut"></div>
	 </div>
	 
    </div>
	
	
	<div class="row">
	

	 
	       <div class="col-lg-6 form-group">  
		 <label >Email contacto:</label>
                            <input type="text" class="form-control" name="correo" id="correo" placeholder="Correo" value="<?php echo @$result->correo;?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this); "  >
                            <div id="errorrutcontacto"></div>
                       </div>
	 
		<div class="col-lg-6 form-group"> 
			<label ><span class="obligatorio">*</span>Inspector:</label>
			<input type="text" class="form-control" name="inspector" id="inspector" placeholder="Inspector" required data-validation-required  value="<?php echo @$inspector;?>" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" />
			</div>
	 
	 
    </div>
	
	
	<div class="row">
   
					   
        <div class="col-lg-12 form-group">  
			<label>Observaciones:</label>
			<textarea name="observaciones" class="form-control" id="observaciones" placeholder="Observaciones para la visita" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);" onkeydown="limita(this,255);"><?php echo @$result->observaciones;?></textarea>
		</div>
    </div>
	
 
	
 
	<div class="row">
<div class="col-sm-3 form-group">

<label>Foto Rut:</label>

<input type="file" name="imagen" id="imagen" <?php echo @$imagen_requerida;?> class="form-control" onChange="validaRutImagen();" style="padding: 4px; " />
<button id="upload" class="btn btn-info btn-xs" >Seleccionar Archivo</button></div>
<div id="errorarchivo" style="min-height:5px;"></div>
 <?php echo @$imagen_rut;?>
 
</div>
</div>


	
		
<div class="row">	
	<div class="col-lg-12 text-center">
                    <br><input type="hidden" name="token" id="token" value="<?php echo @$token;?>">
					<input type="hidden" name="token_propiedad" id="token_propiedad" value="<?php echo @$token_propiedad;?>">
					<input type="hidden" name="nav" id="nav" value="<?php echo @$_GET["nav"];?>">
                         <a href="<?php echo $nav;?>">
                         <button type="button" class="btn btn-primary"> &lt;&lt; volver </button></a>
						 
						  &nbsp; &nbsp; 
					
					
                        <button type="submit" class="btn btn-primary"> Guardar </button>
						<br>  
                         
             
    </div> </div>
	 </form>
<br> 





	
	<br>  <br>
	
	
	
  

	 
	 
	 

	 
	 
	 
	 
	 
	 
</div> <!-- container -->
   
   
   
                

           
