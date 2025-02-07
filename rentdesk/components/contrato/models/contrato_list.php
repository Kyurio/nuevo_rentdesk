<?php
@include("../../includes/sql_inyection.php");

echo "";
//************************************************************************************************************
//proceso para las navegaciones
$token_propiedad = @$_GET["token_propiedad"];
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=contrato&view=contrato_list&token_propiedad=$token_propiedad&nav=$nav");

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=contrato&view=contrato_list";
}	


//************************************************************************************************************

?>