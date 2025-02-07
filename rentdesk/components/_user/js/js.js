function enviar(){
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	
	
var formData = new FormData(document.getElementById("formulario"));

 if( valida_mail(document.getElementById("email")) ){
   
   $.ajax({
                url: "components/user/models/insert_update.php",
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
					var token = retorno[3];
							
						if (resultado == 'OK'){
							$.showAlert({title: "Atención", body: mensaje});
							document.location.href="index.php?component=user&view=user&token="+token;
							return false;
						}else{
							$.showAlert({title: "Error", body: mensaje});
							return false;
						}	
                });
      
 }else{
	 $.showAlert({title: "Error", body: "Debe ingresar un email corecto."});
	 $("#email").focus();
	 return false;
 }// if( valida_mail(e) )

}//function enviar


//Desde acá código para Datatable listado de usuarios
//*****************************************************************************************
function loadUser(){
	
$(document).ready(function() { 

  $('#tabla').DataTable( {
	   "order": [[ 0, "asc" ]],
        "processing": true,
        "serverSide": true,
		"pageLength": 10,
		"columnDefs": [ { orderable: false, targets: [3,4] } ],
        "ajax": {
			"url":"components/user/models/user_list_procesa.php",
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


$("<div id='divbotonbuscar' ><span id='buscar' class='glyphicon glyphicon-search' ></span></div>").insertBefore('.dataTables_filter input');


//Para realizar la búsqueda al hacer click en el botón
$('#buscar').click(function(e){
	    var table = $('#tabla').DataTable();
	    table.search( $("div.dataTables_filter input").val()).draw();
		//mostrar u ocultar botón para resetear las búsquedas y orden
		
		
    });//$('#buscar').click(function(e){

}); //$(document).ready(function() 

} //function loadUsers()

//************************************************************************
function deleteUser(token){
	$.showConfirm({title: "Por Favor Confirme.", body:"Realmente desea Eliminar El registro? No se puede deshacer.", textTrue: "Si", textFalse: "No",
	onSubmit: function (result) {
		if(result) {
				  $.ajax({
					  type: 'POST',
					  url: "components/user/models/delete.php",
					  data:  "token="+token,
					  success: function(res){
							var retorno = res.split(',xxx,');
							var resultado = retorno[1];
							var mensaje = retorno[2];
							var token = retorno[3];
									
								if (resultado == 'OK'){
									$.showAlert({title: "Atención", body: mensaje});
									document.location.reload(); 
									return false;
								}else{
									$.showAlert({title: "Error", body: mensaje});
									return false;
								}	 
					   }
				   });  
            }else {
				//nada
            }
		},onDispose: function () {
        //nada
		}
	});
	
}

//***********************************************************************
$(document).ready(function() { 

$('#clave').on('blur', function(){
    if(this.value.length < 6){ // checks the password value length
	  $.showAlert({title: "Error", body: "La contraseña debe tener al menos 6 caracteres. Reingrésela."});
	   this.value = "";
       $(this).focus(); // focuses the current field.
	    
       return false; // stops the execution.
    }
	 
});

});


function agregarEmpresa(){
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	

var empresa 	= document.getElementById("empresa").value;
var sucursal 	= document.getElementById("sucursal").value;
var token 		= document.getElementById("token").value;

var datos="empresa="+empresa+"&sucursal="+sucursal+"&token="+token;

	if( empresa!="" ){
   
   $.ajax({
                url: "components/user/models/agrega_empresa.php",
                type: "POST",
                data: datos
            })
                .done(function(res){
					var retorno = res.split(',xxx,');
					var resultado = retorno[1];
					$.showAlert({title: "Atención", body: "La empresa se ha agregado"});		
					document.location.reload();
						
                });
      
 }else{
	 $.showAlert({title: "Error", body: "Debe seleccionar la Empresa y Sucursal."});
	 return;
 }// if( empresa!="" && sucursal!="" )
	
	
}//function agregarEmpresa()


//***********************************************************************************************************
//***********************************************************************************************************


$(document).ready(function() {
	
  try {
  var empresa = document.getElementById("empresa").value;


	  $.ajax({
	  type: 'POST',
	  url: "components/user/models/setea_sucursal.php",
	  data:  "empresa="+empresa,
	  success: function(resp){
			var retorno = resp.split('xxx,');
			var resultado1 = retorno[1];

					try {
					   $("#divsucursal").html(resultado1); 
					  
					}
					catch(err) {
						 
					}

	   }
	   }); 
	}
	catch(err) {
		 
	}

 } );
 
 //********************************************************************************
 
function seteaSucursal(empresa, sucursal){

$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);		
	
$.ajax({
type: 'POST',
url: "components/user/models/setea_sucursal.php",
data:  "empresa="+empresa+"&sucursal="+sucursal,
  success: function(resp){
		var retorno = resp.split(',xxx,');
		var resultado = retorno[1];
		console.log(retorno);
		
			try {
			   $("#divsucursal").html(resultado);  
			   
			}
			catch(err) {
				 
			}
		
	
   }
   }); 
	

}//function seteaSucursal
	

//************************************************************************
function deletePermisosUser(token_empresa,token_sucursal,token){
	$.showConfirm({title: "Por Favor Confirme.", body:"Realmente desea Eliminar El registro? No se puede deshacer.", textTrue: "Si", textFalse: "No",
	onSubmit: function (result) {
		if(result) {
				$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	
				  $.ajax({
					  type: 'POST',
					  url: "components/user/models/delete_permiso.php",
					  data:  "token_empresa="+token_empresa+"&token_sucursal="+token_sucursal+"&token="+token,
					  success: function(res){
							var retorno = res.split(',xxx,');
							var resultado = retorno[1];
							var mensaje = retorno[2];
							var token = retorno[3];
									
								if (resultado == 'OK'){
									$.showAlert({title: "Atención", body: mensaje});
									document.location.reload(); 
									return false;
								}else{
									$.showAlert({title: "Error", body: mensaje});
									return false;
								}	 
					   }
				   });  
            }else {
				//nada
            }
		},onDispose: function () {
        //nada
		}
	});
}