<script>loadBancos();</script>
<h2>Bancos</h2>           
<a href='index.php?component=banco&view=banco'><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i> <span style="font-size:18px; padding-bottom:10px;">Agregar un Banco</span></a>

       <div class="herramientas">
       <button type="button" class="btn btn-info btn-sm" onClick="document.location.reload();">
          <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
        </div>
        
<table id="tabla" class="display" cellspacing="0" width="100%">

        <thead>
            <tr>
                <th>Id. Banco</th>
                <th>Descripción</th>
				<th>Código Interno</th>
				<th>Código SBIF</th>
                <th>Orden</th>
				<th>Activo</th>
                <th>Ver/Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
      
    </table>