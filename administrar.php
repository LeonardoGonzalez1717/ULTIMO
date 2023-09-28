<?php 
include_once 'model/conexion.php';
session_start();
if (isset($_POST) && !empty($_POST)) {
    $estudiante =$_POST['estudiante'];
    $ano=$_POST['ano'];  
    $periodo = $_SESSION['periodos']['id'];
    $errores=array();




} 
if (count($errores) == 0) {
    
    $sql = "insert into cursando values(null, '$estudiante', '$ano', '$periodo')";
    $guardar = mysqli_query($db, $sql);
    if (isset($_SESSION['usuario_admin'])) {
        $usuario_name = $_SESSION['usuario_admin']['nombre'];
        $usuario_id = $_SESSION['usuario_admin']['id'];
    }else{
        $usuario_name = $_SESSION['usuario_lector']['nombre'];
        $usuario_id = $_SESSION['usuario_lector']['id'];
    }
    
        
        $movimiento = "El usuario " . $usuario_name . " ha asignado un alumno en un año";
        $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
        $query = mysqli_query($db, $sqli);
        if ($query) {
            if ($guardar == true) {
                $_SESSION['Usuario']['exito'] = 'Alumno registrado con exito';
                header('location:  administrar_E.php');
                exit();
                
            }else {
                $_SESSION['Usuario']['error'] = 'Error al registrar Usuario';
                header('location:  administrar_E.php');
                exit();
            }
        }
}else{
    $_SESSION['alertas'] = $errores;
    header("location: administrar_E.php");
    exit;

}

?>