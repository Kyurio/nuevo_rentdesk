function enviar(){
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	
	
var formData = new FormData(document.getElementById("formulario"));

   $.ajax({
                url: "components/tipoDocumento/models/insert_update.php",
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
					document.location.href="index.php?component=tipoDocumento&view=tipoDocumento&token="+token;
					return false;
				}else{
					$.showAlert({title: "Error", body: mensaje});
					return false;
				}	 
		});
}//function enviar


//Desde acá código para Datatable listado
//*****************************************************************************************
function loadTipoDocumento(){
	
$(document).ready(function() { 

  $('#tabla').DataTable( {
	   "order": [[ 2, "asc" ]],
        "processing": true,
        "serverSide": true,
		"pageLength": 10,
		"columnDefs": [ { orderable: false, targets: [4,5] } ],
        "ajax": {
			"url":"components/tipoDocumento/models/tipoDocumento_list_procesa.php",
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
function deleteTipo_documento(token){
	$.showConfirm({title: "Por Favor Confirme.", body:"Realmente desea Eliminar El registro? No se puede deshacer.", textTrue: "Si", textFalse: "No",
	onSubmit: function (result) {
		if(result) {
				  $.ajax({
					  type: 'POST',
					  url: "components/tipoDocumento/models/delete.php",
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
