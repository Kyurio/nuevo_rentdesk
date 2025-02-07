<script>loadUser();</script>
<h2>Usuarios del Sistema</h2>           
<a href='index.php?component=user&view=user'><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i> <span style="font-size:18px; padding-bottom:10px;">Agregar un nuevo Usuario</span></a>

       <div class="herramientas">
		<button type="button" class="btn btn-default btn-sm recargar" onClick="document.location.reload();">
          <span class="fas fa-search"></span> Recargar Datos
        </button>
        </div>
        
<table id="tabla" class="display" cellspacing="0" width="100%">


        <thead>
            <tr>
                <th>Nombre Usuario</th>
                <th>Email</th>
				<th>Rol de Usuario</th>
                <th>Ver/Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
      
    </table>