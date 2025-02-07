<?php
@include("../../includes/sql_inyection.php");

echo "";
//************************************************************************************************************
//proceso para las navegaciones
$token_contrato = @$_GET["token_contrato"];
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=pago&view=pago_list&token_contrato=$token_contrato&nav=$nav");

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=pago&view=pago_list";
}	


//************************************************************************************************************

?>