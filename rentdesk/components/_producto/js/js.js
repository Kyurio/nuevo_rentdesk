function enviar(){
	
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	

	$("#id_tipo_monto").val($("#tipo_monto").val());
	$("#id_tipo_moneda").val($("#tipo_moneda").val());
	$("#id_tipo_responsable").val($("#tipo_responsable").val());
	$("#id_prod_monto_mayor").val($("#prod_monto_mayor").val());
	$("#id_monto_mayor").val($("#montoMayor").val());
	
var formData = new FormData(document.getElementById("formulario"));

   $.ajax({
                url: "components/producto/models/insert_update.php",
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
					document.location.href="index.php?component=producto&view=producto&token="+token;
					return false;
				}else{
					$.showAlert({title: "Error", body: mensaje});
					return false;
				}	 
		});
}//function enviar


//Desde acá código para Datatable listado
//*****************************************************************************************
function loadProductos(){
	
$(document).ready(function() { 

  $('#tabla').DataTable( {
	   "order": [[ 0, "asc" ]],
        "processing": true,
        "serverSide": true,
		"pageLength": 10,
		"columnDefs": [ { orderable: false, targets: [6,7] } ],
        "ajax": {
			"url":"components/producto/models/producto_list_procesa.php",
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
function deleteProducto(token){
	$.showConfirm({title: "Por Favor Confirme.", body:"Realmente desea Eliminar El registro? No se puede deshacer.", textTrue: "Si", textFalse: "No",
	onSubmit: function (result) {
		if(result) {
				  $.ajax({
					  type: 'POST',
					  url: "components/producto/models/delete.php",
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
function cambiaProducto(objeto){
	
	/*Marca el tipo de responsable Predefinido*/
	var resp_pre = $('#tipo_producto').find(':selected').data('id_tipo_responsable_pre');
	
	$("#tipo_responsable").val("");
	$("#tipo_responsable").prop('disabled', false);
	if(resp_pre != ""){
		$("#tipo_responsable").val(resp_pre);
		$("#tipo_responsable").prop('disabled', true);
	}	
	
	/*Deshabilita campos para Monto Mayor*/
	$("#diasGraciaMontoMayor").val("");
	$("#diasGraciaMontoMayor").prop('disabled', true);
	$("#prod_monto_mayor").val("");
	$("#prod_monto_mayor").prop('disabled', true);

	
	/*Activa Campos segun tipo de producto*/
	var valor = $('#tipo_producto').val();
	console.log(valor);
	switch (valor) {
		  case "":
			$("#tipo_monto").val("");
			$("#tipo_monto").prop('disabled', false);
			$("#tipo_moneda").val("");
			$("#tipo_moneda").prop('disabled', false);
			$("#montoMayor").val("N");
	        $("#montoMayor").prop('disabled', true);
			break;
		  case "1":
			$("#tipo_monto").val("1");
			$("#tipo_monto").prop('disabled', true);
			$("#tipo_moneda").val("");
			$("#tipo_moneda").prop('disabled', false);
			$("#montoMayor").prop('disabled', false);
			$("#editable").prop('disabled', false);
			break;
		  case "2":
			$("#tipo_monto").prop('disabled', false);
			$("#montoMayor").val("N");
	        $("#montoMayor").prop('disabled', true);
			$("#editable").prop('disabled', false);
			break;
		  case "3":
			$("#tipo_monto").val("1");
			$("#tipo_monto").prop('disabled', true);
			$("#tipo_moneda").val("");
			$("#tipo_moneda").prop('disabled', false);
			$("#montoMayor").val("N");
	        $("#montoMayor").prop('disabled', true);
			$("#editable").prop('disabled', false);
			break;
		  case "4":
		    $("#tipo_monto").val("");
			$("#tipo_monto").prop('disabled', false);
			$("#montoMayor").val("N");
	        $("#montoMayor").prop('disabled', true);
			$("#editable").val("N");
			$("#editable").prop('disabled', true);
			$("#valor").val("");
	        $("#valor").prop('disabled', false);
			$("#seleccionable").val("N");
			$("#seleccionable").prop('disabled', true);
			break;
		  case "5":
			$("#montoMayor").val("N");
	        $("#montoMayor").prop('disabled', true);
			$("#editable").prop('disabled', false);
			break;
	}

	return false;
}	

//************************************************************************
function cambiaProductoIni(){
	
	/*Marca el tipo de responsable Predefinido*/
	var resp_pre = $('#tipo_producto').find(':selected').data('id_tipo_responsable_pre');
	
	$("#tipo_responsable").prop('disabled', false);
	if(resp_pre != ""){
		$("#tipo_responsable").val(resp_pre);
		$("#tipo_responsable").prop('disabled', true);
	}	

	/*Activa Campos segun tipo de producto*/
	var valor = $('#tipo_producto').val();
	switch (valor) {
		  case "":
			$("#tipo_monto").val("");
			$("#tipo_monto").prop('disabled', false);
			$("#tipo_moneda").val("");
			$("#tipo_moneda").prop('disabled', false);
			$("#montoMayor").val("N");
	        $("#montoMayor").prop('disabled', true);
			break;
		  case "1":
			$("#tipo_monto").val("1");
			$("#tipo_monto").prop('disabled', true);
			$("#tipo_moneda").prop('disabled', false);
			$("#montoMayor").prop('disabled', false);
			break;
		  case "2":
			$("#tipo_monto").prop('disabled', false);
			$("#montoMayor").val("N");
	        $("#montoMayor").prop('disabled', true);
			break;
		  case "3":
			$("#tipo_monto").val("1");
			$("#tipo_monto").prop('disabled', true);
			$("#tipo_moneda").prop('disabled', false);
			$("#montoMayor").val("N");
	        $("#montoMayor").prop('disabled', true);
			break;
		  case "4":
			$("#tipo_monto").prop('disabled', false);
			$("#montoMayor").val("N");
	        $("#montoMayor").prop('disabled', true);
			$("#editable").val("N");
			$("#editable").prop('disabled', true);
	        $("#valor").prop('disabled', false);
			$("#seleccionable").val("N");
			$("#seleccionable").prop('disabled', true);
			break;
		  case "5":
			$("#montoMayor").val("N");
	        $("#montoMayor").prop('disabled', true);
			break;
	}
	return false;
}	

//************************************************************************
function cambiaTipoMonto(objeto){
	
	/*Marca el tipo de responsable Predefinido*/
	var tipo_monto = $('#tipo_monto').val();
	
	if(tipo_monto == "2"){
		$("#tipo_moneda").val("");
		$("#tipo_moneda").prop('disabled', true);
	}else{
		$("#tipo_moneda").prop('disabled', false);
	}	
	return false;
}	

//************************************************************************
function validaEditable(valor){
	var valor = $('#editable').val();
	switch (valor) {
	  case "S":
	    $("#valor").val("");
		$("#valor").prop('disabled', true);
		$('#valor').removeAttr("required");
		break;
	  case "N":
		 $("#valor").prop("required", true);
		 $('#valor').removeAttr("disabled");
		break;
	}
	return false;
}	

//************************************************************************
function validaMontoMayor(valor){
	var valor = $('#montoMayor').val();
	switch (valor) {
	  case "S":
		$("#diasGraciaMontoMayor").prop('disabled', false);
		$("#prod_monto_mayor").prop('disabled', false);
		break;
	  case "N":
		$("#diasGraciaMontoMayor").val("");
		$("#diasGraciaMontoMayor").prop('disabled', true);
		$("#prod_monto_mayor").val("");
		$("#prod_monto_mayor").prop('disabled', true);
		break;
	}
	return false;
}	

function cargarSetting(){
	cambiaProductoIni();
	validaEditable($("#editable"));
	validaMontoMayor($("#prod_monto_mayor"));
	cambiaTipoMonto($("#tipo_monto"));
}
  