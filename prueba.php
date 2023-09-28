<?php
session_start();
require_once 'model/conexion.php';
var_dump($_POST);


$materia = array();
$materia = $_POST;
$materia_count = count($materia) - 1;
$ano = $_POST['ano'];
$periodo = $_SESSION['periodos']['periodo'];

for ($i=1; $i<=$materia_count; $i++) { 
    $materias = $_POST['materia'.$i];
    $sql = "insert into pensum values(null,'$ano', '$materias', '$periodo')";
    $guardar = mysqli_query($db, $sql);

}

