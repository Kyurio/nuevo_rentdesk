<script>
    loadRoles();
</script>
<h2>Roles del Sistema</h2>
<a href="index.php?component=rol&amp;view=rol"><i class="fa fa-fw fa-users-cog  icon-add" aria-hidden="true"></i> <span style="font-size:14px; padding-bottom:8px;">Agregar un nuevo Rol</span></a>


<div class="herramientas">
    <button type="button" class="btn btn-info btn-sm" onClick="document.location.reload();">
        <i class="fas fa-search"></i> Recargar Datos
    </button>
</div>

<table id="tabla" class="display" cellspacing="0" width="100%">


    <thead>
        <tr>
            <th>Id.</th>
            <th>Nombre</th>
            <th>Editar</th>
            <th>Eliminar</th>
        </tr>
    </thead>

</table>