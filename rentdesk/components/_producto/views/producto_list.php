<script>loadProductos();</script>
<h2>Productos</h2>           
<a href='index.php?component=producto&view=producto'><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i> <span style="font-size:18px; padding-bottom:10px;">Agregar un Producto</span></a>

       <div class="herramientas">
		<button type="button" class="btn btn-info btn-sm" onClick="document.location.reload();">
          <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
        </div>
        
<table id="tabla" class="display" cellspacing="0" width="100%">

        <thead>
            <tr>
                <th>Id.</th>
                <th>Descripción</th>
				<th>Tipo</th>
				<th>Moneda</th>
				<th>Responsable</th>
				<th>Activo</th>
                <th>Ver/Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
      
    </table>