<?php 
require_once 'model/conexion.php';
session_start();
if (isset($_POST)) {
$periodo = $_SESSION['periodos']['id'];
$periodo_new = $periodo + 1;
$id_ano = $_POST['ano'];
$id_ano_new = $id_ano + 3;
$cuenta = $_POST['cuenta'];

    $id_alumno = $_POST['alumno'];
    $slq_reparacion = "select * from alumno where id = $id_alumno";
    $query_reparacion = mysqli_query($db, $slq_reparacion);
    $querys_reparacion = mysqli_fetch_assoc($query_reparacion);


    $verify = "select * from cursando where id_alumno = $id_alumno and id_periodo = $periodo_new";
    $query = mysqli_query($db, $verify);
    if (mysqli_num_rows($query) > 0) {
        $_SESSION['guardado'] = 'Estudiante ya estudia en el siguiente periodo';
    }else{

        for ($i=1; $i<=$cuenta; $i++) { 
            $count_materia = array();
            $nota = $_POST['nota'.$i];
            $materia = $_POST['materia'.$i];
            if ($nota >= 10) {
            
                $count_materia[] = $nota;
            $count[] = count($count_materia);
            $cuneta = count($count);
            if ($cuneta == $cuenta) {
                
                $sql = "insert into cursando values(null, $id_alumno, $id_ano_new, '$periodo_new')";
                $guardar = mysqli_query($db, $sql);

                if (isset($_SESSION['usuario_admin'])) {
                    $usuario_name = $_SESSION['usuario_admin']['nombre'];
                    $usuario_id = $_SESSION['usuario_admin']['id'];
                }
        
                $movimiento = "El usuario " . $usuario_name . " ha aprobado al estudiante ".$querys_reparacion['nombre'].' '.$querys_reparacion['apellido'];
                $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
                $query = mysqli_query($db, $sqli);
                if ($query) {
                    
                    if ($guardar) {
                        $_SESSION['guardado'] = 'El estudiante ha aprobado con exito, estudiara el siguiente año';
                    }else{
                        $_SESSION['guardado'] = 'Periodo no encontrado';
                        
                    }
                }
            }else{
               
               
            }
        }else{
            
            $sql = "insert into cursando values(null, $id_alumno, $id_ano, '$periodo_new')";
            $guardar = mysqli_query($db, $sql);


            if (isset($_SESSION['usuario_admin'])) {
                $usuario_name = $_SESSION['usuario_admin']['nombre'];
                $usuario_id = $_SESSION['usuario_admin']['id'];
            }
    
            $movimiento = "El usuario " . $usuario_name . " ha mandado al estudiante " . $querys_reparacion['nombre'].' '.$querys_reparacion['apellido'] . " repetir el periodo";
            $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
            $query = mysqli_query($db, $sqli);
            if ($query) {
                
                if ($guardar) {
                    $_SESSION['guardado'] = 'El estudiante NO ha aprobado con exito, repetira el año';
                    
                }
            }
        }
    }
}
}
header('location: reparacion_view.php'); 


?>