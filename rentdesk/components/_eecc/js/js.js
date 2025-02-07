function enviarPago(cant_dec,sep_mil){
	
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);	
	var monto_minimo = desformatearNumero($("#monto_deuda").val(),cant_dec,sep_mil);
	var monto_pagado = desformatearNumero($("#monto_pagado").val(),cant_dec,sep_mil);
	$("#monto_cheque").val($("#monto_pagado").val());
	
	
	if(monto_pagado == 0){
		$.showAlert({title: "Atención", body: "El monto pagado debe ser mayor a 0"});
	}else{
		if(monto_pagado < monto_minimo){
			$.showAlert({title: "Atención", body: "El monto pagado debe ser mayor o igual al monto minimo"});
		}else{
		var formData = new FormData(document.getElementById("formulario"));

	   $.ajax({
					url: "components/eecc/models/ingresa_pago.php",
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
						window.history.back();
						return false;
					}else{
						$.showAlert({title: "Error", body: mensaje});
						return false;
					}	 
			});
		}
	}	
	
	
	
	
}//function enviar


//Desde acá código para Datatable listado
function loadEECC(token,nav){
	
$(document).ready(function() { 
  $('#tabla').DataTable( {
	   "order": [[ 1, "desc" ]],
        "processing": true,
        "serverSide": true,
		"pageLength": 12,
		"columnDefs": [ { orderable: false, targets: [3] } ],
        "ajax": {
			"url":"components/eecc/models/eecc_list_procesa.php?token_contrato="+token+'&nav='+nav,
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


function esCheque(valor,cant_decimales,separador_mil){

	if(valor=="4"){
		$(".cheques").css("display","block");
		$("#monto_pagado").val("");
		$("#monto_pagado").prop('disabled', true);
		AgregarCampos('',cant_decimales,separador_mil);
	}else{
		$(".cheques").html("");
		$(".cheques").css("display","none");
		$("#monto_pagado").val("");
		$("#monto_pagado").prop('disabled', false);
	}

}//esCheque(valor)



function addCalendario(item){
	$(document).ready(function() { 
	
			var d = new Date();
			var strDate =  + d.getDate() + "/" + (d.getMonth()+1) + "/" + d.getFullYear();
	
				$('#datetimepicker'+item).datetimepicker({
				format : "DD-MM-YYYY",
				defaultDate: moment(strDate,"DD-MM-YYYY")
			});
	
	})
	
}



var nextCheques = 0;

function AgregarCampos(agrega,cant_decimales,separador_mil){

$(".agrega"+agrega).css("display","none");

nextCheques++;

var eliminar = "";

if( parseInt(nextCheques) >= 2 )
eliminar = "<div style='width:100%; height:16px; border-top:1px solid #cccccc; font-size:12px;'><a href='javascript: QuitaCheque("+nextCheques+","+cant_decimales+", \""+separador_mil+"\");'><i class='fas fa-window-close' style='color:#ff6262;'></i>Borrar cheque</a></div>";

var nuevoBanco = "<select id='banco"+nextCheques+"' name='banco"+nextCheques+"' class='form-control'  required data-validation-required  >"+bancos+"</select>";

campos = '<div class="campos'+nextCheques+'"><div class="row">'+				
					eliminar+' <div class="col-md-3">'+
						'<div class="form-group">'+
                        '<label ><span class="obligatorio">*</span> Banco: </label>'+nuevoBanco+
                        '</div>'+
					'</div>'+
					'<div class="col-md-3">'+		
						'<div class="form-group">'+
                        '<label ><span class="obligatorio">*</span> N° de serie:</label>'+
                            '<input type="text" class="form-control" maxlength="250" name="serie'+nextCheques+'" id="serie'+nextCheques+'" value=""  required data-validation-required  >'+
                        '</div>'+
					'</div>'+
					'<div class="col-md-3">'+		
						'<div class="form-group">'+
                        '<label><span class="obligatorio">*</span> Fecha:</label>'+
							'<div class="input-group" id="datetimepicker'+nextCheques+'">'+
							  '<input type="text" class="form-control" maxlength="50" name="fecha'+nextCheques+'" id="fecha'+nextCheques+'" required data-validation-required  placeholder="dd-mm-yyyy" value="" onblur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">'+
							   '<span class="input-group-addon calendariodatepickter"><i class="fa fa-calendar" aria-hidden="true"></i></span>'+
							'</div>'+
						'</div>'+
					'</div>'+
					'<div class="col-md-3">'+		
						'<div class="form-group">'+
                        '<label ><span class="obligatorio">*</span> Monto:</label>'+
                            '<input type="text" class="form-control" maxlength="250" name="monto'+nextCheques+'" id="monto'+nextCheques+'" required data-validation-required  value="" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);numberFormat(this,\''+cant_decimales+'\',\''+separador_mil+'\');SumaMontosCheques(\''+cant_decimales+'\',\''+separador_mil+'\');"   >'+
                        '</div>'+
					'</div>'+
				'</div> </div><div class="agrega'+nextCheques+'" style=" height:16px; border-top:1px solid #cccccc;"><a href="#" onclick="AgregarCampos('+nextCheques+',\''+cant_decimales+'\',\''+separador_mil+'\');"><i class="fas fa-plus-square" style="color:#2a00ff;"></i> Agregar otro Cheque</a></div>';
			
			
	
				
				
$(".cheques").append(campos);

addCalendario(nextCheques);

$("#cantidadCheques").val(nextCheques);


}

function QuitaCheque(indice,cant_dec,sep_mil){
	
	$.showConfirm({
    title: "Confirme", body: "Está seguro que desea eliminar este cheque?", textTrue: "Si", textFalse: "No",
    onSubmit: function (result) {
        if (result) {
            $(".campos"+indice).remove();
			SumaMontosCheques(cant_dec,sep_mil);
        } else {
		
		}
    },
    onDispose: function () {
        console.log("The confirm dialog vanished")
    }
})
	
}//function

function SumaMontosCheques(cant_decimales,separador_mil){	
	var monto_pagado = 0;
	var monto_cheque = 0
	for (var i = 1; i <= nextCheques; i++) {
		monto_cheque = 0 ;
		if($("#monto"+i).val() === undefined || $("#monto"+i).val().trim() === "" ){
			monto_cheque = 0;
		}else{
			monto_cheque = desformatearNumero($("#monto"+i).val(),cant_decimales,separador_mil);
		}		
	   monto_pagado = monto_pagado + monto_cheque;
	}
	$("#monto_pagado").val(monto_pagado);	
	$("#monto_pagado").change(function() {
			numberFormat(this,cant_decimales,separador_mil);
	}).change();
}	
