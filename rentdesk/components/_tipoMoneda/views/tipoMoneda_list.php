<script>loadTipoMoneda();</script>
<h2>Tipos de Moneda</h2>           
<a href='index.php?component=tipoMoneda&view=tipoMoneda'><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i> <span style="font-size:18px; padding-bottom:10px;">Agregar un Tipo de Moneda</span></a>

       <div class="herramientas">
		<button type="button" class="btn btn-default btn-sm recargar" onClick="document.location.reload();">
          <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
        </div>
        
<table id="tabla" class="display" cellspacing="0" width="100%">

        <thead>
            <tr>
                <th>Id.</th>
                <th>Descripción</th>
				<th>Pais</th>
                <th>Orden</th>
				<th>Activo</th>
                <th>Ver/Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
      
    </table>