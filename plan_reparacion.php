<?php 
require_once 'model/conexion.php';
if (isset($_POST['cantidad']) && !empty($_POST['cantidad'])) {
    $evaluacion = $_POST['cantidad'];
    $id_ano = $_POST['id_ano'];

    $sql = "update reparacion set cantidad_evaluacion = $evaluacion where id_ano = $id_ano";
    $query = mysqli_query($db, $sql);
    if ($query) {
        $_SESSION['alerta'] = 'Actualizado con exito';
    }else {
        $_SESSION['alerta'] = 'Error';
    }

}else{
    echo 'error';
}
header('location: reparacion.php?id_ano='.$id_ano);