<?php 



if (isset($_POST['fechaInicio']) && isset($_POST['fechaTermino'])) {
$fecha_termino = $_POST['fechaTermino'];

// Fecha de una semana antes
$fecha_inicio = $_POST['fechaInicio'];

}
else{
// Fecha de hoy
$fecha_termino = date('Y-m-d');

// Fecha de una semana antes
$fecha_inicio = date('Y-m-d', strtotime('-1 week'));


}
?>