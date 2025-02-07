<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");
 

$empresa		= $_POST['empresa'];
$sucursal		= $_POST['sucursal'];
$token			= $_POST['token'];

if($empresa!="" && $sucursal !="" && token !=""){
	
//Aquí el delete y sus comprobaciones

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
	
}//if($empresa!="" && $sucursal !="" && token !="")


?>