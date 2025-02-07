function enviar(){
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	
	
var datos = $("#formulario3").serialize();

  $.ajax({
  type: 'POST',
  url: "components/rol/models/insert_update.php",
  data:  datos,
  success: function(resp){
		var retorno = resp.split(',xxx,');
		var resultado = retorno[1];
		var mensaje = retorno[2];
		var token = retorno[3];
		
		if (resultado == 'OK'){
			$.showAlert({title: "Atención", body: mensaje});
			document.location.href="index.php?component=rol&view=rol&token="+token;
			return false;
		}else{
			$.showAlert({title: "Error", body: mensaje});
			return false;
		}	 
   }
   });  
} 

//Desde acá código para Datatable listado de roles
//*****************************************************************************************
function loadRoles(){
	
$(document).ready(function() { 

  $('#tabla').DataTable( {
	   "order": [[ 0, "asc" ]],
        "processing": true,
        "serverSide": true,
		"columnDefs": [ { orderable: false, targets: [2,3] } ],
        "ajax": {
			"url":"components/rol/models/rol_list_procesa.php",
		"type": "POST"},
		"language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "No encontrado",
            "info": "Mostrando página _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total de registros)",
			"loadingRecords": "Cargando...",
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
function deleteRol(token){
	$.showConfirm({title: "Por Favor Confirme.", body:"Realmente desea Eliminar El registro? No se puede deshacer.", textTrue: "Si", textFalse: "No",
	onSubmit: function (result) {
		if(result) {
				  $.ajax({
					  type: 'POST',
					  url: "components/rol/models/delete.php",
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