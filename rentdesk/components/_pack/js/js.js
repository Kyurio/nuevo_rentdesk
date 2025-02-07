function enviar(){
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	
	
var datos = $("#formulario3").serialize();

  $.ajax({
  type: 'POST',
  url: "components/pack/models/insert_update.php",
  data:  datos,
  success: function(resp){
		var retorno = resp.split(',xxx,');
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var token = retorno[3];
		
		if (resultado == 'OK'){
			$.showAlert({title: "Atención", body: mensaje});
			document.location.href="index.php?component=pack&view=pack&token="+token;
			return false;
		}else{
			$.showAlert({title: "Error", body: mensaje});
			return false;
		}	 
   }
   });  
} 

//Desde acá código para Datatable listado de packes
//*****************************************************************************************
function loadPack(){
	
$(document).ready(function() { 

  $('#tabla').DataTable( {
	   "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
		"pageLength": 10,
		"columnDefs": [ { orderable: false, targets: [3,4] } ],
        "ajax": {
			"url":"components/pack/models/pack_list_procesa.php",
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


$("<div id='divbotonbuscar' ><i id='buscar' class='fas fa-search'></i></div>").insertBefore('.dataTables_filter input');


//Para realizar la búsqueda al hacer click en el botón
$('#buscar').click(function(e){
	    var table = $('#tabla').DataTable();
	    table.search( $("div.dataTables_filter input").val()).draw();
		//mostrar u ocultar botón para resetear las búsquedas y orden
		
		
    });//$('#buscar').click(function(e){

}); //$(document).ready(function() 

} //function loadUsers()

//************************************************************************
function deletePack(token){
	$.showConfirm({title: "Por Favor Confirme.", body:"Realmente desea Eliminar El registro? No se puede deshacer.", textTrue: "Si", textFalse: "No",
	onSubmit: function (result) {
		if(result) {
				  $.ajax({
					  type: 'POST',
					  url: "components/pack/models/delete.php",
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


//************************************************************************
function activarProducto(token){
	var token_pack = $("#token").val();
	if(token_pack == ""){
		$.showAlert({title: "Atención", body: "Debe guardar el Pack para poder asociarle productos"});
		return;
	}	
	
	$.ajax({
		  type: 'POST',
		  url: "components/pack/models/activarProducto.php",
		  data:  "token="+token+"&token_pack="+token_pack,
		  success: function(res){
				var retorno = res.split(',xxx,');
				var resultado = retorno[1];
				var mensaje = retorno[2];
				var token = retorno[3];
						
					if (resultado == 'OK'){
						$("#icono_"+token).removeClass("fa-circle");
						$("#icono_"+token).removeClass("far");
						$("#icono_"+token).addClass("far");
						$("#icono_"+token).addClass("fa-check-circle");
						$("#link_"+token).attr('href','javascript: desactivarProducto("'+token+'","'+token_pack+'");');
						return false;
					}else{
						$.showAlert({title: "Error", body: mensaje});
						return false;
					}	 
		   }
	   });  
}   

//************************************************************************
function desactivarProducto(token){
	var token_pack = $("#token").val();
	
	$.ajax({
		  type: 'POST',
		  url: "components/pack/models/desactivarProducto.php",
		  data:  "token="+token+"&token_pack="+token_pack,
		  success: function(res){
				var retorno = res.split(',xxx,');
				var resultado = retorno[1];
				var mensaje = retorno[2];
				var token = retorno[3];
						
					if (resultado == 'OK'){
						$("#icono_"+token).removeClass("fa-check-circle");
						$("#icono_"+token).removeClass("far");
						$("#icono_"+token).addClass("far");
						$("#icono_"+token).addClass("fa-circle");
						$("#link_"+token).attr('href','javascript: activarProducto("'+token+'","'+token_pack+'");');
						return false;
					}else{
						$.showAlert({title: "Error", body: mensaje});
						return false;
					}	 
		   }
	   });  
} 