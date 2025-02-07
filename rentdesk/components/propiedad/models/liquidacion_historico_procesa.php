
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
@$inicio        = $_POST["start"];
@$num_reg        = $_POST["length"];
@$num_reg_principal        = $_POST["length"];

$draw            = @$_POST["draw"];
$inicio            = @$_POST["start"];
@$fin            = @$_POST["length"];
$busqueda         = @$_POST["search"]["value"];

$cantidad_filtrados = 0;
$cantidad_registros = 0;




$orden         = "";
if (!empty($_POST["order"][0]["column"]))
  $orden         = @$_POST["order"][0]["column"];

$direccion = "";
if (!empty($_POST["order"][0]["dir"]))
  $direccion = @$_POST["order"][0]["dir"];


$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];


$coma = 0;
$signo_coma = "";
$datos        = "";

if ($inicio == "") {
  $inicio = 0;
}
if ($num_reg == "") {
  $num_reg = 99999;
}

$cant_rows = $num_reg;

$fecha_inicio = $_POST["fecha_inicio"];
$fecha_inicio = $fecha_inicio . " 00:00:00";
$fecha_termino = $_POST["fecha_fin"];
$fecha_termino = $fecha_termino . " 23:59:59";

$queryCheques = "SELECT 
    pl.id AS liquidacion_id,
    pl.id_ficha_propiedad,
    pl.id_ficha_arriendo,
    CONCAT(
                vp.direccion, 
                ' #', vp.numero, 
                CASE 
                    WHEN vp.numero_depto IS NOT NULL AND vp.numero_depto <> '' THEN CONCAT(' Dpto ', vp.numero_depto) 
                    ELSE '' 
                END, 
                CASE 
                    WHEN vp.piso IS NOT NULL AND vp.piso <> 0 THEN CONCAT(' Piso ', vp.piso) 
                    ELSE '' 
                END
          ) AS direccion_concat,
    vp.numero,
    pl.fecha_liquidacion,
    pl.url_liquidacion,
    vp.comuna,
    vp.region,
    string_agg(CONCAT_WS(' ', vp_propietarios.nombre_1, vp_propietarios.nombre_2, vp_propietarios.nombre_3), ', ') AS nombres_propietarios
FROM 
    propiedades.propiedad_liquidaciones pl
INNER JOIN 
    propiedades.vis_propiedades vp 
    ON vp.id_propiedad = pl.id_ficha_propiedad
INNER JOIN 
    propiedades.ficha_arriendo fa 
    ON fa.id = pl.id_ficha_arriendo
LEFT JOIN 
    propiedades.propiedad_copropietarios pc 
    ON pc.id_propiedad = pl.id_ficha_propiedad AND pc.nivel_propietario = 1 AND pc.habilitado = true
LEFT JOIN 
    propiedades.vis_propietarios vp_propietarios 
    ON pc.id_propietario = vp_propietarios.id
WHERE 
    pl.fecha_liquidacion BETWEEN '$fecha_inicio' AND '$fecha_termino'
GROUP BY 
   pl.id, vp.direccion, vp.numero, pl.fecha_liquidacion, pl.url_liquidacion, vp.comuna, vp.region, vp.numero_depto, vp.piso
ORDER BY 
    pl.id DESC
";
// }

// var_dump("QUERY HISTORIAL: ", $queryCheques);
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCheques, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objPropLiqGenMasiva = json_decode($resultado);


$coma = 0;
foreach ($objPropLiqGenMasiva as $key => $item) {

  if ($coma == 1)
    $signo_coma = ",";

  $coma = 1;

  $id_liquidacion = $item->liquidacion_id;
  $codigo_propiedad = $item->id_ficha_propiedad;
  $codigo_arriendo = $item->id_ficha_arriendo;
  $propiedad = htmlspecialchars($item->direccion_concat);
  $propietario = htmlspecialchars($item->nombres_propietarios);
  $fecha_liquidacion = $item->fecha_liquidacion;
  $url_liquidacion = $item->url_liquidacion;


  $datos = $datos . "
     $signo_coma
     [
     \"$id_liquidacion\",
      \"$codigo_propiedad\",
      \"$codigo_arriendo\",
      \"$propiedad\",
      \"$propietario\",
      \"$fecha_liquidacion\",
      \"$url_liquidacion\"
    ]";
}



echo "
{
  \"draw\": 0,
  \"recordsTotal\": 0,
  \"recordsFiltered\": 0,
  \"data\": [
    $datos
  ]
}";
