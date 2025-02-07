<?php
@include("../../includes/sql_inyection.php");

echo "";
//************************************************************************************************************
//proceso para las navegaciones
$token_contrato = @$_GET["token_contrato"];
$token = @$_GET["token"];
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=liquidacion&view=liquidacion_list&token_contrato=$token_contrato&token=$token&nav=$nav");

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=liquidacion&view=liquidacion_list";
}	


//************************************************************************************************************

?>