<?php
session_start();
include("../../../includes/sql_inyection_salto_textarea.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id = @$_POST["id"];
//var_dump($id);
$id = @$_GET["id"];
//var_dump($id);


$num_reg = 50;
$inicio = 0;

/* Codeudor no tiene cuentas bancarias
       
       $delete= "   DELETE FROM propiedades.arrendatario_ctas_bancarias where id_arrendatario = $id ";
       
      $dataCab = array("consulta" => $delete);
       $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	   
	   if ($resultadoCab != "OK"){
		   echo ",xxx,ERROR,xxx,Problemas al borrar arrendatario,xxx,-,xxx,";
		   return; 
	   } 
	*/   
	  $delete= "   DELETE FROM propiedades.persona_codeudor where id_persona = $id ";
       
      $dataCab = array("consulta" => $delete);
       $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	   //var_dump($resultadoCab);
	   if ($resultadoCab != "OK"){
		   echo ",xxx,ERROR,xxx,Problemas al borrar arrendatario ,xxx,-,xxx,";
		   return ;
		   
	   }
	   
	   $delete= " DELETE FROM propiedades.propietario_ctas_bancarias where id_propietario = $id ";
       
      $dataCab = array("consulta" => $delete);
       $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	   
	   if ($resultadoCab != "OK"){
		   echo ",xxx,ERROR,xxx,Problemas al borrar arrendatario,xxx,-,xxx,";
		   return; 
	   } 
	   
	  $delete= "   DELETE FROM propiedades.persona_propietario where id_persona = $id ";
       
      $dataCab = array("consulta" => $delete);
       $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	   
	   if ($resultadoCab != "OK"){
		   echo ",xxx,ERROR,xxx,Problemas al borrar arrendatario ,xxx,-,xxx,";
		   return ;
		   
	   }

       $delete= "   DELETE FROM propiedades.arrendatario_ctas_bancarias where id_arrendatario = $id ";
       
      $dataCab = array("consulta" => $delete);
       $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	   
	   if ($resultadoCab != "OK"){
		   echo ",xxx,ERROR,xxx,Problemas al borrar arrendatario,xxx,-,xxx,";
		   return; 
	   } 
	   
	  $delete= "   DELETE FROM propiedades.persona_arrendatario where id_persona = $id ";
       
      $dataCab = array("consulta" => $delete);
       $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	   
	   if ($resultadoCab != "OK"){
		   echo ",xxx,ERROR,xxx,Problemas al borrar arrendatario ,xxx,-,xxx,";
		   return ;
		   
	   }
	   
	   	  $delete= "   DELETE FROM propiedades.persona_direcciones where id_persona = $id ";
       
      $dataCab = array("consulta" => $delete);
       $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	   
	   if ($resultadoCab != "OK"){
		   echo ",xxx,ERROR,xxx,Problemas al borrar cliente direcciones ,xxx,-,xxx,";
		   return ;
		   
	   }
	   
	   $delete= "   DELETE FROM propiedades.persona_juridica where id_persona = $id ";
       
      $dataCab = array("consulta" => $delete);
       $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	   
	   if ($resultadoCab != "OK"){
		   echo ",xxx,ERROR,xxx,Problemas al borrar cliente juridica,xxx,-,xxx,";
		   return ;
		   
	   }
	   
	   $delete= "   DELETE FROM propiedades.persona_natural where id_persona = $id ";
       
      $dataCab = array("consulta" => $delete);
       $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	   
	   if ($resultadoCab != "OK"){
		   echo ",xxx,ERROR,xxx,Problemas al borrar cliente natural ,xxx,-,xxx,";
		   return ;
		   
	   }

	  $delete= "   DELETE FROM propiedades.persona where id = $id ";
       
      $dataCab = array("consulta" => $delete);
       $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	   
	   if ($resultadoCab != "OK"){
		   echo ",xxx,ERROR,xxx,Problemas al borrar cliente ,xxx,-,xxx,";
		   return ;
		   
	   }

 echo ",xxx,OK,xxx,Cliente eliminado,xxx,-,xxx,";