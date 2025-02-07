function enviar(){
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	
	
var formData = new FormData(document.getElementById("formulario"));

   $.ajax({
                url: "components/perfil/models/insert_update.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	            processData: false
            })
             .done(function(res){
				
				var retorno = res.split(',xxx,');
		        var resultado = retorno[1];
				var mensaje = retorno[2];
				
				if (resultado == 'OK'){
					$.showAlert({title: "Atención", body: mensaje});
					document.location.reload(); 
					return false;
				}else{
					$.showAlert({title: "Error", body: mensaje});
					return false;
				}	 
		});
}//function enviar


 

function validaImagen(e){
	var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
	if ($.inArray($(e).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
		$("#errorarchivo").html("Error. El Archivo debe ser una imagen.");
		return false;
	}else{
		$("#errorarchivo").html("");
		return true;
		}
		
}
 

//****************************************************************************************

function enviarFoto(fotoActual){
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	
	
var formData = new FormData();
formData.append('fileUpload', $('input[type=file]')[0].files[0]);
formData.append('fotoActual',fotoActual);
 
if(  validaImagen( $("#fileUpload") )==true ) {

   $.ajax({
			url: "components/perfil/models/subir_foto.php",
			type: "post",
			dataType: "html",
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(res){
			var retorno = res.split(',xxx,');
			var resultado = retorno[1];
			var mensaje = retorno[2];
			
			if (resultado == 'OK'){
				$.showAlert({title: "Atención", body: mensaje});
				document.location.reload(); 
				return false;
			}else{
				$.showAlert({title: "Error", body: mensaje});
				return false;
			}	 
		  
		});
      
}//if(validaImagen( $("#imagen") )==true)

}//function enviar


