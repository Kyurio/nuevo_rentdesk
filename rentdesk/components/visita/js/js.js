function enviar(){
$("#id_tipo").val($("#tipo").val());
var token_propiedad = $("#token_propiedad").val();
var nav = $("#nav").val();

$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	
	
var formData = new FormData(document.getElementById("formulario5"));

  if(    validaArchivo( $("#imagen") )==true || $("#imagen").val()=="" )  {
   
   $.ajax({
                url: "components/visita/models/insert_update.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	            processData: false
            })
                .done(function(res){
				//alert(res);
				var retorno = res.split(',xxx');
		        var resultado = retorno[1];
				var mensaje = retorno[2];
				
				 if (resultado == 'ERROR'){
					$.showAlert({title: "Error", body: mensaje});
					return false; 
				 }else{ 	 
					$.showAlert({title: "Atención", body: "La visita se ha guardado."})
					document.location.href="index.php?component=visita&view=visita&token="+resultado+"&token_propiedad="+token_propiedad+"&nav="+nav;
				 }
                });
      
  }//if(validaImagen( $("#imagen") )==true)  {

}//function enviar 
//Datatable

function loadVisita(token,nav){
	
$(document).ready(function() { 

  $('#tabla').DataTable( {
	   "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
		"pageLength": 50,
		"columnDefs": [ { orderable: false, targets: [3,4] } ],
        "ajax": {
			"url":"components/visita/models/visita_list_procesa.php?token_propiedad="+token+'&nav='+nav,
		"type": "POST"},
		"language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "No encontrado",
            "info": "Mostrando página _PAGE_ de _PAGES_ (Total de registros: _MAX_)",
            "infoEmpty": "Sin resultados",
            "infoFiltered": " <strong>Total de registros filtrados: _TOTAL_ </strong>",
			"loadingRecords": "Cargando...",
			"search": "buscar: ",
            "processing":     "Procesando...",
			"paginate": {
        "first":      "Primero",
        "last":       "Último",
        "next":       "siguiente",
        "previous":   "anterior"
    },
        }
		
    } );
	
$("div.dataTables_filter input").unbind(); // se desactiva la busqueda al presionar una tecla


$("<div id='divbotonbuscar' ><span id='buscar' class='fas fa-search' ></span></div>").insertBefore('.dataTables_filter input');


//Para realizar la búsqueda al hacer click en el botón
$('#buscar').click(function(e){
	    var table = $('#tabla').DataTable();
	    table.search( $("div.dataTables_filter input").val()).draw();
		//mostrar u ocultar botón para resetear las búsquedas y orden
		
		
    });//$('#buscar').click(function(e){

}); //$(document).ready(function() 


} //function













$( document ).ready(function() {

$("#rut")
  .rut({formatOn: 'blur', validateOn: 'blur'})
  .on('rutInvalido', function(){ 
    $(this).parents(".control-group").addClass("errorClass");
	$(this).css("border-color","red");
	$("#errorrut").html("Rut inválido. Debe ingresar un Rut válido.");
	$( "#rut" ).addClass( "rutnovalido" );
	
  })
  .on('rutValido', function(){ 
    $(this).parents(".control-group").removeClass("errorClass")
	$(this).css("border-color","#ccc");
	$("#errorrut").html("");
	$( "#rut" ).removeClass( "rutnovalido" );
	
  });

 









$("#rut_contacto")
  .rut({formatOn: 'blur', validateOn: 'blur'})
  .on('rutInvalido', function(){
	
	if(document.getElementById("rut_contacto").value!=""){
    $(this).parents(".control-group").addClass("errorClass");
	$(this).css("border-color","red");
	$("#errorrutcontacto").html("Rut inválido. Debe ingresar un Rut válido.");
	$( "#rut_contacto" ).addClass( "rutnovalido" );
	
	}
	
  })
  .on('rutValido', function(){ 
    $(this).parents(".control-group").removeClass("errorClass")
	$(this).css("border-color","#ccc");
	$("#errorrutcontacto").html("");
	$( "#rut_contacto" ).removeClass( "rutnovalido" );
	
  });
	
	
	

});  //$( document ).ready(function()




function validaArchivo(e){
	
	if(e.val()=="")
	return true;
	
        var fileExtension = ['jpeg', 'jpg', 'png','pdf','doc','docx'];
        if ($.inArray($(e).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
			$.showAlert({title: "Atención", body: "El archivo Rut debe ser una imagen, word o pdf."});
			$(e).val("");
			return false;
        }else{
			return true;
			}
		
}


function enviarArchivo(formulario, archivo, titulo, token){
	
	
//$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	

var token = document.getElementById("token").value;
 
	
var formData = new FormData(document.getElementById(formulario));

  if(validaArchivo( $("#"+archivo) )==true )  {
	  
	  if(titulo!=""){
   
   $.ajax({
                url: "components/proyecto/models/subir_archivo.php?token="+token+"&archivo="+archivo+"&titulo="+titulo,
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	            processData: false
            })
                .done(function(res){
				//alert(res);
				var retorno = res.split(',xxx,');
		        var resultado = retorno[1];
				
				//BootstrapDialog.alert('La Empresa se ha actualizado.');
   			    //setTimeout(function(){ document.location.href="index.php?component=company&view=company"; }, 2500);
				BootstrapDialog.show({
            message: "El Archivo se ha subido.",
			type: BootstrapDialog.TYPE_PRIMARY,
			title: "Atención",
			buttons: [{
                label: 'Aceptar',
				cssClass: 'btn-primary',
             action: function(dialogItself){
                    dialogItself.close();
					document.location.href='index.php?component=proyecto&view=proyecto&token='+token+"&openar=1";
                }
           
            }]
        });
		

				  
				  
                });
				
	  }else{
		  BootstrapDialog.alert('Debe ingresar un título para el Archivo.');
	  }//if(titulo!="")
      
  }//if(validaImagen( $("#imagen") )==true)  {
	
}//function enviarArchivo()



function borrarArchivo(tokenArchivo, token){
	
	BootstrapDialog.confirm('Confirma que la eliminación del Archivo. No se puede deshacer.', function(result){
            if(result) {
               
			   
$.ajax({
  type: 'POST',
  url: "components/proyecto/models/borrar_archivo.php",
  data:  "token="+tokenArchivo,
  success: function(resp){
		var retorno = resp.split('xxx,');
		var resultado = retorno[1];
		//alert(resp); 
			//BootstrapDialog.alert('El archivo se ha eliminado.');
		//	document.location.href="index.php?component=proyecto&view=proyecto&token="+token+"&openar=4&#listadoArchivos";
			
						BootstrapDialog.show({
            message: "El Archivo se ha eliminado.",
			type: BootstrapDialog.TYPE_PRIMARY,
			title: "Atención",
			buttons: [{
                label: 'Aceptar',
				cssClass: 'btn-primary',
             action: function(dialogItself){
                    dialogItself.close();
					document.location.href="index.php?component=proyecto&view=proyecto&token="+token+"&openar=4";
                }
           
            }]
        });
		
		
			

   
                        }
    }); 
			   
			   
			   
			   
            }else {
               // alert('no.');
            }
        });
	
}//function borrarArchivo(token)



function rutexiste(rut, e){
	 
  $.ajax({
  type: 'POST',
  url: "components/empresa/models/rut_existe.php",
  data:  "rut="+rut,
  success: function(resp){
		var retorno = resp.split(',xxx,');
		var resultado = retorno[1];
		//alert(resp);
	
		if(resultado=="1"){
			BootstrapDialog.alert('El Rut ya existe como proyecto. Búsquelo en el listado de proyectos o ingrese otro Rut para este proyecto.', function(){
          document.getElementById(e).value="";
        });
		} 
		
		  
  
                        }//success
    });  
			   
			   
  
}



 function enviarItem(){
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	
	
var datos = $("#formulario1").serialize();

   
   $.ajax({
                url: "components/visita/models/insert_item.php",
                type: "post",
                dataType: "html",
                data: datos,
                cache: false
            })
                .done(function(res){
				//alert(res);
				var retorno = res.split(',xxx');
		        var resultado = retorno[1];
				 
				$.showAlert({title: "Atención", body: "El Item se ha agregado."})
			    document.location.reload();
    

				  
				  
                });
      
}//function enviar 

//*****************************************************************************************************************************
 function enviarSubItem(formulario, item, respuesta, token){
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

var valorItem 		= document.getElementById(item).value;
var valorRespuesta 	= document.getElementById(respuesta).value;
	
var datos = "&item="+valorItem+"&respuesta="+valorRespuesta+"&token="+token;

//alert("&item="+valorItem+"&respuesta="+valorRespuesta+"&token="+token);
   
   $.ajax({
                url: "components/visita/models/insert_subitem.php",
                type: "post",
                dataType: "html",
                data: datos,
                cache: false
            })
                .done(function(res){
				//alert(res);
				var retorno = res.split(',xxx');
		        var resultado = retorno[1];
				 
				$.showAlert({title: "Atención", body: "El Item se ha agregado."})
			    document.location.reload();
    

				  
				  
                });
      
}//function enviar 

//*****************************************************************************************************************

	function borrarSubItem(token){
		
		
		
		
		$.showConfirm({
    title: "Confirmación", body: "Está seguro de borrar el item?", textTrue: "Si", textFalse: "No",
    onSubmit: function (result) {
        if (result) {
            
			
			
$.ajax({
  type: 'POST',
  url: "components/visita/models/borrar_subitem.php",
  data:  "token="+token,
  success: function(resp){
		var retorno = resp.split(',xxx');
		var resultado = retorno[1];
		//alert(resp);
	$.showAlert({title: "Atención", body: "El Item se ha eliminado."})
document.location.reload();
  
                        }
    });  
			
			
			
			
        } else {
            
        }
    },
    onDispose: function () {
        console.log("The confirm dialog vanished")
    }
})



	}


//****************************************************************************************************************************

function validaFoto(e){
	
	if(e.val()=="")
	return true;
	
        var fileExtension = ['jpeg', 'jpg', 'png'];
        if ($.inArray($(e).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
			$.showAlert({title: "Atención", body: "El archivo debe ser una imagen."});
			$(e).val("");
			return false;
        }else{
			return true;
			}
		
}





function validaFotoMultiple(e, formulario) {

var filelength = $('#'+formulario+' input[type=file]').get(0).files.length;


var filesObj = $('#'+formulario+' input[type=file]').get(0).files;

  var filesArray = Object.keys(filesObj).map(function(key){
    return filesObj[key];
  });
  
  filesArray.forEach(function(file){
	  
	  var FileName = file.name;
 
      var FileExt = FileName.substr(FileName.lastIndexOf('.') + 1);
	  
	 // alert(FileExt);
	
    if (   (FileExt.toUpperCase() != "JPG") && (FileExt.toUpperCase() != "PNG")    ) {
      var error = "tipo de archivo : " + FileExt + "\n\n";
      error += "No es imagen .\n\n";
	 //alert("no");
      return false
    }

	 
  });

	//alert("si");
	return true;
 
}

//*****************************************************************************************************************************

function enviarFotoMultiple(formulario, archivo, token){
	
	
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	
	
var formData = new FormData(document.getElementById(formulario));

//alert(validaFotoMultiple( $("#"+archivo) , formulario ));

  if(    validaFotoMultiple( $("#"+archivo) , formulario )==true  )  {
   
   $.ajax({
                url: "components/visita/models/subir_foto_multiple.php?token="+token+"&archivo="+archivo,
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	            processData: false
            })
                .done(function(res){
				//alert(res);
				var retorno = res.split(',xxx');
		        var resultado = retorno[1];
				 
				//$.showAlert({title: "Atención", body: "La ."})
			    document.location.reload();

				  
                });
      
  }//if(validaImagen( $("#imagen") )==true)  {
}//function enviarArchivo()

//*****************************************************************************************************************************

function enviarFoto(formulario, archivo, token){
	
	
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	
	
var formData = new FormData(document.getElementById(formulario));

  if(    validaFoto( $("#"+archivo) )==true  )  {
   
   $.ajax({
                url: "components/visita/models/subir_foto.php?token="+token+"&archivo="+archivo,
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	            processData: false
            })
                .done(function(res){
				//alert(res);
				var retorno = res.split(',xxx');
		        var resultado = retorno[1];
				 
				//$.showAlert({title: "Atención", body: "La ."})
			    document.location.reload();

				  
                });
      
  }//if(validaImagen( $("#imagen") )==true)  {
}//function enviarArchivo()
	 
 
 
 //***************************************************************************************************

function borrarFoto(token){
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	

var datos = "token="+token;


$.showConfirm({
    title: "Confirmación", body: "Está seguro de eliminar la foto? No se puede deshacer.", textTrue: "Si", textFalse: "No",
    onSubmit: function (result) {
        if (result) {


   
   $.ajax({
                url: "components/visita/models/borrar_foto.php",
                type: "post",
                dataType: "html",
                data: datos,
                cache: false
            })
                .done(function(res){
				//alert(res);
				var retorno = res.split(',xxx');
		        var resultado = retorno[1];
				 
				//$.showAlert({title: "Atención", body: "El Item se ha agregado."})
			    document.location.reload();
    

				  
				  
                });
				
				
				
				
		} else {
            
        }
    },
    onDispose: function () {
        console.log("The confirm dialog vanished")
    }
})
      
}//function enviar 
 
  
//***********************************************************************************************************



function deleteVisita(token){
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	

var datos = "token="+token;


$.showConfirm({
    title: "Confirmación", body: "Está seguro que desea eliminar la Visita? No se puede deshacer.", textTrue: "Si", textFalse: "No",
    onSubmit: function (result) {
        if (result) {


   
   $.ajax({
                url: "components/visita/models/visita_delete.php",
                type: "post",
                dataType: "html",
                data: datos,
                cache: false
            })
                .done(function(res){
				//alert(res);
				var retorno = res.split(',xxx');
		        var resultado = retorno[1];
				 
				$.showAlert({title: "Atención", body: "La Visita se ha eliminado."})
			    document.location.reload();
    

				  
				  
                });
				
				
				
				
		} else {
            
        }
    },
    onDispose: function () {
        console.log("The confirm dialog vanished")
    }
})
      
}//function enviar 

function validaRutImagen(){

var e = document.getElementById("imagen");

if(e.value=="")
return true;

        var fileExtension = ['jpeg', 'jpg', 'png','pdf','doc','docx'];
        if ($.inArray($(e).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
$.showAlert({title: "Atención", body: "El archivo Rut debe ser una imagen, word o pdf."});
$(e).val("");
return false;
        }else{
return true;
}

}


function enviarCheckout(checkoutform, tipo, texto, token){
	
	var mensaje;
	
	if(tipo=="1")
	mensaje = "Confirma la recepción conforme.";

	if(tipo=="2"){
		mensaje = "Confirma la recepción con observaciones.";
		if(texto.trim() == ""){
			$.showAlert({title: "Atención", body: "Debe ingresar la observación"});
			return;
		}
	}

var datos = "token="+token+"&tipo="+tipo+"&texto="+texto;


$.showConfirm({
    title: "Confirmación", body: mensaje, textTrue: "Si", textFalse: "No",
    onSubmit: function (result) {
        if (result) {

			$.ajax({
                url: "components/visita/models/visita_aprueba_chekout.php",
                type: "post",
                dataType: "html",
                data: datos,
                cache: false
            })
                .done(function(res){
				//alert(res);
				var retorno = res.split(',xxx');
		        var resultado = retorno[1];
				 
				$.showAlert({title: "Atención", body: "Checkout Actualizado."})
			    document.location.reload();

                });
				
				
				
				
		} else {
            
        }
    },
    onDispose: function () {
        console.log("The confirm dialog vanished")
    }
})




 
	

 
}//enviarCheckout




//******************************************************************************************************


function imprimirVisita(url,token,aEwqjgt12a4rtFdQ1,tipoExport,codReferencia){

window.location.href = url+"?aEwqjgt12a4rtFdQ1="+aEwqjgt12a4rtFdQ1+"&token="+token+"&tipoExport="+tipoExport+"&codReferencia="+codReferencia;  

}  

