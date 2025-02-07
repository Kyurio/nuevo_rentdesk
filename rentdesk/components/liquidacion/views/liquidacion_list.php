<script>
    loadLiquidacion('<?php echo @$token_contrato; ?>', '<?php echo @$token; ?>', '<?php echo @$pag_origen; ?>');
</script>
<h2>Historial Liquidaciones</h2>
<div class="herramientas">
    <button type="button" class="btn btn-info btn-sm" onClick="document.location.reload();">
        <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
    </button>
</div>

<table id="tabla" class="display" cellspacing="0" width="100%">

    <thead>
        <tr>
            <th>Periodo</th>
            <th>Fecha Generación</th>
            <th>Estado</th>
            <th>Ver</th>
        </tr>
    </thead>

</table>

<div class="col-lg-12 text-center">
    <a href="<?php echo $nav; ?>">
        <button type="button" class="btn btn-primary"> &lt;&lt; volver </button></a>
</div>